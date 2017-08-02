<?php

namespace common\models;

use Yii;
use common\core\CoreCommonActiveRecord;
/**
 * This is the model class for table "stores".
 *
 * @property integer $s_id
 * @property integer $s_add_user_id
 * @property string $s_add_user_name
 * @property integer $s_auditor_id
 * @property string $s_name
 * @property string $s_owner_email
 * @property string $s_owner_phone
 * @property string $s_owner_name
 * @property string $s_remark
 * @property string $s_refuse_reason
 * @property integer $s_status
 * @property string $s_county
 * @property string $s_bank_people_name
 * @property string $s_bank_num
 * @property string $s_bank_sub
 * @property string $s_bank_addr
 * @property string $s_bank_name
 * @property integer $s_bank_is_private
 * @property string $s_city
 * @property string $s_province
 * @property string $s_gov_name
 * @property string $s_addr
 * @property double $s_service_charge
 * @property string $s_photo_one
 * @property string $s_photo_two
 * @property string $s_photo_three
 * @property string $s_photo_four
 * @property string $s_photo_five
 * @property string $s_photo_six
 * @property integer $s_created_at
 * @property integer $s_updated_at
 */


class Stores extends CoreCommonActiveRecord
{
    // 10正常  1审核拒绝 2关闭
    const STATUS_ACTIVE = 10; // 激活
    const STATUS_REFUSE = 1; //审核拒绝
    const STATUS_STOP = 2; // 关闭
    const STATUS_DELETE = 0; // 删除
    const STATUS_WAIT_ACTIVE = 3; // 待激活
    const STATUS_FREEZED = 4; // 冻结

    // 是否对私账户
    const BANK_PRIVATE = 1;
    const BANK_PRIVATE_NOT = 0;
    public static function getAllBankType()
    {
        return [
            1=>'是',
            0=>'否'
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'stores';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['s_name', 's_owner_email', 's_owner_phone', 's_owner_name', 's_remark', 's_county', 's_bank_people_name','s_idcard_num', 's_bank_num', 's_bank_sub', 's_bank_addr',
                's_bank_name', 's_bank_is_private', 's_city', 's_province', 's_addr', 's_service_charge'], 'required', 'except' => 'search'],

            [['s_add_user_id', 's_auditor_id', 's_status', 's_bank_is_private', 's_created_at', 's_updated_at'], 'integer'],
            [['s_service_charge', 's_bank_num'], 'number'],
            [['s_add_user_name', 's_owner_name', 's_bank_people_name'], 'string', 'max' => 10],
            [['s_name', 's_bank_addr', 's_gov_name'], 'string', 'max' => 30],
            [['s_owner_phone'], 'string', 'max' => 11],
            [['s_remark', 's_refuse_reason'], 'string', 'max' => 250],
            [['s_county', 's_bank_sub', 's_bank_name', 's_city', 's_province'], 'string', 'max' => 20],
            [['s_addr', 's_photo_one', 's_photo_two', 's_photo_three', 's_photo_four', 's_photo_five', 's_photo_six', 's_photo_seven', 's_photo_eight'], 'string', 'max' => 50],
            ['s_idcard_num', 'match', 'pattern'=>'/^(\d{15}$|^\d{18}$|^\d{17}(\d|X|x))$/', 'message'=>'身份证号码错误'],
            [['s_owner_phone'], 'match', 'pattern' => '/^1[3|5|7|8]\d{9}/', 'except' => 'search'],
            [['s_owner_email'], 'email', 'except' => 'search']
        ];
    }

    public function scenarios()
    {
        $scen = parent::scenarios();
        $scen['search'] = ['s_name', 's_owner_phone', 's_owner_name', 's_province', 's_city', 's_county'];
        return $scen;
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            return $this->s_updated_at = time();
        }
        return false;

    }

    /**
     * 返回所有状态
     * @return array
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public static function getAllStatus()
    {
        return [
            self::STATUS_ACTIVE=>'正常',
            self::STATUS_STOP=>'关闭',
            self::STATUS_REFUSE=>'审核拒绝',
            self::STATUS_WAIT_ACTIVE=>'待激活',
            self::STATUS_FREEZED=>'冻结'
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            's_id' => 'ID',
            's_add_user_id' => '添加人id',
            's_add_user_name' => '添加人姓名',
            's_auditor_id' => '审核人id',
            's_name' => '商铺招牌名称',
            's_owner_email' => '负责人邮箱',
            's_owner_phone' => '负责人电话',
            's_owner_name' => '商铺负责人姓名',
            's_remark' => '备注',
            's_refuse_reason' => '拒绝原因',
            's_status' => '商铺状态 10正常 0待审核 1审核拒绝',
            's_county' => '县/区',
            's_bank_people_name' => '结算账户的账户所有人姓名',
            's_idcard_num'=>'结算账户的账户所有人身份证号码',
            's_bank_num' => '结算账户卡号',
            's_bank_sub' => '结算账户开户行支行',
            's_bank_addr' => '结算账户开户行省市',
            's_bank_name' => '结算账户银行名',
            's_bank_is_private' => '是否对私账户 1是 0否',
            's_city' => '市',
            's_province' => '省',
            's_gov_name' => '商铺工商局注册名称',
            's_addr' => '商铺具体地址',
            's_service_charge' => '门店服务费',
            's_created_at' => '商铺添加时间',
            's_updated_at' => '商铺修改时间',
        ];
    }

    public function createStores($param)
    {
        if($this->load($param) && $this->validate()) {
            $user = Yii::$app->getUser()->getIdentity();
            $this->s_add_user_id = $user->getId();
            $this->s_add_user_name = $user->realname;
            $this->s_created_at = $_SERVER['REQUEST_TIME'];

            return $this->save(false) ? $this : null;
        }
        return null;
    }

    public function updateStore($params)
    {
        $this->load($params);
        if ($this->validate()) {
            return $this->save(false) ? $this : null;
        }
        return null;
    }

    public function search($param)
    {
        $this->setScenario('search');
        $this->load($param);
        $query = self::find()->where(['!=', 's_status', self::STATUS_DELETE]);
        if (!$this->validate()) {
            return $query->where('1=2');
        }

        $query->andFilterWhere(['like', 's_owner_name', $this->s_owner_name])
            ->andFilterWhere(['like', 's_owner_phone', $this->s_owner_phone])
            ->andFilterWhere(['like', 's_name', $this->s_name])
            ->andFilterWhere(['like', 's_owner_email', $this->s_owner_email]);
        $query->andFilterWhere(['s_province'=>$this->s_province]);
        $query->andFilterWhere(['s_city'=>$this->s_city]);
        $query->andFilterWhere(['s_county'=>$this->s_county]);
        return $query;
    }


}
