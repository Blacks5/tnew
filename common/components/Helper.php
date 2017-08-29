<?php
/**
 * Created by PhpStorm.
 * Date: 16/10/17
 * Time: 22:16
 * @author 涂鸿 <hayto@foxmail.com>
 */
namespace common\components;

use common\models\Department;
use common\models\Jobs;
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
            ->from(TooRegion::tableName())->where(['parent_id'=>1])->orWhere(['parent_id'=>0])->indexBy('region_id')->column();
        return $data;
    }

    /**
     * 根据省份id 获取省份
     * @param $province
     * @return array
     * @author OneStep
     */
    public static function getProvinceByProvinceId($province)
    {
        $data = (new Query())
            ->select(['region_name'])
            ->from(TooRegion::tableName())->where(['region_id'=>$province])->indexBy('region_id')->column();

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

    /**
     * 通过id获取银行名
     * @param $id
     * @return mixed
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public static function getBankNameById($id)
    {
        $bl = array_column(\Yii::$app->params['bank_list'], 'bank_name', 'bank_id');
        if(isset($bl[$id])){
            return $bl[$id];
        }
        return false;
    }

    /**
     * 获取婚姻状态字符串
     * @param $id
     * @return mixed
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public static function getMaritalStatusString($id)
    {
        return array_column(\Yii::$app->params['marital_status'], 'marital_str', 'marital_id')[$id];
    }


    /**
     * 获取住房情况
     * @param $id
     * @return mixed
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public static function getHouseInfoString($id)
    {
        return array_column(\Yii::$app->params['house_info'], 'house_info_str', 'house_info_id')[$id];
    }

    /**
     * 亲人关系
     * @param $id
     * @return mixed
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public static function getKindShipString($id)
    {
        return array_column(\Yii::$app->params['kinship'], 'kinship_str', 'kinship_id')[$id];
    }


    /**
     * 公司所在行业
     * @param $id
     * @return mixed
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public static function getCompanyIndustryString($id)
    {
        return array_column(\Yii::$app->params['company_kind'], 'company_kind_name', 'company_kind_id')[$id];
    }
    public static function getCompanyTypeString($id)
    {
        return array_column(\Yii::$app->params['company_type'], 'company_type_name', 'company_type_id')[$id];
    }

    /**
     * 根据job id获取职位名
     * @param $j_id
     * @return false|null|string
     * @author too <hayto@foxmail.com>
     */
    public static function getJobNameByjobid($j_id)
    {
        return (new Query())->select(['j_name'])->from(Jobs::tableName())->where(['j_id'=>$j_id])->scalar();
    }

    public static function getProdNameByProdid($prod_id)
    {
        $goods_info = array_column(\Yii::$app->params['goods_type'], null,'t_id');
        $prod_name = '无此类型产品';
        if(isset($goods_info[$prod_id])){
            $prod_name = $goods_info[$prod_id]['t_name'];
        }
        return $prod_name;
    }

}