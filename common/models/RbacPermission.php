<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%rbac_permission}}".
 *
 * @property integer $permission_id
 * @property string $permission_name
 * @property string $permission_route
 * @property integer $permission_parent_id
 * @property integer $permission_create_at
 */
class RbacPermission extends \common\core\CoreActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%rbac_permission}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['permission_name', 'permission_route', 'permission_parent_id', 'permission_create_at'], 'required'],
            [['permission_parent_id', 'permission_create_at'], 'integer'],
            [['permission_name'], 'string', 'max' => 10],
            [['permission_route'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'permission_id' => 'Permission ID',
            'permission_name' => '权限名称（菜单名称）',
            'permission_route' => '权限对应的操作',
            'permission_parent_id' => '父id',
            'permission_create_at' => '创建时间',
        ];
    }
}
