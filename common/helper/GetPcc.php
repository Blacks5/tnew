<?php
/**
 * Created by PhpStorm.
 * User: too
 * Date: 2016/8/17
 * Time: 16:23
 * @author 涂鸿 <hayto@foxmail.com>
 */

namespace common\helper;


use app\models\TooRegion;
use yii\db\Query;

class GetPcc
{
    /**
     * 通过id取下面的子地区
     * @param int $id
     * @return array
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public static function getPcc($id = 1)
    {
        $provinces = (new Query())->from(TooRegion::tableName())->where(['parent_id' => $id])->all();
        return array_column($provinces, 'region_name', 'region_id');
    }

    /**
     * 通过id取地名
     * @param $id
     * @return false|null|string
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public static function getAddName($id)
    {
        return (new Query())->select(['region_name'])->from(TooRegion::tableName())->where(['region_id' => $id])->scalar();
    }
}