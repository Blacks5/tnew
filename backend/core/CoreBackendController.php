<?php
/**
 * Created by PhpStorm.
 * User: too
 * Date: 2016/10/14
 * Time: 17:34
 * @author 涂鸿 <hayto@foxmail.com>
 */
namespace backend\core;

use yii;
use common\core\CoreCommonController;

class CoreBackendController extends CoreCommonController
{
    public function beforeAction($action)
    {
        Yii::$app->getView()->title = '天牛金融--后台';
        return parent::beforeAction($action);
    }
}