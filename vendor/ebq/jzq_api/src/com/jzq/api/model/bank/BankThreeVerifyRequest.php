<?php
/**
 * User: huhu
 * DateTime: 2017-06-06 0006 14:27
 */
namespace com\jzq\api\model\bank;
use org\ebq\api\model\RichServiceRequest;

class BankThreeVerifyRequest extends RichServiceRequest{

    static $v="1.0";
    static $method="bank.three.verify";

    /**银行卡账号*/
    public $cardNo;

    /**银行卡账户名*/
    public $cardName;

    /**证件类型*/
    public $certType;

    /**证件卡号*/
    public $certNo;

    /**异步回调地址*/
    public $notifyUrl;

    function validate(){
        $this->certType=self::trim($this->certType);
        assert(!is_null($this->cardNo),"银行卡号不能为空");
        assert(!is_null($this->cardName),"银行卡账户名称不能为空");
        assert($this->certType!='',"证件类型不能为空");
        assert(!is_null($this->certNo),"证件号码不能为空");
        return parent::validate();
    }
}