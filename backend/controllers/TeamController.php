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
use yii;
use backend\components\CustomBackendException;

class TeamController extends CoreBackendController
{
    public function actionIndexp()
    {
        echo "父菜单";
    }
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
        $provinces = Helper::getAllProvince();
        return $this->render('update', [
            'model' => $model,
            'provinces' => $provinces
        ]);
    }

    public function actionView($t_id)
    {
        if ($model = Team::findOne(['t_id' => $t_id])) {
            return $this->render('view', ['model' => $model]);
        } else {
            return Yii::$app->getRespontse()->redirect(['index']);
        }
    }

    public function actionDelete($t_id)
    {
        if (TeamUser::findOne(['tu_tid' => $t_id])) {
            Yii::$app->getSession()->setFlash('message', '该团队有成员, 不能删除');
            return $this->success('操作失败', yii\helpers\Url::toRoute(['team/index']));
        }
        Team::deleteAll(['t_id' => $t_id]);
        return $this->success('操作成功', yii\helpers\Url::toRoute(['team/index']));
    }
}

