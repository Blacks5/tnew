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
use common\components\Helper;
use common\components\Contract;
use common\models\CalInterest;
use common\models\Customer;
use common\models\Goods;
use common\models\OrderImages;
use common\models\Orders;
use common\models\Product;
use common\models\Repayment;
use common\models\Sms;
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
        try {
            $userinfo = Yii::$app->getUser()->getIdentity();
            // 可选商户 同一个区县
            $stores = (new yii\db\Query())->select(['s_id', 's_name'])
                ->from(Stores::tableName())->where(['s_status' => Stores::STATUS_ACTIVE, 's_county' => $userinfo->county])->all();
            // 商品类型
            $goods_type = Yii::$app->params['goods_type'];

            // 可选产品
            $select = ['p_id', 'p_name', 'p_month_rate', 'p_period', 'p_add_service_fee', 'p_free_pack_fee', 'p_finance_mangemant_fee', 'p_customer_management'];
            $products = (new yii\db\Query())->select($select)
                ->from(Product::tableName())->where(['p_status' => Product::STATUS_OK])->all();

            // 暗号
            $secret_code = Yii::$app->params['secret_code'];

            // 省
            $provinces = (new yii\db\Query())->from(TooRegion::tableName())->where(['parent_id' => 1])->all();

            $marital_status = Yii::$app->params['marital_status'];
            $bank_list = Yii::$app->params['bank_list'];

            $house_info = Yii::$app->params['house_info'];
            $kinship = Yii::$app->params['kinship'];
            $company_type = Yii::$app->params['company_type'];
            $company_kind = Yii::$app->params['company_kind'];

            $data = [
                'stores' => $stores,
                'goods_type' => $goods_type,
                'products' => $products,
                'secret_code' => $secret_code,
                'provinces' => $provinces,
                'marital_status' => $marital_status,
                'bank_list' => $bank_list,
                'house_info' => $house_info,
                'kinship' => $kinship,
                'company_kind' => $company_kind,
                'company_type' => $company_type,
            ];
            return ['status' => 1, 'message' => '获取成功', 'data' => $data];
        } catch (CustomApiException $e) {
            return ['status' => 0, 'message' => '获取失败', 'data' => []];
        } catch (yii\base\Exception $e) {
            return ['status' => 0, 'message' => '获取失败_sys', 'data' => []];
        }
    }

    /**
     * 提交订单
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionAddOrders()
    {
        $model = new OrdersHelper();
        try {
            return $model->placeOrders(\Yii::$app->getRequest()->post());
        } catch (CustomApiException $e) {
            return ['status' => 0, 'message' => $e->getMessage()];
        } catch (yii\base\Exception $e) {
            return ['status' => 0, 'message' => '系统错误_sys'];
        }
    }

    /**
     * 待上传图片的订单列表
     * 包括订单状态2(待初审)和6(待复审)的
     * @return array
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionWaitUploadImages()
    {
        $where = ['and',
            ['o_status' => [Orders::STATUS_NOT_COMPLETE, Orders::STATUS_WAIT_CHECK_AGAIN]], // 不完整的订单
            ['o_user_id' => Yii::$app->getUser()->getIdentity()->getId()], // 只读当前登录用户的
        ];
        try {
            $query = (new yii\db\Query())->select(['o_serial_id', 'o_id', 'o_created_at', 'o_status', 'c_customer_name', 'o_operator_remark'])
                ->from(Orders::tableName())->leftJoin(Customer::tableName(), 'o_customer_id=c_id')
                ->where($where);
            $count_query = clone $query;
            $total_count = $count_query->count();
            $pages = new yii\data\Pagination(['totalCount' => $total_count]);
            $pages->pageSize = Yii::$app->params['page_size'];
            $data = $query->orderBy(['o_created_at' => SORT_DESC])->offset($pages->offset)->limit($pages->limit)->all();
            array_walk($data, function (&$v) {
                $v['o_status'] = Orders::getAllStatus()[$v['o_status']];
            });
            return ['status' => 1, 'message' => '获取成功', 'data' => $data];
        } catch (yii\base\Exception $e) {
            return ['status' => 0, 'message' => '获取失败_sys', 'data' => []];
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
        $model->key = $key;
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
                Yii::error('print_photo');
//            $key = $model->upload();
                $order_image_model->$type = $key;
                $order_image_model->save(false);
                $url = $model->getUrl($key);
                $data = ['key' => $key, 'url' => $url];
                return ['status' => 1, 'message' => 'ok', 'data' => $data];
            }
            throw new CustomApiException('无效订单');
        } catch (CustomApiException $e) {
            return ['status' => 0, 'message' => '上传失败', 'data' => []];
        } catch (yii\base\Exception $e) {
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
        $select = ['o_id', 'c_customer_cellphone', 'o_status', 'oi_front_id', 'oi_back_id', 'oi_customer', 'oi_front_bank'/*,'oi_back_bank'*/, 'oi_family_card_one', 'oi_family_card_two', 'oi_after_contract',
            'oi_driving_license_one', 'oi_driving_license_two', 'oi_video', 'oi_pick_goods', 'oi_serial_num'];
        $data = (new yii\db\Query())->select($select)
            ->from(Orders::tableName())
            ->leftJoin(OrderImages::tableName(), 'o_images_id=oi_id')
            ->leftJoin(Customer::tableName(), 'o_customer_id=c_id')
            ->where(['o_id' => $oid, 'o_user_id' => Yii::$app->getUser()->getIdentity()->getId()])
            ->andWhere(['o_status'=>[Orders::STATUS_NOT_COMPLETE, Orders::STATUS_WAIT_CHECK_AGAIN]])
            ->one();

        if ($data) {
            $model = new UploadFile();
            foreach ($data as $k => $v) {
                $url = '';
                if (!empty($v)) {
                    $url = $model->getUrl($v);
                }
                $data1[] = ['type' => $k, 'url' => $url, 'key' => $v];
            }
            $res['data'] = $data1;
            $res['order_status'] = $data['o_status']; // 订单状态，方便客户端知道该验证哪个阶段的比传图片
            $res['c_customer_cellphone'] = $data['c_customer_cellphone']; // 客户手机号码，发验证码用
            return ['status' => 1, 'message' => 'ok', 'data' => $res];
        }
        return ['status' => 0, 'message' => '无数据', 'data' => []];
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
        return ['status' => 1, 'message' => 'ok', 'data' => $token];
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
        try {
            $order_status = (new yii\db\Query())->from(Orders::tableName())->select(['o_status'])->where(['o_id' => $oid, 'o_user_id' => $userinfo->getId()])->scalar();
            // 只能删订单状态是 不完整 的
            if (($order_status != Orders::STATUS_NOT_COMPLETE) && ($order_status != Orders::STATUS_WAIT_CHECK_AGAIN)) {
                throw new CustomApiException('该订单状态不允许删除图片');
            }
//            if($order_image_model = OrderImages::findOne(['oi_id'=>$oid, 'oi_user_id'=>$userinfo->getId()])){
            if ($order_image_model = OrderImages::find()->leftJoin(Orders::tableName(), 'oi_id=o_images_id')->where(['o_id' => $oid, 'oi_user_id' => $userinfo->getId()])->one()) {
                $model->delFile($key); // 删除七牛上的
                $order_image_model->$type = ''; // 清空数据库字段
                if ($order_image_model->save(false) === false) {
                    throw new \Exception('删除失败');
                }
                return ['status' => 1, 'message' => '删除成功', 'data' => []];
            }
            return ['status' => 0, 'message' => '图片不存在', 'data' => []];
        } catch (CustomApiException $e){
            return ['status' => 0, 'message' => $e->getMessage(), 'data' => []];
        } catch (yii\base\Exception $e) {
            return ['status' => 0, 'message' => '删除失败_sys', 'data' => []];
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
        $c_customer_cellphone = Yii::$app->getRequest()->post('c_customer_cellphone'); // 手机号
        $verify_code = Yii::$app->getRequest()->post('verify_code'); // 验证码
        $order_status = Yii::$app->getRequest()->post('order_status'); // 订单状态

        $trans = Yii::$app->db->beginTransaction();
        try {
            $model = Orders::findBySql("select * from " . Orders::tableName() . " where o_id=:oid and o_user_id=" . $user_id . " limit 1 for update", [':oid' => $oid])->one();
            if (!$model) {
                throw new CustomApiException('无效订单');
            }
            if ($oi_model = OrderImages::findBySql("select * from " . OrderImages::tableName() . " where oi_id=:o_images_id and oi_user_id=" . $user_id . " limit 1 for update", [':o_images_id' => $model->o_images_id])->one()) {
                if (!$oi_model->validate()) {
                    $msg = $oi_model->getFirstErrors();
                    throw new CustomApiException(reset($msg));
                }

                // 如果是初审通过，订单状态是6， 就需要'oi_video', 'oi_after_contract' 都必须上传了
                if ($model->o_status == Orders::STATUS_WAIT_CHECK_AGAIN) {
                    if (!empty($oi_model->oi_pick_goods) === false) {
                        throw new CustomApiException('请上传提货照');
                    }
                    if (!empty($oi_model->oi_serial_num) === false) {
                        throw new CustomApiException('请上传串码照');
                    }
                    if (!empty($oi_model->oi_after_contract) === false) {
                        throw new CustomApiException('请上传合同照片');
                    }
                }else{
                    $model->o_status = Orders::STATUS_WAIT_CHECK;
                }


                if (!$model->save(false)) {
                    throw new CustomApiException('上传失败2');
                }
                $verify = new Sms();
                if (!$verify->verify($c_customer_cellphone, $verify_code)) {
                    throw new CustomApiException('验证码错误5');
                }
                $trans->commit();
                return ['status' => 1, 'message' => '上传成功', 'data' => []];
            }
            throw new CustomApiException('上传失败5');
        } catch (CustomApiException $e) {
            $trans->rollBack();
            return ['status' => 0, 'message' => $e->getMessage(), 'data' => []];
        } catch (yii\base\Exception $e) {
            $trans->rollBack();
            return ['status' => 0, 'message' => '上传失败4', 'data' => []];
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
        $limit_time = ($_SERVER['REQUEST_TIME'] - 24 * 3600);
//        $limit_time = 0;
        $where = ['and',
            ['>=', 'o_created_at', $limit_time], // 最近24小时
            ['o_user_id' => Yii::$app->getUser()->getIdentity()->getId()], // 只读当前登录用户的
        ];
        try {
            $query = (new yii\db\Query())->select(['o_serial_id', 'o_id', 'p_name', 'p_type', 'o_total_price', 'o_total_deposit', 'o_created_at', 'o_status', 'c_customer_name', 'c_customer_cellphone', 'o_operator_remark', 's_name'])
                ->from(Orders::tableName())
                ->leftJoin(Customer::tableName(), 'o_customer_id=c_id')
                ->leftJoin(Product::tableName(), 'o_product_id=p_id')
                ->leftJoin(Stores::tableName(), 'o_store_id=s_id')
                ->where($where);
            $count_query = clone $query;
            $total_count = $count_query->count();
            $pages = new yii\data\Pagination(['totalCount' => $total_count]);
            $pages->pageSize = Yii::$app->params['page_size']; // common里的
            $data = $query->orderBy(['o_created_at' => SORT_DESC])->offset($pages->offset)->limit($pages->limit)->all();
            array_walk($data, function (&$v) {
//                $v['o_created_at'] = date('Y-m-d H:i:s', $v['o_created_at']);
                $v['o_total_price'] += 0;
                $v['o_total_deposit'] += 0;
                $v['o_total_borrow_money'] = $v['o_total_price'] - $v['o_total_deposit'];
                $v['o_status'] = Orders::getAllStatus()[$v['o_status']];
                $v['p_type'] = Yii::$app->params['goods_type'][$v['p_type']-1]['t_name'];
                // 不显示以下数据
                unset($v['o_total_price']);
            });
            return ['status' => 1, 'message' => '获取成功', 'data' => $data];
        } catch (yii\base\Exception $e) {
            return ['status' => 0, 'message' => '获取失败_sys', 'data' => []];
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
//        $limit_time = ($_SERVER['REQUEST_TIME'] - 24 * 3600);
//        $limit_time = 0;
        $where = ['and',
//            ['>=', 'o_created_at', $limit_time], // 最近24小时
            ['o_user_id' => Yii::$app->getUser()->getIdentity()->getId()], // 只读当前登录用户的
        ];
        $where = ['o_user_id' => Yii::$app->getUser()->getIdentity()->getId()];
        $c_customer_name = Yii::$app->getRequest()->get('c_customer_name');
        try {
            $query = (new yii\db\Query())->select(['o_serial_id', 'o_id', 'p_name', 'p_type', 'o_total_price', 'o_total_deposit', 'o_created_at', 'o_status', 'c_customer_name', 'c_customer_cellphone', 'o_operator_remark', 's_name'])
                ->from(Orders::tableName())
                ->leftJoin(Customer::tableName(), 'o_customer_id=c_id')
                ->leftJoin(Product::tableName(), 'o_product_id=p_id')
                ->leftJoin(Stores::tableName(), 'o_store_id=s_id')
                ->where($where);
            $query->andFilterWhere(['like', 'c_customer_name', $c_customer_name]); // 用户名 搜索
            $count_query = clone $query;
            $total_count = $count_query->count();
            $pages = new yii\data\Pagination(['totalCount' => $total_count]);
            $pages->pageSize = Yii::$app->params['page_size']; // common里的
            $data = $query->orderBy(['o_created_at' => SORT_DESC])->offset($pages->offset)->limit($pages->limit)->all();
            array_walk($data, function (&$v) {
//                $v['o_created_at'] = date('Y-m-d H:i:s', $v['o_created_at']);
                $v['o_total_price'] += 0;
                $v['o_total_deposit'] += 0;
                $v['o_total_borrow_money'] = $v['o_total_price'] - $v['o_total_deposit'];
                $v['o_status'] = Orders::getAllStatus()[$v['o_status']];
                $v['p_type'] = Yii::$app->params['goods_type'][$v['p_type']-1]['t_name'];
                // 不显示以下数据
                unset($v['o_total_price']);
            });
            return ['status' => 1, 'message' => '获取成功', 'data' => $data];
        } catch (yii\base\Exception $e) {
            return ['status' => 0, 'message' => '获取失败_sys', 'data' => []];
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
        if ($data = (new yii\db\Query())->from(TooRegion::tableName())->where(['parent_id' => $pid])->all()) {
            return ['status' => 1, 'message' => 'success', 'data' => $data];
        } else {
            return ['status' => 0, 'message' => 'error', 'data' => []];
        }
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
        $p_add_service_fee = $get->get('p_add_service_fee'); // 个人保障计划
        $p_free_pack_fee = $get->get('p_free_pack_fee'); // 贵宾服务包


        try {
            if (!$total_money || !$product_id) {
                throw new CustomApiException('请求错误');
            }
            $select = ['p_period', 'p_month_rate', 'p_add_service_fee', 'p_free_pack_fee', 'p_finance_mangemant_fee', 'p_customer_management'];
            if (!$data = Product::find()->select($select)->where(['p_id' => $product_id])->asArray()->one()) {
                throw new CustomApiException('请求错误');
            }
            $every_month_repay = CalInterest::calEveryMonth($total_money, $data['p_period'], $data['p_month_rate']);
            // 个人保障计划
            if ($p_add_service_fee == 1) {
                $every_month_repay += round($total_money * $data['p_add_service_fee']/100, 4);
            }
            // 贵宾服务包
            if ($p_free_pack_fee == 1) {
                $every_month_repay += $data['p_free_pack_fee'];
            }
            // 财务管理费
            $p_finance_mangemant_fee = round($total_money * $data['p_finance_mangemant_fee']/100, 4);
            // 客户管理费
            $p_customer_management = round($total_money * $data['p_customer_management']/100, 4);
            $every_month_repay += $p_finance_mangemant_fee + $p_customer_management;
            $res = [];
            for ($i = 0; $i < $data['p_period']; $i++) {
                $res[$i]['time'] = strtotime('+' . $i + 1 . 'months'); // 下个月的明天
                $res[$i]['money'] = round($every_month_repay, 2);
            }
            return ['status' => 1, 'message' => 'success', 'data' => $res];
        } catch (CustomApiException $e) {
            return ['status' => 0, 'message' => $e->getMessage(), 'data' => []];
        } catch (yii\base\ErrorException $e) {
            return ['status' => 0, 'message' => '系统错误_sys', 'data' => []];
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
        if (1 == 1) {
            return ['status' => 1, 'message' => 'success'];
        }
        return ['status' => 0, 'message' => '身份证号码不正确'];
    }


    /**
     * 获取所有逾期的客户
     *按订单显示一条
    SELECT
        sum(r_overdue_money) AS total_overdue_money,
        max(c_customer_name) as c_customer_name,
        max(c_customer_cellphone) as c_customer_cellphone,
        sum(r_overdue_day) as total_overduy_day,
        (max(r_balance)+sum(r_overdue_money)) as total_debt
    FROM  orders
    LEFT JOIN repayment ON o_id = r_orders_id
    left join customer on o_customer_id=c_id
    WHERE
        r_status = 2 and o_status=10 and o_user_id=员工id
    GROUP BY
        o_id
     *
     *
     *
     * 按还款计划显示多条
     * SELECT
    r_overdue_money,
    c_customer_name,
    c_customer_cellphone,
    r_overdue_day,
    (max(r_balance)+sum(r_overdue_money)) as total_debt
    FROM
    orders
    LEFT JOIN repayment ON o_id = r_orders_id
    left join customer on o_customer_id=c_id
    WHERE
    r_status = 2 and o_status=10 and o_user_id=11 and r_overdue_day>=0
    GROUP BY
    r_id
     * @return array
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionGetOverdueByUid()
    {
        Yii::$app->getResponse()->format = yii\web\Response::FORMAT_JSON;

        $userinfo = Yii::$app->getUser();
        $uid = $userinfo->getId();
        $days = Yii::$app->getRequest()->get('days', 1);
        $days = 0;
        /*$select = [
            'sum(r_overdue_money) AS total_overdue_money',
            'max(c_customer_name) as c_customer_name',
            'max(c_customer_cellphone) as c_customer_cellphone',
            'sum(r_overdue_day) as total_overduy_day',
            '(max(r_balance)+sum(r_overdue_money)) as total_debt'
        ];*/
        $select = [
            'r_overdue_money', 'c_customer_name', 'c_customer_cellphone', 'r_overdue_day', '(max(r_balance)+sum(r_overdue_money)) as total_debt'
        ];
        $query = Orders::find()->select($select)
            ->leftJoin(Repayment::tableName(), 'o_id=r_orders_id')
            ->leftJoin(Customer::tableName(), 'r_customer_id=c_id')
            ->where(['o_user_id'=>$uid])
            ->andWhere(['r_status'=>Repayment::STATUS_OVERDUE]); // 状态为逾期，

        $data = $query->andFilterWhere(['>=', 'r_overdue_day', $days])->groupBy('r_id')->asArray()->all();

        array_walk($data, function(&$v){
            $v['r_overdue_money'] = round($v['r_overdue_money'], 2);
            $v['total_debt'] = round($v['total_debt'], 2);
        });
        return ['status'=>1, 'message'=>'ok', 'data'=>$data];
    }


    /**
     * 给客户端返回合同内容
     * @return array
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionGetContract($o_id=8)
    {
        Yii::$app->getResponse()->format = 'json';
        $data = "我是合同，屌不屌" . $o_id;
        $data = Contract::genContractForOid($o_id);
        $url = 'http://211.149.163.238/contract/index';
        return ['status' => 1, 'message' => 'ok', 'data' => $url];
    }

    /**
     * 给客户端返回订单详情
     *
    SELECT *
    from orders
    LEFT JOIN customer on c_id = o_customer_id
    LEFT JOIN product on o_product_id = p_id
    LEFT JOIN stores on s_id=o_store_id
    WHERE
    o_id = 8
     * @return array
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionGetOrderDetail($o_id)
    {
        Yii::$app->getResponse()->format = 'json';
        $data = Orders::find()->select('*')
            ->leftJoin(Customer::tableName(), 'c_id=o_customer_id')
            ->leftJoin(Product::tableName(), 'o_product_id=p_id')
            ->leftJoin(Stores::tableName(), 's_id=o_store_id')
            ->where('o_id=:o_id', [':o_id'=>$o_id])
            ->asArray()->one();
        $data['data_goods'] = Goods::find()->where(['g_order_id'=>$o_id])->asArray()->all();


        $total_borrow_money = $data['o_total_price']-$data['o_total_deposit'];
        $id_address = Helper::getAddrName($data['c_customer_province']). Helper::getAddrName($data['c_customer_city']). Helper::getAddrName($data['c_customer_county']). $data['c_customer_idcard_detail_addr'];
        $data['c_bank'] = Yii::$app->params['bank_list'][$data['c_bank']-1]['bank_name'];
        $data['c_customer_gender'] = Customer::getAllGender()[$data['c_customer_gender']];
        $data['c_family_marital_status'] = Yii::$app->params['marital_status'][$data['c_family_marital_status']]['marital_str'];
        $data['c_kinship_relation'] = Yii::$app->params['kinship'][$data['c_kinship_relation']]['kinship_str'];
        $now_address = Helper::getAddrName($data['c_customer_addr_province']). Helper::getAddrName($data['c_customer_addr_city']). Helper::getAddrName($data['c_customer_addr_county']). $data['c_customer_idcard_detail_addr'];
        $job_address = Helper::getAddrName($data['c_customer_jobs_province']). Helper::getAddrName($data['c_customer_jobs_city']). Helper::getAddrName($data['c_customer_jobs_county']). $data['c_customer_jobs_detail_addr'];

        $data_end = <<<"AAA"
        
<h5 style="color:red">客户信息：</h5>
姓名：{$data['c_customer_name']}<br>
性别：{$data['c_customer_gender']}<br>
电话：{$data['c_customer_cellphone']}<br>
身份证号码：{$data['c_customer_id_card']}<br>
身份证地址：{$id_address}<br>
现居地址：{$now_address}<br>
客户银行：{$data['c_bank']}<br>
客户银行卡号：{$data['c_banknum']}<br>
婚姻状况：{$data['c_family_marital_status']}<br>
配偶姓名：{$data['c_family_marital_partner_name']}<br>
配偶手机号：{$data['c_family_marital_partner_cellphone']}<br>
亲属关系：{$data['c_kinship_relation']}<br>
亲属姓名：{$data['c_kinship_name']}<br>
亲属手机号：{$data['c_kinship_cellphone']}<br>

工作单位：{$data['c_customer_jobs_company']}<br>
工作行业：{$data['c_customer_jobs_type']}<br>
单位性质：{$data['c_customer_jobs_industry']}<br>
单位电话：{$data['c_customer_jobs_phone']}<br>
单位地址：{$job_address}<br>
是否购买社保：{$data['c_customer_jobs_is_shebao']}<br>
其他联系人关系：{$data['c_other_people_relation']}<br>
其他联系人姓名：{$data['c_other_people_name']}<br>
其他联系人电话：{$data['c_other_people_cellphone']}<br>

住房情况：{$data['c_family_house_info']}<br>
住房情况：{$data['c_customer_name']}<br>

<h5 style="color:red">订单信息：</h5>
订单号：{$data['o_id']}<br>
总金额：{$data['o_total_price']}<br>
贷款金额：$total_borrow_money<br>
首付金额：{$data['o_total_deposit']}<br>
是否使用个人保障计划：{$data['o_is_add_service_fee']}<br>
是否使用贵宾服务包：{$data['o_is_free_pack_fee']}<br>
是否使用银行代扣：{$data['o_is_auto_pay']}<br>

<h5 style="color:red">产品信息：</h5>
产品名：{$data['p_name']}<br>
期数：{$data['p_period']}<br>
月利率：{$data['p_month_rate']}%<br>
财务管理费利率：{$data['p_finance_mangemant_fee']}%<br>
客户管理费利率：{$data['p_customer_management']}%<br>


<strong>商户信息：</strong><br>
商户名：{$data['s_name']}<br>
负责人姓名：{$data['s_owner_name']}<br>
负责人电话：{$data['s_owner_phone']}<br>
负责人邮箱：{$data['s_owner_email']}<br>
AAA;

        return ['status' => 1, 'message' => 'ok', 'data' => $data_end];
    }


}