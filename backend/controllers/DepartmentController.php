<?php
/**
 * Created by PhpStorm.
 * User: too
 * Date: 2016/9/13
 * Time: 13:19
 * @author 涂鸿 <hayto@foxmail.com>
 */

namespace backend\controllers;
use backend\core\CoreBackendController;
use common\models\Department;
use common\models\Jobs;
use common\models\User;
use yii;
use backend\components\CustomBackendException;

class DepartmentController extends CoreBackendController
{
    /**
     * 部门管理父菜单
     *
     */
    public function actionIndexp()
    {
        echo "父菜单";
    }
    /**
     * 创建部门
     * @return string|yii\web\Response
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionCreateDepartment()
    {
        $this->getView()->title = '创建部门';
        $model = new Department();
        $request = Yii::$app->getRequest();
        if($request->getIsPost()){
            if($model->createDepatment($request->post())){
                return $this->redirect(['view-department', 'd_id'=>$model->d_id]);
            }
        }

        return $this->render('createdepartment', ['model'=>$model]);
    }

    public function actionCreateDepartment_bak()
    {
        $this->getView()->title = '创建部门';
        $request = Yii::$app->getRequest();
        $d_name = $request->post('d_name');
        if ($request->getIsAjax()) {
            try {
                Yii::$app->getResponse()->format = 'json';
                if (!$d_name) {
                    throw new CustomBackendException('请填写部门名称');
                }

                if (Department::find()->where(['d_name' => $d_name])->exists()) {
                    throw new CustomBackendException('部门名称已存在');
                }
                $model = new Department();
                $model->d_name = $d_name;
                if (!$model->save()) {
                    throw new CustomBackendException('添加失败');
                }
                return ['status' => 1, 'message' => '添加成功'];
            } catch (CustomBackendException $e) {
                return ['status' => 0, 'message' => $e->getMessage()];
            } catch (yii\base\Exception $e) {
                return ['status' => 0, 'message' => '系统错误'];
            }
        }
    }
    /**
     * 查看部门
     * @param $d_id
     * @return string|yii\web\Response
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionViewDepartment($d_id)
    {
        if($model = Department::findOne(['d_id'=>$d_id])->toArray()){
            $this->getView()->title = $model['d_name'];
            $jobs = Jobs::findAll(['j_department_id'=>$d_id]);
            return $this->render('viewdepartment', ['model'=>$model, 'jobs'=>$jobs]);
        }
        return $this->redirect(['index']);
    }

    /**
     * 修改部门
     * @param $d_id
     * @return string|yii\web\Response
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionUpdateDepartment($d_id)
    {
        $this->getView()->title = '编辑部门';
        $request = yii::$app->getRequest();
        if(!$model = Department::findOne(['d_id' => $d_id])){
            return $this->redirect(['index']);
        }
        if($request->getIsPost()){
            if($model->updateDepartment($request->post())){
                return $this->redirect(['view-department', 'd_id'=>$model->d_id]);
            }
        }
        return $this->render('updatedepartment', ['model' => $model]);
    }

    /**
     * 删除部门
     * @param $d_id
     * @return yii\web\Response
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionDeleteDepartment($d_id)
    {
        if(Department::deleteAll(['d_id'=>$d_id])){
            return $this->redirect(['index']);
        }
    }

    /**
     * 部门列表
     * @return string
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionIndex()
    {
        $this->getView()->title = '部门列表';
        $model = new Department();
        $query = $model->search(Yii::$app->getRequest()->getQueryParams());
        $querycount = clone $query;
        $pages = new yii\data\Pagination(['totalCount' => $querycount->count()]);
        $pages->pageSize = Yii::$app->params['page_size'];
        $data = $query->offset($pages->offset)->limit($pages->limit)->asArray()->all();
        return $this->render('index', [
            'sear' => $model->getAttributes(),
            'model' => $data,
            'pages' => $pages
        ]);
    }

    /**
     * 新增职位
     * @param $d_id
     * @return string|yii\web\Response
     * @author 涂鸿 <hayto@foxmail.com>
     */
    /*public function actionCreateJob($d_id)
    {
        $this->getView()->title = '创建职位';
        $model = new Jobs();
        $request = Yii::$app->getRequest();
        if($request->getIsPost()){
            if($model->createJob($d_id, $request->post())){
                return $this->redirect(['list-job', 'd_id'=>$d_id]);
            }
        }

        if($d_name = Department::find()->select('d_name')->where(['d_id'=>$d_id])->scalar()) {
            return $this->render('createjob', ['model' => $model, 'd_name' => $d_name]);
        }
    }*/

