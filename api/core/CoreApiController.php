<?php
/**
 * Created by PhpStorm.
 * User: too
 * Date: 2016/10/14
 * Time: 17:34
 * @author 涂鸿 <hayto@foxmail.com>
 */
namespace api\core;

use api\components\AuthApi;
use yii;
use common\core\CoreCommonController;

class CoreApiController extends CoreCommonController
{
    // 关闭csrf验证
//    public $enableCsrfValidation = false;
//    public $tokenParam = 'access-token';
//    public function beforeAction($action)
//    {
//        if(parent::beforeAction($action)){
//            $user = Yii::$app->getUser();
//            $token = Yii::$app->getRequest()->get($this->tokenParam);
//            $identity = $user->loginByAccessToken($token, null);
//            if($identity !== null) return $identity;
//            if($token !== null) throw new yii\web\UnauthorizedHttpException('token已失效,请重新登录');
//            return null;
//        }
//    }

    /**
     * 统一认证登录
     * @return array
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class'=>AuthApi::className()
        ];
        return $behaviors;
    }
}