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

class Loan extends AbstractYijifu
{

    /**
     * 用户放款
     * @author lilaotou <liwansen@foxmail.com>
     * @param $serviceCdoe 服务码
     * @param $amount 代发金额
     * @param $outOrderNo 商户订单号
     * @param $contractUrl 合同照片
     * @param $realName 收款人姓名
     * @param $mobileNo 手机号
     * @param $certNo 身份证号
     * @param $bankCardNo 银行账户
     */
    public function userLoan(){
        $_data = [
            'partnerId'=>$this->partnerId,
            'protocol'=>$this->protocol,
            'version'=>$this->version,
            'orderNo'=>1234,
            'signType'=>$this->signType,
            'sign'=>'',
            'service'=>'fastSign', // 服务码
            'operateType'=>'SIGN'  // 操作类型，默认SIGN签约，MODIFY_SIGN修改
        ];
    }

}