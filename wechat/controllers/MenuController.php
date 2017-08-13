<?php
/**
 * Created by PhpStorm.
 * User: too
 * Date: 2017/8/13
 * Time: 21:14
 * @author too <hayto@foxmail.com>
 */

namespace wechat\controllers;


use EasyWeChat\Foundation\Application;
use yii\web\Controller;

class MenuController extends Controller
{
    /**
     * 添加默认菜单
     * @author too <hayto@foxmail.com>
     */
    public function actionCreateDefaultMenu()
    {
        $config = \Yii::$app->params['wechat'];
        $app = new Application($config);

        $menu = [
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
        $ret = $app->menu->add($menu);
        echo "<pre>";
        echo "add menu seccuss";
        var_dump($ret);
    }

    /**
     * 添加个性化菜单
     * @author too <hayto@foxmail.com>
     */
    public function actionCreateCustomizationMenu()
    {
        $config = \Yii::$app->params['wechat'];
        $app = new Application($config);

        $menu = [
            [
                "type" => "click",
                "name" => "天1",
                "key"  => "V1001_TODAY_MUSIC"
            ],
            [
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
            ],
        ];

        // 每个参数都是非必填的，但不能全部为空
        $matchRule = [
//                "tag_id"=>"2", // 用户标签ID
            "sex"=>"1", // 男1 女2
            "country"=>"中国",
            "province"=>"四川",
            "city"=>"成都",
//                "client_platform_type"=>"2", //IOS(1), Android(2),Others(3)
            "language"=>"zh_CN"
        ];
        $ret = $app->menu->add($menu, $matchRule);
        echo "<pre>";
        echo "add menu seccuss";
        var_dump($ret);
    }

    /**
     * 获取所有菜单
     * @author too <hayto@foxmail.com>
     */
    public function actionGetAllMenu()
    {
        $config = \Yii::$app->params['wechat'];
        $app = new Application($config);

        $menu = $app->menu->all();
        echo "<pre>";
        var_dump($menu);
    }

    /**
     * 通过菜单ID删除菜单
     * @param $menu_id
     * @author too <hayto@foxmail.com>
     */
    public function actionDelMenuById($menu_id)
    {
        if(false === !empty($menu_id)){
            echo "请输入要删除的菜单ID";
            die;
        }
        $config = \Yii::$app->params['wechat'];
        $app = new Application($config);

        $menu = $app->menu->destroy($menu_id);
        echo "<pre>";
        var_dump($menu);
    }
}