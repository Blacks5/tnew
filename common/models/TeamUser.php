<?php

namespace common\models;

use Yii;
use common\core\CoreCommonActiveRecord;
/**
 * This is the model class for table "team_user".
 *
 * @property integer $tu_id
 * @property integer $tu_tid
 * @property integer $tu_sale_id
 */
class TeamUser extends CoreCommonActiveRecord
{
    public $realname;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'team_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tu_tid', 'tu_sale_id'], 'required'],
            [['tu_tid', 'tu_sale_id'], 'integer'],
            [['realname'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tu_id' => 'Tu ID',
            'tu_tid' => '团队id',
            'tu_sale_id' => '销售人员id',
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent classDDD
        $scan = parent::scenarios();
        $scan['search'] = ['realname', 'tu_tid'];
        return $scan;
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
        $data['data'] = $params;
        $select = ['team_user.*', 'user.*'];
        $this->scenario = 'search';
        $this->load($data, 'data');
        //p($params,$this->attributes(),$this->realname);
        $query = TeamUser::find()
            ->select($select)
            ->leftJoin(User::tableName(), 'tu_sale_id=id')
            ->where(['!=', 'status', User::STATUS_DELETE])
            ->andWhere(['!=', 'user.username', 'admin'])
            ->andWhere(['tu_tid' => $this->tu_tid]);
        if (!$this->validate()) {
            //p($this->errors);
            return $query->andWhere('0=1');
        }
        if (Yii::$app->getRequest()->get('realname')) {
            $this->realname = Yii::$app->getRequest()->get('realname');
        }
        $query->andFilterWhere(['like', 'user.realname', $this->realname]);
        /*            ->andFilterWhere(['like', 'user.username', $this->username])
                    ->andFilterWhere(['like', 'user.email', $this->email]);*/

        //echo  $query->createCommand()->getRawSql();die;
        return $query;
    }
}