    /**
     * 新增职位
     * @param $d_id
     * @return string|yii\web\Response
     * @author Bruce
     */
    public function actionCreateJob_bak($d_id)
    {
        $this->getView()->title = '创建职位';
        $request = Yii::$app->getRequest();
        $j_name = $request->post('j_name');
        if($request->getIsAjax()) {
            try {
                Yii::$app->getResponse()->format = 'json';
                if(!Department::find()->where(['d_id'=>$d_id])->exists()){
                    throw new CustomException('非法操作！');
                }
                if (!empty($j_name) === false) {
                    throw new CustomException('请填写职位名称！');
                }
                if (Jobs::find()->where(['j_name' => $j_name, 'j_department_id' => $d_id])->exists()) {
                    throw new CustomException('职位已存在！');
                }
                $model = new Jobs();
                $model->j_department_id = $d_id;
                $model->j_name = $j_name;
                if (!$model->save()) {
                    throw new CustomException('添加失败');
                }
                return ['status' => 1, 'message' => '添加成功'];
            } catch (CustomException $e) {
                return ['status' => 0, 'message' => $e->getMessage()];
            } catch (yii\base\Exception $e) {
                return ['status' => 0, 'message' => '系统错误'];
            }
        }
    }

    /**
     * 编辑职位
     * @param $d_id
     * @return string|yii\web\Response
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionUpdateJob($d_id, $j_id)
    {
        $this->getView()->title = '修改职位';
        $model = Jobs::find(['j_department_id'=>$d_id, 'j_id'=>$j_id])->one();
        if(!$model){
            return $this->redirect(['index']);
        }
        $request = Yii::$app->getRequest();
        if($request->getIsPost()){
            if($model->updateJob($request->post())){
                return $this->redirect(['view-job', 'd_id'=>$model->d_id]);
            }
        }

        if($d_name = Department::find()->select('d_name')->where(['d_id'=>$d_id])->scalar()) {
            return $this->render('updatejob', ['model' => $model, 'd_name' => $d_name]);
        }
    }

    /**
     * 编辑职位
     * @param $d_id
     * @return string|yii\web\Response
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionUpdateJob_bak($d_id, $j_id)
    {
        $this->getView()->title = '修改职位';
        $request = Yii::$app->getRequest();
        $j_name = $request->post('j_name');
        if ($request->getIsAjax()) {
            try {
                Yii::$app->getResponse()->format = 'json';

                if (!Department::find()->where(['d_id' => $d_id])->exists()) {
                    throw new CustomException('非法操作！');
                }
                if (!empty($j_name) === false) {
                    throw new CustomException('请填写职位名称！');
                }
                if (Jobs::find()->where(['j_name' => $j_name, 'j_department_id' => $d_id])->exists()) {
                    throw new CustomException('职位已存在');
                }
                $model_one = Jobs::find()->where(['j_department_id' => $d_id, 'j_id' => $j_id])->one();
                if (!$model_one) {
                    throw new CustomException('编辑失败!');
                }
                $model_one->j_name = $j_name;

                if (!$model_one->save()) {
                    throw new CustomException('编辑失败');
                }
                return ['status' => 1, 'message' => '保存成功'];
            } catch (CustomException $e) {
                return ['status' => 0, 'message' => $e->getMessage()];
            } catch (yii\base\Exception $e) {
                return ['status' => 0, 'message' => '系统错误'];
            }
        }
    }
    /**
     * 职位列表
     * @param $d_id
     * @return string|yii\web\Response
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionListJob($d_id)
    {
        if(!$d_name = Department::find()->select('d_name')->where(['d_id'=>$d_id])->scalar()) {
            return $this->redirect(['index']);
//            return $this->render('updatejob', ['model' => $model, 'd_name' => $d_name]);
        }
        $this->getView()->title = $d_name . '';
        $model = Jobs::find()->where(['j_department_id'=>$d_id])->all();
        if(!$model){
            return $this->redirect(['index']);
        }
        return $this->render('listjob', ['model'=>$model, 'd_id'=>$d_id]);
    }
    /**
     * 删除职位
     * @param $d_id
     * @return yii\web\Response
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionDeleteJob($j_id, $d_id)
    {
        if(Jobs::deleteAll(['j_id'=>$j_id])){
            return $this->redirect(['list-job', 'd_id' => $d_id]);
        }
    }

    /**
     * 删除职位
     * @param $d_id
     * @return yii\web\Response
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionDeleteJob_bak($j_id)
    {
        Yii::$app->getResponse()->format = 'json';
        if (Jobs::deleteAll(['j_id' => $j_id])) {
            return ['status' => 1, 'message' => '删除成功'];
        }
    }


    /**
     * 获取部门下所有员工
     * @param $d_id
     * @return string
     * @author too <hayto@foxmail.com>
     */
    public function actionAllUser($d_id)
    {
        $query = (new yii\db\Query())->from(User::tableName())
            ->where(['department_id'=>$d_id])->andWhere(['!=', 'status', User::STATUS_DELETE]);

        $queryCount = clone $query;
        $pages = new yii\data\Pagination(['totalCount'=>$queryCount->count()]);
        $pages->pageSize = Yii::$app->params['page_size'];
        $data = $query->orderBy(['created_at'=>SORT_DESC])->offset($pages->offset)->limit($pages->limit)->all();
        return $this->render('all-user', ['data'=>$data, 'pages'=>$pages]);
    }

}