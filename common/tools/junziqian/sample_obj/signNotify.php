<?php
	require_once dirname(__FILE__).'/../tool/shaUtils.php';
	require_once dirname(__FILE__).'/../tool/ropUtils.php';
	require_once dirname(__FILE__).'/../model/signNotifyRequest.php';
	require_once dirname(__FILE__).'/../model/signatory.php';
	require_once dirname(__FILE__).'/../model/enum.php';
	use com_junziqian_api_tool\RopUtils as RopUtils;
	use com_junziqian_api_tool\ShaUtils as ShaUtils;
	use com_junziqian_api_model\SignNotifyRequest as SignNotifyRequest;
	use com_junziqian_api_model\Signatory as Signatory;
	use com_junziqian_api_model\IdentityType as IdentityType;
	//组建请求参数
	$signatory=new Signatory();
	$signatory->setSignatoryIdentityType(IdentityType::$IDCARD);
	$signatory->fullName="易凡翔";
	$signatory->identityCard="500240198704146355";
	
	$requestObj=new SignNotifyRequest();
	$requestObj->applyNo="APL780321098117025792";
	$requestObj->signatory=$signatory;
	//
	$requestObj->signNotifyType=SignNotifyRequest::$NOTIFYTYPE_SIGN;
	//TODO
	//$requestObj->backUrl='http://xx.xx.xx/**';
	//请求
	$response=RopUtils::doPostByObj($requestObj);
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
?>