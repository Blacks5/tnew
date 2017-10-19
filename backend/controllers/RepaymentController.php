<?php
/**
 * Created by PhpStorm.
 * User: too
 * Date: 2016/10/20
 * Time: 14:37
 * @author 涂鸿 <hayto@foxmail.com>
 */

namespace backend\controllers;

use backend\components\CustomBackendException;
use backend\core\CoreBackendController;
use Carbon\Carbon;
use common\components\CustomCommonException;
use common\components\Helper;
use common\models\Customer;
use common\models\Orders;
use common\models\OrdersSearch;
use common\models\Product;
use common\models\Repayment;
use common\models\RepaymentSearch;
use common\models\User;
use common\models\YijifuDeduct;
use common\models\YijifuSign;
use common\tools\yijifu\ReturnMoney;
use function GuzzleHttp\Promise\all;
use WebSocket\Client;
use yii\data\Pagination;
use yii;

class RepaymentController extends CoreBackendController
{



    public function actionIndex()
    {
//        p(Yii::$app->getUser()->can(yii\helpers\Url::toRoute(['site/index'])));
//        p(yii\helpers\Url::toRoute(['site/index']));
        echo '父菜单';
    }

    public function beforeAction($action)
    {
        // 两个易极付异步回调地址，不验证csrf
        $free_actions = ["deduct-callback"];
        if(in_array($action->id, $free_actions)){
            $this->enableCsrfValidation = false;
        }
        return true;
    }


    /**
     * 待还款列表
     * @return string
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionWaitRepay()
    {
        $this->getView()->title = '待还款列表';
        $params = Yii::$app->getRequest()->getQueryParams();

        $model = new RepaymentSearch();
        $query = $model->repaymentListByOrders($params);
//        $time = $_SERVER['REQUEST_TIME']+(3600*24*33);
        $query = $query->andWhere(['<','o_created_at',strtotime(Yii::$app->params['customernew_date'])])->andWhere(['o_status'=>Orders::STATUS_PAYING])->asArray()->all();
        $repayment =[];
        foreach ($query as $k => $v){
            $repQuery = Repayment::find()->select('r_id')->where(['r_orders_id'=>$v['o_id'], 'r_status'=>1])->orderBy('r_pre_repay_date');
            if(!empty($params['s_time'])){
                $repQuery = $repQuery->andFilterWhere(['>=', 'r_pre_repay_date', strtotime($params['s_time'] . '00:00:00')]);
            }
            if(!empty($params['e_time'])){
                $repQuery = $repQuery->andFilterWhere(['<=', 'r_pre_repay_date', strtotime($params['e_time'] . '00:00:00')]);
            }

            $repayment[$k] = $repQuery->asArray()->one();

        }
        $repaymentQuery = Repayment::find()
            ->select('*')
            ->leftJoin(Orders::tableName(), 'o_id=r_orders_id')
            ->leftJoin(Customer::tableName(), 'c_id=r_customer_id')
            ->leftJoin(Product::tableName(), 'o_product_id=p_id')
            ->where(['r_id'=> $repayment])->orderBy('r_pre_repay_date');
        $querycount = clone $repaymentQuery;
        $pages = new yii\data\Pagination(['totalCount' => $querycount->count()]);
        $pages->pageSize = Yii::$app->params['page_size'];
        $data = $repaymentQuery->offset($pages->offset)->limit($pages->limit)->asArray()->all();
        foreach ($data as $k => $v){
            $n = 2;
            $v['o_total_price'] = round($v['o_total_price'], $n);
            $v['o_total_deposit'] = round($v['o_total_deposit'], $n);
            $v['o_total_interest'] = round($v['o_total_interest'], $n);
            $v['r_overdue_money'] = round($v['r_overdue_money'], $n);
            $v['r_total_repay'] = round($v['r_total_repay'], $n);
            $v['r_principal'] = round($v['r_principal'], $n);
            $v['r_interest'] = round($v['r_interest'], $n);
            $v['r_add_service_fee'] = round($v['r_add_service_fee'], $n);
            $v['r_free_pack_fee'] = round($v['r_free_pack_fee'], $n);
            $v['r_finance_mangemant_fee'] = round($v['r_finance_mangemant_fee'], $n);
            $v['r_customer_management'] = round($v['r_customer_management'], $n);

        };


        return $this->render('waitrepay', [
            'sear' => $model->getAttributes(),
            'model' => $data,
            'totalpage' => $pages->pageCount,
            'pages' => $pages
        ]);
    }

    /**
     * 已还款列表 2017-05-08新增
     * @return string
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionAlreadyRepay()
    {
        $this->getView()->title = '已还款列表';
        $model = new RepaymentSearch();
        $query = $model->repaymenlist(Yii::$app->getRequest()->getQueryParams());
//        $time = $_SERVER['REQUEST_TIME']+(3600*24*33);
        $query = $query->andWhere(['r_status' => Repayment::STATUS_ALREADY_PAY])/*->andWhere(['<=', 'r_pre_repay_date', $time])*/;
        $query = $query->andWhere(['<','o_created_at',strtotime(Yii::$app->params['customernew_date'])]);
        $query = $query->orderBy('r_repay_date DESC');
        $querycount = clone $query;
        $pages = new yii\data\Pagination(['totalCount' => $querycount->count()]);
        $pages->pageSize = Yii::$app->params['page_size'];
        $data = $query->offset($pages->offset)->limit($pages->limit)->asArray()->all();

