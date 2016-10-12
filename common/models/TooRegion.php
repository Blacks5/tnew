<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "too_region".
 *
 * @property integer $region_id
 * @property integer $parent_id
 * @property string $region_name
 * @property integer $region_type
 * @property integer $agency_id
 */
class TooRegion extends \app\core\base\BaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'too_region';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'region_type', 'agency_id'], 'integer'],
            [['region_name'], 'string', 'max' => 120],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'region_id' => 'Region ID',
            'parent_id' => 'Parent ID',
            'region_name' => 'Region Name',
            'region_type' => 'Region Type',
            'agency_id' => 'Agency ID',
        ];
    }

    public static function getAllProvinces()
    {
        self::find()->where()->asArray()->all();
    }
}
