<?php
/**
 * Created by PhpStorm.
 * User: too
 * Date: 2017/7/26
 * Time: 13:16
 * @author too <hayto@foxmail.com>
 */

namespace backend\controllers;


use backend\core\CoreBackendController;
use com\jzq\api\model\account\OrganizationAuditStatusRequest;
use com\jzq\api\model\account\OrganizationCreateRequest;
use com\jzq\api\model\bean\Signatory;
use com\jzq\api\model\menu\AuthLevel;
use com\jzq\api\model\menu\DealType;
use com\jzq\api\model\menu\IdentityType;
use com\jzq\api\model\menu\OrganizationType;
use com\jzq\api\model\menu\SequenceInfo;
use com\jzq\api\model\menu\SignLevel;
use com\jzq\api\model\sign\ApplySignFileRequest;
use com\jzq\api\model\sign\DetailAnonyLinkRequest;
use com\jzq\api\model\sign\FileLinkRequest;
use com\jzq\api\model\sign\SignLinkRequest;
use com\jzq\api\model\sign\SignNotifyRequest;
use com\jzq\api\model\sign\SignStatusRequest;
use org\ebq\api\model\bean\UploadFile;
use org\ebq\api\tool\RopUtils;

//use common\tools\junziqian\model\AuthLevel;
//use common\tools\junziqian\model\DealType;
//use common\tools\junziqian\model\IdentityType;
//use common\tools\junziqian\model\SequenceInfo;
//use common\tools\junziqian\model\SignLevel;
//
//use common\tools\junziqian\model\ApplySignFileRequest;
//use common\tools\junziqian\model\UploadFile;
//use common\tools\junziqian\tool\RopUtils;

class JunController extends CoreBackendController
{

    public function actionA()
    {

        $requestObj = new ApplySignFileRequest();
        $requestObj->file = new UploadFile('test1.pdf'); // 合同文件
        $requestObj->contractName = '合同001'; // 合同名
        $requestObj->contractAmount = 1000; // 合同金额
        $requestObj->authLevelRange = '1'; // 验证范围

        // 签合同方
        $signatories = [];

        // 构造一个合同方
        //$signatory = new \common\tools\junziqian\model\Signatory();
        $signatory = new Signatory();
        $signatory->setSignatoryIdentityType(IdentityType::$IDCARD); // 签约类型
        $signatory->fullName = '钟超';
        $signatory->identityCard = '510623199308253919';
        $signatory->mobile = 18990232122;
        $signatory->authLevel = [AuthLevel::$BANKCARD]; // 银行卡认证
        $signatory->forceAuthentication = 0; // 0只需第一次认证过，后面不用认证；1每次都要认证
        $signatory->signLevel = SignLevel::$GENERAL; // 标准图形章
        $signatory->noNeedEvidence = 0; // 隐藏添加现场 0不隐藏 1隐藏
        $signatory->forceEvidence = 0; // 强制添加签约现场图片， 0不强制 1强制
        $signatory->orderNum = 1; //顺序签字
        //$signatory->insureYear = 3; // 购买保险年限
        $signatory->readTime = 20; // 强制阅读时间，单位：秒
        $signatory->serverCaAuto = 0; // 0手动签 1自动签
        $signatory->setChapteJson([
            [
                'page'=>0,
                'chaptes'=>[
                    ['offsetX'=>0.5,'offsetY'=>0.5]
                ]
            ]
        ]);

        array_push($signatories, $signatory);

        $requestObj->signatories = $signatories;

        $requestObj->signLevel = SignLevel::$GENERAL;
        $requestObj->forceAuthentication = 1;
        $requestObj->preRecored = '前执记录，不知道是个啥';
        $requestObj->orderFlag = 1; // 1按orderNum顺序签，默认不按顺序
        //$requestObj->needCa = 1;// 1需要CA；空和0不需要

        //
        //$requestObj->sequenceInfo = new SequenceInfo("XX02", 1, 1);
        $requestObj->serverCa = 1; //使用云证书 1使用 0不使用
        $requestObj->dealType = DealType::$AUTH_SIGN; //

        $junziqian = \Yii::$app->params['junziqian'];
        $response = RopUtils::doPostByObj($requestObj,$junziqian['appkey'],$junziqian['secret'],$junziqian['service_url']);
        $responseJson = json_decode($response);
        var_dump($responseJson, $requestObj->getMethod());
        //APL890202798073974784
    }

    /**
     * @author lilaotou <liwansen@foxmail.com>
     * 获取签约连接
     */
    public function actionA6()
    {
//组建请求参数
        $signatory=new Signatory();
        $signatory->setSignatoryIdentityType(IdentityType::$IDCARD);
        $signatory->fullName="钟超";
        $signatory->identityCard="510623199308253919";

        $requestObj=new SignLinkRequest();
        $requestObj->applyNo="APL890211792079425536";
        $requestObj->signatory=$signatory;

        //请求
        $junziqian = \Yii::$app->params['junziqian'];
        $response = RopUtils::doPostByObj($requestObj,$junziqian['appkey'],$junziqian['secret'],$junziqian['service_url']);
        //以下为返回的一些处理
        $responseJson=json_decode($response);
        var_dump($responseJson); //null
        if($responseJson->success){
            echo $requestObj->getMethod()."->处理成功";
        }else{
            echo $requestObj->getMethod()."->处理失败";
        }
    }

