<?php
/**
 * Created by PhpStorm.
 * User: too
 * Date: 2017/8/9
 * Time: 23:01
 * @author too <hayto@foxmail.com>
 */

namespace wechat\controllers;


use EasyWeChat\Foundation\Application;
use yii\web\Controller;

class WechatController extends Controller
{
    public $enableCsrfValidation = false;
    public function actionIndex()
    {
        $config = \Yii::$app->params['wechat'];
        $app = new Application($config);

        $app->server->setMessageHandler(function($message){
//            var_dump($message);
            return "您好！欢迎关注!";
        });

        return $app->server->serve()->send();
    }


    /**
     * 微信授权回调
     * @return \yii\web\Response
     * @author too <hayto@foxmail.com>
     */
    public function actionOauthCallback()
    {
        $config = \Yii::$app->params['wechat'];
        $app = new Application($config);

        // Overtrue\Socialite\User
        $user = $app->oauth->user();
        $session = \Yii::$app->getSession();
        $session->set('wechat_user', $user);

        $targetUrl = empty($session->get('target_url'))? '/': $session->get('target_url');
        return $this->redirect($targetUrl);
    }




    /**
     * 需要授权的页面
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @author too <hayto@foxmail.com>
     */
    /*public function actionTestLogin()
    {
        Wechat::Login(['wechat/test-login']);

        $session = \Yii::$app->getSession();
        $user = $session->get('wechat_user');

        echo "<pre>";
        var_dump($user);
    }*/
}