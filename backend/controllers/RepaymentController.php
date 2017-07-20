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
use common\components\Helper;
use common\models\Customer;
use common\models\Orders;
use common\models\OrdersSearch;
use common\models\Repayment;
use common\models\RepaymentSearch;
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


    /**
     * 待还款列表
     * @return string
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionWaitRepay()
    {
        $this->getView()->title = '待还款列表';
        $model = new RepaymentSearch();
        $query = $model->repaymenlist(Yii::$app->getRequest()->getQueryParams());
//        $time = $_SERVER['REQUEST_TIME']+(3600*24*33);
        $query = $query->andWhere(['r_status' => Repayment::STATUS_NOT_PAY])
            ->andWhere(['<', 'r_overdue_day', 30]);
        $querycount = clone $query;
        $pages = new yii\data\Pagination(['totalCount' => $querycount->count()]);
        $pages->pageSize = Yii::$app->params['page_size'];
        $data = $query->offset($pages->offset)->limit($pages->limit)->asArray()->all();
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
        $this->getView()->title = '已还清订单';
        $model = new OrdersSearch();
        $query = $model->search(Yii::$app->getRequest()->getQueryParams());
        $query = $query->andWhere(['o_status' => Orders::STATUS_PAY_OVER]);
        $querycount = clone $query;
        $pages = new Pagination(['totalCount' => $querycount->count()]);
        $pages->pageSize = Yii::$app->params['page_size'];
        $data = $query->orderBy(['orders.o_created_at' => SORT_DESC])->offset($pages->offset)->limit($pages->limit)->asArray()->all();

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
        $model = new RepaymentSearch();
        $query = $model->repaymenlist(Yii::$app->getRequest()->getQueryParams());
        $query = $query
            ->andWhere(['>', 'r_overdue_day', 0])->andWhere(['r_status'=>Repayment::STATUS_NOT_PAY]);
        $querycount = clone $query;
        $pages = new yii\data\Pagination(['totalCount' => $querycount->count()]);
        $pages->pageSize = Yii::$app->params['page_size'];
        $data = $query/*->orderBy(['orders.o_created_at' => SORT_DESC])*/
        ->offset($pages->offset)->limit($pages->limit)->asArray()->all();
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
            'pages' => $pages
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
}