<?php
/**
 * Created by PhpStorm.
 * User: too
 * Date: 2017/8/13
 * Time: 19:50
 * @author too <hayto@foxmail.com>
 */

namespace wechat\Tools;


use EasyWeChat\Foundation\Application;

class Wechat
{
    /**
     * 微信登录
     * @param $callback_url 登陆后跳转的地址
     * @param $scopes snsapi_base | snsapi_userinfo | snsapi_login(开放平台)
     * @return bool
     * @author too <hayto@foxmail.com>
     */
    public static function Login(Array $callback_url, $scopes='snsapi_userinfo')
    {
        $session = \Yii::$app->getSession();
        $user = $session->get('wechat_user');

        // 没登录
        if(true === empty($user)){
            $config = \Yii::$app->params['wechat'];
            $app = new Application($config);
            $target_url = \Yii::$app->getUrlManager()->createAbsoluteUrl($callback_url);
            $session->set('target_url', $target_url);
            return $app->oauth->scopes([$scopes])->redirect()->send();
        }
        return true;
    }
}