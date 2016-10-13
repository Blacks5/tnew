<?php
/**
 * Created by PhpStorm.
 * User: too
 * Date: 2016/10/9
 * Time: 15:14
 * @author 涂鸿 <hayto@foxmail.com>
 */
namespace backend\controllers;
use backend\models\Menu;
use common\models\RbacPermission;
use common\models\RbacRole;
use yii;
use backend\core\CoreBackendController;

class SystemController extends CoreBackendController
{

    public function actionGetAssignedMenu($userid)
    {
        p(gethostbyname('baidu.com'), $this->GetHttpStatusCode('www.west.cn'));
        $all_menu = Menu::find()->asArray()->indexBy('id')->all();
        p($all_menu);
        p(Yii::$app->getAuthManager()->getPermissionsByUser($userid));
    }

    function GetHttpStatusCode($url){
        $curl = curl_init();
        curl_setopt($curl,CURLOPT_URL,$url);//获取内容url
        curl_setopt($curl,CURLOPT_HEADER,1);//获取http头信息
        curl_setopt($curl,CURLOPT_NOBODY,1);//不返回html的body信息
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);//返回数据流，不直接输出
        curl_setopt($curl,CURLOPT_TIMEOUT,30); //超时时长，单位秒
        curl_exec($curl);
        $rtn= curl_getinfo($curl,CURLINFO_HTTP_CODE);
        curl_close($curl);
        return  $rtn;
    }
    /**
     * 列出所有角色
     * @return string
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionListAllRoles()
    {
        $data = (new yii\db\Query())->from(RbacRole::tableName())->all();
        $data = $this->tree($data);
        return $this->render('list-all-roles', ['data'=>$data]);
    }

    private function tree(&$data, $pid=0, $level=0, $html='==>>')
    {
        static $tree = [];
        foreach ($data as $k=>$v){
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

    /**
     * 列出所有权限（菜单）
     * @return string
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionListAllPermissions()
    {
        $data = RbacPermission::find()->asArray()->all();
        $data = $this->tree_permission($data, $pid=0, $level=0);
        return $this->render('list-all-permissions', ['data'=>$data]);
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