<?php
namespace jzq\test\sign;
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../clientInfo.php';

use com\jzq\api\model\bean\Signatory;
use com\jzq\api\model\menu\IdentityType;
use com\jzq\api\model\sign\ApplySignHtmlRequest;
use org\ebq\api\tool\RopUtils;
use jzq\test\ClientInfo;

//组建请求参数
$requestObj=new ApplySignHtmlRequest();
//* html文件必须设置meta.charset为utf-8|否则会出现乱码。表单域请使用input type=text的，且注明name属性，宽高设置为0
$requestObj->htmlContent="<meta charset=\"utf-8\">html文件信息".
    "<br/><br/><br/><br/><br/><br/><input type=\"text\" name=\"ebq\" style=\"width:0;height:0;border:0;margin:0;padding:0;\">".
    "<br/><br/><br/><br/><br/><br/><br/><br/><input type=\"text\" name=\"ebq\" style=\"width:0;height:0;border:0;margin:0;padding:0;\">";
//* 文件名称
$requestObj->fileName='文件名称1.pdf';

$requestObj->formChapteFlag=1;//使用表单域构建签约位置

//* 合同名称
$requestObj->contractName="T001";
//签合同方|测试时请改为自己的个人信息进行测试（姓名、身份证号、手机号不能部分或全部隐藏）
$signatories=array();
$signatory=new Signatory();
//* 证件类型
$signatory->setSignatoryIdentityType(IdentityType::$IDCARD);//个人或企业类型
//* 名称或公司名称
$signatory->fullName="//TODO 名称或公司名称";
//* 证件号码
$signatory->identityCard="//TODO 证件号码";
//* 手机号码
$signatory->mobile='//TODO 手机号码';
$signatory->chapteName="ebq";//表单域构建签约位置id名称
array_push($signatories, $signatory);

$requestObj->signatories=$signatories;
//* TODO $requestObj->file外，其它参数请参考applySignFile.php
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