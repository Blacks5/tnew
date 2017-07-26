<?php
/**
 * User: huhu
 * DateTime: 2017-05-15 0015 11:38
 */
namespace com\jzq\api\model\menu;

/**签字状态*/
class SignStatus{
    /**尚未生成初始化合同*/
    static $NOTINIT=-1;
    /**待签*/
    static $INPROGRESS=0;
    /**完成*/
    static $COMPLETED=1;
    /**拒签*/
    static $REFUSE=2;
    /**已保全*/
    static $PRES=3;
}