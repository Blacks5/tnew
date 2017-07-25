<?php
/**
 * User: yfx
 * Date: 2017-01-19 0019
 * Time: 15:18
 */

namespace com_junziqian_api_model;
require_once dirname(__FILE__).'/../model/richServiceRequest.php';
use com_junziqian_api_model\RichServiceRequest as RichServiceRequest;

class ApplySignAttachFileRequest extends RichServiceRequest{
    static $v="1.0";
    static $method="sign.apply.attach.file";

    public $applyNo;
    /**要上传的合同文件*/
    public $file;

    function validate(){
        if(!is_string($this->applyNo)){
            throw new Exception("file applyNo or not a string value");return false;
        }
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