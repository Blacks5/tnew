<?php

/**
 * Created by PhpStorm.
 * User: too
 * Date: 2017/7/4
 * Time: 15:25
 * @author too <hayto@foxmail.com>
 */
namespace common\tools\yijifu;
abstract class AbstractYijifu
{
    protected $protocol = "httpPost"; // 协议类型，默认httpPost  【可不填】
    protected $service = ""; // 服务码
    protected $version = "1.0"; // 服务版本，默认1.0  【可不填】
    protected $partnerId = ""; // 签约的服务平台账号对应的合作方ID

    protected $orderNo = ""; //  请求流水号，保证每次请求都要不同，和业务无关，故使用微秒时间戳即可

    protected $signType = "MD5"; // 签名方式，目前默认MD5，也只支持MD5，注意要大写

    protected $sign = ""; // 签名字符串 待签名的参数都要用ksrot排序

    protected $returnUrl = ""; // 页面跳转返回URL，非跳转接口不需要此参数 【可不填】

    protected $notifyUrl = ""; // 异步通知URL 【可不填】


    private $privateKey = ""; // 商户

    private $api; // api地址


    public function __construct()
    {
        $this->partnerId = \Yii::$app->params['yijifu']['partnerId'];
        $this->privateKey = \Yii::$app->params['yijifu']['privateKey'];
        $this->api = \Yii::$app->params['yijifu']['api'];
        $this->orderNo = str_replace('.', '', microtime(true)). mt_rand(1000000, 9999999);
    }

    protected function getCommonParams()
    {
        return [
            'protocol'=>$this->protocol,
            'service'=>$this->service,
            'version'=>$this->version,
            'partnerId'=>$this->partnerId,
            'orderNo'=>$this->orderNo,
            'signType'=>$this->signType,
            'returnUrl'=>$this->returnUrl,
            'notifyUrl'=>$this->notifyUrl,
            'sign'=>$this->sign
        ];
    }

    /**
     * 预处理请求参数
     * @param array $query_params
     * @return array
     * @author too <hayto@foxmail.com>
     */
    public function prepQueryParams(Array $query_params)
    {
        $query_params['sign'] = $this->signature($query_params);
        return $query_params;
    }

    /**
     * 签名生成sign
     * @param array $query_params
     * @return string sign字符串
     * @author too <hayto@foxmail.com>
     */
    private function signature(Array $query_params)
    {
        ksort($query_params);
        $query_str = urldecode(http_build_query($query_params));
        return md5($query_str. $this->privateKey);
    }
}