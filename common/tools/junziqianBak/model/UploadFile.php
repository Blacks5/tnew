<?php
//namespace com_junziqian_api_model;
namespace common\tools\junziqian\model;
require_once dirname(__FILE__) . '/../tool/ShaUtils.php';
//use com_junziqian_api_tool\RopUtils as RopUtils;
use common\tools\junziqian\tool\RopUtils as RopUtils;
/**
*上传的文件体
*@edit yfx 2016-07-02
*/
class UploadFile{
	
	/**html上传时的参数分隔符*/
	static $boundary="---------------------------7df27332f1714";
	
	/**文件类型*/
	public $fileType;
	/**文件名称*/
	public $fileName;
	/**文件内容*/
	public $content;
	/**编译后的待上传文本*/
	public $uploadStr;
	
	function say(){ 
		echo "fileType：".$this->fileType." fileName：".$this->fileName." content：".$this->content."<br>"; 
	} 
	
	function __construct($filePath=null){
		//组建请求参数
		if($filePath!=null){
			$fileName=RopUtils::getFileName($filePath);
			if(preg_match ("/win/i", PHP_OS)){
				$filePath =iconv("utf-8","gb2312//IGNORE", $filePath);
			}
			//封装上传文件内容
			$this->content=file_get_contents($filePath);
			$this->fileName=$fileName;
		}
	}
}
?>
