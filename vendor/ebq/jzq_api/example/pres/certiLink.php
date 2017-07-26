<?php
namespace jzq\test\pres;
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../clientInfo.php';
use org\ebq\api\tool\RopUtils;
use org\ebq\api\tool\ShaUtils;
use com\jzq\api\model\pres\CertiLinkRequest;
use com\jzq\api\model\bean\Signatory;
use com\jzq\api\model\menu\IdentityType;
use jzq\test\ClientInfo;
//组建请求参数
$signatory=new Signatory();
//* 证件类型
$signatory->setSignatoryIdentityType(IdentityType::$IDCARD);
//* 名称
$signatory->fullName="//TODO 名称";
//* 证件号
$signatory->identityCard="//TODO 证件号";

//组建请求参数
$requestObj=new CertiLinkRequest();
//* 签约编号
$requestObj->applyNo="//TODO 签约编号：APL874xx430678214xxxx";
$requestObj->signatory=$signatory;
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