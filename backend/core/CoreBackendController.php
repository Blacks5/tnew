<?php
/**
 * Created by PhpStorm.
 * Date: 2016/10/8
 * Time: 22:31
 * @author 涂鸿 <hayto@foxmail.com>
 */
namespace backend\core;
use yii;
use common\core\CoreController;
class CoreBackendController extends CoreController
{
    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            if (\yii::$app->getUser()->getIsGuest()) {
                return yii::$app->getResponse()->redirect(['site/login']);
                //return false;
            }
            return true;
        }
        return false;
    }
}