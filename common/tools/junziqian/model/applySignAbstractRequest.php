<?php
namespace com_junziqian_api_model;
require_once dirname(__FILE__).'/../model/richServiceRequest.php';
require_once dirname(__FILE__).'/../model/sequenceInfo.php';
require_once dirname(__FILE__).'/../model/enum.php';
use com_junziqian_api_model\RichServiceRequest as RichServiceRequest;
use com_junziqian_api_model\AuthenticationLevel as AuthenticationLevel;
use com_junziqian_api_model\AuthLevel as AuthLevel;
use com_junziqian_api_model\SignLevel as SignLevel;
use com_junziqian_api_model\SequenceInfo as SequenceInfo;
use Exception as Exception;
/**
 * 签约请求abstractbean
 * @edit yfx 2016-07-02
 */
class ApplySignAbstractRequest extends RichServiceRequest{
	
	/**合同名称 非空，最长100个字符*/
	public $contractName;
	
	/**合同金额*/
	public $contractAmount;
	
	/**签收方 不超过10个签约人 array<com_junziqian_api_model\Signatory>*/
	public $signatories;
	
	/**备注*/
	public $remark;
	/**
	 * 前置记录，此记录会计录到签约日志中，并保全到日志保全和最终的证据保全中，最大字符不能超过2000字符串
	 */
	public $preRecored;
	
	/**
	 * 签字人所需身份认证级别，目前仅支持选择一种
     * @deprecated 属性已过时，请使用$authLevel
	 * @see com_junziqian_api_model/AuthenticationLevel
	 */
	public $authenticationLevel;

    /**
     * 签字人所需身份认证级别|覆盖ApplySignAbstractRequest中值
     * @see com_junziqian_api_model/AuthLevel
     */
    public $authLevel;

    /**
     * 签字人所需身份认证数量，例要使用5种验证方式，选择其中的2种，则authLevelRange="2"
     * authLevelRange暂定为string，以便后面扩展，
     * authenticationLevel 已过时。使用AuthLevel
     * */
    public $authLevelRange;
	
	public $signLevel;
	/**
	 * 强制认证
	 * 0：不强制认证（需要认证时，认证一次就可），
	 * 1：强制认证（需要认证时，每次都要进行认证）.
	 */
	public $forceAuthentication;
	
	/**
	 * 是否顺序签约
	 * 1：是顺序签约
	 * 0：或其它，不是（默认）
	 */
	public $orderFlag;
	
	/**
	 * 是否需要CA，空0为不需要，1需要
	 */
	public $needCa;

    /**人脸识别等级:默认等级(1-100之间整数)，建议范围(60-79)*/
	public $faceThreshold;

	/**多合同顺序签约Info*/
	public $sequenceInfo;

    /**
     * 是否需要服务端证书，云证书：非1不需要，默认|1需要
     */
    public $serverCa;

    /**
     * 处理方式{@link com_junziqian_api_model/DealType}
     */
    public $dealType;

	function validate(){
		$this->contractName=$this->trim($this->contractName);
		$this->remark=$this->trim($this->remark);
		$this->needCa=self::trim($this->needCa);
        $this->serverCa=self::trim($this->serverCa);
        $this->dealType=self::trim($this->dealType);
		if($this->contractName==''){
			throw new Exception("contractName is null");
		}
		if($this->signatories==null||!is_array($this->signatories)||count($this->signatories)==0){
			throw new \RuntimeException("signatory is null or not a array<com_junziqian_api_model\\Signatory> value");return false;
		}
		if(count($this->signatories,0)>30){
			throw new Exception("signatories.size is gt 30");return false;
		}
		$this->orderFlag=$this->trim($this->orderFlag);
		$orderNumArray=array();
		foreach ($this->signatories as $signatory) {
			if($signatory==null||!is_a($signatory,'common\tools\junziqian\model\Signatory')){
				throw new Exception("signatories.value isn't a Signatory value");return false;
			}
			if(!$signatory->validate()){
				return false;
			}
			if($this->orderFlag=="1"){
				if(!(is_int($signatory->orderNum)&&$signatory->orderNum>0&&$signatory->orderNum<100)){
					throw new Exception($signatory->identityCard." orderNum isn't a int in (1,99)");return false;
				}else{
					array_push($orderNumArray, $signatory->orderNum);
				}
			}
		}
        if($this->orderFlag=="1"&&sizeof(array_unique($orderNumArray))!=sizeof($orderNumArray)){
			throw new Exception("had equal orderNum in signatories");return false;
		}
		//php5.4.0-的参考SignLinkRequest方法
		$this->signatories=json_encode($this->signatories,JSON_UNESCAPED_UNICODE);
		//验证级别赋默认值
        //已过时
		$this->authenticationLevel=$this->trim($this->authenticationLevel);
		if($this->authenticationLevel==''){
			$this->authenticationLevel=AuthenticationLevel::$NONE;
		}
		if(isset($this->authLevel)&&!is_null($this->authLevel)){
		    if(!is_array($this->authLevel)){
		        throw new \RuntimeException("authLevel is not a array");return false;
            }
            foreach ($this->authLevel as $val){
                if(!is_int($val)){
                    throw new \RuntimeException("authLevel.$val is not a int value");return false;
                }
                if($val==AuthLevel::$FACE&&((!isset($this->faceThreshold))||is_null($this->faceThreshold)||(!is_int($this->faceThreshold))||($this->faceThreshold<1&&$this->faceThreshold>100))){
                    throw new \RuntimeException("faceThreshold isn't a int in [1,100]");return false;
                }
            }
            $this->authLevel=json_encode($this->authLevel,JSON_UNESCAPED_UNICODE);
        }
		$this->contractAmount=$this->trim($this->contractAmount);
		if($this->contractAmount==null){
			$this->contractAmount="";
		}
		$this->signLevel=$this->trim($this->signLevel);
		if($this->signLevel==''){
			$this->signLevel=SignLevel::$GENERAL;
		}else if($this->signLevel!=SignLevel::$GENERAL&&$this->signLevel!=SignLevel::$SEAL&&$this->signLevel!=SignLevel::$ESIGNSEAL){
			throw new Exception("signLevel.value isn't a SignLevel value");return false;
		}
		$this->forceAuthentication=$this->trim($this->forceAuthentication);
		if($this->forceAuthentication==''){
			$this->forceAuthentication="0";
		}
		if($this->needCa==''){
			$this->needCa="0";
		}
		if((!is_null($this->sequenceInfo))&&(!$this->sequenceInfo->validate())){
            return false;
        }
        $this->sequenceInfo=json_encode($this->sequenceInfo,JSON_UNESCAPED_UNICODE);
		return parent::validate();
	}
}
?>