<?php

namespace common\models;

use Yii;
use common\core\CoreCommonActiveRecord;

/**
 * This is the model class for table "stores_saleman".
 *
 * @property integer $ss_id
 * @property integer $ss_store_id
 * @property integer $ss_saleman_id
 */
class StoresSaleman extends CoreCommonActiveRecord
{
    public $realname;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'stores_saleman';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ss_store_id', 'ss_saleman_id'], 'required'],
            [['ss_store_id', 'ss_saleman_id'], 'integer'],
            [['realname'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ss_id' => 'Ss ID',
            'ss_store_id' => '商户id',
            'ss_saleman_id' => '销售人员id',
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent classDDD
        $scan = parent::scenarios();
        $scan['search'] = ['realname', 'ss_store_id'];
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
        $select = ['stores_saleman.*', 'user.*'];
        $this->scenario = 'search';
        $this->load($data, 'data');
        //p($params,$this->attributes(),$this->realname);
        $query = StoresSaleman::find()
            ->select($select)
            ->leftJoin(User::tableName(), 'ss_saleman_id=id')
            ->where(['status'=>User::STATUS_ACTIVE])
            ->andWhere(['!=', 'user.username', 'admin'])
            ->andWhere(['ss_store_id' => $this->ss_store_id]);
        if (!$this->validate()) {
            //p($this->errors);
            return $query->andWhere('0=1');
        }
        if (Yii::$app->getRequest()->get('realname')) {
            $this->realname = Yii::$app->getRequest()->get('realname');
        }
        $query->andFilterWhere(['like', 'user.realname', $this->realname]);
        return $query;
    }
}
