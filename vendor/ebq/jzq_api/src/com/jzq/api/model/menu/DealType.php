<?php
/**
 * User: huhu
 * DateTime: 2017-05-15 0015 11:38
 */
namespace com\jzq\api\model\menu;

/**合同的处理类型，可以人签，也可以自动签*/
class DealType{
    /**尚未生成初始化合同*/
    static $DEFAULT="0";
    /**自动签字并保全*/
    static $AUTH_SIGN="1";
    /**只做保全，用户不做签字*/
    static $ONLY_PRES="2";
    /**部份云证书自动签字*/
    static $AUTH_SIGN_PART='5';
}