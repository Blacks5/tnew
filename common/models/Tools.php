<?php
/**
 * Created by PhpStorm.
 * User: too
 * Date: 2016/9/27
 * Time: 13:11
 * @author 涂鸿 <hayto@foxmail.com>
 */

namespace common\models;


use app\models\OrderSerial;

class Tools
{
    /**
     * 生成订单号
     * @return string
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public static function generateId()
    {
        if($count = self::generateOrderSerial()) {
            $id = 'W' . date('ymd') . $count . mt_rand(100, 999);
            return $id;
        }
        return false;
    }
    /**
     * 数据库
     * @return bool|int
     * @author 涂鸿 <hayto@foxmail.com>
     */
    private static function generateOrderSerial()
    {
        $date = date('Ymd');
        if($model = OrderSerial::findOne(['serial_time'=>$date])) {
            $model->serial_count += 1;
        }else {
            $model = new OrderSerial();
            $model->serial_time = $date;
            $model->serial_count = 1;
        }
        return $model->save(false) ? $model->serial_count : false;
    }
}