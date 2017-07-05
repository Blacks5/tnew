<?php

/**
 * 易极付 回款接口
 * 共4个
 *
 * Created by PhpStorm.
 * User: too
 * Date: 2017/7/4
 * Time: 15:32
 * @author too <hayto@foxmail.com>
 */
namespace common\tools\yijifu;

use \yii\httpclient\Client as httpClient;

class Sign
{


    public $api;

    public function __construct()
    {
        $this->partnerId = \Yii::$app->params['yijifu']['partnerId'];
        $this->privateKey = \Yii::$app->params['yijifu']['privateKey'];
        $this->api = \Yii::$app->params['yijifu']['api'];
    }

    /**
     * 签约用户
     *
    $data = [
    'merchOrderNo'=>'1234',// 商户订单号
    'merchContractNo'=>'1234', // 商户签约合同号
    'merchContractImage'=> "", // 签约合同照片 支持jpg jpeg bmp png pdf'
    'realName'=>'李四', // 借款人真实姓名
    'bankCardNo'=>'', // 借款人银行卡号
    'certNo'=>'510623198812250210', // 借款人身份证号
    'mobileNo'=>'18990232122', // 借款人手机，用于发通知短信
    'productName'=>'分期一号', //  // 产品名称，将显示在用户短信中
    'loanAmount'=>12, // 借款金额 【可不填】显示在用户短信中
    'totalRepayAmount'=>5888, // 应还总金额 包括所有的各种费用
    ];
     * @author too <hayto@foxmail.com>
     */
    public function signUser(Array $data)
    {
        $_data = [
            'partnerId'=>$this->partnerId,
            'protocol'=>$this->protocol,
            'version'=>$this->version,
            'orderNo'=>str_replace('.', '', microtime(true)). mt_rand(1000000, 9999999), // 请求流水号，和业务无关，故使用微秒时间戳即可
            'signType'=>$this->signType,
            'service'=>'fastSign', // 服务码

        ];
        $data = array_merge($_data, $data);
        ksort($data);

        $wait_sign = urldecode(http_build_query($data)). $this->privateKey;
        $sign = md5($wait_sign);
        $data['sign'] = $sign;
        echo "<hr>";
        var_dump($data);
        $http_client = new httpClient();
        $response = $http_client->post($this->api, $data)/*->setFormat(httpClient::FORMAT_JSON)*/->send();
//        var_dump($response);die;
        if($response->getIsOk()){
            return $response->getData();
        }
        return false;
    }

    /**
     * 查询签约用户
     *
     *
     * 服务码 fastSignQuery
     *
     * @author too <hayto@foxmail.com>
     */
    public function querySignedUser($merchOrderNo)
    {
        $_data = [
            'partnerId'=>$this->partnerId,
            'protocol'=>$this->protocol,
            'version'=>$this->version,
            'orderNo'=>str_replace('.', '', microtime(true)). mt_rand(1000000, 9999999), // 请求流水号，和业务无关，故使用微秒时间戳即可  '149923342892676163263',//
            'signType'=>$this->signType,
            'service'=>'fastSignQuery',
            'merchOrderNo'=>$merchOrderNo
        ];
        ksort($_data);
        $sign = md5(urldecode(http_build_query($_data)). $this->privateKey);

        var_dump(urldecode(http_build_query($_data)). $this->privateKey, $sign);

        $_data['sign'] = $sign;
        echo "<pre>";
        var_dump($_data);
        $http_client = new httpClient();
//        p($_data);die;
        $response = $http_client->post($this->api, $_data)/*->setFormat(httpClient::FORMAT_JSON)*/->send();
//        var_dump($response);die;
        if($response->getIsOk()){
            return $response->getData();
        }
        return false;
    }






}