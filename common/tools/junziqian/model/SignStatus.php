<?php
/**
 * Created by PhpStorm.
 * User: Brucelee
 * Date: 2017/7/26
 * Time: 20:28
 * Author: lilaotou <liwansen@foxmail.com>
 */

namespace common\tools\junziqian\model;


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