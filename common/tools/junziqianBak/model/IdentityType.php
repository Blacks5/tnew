<?php
/**
 * Created by PhpStorm.
 * User: Brucelee
 * Date: 2017/7/26
 * Time: 20:25
 * Author: lilaotou <liwansen@foxmail.com>
 */

namespace common\tools\junziqian\model;


/**
 *用户身分证明类型枚举
 *@edit yfx 2016-07-02
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