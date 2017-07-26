<?php
namespace jzq\test\http;
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../clientInfo.php';
use org\ebq\api\tool\HttpSignUtils;
use jzq\test\ClientInfo;
use org\ebq\api\tool\RopUtils;

//**只用于测试签约的回调
$result=array(
			"resultCode"=>"",
			"msg"=>"",
			"success"=>true,
	);
	if(!isset($_REQUEST['applyNo'])){
		$result['resultCode']="jsonParamsError";
		$result['msg']="applyNo is null";
		$result['success']=false;
        echo(RopUtils::json_encode($result));
		exit(0);
	}
	if(!isset($_REQUEST['identityType'])){
		$result['resultCode']="jsonParamsError";
		$result['msg']="identityType is null";
		$result['success']=false;
        echo(RopUtils::json_encode($result));
		exit(0);
	}
	if(!isset($_REQUEST['fullName'])){
		$result['resultCode']="jsonParamsError";
		$result['msg']="fullName is null";
		$result['success']=false;
        echo(RopUtils::json_encode($result));
		exit(0);
	}
	if(!isset($_REQUEST['identityCard'])){
		$result['resultCode']="jsonParamsError";
		$result['msg']="identityCard is null";
		$result['success']=false;
        echo(RopUtils::json_encode($result));
		exit(0);
	}
	if(!isset($_REQUEST['optTime'])){
		$result['resultCode']="jsonParamsError";
		$result['msg']="optTime is null";
		$result['success']=false;
        echo(RopUtils::json_encode($result));
		exit(0);
	}
	if(!isset($_REQUEST['signStatus'])){
		$result['resultCode']="jsonParamsError";
		$result['msg']="signStatus is null";
		$result['success']=false;
        echo(RopUtils::json_encode($result));
		exit(0);
	}
	if(!isset($_REQUEST['timestamp'])){
		$result['resultCode']="jsonParamsError";
		$result['msg']="timestamp is null";
		$result['success']=false;
        echo(RopUtils::json_encode($result));
		exit(0);
	}
	
	if(!isset($_REQUEST['sign'])){
		$result['resultCode']="jsonParamsError";
		$result['msg']="sign is null";
		$result['success']=false;
        echo(RopUtils::json_encode($result));
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
	} catch (\Exception $e) {
		$result['resultCode']="signError";
		$result['msg']=$e->getMessage();
		$result['success']=false;
	}
	if($result['success']){
		//TODO 做自个的业务相关处理
	}
	echo(RopUtils::json_encode($result));