<?php
namespace common\tools\junziqian\model;
require_once dirname(__FILE__).'/../model/applySignAbstractRequest.php';
require_once dirname(__FILE__) . '/../model/UploadFile.php';
use common\tools\junziqian\model\ApplySignAbstractRequest as ApplySignAbstractRequest;
use \Exception;
/**
 * @author yfx 2016-07-02
 * 签约请求模版方式
 */
class ApplySignTmplRequest extends ApplySignAbstractRequest{
	
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
			throw new Exception("templateNo is null or size gt 100");return false;
		}
		if($this->contractParams!=null&&!is_array($this->contractParams)){
			throw new Exception("contractParams isn't a array");return false;
		}
		$this->contractParams=json_encode($this->contractParams,JSON_UNESCAPED_UNICODE);
		return parent::validate();
	}
	
	static $v="1.0";
	static $method="sign.apply.tmpl";

}
?>