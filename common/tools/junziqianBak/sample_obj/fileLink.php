<?php
	require_once dirname(__FILE__) . '/../tool/ShaUtils.php';
	require_once dirname(__FILE__) . '/../tool/RopUtils.php';
	require_once dirname(__FILE__) . '/../model/FileLinkRequest.php';
	require_once dirname(__FILE__) . '/../model/Signatory.php';
	require_once dirname(__FILE__).'/../model/enum.php';
	use com_junziqian_api_tool\RopUtils as RopUtils;
	use com_junziqian_api_tool\ShaUtils as ShaUtils;
	use com_junziqian_api_model\FileLinkRequest as FileLinkRequest;
	//组建请求参数
	$requestObj=new FileLinkRequest();
	$requestObj->applyNo="APL790090492615462912";
	//请求
	$response=RopUtils::doPostByObj($requestObj);
	//以下为返回的一些处理
	$responseJson=json_decode($response);
	print_r("response:".$response."</br>");
	print_r("format:</br>");
	var_dump($responseJson); //null
	if($responseJson->success){
		echo $requestObj->getMethod()."->处理成功".'</br>';
		echo $responseJson->link.'</br>';
		RopUtils::httpcopy($responseJson->link,"/test.pdf");
		echo "下载文件完成<br/>";
	}else{
		echo $requestObj->getMethod()."->处理失败";
	}
?>