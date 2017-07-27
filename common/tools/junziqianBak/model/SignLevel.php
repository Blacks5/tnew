<?php
/**
 * Created by PhpStorm.
 * User: Brucelee
 * Date: 2017/7/26
 * Time: 20:27
 * Author: lilaotou <liwansen@foxmail.com>
 */

namespace common\tools\junziqian\model;


/**签章等级*/
class SignLevel {
    /**"标准图形章"*/
    static $GENERAL="0";
    /**"公章或手写"*/
    static $SEAL="1";
    /**"公章手写或手写"*/
    static $ESIGNSEAL="2";
}