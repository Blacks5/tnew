<?php
namespace com\jzq\api\model\sign;
use org\ebq\api\model\RichServiceRequest;
use com\jzq\api\model\bean\Signatory;
use RuntimeException;
/**
 * 发起签约提醒短信
 * @edit yfx 2016-07-02
 */
class SignNotifyRequest extends RichServiceRequest{

	/**签字提醒*/
	static $NOTIFYTYPE_SIGN=1;
	/**到期前提醒*/
	static $NOTIFYTYPE_SIGN_EXPIRE_PRE=2;
	/**到期后提醒*/
	static $NOTIFYTYPE_SIGN_EXPIRE_FIX=3;
	
	static $v="1.0";
	static $method="sign.notify";

	/**
	 * 发起签约的申请编号,由发起签约接口返回
	 */
	public $applyNo;

    /**
     * 签约人，必须是发起签约接口传入签约人中，姓名、身份证件一致的签约人
     * 手机号码和地址可以不一样
     * @var Signatory
     */
	public $signatory;
	
	/**
	 * 提醒类型
	 * */
	public $signNotifyType;
	
	/**
	 * 返回地址|主要用于生成签约完成后的回调地址，ex. http://xxxx.xxxx.xxxx/**
	 * */
	public $backUrl;
	
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
		if(!isset($this->signNotifyType)){
			throw new RuntimeException("signNotifyType is null");
		}
		$this->backUrl=self::trim($this->backUrl);
		$this->signatory=$this->signatory->toJson();
		return parent::validate();
	}
}
?>