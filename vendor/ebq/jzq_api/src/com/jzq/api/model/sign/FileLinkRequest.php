<?php
namespace com\jzq\api\model\sign;
use org\ebq\api\model\RichServiceRequest;
use RuntimeException;
/**
 * 请求得到签约文件下载地址
 * @edit yfx 2016-07-26
 */
class FileLinkRequest extends RichServiceRequest{

	static $v="1.0";
	static $method="sign.link.file";

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