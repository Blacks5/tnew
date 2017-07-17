<?php
/**
 * Created by PhpStorm.
 * User: too
 * Date: 2016/10/20
 * Time: 14:31
 * @author 涂鸿 <hayto@foxmail.com>
 */
namespace backend\controllers;

use backend\components\CustomBackendException;
use common\components\CustomCommonException;
use common\models\CalInterest;
use common\models\Customer;
use common\models\Goods;
use common\models\OrderImages;
use common\models\Orders;
use common\models\Repayment;
use common\models\YijifuLoan;
use common\models\YijifuSign;
use common\models\YijifuSignReturnmoney;
use common\tools\yijifu\ReturnMoney;
use WebSocket\Client;
use yii;
use backend\core\CoreBackendController;
use common\models\OrdersSearch;

class BorrowController extends CoreBackendController
{
    public function actionIndex()
    {

        echo '父菜单';
    }
    public function beforeAction($action)
    {
        // 两个易极付异步回调地址，不验证csrf
        $free_actions = ["verify-pass-callback", "deduct-callback"];
        if(in_array($action->id, $free_actions)){
            $this->enableCsrfValidation = false;
        }
        return true;
    }

    // 列表 待审核
    public function actionListWaitVerify()
    {
        $this->getView()->title = '待审核列表';
        $model = new OrdersSearch();
        $query = $model->search(Yii::$app->getRequest()->getQueryParams());
        $query = $query->andWhere(['o_status' => [Orders::STATUS_WAIT_CHECK, Orders::STATUS_WAIT_CHECK_AGAIN, Orders::STATUS_WAIT_APP_UPLOAD_AGAIN]]);
        $querycount = clone $query;
        $pages = new yii\data\Pagination(['totalCount' => $querycount->count()]);
        $pages->pageSize = Yii::$app->params['page_size'];
        $data = $query->orderBy(['orders.o_created_at' => SORT_DESC])->offset($pages->offset)->limit($pages->limit)->asArray()->all();
        return $this->render('listwaitverify', [
            'sear' => $model->getAttributes(),
            'model' => $data,
            'totalpage' => $pages->pageCount,
            'pages' => $pages
        ]);
    }

