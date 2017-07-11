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

}