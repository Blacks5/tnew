<?php
namespace jzq\test\sign;
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../clientInfo.php';

use com\jzq\api\model\bean\Signatory;
use com\jzq\api\model\menu\IdentityType;
use com\jzq\api\model\sign\ApplySignFileUrlRequest;
use org\ebq\api\tool\RopUtils;
use jzq\test\ClientInfo;

//组建请求参数
$requestObj=new ApplySignFileUrlRequest();
//* 合同名称
$requestObj->url="//TODO 地址:http://www.junziqian.dev/test2.pdf";
//* 文件名称
$requestObj->fileName='//TODO 文件名称: 文件名称1.pdf';

//* 合同名称
$requestObj->contractName="//TODO 合同名称 T001";
//签合同方|测试时请改为自己的个人信息进行测试（姓名、身份证号、手机号不能部分或全部隐藏）
$signatories=array();
$signatory=new Signatory();
//* 证件类型
$signatory->setSignatoryIdentityType(IdentityType::$IDCARD);//个人或企业类型
//* 名称或公司名称
$signatory->fullName="//TODO 名称或公司名称";
//* 证件号码
$signatory->identityCard="//TODO 证件号码";
//* 手机号码
$signatory->mobile='//TODO 手机号码';
array_push($signatories, $signatory);

$requestObj->signatories=$signatories;
//* TODO $requestObj->file外，其它参数请参考applySignFile.php
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