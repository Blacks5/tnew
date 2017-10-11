<?php
/**
 * Created by PhpStorm.
 * User: too
 * Date: 2017/7/5
 * Time: 15:21
 * @author too <hayto@foxmail.com>
 */

namespace common\tools\yijifu;
use backend\components\CustomBackendException;
use common\components\CustomCommonException;
use common\models\Customer;
use common\models\Orders;
use common\models\Repayment;
use common\models\YijifuDeduct;
use common\models\YijifuSign;
use common\tools\junziqian\model\UploadFile;
use yii\db\Exception;
use yii\db\Query;
use \yii\httpclient\Client as httpClient;
use backend\services\OperationLog;

/**
 * 回款接口
 * Class ReturnMoney
 * @package common\tools\yijifu
 * @author too <hayto@foxmail.com>
 */
class ReturnMoney extends AbstractYijifu
{

    /**
     * 用户签约
     *
     * @param $borrowerName 借款人真实姓名
     * @param $borrowerIdcardNo 借款人身份证号
     * @param $borrowerBankCardNo 借款人银行卡号
     * @param $borrowerPhoneNo 借款人手机号
     * @param $purchasedProductName 借款人购买的产品，会包含在短信中，例如：iPhone7
     *
     * @param $o_serial_id 通过订单id生成下面两个，下面两个就不用传了
     * @param $merchOrderNo 商户订单号，每次请求都要变，构成：系统订单号+递增序号
     * @param $merchContractNo 商户签约合同号，一直保持不变，直到签约成功，
     *
     *
     * @param $merchContractImageUrl 签约合同照片 支持jpg jpeg bmp png pdf
     * @param $totalRepayAmount 应还总金额，包括各种利息管理费的总和
     * @param string $loanAmount 借款金额【可不填】，显示在用户短信中
     *
     * @return false接口请求失败   true请求成功
     *
     * @throws CustomCommonException
     * @author too <hayto@foxmail.com>
     */
    public function signContractWithCustomer(
        $borrowerName,
        $borrowerIdcardNo,
        $borrowerBankCardNo,
        $borrowerPhoneNo,
        $purchasedProductName,
        $o_serial_id, // 系统核心订单号
        $merchContractImageUrl,
        $totalRepayAmount,
        $loanAmount=''
    )
    {
        // 检测参数
        $_ = func_get_args();
        array_pop($_);
        foreach ($_ as $v){
            if(false === !empty($v)){
//                var_dump($v);die;
                throw new CustomCommonException('参数不全');
            }
        }
        // 设置服务码
        $this->service = 'fastSign';
        // 生成ID
        $_data = (new Query())->from(YijifuSign::tableName())
            ->where(['o_serial_id'=>$o_serial_id, 'status'=>1])
            ->exists();
        if(true === $_data){
            throw new CustomCommonException('该订单已经成功签约');
        }
        $randString = \Yii::$app->getSecurity()->generateRandomString(4);
        $merchOrderNo = $merchContractNo = $o_serial_id. '-'. $randString;


        // 构造api请求参数
        $param_arr = [
            'merchOrderNo'=>$merchOrderNo,
            'merchContractNo'=>$merchContractNo,
            'merchContractImageUrl'=>$merchContractImageUrl,
            'realName'=>$borrowerName,
            'certNo'=>$borrowerIdcardNo,
            'bankCardNo'=>$borrowerBankCardNo,
            'mobileNo'=>$borrowerPhoneNo,
            'productName'=>$purchasedProductName,
            'loanAmount'=>$loanAmount, // 可以不填的，优先不填
            'totalRepayAmount'=>$totalRepayAmount,
            'operateType'=>'SIGN',
        ];
//        $this->notifyUrl = \Yii::$app->urlManager->createAbsoluteUrl(['borrow/verify-pass-callback']);
//        $this->notifyUrl = "http://119.23.15.90:8383/borrow/verify-pass-callback";
        $this->notifyUrl = \Yii::$app->params['domain'] ."/borrow/verify-pass-callback";

        $common = $this->getCommonParams();
        $param_arr = array_merge($common, $param_arr);
        $param_arr = $this->prepQueryParams($param_arr);



        // 发起请求
        $http_client = new httpClient();
        $response = $http_client->post($this->api, $param_arr)/*->setFormat(httpClient::FORMAT_JSON)*/->send();

        $status = 3; // 接口调用失败
        $reuturn = false;
        if($response->getIsOk()){
            $ret = $response->getData();

            /*ob_start();
            var_dump($param_arr);
            echo "=========================\r\n";
            var_dump($ret);
            file_put_contents('/dev.txt', ob_get_contents(), FILE_APPEND);*/

            // 代表接口调用成功
            if(true === $ret['success']) {
                $status = 2; // 等待回掉
                $reuturn = true;
            }else{
                throw new CustomCommonException($ret['resultMessage']);
            }
        }
        $operator_id = \Yii::$app->getUser()->getId();
        // 写签约记录表
        $wait_inster_data = [
            'o_serial_id'=>$o_serial_id,
            'merchOrderNo'=>$merchOrderNo,
            'merchContractNo'=>$merchContractNo,
            'created_at'=>$_SERVER['REQUEST_TIME'],
            'operator_id'=>$operator_id,
            'status'=>$status,
            'sign'=>isset($ret['sign'])?$ret['sign']: '', // 兼容请求失败，没有$ret的情况
            'orderNo'=>isset($ret['orderNo'])?$ret['orderNo']: '',
        ];
        \Yii::$app->getDb()->createCommand()->insert(YijifuSign::tableName(), $wait_inster_data)->execute();
        return $reuturn;
    }