        $stat_data = array();
        //已还总金额
        $stat_data['r_total_repay'] = round($query->sum('r_total_repay'),2);
        //本金
        $stat_data['r_principal'] = round($query->sum('r_principal'),2);
        //利息
        $stat_data['r_interest'] = round($query->sum('r_interest'),2);
        //贵宾服务包
        $stat_data['r_add_service_fee'] = round($query->sum('r_add_service_fee'),2);
        //随心包服务费
        $stat_data['r_free_pack_fee'] = round($query->sum('r_free_pack_fee'),2);
        //财务管理费
        $stat_data['r_finance_mangemant_fee'] = round($query->sum('r_finance_mangemant_fee'),2);
        //客户管理费
        $stat_data['r_customer_management'] = round($query->sum('r_customer_management'),2);
        //逾期滞纳金
        $stat_data['r_overdue_money'] = round($query->sum('r_overdue_money'),2);

        array_walk($data, function(&$v){
            $n = 2;
            $v['o_total_price'] = round($v['o_total_price'], $n);
            $v['o_total_deposit'] = round($v['o_total_deposit'], $n);
            $v['o_total_interest'] = round($v['o_total_interest'], $n);
            $v['r_overdue_money'] = round($v['r_overdue_money'], $n);
            $v['r_total_repay'] = round($v['r_total_repay'], $n);
            $v['r_principal'] = round($v['r_principal'], $n);
            $v['r_interest'] = round($v['r_interest'], $n);
            $v['r_add_service_fee'] = round($v['r_add_service_fee'], $n);
            $v['r_free_pack_fee'] = round($v['r_free_pack_fee'], $n);
            $v['r_finance_mangemant_fee'] = round($v['r_finance_mangemant_fee'], $n);
            $v['r_customer_management'] = round($v['r_customer_management'], $n);
        });
        return $this->render('alreadyrepay', [
            'sear' => $model->getAttributes(),
            'model' => $data,
            'totalpage' => $pages->pageCount,
            'pages' => $pages,
            'stat_data'=>$stat_data
        ]);
    }


    /**
     * 已还清的借款订单列表
     * @return string
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionPayOverList()
    {
        $userList = User::getLowerForId();
        $this->getView()->title = '已还清订单';
        $model = new OrdersSearch();
        $query = $model->search(Yii::$app->getRequest()->getQueryParams());
        $query = $query->andWhere(['o_status' => Orders::STATUS_PAY_OVER]);
        $query = $query->andWhere(['<','o_created_at',strtotime(Yii::$app->params['customernew_date'])]);
        $querycount = clone $query;
        $pages = new Pagination(['totalCount' => $querycount->count()]);
        $pages->pageSize = Yii::$app->params['page_size'];
        $data = $query->orderBy(['orders.o_operator_date' => SORT_DESC])->offset($pages->offset)->limit($pages->limit)->asArray()->all();

        $provinces = Helper::getAllProvince();
        return $this->render('payoverlist', [
            'sear' => $model->getAttributes(),
            'model' => $data,
            'totalpage' => $pages->pageCount,
            'pages' => $pages,
            "provinces"=>$provinces
        ]);
    }

    /**
     * 逾期还款列表
     * @return string
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionOverdueRepay()
    {
        $this->getView()->title = '已逾期还款列表';
        $params = Yii::$app->getRequest()->getQueryParams();

        $model = new RepaymentSearch();
        $query = $model->repaymentListByOrders($params);
//        $time = $_SERVER['REQUEST_TIME']+(3600*24*33);
        $query = $query->andWhere(['<','o_created_at',strtotime(Yii::$app->params['customernew_date'])])->andWhere(['o_status'=>Orders::STATUS_PAYING])->asArray()->all();
        $repayment =[];
        foreach ($query as $k => $v){
            $repQuery = Repayment::find()->select('r_id')->where(['r_orders_id'=>$v['o_id'], 'r_status'=>1])->orderBy('r_pre_repay_date');
            if(!empty($params['s_time'])){
                $repQuery = $repQuery->andFilterWhere(['>=', 'r_pre_repay_date', strtotime($params['s_time'] . '00:00:00')]);
            }
            if(!empty($params['e_time'])){
                $repQuery = $repQuery->andFilterWhere(['<=', 'r_pre_repay_date', strtotime($params['e_time'] . '00:00:00')]);
            }

            $repayment[$k] = $repQuery->asArray()->one();

        }
        $repaymentQuery = Repayment::find()
            ->select('*')
            ->leftJoin(Orders::tableName(), 'o_id=r_orders_id')
            ->leftJoin(Customer::tableName(), 'c_id=r_customer_id')
            ->leftJoin(Product::tableName(), 'o_product_id=p_id')
            ->where(['r_id'=> $repayment])
            ->andWhere(['>', 'r_overdue_day', 3])
            ->orderBy('r_pre_repay_date');
        $querycount = clone $repaymentQuery;
        $pages = new yii\data\Pagination(['totalCount' => $querycount->count()]);
        $pages->pageSize = Yii::$app->params['page_size'];
        $data = $repaymentQuery->offset($pages->offset)->limit($pages->limit)->asArray()->all();

        $stat_data = array();
        //已还总金额
        $stat_data['r_total_repay'] = round($repaymentQuery->sum('r_total_repay'),2);
        //本金
        $stat_data['r_principal'] = round($repaymentQuery->sum('r_principal'),2);
        //利息
        $stat_data['r_interest'] = round($repaymentQuery->sum('r_interest'),2);
        //贵宾服务包
        $stat_data['r_add_service_fee'] = round($repaymentQuery->sum('r_add_service_fee'),2);
        //随心包服务费
        $stat_data['r_free_pack_fee'] = round($repaymentQuery->sum('r_free_pack_fee'),2);
        //财务管理费
        $stat_data['r_finance_mangemant_fee'] = round($repaymentQuery->sum('r_finance_mangemant_fee'),2);
        //客户管理费
        $stat_data['r_customer_management'] = round($repaymentQuery->sum('r_customer_management'),2);
        //逾期滞纳金
        $stat_data['r_overdue_money'] = round($repaymentQuery->sum('r_overdue_money'),2);

        array_walk($data, function(&$v){
            $n = 2;
            $v['o_total_price'] = round($v['o_total_price'], $n);
            $v['o_total_deposit'] = round($v['o_total_deposit'], $n);
            $v['o_total_interest'] = round($v['o_total_interest'], $n);
            $v['r_overdue_money'] = round($v['r_overdue_money'], $n);
            $v['r_total_repay'] = round($v['r_total_repay'], $n);
            $v['r_principal'] = round($v['r_principal'], $n);
            $v['r_interest'] = round($v['r_interest'], $n);
            $v['r_add_service_fee'] = round($v['r_add_service_fee'], $n);
            $v['r_free_pack_fee'] = round($v['r_free_pack_fee'], $n);
            $v['r_finance_mangemant_fee'] = round($v['r_finance_mangemant_fee'], $n);
            $v['r_customer_management'] = round($v['r_customer_management'], $n);
        });
        return $this->render('overdue', [
            'sear' => $model->getAttributes(),
            'model' => $data,
            'totalpage' => $pages->pageCount,
            'pages' => $pages,
            'stat_data'=>$stat_data
        ]);
    }

    /**
     * 逾期还款列表
     * @return string
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionOverdueRepay_bak()
    {
        $this->getView()->title = '已逾期还款列表';
        $model = new RepaymentSearch();
        $query = $model->repaymenlist(Yii::$app->getRequest()->getQueryParams());
        $query = $query->andWhere(['>', 'r_overdue_day', 0]);
        $query = $query->andWhere(['<','o_created_at',strtotime(Yii::$app->params['customernew_date'])]);
        $querycount = clone $query;
        $pages = new yii\data\Pagination(['totalCount' => $querycount->count()]);
        $pages->pageSize = Yii::$app->params['page_size'];
        $data = $query/*->orderBy(['orders.o_created_at' => SORT_DESC])*/
        ->offset($pages->offset)->limit($pages->limit)->asArray()->all();
        array_walk($data, function (&$v) {
            $v['r_overdue_money'] = 108;
        });
