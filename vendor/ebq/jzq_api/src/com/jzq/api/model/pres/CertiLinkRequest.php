<?php
/**
 * User: huhu
 * DateTime: 2017-06-06 0006 14:27
 */
namespace com\jzq\api\model\pres;
use com\jzq\api\model\bean\Signatory;
use org\ebq\api\model\RichServiceRequest;
use RuntimeException;

class CertiLinkRequest extends RichServiceRequest{

    static $v="1.0";
    static $method="certi.link";
    /**
     * 发起签约的申请编号,由发起签约接口返回
     */
    public $applyNo;

    /**
     * 签约人，必须是发起签约接口传入签约人中，姓名、身份证件一致的签约人
     * 手机号码和地址可以不一样
     */
    /**
     * @var Signatory
     */
    public $signatory;

    function validate(){
        $this->applyNo=self::trim($this->applyNo);
        if($this->applyNo==''){
            throw new RuntimeException("applyNo is null");
        }
        if($this->signatory==null||!is_a($this->signatory,Signatory::class)){
            throw new RuntimeException("signatory is null or not a Signatory value");
        }
        if(!$this->signatory->validate()){
            return false;
        }
        $this->signatory=$this->signatory->toJson();
        return parent::validate();
    }
}