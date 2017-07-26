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
use com_junziqian_api_model\AuthLevel;
use com_junziqian_api_model\DealType;
use com_junziqian_api_model\IdentityType;
use com_junziqian_api_model\SequenceInfo;
use com_junziqian_api_model\SignLevel;
use com_junziqian_api_model\UploadFile;

use common\tools\junziqian\model\ApplySignFileRequest;
use common\tools\junziqian\tool\RopUtils;

class JunController extends CoreBackendController
{
    public function actionA()
    {
        $requestObj = new ApplySignFileRequest();
        $requestObj->file = new UploadFile('test.pdf'); // 合同文件
        $requestObj->contractName = '合同001'; // 合同名
        $requestObj->contractAmount = 1000; // 合同金额
        $requestObj->authLevelRange = '1'; // 验证范围

        // 签合同方
        $signatories = [];

        // 构造一个合同方
        $signatory = new \common\tools\junziqian\model\Signatory();
        $signatory->setSignatoryIdentityType(IdentityType::$IDCARD); // 签约类型
        $signatory->fullName = '王五';
        $signatory->identityCard = '510623198812250210';
        $signatory->mobile = 18990232122;
        $signatory->authLevel = [AuthLevel::$BANKCARD]; // 银行卡认证
        $signatory->forceAuthentication = 0; // 0只需第一次认证过，后面不用认证；1每次都要认证
        $signatory->signLevel = SignLevel::$GENERAL; // 标准图形章
        $signatory->noNeedEvidence = 0; // 隐藏添加现场 0不隐藏 1隐藏
        $signatory->forceEvidence = 0; // 强制添加签约现场图片， 0不强制 1强制
        $signatory->orderNum = 1; //顺序签字
        $signatory->insureYear = 3; // 购买保险年限
        $signatory->readTime = 20; // 强制阅读时间，单位：秒
        $signatory->serverCaAuto = 1; // 0手动签 1自动签
        $signatory->setChapteJson([
            [
                'page'=>0,
                'chaptes'=>[
                    ['offsetX'=>0.12],
                    ['offsetY'=>0.23]
                ]
            ]
        ]);

        array_push($signatories, $signatory);

        $requestObj->signatories = $signatories;

        $requestObj->signLevel = SignLevel::$GENERAL;
        $requestObj->forceAuthentication = 1;
        $requestObj->preRecored = '前执记录，不知道是个啥';
        $requestObj->orderFlag = 1; // 1按orderNum顺序签，默认不按顺序
        $requestObj->needCa = 1;// 1需要CA；空和0不需要

        //
        $requestObj->sequenceInfo = new SequenceInfo("XX01", 2, 2);
        $requestObj->serverCa = 1; //使用云证书 1使用 0不使用
        $requestObj->dealType = DealType::$AUTH_SIGN; //

        $response = RopUtils::doPostByObj($requestObj);
        $responseJson = json_decode($response);
        var_dump($responseJson, $requestObj->getMethod());
    }
}