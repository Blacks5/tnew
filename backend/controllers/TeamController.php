<?php
/**
 * Created by PhpStorm.
 * Date: 16/9/11
 * Time: 19:59
 * @author 涂鸿 <hayto@foxmail.com>
 */

namespace backend\controllers;

use backend\components\CustomBackendException;
use backend\core\CoreBackendController;
use common\components\Helper;
use common\models\Orders;
use common\models\Team;
use common\models\TeamUser;
use common\models\User;
use yii;

class TeamController extends CoreBackendController
{
    public function actionIndexp()
    {
        echo "父菜单";
    }

    /**
     * 团队列表
     * @return string
     * @author lzz <leewangyi@126.com>
     */
    public function actionIndex()
    {
        $this->getView()->title = '团队列表';
        $model = new Team();
        $query = $model->search(Yii::$app->getRequest()->getQueryParams());
        $query_count = clone $query;
        $pages = new yii\data\Pagination(['totalCount' => $query_count->count()]);
        $pages->pageSize = Yii::$app->params['page_size'];
        $data = $query->orderBy(['t_id' => SORT_DESC])->offset($pages->offset)->limit($pages->limit)->asArray()->all();

        $provinces = Helper::getAllProvince();
        return $this->render('index', [
            'model' => $data,
            'sear' => $model->getAttributes(),
            'totalpage' => $pages,
            'provinces'=>$provinces
        ]);
    }

    /**
     * 创建团队
     * @return mixed|string
     * @author lzz <leewangyi@126.com>
     */
    public function actionCreate()
    {
        $this->getView()->title = '创建团队';
        $model = new Team();
        $request = Yii::$app->getRequest();
        if ($request->getIsPost()) {
            if ($model->createTeam($request->post())) {
                return $this->success('添加成功', yii\helpers\Url::toRoute(['team/view', 't_id' => $model->t_id]));
            }
        }

        // 所有省
        $provinces = Helper::getAllProvince();

        return $this->render('create', [
            'model' => $model,
            'provinces' => $provinces
        ]);
    }

    /**
     * 编辑团队
     * @param $t_id
     * @return mixed|string
     * @author lzz <leewangyi@126.com>
     */
    public function actionUpdate($t_id)
    {
        $this->getView()->title = '编辑团队';
        if (!$model = Team::findOne($t_id)) {
            return $this->error('团队不存在', yii\helpers\Url::toRoute(['team/index']));
        }
        $request = Yii::$app->getRequest();
        if ($request->getIsPost()) {
            if ($model->updateTeam($request->post(), $t_id)) {
                return $this->success('编辑成功', yii\helpers\Url::toRoute(['team/view', 't_id' => $model->t_id]));
            }
        }
        // 所有省
//        p($model['t_province']);
        $provinces = Helper::getAllProvince();
        $all_citys = Helper::getSubAddr($model['t_province']);
        $all_countys = Helper::getSubAddr($model['t_city']);
        return $this->render('update', [
            'model' => $model,
            'provinces' => $provinces,
            'all_citys' => $all_citys,
            'all_countys' => $all_countys,
        ]);
    }

    /**
     * 查看团队
     * @param $t_id
     * @return string
     * @author lzz <leewangyi@126.com>
     */
    public function actionView($t_id)
    {
        if ($model = Team::findOne(['t_id' => $t_id])) {
            $sear['start_time'] = 1;
            $sear['end_time'] = 1;
            return $this->render('view', ['model' => $model, 'sear'=>$sear]);
        } else {
            return Yii::$app->getRespontse()->redirect(['index']);
        }
    }

    /**
     * 删除团队
     * @param $t_id
     * @return mixed
     * @author lzz <leewangyi@126.com>
     */
    public function actionDelete($t_id)
    {
        if (TeamUser::findOne(['tu_tid' => $t_id])) {
            Yii::$app->getSession()->setFlash('message', '该团队有成员, 不能删除');
            return $this->error('该团队有成员, 不能删除', yii\helpers\Url::toRoute(['team/index']));
        }
        Team::deleteAll(['t_id' => $t_id]);
        return $this->success('操作成功', yii\helpers\Url::toRoute(['team/index']));
    }

    /**
     * 团队成员列表
     * @param $tu_tid
     * @return string
     * @author lzz <leewangyi@126.com>
     */

    public function actionTeamuserindex()
    {
        $this->getView()->title = '团队成员列表';
        $model = new TeamUser();
        //p(Yii::$app->getRequest()->getQueryParams());
        $query = $model->search(Yii::$app->getRequest()->get());
        $query_count = clone $query;
        $pages = new yii\data\Pagination(['totalCount' => $query_count->count()]);
        $pages->pageSize = Yii::$app->params['page_size'];
        $data = $query->orderBy(['tu_id' => SORT_DESC])->offset($pages->offset)->limit($pages->limit)->asArray()->all();
        //p($model->getAttributes());
        return $this->render('teamuserindex', [
            'model' => $data,
            'sear' => $model->getAttributes(),
            'totalpage' => $pages,
        ]);

    }


