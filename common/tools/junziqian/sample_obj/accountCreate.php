<?php
	require_once dirname(__FILE__).'/../tool/shaUtils.php';
	require_once dirname(__FILE__).'/../tool/ropUtils.php';
	require_once dirname(__FILE__).'/../model/enum.php';
	require_once dirname(__FILE__).'/../model/uploadFile.php';
	require_once dirname(__FILE__).'/../model/organizationCreateRequest.php';
	use com_junziqian_api_tool\RopUtils as RopUtils;
	use com_junziqian_api_model\OrganizationCreateRequest as OrganizationCreateRequest;
	use com_junziqian_api_model\OrganizationType as OrganizationType;
	use com_junziqian_api_model\UploadFile as UploadFile;
	//组建请求参数
	$requestObj=new OrganizationCreateRequest();
	//组建请求参数
	$filePath="E:\\tmp\\test.jpg";
	//单位邮箱
	$requestObj->emailOrMobile="276707931@qq.com";
	//单位真实全称
	$requestObj->name="飞一科技";
	//单位还是事件单位
	$requestObj->organizationType=OrganizationType::$ENTERPRISE;
	//单位证件类型
	$requestObj->identificationType=OrganizationCreateRequest::$IDENTIFICATION_TYPE_TRADITIONAL;//0多证;1 3证合一
	//单位执照号
	$requestObj->organizationRegNo="500903000035444";
	//单位执照扫描件
	$requestObj->organizationRegImg=new UploadFile($filePath);//如果为linux系统时后面true不填写
	//组织机构号
	$requestObj->organizationCode="58016467-6";
	//单位执扫描件
	$requestObj->organizationCodeImg=new UploadFile($filePath);
	//税务登记证
	$requestObj->taxCertificateImg=new UploadFile($filePath);
	//签约申请书
	$requestObj->signApplication=new UploadFile($filePath);
	
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