<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "jzq_sign".
 *
 * @property integer $id
 * @property string $o_serial_id
 * @property string $applyNo
 * @property string $IdentityType
 * @property string $fullName
 * @property string $optTime
 * @property integer $signStatus
 * @property string $Timestamp
 * @property integer $operator_id
 * @property string $operator_realname
 * @property integer $created_at
 * @property integer $updated_at
 */
class JzqSign extends \yii\db\ActiveRecord
{

    const STATUS_WAIT_SIGN = 0; //待签约
    const STATUS_ALREADY_SIGNED = 1; // 已签约
    const STATUS_REFUSE_SIGN = 2; // 拒签
    const STATUS_SIGN_AND_BAOQUAN = 3;// 签约加保全
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'jzq_sign';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['signStatus', 'operator_id', 'created_at', 'updated_at'], 'integer'],
            [['o_serial_id', 'applyNo'], 'string', 'max' => 30],
            [['identityType', 'fullName'], 'string', 'max' => 100],
            [['optTime', 'timestamp', 'operator_realname', 'identityCard'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'o_serial_id' => '核心系统客户订单号',
            'applyNo' => '签约编号',
            'identityType' => '证件类型',
            'fullName' => '企业名称/个人姓名',
            'optTime' => '操作时间',
            'signStatus' => '0 未签、 1 已签、 2 拒签',
            'timestamp' => '回传信息发送时间',
            'operator_id' => '操作人ID',
            'operator_realname' => '操作人姓名',
            'created_at' => '记录创建时间',
            'updated_at' => '记录更新时间',
            'identityCard' => '身份证号码',
        ];
    }
}
