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
     * 最近30天待还的 计划
     * @return string
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionWaitRepay()
    {
        $this->getView()->title = '待还款列表';
        $model = new RepaymentSearch();
        $query = $model->repaymenlist(Yii::$app->getRequest()->getQueryParams());
        $query = $query->andWhere(['r_status' => Repayment::STATUS_NOT_PAY]);
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
            /*'o_total_price' => string '10000.000' (length=9)
      'o_total_deposit' => string '1000.000' (length=8)
      'o_total_interest' => string '0.000'
 'r_overdue_money' => string '0.00000'
'r_total_repay' => string '833.08575' (length=9)
      'r_principal' => string '703.08300' (length=9)
      'r_interest' => string '105.00300' (length=9)
      'r_add_service_fee' => string '0.00000' (length=7)
      'r_free_pack_fee' => string '0.00000' (length=7)
      'r_finance_mangemant_fee' => string '12.00000' (length=8)
      'r_customer_management' => string '13.00000*/
        });
//        p($data);
        return $this->render('waitrepay', [
            'sear' => $model->getAttributes(),
            'model' => $data,
            'totalpage' => $pages->pageCount,
            'pages' => $pages
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
        return $this->render('payoverlist', [
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
    public function actionOverdueRepay()
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
        return $this->render('overdue', [
            'sear' => $model->getAttributes(),
            'model' => $data,
            'totalpage' => $pages->pageCount,
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