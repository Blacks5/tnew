<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "freezeuser_log".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $operator_id
 * @property string $freeze_remark
 * @property integer $created_at
 */
class FreezeuserLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'freezeuser_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'operator_id', 'created_at'], 'integer'],
            [['freeze_remark'], 'string', 'max' => 128],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => '用户id',
            'operator_id' => '操作者id',
            'freeze_remark' => '冻结原因',
            'created_at' => '创建时间',
        ];
    }
}
