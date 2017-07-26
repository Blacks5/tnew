<?php
/**
 * User: huhu
 * DateTime: 2017-06-02 0002 14:51
 */
namespace com\jzq\api\model\sign;
use org\ebq\api\model\RichServiceRequest;
use org\ebq\api\model\bean\UploadFile;
use RuntimeException;
/**
 *  上传签名图
 */
class AraphicSignRequset extends RichServiceRequest{

    static $v="1.0";
    static $method="graphic.sign";

    /**
     * 签约人证件号码
     */
    public $signatoryIdentityCard;

    /**手写签字图片*/
    /**
     * @var UploadFile
     */
    public $signImgFile;

    function validate(){
        if($this->signImgFile==null||!is_a($this->signImgFile, UploadFile::class)){
            throw new RuntimeException("file is null or not a UploadFile value");
        }
        return parent::validate();
    }

    /**
     * 不签名的filed
     */
    function getIgnoreSign(){
        $ignoreSign=array('signImgFile');
        return $ignoreSign;
    }
}