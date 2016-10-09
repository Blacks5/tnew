<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%rbac_role}}".
 *
 * @property integer $role_id
 * @property string $role_name
 * @property integer $role_parent_id
 * @property integer $role_create_at
 */
class RbacRole extends \common\core\CoreActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%rbac_role}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['role_name', 'role_create_at'], 'required'],
            [['role_parent_id', 'role_create_at'], 'integer'],
            [['role_name'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'role_id' => 'Role ID',
            'role_name' => '角色名字',
            'role_parent_id' => '父权限id',
            'role_create_at' => '创建时间',
        ];
    }
}
