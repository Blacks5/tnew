<?php
/**
 * Created by PhpStorm.
 * Date: 16/10/17
 * Time: 22:16
 * @author 涂鸿 <hayto@foxmail.com>
 */
namespace common\components;

use common\models\TooRegion;
use yii\db\Query;

class Helper
{
    /**
     * 获取全部省
     * @return array [1=>'chengdu', 2=>'chongqing']
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public static function getAllProvince()
    {
        $data = (new Query())
            ->select(['region_name'])
            ->from(TooRegion::tableName())->where(['parent_id'=>1])->indexBy('region_id')->column();
        return $data;
    }

    /**
     * 获取全部子地区
     * @param $p_id
     * @return array [1=>'chengdu', 2=>'chongqing']
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public static function getSubAddr($p_id)
    {
        $data = (new Query())
            ->select(['region_name'])
            ->from(TooRegion::tableName())->where(['parent_id'=>$p_id])->indexBy('region_id')->column();
        return $data;
    }

    /**
     * 根据id获取地名
     * @param $id
     * @return false|null|string
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public static function getAddrName($id)
    {
        return (new Query())->select(['region_name'])->from(TooRegion::tableName())->where(['region_id' => $id])->scalar();
    }
}