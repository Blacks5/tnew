<?php

namespace backend\controllers;
use backend\core\CoreBackendController;
use backend\models\Log;
use backend\models\Menu;
use backend\models\PasswordForm;
use yii\data\Pagination;

use Yii;

//class IndexController extends \yii\web\Controller
class IndexController extends CoreBackendController
{
    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        return $this->render('index');
    }


    public function actionWelcome()
    {
        //$action = Yii::$app->controller->module->requestedRoute;
        //var_dump(\Yii::$app->user->can('/site/index'));exit;
        //最近登录记录
        $log = Log::find()->limit(Yii::$app->params['page_size'])->orderBy('id desc')->asArray()->all();
        return $this->render('welcome',[
            'log' => $log,
        ]);
    }
}

