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
        $query = $query->andWhere(['o_status'=>Orders::STATUS_WAIT_CHECK]);
        $querycount = clone $query;
        $pages = new yii\data\Pagination(['totalCount' => $querycount->count()]);
        $pages->pageSize = 10;//Yii::$app->params['page_size'];
        $data = $query->orderBy(['orders.o_created_at' => SORT_DESC])->offset($pages->offset)->limit($pages->limit)->asArray()->all();
        return $this->render('listwaitverify', [
            'sear' => $model->getAttributes(),
            'model' => $data,
            'totalpage' => $pages->pageCount,
            'pages'=>$pages
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
        $pages->pageSize = Yii::$app->params['page_size'];
        $data = $query->orderBy(['orders.o_created_at' => SORT_DESC])->offset($pages->offset)->limit($pages->limit)->asArray()->all();
        return $this->render('index', [
            'sear' => $model->getAttributes(),
            'model' => $data,
            'totalpage' => $pages->pageCount,
            'pages'=>$pages
        ]);
    }

    /**
     * 已通过列表
     * @return string
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionListVeriftPass()
    {
        $this->getView()->title = '借款通过列表';
        $model = new OrdersSearch();
        $query = $model->search(Yii::$app->getRequest()->getQueryParams());
        $query = $query->andWhere(['o_status' => Orders::STATUS_PAYING]);
        $querycount = clone $query;
        $pages = new yii\data\Pagination(['totalCount' => $querycount->count()]);
        $pages->pageSize = Yii::$app->params['page_size'];
        $data = $query->orderBy(['orders.o_created_at' => SORT_DESC])->offset($pages->offset)->limit($pages->limit)->asArray()->all();
        return $this->render('index', [
            'sear' => $model->getAttributes(),
            'model' => $data,
            'totalpage' => $pages->pageCount,
            'pages'=>$pages
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
        if($model = Orders::getOne($order_id)){
            return $this->render('view', ['model'=>$model]);
        }
        return $this->error('数据不存在！'/*, yii\helpers\Url::toRoute(['borrow'])*/);
    }

    // 审核通过
    public function actionVeriftPass($order_id)
    {
// 审核通过 10. 增加逾期还款列表: 自动计算滞纳金.
        $resq = Yii::$app->getRequest();
        if($resq->getIsAjax()){
            Yii::$app->getResponse()->format = 'json';
            try {
                CalInterest::genRefundPlan($order_id);
                return ['status' => 1, 'message' => '审核通过，已生成还款计划'];
            }catch(CustomCommonException $e){
                return ['status' => 0, 'message' => $e->getMessage()];
            }catch(yii\base\Exception $e){
                return ['status' => 0, 'message' => '系统错误'];
            }
        }
    }

    /**
     * 取消和拒绝的区别: 取消可以重提, 拒绝后该身份证三个月内都不能再提.
     * 1，锁定订单。2，修改客户信息，3，修改订单信息
     * @param $o_id
     * @return array
     * @throws yii\db\Exception
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionVeriftRefuse($o_id)
    {
        $resq = Yii::$app->getRequest();
        if($resq->getIsPost()){
//            Yii::$app->getResponse()->format = 'json';
            $o_operator_remark = $resq->post('remark');
            $userinfo = Yii::$app->getUser()->getIdentity();

            $trans = Yii::$app->db->beginTransaction();
            try{
                if(is_null($o_operator_remark)){
                    throw new CustomBackendException('请填写拒绝原因');
                }

                $model = Orders::findBySql('select * from '.Orders::tableName(). ' where o_status=0 and o_id=:oid limit 1 for update', [':oid'=>$o_id])->one();
                if($model){
                    $model->o_status = Orders::STATUS_REFUSE;
                    $model->o_operator_id = $userinfo->id;
                    $model->o_operator_realname = $userinfo->realname;
                    $model->o_operator_date = date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']);
                    $model->o_operator_remark = $o_operator_remark;
                    if(!$model->save(false)) throw new CustomBackendException('操作失败');
                    Customer::updateAll(['c_is_forbidden'=>1, 'c_forbidden_time'=>strtotime('+3 months')], ['c_id'=>$model->o_customer_id]);
                }

                $trans->commit();
                return $this->success('拒绝成功');
//                return ['status' => 1, 'message' => '拒绝成功'];
            }catch(CustomBackendException $e){
                $trans->rollBack();
                return $this->error($e->getMessage());
//                return ['status' => 0, 'message' => $e->getMessage()];
            }catch(yii\base\Exception $e){
                $trans->rollBack();
                return $this->error('审核异常');
//                return ['status' => 0, 'message' => '审核异常'];
            }
        }

        return $this->error('操作失败');
    }

    /**
     * 取消和拒绝的区别: 取消可以重提, 拒绝后该身份证三个月内都不能再提.
     * @param $order_id
     * @return mixed
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionVeriftCancel($order_id)
    {
//        Yii::$app->getResponse()->format = 'json';
        $order_model = Orders::findOne(['o_id'=>$order_id]);
        $order_model->o_status = Orders::STATUS_CANCEL;
        if(!$order_model->save(false)){
            return $this->error('取消失败');
//            return ['status' => 0, 'message' => '取消失败'];
        }
        return $this->success('取消成功');
//        return ['status' => 1, 'message' => '取消成功'];
    }

    /**
     * 撤销已经审核通过的借款
     * @param $o_id
     * @return mixed
     * @throws yii\db\Exception
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionVeriftRevoke($o_id)
    {
        if (Yii::$app->getRequest()->getIsAjax()) {
            // 1订单改为撤销状态 2删除所有还款计划 3
            try {
                Yii::$app->getResponse()->format = 'json';
                $trans = Yii::$app->db->beginTransaction();
                $sql = 'select * from ' . Orders::tableName() . ' where o_id=:o_id and o_status=' . Orders::STATUS_PAYING . ' limit 1 for update';
                $order_model = Orders::findBySql($sql, [':o_id' => $o_id])->one();
                if (!$order_model) {
                    throw new CustomBackendException('订单不存在');
                }
                $order_model->o_status = Orders::STATUS_REVOKE;
                if(!$order_model->save(false)){
                    throw new CustomBackendException('撤销订单失败');
                }
                $sql = 'select * from ' . Repayment::tableName() . ' where r_orders_id=:r_orders_id for update';
                // 锁数据用
                Repayment::findBySql($sql, [':r_orders_id' => $o_id])->all();
                if (!Repayment::deleteAll(['r_orders_id'=>$o_id])) {
                    throw new CustomBackendException('还款计划操作失败');
                }
                $trans->commit();
                return $this->success('撤销订单成功');
//                return ['status' => 1, 'message' => '撤销订单成功'];
            } catch (CustomBackendException $e) {
                $trans->rollBack();
                return $this->error($e->getMessage());
//                return ['status' => 0, 'message' => $e->getMessage()];
            } catch (yii\base\Exception $e) {
                $trans->rollBack();
                return $this->error('系统错误');
//                return ['status' => 0, 'message' => '系统错误'];
            }
        }
        return $this->error('撤销失败');
    }


}