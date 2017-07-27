<?php
	require_once dirname(__FILE__) . '/../tool/HttpSignUtils.php';
	require_once(dirname(__FILE__) . '/../cfg/ClientInfo.php');
	use com_junziqian_api_tool\HttpSignUtils as HttpSignUtils;
	use com_junziqian_api_cfg\ClientInfo as ClientInfo;
	$result=array(
			"resultCode"=>"",
			"msg"=>"",
			"success"=>true,
	);
	if(!isset($_REQUEST['applyNo'])){
		$result['resultCode']="jsonParamsError";
		$result['msg']="applyNo is null";
		$result['success']=false;
		echo(json_encode($result,JSON_UNESCAPED_UNICODE));
		exit(0);
	}
	if(!isset($_REQUEST['identityType'])){
		$result['resultCode']="jsonParamsError";
		$result['msg']="identityType is null";
		$result['success']=false;
		echo(json_encode($result,JSON_UNESCAPED_UNICODE));
		exit(0);
	}
	if(!isset($_REQUEST['fullName'])){
		$result['resultCode']="jsonParamsError";
		$result['msg']="fullName is null";
		$result['success']=false;
		echo(json_encode($result,JSON_UNESCAPED_UNICODE));
		exit(0);
	}
	if(!isset($_REQUEST['identityCard'])){
		$result['resultCode']="jsonParamsError";
		$result['msg']="identityCard is null";
		$result['success']=false;
		echo(json_encode($result,JSON_UNESCAPED_UNICODE));
		exit(0);
	}
	if(!isset($_REQUEST['optTime'])){
		$result['resultCode']="jsonParamsError";
		$result['msg']="optTime is null";
		$result['success']=false;
		echo(json_encode($result,JSON_UNESCAPED_UNICODE));
		exit(0);
	}
	if(!isset($_REQUEST['signStatus'])){
		$result['resultCode']="jsonParamsError";
		$result['msg']="signStatus is null";
		$result['success']=false;
		echo(json_encode($result,JSON_UNESCAPED_UNICODE));
		exit(0);
	}
	if(!isset($_REQUEST['timestamp'])){
		$result['resultCode']="jsonParamsError";
		$result['msg']="timestamp is null";
		$result['success']=false;
		echo(json_encode($result,JSON_UNESCAPED_UNICODE));
		exit(0);
	}
	
	if(!isset($_REQUEST['sign'])){
		$result['resultCode']="jsonParamsError";
		$result['msg']="sign is null";
		$result['success']=false;
		echo(json_encode($result,JSON_UNESCAPED_UNICODE));
		exit(0);
	}
	
	$applyNo=$_REQUEST['applyNo'];
	$identityType=$_REQUEST['identityType'];
	$fullName=$_REQUEST['fullName'];
	$identityCard=$_REQUEST['identityCard'];
	$optTime=$_REQUEST['optTime'];
	$signStatus=$_REQUEST['signStatus'];//签约状态	0未签、1已签、2拒签
	$timestamp=$_REQUEST['timestamp'];
	$sign=$_REQUEST['sign'];
	
	$bodyParams=array(
			'applyNo'=>$applyNo,
			'identityType'=>$identityType,
			'fullName'=>$fullName,
			'identityCard'=>$identityCard,
			'optTime'=>$optTime, 
			'signStatus'=>$signStatus
	);
	try {
		HttpSignUtils::checkHttpSign($bodyParams, $timestamp, ClientInfo::$app_key, ClientInfo::$app_secret, $sign);
	} catch (Exception $e) {
		$result['resultCode']="signError";
		$result['msg']=$e->getMessage();
		$result['success']=false;
	}
	if($result['success']){
		//TODO 做自个的业务相关处理
		
	}
	echo(json_encode($result,JSON_UNESCAPED_UNICODE));
?>