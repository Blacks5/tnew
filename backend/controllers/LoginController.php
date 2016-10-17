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

class LoginController extends CoreCommonController
{
    /**
     * 登录
     * @return string|yii\web\Response
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionLogin()
    {
        Yii::$app->getView()->title = 'xxxx';
        if (!Yii::$app->getUser()->getIsGuest()) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->getRequest()->post()) && $model->login()) {
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