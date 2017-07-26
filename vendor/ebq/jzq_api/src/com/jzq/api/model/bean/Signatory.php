<?php
namespace com\jzq\api\model\bean;
use org\ebq\api\tool\RopUtils;
use RuntimeException;
/**
 * 签字人信息
 * @author huhu 2016-07-02
 */
class Signatory{

	/**用户类型（个人、企业）*/
	public $userType;
	
	/**姓名，不超过50个字符*/
	public $fullName;
	
	/**身份类型{@link identityType.code}*/
	public $identityType;
	
	/**证号，不超过50个字符*/
	public $identityCard;
	
	/**手机号码，11个字符*/
	public $mobile;

    /**邮箱，企业专门*/
	public $email;
	
	/**地址，不超过100个字符*/
	public $address;
	
	/**
	 * 签字人所需身份认证级别，目前仅支持选择一种|覆盖ApplySignAbstractRequest中值
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
	
	/**
	 * 强制认证|覆盖ApplySignAbstractRequest中值
	 * 0：不强制认证（需要认证时，认证一次就可），
	 * 1：强制认证（需要认证时，每次都要进行认证）.
	 */
	public $forceAuthentication;
	
	/**
	 * 签字类型，标准图形章或公章、手写|覆盖ApplySignAbstractRequest中值
	 * @see com_junziqian_api_model/SignLevel
	 */
	public $signLevel;
	
	/**
	 * 强制添加现场
	 * 0或null：不强制添加现场
	 * 1：强制添加现场
	 */
	public $forceEvidence;
	
	/**
     * 页数，比例offsetX,offsetY:0-0.5-1;(x,y为比例，值范设置为0-1之间)
     * json数组like:[{"page":0,","chaptes":[{"offsetX":0.12,"offsetY":0.23}]},{"page":1,"chaptes":[{"offsetX":0.45,"offsetY":0.67}]}]
     * chapte签字个字不能超过20个。
     */
	public $chapteJson;

    /**
     * ApplySignAbstractRequest->formChapteFlag为1时,表单域的id名称
     * @var string
     */
    public $chapteName;
	
	/**
	 * 强制不添加现场
	 * 1强制不添加
	 * 0不强制（默认为0）
	 */
	public $noNeedEvidence;
	
	/**
	 * 顺序签约的顺序1-99之间的整数
     *
	 */
	public $orderNum;

    /**
     * 买保险年
     * 0：不买
     * 1-3：买(签完)年
     */
	public $insureYear;

    /**
     * 签约发起方可指定不验证就直接签约
     * 0：验证(默认)
     * 1：不验证
     */
    public $noNeedVerify;


    /**
     * 是否自动云证书签约，0或null不自动，1自动(当且只当合同处理方式为部份自动时有效)
     * 0或null：不自动
     * 1：自动
     */
    public $serverCaAuto;

    /**
     * 强制阅读时间，int:1-300
     */
    public $readTime;


	function setSignatoryIdentityType($identityType) {
		if(!is_array($identityType)){
			throw new RuntimeException("identityType is not array");
		}
		$this->identityType=$identityType["code"];
		$this->userType=$identityType["type"];
		return true;
	}
	
	function setChapteJson($chapteJson) {
		if(isset($chapteJson)){
			$this->chapteJson=RopUtils::json_encode($chapteJson);
		}
	}
	
	function validate(){
		$this->identityCard=self::trim($this->identityCard);
		$this->identityType=self::trim($this->identityType);
		$this->forceEvidence=self::trim($this->forceEvidence);
		$this->noNeedEvidence=self::trim($this->noNeedEvidence);
        $this->serverCaAuto=self::trim($this->serverCaAuto);
        if(!is_null($this->insureYear)){
            if(!is_numeric($this->insureYear)){
                throw new RuntimeException("signatory.insureYear is not numeric");
            }
            if($this->insureYear<1||$this->insureYear>3){
                throw new RuntimeException("signatory.insureYear is not in [1-3]");
            }
        }
        $this->noNeedVerify=self::trim($this->noNeedVerify);
		if($this->identityCard==''){
			throw new RuntimeException("signatory.identityCard is null");
		}
		if($this->identityType==null){
			throw new RuntimeException("signatory.identityType is null");
		}
		if(!(is_numeric($this->userType)&&($this->userType==0||$this->userType==1))){
			throw new RuntimeException("signatory.userType is error");
		}
        if(isset($this->authLevel)&&!is_null($this->authLevel)){
            if(!is_array($this->authLevel)){
                throw new RuntimeException("authLevel is not a array");
            }
            foreach ($this->authLevel as $val){
                if(!is_int($val)){
                    throw new RuntimeException("authLevel.$val is not a int value");
                }
            }
        }

		if(isset($this->forceAuthentication)&&is_string($this->forceAuthentication)){
			$this->forceAuthentication=intval($this->forceAuthentication);
		}
		if(isset($this->signLevel)&&is_string($this->signLevel)){
			$this->signLevel=intval($this->signLevel);
		}
		if(isset($this->readTime)){
		    if(!is_int($this->readTime)){
                throw new RuntimeException("readTime is not a int value");
            }else if($this->readTime<=0||$this->readTime>300){
                throw new RuntimeException("readTime need in [1-300]");
            }
        }
		return true;
	}
	
	function toJson(){
		return RopUtils::json_encode($this);
	}
	
    /**
     * 处理无效params转数字null或0为'0'
     * @param $str
     * @return string
     */
	static function trim($str){
		if($str==null){
			if(is_numeric($str)){
				return '0';
			}
			return '';
		}else{
			if(is_numeric($str)){
				return $str;
			}else{
				return trim($str.'');
			}
		}
	}
}
?>