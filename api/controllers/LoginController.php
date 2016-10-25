<?php
/**
 * Created by PhpStorm.
 * User: too
 * Date: 2016/10/19
 * Time: 14:47
 * @author 涂鸿 <hayto@foxmail.com>
 */
namespace api\controllers;

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
        if(!$model->validate()){
            return ['status'=>0, 'message'=>'用户名或密码错误', 'data'=>[]];
        }
        if($model->login()){
            $user = Yii::$app->getUser()->getIdentity();
            $data = [
                'id'=>$user->id,
                'realname'=>$user->realname,
                'access_token'=>$user->access_token
            ];
            return ['status'=>1, 'message'=>'登录成功', 'data'=>$data];
        }
        return ['status'=>0, 'message'=>'登录异常', 'data'=>[]];
    }
}