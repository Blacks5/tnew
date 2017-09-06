<?php
/**
 * Created by PhpStorm.
 * User: too
 * Date: 2017/7/26
 * Time: 13:16
 * @author too <hayto@foxmail.com>
 */

namespace backend\controllers;


use backend\components\CustomBackendException;
use backend\core\CoreBackendController;
use Codeception\Lib\Connector\Yii1;
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
use common\models\JzqSign;
use common\models\Orders;
use org\ebq\api\model\bean\UploadFile;
use org\ebq\api\tool\RopUtils;

use yii;
use yii\db\Query;
use common\components\CustomCommonException;
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

    public function actionIndexp()
    {
        echo "我是菜单";
    }
    /**
     * @author lilaotou <liwansen@foxmail.com>
     * 上传合同
     */
    public function actionA($order_id)
    {
        $request = Yii::$app->getRequest();
        if($request->getIsAjax()){
            try{
                Yii::$app->getResponse()->format = yii\web\Response::FORMAT_JSON;
                $order_data = Orders::getOne($order_id);

                if($order_data === false){
                    return ['status' => 2, 'message' => '数据不存在!'];
                }else{

//                    $contract_url = "http://119.23.15.90:8383/contract/index?o_id=". $order_data['o_id'] ."&pdf=.pdf";
                    $contract_url = Yii::$app->params['domain'] ."/contract/index?o_id=". $order_data['o_id'] ."&pdf=.pdf";
                    $requestObj = new ApplySignFileRequest();
                    $requestObj->file = new UploadFile("$contract_url"); // 合同文件
                    $requestObj->contractName = $order_data['c_customer_name'] . '合同'; // 合同名
                    $requestObj->contractAmount = $order_data['o_total_price'] - $order_data['o_total_deposit']; // 合同金额
                    $requestObj->authLevelRange = '1'; // 验证范围

                    // 签合同方
                    $signatories = [];

                    // 构造一个合同方
                    $signatory = new Signatory();
                    $signatory->setSignatoryIdentityType(IdentityType::$IDCARD); // 签约类型,当前为身份证
                    $signatory->fullName = $order_data['c_customer_name'];
                    $signatory->identityCard = $order_data['c_customer_id_card'];
                    $signatory->mobile = $order_data['c_customer_cellphone'];
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
                                ['offsetX'=>0.8,'offsetY'=>0.8]
                            ]
                        ],
                        [
                            'page'=>1,
                            'chaptes'=>[
                                ['offsetX'=>0.8,'offsetY'=>0.8]
                            ]
                        ],
                        [
                            'page'=>2,
                            'chaptes'=>[
                                ['offsetX'=>0.8,'offsetY'=>0.8]
                            ]
                        ],
                        [
                            'page'=>3,
                            'chaptes'=>[
                                ['offsetX'=>0.8,'offsetY'=>0.8]
                            ]
                        ],
                        [
                            'page'=>4,
                            'chaptes'=>[
                                ['offsetX'=>0.8,'offsetY'=>0.8]
                            ]
                        ],
                        [
                            'page'=>5,
                            'chaptes'=>[
                                ['offsetX'=>0.8,'offsetY'=>0.8]
                            ]
                        ],
                        [
                            'page'=>6,
                            'chaptes'=>[
                                ['offsetX'=>0.8,'offsetY'=>0.8]
                            ]
                        ],
                        [
                            'page'=>7,
                            'chaptes'=>[
                                ['offsetX'=>0.8,'offsetY'=>0.8]
                            ]
                        ],
                        [
                            'page'=>8,
                            'chaptes'=>[
                                ['offsetX'=>0.8,'offsetY'=>0.8]
                            ]
                        ],
                        [
                            'page'=>9,
                            'chaptes'=>[
                                ['offsetX'=>0.8,'offsetY'=>0.8]
                            ]
                        ],
                    ]);

                    array_push($signatories, $signatory);

                    $requestObj->signatories = $signatories;

                    $requestObj->signLevel = SignLevel::$GENERAL;
                    $requestObj->forceAuthentication = 1;
                    $requestObj->preRecored = $order_data['c_customer_name'] . '签约';
                    $requestObj->orderFlag = 1; // 1按orderNum顺序签，默认不按顺序
                    //$requestObj->needCa = 1;// 1需要CA；空和0不需要

                    //
                    //$requestObj->sequenceInfo = new SequenceInfo("XX02", 1, 1);
                    $requestObj->serverCa = 1; //使用云证书 1使用 0不使用
                    $requestObj->dealType = DealType::$DEFAULT; //

                    $junziqian = \Yii::$app->params['junziqian'];
                    $response = RopUtils::doPostByObj($requestObj,$junziqian['appkey'],$junziqian['secret'],$junziqian['service_url']);
                    $responseJson = json_decode($response);
                    var_dump($responseJson);exit;
                    //echo $responseJson->applyNo;
                    if($responseJson->success){
                        //上传成功,写记录表
                        $wait_inster_data = [
                            'o_serial_id'=>$order_data['o_serial_id'],
                            'applyNo'=>$responseJson->applyNo,
                            'signStatus'=>0, // 0 未签、 1 已签、 2 拒签
                            'operator_id'=>Yii::$app->getUser()->getIdentity()->getId(),
                            'operator_realname'=>Yii::$app->getUser()->getIdentity()->realname,
                            'created_at'=>$_SERVER['REQUEST_TIME']
                        ];
                        \Yii::$app->getDb()->createCommand()->insert(JzqSign::tableName(), $wait_inster_data)->execute();
                        return ['status' => 1, 'message' => '上传成功'];
                    }else{
                        var_dump($responseJson);
                        return ['status' => 2, 'message' => '上传失败'];
                    }
                    //var_dump($responseJson, $requestObj->getMethod());
                }

            } catch (CustomCommonException $e) {
                return ['status' => $e->getCode(), 'message' => $e->getMessage()];
            } catch (yii\base\Exception $e) {
                return ['status' => 2, 'message' => '系统错误'];
            }
        }


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
        $signatory->fullName="艾红";
        $signatory->identityCard="510623199511164524";

        $requestObj=new SignLinkRequest();
        $requestObj->applyNo="APL890540460152590336";
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
    public function actionA7($order_id){

        $request = Yii::$app->getRequest();
        if($request->getIsAjax()){

            try{
                Yii::$app->getResponse()->format = yii\web\Response::FORMAT_JSON;

                $order_data = Orders::getOne($order_id);
                if($order_data === false){
                    return ['status' => 2, 'message' => '数据不存在!'];
                }else{
                    //获取签约记录
                    $_data = (new Query())->from(JzqSign::tableName())
                        ->where(['o_serial_id'=>$order_data['o_serial_id']])
                        ->one();
                    if($_data){
                        $signatory=new Signatory();
                        $signatory->setSignatoryIdentityType(IdentityType::$IDCARD);
                        $signatory->fullName = $order_data['c_customer_name'];
                        $signatory->identityCard = $order_data['c_customer_id_card'];
                        $requestObj=new SignNotifyRequest();
                        $requestObj->applyNo = $_data['applyNo'];
                        $requestObj->signatory=$signatory;
//
                        $requestObj->signNotifyType=SignNotifyRequest::$NOTIFYTYPE_SIGN;

                        $junziqian = \Yii::$app->params['junziqian'];
                        $response = RopUtils::doPostByObj($requestObj,$junziqian['appkey'],$junziqian['secret'],$junziqian['service_url']);
                        //以下为返回的一些处理
                        $responseJson=json_decode($response);
//var_dump($responseJson);
                        if($responseJson->success){
                            return ['status' => 1, 'message' => '短信发送成功!'];
                        }else{
                            return ['status' =>2, 'message' => '短信发送失败!'];
                        }
                    }else{
                        return ['status' => 2, 'message' => '请先上传签约合同!'];
                    }
                }
            } catch (CustomCommonException $e) {
                return ['status' => $e->getCode(), 'message' => $e->getMessage()];
            } catch (yii\base\Exception $e) {
                return ['status' => 2, 'message' => '系统错误'];
            }
        }
    }

    /**
     * 签约详情
     */
    public function actionA10($applyNo){
        $requestObj=new DetailAnonyLinkRequest();
        $requestObj->applyNo = $applyNo;
        //请求
        $junziqian = \Yii::$app->params['junziqian'];
        $response = RopUtils::doPostByObj($requestObj,$junziqian['appkey'],$junziqian['secret'],$junziqian['service_url']);
        //以下为返回的一些处理
        $responseJson=json_decode($response);
        //var_dump($responseJson);
        return $responseJson;

    }

    /**
     * 签约合同下载
     */
    public function actionA11(){
        //组建请求参数
        $requestObj=new FileLinkRequest();
        $requestObj->applyNo="APL892616500865798144"; //签约编号
//请求
        $junziqian = \Yii::$app->params['junziqian'];
        $response = RopUtils::doPostByObj($requestObj,$junziqian['appkey'],$junziqian['secret'],$junziqian['service_url']);
//以下为返回的一些处理
        $responseJson=json_decode($response);
        if($responseJson->success === false){
            var_dump($responseJson->error->message);die;
        }else{
            var_dump($responseJson->link);die;
        }
        var_dump($responseJson->success); //null
    }

    /**
     * 保全合同下载
     */
    public function actionA12(){
        $signatory=new Signatory();
        $signatory->setSignatoryIdentityType(IdentityType::$IDCARD);
        $signatory->fullName="易凡翔";
        $signatory->identityCard="510623199511164524";
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
        $signatory->identityCard="510623199511164524";
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

    public function beforeAction($action)
    {
        if('callback' === $action->id){
            $this->enableCsrfValidation = false;
        }
        return true;
    }

    /**
     * 客户签约后，平台回调地址
     * @author too <hayto@foxmail.com>
     */
    public function actionCallback()
    {
        try{
            $request = Yii::$app->getRequest();
            $post = $request->post();
            /*$post = [
                "sign"=>"94c19db05ad83c5b3f18a60c57e93a2e1470fd10",
            "timestamp"=>"1501338027078",
            "identityType"=>"1",
            "optTime"=>"1501336848000",
            "signStatus"=>"3",
            "applyNo"=>"APL891288745368752128",
            "identityCard"=>"510623198812250210",
            "fullName"=>"涂鸿"
            ];*/


            $applyNo = $post['applyNo'] ?? '';
            $model = JzqSign::find()->where(['applyNo'=>$applyNo])->one();
            if(false === !empty($model)){
                throw new CustomBackendException('数据不存在');
            }
            $model->identityType = $post['identityType'];
            $model->fullName = $post['fullName'];
            $model->identityCard = $post['identityCard'];
            $model->optTime = $post['optTime'];
            $model->signStatus = $post['signStatus'];
            $model->timestamp = $post['timestamp'];
            $model->updated_at = $_SERVER['REQUEST_TIME'];
            if(false === $model->save()){
                throw new CustomBackendException('修改状态失败');
            }
            echo json_encode(['success'=>true]);
        }catch (CustomBackendException $e){
//            var_dump($e->getMessage());
            echo json_encode(['success'=>false, 'msg'=>$e->getMessage()]);
        }catch (\Exception $e){
            /*ob_start();
            var_dump($e);
            $a = ob_get_clean();
            file_put_contents('/dev.txt', $a, FILE_APPEND);*/

            echo json_encode(['success'=>false, 'msg'=>$e->getMessage()]);
        }
    }

    /**
     * 君子签-签约记录列表
     * @author lilaotou <liwansen@foxmail.com>
     */
    public function actionJzqlogs(){
        $request = Yii::$app->getRequest();
        $o_serial_id = $request->get('o_serial_id') ? trim($request->get('o_serial_id')) : '';
        $applyNo = $request->get('applyNo') ? trim($request->get('applyNo')) : '';

        $query = (new Query())->from(JzqSign::tableName());
        $query->Where(['>','id','0']);
        if (!empty($o_serial_id)) {
            $query->andWhere(['o_serial_id'=>$o_serial_id]);
        }
        if (!empty($applyNo)) {
            $query->andWhere(['applyNo'=>$applyNo]);
        }
        $querycount = clone $query;
        $pages = new yii\data\Pagination(['totalCount' => $querycount->count()]);
        $pages->pageSize = Yii::$app->params['page_size'];
        $data = $query->orderBy(['created_at' => SORT_DESC])->offset($pages->offset)->limit($pages->limit)->all();
        foreach ($data as $k=>$v){
            $ret = $this->actionA10($v['applyNo']);
            $data[$k]['link'] = $ret->success ? $ret->link : '';
        }
        return $this->render('jzqlogs', [
            'model' => $data,
            'o_serial_id'=>$o_serial_id,
            'applyNo'=>$applyNo,
            'totalpage' => $pages->pageCount,
            'pages' => $pages
        ]);
    }

    /**
     * 君子签-签约记录详情
     * @author lilaotou <liwansen@foxmail.com>
     */

    public function actionView($o_serial_id){
        $_data = (new Query())->from(JzqSign::tableName())->where(['o_serial_id'=>$o_serial_id])->one();
        if(!$_data){
            $this->error('信息不存在!');
        }
        $ret = $this->actionA10($_data['applyNo']);
        $link = $ret->success ? $ret->link : '';
        if(!$link){
            return $this->error('签约处理中!');
        }else{
            header("Location:" . $link);
        }
    }
}