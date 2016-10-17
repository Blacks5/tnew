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
    /*public function beforeAction($action)
    {
        if(parent::beforeAction($action)){
            if(Yii::$app->getUser()->getIsGuest()){
                return Yii::$app->getResponse()->redirect(['x/x']);
            }
            return true;
        }
        return false;
    }*/

}