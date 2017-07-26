<?php
namespace com\jzq\api\model\sign;
use com\jzq\api\model\bean\Signatory;
use com\jzq\api\model\menu\DealType;
use org\ebq\api\model\bean\UploadFile;
use org\ebq\api\model\RichServiceRequest;
use com\jzq\api\model\menu\AuthLevel;
use com\jzq\api\model\menu\SignLevel;
use com\jzq\api\model\menu\SequenceInfo;
use org\ebq\api\tool\RopUtils;
use RuntimeException;
/**
 * 签约请求abstract bean
 * @edit yfx 2017-06-02
 */
class ApplySignAbstractRequest extends RichServiceRequest{
	
	/**合同名称 非空，最长100个字符*/
	public $contractName;
	
	/**合同金额*/
	public $contractAmount;
	
    /**
     * @var array<Signatory>
     */
	public $signatories;
	
	/**备注*/
	public $remark;

	/**
	 * 前置记录，此记录会计录到签约日志中，并保全到日志保全和最终的证据保全中，最大字符不能超过2000字符串
	 */
	public $preRecored;

    /**
     * 签字人所需身份认证级别|覆盖ApplySignAbstractRequest中值
     * @var AuthLevel
     */
    public $authLevel;

    /**
     * 签字人所需身份认证数量，例要使用5种验证方式，选择其中的2种，则authLevelRange="2"
     * @var string 暂定为string，以便后面扩展，
     */
    public $authLevelRange;

    /**
     * @var SignLevel
     */
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
    /**
     * @var SequenceInfo
     */
	public $sequenceInfo;

    /**
     * 是否需要服务端证书，云证书：非1不需要，默认|1需要
     */
    public $serverCa;

    /**
     * 处理方式
     * @var DealType
     */
    public $dealType;

    /**
     * 是否显示二维码，0不显示，1显示
     */
    public $qrCode;

    /**
     * 附件信息1
     * @var UploadFile
     */
    public $attachFile1;
    /**
     * 附件信息2
     * @var UploadFile
     */
    public $attachFile2;
    /**
     * 附件信息3
     * @var UploadFile
     */
    public $attachFile3;

    /**
     * 是否使用表单域的方式来确认签字位置,1为是,其它为否。为1时，必须设置Signatory->chapteName
     * @var string
     */
    public $formChapteFlag;

	function validate(){
		$this->contractName=$this->trim($this->contractName);
        $this->formChapteFlag=self::trim($this->formChapteFlag);
        $this->signLevel=$this->trim($this->signLevel);
        $this->forceAuthentication=$this->trim($this->forceAuthentication);
        $this->orderFlag=$this->trim($this->orderFlag);
		if($this->contractName==''){
			throw new RuntimeException("contractName is null");
		}
		if($this->signatories==null||!is_array($this->signatories)||count($this->signatories)==0){
			throw new RuntimeException('signatory is null or not a array<com\jzq\api\model\bean\Signatory> value');
		}
		if(count($this->signatories,0)>30){
			throw new RuntimeException("signatories.size is gt 30");
		}
		$orderNumArray=array();
		foreach ($this->signatories as $signatory) {
			if($signatory==null||!is_a($signatory,Signatory::class)){
				throw new RuntimeException("signatories.value isn't a Signatory value");
			}
			if(!$signatory->validate()){
				return false;
			}
			if($this->formChapteFlag=="1"&&!is_string($signatory->chapteName)){
                throw new RuntimeException("signatory.chapteName isn't a string value");
            }
			if($this->orderFlag=="1"){
				if(!(is_int($signatory->orderNum)&&$signatory->orderNum>0&&$signatory->orderNum<100)){
					throw new RuntimeException($signatory->identityCard." orderNum isn't a int in (1,99)");
				}else{
					array_push($orderNumArray, $signatory->orderNum);
				}
			}
		}
        if($this->orderFlag=="1"&&sizeof(array_unique($orderNumArray))!=sizeof($orderNumArray)){
			throw new RuntimeException("had equal orderNum in signatories");
		}
		//php5.4.0-的参考SignLinkRequest方法
		$this->signatories=RopUtils::json_encode($this->signatories);
		//验证级别赋默认值
		if(isset($this->authLevel)&&!is_null($this->authLevel)){
		    if(!is_array($this->authLevel)){
		        throw new RuntimeException("authLevel is not a array");
            }
            foreach ($this->authLevel as $val){
                if(!is_int($val)){
                    throw new RuntimeException("authLevel.$val is not a int value");
                }
                if($val==AuthLevel::$FACE&&((!isset($this->faceThreshold))||is_null($this->faceThreshold)||(!is_int($this->faceThreshold))||($this->faceThreshold<1&&$this->faceThreshold>100))){
                    throw new RuntimeException("faceThreshold isn't a int in [1,100]");
                }
            }
            $this->authLevel=RopUtils::json_encode($this->authLevel);
        }
		$this->contractAmount=$this->trim($this->contractAmount);
		if($this->contractAmount==null){
			$this->contractAmount="";
		}
		if($this->signLevel==''){
			$this->signLevel=SignLevel::$GENERAL;
		}else if($this->signLevel!=SignLevel::$GENERAL&&$this->signLevel!=SignLevel::$SEAL&&$this->signLevel!=SignLevel::$ESIGNSEAL){
			throw new RuntimeException("signLevel.value isn't a SignLevel value");
		}
		if($this->forceAuthentication==''){
			$this->forceAuthentication="0";
		}
		if((!is_null($this->sequenceInfo))&&(!$this->sequenceInfo->validate())){
            return false;
        }
        $this->sequenceInfo=RopUtils::json_encode($this->sequenceInfo);

        if($this->attachFile1!=null&&!is_a($this->attachFile1, UploadFile::class)){
            throw new RuntimeException("attachFile1 is not a UploadFile value");
        }
        if($this->attachFile2!=null&&!is_a($this->attachFile2, UploadFile::class)){
            throw new RuntimeException("attachFile2 is not a UploadFile value");
        }
        if($this->attachFile3!=null&&!is_a($this->attachFile3, UploadFile::class)){
            throw new RuntimeException("attachFile3 is not a UploadFile value");
        }
		return parent::validate();
	}

    /**
     * 不签名的filed
     */
    function getIgnoreSign(){
        $ignoreSign=array('attachFile1','attachFile2','attachFile3');
        $parr=parent::getIgnoreSign();
        if(is_array($parr)){
            $ignoreSign=array_merge($ignoreSign,$parr);
        }
        return $ignoreSign;
    }
}