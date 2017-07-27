<?php
	require_once dirname(__FILE__) . '/../tool/ShaUtils.php';
	require_once dirname(__FILE__) . '/../tool/RopUtils.php';
	require_once dirname(__FILE__) . '/../model/PingRequest.php';
	require_once dirname(__FILE__) . '/../model/UploadFile.php';
	use com_junziqian_api_tool\RopUtils as RopUtils;
	use com_junziqian_api_tool\ShaUtils as ShaUtils;
	use com_junziqian_api_model\PingRequest as PingRequest;
	use com_junziqian_api_model\UploadFile as UploadFile;
	/**
	try {
		$file=new UploadFile("http://dev.suiyixun.com/public/dealer_pdf_contract/MER20160020753_1954_2009_contract.pdf");
	} catch (Exception $e) {
		throw new Exception("取文件失败了");
	}
	var_dump($file);
	*/
	//组建请求参数
	$requestObj=new PingRequest();
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