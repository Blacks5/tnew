<?php
/**
 * User: huhu
 * DateTime: 2017-05-15 0015 11:24
 */
namespace com\jzq\api\model\menu;
/**
 *用户身分证明类型枚举
 */
class IdentityType{
    /**"身份证"*/
    static $IDCARD=array("code" => 1, "type" => 0);
    /**"护照"*/
    static $PASSPORT=array("code" => 2, "type" => 0);
    /**"台胞证"*/
    static $MTP=array("code" => 3, "type" => 0);
    /**"港澳居民来往内地通行证"*/
    static $RTMP=array("code" => 4, "type" => 0);
    /**"营业执照"*/
    static $BIZLIC=array("code" => 11, "type" => 1);
    /**"统一社会信用代码"*/
    static $USCC=array("code" => 12, "type" => 1);
    /**"其他"*/
    static $OTHER=array("code" => 99, "type" => 3);
}