<?php
/**
 * Created by PhpStorm.
 * User: Brucelee
 * Date: 2017/7/4
 * Time: 22:31
 * Author: lilaotou <liwansen@foxmail.com>
 */

namespace common\tools\yijifu;
use common\models\Stores;
use common\models\Orders;
use common\components\CustomCommonException;
use common\models\YijifuLoan;
use Yii;
use yii\db\Query;
use \yii\httpclient\Client as httpClient;

class Loan extends AbstractYijifu
{

    /**
     * 根据传入订单id判断放款方式(对公或对私),暂时弃用
     * 只获取还款中的订单
     * @param $order_id 系统订单id
     * @author lilaotou <liwansen@foxmail.com>
     *
     */
    public function lanLocation($order_id){
        $_data = (new Query())->from(Orders::tableName())
            ->join('LEFT JOIN', 'stores', 'orders.o_store_id = stores.s_id')
            ->where(['orders.order_id'=>$order_id,'orders.o_status'=>10])
            ->one();

        if($_data === false){
            throw new CustomCommonException('系统错误!');
        }else{
            if($_data['s_bank_is_private'] == 1){
                //对私
              //  $this->userLoan($amount,$outOrderNo,$contractUrl,$realName,$mobileNo,$certNo,$bankCardNo);
            }else{
                //对公
               // $this->storeLoan($amount, $outOrderNo, $contractUrl,$realName, $mobileNo, $certNo, $bankCardNo, $bankCode, $bankName, $sellerBankProvince, $sellerBankCity, $sellerBankAddress);
            }
        }
    }



    /**
     * 用户放款-对个人账户
     * @param $amount 代发金额
     * @param $outOrderNo 商户订单号,此值就是系统的订单号
     * @param $contractUrl 合同照片
     * @param $realName 收款人姓名
     * @param $mobileNo 手机号
     * @param $certNo 身份证号,身份证最后一位为字母，字母必须为大写
     * @param $bankCardNo 个人银行账户
     *
     * @author lilaotou <liwansen@foxmail.com>
     */
    public function userLoan(
        $amount,
        $outOrderNo,
        $contractUrl,
        $realName,
        $mobileNo,
        $certNo,
        $bankCardNo
    ){
        // 检测参数
        $_ = func_get_args();
        foreach ($_ as $v){
            if(false === !empty($v)){
                throw new CustomCommonException('参数不全');
            }
        }

        //只有未放款的订单才能放款
        $_data = (new Query())->from(YijifuLoan::tableName())
            ->where(['order_id'=>$outOrderNo])
            ->one();

        if($_data&&($_data['status'] == 2)){
            throw new CustomCommonException('该订单已经成功放款!');
        }

        // 设置服务码
        $this->service = 'yxtQuicklyRemittance';

        //构造api参数
        $param_arr = array(
            'amount'=>$amount,
            'outOrderNo'=>$outOrderNo,
            'contractUrl'=>$contractUrl,
            'realName'=>$realName,
            'mobileNo'=>$mobileNo,
            'certNo'=>$certNo,
            'bankCardNo'=>$bankCardNo
        );

        //创建一部回调链接
        //$this->notifyUrl = \Yii::$app->urlManager->createAbsoluteUrl(['site/asyncloan']);
        $this->notifyUrl = 'http://leemoo.ngrok.cc/site/asyncloan';

        $common = $this->getCommonParams();
        $param_arr = array_merge($common, $param_arr);
        $param_arr = $this->prepQueryParams($param_arr);

        //发起请求
        $http_client = new httpClient();
        $response = $http_client->post($this->api, $param_arr)->send();
        if($response->getIsOk()){
            $ret = $response->getData();
        }else{
            $ret = false;
        }
        return $ret;
    }

