<?php
/**
 * User: huhu
 * DateTime: 2017-05-15 0015 11:36
 */
namespace com\jzq\api\model\menu;
/**
 * 验证等级
 */
class AuthLevel{
    /**USBKey数字证书认证CA*/
    static $USEKEY=1;
    /**银行卡认证*/
    static $BANKCARD=2;
    /**支付宝认证*/
    static $ALIPAY=3;
    /**三要素认证*/
    static $BANKTHREE=10;
    /**人脸识别*/
    static $FACE=11;
}