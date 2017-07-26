<?php
/**
 * Created by PhpStorm.
 * User: Brucelee
 * Date: 2017/7/26
 * Time: 20:26
 * Author: lilaotou <liwansen@foxmail.com>
 */

namespace common\tools\junziqian\model;


/**
 * 验证等级
 * @deprecated 类已过时，请使用AuthLevel
 */
class AuthenticationLevel{
    /**无认证*/
    static $NONE="0";
    /**USBKey数字证书认证CA*/
    static $USEKEY="1";
    /**银行卡认证*/
    static $BANKCARD="2";
    /**支付宝认证*/
    static $ALIPAY="3";
    /**请选择任意一种验证方式*/
    static $ANYONE="4";
    /**请选择任意两种验证方式*/
    static $ANYTWO="5";
    /**请选择任意三种验证方式*/
    static $ANYTHREE="6";
    /**请使用CA及银行卡验证*/
    static $USEBANK="7";
    /**请使用银行卡及支付宝验证*/
    static $BANKALI="8";
    /**请使用CA及支付宝验证*/
    static $USEALI="9";
    /**三要素认证*/
    static $BANKTHREE="10";
    /**其他*/
    static $OTHER="99";
}