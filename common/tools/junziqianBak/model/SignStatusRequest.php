<?php
namespace com_junziqian_api_model;
require_once dirname(__FILE__).'/../model/richServiceRequest.php';
use com_junziqian_api_model\RichServiceRequest as RichServiceRequest;
use Exception as Exception;
/**
 * 请求得到签约状态
 * @edit yfx 2016-11-01
 */
class SignStatusRequest extends RichServiceRequest{

	static $v="1.0";
	static $method="sign.status";

	/**
	 * 发起签约的申请编号,由发起签约接口返回
	 */
	public $applyNo;
	
	/**
	 * 签约人，必须是发起签约接口传入签约人中，姓名、身份证件一致的签约人
	 * 
	 * 手机号码和地址可以不一样
	 * 此字段填写：查询当前签约人的签字状态
	 * 未填:查询整个合同的签字状态 签字状态结果值为signStatus(@link enum.php/SignStatus)
	 */
	public $signatory;
	
	/**
	 * 验证方法，也有一些转换关系在里面
	 * @see \com_junziqian_api_model\RichServiceRequest::validate()
	 */
	function validate(){
		$this->applyNo=self::trim($this->applyNo);
		if($this->applyNo==''){
			throw new Exception("applyNo is null");return false;
		}
		if(!is_null($this->signatory)){
			if(!is_a($this->signatory,'com_junziqian_api_model\Signatory')){
				throw new Exception("signatory is null or not a Signatory value");return false;
			}
			if(!$this->signatory->validate()){
				return false;
			}
			$this->signatory=$this->signatory->toJson();
		}
		return parent::validate();
	}
}
?>