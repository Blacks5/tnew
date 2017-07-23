<?php
/**
 * Created by PhpStorm.
 * User: too
 * Date: 2016/10/19
 * Time: 14:47
 * @author 涂鸿 <hayto@foxmail.com>
 */
namespace api\controllers;

use common\models\User;
use yii;
use yii\rest\Controller;
use common\models\LoginForm;

/**
 * 登录控制器，不要判断登录，和其他控制器集成的父类不同
 * Class LoginController
 * @package api\controllers
 * @author 涂鸿 <hayto@foxmail.com>
 */
class LoginController extends Controller
{
    public $enableCsrfValidation = false;
    /**
     * 安卓端登录
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionLogin()
    {
        $request = Yii::$app->getRequest();
        $model = new LoginForm();
        $model->username = $request->post('username');
        $model->password = $request->post('password');
        usleep(500); // 防止暴力破解密码
        if(!$model->validate()){
            return ['status'=>0, 'message'=>'用户名或密码错误', 'data'=>[]];
        }
        if($model->login()){
            $user = Yii::$app->getUser()->getIdentity();

            // 新增判断：只有销售部能登录
            if (1 != $user['attributes']['id']){
                if(26 != $user['attributes']['department_id']){
                    Yii::$app->getUser()->logout();
                    return ['status'=>0, 'message'=>'没有登录权限', 'data'=>[]];
                }
            }


            // 登录成功，更新access_token
            if(null == User::updateAccessToken($user)){
                return ['status'=>0, 'message'=>'登录失败', 'data'=>[]];
            }

            $data = [
                'id'=>$user->id,
                'realname'=>$user->realname,
                'access_token'=>$user->access_token
            ];
            return ['status'=>1, 'message'=>'登录成功', 'data'=>$data];
        }
        return ['status'=>0, 'message'=>'登录失败', 'data'=>[]];
    }

    private function onlySalesCanLogin($userId)
    {

    }

    /**
     * @todo 还没使用
     * 给客户端返回api请求的地址
     * @return array
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionReturnUrl()
    {
//        $url = 'http://211.149.163.238:89/'; // online西数
        $url = 'http://119.23.15.90:89/'; // online阿里
//        $url = 'http://192.168.50.8:889'; // mac and thinkpad
//        $url = 'http://192.168.0.194:889/'; // win
        return ['status' => 1, 'message' => 'ok', 'data' => $url];
    }


}