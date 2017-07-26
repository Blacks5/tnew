<?php
namespace jzq\test\pres;
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../clientInfo.php';
use com\jzq\api\model\bean\Signatory;
use com\jzq\api\model\menu\IdentityType;
use com\jzq\api\model\pres\PresFileLinkRequest;
use org\ebq\api\tool\RopUtils;
use jzq\test\ClientInfo;
	//组建请求参数
	$signatory=new Signatory();
	//* 证件类型
	$signatory->setSignatoryIdentityType(IdentityType::$IDCARD);
	//* 名称
	$signatory->fullName="//TODO 名称";
	//* 证件号
	$signatory->identityCard="//TODO 证件号";
	
	$requestObj=new PresFileLinkRequest();
	//* 签约编号
	$requestObj->applyNo="//TODO 签约编号 APL790090492xxxxxxxxx";
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
		echo $responseJson->link.'</br>';
		RopUtils::httpcopy($responseJson->link,"/test.pdf");
		echo "下载文件完成<br/>";
	}else{
		echo $requestObj->getMethod()."->处理失败";
	}