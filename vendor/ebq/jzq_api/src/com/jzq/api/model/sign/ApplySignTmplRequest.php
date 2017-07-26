<?php
namespace com\jzq\api\model\sign;
use org\ebq\api\tool\RopUtils;
use RuntimeException;
/**
 * @author yfx 2016-07-02
 * 签约请求模版方式
 */
class ApplySignTmplRequest extends ApplySignAbstractRequest{

    static $v="1.0";
    static $method="sign.apply.tmpl";
	
	/**
	 * 合同模板编号
	 * 非空，最长100个字符
	 */
	public $templateNo;
	
	/**
	 * 合同内容填充参数
	 */
	public $contractParams;
	
	function validate(){
		if(!is_string($this->templateNo)||strlen($this->templateNo)==0||strlen($this->templateNo)>100){
			throw new RuntimeException("templateNo is null or size gt 100");
		}
		if($this->contractParams!=null&&!is_array($this->contractParams)){
			throw new RuntimeException("contractParams isn't a array");
		}
		$this->contractParams=RopUtils::json_encode($this->contractParams);
		return parent::validate();
	}

}