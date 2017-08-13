<?php
/**
 * Created by PhpStorm.
 * User: too
 * Date: 2017/8/13
 * Time: 21:49
 * @author too <hayto@foxmail.com>
 */

namespace common\services;


use api\components\CustomApiException;
use common\components\CustomCommonException;
use common\models\Sms;
use common\models\User;

class Order
{
    public function addOrder($params)
    {
        // 判断验证码
        $verify = new Sms();
        if(!$verify->verify($params['c_customer_cellphone'], $params['verify_code'])){
            throw new CustomCommonException('验证码错误');
        }

        $session = \Yii::$app->getSession();
        $wechat_user = $session->get('wechat_user');
        $user = User::find()->where(['wechat_openid'=>$wechat_user['id']])->one()->toArray();
        if(User::STATUS_ACTIVE != $user['status']){
            throw new CustomCommonException('无权提交订单');
        }
        // 1写order_images表

        // 2写customer表

        // 3写orders表
        // 3-1生成订单号

        // 4写goods表
    }
}