<?php
namespace common\tools\junziqian\model;
//require_once dirname(__FILE__).'/../model/richServiceRequest.php';
//require_once dirname(__FILE__).'/../model/enum.php';
use common\tools\junziqian\model\RichServiceRequest as RichServiceRequest;
use common\tools\junziqian\model\OrganizationType as OrganizationType;
use \Exception;
/**
 * 账户创建相关
 * @edit yfx 2016-07-02
 */
class OrganizationCreateRequest extends RichServiceRequest{

	static $v="1.0";
	static $method="organization.create";

	/**传统的多证方式*/
	static $IDENTIFICATION_TYPE_TRADITIONAL = 0;
	/**多证合一*/
	static $IDENTIFICATION_TYPE_ALLINONE = 1;
	
	/**
	 * 邮箱或手机号|必填
	 */
	public $emailOrMobile;
	
	/**
	 * 公司名称|必填
	 */
	public $name;
	
	/**
	 * 公司类型{@link OrganizationType}
	 */
	public $organizationType;
	
	/**
	 * 证件类型：多证0 和多证合一1
	 */
	public $identificationType;
	
	/**
	 * 组织注册编号，营业执照号或事业单位事证号或统一社会信用代码
	 */
	public $organizationRegNo;
	
	/**
	 * 组织注册证件扫描件，营业执照或事业单位法人证书|{@link UploadFile}
	 */
	public $organizationRegImg;
	
	/**
	 * 组织结构代码
	 */
	public $organizationCode;
	
	/**
	 * 组织结构代码扫描件|{@link UploadFile}
	 */
	public $organizationCodeImg;
	
	/**
	 * 税务登记扫描件,事业单位选填，普通企业必选|{@link UploadFile}
	 */
	public $taxCertificateImg;
	
	/**
	 * 签约申请书扫描图{@link UploadFile}
	 */
	public $signApplication;
	
	/**
	 * 法人身份证号
	 */
	public $legalIdentityCard;
	
	/**
	 * 法人姓名
	 */
	public $legalName;
	
	/**
	 * 法人身份证正面|{@link UploadFile}
	 */
	public $legalIdentityFrontImg;
	
	/**
	 * 法人身份证反面|{@link UploadFile}
	 */
	public $legalIdentityBackImg;
	
	function validate(){
		$this->emailOrMobile=self::trim($this->emailOrMobile);
		$this->name=self::trim($this->name);
		$this->organizationType=self::trim($this->organizationType);
		$this->identificationType=self::trim($this->identificationType);
		$this->organizationRegNo=self::trim($this->organizationRegNo);
		$this->organizationCode=self::trim($this->organizationCode);
		if($this->emailOrMobile==''){
			throw new Exception("emailOrMobile is null");return false;
		}
		if($this->name==''){
			throw new Exception("name is null");return false;
		}
		if($this->organizationType==''||($this->organizationType!=OrganizationType::$ENTERPRISE&&$this->organizationType!=OrganizationType::$PUBLIC_INSTITUTION)){
			throw new Exception("organizationType is null or not a OrganizationType value");return false;
		}
		if($this->identificationType==''||($this->identificationType!="0"&&$this->identificationType!="1")){
			throw new Exception("identificationType is null or not a OrganizationType value");return false;
		}
		if($this->organizationRegNo==''){
			throw new Exception("organizationRegNo is null ");return false;
		}
		if($this->organizationRegImg!=null&&!is_a($this->organizationRegImg, "com_junziqian_api_model\UploadFile")){
			throw new Exception("organizationRegImg is not a UploadFile value");return false;
		}
		if($this->organizationCodeImg!=null&&!is_a($this->organizationCodeImg, "com_junziqian_api_model\UploadFile")){
			throw new Exception("organizationCodeImg is not a UploadFile value");return false;
		}
		if($this->taxCertificateImg!=null&&!is_a($this->taxCertificateImg, "com_junziqian_api_model\UploadFile")){
			throw new Exception("taxCertificateImg is not a UploadFile value");return false;
		}
		if($this->signApplication!=null&&!is_a($this->signApplication, "com_junziqian_api_model\UploadFile")){
			throw new Exception("signApplication is not a UploadFile value");return false;
		}
		
		//图片必填项校验
		if($this->identificationType=="0"){//多证
			if($this->organizationCode==''){
				throw new Exception("organizationCode is null ");return false;
			}
			if($this->organizationRegImg==null){
				throw new Exception("organizationRegImg is null ");return false;
			}
			if($this->organizationType==OrganizationType::$ENTERPRISE){
				if($this->taxCertificateImg==null){
					throw new Exception("taxCertificateImg is null ");return false;
				}
			}
		}
		
		$this->legalIdentityCard=self::trim($this->legalIdentityCard);
		$this->legalName=self::trim($this->legalName);
		if($this->legalIdentityFrontImg!=null&&!is_a($this->legalIdentityFrontImg, "common\\tools\\junziqian\\model\\UploadFile")){
			throw new Exception("legalIdentityFrontImg is not a UploadFile value");return false;
		}
		if($this->legalIdentityBackImg!=null&&!is_a($this->legalIdentityBackImg, "common\\tools\\junziqian\\model\\UploadFile")){
			throw new Exception("legalIdentityBackImg is not a UploadFile value");return false;
		}
		return parent::validate();
	}

	
	/**
	 * 不签名的filed
	 */
	function getIgnoreSign(){
		//确认图片信息不做签名
		$ignoreSign=array('organizationRegImg','organizationCodeImg','taxCertificateImg','signApplication','legalIdentityFrontImg','legalIdentityBackImg');
		return $ignoreSign;
	}
}
?>