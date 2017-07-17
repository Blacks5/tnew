<?php
/**
 * Created by PhpStorm.
 * User: too
 * Date: 2017/7/5
 * Time: 15:21
 * @author too <hayto@foxmail.com>
 */

namespace common\tools\yijifu;
use common\components\CustomCommonException;
use common\models\Customer;
use common\models\Orders;
use common\models\YijifuDeduct;
use common\models\YijifuSign;
use yii\db\Query;
use \yii\httpclient\Client as httpClient;

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
//            'loanAmount'=>$loanAmount, // 可以不填的，优先不填
            'totalRepayAmount'=>$totalRepayAmount,
            'operateType'=>'SIGN',
        ];
//        $this->notifyUrl = \Yii::$app->urlManager->createAbsoluteUrl(['borrow/verify-pass-callback']);
        $this->notifyUrl = "http://local80t.ngrok.cc/borrow/verify-pass-callback";

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
            'deductAmount'=>0,
            'operateType'=>1, // 签约
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
    public function deduct($o_serial_id, $merchSignOrderNo, $deductAmount)
    {
        $this->service = 'fastDeduct';
        $randString = \Yii::$app->getSecurity()->generateRandomString(4);
        $merchOrderNo = $o_serial_id. '-'. $randString;
        $param_arr = [
            'merchOrderNo'=>$merchOrderNo, // 自己生成的，貌似和业务无关，相当于自增主键的作用
            'merchSignOrderNo'=>$merchSignOrderNo,
            'deductAmount'=>$deductAmount
        ];
        $common_param = $this->getCommonParams();
        $param = array_merge($common_param, $param_arr);
        $param = $this->prepQueryParams($param);

        $client = new httpClient();
        $response = $client->post($this->api, $param)->send();

        $status = 8; //接口调用失败
        if($response->getIsOk()){
            $ret = $response->getData();
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
            'status'=>$status
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
    public function queryDeduct()
    {
        $this->service = 'fastDeductQuery';
    }
}