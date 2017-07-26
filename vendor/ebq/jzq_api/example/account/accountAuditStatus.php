<?php
namespace jzq\test\account;
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../clientInfo.php';

use com\jzq\api\model\account\OrganizationAuditStatusRequest;
use org\ebq\api\tool\RopUtils;

use jzq\test\ClientInfo;
	//组建请求参数
	$requestObj=new OrganizationAuditStatusRequest();
	//* 单位邮箱或手机
	$requestObj->emailOrMobile="//TODO要查的账户";
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