    /**
     * @param $merchOrderNo
     * @return array|bool|mixed false表示接口请求失败
     *
     * @throws CustomCommonException
     * @author too <hayto@foxmail.com>
     */
    public function querySignedCustomer($merchOrderNo)
    {
        if(false === !empty($merchOrderNo)){
            throw new CustomCommonException('缺少参数');
        }
        $this->service = 'fastSignQuery';
//        $this->notifyUrl = "http://119.23.15.90:8383/repayment/deduct-callback";
        $common = $this->getCommonParams();
        $param_arr = ['merchOrderNo'=>$merchOrderNo];
        $param_arr = array_merge($param_arr, $common);
        $param_arr = $this->prepQueryParams($param_arr);

        $http_client = new httpClient();
        $response = $http_client->post($this->api, $param_arr)->send();
        if($response->getIsOk()){
            return $response->getData();
        }
        return false;
    }


    /**
     * 发起代扣申请
     * @param $o_serial_id 系统核心订单号
     * @param $merchSignOrderNo  商户签约订单号
     * @param $deductAmount 代扣金额
     */
    public function deduct($o_serial_id,$repayment_id, $merchSignOrderNo, $deductAmount, $url = "/repaymentnew/deduct-callback")
    {
        $this->service = 'fastDeduct';
        $randString = \Yii::$app->getSecurity()->generateRandomString(4);
        $merchOrderNo = $o_serial_id. '-'. $randString;
        $param_arr = [
            'merchOrderNo'=>$merchOrderNo, // 自己生成的，貌似和业务无关，相当于自增主键的作用
            'merchSignOrderNo'=>$merchSignOrderNo,
            'deductAmount'=>$deductAmount
        ];
//        $this->notifyUrl = "http://119.23.15.90:8383/borrownew/deduct-callback";
        $this->notifyUrl = \Yii::$app->params['domain'] . $url;
        $common_param = $this->getCommonParams();
        $param = array_merge($common_param, $param_arr);
        $param = $this->prepQueryParams($param);
        $client = new httpClient();
        $response = $client->post($this->api, $param)->send();

        $status = 8; //接口调用失败
        if($response->getIsOk()){
            $ret = $response->getData();
//            var_dump($ret);exit;
            if(true === $ret['success']) {
                $status = 0; // 等待回掉
                $reuturn = true;
            }else{
                throw new CustomCommonException($ret['resultMessage']);
            }
        }

        $operator_id = \Yii::$app->getUser()->getId();
        // 写放款记录表
        $wait_inster_data = [
            'o_serial_id'=>$o_serial_id,
            'merchOrderNo'=>$merchOrderNo,
            'merchSignOrderNo'=>$merchSignOrderNo,
            'deductAmount'=>$deductAmount,
            'created_at'=>$_SERVER['REQUEST_TIME'],
            'operator_id'=>$operator_id,
            'status'=>$status,
            'repayment_id'=>$repayment_id,
            'repayment_ids'=>$repayment_id,
//            'description'=>$ret['resultMessage'],
//            'errorCode'=>$ret['errorCode']
        ];
        \Yii::$app->getDb()->createCommand()->insert(YijifuDeduct::tableName(), $wait_inster_data)->execute();
        return $reuturn;
    }

