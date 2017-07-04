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
    public $protocol = "httpPost"; // 协议类型，默认httpPost
    public $service = ""; // 服务码
    public $version = "1.0"; // 服务版本，默认1.0
    public $partnerId = ""; // 签约的服务平台账号对应的合作方ID
    public $orderNo = ""; // 请求流水号
    public $signType = "MD5"; // 签名方式，目前默认MD5，也只支持MD5，注意要大写
    public $sign = ""; // 签名字符串
    public $returnUrl = ""; // 页面跳转返回URL，非跳转接口不需要此参数
    public $notifyUrl = ""; // 异步通知URL

    public $api = "http://merchantapi.yijifu.net/gateway.html";

    public function __construct()
    {
        $this->partnerId = \Yii::$app->params['yijifu']['partnerId'];
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
            'orderNo'=>1234,
            'signType'=>$this->signType,
            'sign'=>'',
            'service'=>'fastSign', // 服务码
            'operateType'=>'SIGN'  // 操作类型，默认SIGN签约，MODIFY_SIGN修改
        ];
        $data = array_merge($_data, $data);

        $http_client = new httpClient();
        $response = $http_client->post($this->api, $data)->send();
        if($response->getIsOk()){
            return $response->getData();
        }
        return false;
    }

    /**
     * 查询签约用户
     *
     *
     * 服务码fastSignQuery
     *
     * @author too <hayto@foxmail.com>
     */
    public function querySignedUser()
    {

    }

    /**
     * 发起代扣
     *
     *
     * 服务码fastDeduct
     *
     * @author too <hayto@foxmail.com>
     */
    public function deduct()
    {

    }

    /**
     * 查询代扣
     *
     *
     * 服务码fastDeductQuery
     *
     *
     * @author too <hayto@foxmail.com>
     */
    public function queryDeduct()
    {

    }




}