    /**
     * 添加团队成员
     * @author lzz <leewangyi@126.com>
     */
    public function actionAddsale($tu_tid)
    {
        try {
            $this->getView()->title = '添加销售';
            $model = new TeamUser();

            $request = Yii::$app->getRequest();
            if ($request->getIsPost()) {
                if (!Team::findOne($tu_tid)) {
                    throw new CustomBackendException('团队不存在');
                }

                if (TeamUser::findOne(['tu_sale_id' => $request->post('tu_sale_id')])) {
                    throw new CustomBackendException('该成员已在团队, 不能添加');
                }
                $model->tu_tid = $tu_tid;
                $model->tu_sale_id = $request->post('tu_sale_id');
                $model->save();
                return $this->success('操作成功', yii\helpers\Url::toRoute(['team/teamuserindex', 'tu_tid' => $tu_tid]));
            }
            return $this->render('addsale', [
                'model' => $model
            ]);
        } catch (CustomBackendException $e) {
            return $this->error($e->getMessage(), yii\helpers\Url::toRoute(['team/index']));
        } catch (yii\base\Exception $e) {
            return $this->error('系统错误', yii\helpers\Url::toRoute(['team/index']));
        }
    }

    /**
     * 删除团队成员
     * @param $id
     * @return mixed
     * @author lzz <leewangyi@126.com>
     */
    public function actionDeletesale($tu_id, $tu_tid)
    {
        try {
            $this->getView()->title = '删除团队成员';
            if (!TeamUser::findOne(['tu_id' => $tu_id])) {
                throw new CustomBackendException('信息不存在');
            }
            TeamUser::deleteAll(['tu_id' => $tu_id]);
            return $this->success('操作成功', yii\helpers\Url::toRoute(['team/teamuserindex', 'tu_tid' => $tu_tid]));

        } catch (CustomBackendException $e) {
            return $this->error($e->getMessage(), yii\helpers\Url::toRoute(['team/index']));
        } catch (yii\base\Exception $e) {
            return $this->error('系统错误', yii\helpers\Url::toRoute(['team/index']));
        }
    }

    /**
     * 获取员工列表
     * @author lzz <leewangyi@126.com>
     */

    public function actionGetusers()
    {

        $user_model = new User();
        $realname = Yii::$app->getRequest()->get('realname') ? Yii::$app->getRequest()->get('realname') : '';
        $where = "username != 'admin' and status != " . User::STATUS_DELETE . " and department_id = '26'";
        if ($realname) {
            $where .= " and realname like '%$realname%'";
        }
        $data = $user_model->find()->where($where);
        $lists = $data
            ->limit(10)->orderBy("id asc")
            ->asArray()
            ->all();
        if ($lists) {
            $msg = array();
            $msg['status'] = 1;
            $str = '';
            foreach ($lists as $k => $row) {
                $str .= "<li class='userlist' onclick=\"select_one('$row[id]','$row[realname]');\" ><span class='userid' style='display: none;'>$row[id]</span><a class='usernme'>$row[realname]</a></li>";
            }
            $msg['name'] = $str;
            echo json_encode($msg);
            exit();
        } else {
            $msg = array();
            $msg['status'] = 2;
            $msg['text'] = '暂无数据!';
            echo json_encode($msg);
            exit();
        }
    }


