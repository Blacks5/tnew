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

        /**
         *
         */

        $app->server->setMessageHandler(function($message) use($app){
//            $userinfo = $app->user->get($message['FromUserName']);

            ob_start();
            var_dump($message);
//            var_dump($userinfo);

            $ret = ob_get_clean();
            return $ret;
//            return $message->MsgType. "您好！欢迎关注!";
        });

        $response = $app->server->serve();

        return $response->send();
    }
}