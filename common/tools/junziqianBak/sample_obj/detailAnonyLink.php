<?php
	require_once dirname(__FILE__) . '/../tool/ShaUtils.php';
	require_once dirname(__FILE__) . '/../tool/RopUtils.php';
	require_once dirname(__FILE__) . '/../model/DetailAnonyLinkRequest.php';
	require_once dirname(__FILE__) . '/../model/Signatory.php';
	require_once dirname(__FILE__).'/../model/enum.php';
	use com_junziqian_api_tool\RopUtils as RopUtils;
	use com_junziqian_api_tool\ShaUtils as ShaUtils;
	use com_junziqian_api_model\DetailAnonyLinkRequest as DetailAnonyLinkRequest;
	//组建请求参数
	$requestObj=new DetailAnonyLinkRequest();
	$requestObj->applyNo="APL744767886177996800";
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