    /**
     * 用户放款-对公账户
     * @param $amount 代发金额
     * @param $outOrderNo 商户订单号,此值就是系统的订单号
     * @param $contractUrl 合同照片
     * @param $realName 公司账户的开户名
     * @param $mobileNo 公司法人的手机号
     * @param $certNo 公司法人的身份证号；若身份证最后一位为字母，字母必须为大写。
     * @param $bankCardNo 公司银行账户
     * @param $bankCode 银行编码 如ABC
     * @param $bankName 银行名称 如中国农业银行
     * @param $sellerBankProvince 商家银行开户行所在省级名称 如四川省
     * @param $sellerBankCity 商家银行开户行所在市级名称 如成都市
     * @param $sellerBankAddress 商家银行开户行地址 如青羊区XX支行
     *
     * @author lilaotou <liwansen@foxmail.com>
     */
    public function storeLoan(
        $amount,
        $outOrderNo,
        $contractUrl,
        $realName,
        $mobileNo,
        $certNo,
        $bankCardNo,
        $bankCode,
        $bankName,
        $sellerBankProvince,
        $sellerBankCity,
        $sellerBankAddress
    ){
        // 检测参数
        $_ = func_get_args();
        foreach ($_ as $v){
            if(false === !empty($v)){
                throw new CustomCommonException('参数不全');
            }
        }

        //只有未放款的订单才能放款
        $_data = (new Query())->from(YijifuLoan::tableName())
            ->where(['order_id'=>$outOrderNo])
            ->one();

        if($_data&&($_data['status'] == 2)){
            throw new CustomCommonException('该订单已经成功放款!');
        }

        // 设置服务码
        $this->service = 'yxtQuicklyRemittance';

        //构造api参数
        $param_arr = array(
            'amount'=>$amount,
            'outOrderNo'=>$outOrderNo,
            'contractUrl'=>$contractUrl,
            'realName'=>$realName,
            'mobileNo'=>$mobileNo,
            'certNo'=>$certNo,
            'bankCardNo'=>$bankCardNo,
            'bankCode'=>$bankCode,
            'bankName'=>$bankName,
            'sellerBankProvince'=>$sellerBankProvince,
            'sellerBankCity'=>$sellerBankCity,
            'sellerBankAddress'=>$sellerBankAddress
        );

        //创建一部回调链接
        //$this->notifyUrl = \Yii::$app->urlManager->createAbsoluteUrl(['site/asyncloan']);
        $this->notifyUrl = 'http://leemoo.ngrok.cc/site/asyncloan';

        $common = $this->getCommonParams();
        $param_arr = array_merge($common, $param_arr);
        $param_arr = $this->prepQueryParams($param_arr);

        //发起请求
        $http_client = new httpClient();
        $response = $http_client->post($this->api, $param_arr)->send();
        if($response->getIsOk()){
            $ret = $response->getData();
        }else{
            $ret = false;
        }
        return $ret;
    }

    /**
     * 查询放款记录
     * @param $externalOrderNo 外部订单号(系统的订单号)
     * @param $contractNo 代发流水号
     * @return array|bool|mixed false表示接口请求失败
     *
     * @throws CustomCommonException
     * @author too <hayto@foxmail.com>
     */
    public function querySignedCustomer($externalOrderNo,$contractNo)
    {
        if(false === !empty($externalOrderNo)){
            throw new CustomCommonException('缺少参数');
        }
        if(false === !empty($contractNo)){
            throw new CustomCommonException('缺少参数');
        }
        $this->service = 'installmentRemittanceQuery';
        $common = $this->getCommonParams();
        $param_arr = [
            'externalOrderNo'=>$externalOrderNo,
            'contractNo'=>$contractNo
        ];
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
     * 根据传入银行名称,接口需要银行名称,银行编码
     * @param $s_bank_sub 系统中商家的结算账户开户行支行
     * @param $type 1对私   2对公
     * @author lilaotou <liwansen@foxmail.com>
     */
    public function getbancode($s_bank_sub,$type){
        if($type == 1){
            $bank_arr = array(
                '中国银行'=>'BOC',
                '中国农业银行'=>'ABC',
                '中国工商银行'=>'ICBC',
                '中国建设银行'=>'CCB',
                '中国光大银行'=>'CEB',
                '兴业银行'=>'CIB',
                '民生银行'=>'CMBC',
                '中信银行'=>'CITIC',
                '重庆农村商业银行'=>'CQRCB',
                '中国邮政储蓄银行'=>'PSBC',
                '平安银行'=>'PINGANBK',
                '交通银行'=>'COMM',
                '广东发展银行'=>'CGB'
            );
        }else{
            $bank_arr = array(
                '中国银行'=>'BOC',
                '中国农业银行'=>'ABC',
                '中国工商银行'=>'ICBC',
                '中国建设银行'=>'CCB',
                '中国光大银行'=>'CEB',
                '兴业银行'=>'CIB',
                '民生银行'=>'CMBC',
                '中信银行'=>'CITIC',
                '重庆农村商业银行'=>'CQRCB',
                '中国邮政储蓄银行'=>'PSBC',
                '平安银行'=>'PINGANBK',
                '交通银行'=>'COMM',
                '广东发展银行'=>'CGB',
                '华夏银行'=>'HXB'
            );
        }
        $return_data = array();
        foreach($bank_arr as $k=>$v){
            if(strpos($s_bank_sub,$k)){
                $return_data['bankname'] = $k;
                $return_data['bankcode'] = $v;
                break;
            }
        }
        return $return_data;
    }

}