<?php
namespace jzq\test\account;
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../clientInfo.php';
use com\jzq\api\model\account\CertiAuthRequest;
use org\ebq\api\model\bean\UploadFile;
use org\ebq\api\tool\RopUtils;

use jzq\test\ClientInfo;
//组建请求参数
	$requestObj=new CertiAuthRequest();
	//* 申请表文件
	$requestObj->applyTable=new UploadFile("E:\\tmp\\2.png");
	//* 个人
	//$requestObj->withPersonalSignatory("15320xxxx50", "500240xxxxxxxx6355", "xxx");
	//* 企业|必须先创建企业用户后可使用
	$requestObj->withEnterpriseSignatory("//TODO 证件号", "//TODO 邮箱");
	
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