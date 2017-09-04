<?php

namespace common\models;

use common\models\User;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * UserSearch represents the model behind the search form about `common\models\User`.
 */
class UserSearch extends User
{
    public $username;
    public $realname;
    public $email;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status', 'created_at', 'updated_at', 'province', 'city', 'county'], 'integer'],
            [['username', 'realname', 'email'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return [
            'search' => ['username', 'realname', 'email', 'province', 'city', 'county']
        ];
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $user = new User();
        $area = $this->getUserArea();
        $query = User::find()->select('*')
            ->leftJoin(Department::tableName(), 'd_id=department_id')
            ->leftJoin(Jobs::tableName(), 'j_id=job_id')
            ->where(['!=', 'status', self::STATUS_DELETE])
            ->andWhere(['!=', 'username', 'admin']);
        if($area['level']>1){
            $query->andWhere(['>=', 'level', $area['level']])->andWhere([$area['area']=>$area['area_value'], 'department_id'=>26])->orWhere(['id'=>$area['id']]);
        }

        /*if($area['level']>1){
            $query->andWhere(['department_id'=>26, $area['area']=>$area['area_value']]);
        }*/
        $this->setScenario('search');
        $this->load($params);
        if (!$this->validate()) {
            return $query->andWhere('0=1');
        }

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'realname', $this->realname])
            ->andFilterWhere(['like', 'email', $this->email]);
        $query->andFilterWhere(['province'=>$this->province]);
        $query->andFilterWhere(['city'=>$this->city]);
        $query->andFilterWhere(['county'=>$this->county]);

        return $query;
    }
}
