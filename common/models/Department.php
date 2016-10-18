<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "department".
 *
 * @property integer $d_id
 * @property string $d_name
 * @property integer $d_status
 */
class Department extends \common\core\CoreCommonActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'department';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['d_status'], 'integer'],
            [['d_name'], 'string', 'max' => 15],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'd_id' => 'D ID',
            'd_name' => 'D Name',
            'd_status' => 'D Status',
        ];
    }

    /**
     * 获取所有部门
     * @return array [1=>'财务部', 2=>'运维部']
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public static function getAllDepartments()
    {
        $data = static::find()->select(['d_name'])->indexBy('d_id')->where(['d_status'=>10])->column();
        return $data;
    }
}
