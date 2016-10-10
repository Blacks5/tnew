<?php
/**
 * Created by PhpStorm.
 * User: too
 * Date: 2016/10/9
 * Time: 15:14
 * @author 涂鸿 <hayto@foxmail.com>
 */
namespace backend\controllers;
use common\models\RbacPermission;
use common\models\RbacRole;
use yii;
use backend\core\CoreBackendController;

class SystemController extends CoreBackendController
{
    // 列出所有角色
    public function actionListallroles()
    {
//        Yii::$app->getResponse()->format = 'json';
        $data = (new yii\db\Query())->from(RbacRole::tableName())->all();
//        p($data);
        p($this->tree($data));
//        return $data;
    }

    public function tree(&$data, $pid=0, $level=0, $html='--|')
    {
        static $tree = [];
        foreach ($data as $k=>$v){
//            echo $pid. PHP_EOL;
            if($v['role_parent_id'] == $pid){
                $v['role_name'] = str_repeat($html, $level). $v['role_name'];
                $tree[] = $v;
                unset($data[$k]);
                $this->tree($data, $v['role_id'], $level+1, $html);
            }
        }
        return $tree;
    }

    // 创建角色
    public function actionCreaterole()
    {

    }

    // 删除角色
    public function actionDeleterole($role_id)
    {

    }

    // 列出所有权限（菜单）
    public function actionListAllPermission()
    {
        $data = RbacPermission::find()->asArray()->all();
        $data = $this->tree_permission($data, $pid=0, $level=0);
        return $this->render('list-all-permission', ['data'=>$data]);
    }

    private function tree_permission(&$data, $pid=0, $level=0, $html="==>>")
    {
        static $tree = [];
        foreach ($data as $k=>$v){
//            echo $pid. PHP_EOL;
            if($v['permission_parent_id'] == $pid){
                $v['permission_name'] = str_repeat($html, $level). $v['permission_name'];
                $tree[] = $v;
                unset($data[$k]);
                $this->tree_permission($data, $v['permission_id'], $level+1, $html);
            }
        }
        return $tree;
    }
    // 创建权限（菜单）
    public function actionCreatePermission()
    {
        $request = Yii::$app->getRequest();
        if($request->getIsGet()){
            return $this->render('create-permission');
        }
    }

    // 给角色分配权限
    public function actionAssignpermissiontorole()
    {

    }

    // 给用户分配角色
    public function actionAssignroletouser()
    {

    }
}