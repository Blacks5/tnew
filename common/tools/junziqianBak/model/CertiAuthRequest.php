<?php
namespace common\tools\junziqian\model;
//require_once dirname(__FILE__).'/../model/richServiceRequest.php';
//require_once dirname(__FILE__) . '/../model/UploadFile.php';
//require_once dirname(__FILE__) . '/../model/Signatory.php';
//require_once dirname(__FILE__).'/../model/enum.php';
use common\tools\junziqian\model\RichServiceRequest as RichServiceRequest;
use common\tools\junziqian\model\UploadFile as UploadFile;
use common\tools\junziqian\model\Signatory as Signatory;
use common\tools\junziqian\model\IdentityType as IdentityType;
use \Exception;
/**
 * @author yfx 2016-07-02
 * 签约请求上传文件方式
 */
class CertiAuthRequest extends RichServiceRequest{
	/**要上传的申请表*/
	public $applyTable;
	
	/**申请人信息*/
	public $signatory;
	
	function validate(){
		if($this->applyTable==null||!is_a($this->applyTable, "common\\tools\\junziqian\\model\\UploadFile")){
			throw new Exception("file is null or not a UploadFile value");return false;
		}
		if($this->signatory==null||!is_a($this->signatory,'common\\tools\\junziqian\\model\\Signatory')){
			throw new Exception("signatory is null or not a Signatory value");return false;
		}
		if(!$this->signatory->validate()){
			return false;
		}
		$this->signatory=$this->signatory->toJson();
		return parent::validate();
	}
	
	/**个人申请*/
	function withPersonalSignatory($mobile,$identityCard,$fullName){
		$mobile=$this->trim($mobile);
		$identityCard=$this->trim($identityCard);
		$fullName=$this->trim($fullName);
		if($mobile==''||$identityCard==''||$fullName==''){
			throw new Exception("mobile|identityCard|fullName has null value");return false;
		}
		$signatory=new Signatory();
		$signatory->mobile=$mobile;
		$signatory->identityCard=$identityCard;
		$signatory->fullName=$fullName;
		$signatory->setSignatoryIdentityType(IdentityType::$IDCARD);
		$this->signatory=$signatory;
	}
	
	/**企业申请*/
	function withEnterpriseSignatory($identityCard,$email){
		$email=$this->trim($email);
		$identityCard=$this->trim($identityCard);
		if($email==''||$identityCard==''){
			throw new Exception("email|identityCard has null value");return false;
		}
		$signatory=new Signatory();
		$signatory->email=$email;
		$signatory->identityCard=$identityCard;
		$signatory->setSignatoryIdentityType(IdentityType::$BIZLIC);
		$this->signatory=$signatory;
	}
	
	static $v="1.0";
	static $method="serti.auth";
	
	/**
	 * 不签名的filed
	 */
	function getIgnoreSign(){
		$ignoreSign=array('applyTable');
		return $ignoreSign;
	}
}
?>