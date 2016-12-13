<?php
/**
 * Created by PhpStorm.
 * Date: 16/9/3
 * Time: 16:35
 * @author 涂鸿 <hayto@foxmail.com>
 */

namespace api\components;

use yii;
use yii\filters\auth\AuthMethod;
use yii\web\UnauthorizedHttpException;
class AuthApi extends AuthMethod
{
//    public $optional = ['upload-pic']; // 这里的方法不需要登录  【业务：必须要登录才有用户id】
    public $tokenParam = 'access-token';
    public function authenticate($user, $request, $response)
    {
        $accessToken = $request->get($this->tokenParam);
        if (is_string($accessToken)) {
            $identity = $user->loginByAccessToken($accessToken, get_class($this));
            if ($identity !== null) {
                return $identity;
            }
        }
        if ($accessToken !== null) {
            throw new yii\web\UnauthorizedHttpException('token已失效,请重新登录');
        }

        return null;
    }

    /**
     * @inheritdoc
     */
    public function handleFailure($response)
    {
        throw new UnauthorizedHttpException('杯具啊，太久了，用户标示为null');
    }
}