<?php
/**
 * Created by PhpStorm.
 * User: too
 * Date: 2016/10/19
 * Time: 14:58
 * @author 涂鸿 <hayto@foxmail.com>
 */
namespace api\controllers;

use api\components\CustomApiException;
use api\models\OrdersHelper;
use common\models\CalInterest;
use common\models\Customer;
use common\models\OrderImages;
use common\models\Orders;
use common\models\Product;
use common\models\Stores;
use common\models\TooRegion;
use common\models\UploadFile;
use Qiniu\Processing\ImageUrlBuilder;
use yii;
use api\core\CoreApiController;

class OrderController extends CoreApiController
{
    /**
     * 初始化订单数据
     * @return string
     */
    public function actionInitialize()
    {
        try{
            $userinfo = Yii::$app->getUser()->getIdentity();
            // 可选商户 同一个区县
            $stores = (new yii\db\Query())->select(['s_id', 's_name'])
                ->from(Stores::tableName())->where(['s_status'=>Stores::STATUS_ACTIVE, 's_county'=>$userinfo->county])->all();
            // 商品类型
            $goods_type = Yii::$app->params['goods_type'];

            // 可选产品
            $select = ['p_id', 'p_name', 'p_month_rate', 'p_period', 'p_add_service_fee', 'p_free_pack_fee', 'p_finance_mangemant_fee', 'p_customer_management'];
            $products = (new yii\db\Query())->select($select)
                ->from(Product::tableName())->where(['p_status'=>Product::STATUS_OK])->all();

            // 暗号
            $secret_code = Yii::$app->params['secret_code'];

            // 省
            $provinces = (new yii\db\Query())->from(TooRegion::tableName())->where(['parent_id'=>1])->all();

            $marital_status = Yii::$app->params['marital_status'];
            $bank_list = Yii::$app->params['bank_list'];

            $house_info = Yii::$app->params['house_info'];
            $kinship = Yii::$app->params['kinship'];
            $company_type = Yii::$app->params['company_type'];
            $company_kind = Yii::$app->params['company_kind'];

            $data = [
                'stores'=>$stores,
                'goods_type'=>$goods_type,
                'products'=>$products,
                'secret_code'=>$secret_code,
                'provinces'=>$provinces,
                'marital_status'=>$marital_status,
                'bank_list'=>$bank_list,
                'house_info'=>$house_info,
                'kinship'=>$kinship,
                'company_kind'=>$company_kind,
                'company_type'=>$company_type,
            ];
            return ['status'=>1, 'message'=>'获取成功', 'data'=>$data];
        }catch(CustomApiException $e){
            return ['status'=>0, 'message'=>'获取失败', 'data'=>[]];
        }catch (yii\base\Exception $e){
            return ['status'=>0, 'message'=>'获取失败_sys', 'data'=>[]];
        }
    }

