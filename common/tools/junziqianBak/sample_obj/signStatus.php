<?php
	require_once dirname(__FILE__) . '/../tool/ShaUtils.php';
	require_once dirname(__FILE__) . '/../tool/RopUtils.php';
	require_once dirname(__FILE__) . '/../model/SignStatusRequest.php';
	require_once dirname(__FILE__) . '/../model/Signatory.php';
	require_once dirname(__FILE__).'/../model/enum.php';
	use com_junziqian_api_tool\RopUtils as RopUtils;
	use com_junziqian_api_tool\ShaUtils as ShaUtils;
	use com_junziqian_api_model\SignStatusRequest as SignStatusRequest;
	use com_junziqian_api_model\Signatory as Signatory;
	use com_junziqian_api_model\IdentityType as IdentityType;
	use com_junziqian_api_model\SignStatus as SignStatus;
	//组建请求参数
	$signatory=new Signatory();
	$signatory->setSignatoryIdentityType(IdentityType::$IDCARD);
	$signatory->fullName="易凡翔";
	$signatory->identityCard="500240198704146355";
	
	$requestObj=new SignStatusRequest();
	$requestObj->applyNo="APL790090492615462912";
	$requestObj->signatory=$signatory;
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
	//signStatus 参考enum.php的SignStatus
?>