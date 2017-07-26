<?php
namespace jzq\test\sign;
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../clientInfo.php';

use com\jzq\api\model\bean\Signatory;
use com\jzq\api\model\menu\IdentityType;
use com\jzq\api\model\sign\SignLinkRequest;
use org\ebq\api\tool\RopUtils;
use jzq\test\ClientInfo;
//组建请求参数
$signatory=new Signatory();
//* 证件类型，个人或企业
$signatory->setSignatoryIdentityType(IdentityType::$IDCARD);
//* 名称或公司名称
$signatory->fullName="//TODO xxx";
//* 证件号码
$signatory->identityCard="//TODO　5002xxxxxxxxxxxxxx";

$requestObj=new SignLinkRequest();
//* 签约编号
$requestObj->applyNo="//TODO　APL79009049xxxxxxxxxxx";
$requestObj->signatory=$signatory;
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