//        var_dump($data, $model->getAttributes());die;
        return $this->render('overdue', [
            'sear' => $model->getAttributes(),
            'model' => $data,
            'totalpage' => $pages->pageCount,
            'pages'=>$pages
        ]);
    }

    /**
     * 还款操作
     * @param $refund_id
     * @return array
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionRepay($refund_id)
    {
        if(Yii::$app->getRequest()->getIsAjax()) {
            $trans = Yii::$app->getDb()->beginTransaction();
            try {
                Yii::$app->getResponse()->format = yii\web\Response::FORMAT_JSON;
                $sql = "select * from ". Repayment::tableName()." where r_id=:r_id and r_status=:r_status limit 1 for update";
                if (!$repay_model = Repayment::findBySql($sql, ['r_id' => $refund_id, ':r_status' => Repayment::STATUS_NOT_PAY])->one()) {
                    throw new CustomBackendException('数据异常', 2);
                }
                $repay_model->r_status = Repayment::STATUS_ALREADY_PAY; // 已还
                $repay_model->r_repay_date = $_SERVER['REQUEST_TIME']; // 已还
                $repay_model->r_operator_id = Yii::$app->getUser()->getIdentity()->getId(); // 已还
                $repay_model->r_operator_date = $_SERVER['REQUEST_TIME']; // 已还
                if (!$repay_model->save(false)) {
                    throw new CustomBackendException('还款操作失败', 5);
                }
                // 如果是最后一期，再把order表的状态改了
                if($repay_model->r_is_last == 1){
                    if(Orders::updateAll(['o_status'=>Orders::STATUS_PAY_OVER], ['o_id'=>$repay_model->r_orders_id]) != 1){
                        throw new CustomBackendException('还款操作失败', 5);
                    }
                }
                /*累积客户的 总支付利息*/
                $sql = "select * from customer where c_id=".$repay_model->r_customer_id. " limit 1 for update";
                $c = Customer::findBySql($sql)->one();
                $c->c_total_interest += $repay_model->r_total_repay;
                $c->save(false);

                $trans->commit();
                return ['status' => 1, 'message' => '还款操作成功'];
            } catch (CustomBackendException $e) {
                $trans->rollBack();
                return ['status' => $e->getCode(), 'message' => $e->getMessage()];
            } catch (yii\base\Exception $e) {
                $trans->rollBack();
                return ['status' => 0, 'message' => '系统错误'];
            }
        }
    }
