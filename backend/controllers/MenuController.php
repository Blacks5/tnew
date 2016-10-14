<?php

namespace backend\controllers;

use Yii;
use backend\models\Menu;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\models\AuthItem;
use yii\data\Pagination;

/**
 * MenuController implements the CRUD actions for Menu model.
 */
class MenuController extends Controller
{
    /**
     * @inheritdoc
     */
    /*public function behaviors()
    {
        $action = Yii::$app->controller->module->requestedRoute;
        if(\Yii::$app->user->can($action)){
            return [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['GET'],
                    ],
                ],
            ];
        }else{
            //throw new \yii\web\UnauthorizedHttpException('对不起，您现在还没获此操作的权限');
            echo '<div style="margin: 100px auto;text-align: center;background-color: #1ab394; color: #ffffff;width: 500px;height: 50px;line-height: 50px;border-radius: 5px;;"><h4>对不起，您现在还没获此操作的权限</h4></div>';
        }

    }*/

    /**
     * Lists all Menu models.
     * @return mixed
     */
    public function actionIndex()
    {
        $menu = new Menu();
        $menu = $menu->getMenuList();

        return $this->render('index', [
            'menu' => $menu
        ]);
    }

    /**
     * Displays a single Menu model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Menu model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Menu();
        $menu = $model->getAllMenu();
        $menuArr = array('0'=>"顶级菜单");
        foreach($menu as $v){
            $menuArr[$v['id']] = $v['name'];
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //增加菜单同时增加权限
            $post = Yii::$app->request->post();
            $name = $post['Menu']['route'];
            if(!empty($name)){
                $auth = Yii::$app->authManager;
                $createPost = $auth->createPermission($name);
                $createPost->description = '创建了[' . $name. ']权限';
                $auth->add($createPost);

                //将权限赋给角色
                $parent = $auth->createRole('超级管理员');                //创建角色对象
                $child = $auth->createPermission($name);     //创建权限对象
                $auth->addChild($parent, $child);                           //添加对应关系
            }
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
                'menuArr' => $menuArr,
            ]);
        }
    }

    /**
     * Updates an existing Menu model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $menu = $model->getAllMenu();
        $menuArr = array('0'=>"顶级菜单");
        foreach($menu as $v){
            $menuArr[$v['id']] = $v['name'];
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
                'menuArr' => $menuArr,
            ]);
        }
    }

    /**
     * Deletes an existing Menu model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        //通过id找到router
        $MenuModel = new Menu();
        $name = $MenuModel->getRouteById($id);
        //删除菜单同时删除权限
        if(!empty($name)){
            $model = new AuthItem();
            $model->setScenario(AuthItem:: SCENARIOS_DELETE);
            $model-> name = $name;
            $res =  $model->romoveItem();
        }

        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    /**
     * Finds the Menu model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Menu the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Menu::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
