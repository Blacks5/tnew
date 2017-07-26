<?php
/**
 * User: huhu
 * DateTime: 2017-06-06 0006 14:47
 */
namespace com\jzq\api\model\sign;
use org\ebq\api\model\RichServiceRequest;
use org\ebq\api\model\bean\UploadFile;
use RuntimeException;
class graphicSignRequest extends RichServiceRequest{
    static $v="1.0";
    static $method="graphic.sign";

    /**
     * 签约人证件号码|证件号
     */
    public $signatoryIdentityCard;

    /**
     * 手写签字图片
     * @var UploadFile
     */
    public $signImgFile;

    function validate(){
        $this->signatoryIdentityCard=self::trim($this->signatoryIdentityCard);
        if($this->signatoryIdentityCard==''){
            throw new RuntimeException("signatoryIdentityCard is null");
        }
        if($this->signImgFile==null||!is_a($this->signImgFile,UploadFile::class)){
            throw new RuntimeException("signImgFile is null or not a UploadFile value");
        }
        return parent::validate();
    }


    /**filter params*/
    function getIgnoreSign(){
        return ["signImgFile"];
    }
}