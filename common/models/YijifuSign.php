<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "yijifu_sign".
 *
 * @property integer $id
 * @property string $o_serial_id
 * @property string $merchOrderNo
 * @property string $merchContractNo
 * @property double $deductAmount
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $operator_id
 * @property integer $status
 * @property string $orderNo
 * @property string $sign
 * @property string $bankName
 * @property string $bankCardType
 * @property string $bankCode
 */
class YijifuSign extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'yijifu_sign';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['o_serial_id', 'merchOrderNo', 'merchContractNo', 'created_at'], 'required'],
            [['deductAmount'], 'number'],
            [['created_at', 'updated_at', 'operator_id', 'status'], 'integer'],
            [['o_serial_id'], 'string', 'max' => 30],
            [['merchOrderNo', 'orderNo', 'bankCode'], 'string', 'max' => 40],
            [['merchContractNo', 'sign', 'bankName'], 'string', 'max' => 64],
            [['bankCardType'], 'string', 'max' => 50],
            [['logs'], 'text'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'o_serial_id' => 'O Serial ID',
            'merchOrderNo' => 'Merch Order No',
            'merchContractNo' => 'Merch Contract No',
            'deductAmount' => 'Deduct Amount',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'operator_id' => 'Operator ID',
            'status' => 'Status',
            'orderNo' => 'Order No',
            'sign' => 'Sign',
            'bankName' => 'Bank Name',
            'bankCardType' => 'Bank Card Type',
            'bankCode' => 'Bank Code',
            'logs' => '日志文件',
        ];
    }
}