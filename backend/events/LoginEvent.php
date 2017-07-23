<?php
/**
 * Created by PhpStorm.
 * User: too
 * Date: 2016/11/7
 * Time: 13:24
 * @author 涂鸿 <hayto@foxmail.com>
 */
namespace backend\events;
use backend\models\Log;
use yii;

class LoginEvent
{
    /**
     * 登录时写登录日志
     *
     * 绑定事件
     * Yii::$app->getUser()->on(yii\web\User::EVENT_AFTER_LOGIN, ['backend\events\LoginEvent', 'loginLog']);
     *
     * @param $event
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public static function writeLoginLog($event)
    {
        // 获取User对象
        $identity = $event->identity;

        // 记录上次登录时间
        $identity->last_login_at = $_SERVER['REQUEST_TIME'];
        $identity->save(false);

        // session也记录一个
        // 在 mdm\admin\components\AccessControl::$beforeAction
        $session = Yii::$app->getSession();
        $session->set('logintime', $_SERVER['REQUEST_TIME']);

        // 写登录日志
        $userIP = Yii::$app->getRequest()->getUserIP();
        if(Yii::$app->getUser()->identity->username !== 'admin') {
            Yii::$app->getDb()->createCommand()->insert(
                Log::tableName(), [
                'username' => $identity->username,
                'create_time' => $_SERVER['REQUEST_TIME'],
                'ip' => $userIP,
                'data' => '',
            ])->execute();
        }
    }

    /**
     * 2016-11-07 弃用本方法，合并到writeLoginLog()方法里了，减少一个事件绑定，为了性能
     *
     * 更新上次登录时间
     * @param $event
     * @author 涂鸿 <hayto@foxmail.com>
     */
    /*public function updateLastLoginTime($event)
    {
        // 获取User对象
        $identity = $event->identity;

        // 记录上次登录时间
        $identity->last_login_at = $_SERVER['REQUEST_TIME'];
        $identity->save(false);

        // session也记录一个
        // 在 mdm\admin\components\AccessControl::$beforeAction 使用
        $session = Yii::$app->getSession();
        $session->set('logintime', $_SERVER['REQUEST_TIME']);
    }*/
}