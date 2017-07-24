<?php
namespace com_junziqian_api_model;
require_once dirname(__FILE__).'/../model/richServiceRequest.php';
require_once dirname(__FILE__).'/../model/enum.php';
use com_junziqian_api_model\RichServiceRequest as RichServiceRequest;
use com_junziqian_api_model\OrganizationType as OrganizationType;
use Exception as Exception;
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
			throw new Exception("emailOrMobile is null");return false;
		}
		return parent::validate();
	}
}
?>