    /*<p>个人保证计划捆绑率：10%</p>
            <p>贵宾服务包捆绑率：10%</p>
            <p>总提单：10%</p>
            <p>成功提单：10%</p>
            <p>总借出金额：10%</p>*/
    /**
     * 以团队为单位 统计业绩
     *
     *
    /*select
    (select sum(o_is_add_service_fee) from orders where o_user_id in (select tu_sale_id from team_user where tu_tid=9) and o_status in (5, 10)) as total_success_o_is_add_service_fee  -- 总成功数量  个人保证计划
    ,(select sum(o_is_free_pack_fee) from orders where o_user_id in (select tu_sale_id from team_user where tu_tid=9) and o_status in (5, 10))  as total_success_o_is_free_pack_fee -- 总成功数量 贵宾包
    ,count(*) as total -- 总提单
    ,(select count(*) from orders where o_user_id in (select tu_sale_id from team_user where tu_tid=9) and o_status in (5, 10)) as total_success -- 总成功提单
    ,(select sum(o_total_price) from orders where o_user_id in (select tu_sale_id from team_user where tu_tid=9) and o_status in (5, 10)) as total_o_total_price -- 总借出金额
    from orders where o_user_id in (select tu_sale_id from team_user where tu_tid=9)

    o_created_at

     *
     * @return array
     * @author too <hayto@foxmail.com>
     */
    public function actionCalYj()
    {
        $request = Yii::$app->getRequest();
        if($request->getIsAjax()){
            Yii::$app->getResponse()->format = yii\web\Response::FORMAT_JSON;
            try{
                $st = $request->get('st');
                $et = $request->get('et');
                $teamid = $request->get('teamid');



                    // 订单成功的状态
                $success_status = [Orders::STATUS_PAYING, Orders::STATUS_PAY_OVER];
                // 团队的所有成员
                $sub_users = (new yii\db\Query())->from('team_user')->select(['tu_sale_id'])->where(['tu_tid'=>$teamid]);

                // 个人保障计划捆绑成功 总数
                $sub_o_is_add_service_fee = (new yii\db\Query())->select(['sum(o_is_add_service_fee)'])->from('orders')->where(['o_user_id'=>$sub_users])->andWhere(['o_status'=>$success_status]);
                // 贵宾服务包捆绑成功 总数
                $sub_o_is_free_pack_fee = (new yii\db\Query())->select(['sum(o_is_free_pack_fee)'])->from('orders')->where(['o_user_id'=>$sub_users])->andWhere(['o_status'=>$success_status]);
                // 总成功提单
                $sub_total_success_orders = (new yii\db\Query())->select(['count(*)'])->from('orders')->where(['o_user_id'=>$sub_users])->andWhere(['o_status'=>$success_status]);
                // 总提单
                $sub_total__orders = (new yii\db\Query())->select(['count(*)'])->from('orders')->where(['o_user_id'=>$sub_users]);
                // 总借出
                $sub_total_borrow = (new yii\db\Query())->select(['sum(o_total_price)-sum(o_total_deposit)'])->from('orders')->where(['o_user_id'=>$sub_users])->andWhere(['o_status'=>$success_status]);

                $dv = new yii\validators\DateValidator();
                $dv->format = 'php:Y-m-d';
                if(!empty($st)){
                    if(false === $dv->validate($st)) {
                        throw new CustomBackendException('时间格式错误');
                    }
                    $st = strtotime($st);
                    $sub_o_is_add_service_fee->andWhere(['>=', 'o_created_at', $st]);
                    $sub_o_is_free_pack_fee->andWhere(['>=', 'o_created_at', $st]);
                    $sub_total_success_orders->andWhere(['>=', 'o_created_at', $st]);
                    $sub_total__orders->andWhere(['>=', 'o_created_at', $st]);
                    $sub_total_borrow->andWhere(['>=', 'o_created_at', $st]);
                }
                if(!empty($et)){
                    if(false === $dv->validate($et)) {
                        throw new CustomBackendException('时间格式错误');
                    }
                    $et = strtotime($et. ' 23:59:59');
                    $sub_o_is_add_service_fee->andWhere(['<=', 'o_created_at', $et]);
                    $sub_o_is_free_pack_fee->andWhere(['<=', 'o_created_at', $et]);
                    $sub_total_success_orders->andWhere(['<=', 'o_created_at', $et]);
                    $sub_total__orders->andWhere(['<=', 'o_created_at', $et]);
                    $sub_total_borrow->andWhere(['<=', 'o_created_at', $et]);
                }

                $data = (new yii\db\Query())->from('orders')->select([
                    'total_success_o_is_add_service_fee'=>$sub_o_is_add_service_fee,// 个人保障计划捆绑成功 总数
                    'total_success_o_is_free_pack_fee'=>$sub_o_is_free_pack_fee,// 贵宾服务包捆绑成功 总数
                    'total_success_orders'=>$sub_total_success_orders,// 总成功提单
                    'total_o_total_price'=>$sub_total_borrow,// 总借出
                    'sub_total__orders'=>$sub_total__orders // 总提单
                ])->where(['o_user_id'=>$sub_users])->one();

                $data = [
                    // 个人保证计划捆绑率
                    'o_is_add_service_fee'=>($data['total_success_orders'] != 0)? round($data['total_success_o_is_add_service_fee']/$data['total_success_orders']*100, 2). '%' : '0%',
                    // 贵宾服务包捆绑率
                    'o_is_free_pack_fee'=>($data['total_success_orders'] != 0)? round($data['total_success_o_is_free_pack_fee']/$data['total_success_orders']*100, 2). '%' : '0%',
                    // 总提单
                    'total_orders'=>$data['sub_total__orders'],
                    // 成功提单
                    'success_total_orders'=>$data['total_success_orders'],
                    // 总借出金额
                    'total_borrow_money'=>round($data['total_o_total_price'], 2)
                ];
                return ['status'=>1, 'data'=>$data, 'message'=>'success'];
            }catch (CustomBackendException $e){
                return ['status'=>0, 'message'=>$e->getMessage()];
            }catch (\Exception $e){
                return ['status'=>0, 'message'=>'网络错误'];
            }

        }
    }

}

