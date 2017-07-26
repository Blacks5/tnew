<?php
namespace jzq\test\account;
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../clientInfo.php';
use org\ebq\api\tool\RopUtils;
use com\jzq\api\model\account\OrganizationCreateRequest;
use com\jzq\api\model\menu\OrganizationType;
use org\ebq\api\model\bean\UploadFile;

use jzq\test\ClientInfo;
    //组建请求参数
	$requestObj=new OrganizationCreateRequest();
	//* 组建请求参数
	$filePath="E:\\tmp\\2.png";
    //* 单位邮箱
    $requestObj->emailOrMobile="//TODO邮箱";
	//* 单位真实全称
	$requestObj->name="XXXX";
	//* 单位还是事件单位
	$requestObj->organizationType=OrganizationType::$ENTERPRISE;
	//* 单位证件类型
	$requestObj->identificationType=OrganizationCreateRequest::$IDENTIFICATION_TYPE_ALLINONE;//0多证;1 3证合一
	//* 单位执照号
	$requestObj->organizationRegNo="XXXXXXXXXXXXX";
	//* 单位执照扫描件
	$requestObj->organizationRegImg=new UploadFile($filePath);//如果为linux系统时后面true不填写

	//组织机构号
	//$requestObj->organizationCode="58016467-6";
	//单位执扫描件
	//$requestObj->organizationCodeImg=new UploadFile($filePath);
	//税务登记证
	//$requestObj->taxCertificateImg=new UploadFile($filePath);
	//* 签约申请书
	$requestObj->signApplication=new UploadFile($filePath);
	
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