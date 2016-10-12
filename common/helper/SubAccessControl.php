<?php
/**
 * Created by PhpStorm.
 * User: too
 * Date: 2016/9/20
 * Time: 15:12
 * @author 涂鸿 <hayto@foxmail.com>
 */

namespace common\helper;

use mdm\admin\components\AccessControl;
use yii\web\ForbiddenHttpException;

class SubAccessControl extends AccessControl
{
    protected function denyAccess($user)
    {
        /*if ($user->getIsGuest()) {
            return $user->loginRequired();
        }*/
//        if(\Yii::$app->getRequest()->getIsAjax()){
//            return ['stat'];
//        }else{
        throw new ForbiddenHttpException(\Yii::t('yii', 'You are not allowed to perform this action.'));
//        }
    }
}