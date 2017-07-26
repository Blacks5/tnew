<?php
/**
 * User: yfx
 * Date: 2017-06-01 0019
 * Time: 15:18
 */
namespace com\jzq\api\model\sign;
use org\ebq\api\model\RichServiceRequest;
use org\ebq\api\model\bean\UploadFile;
use RuntimeException;
class ApplySignAttachFileRequest extends RichServiceRequest{
    static $v="1.0";
    static $method="sign.apply.attach.file";

    public $applyNo;
    /**要上传的合同文件*/
    public $file;

    function validate(){
        if(!is_string($this->applyNo)){
            throw new RuntimeException("file applyNo or not a string value");
        }
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
        return $ignoreSign;
    }

}