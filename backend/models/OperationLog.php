<?php

namespace backend\models;

use Yii;

class OperationLog extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'operation_logs';
    }

    public function rules()
    {
        return [
            [['type_tag', 'ip', 'memo', 'data'], 'required'],
            [['operator_id'], 'required'],
            // [['order_id'], 'required'],
        ];
    }
}
