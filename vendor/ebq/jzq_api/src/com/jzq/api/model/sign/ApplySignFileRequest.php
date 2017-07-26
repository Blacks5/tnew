<?php
namespace com\jzq\api\model\sign;
use org\ebq\api\model\bean\UploadFile;
use RuntimeException;
/**
 * @author yfx 2016-07-02
 * 签约请求上传文件方式
 */
class ApplySignFileRequest extends ApplySignAbstractRequest{
    static $v="1.0";
    static $method="sign.apply.file";

    /**
     * 要上传的合同文件
     * @var UploadFile
     */
	public $file;
	
	function validate(){
		if($this->file==null||!is_a($this->file, UploadFile::class)){
			throw new RuntimeException("file is null or not a UploadFile value");
		}
		return parent::validate();
	}

    /**
     * 不签名的filed
     */
    function getIgnoreSign(){
        $ignoreSign=array('file');
        $parr=parent::getIgnoreSign();
        if(is_array($parr)){
            $ignoreSign=array_merge($ignoreSign,$parr);
        }
        return $ignoreSign;
    }
}