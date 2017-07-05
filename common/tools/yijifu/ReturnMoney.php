<?php
/**
 * Created by PhpStorm.
 * User: too
 * Date: 2017/7/5
 * Time: 15:21
 * @author too <hayto@foxmail.com>
 */

namespace common\tools\yijifu;
use common\models\Customer;
use common\models\Orders;
use yii\db\Query;

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
     * @param $merchOrderNo 商户订单号，每次请求都要变，构成：系统订单号+递增序号
     * @param $merchContractNo 商户签约合同号，一直保持不变，直到签约成功，会影响$operateType的值，新签是 SIGN，修改是 MODIFY_SIGN
     * @param $merchContractImageUrl 签约合同照片 支持jpg jpeg bmp png pdf
     * @param $totalRepayAmount 应还总金额，包括各种利息管理费的总和
     * @param string $loanAmount 借款金额【可不填】，显示在用户短信中
     * @param string $operateType 操作类型，根据$merchContractNo，第一次是 SIGN，以后是MODIFY_SIGN【只能修改手机号和银行卡号】
     * @author too <hayto@foxmail.com>
     */
    private function signContractWithCustomer(
        $borrowerName,
        $borrowerIdcardNo,
        $borrowerBankCardNo,
        $borrowerPhoneNo,
        $purchasedProductName,
        $merchOrderNo,
        $merchContractNo,
        $merchContractImageUrl,
        $totalRepayAmount,
        $loanAmount='',
        $operateType = 'SIGN'
    )
    {

        $this->service = 'fastSign';
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
            'operateType'=>$operateType,
        ];

        $common = $this->getCommonParams();
        p($param_arr, $common);
    }


    public function signContractWithCustomerByOrderid($order_id)
    {
        /**
         * todo
         * 1, 找到信息，拼凑签约
         * 2，签约
         * 3，把信息写入数据库
         */
        $data = (new Query())->from(Orders::tableName())
            ->select(['c_customer_name', 'c_customer_id_card', 'c_customer_cellphone', 'c_banknum'])
            ->leftJoin(Customer::tableName(), 'orders.o_customer_id=customer.c_id')
            ->where(['o_id'=>$order_id])->one();
        p($data, true);

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

    private function record()
    {

    }

    public function querySignedCustomer($merchOrderNo)
    {
        $this->service = 'fastSignQuery';
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