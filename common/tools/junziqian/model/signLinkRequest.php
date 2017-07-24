<?php
namespace com_junziqian_api_model;
require_once dirname(__FILE__).'/../model/richServiceRequest.php';
use com_junziqian_api_model\RichServiceRequest as RichServiceRequest;
use Exception as Exception;
/**
 * 请求得到签约地址
 * @edit yfx 2016-07-02
 */
class SignLinkRequest extends RichServiceRequest{

	static $v="1.0";
	static $method="sign.link";

	/**
	 * 发起签约的申请编号,由发起签约接口返回
	 */
	public $applyNo;
	
	/**
	 * 签约人，必须是发起签约接口传入签约人中，姓名、身份证件一致的签约人
	 * 手机号码和地址可以不一样
	 */
	public $signatory;
	
	function validate(){
		$this->applyNo=self::trim($this->applyNo);
		if($this->applyNo==''){
			throw new Exception("applyNo is null");return false;
		}
		if($this->signatory==null||!is_a($this->signatory,'com_junziqian_api_model\Signatory')){
			throw new Exception("signatory is null or not a Signatory value");return false;
		}
		if(!$this->signatory->validate()){
			return false;
		}
		$this->signatory=$this->signatory->toJson();
		return parent::validate();
	}
}
?>