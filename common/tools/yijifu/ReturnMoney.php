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
use common\models\YijifuSignReturnmoney;
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
     * @param $order_id 通过订单id生成下面两个，下面两个就不用传了
     * @param $merchOrderNo 商户订单号，每次请求都要变，构成：系统订单号+递增序号
     * @param $merchContractNo 商户签约合同号，一直保持不变，直到签约成功，
     *
     *
     * @param $merchContractImageUrl 签约合同照片 支持jpg jpeg bmp png pdf
     * @param $totalRepayAmount 应还总金额，包括各种利息管理费的总和
     * @param string $loanAmount 借款金额【可不填】，显示在用户短信中
     *
     * @return false接口请求失败   array接口返回信息
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
        $order_id, // 系统核心订单号
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
                throw new CustomCommonException('参数不全');
            }
        }

        // 设置服务码
        $this->service = 'fastSign';

        // 生成ID
        $_data = (new Query())->from(YijifuSignReturnmoney::tableName())
            ->where(['order_id'=>$order_id, 'status'=>2])
            ->one();
        if(false === $_data){
            $merchOrderNo = $order_id.'-1';
            $merchContractNo = $order_id.'-1';
        }else{
            if($_data['status'] == 1){
                throw new CustomCommonException('该订单已经成功签约');
            }
            $merchOrderNo = $order_id. '-'. (substr($_data['merchOrderNo'], -1)+1);
            $merchContractNo = $order_id. '-'. (substr($_data['merchContractNo'], -1)+1);
        }

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
            'loanAmount'=>$loanAmount,
            'totalRepayAmount'=>$totalRepayAmount,
            'operateType'=>'SIGN',
        ];
        $this->notifyUrl = \Yii::$app->urlManager->createUrl(['site/async']);
        $common = $this->getCommonParams();
        $param_arr = array_merge($common, $param_arr);
        $param_arr = $this->prepQueryParams($param_arr);

        // 发起请求
        $status = 2;
        $http_client = new httpClient();
        $response = $http_client->post($this->api, $param_arr)/*->setFormat(httpClient::FORMAT_JSON)*/->send();
        if($response->getIsOk()){
            $ret = $response->getData();
            if($ret['resultCode'] === 'EXECUTE_SUCCESS'){
                $status = 1;
            }
        }else{
            $ret = false;
        }

        $operator_id = 101;

        // 写签约记录表
        if(false === $_data){
            $wait_inster_data = [
                'order_id'=>$order_id,
                'merchOrderNo'=>$merchOrderNo,
                'merchContractNo'=>$merchContractNo,
                'deductAmount'=>0,
                'operateType'=>1, // 签约
                'created_at'=>$_SERVER['REQUEST_TIME'],
                'operator_id'=>$operator_id,
                'status'=>$status
            ];
            \Yii::$app->getDb()->createCommand()->insert(YijifuSignReturnmoney::tableName(), $wait_inster_data)->execute();
        }else{
            $_data['merchOrderNo'] = $merchOrderNo;
            $_data['merchContractNo'] = $merchContractNo;
            $_data['status'] = $status;
            $_data['operator_id'] = $operator_id;
            $_data['updated_at']=$_SERVER['REQUEST_TIME'];
            \Yii::$app->getDb()->createCommand()->update(YijifuSignReturnmoney::tableName(), $_data, ['id'=>$_data['id']])->execute();
        }
        return $ret;
    }


    /**
     * @deprecated 暂时废弃中
     * @param $order_id
     * @author too <hayto@foxmail.com>
     */
    public function signContractWithCustomerByOrderid($order_id)
    {
        /**
         * todo
         * 1, 找到信息，拼凑签约
         * 2，签约
         * 3，把信息写入数据库
         */
        /*$data = (new Query())->from(Orders::tableName())
            ->select(['c_customer_name', 'c_customer_id_card', 'c_customer_cellphone', 'c_banknum'])
            ->leftJoin(Customer::tableName(), 'orders.o_customer_id=customer.c_id')
            ->where(['o_id'=>$order_id])->one();
        p($data, true);*/

        $this->signContractWithCustomer('张飞',
            '510623199912250210',
            '6222555511112222',
            18990232111,
            'iPhone7Plus',
            'tn1111',
            'ht1111',
            'http://php.net/images/to-top@2x.png',
            12.5,
            '',
            'SIGN');
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
     * 发起代扣
     *
     *
     * 服务码 fastDeduct
     *
     * @author too <hayto@foxmail.com>
     */
    public function deduct()
    {
        $this->service = 'fastDeduct';
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