    /**
     * 查询代扣
     *
     *
     * 服务码 fastDeductQuery
     *
     *
     * @author too <hayto@foxmail.com>
     */
    public function queryDeduct($merchOrderNo)
    {
        $this->service = 'fastDeductQuery';
        if(false === !empty($merchOrderNo)){
            throw new CustomCommonException('缺少参数');
        }
//        $this->notifyUrl = "http://119.23.15.90:8383/repayment/deduct-callback";
        $common = $this->getCommonParams();
        $param_arr = ['merchOrderNo'=>$merchOrderNo];
        $param_arr = array_merge($param_arr, $common);
        $param_arr = $this->prepQueryParams($param_arr);

        $http_client = new httpClient();
        $response = $http_client->post($this->api, $param_arr)->send();
        if($response->getIsOk()){
            return $response->getData();
        }
        return false;
    }

    /**
     * 对账文件下载
     * @return array|bool|mixed
     * @author too <hayto@foxmail.com>
     */
    public function downloadBill($accountDay, $fileFormat = "EXCEL", $transactionAccount = "selfinstallment@yiji.com")
    {

//        $transactionAccount = "20160831020000752643"; // 交易账户
//        $accountDay = "20170718"; // 交易日期
//        $fileFormat = "EXCEL"; // 格式

        $param_arr = [
            "transactionAccount"=>$transactionAccount,
            "accountDay"=>$accountDay,
            "fileFormat"=>$fileFormat,
        ];
        $this->service = "billDownload";
        $common = $this->getCommonParams();
        $param_arr = array_merge($param_arr, $common);
        $param_arr = $this->prepQueryParams($param_arr);
        $http_client = new httpClient();
        $response = $http_client->post($this->api, $param_arr)->send();
        if($response->getIsOk()){
            return $response->getData();
        }
        return false;
    }

    /**
     * 修改签约
     * @param $yijifu  (orders orders_images yijifu_sign)
     * @param $customer  (customer)
     * @param $logs     (日志文件)
     * @return bool
     * @throws CustomCommonException
     * @author OneStep
     */
    public function modifySign($yijifu, $customer, $logs)
    {
        $img = new \common\models\UploadFile();
        $param_arr = [
            'service' => 'fastSign',
            'merchOrderNo'=>$yijifu['merchOrderNo']. mt_rand(1000,9000),
            'merchContractNo'=>$yijifu['merchContractNo'],
            'merchContractImageUrl'=>$img->getUrl($yijifu['oi_after_contract']),
            'realName'=>$customer['c_customer_name'],
            'certNo'=>$customer['c_customer_id_card'],
            'bankCardNo'=>$customer['c_banknum'],
            'mobileNo'=>$customer['c_customer_cellphone'],
            'productName'=>$customer['c_customer_name'].'的订单',
            'loanAmount'=>'', // 可以不填的，优先不填
            'totalRepayAmount'=>$yijifu['o_total_price'] - $yijifu['o_total_deposit'],
            'operateType'=>'MODIFY_SIGN',
        ];
        //$this->notifyUrl = \Yii::$app->params['domain'] ."/borrow/update-bank-call-back";  //修改签约 异步回调使用的是签约时的异步地址
        $common = $this->getCommonParams();
        $param_arr = array_merge($common, $param_arr);
        $param_arr = $this->prepQueryParams($param_arr);


        $http_client = new httpClient();
        $response = $http_client->post($this->api, $param_arr)/*->setFormat(httpClient::FORMAT_JSON)*/->send();

        $status = 3; // 接口调用失败
        $reuturn = false;
        if($response->getIsOk()){

            $ret = $response->getData();

            /*ob_start();
            var_dump($param_arr);
            echo "=========================\r\n";
            var_dump($ret);
            file_put_contents('/dev.txt', ob_get_contents(), FILE_APPEND);*/

            // 代表接口调用成功

            if(true === $ret['success']) {
                $status = 2; // 等待回掉
                $reuturn = true;

                $yijifu_sign =  YijifuSign::findOne(['o_serial_id'=>$yijifu['o_serial_id']]);

                $logs['orderNo'] = $yijifu_sign->orderNo;
                $logs['status'] = $yijifu_sign->status;
                $logs['bankName'] = $yijifu_sign->bankName;
                $logs['bankCode'] = $yijifu_sign->bankCode;
                $logs['bankCardType'] = $yijifu_sign->bankCardType;


                $yijifu_sign->status = $status;                           //修改后的状态  2 等待回调
                $yijifu_sign->orderNo = $ret['orderNo'];                  //本次修改的流水号, 异步回调会用
                $yijifu_sign->logs = json_encode($logs);
                if(false === $yijifu_sign->save(false)){
                    throw new CustomCommonException('修改签约失败!');
                }
            }else{
                throw new CustomCommonException($ret['resultMessage']);
            }
        }

        return $reuturn;
    }

