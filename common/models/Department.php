<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "department".
 *
 * @property integer $d_id
 * @property string $d_name
 * @property integer $d_status
 */
class Department extends \common\core\CoreActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'department';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['d_status'], 'integer'],
            [['d_name'], 'string', 'max' => 15],
            [['d_name'], 'unique', 'message' => '已存在']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'd_id' => 'D ID',
            'd_name' => '部门名称',
            'd_status' => '部门状态: 10正常 0禁用',
        ];
    }

    public function createDepatment($param)
    {
        $this->load($param);
        if (!$this->validate()) {
            return null;
        }
        return $this->save(false) ? $this->toArray() : null;
    }

    public function updateDepartment($param)
    {
        $this->load($param);
        if (!$this->validate()) {
            return false;
        }
        return $this->save(false) ? $this : null;
    }

    public function scenarios()
    {
        $scen = parent::scenarios();
        $scen['search'] = ['d_name', 'd_status'];
        return $scen;
    }

    public function search($param)
    {
        $this->setScenario('search');
        $this->load($param);
        $query = self::find();
        if (!$this->validate()) {
            return $query->where('1=2');
        }

        $query->andFilterWhere(['like', 'd_name', $this->d_name])
            ->andFilterWhere(['=', 'd_status', $this->d_status]);
        return $query;
    }
}
