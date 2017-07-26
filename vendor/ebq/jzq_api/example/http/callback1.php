<?php
/**
 * User: huhu
 * DateTime: 2017-06-21 0021 18:03
 */
namespace jzq\test\http;
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../clientInfo.php';
use org\ebq\api\tool\HttpSignUtils;
use jzq\test\ClientInfo;
use org\ebq\api\tool\RopUtils;

//**只用于测试3要素及以后其它新增接口
$result=array(
    "resultCode"=>"",
    "msg"=>"",
    "success"=>true,
);
if(!isset($_REQUEST['method'])){
    $result['resultCode']="jsonParamsError";
    $result['msg']="applyNo is null";
    $result['success']=false;
    echo(RopUtils::json_encode($result));
    exit(0);
}
if(!isset($_REQUEST['data'])){
    $result['resultCode']="jsonParamsError";
    $result['msg']="identityType is null";
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

$method=$_REQUEST['method'];
$data=$_REQUEST['data'];
$timestamp=$_REQUEST['timestamp'];
$sign=$_REQUEST['sign'];
//注意key的顺序
$bodyParams=array(
    'data'=>$data,
    'method'=>$method
);
try {
    HttpSignUtils::checkHttpSign($bodyParams, $timestamp, ClientInfo::$app_key, ClientInfo::$app_secret, $sign);
} catch (\RuntimeException $e) {
    $result['resultCode']="signError";
    $result['msg']=$e->getMessage();
    $result['success']=false;
}
if($result['success']){
    //TODO 做自个的业务相关处理
}
echo(RopUtils::json_encode($result));