    public function changeYijifuSign($value)
    {
        $trans = \Yii::$app->getDb()->beginTransaction();
        try{
            $reuturn = false;
            foreach ($value as $k => $v){
                $logs = json_decode($v['logs'], true);
                if (!is_array($logs)) {
                    $logs = [];
                }
                $logs['fix-data-bak'] = $v;
                $logs = json_encode($logs);   //历史内容存入logs
                $randString = \Yii::$app->getSecurity()->generateRandomString(5);
                $merchOrderNo = $merchContractNo = $v['o_serial_id'] . '-'. $randString;  //新签约合同号
                $loanAmount = $v['o_total_price'] - $v['o_total_deposit'] + $v['o_service_fee'] + $v['o_service_fee'];
                $totalRepayAmout = Repayment::find()->select('sum(r_total_repay)')->where(['r_orders_id'=>$v['o_id']])->asArray()->column();
                $imgs = new \common\models\UploadFile();
                $this->orderNo = str_replace('.', '', microtime(true)). mt_rand(1000000, 9999999);
                $param_arr = [
                    'service' => 'fastSign',
                    'merchOrderNo'=>$merchOrderNo,
                    'merchContractNo'=>$merchOrderNo,
                    'merchContractImageUrl'=>$imgs->getUrl($v['oi_after_contract']),
                    'realName'=>$v['c_customer_name'],
                    'certNo'=>$v['c_customer_id_card'],
                    'bankCardNo'=>$v['c_banknum'],
                    'mobileNo'=>$v['c_customer_cellphone'],
                    'productName'=>$v['g_goods_name'] .'-'. $v['g_goods_models'],
                    'loanAmount'=>$loanAmount,
                    'totalRepayAmount'=> $totalRepayAmout[0],
                    'operateType'=>'SIGN',
                ];

                $this->notifyUrl = \Yii::$app->params['domain'] ."/borrow/verify-pass-callback";

                $common = $this->getCommonParams();
                $param_arr = array_merge($common, $param_arr);
                $param_arr = $this->prepQueryParams($param_arr);



                // 发起请求
                $http_client = new httpClient();
                $response = $http_client->post($this->api, $param_arr)->send();
                // 记录发送日志
                $operationLog = new OperationLog();
                $operationLog->write(
                    'debug.yijifu.fix-sign-data',
                    '修正易极付签约数据',
                    0,
                    ['send-data' => $param_arr]
                );

                $status = 3; // 接口调用失败
                if($response->getIsOk()){
                    $ret = $response->getData();
                    // 代表接口调用成功
                    if(true === $ret['success']) {
                        $status = 2; // 等待回掉
                    }else{
                        var_dump($ret);die;

                        throw new CustomCommonException($ret['resultMessage'] . ' 易极付错误!');
                    }

                    $operator_id = \Yii::$app->getUser()->getId();
                    $sign = YijifuSign::findOne($v['id']);
                    // 写签约记录表
                    $sign->merchOrderNo = $merchOrderNo;
                    $sign->merchContractNo = $merchOrderNo;
                    $sign->updated_at = $_SERVER['REQUEST_TIME'];
                    $sign->operator_id = $operator_id;
                    $sign->status = $status;
                    $sign->sign = isset($ret['sign'])?$ret['sign']:'';
                    $sign->orderNo = isset($ret['orderNo'])?$ret['orderNo']:'';
                    $sign->logs = $logs;

                    if($sign->save(false) === false){
                        throw new CustomBackendException('修改订单失败');
                    }
                    $reuturn = true;

                }

            }
            $trans->commit();
            return $reuturn;
        }catch (CustomBackendException $e){
            $trans->rollBack();
            return ['status'=> 0, 'message'=> $e->getMessage()];
        }catch (Exception $e){
            $trans->rollBack();
            return ['status'=>0, 'message'=> $e->getMessage()];
        }
    }

}