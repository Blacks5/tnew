<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "yijifu_sign_returnmoney".
 *
 * @property integer $id
 * @property integer $order_id
 * @property string $merchOrderNo
 * @property string $merchContractNo
 * @property double $deductAmount
 * @property integer $operateType
 * @property integer $created_at
 * @property integer $operator_id
 */
class YijifuSignReturnmoney extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'yijifu_sign_returnmoney';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'merchOrderNo', 'merchContractNo', 'created_at'], 'required'],
            [['order_id', 'operateType', 'created_at', 'operator_id'], 'integer'],
            [['deductAmount'], 'number'],
            [['merchOrderNo'], 'string', 'max' => 40],
            [['merchContractNo'], 'string', 'max' => 64],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'Order ID',
            'merchOrderNo' => 'Merch Order No',
            'merchContractNo' => 'Merch Contract No',
            'deductAmount' => 'Deduct Amount',
            'operateType' => 'Operate Type',
            'created_at' => 'Created At',
            'operator_id' => 'Operator ID',
        ];
    }
}
