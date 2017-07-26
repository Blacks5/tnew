<?php
/**
 * User: huhu
 * DateTime: 2017-06-12 0012 17:34
 */
require_once __DIR__ . '/../vendor/autoload.php';
use org\ebq\api\model\PingRequest;
use org\ebq\api\tool\RopUtils;
//组建请求参数
$requestObj=new PingRequest();
$appkey="你的appkey";
$secret="你的app_secret";
$service_url="rop服务service_url";

//请求
$response=RopUtils::doPostByObj($requestObj,$appkey,$secret,$service_url);
//以下为返回的一些处理
$responseJson=json_decode($response);
print_r("response:".$response."</br>");
print_r("format:</br>");
var_dump($responseJson); //null
if($responseJson->success){
    echo $requestObj->getMethod()."->处理成功";
}else{
    echo $requestObj->getMethod()."->处理失败";
}
