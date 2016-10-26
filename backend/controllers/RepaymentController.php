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
        echo '父菜单';
    }

// 待还
    public function actionWaitRepay()
    {
        $this->getView()->title = '待还款列表';
        $model = new RepaymentSearch();
        $query = $model->repaymenlist(Yii::$app->getRequest()->getQueryParams());
        $query = $query->andWhere(['r_status' => Repayment::STATUS_NOT_PAY]);
        $querycount = clone $query;
        $pages = new yii\data\Pagination(['totalCount' => $querycount->count()]);
        $pages->pageSize = 20;//Yii::$app->params['page_size'];
        $data = $query/*->orderBy(['orders.o_created_at' => SORT_DESC])*/->offset($pages->offset)->limit($pages->limit)->asArray()->all();
        return $this->render('waitrepay', [
            'sear' => $model->getAttributes(),
            'model' => $data,
            'totalpage' => $pages->pageCount,
            'pages'=>$pages
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
        $data = $query/*->orderBy(['orders.o_created_at' => SORT_DESC])*/->offset($pages->offset)->limit($pages->limit)->asArray()->all();
        array_walk($data, function(&$v){
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
    public function actionAlreadyRepay($refund_id)
    {
            try {
                if (!$repay_model = Repayment::findOne(['r_id' => $refund_id])) {
                    throw new CustomBackendException('还款计划不存在');
                }
                $repay_model->r_status = 10; // 已还
                $repay_model->r_repay_date = $_SERVER['REQUEST_TIME']; // 已还
//                $repay_model->r_operator_id = Yii::$app->getUser()->getIdentity()->getId(); // 已还
//                $repay_model->r_operator_date = $_SERVER['REQUEST_TIME']; // 已还
                if(!$repay_model->save(false)){
                    throw new CustomBackendException('还款操作失败');
                }
                return $this->success('还款成功');
            }catch(CustomBackendException $e){
                return $this->error($e->getMessage());
            }catch(yii\base\Exception $e){
                return $this->error('系统错误');
            }
        }
}