<?php
namespace jzq\test\bank;
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../clientInfo.php';
use com\jzq\api\model\bank\BankFourVerifyRequest;
use com\jzq\api\model\menu\IdentityType;
use org\ebq\api\tool\RopUtils;
use jzq\test\ClientInfo;
//组建请求参数
$requestObj=new BankFourVerifyRequest();
//* 银行卡号
$requestObj->cardNo="//TODO";
//* 银行卡收款人
$requestObj->cardName="//TODO";
//* 用户类型
$requestObj->certType=IdentityType::$IDCARD["code"];
//* 证件号码
$requestObj->certNo="//TODO";
//* 银行卡预留手机号
$requestObj->userPhoneNo="//TODO";
//请求
$response=RopUtils::doPostByObj($requestObj,ClientInfo::$app_key,ClientInfo::$app_secret,ClientInfo::$services_url);
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