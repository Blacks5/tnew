<?php
namespace com\jzq\api\model\sign;
use org\ebq\api\model\bean\UploadFile;
use RuntimeException;
/**
 * @author yfx 2016-07-02
 * 签约请求上传文件方式
 */
class ApplySignFileUrlRequest extends ApplySignAbstractRequest{
    static $v="1.0";
    static $method="sign.apply.file.url";

    /**文件地址url*/
	public $url;

    /**文件名称.pdf结尾*/
    public $fileName;
	
	function validate(){
		if($this->url==null||!is_string($this->url)){
			throw new RuntimeException("url is null or not a string value");
		}
        if($this->fileName==null||!is_string($this->fileName)){
            throw new RuntimeException("fileName is null or not a string value");
        }
		return parent::validate();
	}

}