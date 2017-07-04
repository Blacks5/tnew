<?php
/**
 * Created by PhpStorm.
 * User: Brucelee
 * Date: 2017/7/4
 * Time: 22:31
 * Author: lilaotou <liwansen@foxmail.com>
 */

namespace common\tools\yijifu;
use \yii\httpclient\Client as httpClient;

class Loan
{

    public $protocol = "httpPost"; // 协议类型，默认httpPost
    public $service = ""; // 服务码
    public $version = "1.0"; // 服务版本，默认1.0
    public $partnerId = ""; // 签约的服务平台账号对应的合作方ID
    public $privateKey = ""; // 私钥
    public $orderNo = ""; // 请求流水号
    public $signType = "MD5"; // 签名方式，目前默认MD5，也只支持MD5，注意要大写
    public $sign = ""; // 签名字符串
    public $returnUrl = ""; // 页面跳转返回URL，非跳转接口不需要此参数
    public $notifyUrl = ""; // 异步通知URL

    public $api = "http://merchantapi.yijifu.net/gateway.html";


    public function __construct()
    {
        $this->partnerId = \Yii::$app->params['yijifu']['partnerId'];
        $this->privateKey = \Yii::$app->params['yijifu']['privateKey'];
    }

    /**
     * 用户放款
     * @author lilaotou <liwansen@foxmail.com>
     $data = array(
        'serviceCdoe'=>'',//服务码
        'amount'=>'',//代发金额
        'outOrderNo'=>'',//商户订单号
        'contractUrl'=>'',//合同照片
        'realName'=>'',//收款人姓名
        'mobileNo'=>'',//手机号
        'certNo'=>'',//身份证号
        'bankCardNo'=>'',//银行账户
     );
     */
    public function userLoan(){
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
    }

}