    /**
     * 已拒绝列表
     * @return string
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionListVerifyRefuse()
    {

        $this->getView()->title = '已拒绝列表';
        $model = new OrdersSearch();
        $query = $model->search(Yii::$app->getRequest()->getQueryParams());
        $query = $query->andWhere(['o_status' => Orders::STATUS_REFUSE]);
        $querycount = clone $query;
        $pages = new yii\data\Pagination(['totalCount' => $querycount->count()]);
        $pages->pageSize = Yii::$app->params['page_size'];
        $data = $query->orderBy(['orders.o_created_at' => SORT_DESC])->offset($pages->offset)->limit($pages->limit)->asArray()->all();
        return $this->render('listverifyrefuse', [
            'sear' => $model->getAttributes(),
            'model' => $data,
            'totalpage' => $pages->pageCount,
            'pages' => $pages
        ]);
    }

    /**
     * 已撤销列表（生成还款计划后，又撤销的）
     * @return string
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionListVerifyRevoke()
    {
        $this->getView()->title = '已撤销列表';
        $model = new OrdersSearch();
        $query = $model->search(Yii::$app->getRequest()->getQueryParams());
        $query = $query->andWhere(['o_status' => Orders::STATUS_REVOKE]);
        $querycount = clone $query;
        $pages = new yii\data\Pagination(['totalCount' => $querycount->count()]);
        $pages->pageSize = Yii::$app->params['page_size'];
        $data = $query->orderBy(['orders.o_created_at' => SORT_DESC])->offset($pages->offset)->limit($pages->limit)->asArray()->all();
        return $this->render('listverifyrevoke', [
            'sear' => $model->getAttributes(),
            'model' => $data,
            'totalpage' => $pages->pageCount,
            'pages' => $pages
        ]);
    }

    /**
     * 已通过列表
     * @return string
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionListVerifyPass()
    {
        $this->getView()->title = '已通过列表';
        $model = new OrdersSearch();
        $query = $model->search(Yii::$app->getRequest()->getQueryParams());
        $query = $query->andWhere(['o_status' => Orders::STATUS_PAYING]);
        $querycount = clone $query;
        $pages = new yii\data\Pagination(['totalCount' => $querycount->count()]);
        $pages->pageSize = Yii::$app->params['page_size'];
        $data = $query->orderBy(['orders.o_created_at' => SORT_DESC])->offset($pages->offset)->limit($pages->limit)->asArray()->all();
        return $this->render('listverifypass', [
            'sear' => $model->getAttributes(),
            'model' => $data,
            'totalpage' => $pages->pageCount,
            'pages' => $pages
        ]);
    }


    /**
     * 订单详情
     * @param $order_id
     * @return mixed|string
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionView($order_id)
    {
        if ($model = Orders::getOne($order_id)) {
            $model['month_repayment'] = CalInterest::calRepayment(
                $model['o_total_price']- $model['o_total_deposit'],
                $model['p_id'],
                $model['o_is_add_service_fee'],
                $model['o_is_free_pack_fee']
                );
            $goods_data = Goods::find()->where(['g_order_id'=>$order_id])->asArray()->all();
            $loan_data = YijifuLoan::find()->where(['y_serial_id'=>$model['o_serial_id']])->asArray()->one();
//          var_dump($loan_data);die;
//            var_dump($goods_data, $model);die;
            return $this->render('view', ['model' => $model, 'goods_data'=>$goods_data, 'loan_data'=>$loan_data]);
        }
        return $this->error('数据不存在！'/*, yii\helpers\Url::toRoute(['borrow'])*/);
    }

    /**
     * 初审通过
     * @param $order_id
     * @return array
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionVerifyPassFirst($order_id)
    {
        $request = Yii::$app->getRequest();
        if ($request->getIsAjax()) {
            try {
                Yii::$app->getResponse()->format = yii\web\Response::FORMAT_JSON;

                $o_operator_remark = trim($request->post('remark'));
                $userinfo = Yii::$app->getUser()->getIdentity();

                /* 初审通过可以不写备注
                 * if (empty($o_operator_remark)) {
                    throw new CustomBackendException('请填写原因', 0);
                }*/

                // 初审0 二审6 都可以取消
                if (!$model = Orders::find()->where(['o_status' => Orders::STATUS_WAIT_CHECK, 'o_id' => $order_id])->one()) {
                    throw new CustomBackendException('订单状态已经改变，不可审核！', 4);
                }
                $model->o_status = Orders::STATUS_WAIT_APP_UPLOAD_AGAIN;
                $model->o_operator_id = $userinfo->id;
                $model->o_operator_realname = $userinfo->realname;
                $model->o_operator_date = $_SERVER['REQUEST_TIME'];
                $model->o_operator_remark = $o_operator_remark;
                if (!$model->save(false)) {
                    throw new CustomBackendException('操作订单失败', 5);
                }
                return ['status' => 1, 'message' => '初审订单通过成功，等待上传客户合同图片'];
            } catch (CustomBackendException $e) {
                return ['status' => $e->getCode(), 'message' => $e->getMessage()];
            } catch (yii\base\Exception $e) {
                return ['status' => 2, 'message' => '系统错误'];
            }
        }
    }

    /**
     * 二审通过+生成还款计划
     * @param $order_id
     * @return mixed
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionVerifyPassbak($order_id)
    {
// 审核通过 10. 增加逾期还款列表: 自动计算滞纳金.
        $request = Yii::$app->getRequest();
        if ($request->getIsAjax()) {
            $trans = Yii::$app->db->beginTransaction();
            try {

                Yii::$app->getResponse()->format = yii\web\Response::FORMAT_JSON;

                $o_operator_remark = trim($request->post('remark'));
                $userinfo = Yii::$app->getUser()->getIdentity();
                // 状态必须为6（初审通过）的才可以终审
                $model = Orders::findBySql('select * from orders where o_id=:order_id and o_status=6 limit 1 for update', [':order_id' => $order_id])->one();
                if (false === !empty($model)) {
                    throw new CustomBackendException('订单状态已经改变，不可审核。', 4);
                }
                $model->o_status = Orders::STATUS_PAYING;
                $model->o_operator_id = $userinfo->id;
                $model->o_operator_realname = $userinfo->realname;
                $model->o_operator_date = $_SERVER['REQUEST_TIME'];
                $model->o_operator_remark = $o_operator_remark;
                if (!$model->save(false)) {
                    throw new CustomBackendException('操作订单失败', 5);
                }

                // 生成还款计划
                if(Repayment::find()->where(['r_orders_id'=>$order_id])->exists()){
                    throw new CustomBackendException('已存在还款计划', 5);
                }
                CalInterest::genRefundPlan($order_id);

                // 累积 客户的 总借款金额
                $sql = "select * from customer where c_id=" . $model->o_customer_id . " limit 1 for update";
                $c = Customer::findBySql($sql)->one();
                $c->c_total_money += $model->o_total_price - $model->o_total_deposit;
                $c->c_total_borrow_times += 1; // 借款次数加一
                $c->save(false);

                $trans->commit();
                return ['status' => 1, 'message' => '终审并放款成功，已生成还款计划！'];
            } catch (CustomBackendException $e) {
                $trans->rollBack();
                return ['status' => $e->getCode(), 'message' => $e->getMessage()];
            } catch (yii\base\Exception $e) {
                $trans->rollBack();
                return ['status' => 2, 'message' => '系统错误'];
            }
        }
    }

    /**
     * 新终审+签约
     * @author too <hayto@foxmail.com>
     */
    public function actionVerifyPass($order_id)
    {
        // 判断是否符合终审
        // 签约+写签约记录表(状态为待回调)
        $request = Yii::$app->getRequest();
        if ($request->getIsAjax()) {
            $trans  = Yii::$app->getDb()->beginTransaction();
            try {
                Yii::$app->getResponse()->format = yii\web\Response::FORMAT_JSON;

                $o_operator_remark = trim($request->post('remark'));
                $userinfo = Yii::$app->getUser()->getIdentity();
                // 状态必须为6（初审通过）的才可以终审
                $sql = "select orders.*,customer.*,order_images.oi_after_contract from orders 
left join customer on customer.c_id=orders.o_customer_id 
 LEFT join order_images on o_images_id=oi_id where o_id=:order_id and o_status=6 limit 1 for update";
//                $model = Orders::findBySql($sql, [':order_id' => $order_id])->one();
                $model = Yii::$app->getDb()->createCommand($sql, [':order_id' => $order_id])->queryOne();
//                var_dump($model);die;
                if (false === !empty($model)) {
                    throw new CustomBackendException('订单状态已经改变，不可审核。', 4);
                }
                // 1,2,7,8都表示已经签约或签约处理中
                if(YijifuSignReturnmoney::find()->where(['status'=>[1,2,7,8], 'orderNo'=>$order_id])->exists()){
                    throw new CustomBackendException('该订单状态已经签约!', 4);
                }
                $data = ['o_operator_id'=>$userinfo->id, 'o_operator_realname'=>$userinfo->realname, 'o_operator_date'=>$_SERVER['REQUEST_TIME'], 'o_operator_remark'=>$o_operator_remark];
                $where = ['o_id'=>$order_id];
                if(0 === Orders::updateAll($data, $where)){
                    throw new CustomBackendException('操作订单失败', 5);
                }

                // 生成还款计划，请求接口要用数据，所以必须在这一步生成
                if (Repayment::find()->where(['r_orders_id' => $order_id])->exists()) {
                    throw new CustomBackendException('已存在还款计划', 5);
                }
                CalInterest::genRefundPlan($order_id);


                $handle = new ReturnMoney();
                // 请求签约接口，并写签约记录表
                // 客户表  商品表 还款计划表
                $r_total_repay = Repayment::findBySql("select r_total_repay from repayment where r_orders_id=:r_orders_id", [':r_orders_id'=>$order_id])->scalar();

                $_goods = Goods::findBySql('select g_goods_name, g_goods_models from goods where g_order_id=:g_order_id', [':g_order_id'=>$order_id])->one();
                $purchasedProductName = $_goods['g_goods_name']. $_goods['g_goods_models'];

                $model['oi_after_contract'] = 'http://local80t.ngrok.cc/img/tianniu.jpg';
                $handle->signContractWithCustomer($model['c_customer_name'],//'钟建蓉',
                    $model['c_customer_id_card'],//'510623197905114125',
                    $model['c_banknum'],
                    $model['c_customer_cellphone'],
                    $purchasedProductName,
                    $model['o_serial_id'],
                    $model['oi_after_contract'],
                    $r_total_repay);

                $trans->commit();
                return ['status' => 1, 'message' => '签约请求发起成功，请等待注意查看通知！'];
            } catch (CustomCommonException $e) {
                $trans->rollBack();
                return ['status' => $e->getCode(), 'message' => $e->getMessage()];
            } catch (yii\base\Exception $e) {
                $trans->rollBack();
                return ['status' => 2, 'message' => '系统错误'];
            }
        }
    }

    /**
     * 签约回调
     * @author too <hayto@foxmail.com>
     */
    public function actionVerifyPassCallback()
    {
        // 签约成功+更新签约记录表(状态为已完成)
        // 修改订单状态+生成还款计划
        // 发通知
        $post = Yii::$app->getRequest()->post();
        if('true' === $post['success']){
            // 已经签约成功了[因为接口奇葩的要访问两次，所以加这个过滤]
            if(YijifuSignReturnmoney::find()->where(['status'=>1, 'orderNo'=>$post['orderNo']])->exists()){
                echo "success";
                return;
            }
            // 事务内
            // 修改签约表，修改订单表，修改客户表，生成还款计划，发ws通知
            $trans = Yii::$app->getDb()->beginTransaction();
            try {
                // 只有待回调的才能处理
                $sql = "select *  from " . YijifuSignReturnmoney::tableName() . " where orderNo=:orderNo and status=2 order by created_at desc  limit 1 for update";
                $yijifu_data = YijifuSignReturnmoney::findBySql($sql, [':orderNo' => $post['orderNo']])->one();
                $sql = "select * from " . Orders::tableName() . " where o_serial_id=:o_serial_id limit 1 for update";
                $order_data = Orders::findBySql($sql, [':o_serial_id'=>$yijifu_data['o_serial_id']])->one();
                $sql = "select * from " . Customer::tableName() . " where c_id=:c_id limit 1 for update";
                $customer_data = Customer::findBySql($sql, [':c_id'=>$order_data['o_customer_id']])->one();



                $status_arr = [
                    'SIGN_DEALING' => 7, // 审核中
                    'SIGN_FAIL' => 6, // 审核失败
                    'CHECK_NEEDED' => 8, // 待审核
                    'CHECK_REJECT' => 5, // 审核拒绝
                    'SIGN_SUCCESS' => 1 // 签约成功
                ];
               /* ob_start();
                var_dump(111, $yijifu_data->toArray(), $order_data->toArray(), $customer_data->toArray());
                file_put_contents('/dev.txt', ob_get_clean(), FILE_APPEND);
                die;*/
                $yijifu_data->bankName = $post['bankName'];
                $yijifu_data->bankCode = $post['bankCode'];
                $yijifu_data->bankCardType = $post['bankCardType'];
                $yijifu_data->status = $status_arr[$post['status']];

                $order_data->o_status = Orders::STATUS_PAYING;
                $order_data->o_operator_date = $_SERVER['REQUEST_TIME'];


                $customer_data->c_total_money += $order_data->o_total_price - $order_data->o_total_deposit; // 累加总借款金额
                $customer_data->c_total_borrow_times += 1; // 借款次数加一

                if (false === $customer_data->save(false)) {
                    throw new CustomBackendException('客户信息修改失败', 5);
                }
                if (false === $yijifu_data->save(false)) {
                    throw new CustomBackendException('签约信息修改失败', 5);
                }
                if (false === $order_data->save(false)) {
                    throw new CustomBackendException('订单信息修改失败', 5);
                }

                // 发送后台广播
                $status_arr_string = [
                    'SIGN_DEALING' => '审核中', // 审核中
                    'SIGN_FAIL' => '审核失败', // 审核失败
                    'CHECK_NEEDED' => '待审核', // 待审核
                    'CHECK_REJECT' => '审核拒绝', // 审核拒绝
                    'SIGN_SUCCESS' => '签约成功' // 签约成功
                ];

                // 发个通知
                $client = new Client(\Yii::$app->params['ws']);
//        $client = new Client('ws://192.168.1.65:8081');
                $string = '订单:'. $order_data['o_serial_id']. ': '. $status_arr_string[$post['status']]; // 订单号 *** 签约成功
                $data = [
                    'cmd'=>'Orders:signNotify',
                    'data'=>[
                        'message'=>$string,
                        'order_id'=>$order_data['o_id']
                    ]
                ];
                $jsonData = json_encode($data, JSON_UNESCAPED_UNICODE);
                $client->send($jsonData);

                $trans->commit();
                echo "success";
            }catch (CustomBackendException $e){
                $trans->rollBack();// 发送给后台通知
                $this->sendToWs($order_data['o_serial_id'], $order_data['o_id'], $e->getMessage());
            }catch (\Exception $e)
            {
                $trans->rollBack();
            }
        }/*else{
            ob_start();
            var_dump($post);
            file_put_contents('/dev.txt', ob_get_clean(), FILE_APPEND);
        }*/
    }

    /**
     * 发起扣款
     * @return array
     * @author too <hayto@foxmail.com>
     */
    public function actionDeduct($repayment_id)
    {
        $request = Yii::$app->getRequest();
        if ($request->getIsAjax()) {
            $trans = Yii::$app->getDb()->beginTransaction();
            try {
                Yii::$app->getResponse()->format = yii\web\Response::FORMAT_JSON;
// select o_serial_id
                $sql = "select o.o_serial_id sum(r_total_repay, r_overdue_money) as deductAmount  from  repayment as r LEFT JOIN orders as o on r.r_orders_id=o.o_id where r_id=:r_id limit 1 for update";
                $r_data = Repayment::findBySql($sql, [':r_id'=>$repayment_id])->one();
                $sql = "select merchOrderNo from yijifu_sign where o_serial_id=:o_serial_id and status=1 limit 1 for update";
                $yi_data = YijifuSign::findBySql($sql, [':o_serial_id'=>$r_data['o_serial_id']])->one();

                $handle = new ReturnMoney();
                $handle->deduct($r_data['o_serial_id'], $yi_data['merchOrderNo'], $r_data['deductAmount']);

                $trans->commit();
                return ['status' => 1, 'message' => '扣款请求发起成功，请等待注意查看通知！'];
            }catch (CustomCommonException $e){
                return ['status' => $e->getCode(), 'message' => $e->getMessage()];
            }catch (\Exception $e){
                return ['status' => 2, 'message' => '系统错误'];
            }
        }
    }

    /**
     * 易极付扣款异步回调
     * @author too <hayto@foxmail.com>
     */
    public function actionDeductCallback()
    {
        $post = Yii::$app->getRequest()->post();
        if('true' === $post['success']){

        }
    }


    /**
     * 拒绝
     * 取消和拒绝的区别: 取消可以重提, 拒绝后该身份证三个月内都不能再提.
     * 1，锁定订单。2，修改客户信息，3，修改订单信息
     * @param $order_id
     * @return array
     * @throws yii\db\Exception
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionVerifyRefuse($order_id)
    {
        $request = Yii::$app->getRequest();

        if ($request->getIsAjax()) {
            $trans = Yii::$app->db->beginTransaction();
            try {
                Yii::$app->getResponse()->format = yii\web\Response::FORMAT_JSON;

                $o_operator_remark = trim($request->post('remark'));
                $userinfo = Yii::$app->getUser()->getIdentity();
                if (empty($o_operator_remark)) {
                    throw new CustomBackendException('请填写拒绝原因', 0);
                }

                // 初审0 二审6 都可以取消
                // for udpate加锁，防止并发
                $sql = "select o_status,c_forbidden_time,c_status,c_id from orders left join customer on o_customer_id=c_id where o_id=:order_id for update";
                $res = Orders::findBySql($sql, [':order_id' => $order_id])->asArray()->one();
//                $trans->rollBack();
//                p($res);
                if (!empty($res) === false) {
                    throw new CustomBackendException('订单不存在', 4);
                }
                if (($res['o_status'] != Orders::STATUS_WAIT_CHECK) && ($res['o_status'] != Orders::STATUS_WAIT_CHECK_AGAIN)) {
                    throw new CustomBackendException('订单状态已经改变，不可审核。', 4);
                }


                $attr = ['o_status' => Orders::STATUS_REFUSE, 'o_operator_id' => $userinfo->getId(), 'o_operator_realname' => $userinfo->realname, 'o_operator_date' => $_SERVER['REQUEST_TIME'], 'o_operator_remark' => $o_operator_remark];

                // 更新订单
                if (1 !== Orders::updateAll($attr, ['o_id' => $order_id])) {
                    throw new CustomBackendException('操作订单失败', 5);
                }

                // 更新客户
                $c_forbidden_time = strtotime('+3 months');

                $attr = ['c_forbidden_time' => $c_forbidden_time, 'c_status' => Customer::STATUS_NOT_OK];
                if (1 !== Customer::updateAll($attr, ['c_id' => $res['c_id']])) {
                    throw new CustomBackendException('操作客户失败', 5);
                }

                $trans->commit();
                return ['status' => 1, 'message' => '拒绝订单成功，该客户三个月不能再次申请借款'];
            } catch (CustomBackendException $e) {
                $trans->rollBack();
                return ['status' => $e->getCode(), 'message' => $e->getMessage()];
            } catch (yii\base\Exception $e) {
                $trans->rollBack();
                return ['status' => 2, 'message' => '系统错误'];
            }
        }
    }

    /**
     * 取消
     * 取消和拒绝的区别: 取消可以重提, 拒绝后该身份证三个月内都不能再提.
     * @param $order_id
     * @return mixed
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionVerifyCancel($order_id)
    {
        $request = Yii::$app->getRequest();
        if ($request->getIsAjax()) {
            try {
                Yii::$app->getResponse()->format = yii\web\Response::FORMAT_JSON;

                $o_operator_remark = trim($request->post('remark'));
                $userinfo = Yii::$app->getUser()->getIdentity();
                if (empty($o_operator_remark)) {
                    throw new CustomBackendException('请填写取消原因', 0);
                }
                // 初审0 二审6 都可以取消
                if (!$model = Orders::find()->where(['o_status' => [Orders::STATUS_WAIT_CHECK, Orders::STATUS_WAIT_CHECK_AGAIN], 'o_id' => $order_id])->one()) {
                    throw new CustomBackendException('订单状态已经改变，不可审核。', 4);
                }
                $model->o_status = Orders::STATUS_CANCEL;
                $model->o_operator_id = $userinfo->id;
                $model->o_operator_realname = $userinfo->realname;
                $model->o_operator_date = $_SERVER['REQUEST_TIME'];
                $model->o_operator_remark = $o_operator_remark;
                if (!$model->save(false)) {
                    throw new CustomBackendException('操作订单失败', 5);
                }

                // 更新客户信息
                $x = $model['o_total_price'] - $model['o_total_deposit'];
                $e = new yii\db\Expression("c_total_money=c_total_money-$x");
                $attr = ["c_total_money" => $e]; // 此处要减掉客户的累积借款金额

                if (1 !== Customer::updateAll($attr, ['c_id' => $model['o_customer_id']])) {
                    throw new CustomBackendException('操作客户失败', 5);
                }

                return ['status' => 1, 'message' => '取消订单成功'];
            } catch (CustomBackendException $e) {
                return ['status' => $e->getCode(), 'message' => $e->getMessage()];
            } catch (yii\base\Exception $e) {
//                p($e->getMessage());
                return ['status' => 2, 'message' => '系统错误'];
            }
        }
    }

    /**
     * 照片不合格，状态设为2，需要重新提交商品
     * @param $order_id
     * @return mixed
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionVerifyFailpic($order_id)
    {
        $request = Yii::$app->getRequest();
        if ($request->getIsAjax()) {
            try {
                Yii::$app->getResponse()->format = yii\web\Response::FORMAT_JSON;

                $o_operator_remark = trim($request->post('remark'));
                $userinfo = Yii::$app->getUser()->getIdentity();
                if (empty($o_operator_remark)) {
                    throw new CustomBackendException('请填写原因', 0);
                }
                // 初审0 二审6 都可以取消
                if (!$model = Orders::find()->where(['o_status' => [Orders::STATUS_WAIT_CHECK, Orders::STATUS_WAIT_CHECK_AGAIN], 'o_id' => $order_id])->one()) {
                    throw new CustomBackendException('订单状态已经改变，不可审核。', 4);
                }
                $model->o_status = Orders::STATUS_NOT_COMPLETE;
                $model->o_operator_id = $userinfo->id;
                $model->o_operator_realname = $userinfo->realname;
                $model->o_operator_date = $_SERVER['REQUEST_TIME'];
                $model->o_operator_remark = $o_operator_remark;
                if (!$model->save(false)) {
                    throw new CustomBackendException('操作订单失败', 5);
                }
                return ['status' => 1, 'message' => '操作订单成功'];
            } catch (CustomBackendException $e) {
                return ['status' => $e->getCode(), 'message' => $e->getMessage()];
            } catch (yii\base\Exception $e) {
                return ['status' => 2, 'message' => '系统错误'];
            }
        }
    }

    /**
     * 撤销已经审核通过的借款
     * @param $o_id
     * @return mixed
     * @throws yii\db\Exception
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionRevoke($o_id)
    {
        if (Yii::$app->getRequest()->getIsAjax()) {
            // 1订单改为撤销状态 2删除所有还款计划 3
            $trans = Yii::$app->db->beginTransaction();
            try {
                Yii::$app->getResponse()->format = 'json';

                $sql = 'select * from ' . Orders::tableName() . ' where o_id=:o_id and o_status=' . Orders::STATUS_PAYING . ' limit 1 for update';
                if (!$order_model = Orders::findBySql($sql, [':o_id' => $o_id])->one()) {
                    throw new CustomBackendException('订单不存在', 2);
                }

                $limit_time = 60 * 60; // 1个小时
                if (($order_model->o_operator_date + $limit_time) <= $_SERVER['REQUEST_TIME']) {
                    throw new CustomBackendException('订单已过可撤销时限', 2);
                }

                $order_model->o_status = Orders::STATUS_REVOKE;
                if (!$order_model->save(false)) {
                    throw new CustomBackendException('撤销订单失败', 5);
                }
                $sql = 'select * from ' . Repayment::tableName() . ' where r_orders_id=:r_orders_id for update';
                // 锁数据用
                Repayment::findBySql($sql, [':r_orders_id' => $o_id])->all();
                if (!Repayment::deleteAll(['r_orders_id' => $o_id])) {
                    throw new CustomBackendException('还款计划操作失败', 5);
                }

                // 更新客户表 总借款金额
                $sql = "select * from customer where c_id=" . $order_model->o_customer_id . " limit 1 for update";
                $c = Customer::findBySql($sql)->one();
                $c->c_total_money -= $order_model->o_total_price - $order_model->o_total_deposit;
                $c->c_total_borrow_times -= 1;
                $c->save(false);

                $trans->commit();
                return ['status' => 1, 'message' => '撤销订单成功'];
            } catch (CustomBackendException $e) {
                $trans->rollBack();
                return ['status' => $e->getCode(), 'message' => $e->getMessage()];
            } catch (yii\base\Exception $e) {
                $trans->rollBack();
                return ['status' => 2, 'message' => '系统错误'];
            }
        }
    }

    public function actionShowpics($oid)
    {
        /*
         * `oi_front_id` varchar(100) NOT NULL DEFAULT '' COMMENT '身份证正面',
  `oi_back_id` varchar(100) NOT NULL DEFAULT '' COMMENT '身份证背面',
  `oi_customer` varchar(100) NOT NULL DEFAULT '' COMMENT '客户现场照',
  `oi_front_bank` varchar(100) NOT NULL DEFAULT '' COMMENT '银行卡正面',
  `oi_back_bank` varchar(100) NOT NULL DEFAULT '' COMMENT '银行卡背面',
  `oi_family_card_one` varchar(100) NOT NULL DEFAULT '' COMMENT '户口本1',
  `oi_family_card_two` varchar(100) NOT NULL DEFAULT '' COMMENT '户口本2',
  `oi_driving_license_one` varchar(100) NOT NULL DEFAULT '' COMMENT '驾照1',
  `oi_driving_license_two` varchar(100) NOT NULL DEFAULT '' COMMENT '驾照2',
  `oi_after_contract` varchar(100) NOT NULL DEFAULT '' COMMENT '二审，合同图片1',
  `oi_video` varchar(255) NOT NULL DEFAULT '' COMMENT '视频',*/
        $select = ['oi_front_id', 'oi_back_id', 'oi_customer', 'oi_front_bank'/*, 'oi_back_bank'*/, 'oi_family_card_one',
            'oi_family_card_two', 'oi_driving_license_one', 'oi_driving_license_two', 'oi_after_contract', 'oi_pick_goods', 'oi_serial_num'];
        $data = Orders::find()->select($select)
            ->leftJoin(OrderImages::tableName(), 'o_images_id=oi_id')
            ->where(['o_id' => $oid])
            ->asArray()->one();
//        p($data);
        return $this->render('pics', ['data' => $data]);
    }
}