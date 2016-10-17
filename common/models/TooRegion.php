<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%too_region}}".
 *
 * @property integer $field1
 * @property integer $field2
 * @property string $field3
 * @property integer $field4
 * @property integer $field5
 */
class TooRegion extends \common\core\CoreCommonActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%too_region}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['field1', 'field2', 'field4', 'field5'], 'integer'],
            [['field3'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'field1' => 'Field1',
            'field2' => 'Field2',
            'field3' => 'Field3',
            'field4' => 'Field4',
            'field5' => 'Field5',
        ];
    }
}