    /**
     * 提交订单
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionAddOrders()
    {
        $model = new OrdersHelper();
        try{
            return $model->placeOrders(\Yii::$app->getRequest()->post());
        }catch(CustomApiException $e){
            return ['status'=>0, 'message'=>$e->getMessage()];
        }catch(yii\base\Exception $e){
            return ['status'=>0, 'message'=>'系统错误_sys'];
        }
    }

    /**
     * 待上传图片的订单
     * @return array
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionWaitUploadImages()
    {
        $where = ['and',
            ['=', 'o_status', Orders::STATUS_NOT_COMPLETE], // 不完整的叮当
            ['o_user_id'=>Yii::$app->getUser()->getIdentity()->getId()], // 只读当前登录用户的
        ];
        try{
            $query = (new yii\db\Query())->select(['o_serial_id', 'o_id', 'o_created_at', 'o_status', 'c_customer_name', 'o_operator_remark'])
                ->from(Orders::tableName())->leftJoin(Customer::tableName(), 'o_customer_id=c_id')->where($where);
            $count_query = clone $query;
            $total_count = $count_query->count();
            $pages = new yii\data\Pagination(['totalCount'=>$total_count]);
            $pages->pageSize = Yii::$app->params['page_size'];
            $data = $query->orderBy(['o_created_at'=>SORT_DESC])->offset($pages->offset)->limit($pages->limit)->all();
            array_walk($data, function(&$v){
                $v['o_status'] = Orders::getAllStatus()[$v['o_status']];
            });
            return ['status'=>1, 'message'=>'获取成功', 'data'=>$data];
        }catch(yii\base\Exception $e){
            return ['status'=>0, 'message'=>'获取失败_sys', 'data'=>[]];
        }
    }

    /**
     * 提交图片
     * @param type图片类型 pic图片文件 oid图片所属订单
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionUploadPic()
    {
        $type = Yii::$app->getRequest()->post('type');
        $oid = Yii::$app->getRequest()->post('oid');
        $key = Yii::$app->getRequest()->post('key');
//        $pic = yii\web\UploadedFile::getInstanceByName('pic'); // 获取图片
        $model = new UploadFile();
        $model->scenario = 'upload';
        $model->key=$key;
        $model->type = $type;
        $model->oid = $oid;
        // 入库
        $userinfo = Yii::$app->getUser()->getIdentity();
        try {
            if ($order_image_model = OrderImages::find()->leftJoin(Orders::tableName(), 'oi_id=o_images_id')->where(['o_id' => $oid, 'oi_user_id' => $userinfo->getId()])->one()) {
                if (!$model->validate()) {
                    $msg = $model->getFirstErrors();
                    throw new CustomApiException(reset($msg));
                }
//            $key = $model->upload();
                $order_image_model->$type = $key;
                $order_image_model->save(false);
                $url = $model->getPicUrl($key);
                $data = ['key' => $key, 'url' => $url];
                return ['status' => 1, 'message' => 'ok', 'data' => $data];
            }
            throw new CustomApiException('无效订单');
        }catch (CustomApiException $e){
            p($e->getMessage());
            return ['status' => 0, 'message' => '上传失败', 'data' => []];
        }catch (yii\base\Exception $e){
            return ['status' => 0, 'message' => '系统错误', 'data' => []];
        }
        echo 'qiguaidehen';
    }

    /**
     * 获取已上传的图片
     * 返回token给客户端
     *
     * 只获取 当前登录用户 且 订单状态为不完整的
     *
     * @return array
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionGetPics()
    {
        $oid = Yii::$app->getRequest()->get('oid');
        $select = ['oi_front_id','oi_back_id','oi_customer','oi_front_bank','oi_back_bank','oi_family_card_one','oi_family_card_two',
            'oi_driving_license_one','oi_driving_license_two', 'oi_video'];
        $data = (new yii\db\Query())->select($select)->from(Orders::tableName())->leftJoin(OrderImages::tableName(), 'o_images_id=oi_id')
            ->where(['o_id'=>$oid, 'o_status'=>Orders::STATUS_NOT_COMPLETE, 'o_user_id'=>Yii::$app->getUser()->getIdentity()->getId()])->one();


$data = ['Fkk1uWoMjh_zwbbeqZ26gaF5yy1j'];
        if($data){
            $model = new UploadFile();
            foreach($data as $k=>$v){
                $url = '';
                if(!empty($v)){
                    $url = $model->getUrl($v);
                }
                $data1[]=['type'=>$k,'url'=>$url, 'key'=>$v];
            }

            return ['status'=>1, 'message'=>'ok', 'data'=>$data1];
        }
//        $data1['token']= $token;
        return ['status'=>0, 'message'=>'无数据', 'data'=>[]];
    }

    /**
     * 客户端获取七牛上传token
     * @return array
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionGetQntoken()
    {
        $model = new UploadFile();
        $token = $model->genToken();
        return ['status'=>1, 'message'=>'ok', 'data'=>$token];
    }

    /**
     * 删除图片
     *
     * 只删除 当前登录用户 且 订单状态为不完整的
     *
     * @param key图片标示 oid订单id type图片类型
     * @return array
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionDelePic()
    {
        $request = Yii::$app->getRequest();
        $userinfo = Yii::$app->getUser()->getIdentity();
        $key = $request->post('key');
        $oid = $request->post('oid');
        $type = $request->post('type');
        $model = new UploadFile();
        $model->scenario = 'delete';
        try{
            $order_status = (new yii\db\Query())->from(Orders::tableName())->select(['o_status'])->where(['o_id'=>$oid, 'o_user_id'=>$userinfo->getId()])->scalar();
            // 只能删订单状态是 不完整 的
            if($order_status != Orders::STATUS_NOT_COMPLETE){
                throw new \Exception('删除失败');
            }
//            if($order_image_model = OrderImages::findOne(['oi_id'=>$oid, 'oi_user_id'=>$userinfo->getId()])){
            if($order_image_model = OrderImages::find()->leftJoin(Orders::tableName(), 'oi_id=o_images_id')->where(['o_id'=>$oid, 'oi_user_id'=>$userinfo->getId()])->one()){
                $model->delePic($key); // 删除七牛上的
                $order_image_model->$type = ''; // 情况数据库字段
                if($order_image_model->save(false) === false){
                    throw new \Exception('删除失败');
                }
                return ['status'=>1, 'message'=>'删除成功', 'data'=>[]];
            }
            return ['status'=>0, 'message'=>'图片不存在', 'data'=>[]];
        }catch(yii\base\Exception $e){
            return ['status'=>0, 'message'=>'删除失败_sys', 'data'=>[]];
        }
    }

    /**
     * 确认上传图片,把订单改为待审核状态
     *
     * 先锁定订单,再获取图片的key,判断是否齐全.齐全就保存,最后修改订单状态
     *
     * @return array
     * @throws yii\db\Exception
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionConfirmOrderImageComplete()
    {
        $user_id = Yii::$app->getUser()->getIdentity()->getId();
        $oid = Yii::$app->getRequest()->post('oid');

        $trans = Yii::$app->db->beginTransaction();
        try{
            $model = Orders::findBySql("select * from ".Orders::tableName(). " where o_id=:oid and o_user_id=".$user_id." limit 1 for update", [':oid'=>$oid])->one();
            if(!$model){
                throw new CustomApiException('无效订单');
            }
            if($oi_model = OrderImages::findBySql("select * from ".OrderImages::tableName(). " where oi_id=:o_images_id and oi_user_id=".$user_id. " limit 1 for update", [':o_images_id'=>$model->o_images_id])->one()) {
                if (!$oi_model->validate()) {
                    $msg = $oi_model->getFirstErrors();
                    throw new CustomApiException(reset($msg));
                }
                $model->o_status = Orders::STATUS_WAIT_CHECK;
                if (!$model->save(false)) {
                    throw new CustomApiException('上传失败2');
                }
                $trans->commit();
                return ['status' => 1, 'message' => '上传成功', 'data' => []];
            }
            throw new CustomApiException('上传失败5');
        }catch(CustomApiException $e)
        {
            $trans->rollBack();
            return ['status'=>0,'message'=>$e->getMessage(), 'data'=>[]];
        }catch(yii\base\Exception $e){
            $trans->rollBack();
            return ['status'=>0,'message'=>'上传失败4', 'data'=>[]];
        }
    }




    /**
     * 获取消息 审核后的返回内容
     *
     * 字段:订单创建时间,订单状态,客户姓名,流水号,审核信息
     *
     * 获取最近24小时的订单列表,不限制状态
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionGetLatest()
    {
        $limit_time = ($_SERVER['REQUEST_TIME']-24*3600);
        $limit_time = 0;
        $where = ['and',
            ['>=', 'o_created_at', $limit_time], // 最近24小时
            ['o_user_id'=>Yii::$app->getUser()->getIdentity()->getId()], // 只读当前登录用户的
        ];
        try{
            $query = (new yii\db\Query())->select(['o_serial_id', 'o_id','p_name', 'o_total_price', 'o_total_deposit', 'o_created_at', 'o_status', 'c_customer_name', 'o_operator_remark'])
                ->from(Orders::tableName())
                ->leftJoin(Customer::tableName(), 'o_customer_id=c_id')
                ->leftJoin(Product::tableName(), 'o_product_id=p_id')
                ->where($where);
            $count_query = clone $query;
            $total_count = $count_query->count();
            $pages = new yii\data\Pagination(['totalCount'=>$total_count]);
            $pages->pageSize = Yii::$app->params['page_size'];
            $data = $query->orderBy(['o_created_at'=>SORT_DESC])->offset($pages->offset)->limit($pages->limit)->all();
            array_walk($data, function(&$v){
//                $v['o_created_at'] = date('Y-m-d H:i:s', $v['o_created_at']);
                $v['o_total_price'] += 0;
                $v['o_total_deposit'] += 0;
                $v['o_total_borrow_money'] = $v['o_total_price']- $v['o_total_deposit'];
                $v['o_status'] = Orders::getAllStatus()[$v['o_status']];
            });
            return ['status'=>1, 'message'=>'获取成功', 'data'=>$data];
        }catch(yii\base\Exception $e){
            return ['status'=>0, 'message'=>'获取失败_sys', 'data'=>[]];
        }
    }

    /**
     * 获取当前登录用户的历史订单
     *
     * 搜索条件:订单,客户姓名,门店,状态,
     *
     * 展示字段:订单编号,门店,客户姓名,身份证号码,产品名称,每月还款,提单时间
     *
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionGetOrdersLists()
    {
        $limit_time = ($_SERVER['REQUEST_TIME']-24*3600);
        $limit_time = 0;
        $where = ['and',
            ['>=', 'o_created_at', $limit_time], // 最近24小时
            ['o_user_id'=>Yii::$app->getUser()->getIdentity()->getId()], // 只读当前登录用户的
        ];
        try{
            $query = (new yii\db\Query())->select(['o_serial_id', 'o_id','p_name', 'o_total_price', 'o_total_deposit', 'o_created_at', 'o_status', 'c_customer_name', 'o_operator_remark'])
                ->from(Orders::tableName())
                ->leftJoin(Customer::tableName(), 'o_customer_id=c_id')
                ->leftJoin(Product::tableName(), 'o_product_id=p_id')
                ->where($where);
            $count_query = clone $query;
            $total_count = $count_query->count();
            $pages = new yii\data\Pagination(['totalCount'=>$total_count]);
            $pages->pageSize = Yii::$app->params['page_size'];
            $data = $query->orderBy(['o_created_at'=>SORT_DESC])->offset($pages->offset)->limit($pages->limit)->all();
            array_walk($data, function(&$v){
//                $v['o_created_at'] = date('Y-m-d H:i:s', $v['o_created_at']);
                $v['o_total_price'] += 0;
                $v['o_total_deposit'] += 0;
                $v['o_total_borrow_money'] = $v['o_total_price']- $v['o_total_deposit'];
                $v['o_status'] = Orders::getAllStatus()[$v['o_status']];
            });
            return ['status'=>1, 'message'=>'获取成功', 'data'=>$data];
        }catch(yii\base\Exception $e){
            return ['status'=>0, 'message'=>'获取失败_sys', 'data'=>[]];
        }
    }

    /**
     * 获取子地区们
     * @param $pid 父地区id
     * @return false|null|string
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionGetSubAddr($pid)
    {
//        Yii::$app->getResponse()->format = 'json';
        if($data = (new yii\db\Query())->from(TooRegion::tableName())->where(['parent_id'=>$pid])->all())
        {
            return ['status'=>1, 'message'=>'success', 'data'=>$data];
        }else{
            return ['status'=>0, 'message'=>'error', 'data'=>[]];
        }
    }

    public function actionDebx()
    {
        CalInterest::debx();
    }

    /**
     * 理论上：总借款额度，使用的产品id
     *
     *
     * 返回安卓端月还款额
     * @param $total_money 总共借多少钱
     * @param $total_months 借多少个月
     * @param $rate_month 月利率
     * @return float
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionGetRepayment()
    {
        $get = Yii::$app->getRequest();
        $total_money = $get->get('total_money'); // 借款额
        $product_id = $get->get('product_id'); // 使用的产品
        $p_add_service_fee = $get->get('p_add_service_fee'); // 增值服务费
        $p_free_pack_fee = $get->get('p_free_pack_fee'); // 随心包服务费

        Yii::error('debug一下');

        try{
            if(!$total_money || !$product_id){
                throw new CustomApiException('请求错误');
            }
            $select = ['p_period', 'p_month_rate', 'p_add_service_fee', 'p_free_pack_fee', 'p_finance_mangemant_fee', 'p_customer_management'];
            if(!$data = Product::find()->select($select)->where(['p_id'=>$product_id])->asArray()->one()){
                throw new CustomApiException('请求错误');
            }
            $every_month_repay = CalInterest::calEveryMonth($total_money, $data['p_period'], $data['p_month_rate']);
            if($p_add_service_fee == 1){
                $every_month_repay += $data['p_add_service_fee'];
            }
            if($p_free_pack_fee == 1){
                $every_month_repay += $data['p_free_pack_fee'];
            }
            $res = [];
            for($i=0; $i<$data['p_period']; $i++){
                $res[$i]['time'] = strtotime('+'. $i+1 . 'months'); // 下个月的明天
                $res[$i]['money'] = round($every_month_repay, 2);
            }
            return ['status'=>1, 'message'=>'success', 'data'=>$res];
        }catch(CustomApiException $e){
            return ['status'=>0, 'message'=>$e->getMessage(), 'data'=>[]];
        }catch(yii\base\ErrorException $e){
            return ['status'=>0, 'message'=>'系统错误_sys', 'data'=>[]];
        }
    }

    /**
     * 调用接口 身份证实名认证
     * @param $idno
     * @param $name
     * @return array
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionVerifyIdno($idno, $name)
    {
        if(1==1){
            return ['status'=>1, 'message'=>'success'];
        }
        return ['status'=>0, 'message'=>'身份证号码不正确'];
    }

    /**
     * 获取当前登录用户一个县的商铺
     * @author 涂鸿 <hayto@foxmail.com>
     */
    private function getStores()
    {

    }
}