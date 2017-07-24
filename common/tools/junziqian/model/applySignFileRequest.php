<?php
namespace com_junziqian_api_model;
require_once dirname(__FILE__).'/../model/applySignAbstractRequest.php';
require_once dirname(__FILE__).'/../model/uploadFile.php';
use com_junziqian_api_model\ApplySignAbstractRequest as ApplySignAbstractRequest;
use com_junziqian_api_model\UploadFile as UploadFile;
use Exception as Exception;
/**
 * @author yfx 2016-07-02
 * 签约请求上传文件方式
 */
class ApplySignFileRequest extends ApplySignAbstractRequest{
    static $v="1.0";
    static $method="sign.apply.file";

	/**要上传的合同文件*/
	public $file;
	
	function validate(){
		if($this->file==null||!is_a($this->file, "com_junziqian_api_model\UploadFile")){
			throw new Exception("file is null or not a UploadFile value");return false;
		}
		return parent::validate();
	}
	
	/**
	 * 不签名的filed
	 */
	function getIgnoreSign(){
		$ignoreSign=array('file');
		return $ignoreSign;
	}
}
?>