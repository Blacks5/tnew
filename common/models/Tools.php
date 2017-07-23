<?php
/**
 * Created by PhpStorm.
 * User: too
 * Date: 2016/9/27
 * Time: 13:11
 * @author 涂鸿 <hayto@foxmail.com>
 */

namespace common\models;


use common\components\CustomCommonException;

class Tools
{
    /**
     * 订单编号生成结构：年 月 日 +公司的订单数量8位 + 客户身份后四位后面+01例如：2017 06 12 000001 3707 01
    同一个身份证号每提交一次最后一位相加一次（包括撤销  取消 ）
     *
     * @param $idcard 客户身份证号码
     * @return string
     * @throws CustomCommonException
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public static function generateId($idcard = null)
    {
        if(empty($idcard)){
            throw new CustomCommonException('请传入身份证号码');
        }
        $lastFourStr = substr($idcard, -4);
        $c_total_borrow_times = Customer::find()->select(['c_total_borrow_times'])->where(['c_customer_id_card'=>$idcard])->scalar();

        if(is_numeric($c_total_borrow_times))
        {
            $c_total_borrow_times += 1;
        }else{
            $c_total_borrow_times = 1;
        }

        $c_total_borrow_times = str_pad($c_total_borrow_times, 2, 0, STR_PAD_LEFT);

        if ($count = self::generateOrderSerial()) {
            $count = str_pad($count, 8, 0, STR_PAD_LEFT);
            $id = date('ymd'. $count ) . $lastFourStr. $c_total_borrow_times;
            // 1707 0000004 13 68
            return $id;
        }
        return false;
    }

    /**
     * 20170718号废弃，用上面那个最新的
     * 生成订单号，长度最小15位，当日订单>=1000万则16位
     * w+年月+当天订单数+日+2位随机数
     * @return string
     * @author 涂鸿 <hayto@foxmail.com>
     */
    /*public static function generateId()
    {
        if ($count = self::generateOrderSerial()) {
            $count = str_pad($count, 7, 0, STR_PAD_LEFT);
            $id = date('ym'. $count .'d') . mt_rand(10, 99);
            // 1707 0000004 13 68
            return $id;
        }
        return false;
    }*/



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