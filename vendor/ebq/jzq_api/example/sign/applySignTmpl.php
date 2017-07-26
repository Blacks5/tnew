<?php
namespace jzq\test\sign;
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../clientInfo.php';

use com\jzq\api\model\bean\Signatory;
use com\jzq\api\model\menu\IdentityType;
use com\jzq\api\model\sign\ApplySignTmplRequest;
use org\ebq\api\tool\RopUtils;
use jzq\test\ClientInfo;

//组建请求参数
$requestObj=new ApplySignTmplRequest();
//* 合同名称
$requestObj->contractName="T001";
//* 模板编号
$requestObj->templateNo="T001";
//$requestObj->contractAmount=null;
//* 参数
$requestObj->contractParams=array(
    "NO"=>"SN123456789",
    "projectName"=>"君子签",
    "dept"=>"技术部",
    "name"=>"文松"
);
//$requestObj->authLevel=[
//   AuthLevel::$FACE
//];
//$requestObj->faceThreshold=67; //人脸识别阈值

//签合同方
$signatories=array();
//测试时请改为自己的个人信息进行测试（姓名、身份证号、手机号不能部分或全部隐藏）
$signatory=new Signatory();
$signatory->setSignatoryIdentityType(IdentityType::$IDCARD);
$signatory->fullName="xxXX";
$signatory->identityCard="xxxxxxxxXXXXXX";
//$signatory->signLevel=SignLevel::$SEAL;
//$signatory->authenticationLevel=AuthenticationLevel::$BANKCARD;
//$signatory->forceAuthentication=1;
$signatory->mobile='153XXXXXXXX';
// $signatory->authLevel=[
//   AuthLevel::$USEKEY,AuthLevel::$BANKCARD
//];
//$signatory->forceAuthentication=0; //0 只需第一次认证过，后面不用认证;1 每次签约都要认证
//$signatory->signLevel=SignLevel::$GENERAL;//
//$signatory->noNeedEvidence=0;
//$signatory->forceEvidence=0;
//$signatory->orderNum=1;
//[{"page":0,","chaptes":[{"offsetX":0.12,"offsetY":0.23}]},{"page":1,"chaptes":[{"offsetX":0.45,"offsetY":0.67}]}]
//固定签章位置，以文件页左上角(0.0,0.0)为基准，按百分比进行设置）page为页码，从0开始计数，offsetX,offsetY(x,y为比例，值范围设置为0-1之间)  每页为一个数组，以此类推。
/*
$signatory->setChapteJson(array(
    array(
        'page'=>0,//用户id
        'chaptes'=>array(
            array("offsetX"=>0.12,"offsetY"=>0.23),
            array("offsetX"=>0.45,"offsetY"=>0.67)
        )
    )
));
*/
//echo $signatory->setChapteJson."</br>";
array_push($signatories, $signatory);
//$signatory=new Signatory();
//$signatory->setSignatoryIdentityType(IdentityType::$IDCARD);
//$signatory->fullName="文xx";
//$signatory->identityCard="5001051983092xxxxx";
//$signatory->mobile='153203XXXXX';
//$signatory->orderNum=2;
//array_push($signatories, $signatory);

//$signatory=new Signatory();
//$signatory->setSignatoryIdentityType(IdentityType::$BIZLIC);//证件类型
//$signatory->fullName="XX公司";//企业名称
//$signatory->identityCard="4201030xxxxxxx";//营业执照号或统一社会信用代码
//$signatory->email='153203XXXXX';//企业注册账户邮箱
//$signatory->orderNum=3;
//array_push($signatories, $signatory);

$requestObj->signatories=$signatories;

//$requestObj->signLevel=SignLevel::$GENERAL;

/**
 * 强制认证(默认0)
 * 0：不强制认证（需要认证时，认证一次就可），
 * 1：强制认证（需要认证时，每次都要进行认证）.
 */
//$requestObj->forceAuthentication=1;
//$requestObj->preRecored="前执记录，会计录到日志中！";
//$requestObj->orderFlag=1;//1表示按顺序签（按signatories.orderNum顺序），默认不按顺序
//$requestObj->needCa=1;//是否需要CA，空0为不需要，1需要
//$requestObj->sequenceInfo=new SequenceInfo("XX001",2,2);//连续签，第一个字段，连续签合同唯一id，自行设定；第二个字段，第几个签；第三个字段，总的合同份数
//$requestObj->serverCa=1;   //云证书
//$requestObj->dealType=DealType::$AUTH_SIGN;  //签约类型

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