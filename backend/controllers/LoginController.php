<?php
/**
 * Created by PhpStorm.
 * User: too
 * Date: 2016/10/14
 * Time: 17:38
 * @author 涂鸿 <hayto@foxmail.com>
 */
namespace backend\controllers;

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
        Yii::$app->getView()->title = '登录';
        if (!Yii::$app->getUser()->getIsGuest()) {
            return $this->goHome();
        }
        usleep(500);
        $model = new LoginForm();
        if ($model->load(Yii::$app->getRequest()->post()) && $model->login()) {
            $session = Yii::$app->getSession();
            $session->set('logintime', $_SERVER['REQUEST_TIME']); // 记录一个登录时间，用于判断不重复登录
            $model->loginLog();
            return $this->goBack();
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