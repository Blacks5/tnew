<?php
/**
 * Created by PhpStorm.
 * User: too
 * Date: 2016/9/27
 * Time: 13:11
 * @author 涂鸿 <hayto@foxmail.com>
 */

namespace common\models;


class Tools
{
    /**
     * 生成订单号，长度最小15位，当日订单>=1000万则16位
     * w+年月+当天订单数+日+2位随机数
     * @return string
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public static function generateId()
    {
        if ($count = self::generateOrderSerial()) {
            $count = str_pad($count, 7, 0, STR_PAD_LEFT);
            $id = date('ym'. $count .'d') . mt_rand(10, 99);
            return $id;
        }
        return false;
    }

    /**
     * 数据库生成 当天截止此时的总订单数
     * @return bool|int
     * @author 涂鸿 <hayto@foxmail.com>
     */
    private static function generateOrderSerial()
    {
        $date = date('Ymd');
        if ($model = OrderSerial::findOne(['serial_time' => $date])) {
            $model->serial_count += 1;
        } else {
            $model = new OrderSerial();
            $model->serial_time = $date;
            $model->serial_count = 1;
        }
        return $model->save(false) ? $model->serial_count : false;
    }
}