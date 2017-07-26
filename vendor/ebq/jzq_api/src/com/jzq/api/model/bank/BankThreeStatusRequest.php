<?php
/**
 * User: huhu
 * DateTime: 2017-06-06 0006 14:27
 */
namespace com\jzq\api\model\bank;
use org\ebq\api\model\RichServiceRequest;

class BankThreeStatusRequest extends RichServiceRequest{

    static $v="1.0";
    static $method="bank.three.status";
    /**
     * 3要素校验返回的编号
     */
    public $orderNo;

    function validate(){
        assert(!is_null($this->orderNo),"银行卡号不能为空");
        return parent::validate();
    }
}