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
class Department extends \common\core\CoreCommonActiveRecord
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

    /**
     * @param $param
     * @return array|null
     */
    public function createDepatment($param)
    {
        $this->load($param);
        if(!$this->validate()){
            return null;
        }
        return $this->save(false) ? $this->toArray() : null;
    }

    /**
     * @param $param
     * @return bool|Department|null
     */
    public function updateDepartment($param)
    {
        $this->load($param);
        if(!$this->validate()){
            return false;
        }
        return $this->save(false) ? $this : null;
    }

    /**
     * 获取所有部门
     * @return array [1=>'财务部', 2=>'运维部']
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public static function getAllDepartments()
    {
        $data = static::find()->select(['d_name'])->indexBy('d_id')->where(['d_status'=>10])->column();
        return $data;
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
