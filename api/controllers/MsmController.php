<?php
/**
 * Created by PhpStorm.
 * User: too
 * Date: 2016/8/29
 * Time: 11:38
 * @author 涂鸿 <hayto@foxmail.com>
 */

namespace api\controllers;

use api\components\CustomApiException;
use api\core\CoreApiController;
use common\models\Sms;
use yii\base\Exception;

class MsmController extends CoreApiController
{

    /**
     * 发送手机短信
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionSendMsm($phone)
    {
        $msg = \Yii::$app->params['submit_orders'];
        try{
            \Yii::$app->getResponse()->format = 'json';
            $sender = new Sms();
            if($sender->sendSms($phone, $msg)){
                return ['status'=>1, 'message'=> '发送成功'];
            }
            throw new CustomApiException('发送失败-');
        }catch(CustomApiException $e){
            return ['status'=>0, 'message'=>$e->getMessage()];
        }catch(Exception $e){
            return ['status'=>0, 'message'=>'系统错误'];
        }
    }

}