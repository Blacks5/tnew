<?php

namespace common\models;

use Yii;
use common\core\CoreCommonActiveRecord;
/**
 * This is the model class for table "team".
 *
 * @property integer $t_id
 * @property string $t_name
 * @property integer $t_province
 * @property integer $t_city
 * @property integer $t_county
 */
class Team extends CoreCommonActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'team';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['t_name', 't_province', 't_city', 't_county'], 'required', 'except' => 'search'],
            [['t_province', 't_city', 't_county'], 'integer'],
            [['t_name'], 'string', 'max' => 10]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            't_id' => 'T ID',
            't_name' => '团队名称',
            't_province' => '团队省',
            't_city' => '团队市',
            't_county' => '团队区/县',
        ];
    }

    /**
     * 创建团队
     * @param $param
     * @return Team|null
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function createTeam($param)
    {
        $this->load($param);
        if(!$this->validate()){
            return null;
        }
        return $this->save(false) ? $this : null;
    }

    /**
     * 编辑团队
     * @param $param
     * @return Team|null
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function updateTeam($param)
    {
        $this->load($param);
        if(!$this->validate()){
            return null;
        }
        return $this->save(false) ? $this : null;
    }

    public function scenarios()
    {
        $scen = parent::scenarios();
        $scen['search'] = ['t_name', 't_province', 't_city', 't_county'];
        return $scen;
    }

    public function search($param)
    {
        $this->setScenario('search');
        $this->load($param);
        $query = self::find()->where('t_id > 0');
        if (!$this->validate()) {
            return $query->where('1=2');
        }

        $query->andFilterWhere(['like', 't_name', $this->t_name]);
        $query->andFilterWhere(['t_province'=>$this->t_province])
            ->andFilterWhere(['t_city'=>$this->t_city])
            ->andFilterWhere(['t_county'=>$this->t_county]);
        return $query;
    }
}