//    /**
//     * 发起扣款
//     * @return array
//     * @author too <hayto@foxmail.com>
//     */
//    private function actionRepayBak($refund_id)
//    {
//        $request = Yii::$app->getRequest();
//        if ($request->getIsAjax()) {
//
//            $trans = Yii::$app->getDb()->beginTransaction();
//            try {
//                Yii::$app->getResponse()->format = yii\web\Response::FORMAT_JSON;
//// select o_serial_id
//                $sql = "select o.o_serial_id, (r_total_repay+ r_overdue_money) as deductAmount  from  repayment as r LEFT JOIN orders as o on r.r_orders_id=o.o_id and r_status=". Repayment::STATUS_NOT_PAY." where r_id=:r_id limit 1 for update";
////                $r_data = Repayment::findBySql($sql, [':r_id'=>$refund_id])->one();
//                $r_data = Yii::$app->getDb()->createCommand($sql, [':r_id'=>$refund_id])->queryOne();
//                $sql = "select merchOrderNo from yijifu_sign where o_serial_id=:o_serial_id and status=1 limit 1 for update";
//                $yi_data = YijifuSign::findBySql($sql, [':o_serial_id'=>$r_data['o_serial_id']])->one();
//
//                $handle = new ReturnMoney();
//                $handle->deduct($r_data['o_serial_id'], $refund_id, $yi_data['merchOrderNo'], $r_data['deductAmount']);
//
//                $trans->commit();
//                return ['status' => 1, 'message' => '扣款请求发起成功，请等待注意查看通知！'];
//            }catch (CustomCommonException $e){
//                $trans->rollBack();
//                return ['status' => $e->getCode(), 'message' => $e->getMessage()];
//            }catch (\Exception $e){
//                $trans->rollBack();
//                throw $e;
//                return ['status' => 2, 'message' => '系统错误'];
//            }
//        }
//    }
//    /**
//     * 易极付扣款异步回调
//     * @author too <hayto@foxmail.com>
//     */
//    private function actionDeductCallback()
//    {
//        $post = Yii::$app->getRequest()->post();
//        if('true' === $post['success']){
//            $status_arr = [
//                'INIT' => 1, // 待处理
//                'WITHHOLD_DEALING' => 2, // 代扣处理中
//                'CHECK_NEEDED' => 3, // 待审核
//                'CHECK_REJECT' => 4, // 审核驳回
//                'WITHHOLD_FAIL' => 5, // 代扣失败
//                'WITHHOLD_SUCCESS' => 6, // 代扣成功
//                'SETTLE_SUCCESS' => 7, // 结算成功
//            ];
//            $data = [
//                'realName'=>$post['realName'],
//                'bankCardNo'=>$post['bankCardNo'],
//                'bankCode'=>$post['bankCode'],
//                'realRepayTime'=>isset($post['realRepayTime'])?$post['realRepayTime']:0,
//                'errorCode'=>isset($post['errorCode'])? $post['errorCode']: '',
//                'description'=>isset($post['description']) ? $post['description']: '',
//                'status'=>isset($status_arr[$post['status']])? $status_arr[$post['status']]: '未知状态'
//            ];
//            $where = ['merchOrderNo'=>$post['merchOrderNo']];
//            $trans = Yii::$app->getDb()->beginTransaction();
//            try{
//
//                YijifuDeduct::updateAll($data, $where);
//                $status_str = [
//                    'INIT' => '待处理', // 待处理
//                    'WITHHOLD_DEALING' => '代扣处理中', // 代扣处理中
//                    'CHECK_NEEDED' => '待审核', // 待审核
//                    'CHECK_REJECT' => '审核驳回', // 审核驳回
//                    'WITHHOLD_FAIL' => '代扣失败', // 代扣失败
//                    'WITHHOLD_SUCCESS' => '代扣成功', // 代扣成功
//                    'SETTLE_SUCCESS' => '结算成功', // 结算成功
//                ];
//
//
//                $yijifu_data = YijifuDeduct::find()->where(['merchOrderNo'=>$post['merchOrderNo']])->one();
//                $sql = "select * from ". Repayment::tableName()." where r_id=:r_id and r_status=:r_status limit 1 for update";
//                if (!$repay_model = Repayment::findBySql($sql, ['r_id' => $yijifu_data['repayment_id'], ':r_status' => Repayment::STATUS_NOT_PAY])->one()) {
//                    throw new CustomBackendException('数据异常', 2);
//                }
//                $repay_model->r_status = Repayment::STATUS_ALREADY_PAY; // 已还
//                $repay_model->r_repay_date = $_SERVER['REQUEST_TIME']; // 已还
//                $repay_model->r_operator_id = $yijifu_data['operator_id']; // 已还
//                $repay_model->r_operator_date = $_SERVER['REQUEST_TIME']; // 已还
//                if (!$repay_model->save(false)) {
//                    throw new CustomBackendException('还款操作失败', 5);
//                }
//                // 如果是最后一期，再把order表的状态改了
//                if($repay_model->r_is_last == 1){
//                    if(Orders::updateAll(['o_status'=>Orders::STATUS_PAY_OVER], ['o_id'=>$repay_model->r_orders_id]) != 1){
//                        throw new CustomBackendException('还款操作失败', 5);
//                    }
//                }
//                /*累积客户的 总支付利息*/
//                $sql = "select * from customer where c_id=".$repay_model->r_customer_id. " limit 1 for update";
//                $c = Customer::findBySql($sql)->one();
//                $c->c_total_interest += $repay_model->r_total_repay+ $repay_model->r_overdue_money;
//                $c->save(false);
//
//                $this->sendToWsByDeduct($yijifu_data['o_serial_id'], $repay_model['r_orders_id'], $status_str[$post['status']]);
//                $trans->commit();
//                echo "success";
//            }catch (CustomCommonException $e){
//                $trans->rollBack();
//                $this->sendToWsByDeduct($yijifu_data['o_serial_id'], $repay_model['r_orders_id'], $status_str[$post['status']]);
//            }catch (\Exception $e){
//                $trans->rollBack();
//                $this->sendToWsByDeduct($yijifu_data['o_serial_id'], $repay_model['r_orders_id'], '系统错误');
//            }
//        }else{
//            // 接口调用失败
//        }
//    }
//    private function sendToWsByDeduct($o_serial_id, $o_id, $status_str)
//    {
//        $client = new Client(\Yii::$app->params['ws']);
////        $client = new Client('ws://192.168.1.65:8081');
//        $string = '代扣订单:'. $o_serial_id. ': '. $status_str; // 订单号 *** 签约成功
//        $data = [
//            'cmd'=>'Orders:deductNotify',
//            'data'=>[
//                'message'=>$string,
//                'order_id'=>$o_id
//            ]
//        ];
//        $jsonData = json_encode($data, JSON_UNESCAPED_UNICODE);
//        $client->send($jsonData);
//    }

    /**
     * 某个订单的所有还款计划
     * @param $order_id
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionAllRepaymentList($order_id)
    {
        $this->getView()->title = '还款列表';
        $query = RepaymentSearch::repaymenlistbyorderid($order_id);
//        $query = $query->andWhere(['r_status' => Repayment::STATUS_NOT_PAY]);
        $querycount = clone $query;
        $pages = new yii\data\Pagination(['totalCount' => $querycount->count()]);
        $pages->pageSize = 20;//Yii::$app->params['page_size'];
        $data = $query/*->orderBy(['orders.o_created_at' => SORT_DESC])*/
        ->offset($pages->offset)->limit($pages->limit)->asArray()->all();

        return $this->render('allrepaymentlist', [
            'model' => $data,
            'totalpage' => $pages->pageCount,
            'pages' => $pages
        ]);
    }


    /**
     * 修改还款时间
     * @param $order_id
     * @return array|string
     * @author OneStep
     */
    public function actionUpdateRepayTime($order_id)
    {
        $data = Repayment::find()->leftJoin(Orders::tableName(), 'orders.o_id = repayment.r_orders_id')
            ->where(['r_orders_id' => $order_id])
            ->andWhere(['<', 'r_overdue_day', 4])
            ->andWhere(['r_status' => 1])
            ->orderBy(['r_pre_repay_date' => 'SORT_DESC'])
            ->asArray()->one();

        if (yii::$app->request->getIsAjax()) {
            Yii::$app->getResponse()->format = yii\web\Response::FORMAT_JSON;
            $request = yii::$app->request;
            $repayment = Repayment::find()
                ->where(['r_orders_id' => $request->post('r_order_id')])
                ->andWhere(['<', 'r_overdue_day', 4])
                ->andWhere(['r_status' => 1])
                ->orderBy('r_pre_repay_date ASC')
                ->asArray()->all();
            $Date = (strtotime($request->post('o_update_time')) - $repayment[0]['r_pre_repay_date']) / (24 * 3600) + 1;

            $Month = Carbon::createFromTimestamp(strtotime($request->post('o_update_time')))->startOfMonth();
            $dt = Carbon::createFromTimestamp($repayment[0]['r_pre_repay_date'])->addDay(floor($Date));
            foreach ($repayment as $k => $v) {
                $newDate = Repayment::find()->where(['r_id' => $v['r_id']])->andWhere(['r_status'=>1])->one();
                if ($dt->month != $Month->month) {
                    $dt->month = $Month->month;
                    $newDate->r_pre_repay_date = strtotime($dt->endOfMonth()->toDateTimeString());
                } else {
                    $newDate->r_pre_repay_date = strtotime($dt->toDateTimeString());
                }

                $newDate->save(false);
                $dt->addMonth(1);
                $Month->addMonth(1);

            }


            $order = Orders::find()->where(['o_id' => $request->post('r_order_id')])->one();  //修改orders 还款时间次数
            $order->o_number_of_modify_date = $order->o_number_of_modify_date + 1;
            if ($order->save()) {
                return ['status' => 1, 'message' => '修改成功!'];
            }
        }
        return $this->render('update_time', ['data' => $data]);

    }

    public function actionUpdateUndesirable($o_id, $value)
    {
        Yii::$app->getResponse()->format = yii\web\Response::FORMAT_JSON;
        if(yii::$app->request->getIsAjax()){
            $is = $value==0?1:0;
            $orders = Orders::findOne($o_id);
            $orders->o_is_undesirable = $is;
            if($orders->save(false)){
                return ['status'=>1, 'message'=>'修改不良成功!'];
            }
        }
        return ['status'=>2,'message'=>'不知道怎么就出错了!'];
    }

}