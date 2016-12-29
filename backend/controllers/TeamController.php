<?php
/**
 * Created by PhpStorm.
 * Date: 16/9/11
 * Time: 19:59
 * @author 涂鸿 <hayto@foxmail.com>
 */

namespace backend\controllers;

use backend\core\CoreBackendController;
use common\models\Team;
use common\models\TeamUser;
use common\components\Helper;
use common\models\User;
use common\models\UserSearch;
use yii;
use backend\components\CustomBackendException;

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
        $pages->pageSize = 3;//Yii::$app->params['page_size'];
        $data = $query->orderBy(['t_id' => SORT_DESC])->offset($pages->offset)->limit($pages->limit)->asArray()->all();
        return $this->render('index', [
            'model' => $data,
            'sear' => $model->getAttributes(),
            'totalpage' => $pages,
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
            return $this->render('view', ['model' => $model]);
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
        $pages->pageSize = 3;//Yii::$app->params['page_size'];
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
        $where = "username != 'admin' and status != " . User::STATUS_DELETE;
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



}

