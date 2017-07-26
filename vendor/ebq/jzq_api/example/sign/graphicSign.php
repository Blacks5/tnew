<?php
namespace jzq\test\sign;
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../clientInfo.php';

use com\jzq\api\model\sign\graphicSignRequest;
use org\ebq\api\model\bean\UploadFile;
use org\ebq\api\tool\RopUtils;
use jzq\test\ClientInfo;
//组建请求参数
$requestObj=new GraphicSignRequest();
//* 证件号或营业执照号
$requestObj->signatoryIdentityCard="500240198704146355";
//* 上传的文件
$requestObj->signImgFile=new UploadFile("E:\\tmp\\test.jpg");
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