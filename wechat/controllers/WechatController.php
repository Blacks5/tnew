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

//            $app->menu->destroy();
            $buttons = [
                [
                    "type" => "click",
                    "name" => "天",
                    "key"  => "V1001_TODAY_MUSIC"
                ],
                [
                    "name"       => "牛",
                    "sub_button" => [
                        [
                            "type" => "view",
                            "name" => "金融",
                            "url"  => "http://www.baidu.com/"
                        ],
                        [
                            "type" => "click",
                            "name" => "赞一下我们",
                            "key" => "V1001_GOOD"
                        ]
                    ],
                ],
            ];

            $buttons_gx = [
                [
                    "type" => "click",
                    "name" => "天1",
                    "key"  => "V1001_TODAY_MUSIC"
                ],
                /*[
                    "name"       => "牛1",
                    "sub_button" => [
                        [
                            "type" => "view",
                            "name" => "金融1",
                            "url"  => "http://www.baidu.com/"
                        ],
                        [
                            "type" => "click",
                            "name" => "赞一下我们1",
                            "key" => "V1001_GOOD"
                        ]
                    ],
                ],*/
            ];
            $matchRule = [
//                "tag_id"=>"2",
                "sex"=>"1",
                "country"=>"中国",
                "province"=>"四川",
                "city"=>"成都",
//                "client_platform_type"=>"2",
                "language"=>"zh_CN"
            ];
//            var_dump($app->menu->add($buttons_gx, $matchRule));
//            var_dump($app->menu->add($buttons));
//            var_dump($app->menu->destroy(415564445));
            var_dump($app->user_tag->lists());
            $ret = ob_get_clean();
            return $ret;
//            return $message->MsgType. "您好！欢迎关注!";
        });

        $response = $app->server->serve();

        return $response->send();
    }
}