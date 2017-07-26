<?php
namespace com\jzq\api\model\sign;
use org\ebq\api\model\RichServiceRequest;
use com\jzq\api\model\bean\Signatory;
use RuntimeException;
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
     * @var Signatory
	 */
    /**
     * 签约人，必须是发起签约接口传入签约人中，姓名、身份证件一致的签约人
     * 手机号码和地址可以不一样
     * 此字段填写：查询当前签约人的签字状态
     * 未填:查询整个合同的签字状态 签字状态结果值为signStatus(@link enum.php/SignStatus)
     * @var Signatory
     */
	public $signatory;
	
	/**
	 * 验证方法，也有一些转换关系在里面
	 */
	function validate(){
		$this->applyNo=self::trim($this->applyNo);
		if($this->applyNo==''){
			throw new RuntimeException("applyNo is null");
		}
		if(!is_null($this->signatory)){
			if(!is_a($this->signatory,Signatory::class)){
				throw new RuntimeException("signatory is null or not a Signatory value");
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