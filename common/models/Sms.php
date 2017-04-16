<?php
/**
 * Created by PhpStorm.
 * User: too
 * Date: 2016/8/29
 * Time: 11:42
 * @author 涂鸿 <hayto@foxmail.com>
 */

namespace common\models;

use api\components\CustomApiException;
use common\models\chuanglan_sms\ChuangLanSms;
use yii;

class Sms
{
    private $sender=null;
    private $cache_handle=null;

    public function __construct()
    {
        if ($this->sender === null) {
            $this->sender = new ChuangLanSms();
        }
        $this->cache_handle = Yii::$app->getCache();
    }

    /**
     * 发送短信
     * @param $phone
     * @param $msg
     * @param string $needstatus
     * @return bool
     * @throws CustomException
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function sendSms($phone, $msg, $needstatus = 'true')
    {
        $patt = '/^1(3[0-9]|4[57]|5[0-35-9]|7[013678]|8[0-9])\d{8}$/';
        if (!preg_match($patt, $phone)) {
            throw new CustomApiException('请填写正确的手机号码');
        }

        if ($this->cache_handle->exists($phone)) {
            throw new CustomApiException(Yii::$app->params['sms_timeout']/60 . '分钟内只能发送一次验证码');
        }
        // 发送短信并解析结果
        $code = mt_rand(100000, 999999);
//        $code = 1234;
        $msg .= $code;

        $result = $this->sender->sendSMS($phone, $msg, $needstatus);
        $result = $this->sender->execResult($result);
        $result[1] = 0;

        if (isset($result[1]) && $result[1] == 0) {
            return $this->cache_handle->set($phone, $code, Yii::$app->params['sms_timeout']); // 发送成功
        }

        throw new CustomApiException('发送失败,请重试.');
    }

    /**
     * 验证短信码
     * @param $phone
     * @param $code
     * @return bool
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function verify($phone, $code)
    {
        return $this->cache_handle->get($phone) == $code;
    }
}