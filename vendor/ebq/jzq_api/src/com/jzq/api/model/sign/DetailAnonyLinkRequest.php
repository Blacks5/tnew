<?php
namespace com\jzq\api\model\sign;
use org\ebq\api\model\RichServiceRequest;
use RuntimeException;
/**
 * 请求得到签约文件查看地址（最新）
 * @edit yfx 2016-08-18
 */
class DetailAnonyLinkRequest extends RichServiceRequest{

	static $v="1.0";
	static $method="sign.link.anony.detail";

	/**
	 * 发起签约的申请编号,由发起签约接口返回
	 */
	public $applyNo;
	
	function validate(){
		$this->applyNo=self::trim($this->applyNo);
		if($this->applyNo==''){
			throw new RuntimeException("applyNo is null");
		}
		return parent::validate();
	}
}
?>