    /**
     * 短信签约提醒
     */
    public function actionA7(){

        $signatory=new Signatory();
        $signatory->setSignatoryIdentityType(IdentityType::$IDCARD);
        $signatory->fullName="钟超";
        $signatory->identityCard="510623199308253919";
        $requestObj=new SignNotifyRequest();
        $requestObj->applyNo="APL890216212989087744";
        $requestObj->signatory=$signatory;
//
        $requestObj->signNotifyType=SignNotifyRequest::$NOTIFYTYPE_SIGN;
        //TODO
        //$requestObj->backUrl='http://xx.xx.xx/**';
        //请求
        $junziqian = \Yii::$app->params['junziqian'];
        $response = RopUtils::doPostByObj($requestObj,$junziqian['appkey'],$junziqian['secret'],$junziqian['service_url']);
        //以下为返回的一些处理
        $responseJson=json_decode($response);
        var_dump($responseJson); //null
    }

    /**
     * 签约详情
     */
    public function actionA10(){
        $requestObj=new DetailAnonyLinkRequest();
        $requestObj->applyNo="APL890216212989087744";
        //请求
        $junziqian = \Yii::$app->params['junziqian'];
        $response = RopUtils::doPostByObj($requestObj,$junziqian['appkey'],$junziqian['secret'],$junziqian['service_url']);
        //以下为返回的一些处理
        $responseJson=json_decode($response);
        var_dump($responseJson); //null

    }

    /**
     * 签约合同下载
     */
    public function actionA11(){
        //组建请求参数
        $requestObj=new FileLinkRequest();
        $requestObj->applyNo="APL890216212989087744"; //签约编号
//请求
        $junziqian = \Yii::$app->params['junziqian'];
        $response = RopUtils::doPostByObj($requestObj,$junziqian['appkey'],$junziqian['secret'],$junziqian['service_url']);
//以下为返回的一些处理
        $responseJson=json_decode($response);
        var_dump($responseJson); //null
    }

    /**
     * 保全合同下载
     */
    public function actionA12(){
        $signatory=new Signatory();
        $signatory->setSignatoryIdentityType(IdentityType::$IDCARD);
        $signatory->fullName="易凡翔";
        $signatory->identityCard="500240198704146355";
        $requestObj=new PresFileLinkRequest();
        $requestObj->applyNo="APL749783477280444416";
        $requestObj->signatory=$signatory;
        //请求
        $junziqian = \Yii::$app->params['junziqian'];
        $response = RopUtils::doPostByObj($requestObj,$junziqian['appkey'],$junziqian['secret'],$junziqian['service_url']);
        //以下为返回的一些处理
        $responseJson=json_decode($response);

        var_dump($responseJson);
    }
    /**
     * 签约状态查询
     */
    public function actionA13(){
        $signatory=new Signatory();
        $signatory->setSignatoryIdentityType(IdentityType::$IDCARD);
        $signatory->fullName="易凡翔";
        $signatory->identityCard="500240198704146355";
        $requestObj=new SignStatusRequest();
        $requestObj->applyNo="APL790090492615462912";
        $requestObj->signatory=$signatory;
        //请求
        $junziqian = \Yii::$app->params['junziqian'];
        $response = RopUtils::doPostByObj($requestObj,$junziqian['appkey'],$junziqian['secret'],$junziqian['service_url']);
        //以下为返回的一些处理
        $responseJson=json_decode($response);
        var_dump($responseJson); //null
    }

    /**
     * 企业信息认证
     */
    public function actionA15(){
        $requestObj=new OrganizationCreateRequest();
        //组建请求参数
        //证件扫描图片位置
                $filePath="test.jpg";
        //企业邮箱
                $requestObj->emailOrMobile="15723089637"; //企业可用手机或者邮箱
        //企业真实全称
                $requestObj->name="test 科技公司";
        //企业还是事件单位
                $requestObj->organizationType=OrganizationType::$ENTERPRISE;
        //企业证件类型
                $requestObj->identificationType=OrganizationCreateRequest::$IDENTIFICATION_TYPE_TRADITIONAL;//0 多证;1 三证合一
        //营业执照号
        $requestObj->organizationRegNo="500903000035444";
        //营业执照扫描件
        $requestObj->organizationRegImg=new UploadFile($filePath);//如果为 linux 系统时后面 true 不填写
        //组织机构代码
        $requestObj->organizationCode="58016467-6";
        //组织机构代码扫描件
        $requestObj->organizationCodeImg=new UploadFile($filePath);
        //税务登记证扫描件
        $requestObj->taxCertificateImg=new UploadFile($filePath);
        //签约申请书
        $requestObj->signApplication=new UploadFile($filePath);
        //请求
        $junziqian = \Yii::$app->params['junziqian'];
        $response = RopUtils::doPostByObj($requestObj,$junziqian['appkey'],$junziqian['secret'],$junziqian['service_url']);
        //以下为返回的一些处理
        $responseJson=json_decode($response);

        var_dump($responseJson);
    }

    /**
     * 企业信息认证结果查询
     */
    public function actionA16(){
        //组建请求参数
        $requestObj=new OrganizationAuditStatusRequest();
        //单位邮箱或手机
        $requestObj->emailOrMobile="276707931@qq.com";
        //请求
        $junziqian = \Yii::$app->params['junziqian'];
        $response = RopUtils::doPostByObj($requestObj,$junziqian['appkey'],$junziqian['secret'],$junziqian['service_url']);
        //以下为返回的一些处理
        $responseJson=json_decode($response);
        var_dump($responseJson); //null
    }



}