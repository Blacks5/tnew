<?php
namespace jzq\test\sign;
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../clientInfo.php';

use com\jzq\api\model\sign\FileLinkRequest;
use org\ebq\api\tool\RopUtils;
use jzq\test\ClientInfo;
//组建请求参数
$requestObj=new FileLinkRequest();
//* 签约编号
$requestObj->applyNo="//TODO 签约编号 APL7900904926xxxxx";
//请求
$response=RopUtils::doPostByObj($requestObj,ClientInfo::$app_key,ClientInfo::$app_secret,ClientInfo::$services_url);
//以下为返回的一些处理
$responseJson=json_decode($response);
print_r("response:".$response."</br>");
print_r("format:</br>");
var_dump($responseJson); //null
if($responseJson->success){
    echo $requestObj->getMethod()."->处理成功".'</br>';
    echo $responseJson->link.'</br>';
    RopUtils::httpcopy($responseJson->link,"/test.pdf");
    echo "下载文件完成<br/>";
}else{
    echo $requestObj->getMethod()."->处理失败";
}