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
use common\models\Orders;
use common\models\Repayment;
use yii;
use backend\core\CoreBackendController;
use common\models\OrdersSearch;

class BorrowController extends CoreBackendController
{
    public function actionIndex()
    {
        echo '父菜单';
    }

    // 列表 待审核
    public function actionListWaitVerify()
    {
        $this->getView()->title = '待审核列表';
        $model = new OrdersSearch();
        $query = $model->search(Yii::$app->getRequest()->getQueryParams());
        $query = $query->andWhere(['o_status' => [Orders::STATUS_WAIT_CHECK, Orders::STATUS_WAIT_CHECK_AGAIN]]);
        $querycount = clone $query;
        $pages = new yii\data\Pagination(['totalCount' => $querycount->count()]);
        $pages->pageSize = 10;//Yii::$app->params['page_size'];
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
    public function actionListVeriftRefuse()
    {
        $this->getView()->title = '已拒绝列表';
        $model = new OrdersSearch();
        $query = $model->search(Yii::$app->getRequest()->getQueryParams());
        $query = $query->andWhere(['o_status' => Orders::STATUS_REFUSE]);
        $querycount = clone $query;
        $pages = new yii\data\Pagination(['totalCount' => $querycount->count()]);
        $pages->pageSize = 10;//Yii::$app->params['page_size'];
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
    public function actionListVeriftRevoke()
    {
        $this->getView()->title = '已撤销列表';
        $model = new OrdersSearch();
        $query = $model->search(Yii::$app->getRequest()->getQueryParams());
        $query = $query->andWhere(['o_status' => Orders::STATUS_REVOKE]);
        $querycount = clone $query;
        $pages = new yii\data\Pagination(['totalCount' => $querycount->count()]);
        $pages->pageSize = 10;//Yii::$app->params['page_size'];
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
    public function actionListVeriftPass()
    {
        $this->getView()->title = '已通过列表';
        $model = new OrdersSearch();
        $query = $model->search(Yii::$app->getRequest()->getQueryParams());
        $query = $query->andWhere(['o_status' => Orders::STATUS_PAYING]);
        $querycount = clone $query;
        $pages = new yii\data\Pagination(['totalCount' => $querycount->count()]);
        $pages->pageSize = 10;//Yii::$app->params['page_size'];
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
            return $this->render('view', ['model' => $model]);
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
                $model->o_status = Orders::STATUS_WAIT_CHECK_AGAIN;
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
    public function actionVerifyPass($order_id)
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
                if(!$model = Orders::findBySql('select * from orders where o_id=:order_id and o_status=6 limit 1 for update', [':order_id'=>$order_id])->one()){
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
                CalInterest::genRefundPlan($order_id);

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
     * 拒绝
     * 取消和拒绝的区别: 取消可以重提, 拒绝后该身份证三个月内都不能再提.
     * 1，锁定订单。2，修改客户信息，3，修改订单信息
     * @param $o_id
     * @return array
     * @throws yii\db\Exception
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionVerifyRefuse($order_id)
    {
        $request = Yii::$app->getRequest();
        if ($request->getIsAjax()) {
            try {
                Yii::$app->getResponse()->format = yii\web\Response::FORMAT_JSON;

                $o_operator_remark = trim($request->post('remark'));
                $userinfo = Yii::$app->getUser()->getIdentity();
                if (empty($o_operator_remark)) {
                    throw new CustomBackendException('请填写拒绝原因', 0);
                }
                // 初审0 二审6 都可以取消
                if (!$model = Orders::find()->where(['o_status' => [Orders::STATUS_WAIT_CHECK, Orders::STATUS_WAIT_CHECK_AGAIN], 'o_id' => $order_id])->one()) {
                    throw new CustomBackendException('订单状态已经改变，不可审核。', 4);
                }
                $model->o_status = Orders::STATUS_REFUSE;
                $model->o_operator_id = $userinfo->id;
                $model->o_operator_realname = $userinfo->realname;
                $model->o_operator_date = $_SERVER['REQUEST_TIME'];
                $model->o_operator_remark = $o_operator_remark;
                if (!$model->save(false)) {
                    throw new CustomBackendException('操作订单失败', 5);
                }
                return ['status' => 1, 'message' => '拒绝订单成功，该客户三个月不能再次申请借款'];
            } catch (CustomBackendException $e) {
                return ['status' => $e->getCode(), 'message' => $e->getMessage()];
            } catch (yii\base\Exception $e) {
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
                return ['status' => 1, 'message' => '取消订单成功'];
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
                if(!$order_model = Orders::findBySql($sql, [':o_id' => $o_id])->one()){
                    throw new CustomBackendException('订单不存在', 2);
                }

                $limit_time = 30 * 60; // 半个小时
                if(($order_model->o_operator_date + $limit_time) <= $_SERVER['REQUEST_TIME']){
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
}