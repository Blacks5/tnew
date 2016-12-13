<?php
/**
 * Created by PhpStorm.
 * User: too
 * Date: 2016/10/14
 * Time: 17:38
 * @author 涂鸿 <hayto@foxmail.com>
 */
namespace backend\controllers;

use backend\events\LoginEvent;
use common\models\LoginForm;
use yii;
use common\core\CoreCommonController;

/**
 * 登录 退出 控制器
 * 唯一一个继承自CoreCommonController的控制器
 * Class LoginController
 * @package backend\controllers
 * @author 涂鸿 <hayto@foxmail.com>
 */
class LoginController extends CoreCommonController
{
    /**
     * 登录
     * @return string|yii\web\Response
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionLogin()
    {
//        phpinfo();die;
        $_SESSION['a'] = 1;
        Yii::$app->getView()->title = '登录';
        if (!Yii::$app->getUser()->getIsGuest()) {
            return $this->goHome();
        }
        usleep(500);
        $model = new LoginForm();
        if ($model->load(Yii::$app->getRequest()->post())) {

            // 先綁定事件hander \yii\web\User里会trigger
            // 写登录日志
            Yii::$app->getUser()->on(yii\web\User::EVENT_AFTER_LOGIN, ['backend\events\LoginEvent', 'writeLoginLog']);

            // 然后执行
            $model->login();
//            p($_SESSION);
            return $this->redirect(['site/index']);
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * 退出登录
     * @return yii\web\Response
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionLogout()
    {
        Yii::$app->getUser()->logout();
        return $this->goHome();
    }
}