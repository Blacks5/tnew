<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "order_serial".
 *
 * @property integer $serial_id
 * @property integer $serial_time
 * @property integer $serial_count
 */
class OrderSerial extends \app\core\base\BaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order_serial';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['serial_time', 'serial_count'], 'required'],
            [['serial_time', 'serial_count'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'serial_id' => 'Serial ID',
            'serial_time' => 'Serial Time',
            'serial_count' => 'Serial Count',
        ];
    }
}
