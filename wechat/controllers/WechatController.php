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
    public function actionIndex()
    {
        echo 1;die;
        $config = \Yii::$app->params['wechat'];
        $app = new Application($config);

        $app->server->setMessageHandler(function($message){
            return "您好！欢迎关注天牛金融!";
        });
        $response = $app->server->serve();

        $response->send();
    }
}