<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "jobs".
 *
 * @property integer $j_id
 * @property string $j_name
 * @property integer $j_department_id
 * @property integer $j_status
 */
class Jobs extends \app\core\base\BaseActiveRecord
{
    public $d_id;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'jobs';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['j_name', 'j_department_id'], 'required'],
            [['j_department_id', 'j_status'], 'integer'],
            [['j_name'], 'string', 'max' => 10],
            ['d_id', 'exist', 'targetClass' => 'app\models\Department', 'targetAttribute' => 'd_id']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'j_id' => 'J ID',
            'j_name' => '职位名',
            'j_department_id' => '所属部门id',
            'j_status' => '职位状态: 10正常 0禁用',
        ];
    }

    public function createJob($d_id, $param)
    {
        $this->load($param);
        $this->j_department_id = $d_id;
        if (!$this->validate()) {
            return false;
        }

        return $this->save(false) ? $this : null;
    }

    public function updateJob($param)
    {
        $this->load($param);
        if (!$this->validate()) {
            return false;
        }
        return $this->save(false) ? $this : null;
    }
}
