<?php
namespace com\jzq\api\model\account;
use org\ebq\api\model\RichServiceRequest;
use RuntimeException;
use org\ebq\api\model\bean\UploadFile;
use com\jzq\api\model\bean\Signatory;
use com\jzq\api\model\menu\IdentityType;
/**
 * @author yfx 2017-06-02
 * 签约请求上传文件方式
 */
class CertiAuthRequest extends RichServiceRequest{

    static $v="1.0";
    static $method="serti.auth";

    /**
     * 要上传的申请表
     * @var UploadFile
     */
	public $applyTable;
	
    /**
     * 申请人信息
     * @var Signatory
     */
	public $signatory;
	
	function validate(){
		if($this->applyTable==null||!is_a($this->applyTable, UploadFile::class)){
			throw new RuntimeException("file is null or not a UploadFile value");
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
	
    /**
     * 个人申请
     * @param $mobile
     * @param $identityCard IdentityType
     * @param $fullName
     */
	function withPersonalSignatory($mobile,$identityCard,$fullName){
		$mobile=$this->trim($mobile);
		$identityCard=$this->trim($identityCard);
		$fullName=$this->trim($fullName);
		if($mobile==''||$identityCard==''||$fullName==''){
			throw new RuntimeException("mobile|identityCard|fullName has null value");
		}
		$signatory=new Signatory();
		$signatory->mobile=$mobile;
		$signatory->identityCard=$identityCard;
		$signatory->fullName=$fullName;
		$signatory->setSignatoryIdentityType(IdentityType::$IDCARD);
		$this->signatory=$signatory;
	}
	
    /**
     * 企业申请
     * @param $identityCard
     * @param $email
     */
	function withEnterpriseSignatory($identityCard,$email){
		$email=$this->trim($email);
		$identityCard=$this->trim($identityCard);
		if($email==''||$identityCard==''){
			throw new RuntimeException("email|identityCard has null value");
		}
		$signatory=new Signatory();
		$signatory->email=$email;
		$signatory->identityCard=$identityCard;
		$signatory->setSignatoryIdentityType(IdentityType::$BIZLIC);
		$this->signatory=$signatory;
	}
	
	/**
	 * 不签名的filed
	 */
	function getIgnoreSign(){
		$ignoreSign=array('applyTable');
		return $ignoreSign;
	}
}