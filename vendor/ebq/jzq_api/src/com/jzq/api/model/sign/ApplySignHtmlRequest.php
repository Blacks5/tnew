<?php
namespace com\jzq\api\model\sign;
use org\ebq\api\model\bean\UploadFile;
use RuntimeException;
/**
 * @author yfx 2016-07-02
 * 签约请求上传文件方式
 */
class ApplySignHtmlRequest extends ApplySignAbstractRequest{
    static $v="1.0";
    static $method="sign.apply.html";

    /**
     * 要上传的合同html
     * html文件必须设置meta.charset为utf-8|否则会出现乱码。表单域请使用input type=text的，且注明name属性，宽高设置为0
     * @var string
     */
	public $htmlContent;

    /**
     * 合同文件名称，以.pdf结尾
     * @var string
     */
    public $fileName;
	
	function validate(){
        if($this->htmlContent==null||!is_string($this->htmlContent)){
            throw new RuntimeException("htmlContent is null or not a string value");
        }
        if($this->fileName==null||!is_string($this->fileName)){
            throw new RuntimeException("fileName is null or not a string value");
        }
        return parent::validate();
	}

    /**
     * 不签名的filed
     */
    function getIgnoreSign(){
        $ignoreSign=array('htmlContent');
        $parr=parent::getIgnoreSign();
        if(is_array($parr)){
            $ignoreSign=array_merge($ignoreSign,$parr);
        }
        return $ignoreSign;
    }
}