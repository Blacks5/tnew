<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "jobs".
 *
 * @property integer $j_id
 * @property string $j_name
 * @property integer $j_department_id
 * @property integer $j_status
 */
class Jobs extends \common\core\CoreCommonActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'jobs';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['j_name', 'j_department_id'], 'required'],
            [['j_department_id', 'j_status'], 'integer'],
            [['j_name'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'j_id' => 'J ID',
            'j_name' => 'J Name',
            'j_department_id' => 'J Department ID',
            'j_status' => 'J Status',
        ];
    }

    /**
     * 获取某个部门下所有职位
     * @param $d_id
     * @return array
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public static function getJobs($d_id)
    {
        $data = static::find()->select(['j_name'])->indexBy('j_id')->where(['j_department_id'=>$d_id])->column();
        return $data;
    }
}
