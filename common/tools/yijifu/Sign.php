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


    /**
     * 签约用户
     *
     *
     * 服务码fastSign
     *
     *
     * @author too <hayto@foxmail.com>
     */
    public function signUser()
    {
        $this->service = "fastSign";
        $merchOrderNo = "1234"; // 商户订单号
        $merchContractNo = ""; // 商户签约合同号
        $merchContractImage = ""; // 签约合同照片 支持jpg jpeg bmp png pdf
        $realName = ""; // 借款人真实姓名
        $certNo = ""; // 借款人身份证号
        $bankCardNo = ""; // 借款人银行卡号
        $mobileNo = ""; // 借款人手机，用于发通知短信
        $productName = ""; // 产品名称，将显示在用户短信中
        $loanAmount = ""; // 借款金额 【可不填】显示在用户短信中
        $totalRepayAmount = ""; // 应还总金额 包括所有的各种费用
        $operateType = "SIGN"; // 操作类型，默认SIGN签约，MODIFY_SIGN修改

        $data ="";
        $http_client = new httpClient();
        $http_client->post($this->api, $data)->send();
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