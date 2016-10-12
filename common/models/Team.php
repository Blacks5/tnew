<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "team".
 *
 * @property integer $t_id
 * @property string $t_name
 * @property integer $t_province
 * @property integer $t_city
 * @property integer $t_county
 */
class Team extends \app\core\base\BaseActiveRecord
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
            [['t_name', 't_province', 't_city', 't_county'], 'required'],
            [['t_province', 't_city', 't_county'], 'integer'],
            [['t_name'], 'string', 'max' => 10],
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
        if (!$this->validate()) {
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
        if (!$this->validate()) {
            return null;
        }
        return $this->save(false) ? $this : null;
    }
}
