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
            [['id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['username', 'realname', 'email'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent classDDD
        return [
            'search' => ['username', 'realname', 'email']
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
        $query = User::find()->select('*')
            ->leftJoin(Department::tableName(), 'd_id=department_id')
            ->where(['!=', 'status', self::STATUS_DELETE])
            ->andWhere(['!=', 'username', 'admin']);
        $this->setScenario('search');
        $this->load($params);
        if (!$this->validate()) {
            return $query->andWhere('0=1');
        }

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'realname', $this->realname])
            ->andFilterWhere(['like', 'email', $this->email]);

//        echo  $query->createCommand()->getRawSql();die;
        return $query;
    }
}
