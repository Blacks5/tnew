<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "yijifu_loan".
 *
 * @property integer $id
 * @property integer $order_id
 * @property double $amount
 * @property double $realRemittanceAmount
 * @property string $contractNo
 * @property double $chargeAmount
 * @property integer $type
 * @property integer $created_at
 * @property integer $operator_id
 */
class YijifuLoan extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'yijifu_loan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'contractNo', 'created_at'], 'required'],
            [['order_id', 'type', 'created_at', 'operator_id'], 'integer'],
            [['amount', 'realRemittanceAmount', 'chargeAmount'], 'number'],
            [['contractNo'], 'string', 'max' => 40],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => '核心系统客户订单号',
            'amount' => '代发金额',
            'realRemittanceAmount' => '实际代发金额',
            'contractNo' => '代发流水号',
            'chargeAmount' => '代发手续费',
            'type' => '放款类型：1对私，2对公',
            'created_at' => '记录创建时间',
            'operator_id' => '操作人id',
        ];
    }
}
