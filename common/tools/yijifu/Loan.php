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

class Loan extends AbstractYijifu
{

    /**
     * 用户放款
     * @param $amount 代发金额
     * @param $outOrderNo 商户订单号
     * @param $contractUrl 合同照片
     * @param $realName 收款人姓名
     * @param $mobileNo 手机号
     * @param $certNo 身份证号
     * @param $bankCardNo 银行账户
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

        // 设置服务码
        $this->service = 'yxtQuicklyRemittance';

        //构造api参数
        $params_arr = array(
            'amount'=>$amount,
            'outOrderNo'=>$outOrderNo,
            'contractUrl'=>$contractUrl,
            'realName'=>$realName,
            'mobileNo'=>$mobileNo,
            'certNo'=>$certNo,
            'bankCardNo'=>$bankCardNo
        );

    }

}