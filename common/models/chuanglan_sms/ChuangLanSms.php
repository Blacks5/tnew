<?php

/**
 * Created by PhpStorm.
 * Date: 16/9/2
 * Time: 19:04
 * @author 涂鸿 <hayto@foxmail.com>
 */
namespace common\models\chuanglan_sms;
use yii;
class ChuangLanSms
{
    const SEND_SMS_URL = 'http://222.73.117.169/msg/HttpBatchSendSM';
    const QUERY_BALANCE_URL = 'http://222.73.117.169/msg/QueryBalance';
    const ACCOUNT = 'N7704903';
    const PASSWORD = 'Ps8d27dc';


    /**
     * 发送短信
     *
     * @param string $mobile 手机号码
     * @param string $msg 短信内容
     * @param string $needstatus 是否需要状态报告
     * @param string $extno   扩展码，可选
     */
    public function sendSMS( $mobile, $msg, $needstatus = 'true', $extno = '') {
        //创蓝接口参数
        $postArr = array (
            'account' => self::ACCOUNT,
            'pswd' => self::PASSWORD,
            'msg' => $msg,
            'mobile' => $mobile,
            'needstatus' => $needstatus,
            'extno' => $extno
        );

        $result = $this->curlPost( self::SEND_SMS_URL , $postArr);
        return $result;
    }

    /**
     * 查询额度
     * @return mixed
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function queryBalance() {
        //查询参数
        $postArr = array (
            'account' => self::ACCOUNT,
            'pswd' => self::PASSWORD,
        );
        $result = $this->curlPost(self::QUERY_BALANCE_URL, $postArr);
        return $result;
    }

    /**
     * 处理返回值
     * @param $result
     * @return array
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function execResult($result){
        $result=preg_split("/[,\r\n]/",$result);
        return $result;
    }

    /**
     * 通过CURL发送HTTP请求
     * @param string $url  //请求URL
     * @param array $postFields //请求参数
     * @return mixed
     */
    private function curlPost($url,$postFields){
        $postFields = http_build_query($postFields);
        $ch = curl_init ();
        curl_setopt ( $ch, CURLOPT_POST, 1 );
        curl_setopt ( $ch, CURLOPT_HEADER, 0 );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt ( $ch, CURLOPT_URL, $url );
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $postFields );
        $result = curl_exec ( $ch );
        curl_close ( $ch );
        return $result;
    }
}