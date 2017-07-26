<?php
namespace com\jzq\api\model\account;
use org\ebq\api\model\RichServiceRequest;
use RuntimeException;
/**
 * 账户审核状态
 * @edit yfx 2016-07-12
 */
class OrganizationAuditStatusRequest extends RichServiceRequest{

	static $v="1.0";
	static $method="organization.audit.status";
	
	/**
	 * 邮箱或手机号|必填
	 */
	public $emailOrMobile;
	
	function validate(){
		$this->emailOrMobile=self::trim($this->emailOrMobile);
		if($this->emailOrMobile==''){
			throw new RuntimeException("emailOrMobile is null");
		}
		return parent::validate();
	}
}
?>