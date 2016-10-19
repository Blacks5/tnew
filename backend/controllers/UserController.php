<?php

namespace backend\controllers;
use backend\core\CoreBackendController;
use backend\models\AuthItem;
use backend\models\Menu;
use backend\models\PasswordForm;
use common\components\Helper;
use common\models\Department;
use common\models\Jobs;
use common\models\User;
use yii\data\Pagination;
//use backend\models\User;
use common\models\UserSearch;

use Yii;
use yii\web\Response;

/**
 * 员工控制器
 * Class UserController
 * @package backend\controllers
 * @author 涂鸿 <hayto@foxmail.com>
 */
class UserController extends CoreBackendController
{
    //public $enableCsrfValidation = false;

    /*public function beforeAction($action)
    {
        $action = Yii::$app->controller->module->requestedRoute;
        if(\Yii::$app->user->can($action)){
            return true;
        }else{
            echo '<div style="margin: 100px auto;text-align: center;background-color: #1ab394; color: #ffffff;width: 500px;height: 50px;line-height: 50px;border-radius: 5px;;"><h4>对不起，您现在还没获此操作的权限</h4></div>';
        }
    }*/

    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * 获取子地区
     * @param $p_id
     * @return array
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionGetSubAddr($p_id)
    {
        Yii::$app->getResponse()->format = Response::FORMAT_JSON;
        return Helper::getSubAddr($p_id);
    }

    public function actionGetJobs($d_id)
    {
        Yii::$app->getResponse()->format = Response::FORMAT_JSON;
        return Jobs::getJobs($d_id);
    }

    /**
     * 员工列表
     * @return string
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionList()
    {
        $query = new UserSearch();
        $model = $query->search(Yii::$app->getRequest()->getQueryParams());
        $clone_model = clone $model;
        $pages = new Pagination(['totalCount' =>$clone_model->count(), 'pageSize' => '2']);
        $user = $model->joinWith('usergroup')->orderBy(['id'=>SORT_DESC])->offset($pages->offset)->limit($pages->limit)->all();
        return $this->render('list',[
            'sear'=>$query->getAttributes(),
            'user'=>$user,
            'pages' => $pages
        ]);
    }

    /**
     * 新增员工
     * @return string|\yii\web\Response
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionCreate()
    {
        Yii::$app->getView()->title = '新增员工';

        $model = new User();
        $model1 = new AuthItem();

        $auth = Yii::$app->getAuthManager();
        $item = $auth->getRoles(); // 获取所有角色
        $item_one = array_keys($item);
        $item_one = array_combine($item_one, $item_one);

        $request = Yii::$app->getRequest();
        if($request->getIsPost()){
            $post = $request->post();
            if($model->createUser($post)){
                // 分配部门角色
                $role = $auth->createRole($post['AuthItem']['name']);
                $auth->assign($role, $model->id);
                return $this->redirect(['list']);
            }
        }else {
            $all_province = Helper::getAllProvince();
            $all_departments = Department::getAllDepartments();
            return $this->render('create', [
                'model' => $model,
                'model1' => $model1,
                'item' => $item_one,
                'all_province'=>$all_province,
                'all_departments'=>$all_departments
            ]);
        }
    }

    /**
     * 更新员工
     * @return string|\yii\web\Response
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionUpdate(){
        $item_name = Yii::$app->request->get('item_name');
        $id = Yii::$app->request->get('id');
        $model = User::find()->joinWith('usergroup')->where(['id'=>$id])->one();
        $auth = Yii::$app->authManager;
        $item = $auth->getRoles();
        $itemArr =array();
        foreach($item as $v){
            $itemArr[] .= $v->name;
        }
        foreach($itemArr as $key=>$value)
        {
            $item_one[$value]=$value;
        }
        $model1 = $this->findModel($id);
        if ($model1->load(Yii::$app->request->post())) {
            $post = Yii::$app->request->post();
            //更新密码
            if(!empty($post['User']['auth_key_new'])){
                $model1->setPassword($post['User']['auth_key_new']);
                $model1->generateAuthKey();
            }else{
                $model1->auth_key = $post['User']['auth_key'];
            }
            $model1->save($post);
            //分配角色
            $role = $auth->createRole($post['AuthAssignment']['item_name']);                //创建角色对象
            $user_id = $id;                                             //获取用户id，此处假设用户id=1
            $auth->revokeAll($user_id);
            $auth->assign($role, $user_id);                           //添加对应关系

            return $this->redirect(['user/update', 'id' => $model1->id]);
        }

        $all_province = Helper::getAllProvince();
        $all_departments = Department::getAllDepartments();
        return $this->render('update',[
            'model' => $model,
            'item' => $item_one,

            'all_province'=>$all_province,
            'all_departments'=>$all_departments
        ]);
    }

    /**
     * 删除员工
     * @param $id
     * @return \yii\web\Response
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->status = 0;
        $model->save(false);
        return $this->redirect(['list']);
    }

    /**
     * 修改员工密码
     * @param $id
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionModPwd($id)
    {

    }

    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
