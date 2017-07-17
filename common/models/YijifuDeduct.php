<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "yijifu_deduct".
 *
 * @property integer $id
 * @property string $o_serial_id
 * @property string $merchOrderNo
 * @property string $merchSignOrderNo
 * @property string $deductAmount
 * @property string $realName
 * @property string $bankCardNo
 * @property string $bankCode
 * @property integer $realRepayTime
 * @property integer $status
 * @property string $errorCode
 * @property string $description
 * @property integer $operator_id
 * @property integer $created_at
 */
class YijifuDeduct extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'yijifu_deduct';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['deductAmount'], 'number'],
            [['realRepayTime', 'status', 'operator_id', 'created_at'], 'integer'],
            [['status'], 'required'],
            [['o_serial_id'], 'string', 'max' => 20],
            [['merchOrderNo', 'merchSignOrderNo', 'bankCardNo'], 'string', 'max' => 40],
            [['realName', 'bankCode'], 'string', 'max' => 16],
            [['errorCode'], 'string', 'max' => 10],
            [['description'], 'string', 'max' => 128],
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
            'merchSignOrderNo' => 'Merch Sign Order No',
            'deductAmount' => 'Deduct Amount',
            'realName' => 'Real Name',
            'bankCardNo' => 'Bank Card No',
            'bankCode' => 'Bank Code',
            'realRepayTime' => 'Real Repay Time',
            'status' => 'Status',
            'errorCode' => 'Error Code',
            'description' => 'Description',
            'operator_id' => 'Operator ID',
            'created_at' => 'Created At',
        ];
    }
}
