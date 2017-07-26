<?php
namespace jzq\test\sign;
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../clientInfo.php';

use com\jzq\api\model\bean\Signatory;
use com\jzq\api\model\menu\DealType;
use com\jzq\api\model\menu\IdentityType;
use com\jzq\api\model\menu\SequenceInfo;
use com\jzq\api\model\menu\SignLevel;
use com\jzq\api\model\sign\ApplySignFileRequest;
use org\ebq\api\model\bean\UploadFile;
use org\ebq\api\tool\RopUtils;
use jzq\test\ClientInfo;
//组建请求参数
$requestObj=new ApplySignFileRequest();

//组建请求参数
//* 签约文件
$requestObj->file=new UploadFile("E:\\tmp\\test.pdf");
//* 合同名称
$requestObj->contractName="合同0001";
//附件1
//$requestObj->attachFile1=new UploadFile("E:\\tmp\\2.png");
//附件2
//$requestObj->attachFile3=new UploadFile("E:\\tmp\\2.png");
//附件3
//$requestObj->attachFile3=new UploadFile("E:\\tmp\\2.png");
//验证方式
//$requestObj->authLevel=[
//   AuthLevel::$FACE
//];
//验证范围，为string，暂只支持正整数，且小于验证方式数量。
//$requestObj->authLevelRange="1";
//当$requestObj->authLevel=存在AuthLevel::$FACE时，必须设置人脸校验阀值
//$requestObj->faceThreshold=67;

//是否使用云证书1使用,其它:不使用
$requestObj->serverCa=0;
//签约处理类型
$requestObj->dealType=DealType::$DEFAULT;
//是否顺序签约1为按顺序，其它无序
$requestObj->orderFlag=0;//1表示按顺序签（按signatories.orderNum顺序），默认不按顺序

//* 签约方
$signatories=array();
//签约方1
$signatory=new Signatory();
//* 证件类型
$signatory->setSignatoryIdentityType(IdentityType::$IDCARD);
//* 名称或公司名称
$signatory->fullName="xxx";
//* 证件号码、营业执照号、社会信用号
$signatory->identityCard="50024xxxxxxxxxxxxxxxx";
//* 手机号码,为个人时必填,企业可不填
$signatory->mobile='xxxxxxx';
//签约方1的校验等级，如设置将覆盖ApplySignFileRequest->authLevel
//$signatory->authLevel=[
//   AuthLevel::$USEKEY,AuthLevel::$FACE
// ];
//签约方1的验证范围
//$signatory->authLevelRange="1";//验证范围，为string，暂只支持正整数，且小于验证方式数量。
//强制阅读时间
//$signatory->readTime=60;//等待时间
//强制身份认证
//$signatory->forceAuthentication=0;
//$signatory->signLevel=SignLevel::$ESIGNSEAL;
//$signatory->noNeedEvidence=0;
//$signatory->forceEvidence=0;
//当orderFlag1时必须指定签约方的orderNum
//$signatory->orderNum=1;
//签字位置目前支持pdf本身有表单域参考(applySignHtml.php)的pdf文件或使用json串确定固定位置方式
//以下使用json串确定位置。页以0开始，注意
//签字位置,页码从0开始 [{"page":0,","chaptes":[{"offsetX":0.12,"offsetY":0.23}]},{"page":1,"chaptes":[{"offsetX":0.45,"offsetY":0.67}]}]]
/*$signatory->setChapteJson(array(
    array(
        'page'=>0,
        'chaptes'=>array(
            array("offsetX"=>0.12,"offsetY"=>0.23),
            array("offsetX"=>0.45,"offsetY"=>0.67)
        )
    ),
    array(
        'page'=>2,
        'chaptes'=>array(
            array("offsetX"=>0.5,"offsetY"=>0.5)
        )
    )
));*/
//echo $signatory->setChapteJson."</br>";
array_push($signatories, $signatory);
//签约方2
//$signatory=new Signatory();
//$signatory->setSignatoryIdentityType(IdentityType::$IDCARD);
//$signatory->fullName="文xx";
//$signatory->identityCard="5001051983092xxxxx";
//$signatory->mobile='153203XXXXX';
//$signatory->orderNum=2;
//array_push($signatories, $signatory);
/*
$signatory=new Signatory();
$signatory->setSignatoryIdentityType(IdentityType::$BIZLIC);
$signatory->fullName="陈x";
$signatory->identityCard="50024019870414xxxx";
$signatory->mobile='1389691xxxx';
$signatory->serverCaAuto=1;
array_push($signatories, $signatory);
*/
$requestObj->signatories=$signatories;

//$requestObj->signLevel=SignLevel::$ESIGNSEAL;
/**
 * 强制认证(默认0)
 * 0：不强制认证（需要认证时，认证一次就可），
 * 1：强制认证（需要认证时，每次都要进行认证）.
 */
//$requestObj->forceAuthentication=1;
//$requestObj->preRecored="前执记录，会计录到日志中！";
//$requestObj->needCa=1;//是否需要CA，空0为不需要，1需要
//$requestObj->sequenceInfo=new SequenceInfo("XX001",2,2);
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