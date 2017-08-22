<?php

namespace common\models;

use Yii;
use common\core\CoreCommonActiveRecord;
/**
 * This is the model class for table "customer".
 *
 * @property integer $c_id
 * @property string $c_customer_name
 * @property string $c_customer_id_card
 * @property string $c_customer_cellphone
 * @property integer $c_customer_id_card_endtime
 * @property integer $c_customer_county
 * @property integer $c_customer_city
 * @property integer $c_customer_province
 * @property integer $c_customer_gender
 * @property string $c_customer_idcard_detail_addr
 * @property string $c_customer_qq
 * @property string $c_customer_wechat
 * @property string $c_customer_email
 * @property integer $c_family_marital_status
 * @property string $c_family_marital_partner_name
 * @property string $c_family_marital_partner_cellphone
 * @property integer $c_family_house_info
 * @property integer $c_family_expenses
 * @property integer $c_family_income
 * @property string $c_kinship_name
 * @property integer $c_kinship_relation
 * @property string $c_kinship_cellphone
 * @property string $c_kinship_addr
 * @property integer $c_customer_addr_province
 * @property integer $c_customer_addr_city
 * @property integer $c_customer_addr_county
 * @property string $c_customer_addr_detail
 * @property string $c_customer_jobs_company
 * @property integer $c_customer_jobs_industry
 * @property integer $c_customer_jobs_type
 * @property string $c_customer_jobs_section
 * @property string $c_customer_jobs_title
 * @property integer $c_customer_jobs_is_shebao
 * @property integer $c_customer_jobs_province
 * @property integer $c_customer_jobs_city
 * @property integer $c_customer_jobs_county
 * @property string $c_customer_jobs_detail_addr
 * @property string $c_customer_jobs_phone
 * @property integer $c_other_people_relation
 * @property string $c_other_people_name
 * @property string $c_other_people_cellphone
 * @property integer $c_created_at
 * @property integer $c_updated_at
 * @property string $c_total_interest
 * @property string $c_total_money
 * @property string $c_total_borrow_times
 * @property integer $c_status
 * @property integer $c_bank
 * @property integer $c_banknum
 * @property integer $c_backnum_owner
 * c_bank 开户银行
c_banknum 银行卡号
c_backnum_owner 开户人姓名
 */
class Customer extends CoreCommonActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'customer';
    }

    const GENDER_MAN = 1;
    const GENDER_WOMAN = 0;
    public static function getAllGender()
    {
        return [
            self::GENDER_WOMAN =>'女',
            self::GENDER_MAN =>'男',
        ];
    }

    const STATUS_OK = 10;
    const STATUS_NOT_OK = 0;
    public static function getAllStatus()
    {
        return [
            self::STATUS_OK => '正常',
            self::STATUS_NOT_OK =>'拉黑'
        ];
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // 当已婚时，配偶才是必填项
            [['c_family_marital_partner_name', 'c_family_marital_partner_cellphone'], 'required', 'when'=>function(){
                return $this->c_family_marital_status == '2'; // 2是已婚
            }],

            [['c_customer_name', 'c_customer_id_card', 'c_customer_cellphone', 'c_customer_id_card_endtime', 'c_customer_county', 'c_customer_city', 'c_customer_province', 'c_customer_gender', 'c_customer_idcard_detail_addr', 'c_family_marital_status', 'c_family_house_info'/*, 'c_family_expenses'*/, 'c_family_income', 'c_kinship_name', 'c_kinship_relation', 'c_kinship_cellphone', /*'c_kinship_addr', */'c_customer_addr_province', 'c_customer_addr_city', 'c_customer_addr_county', 'c_customer_addr_detail', 'c_customer_jobs_company', 'c_customer_jobs_industry', 'c_customer_jobs_type', 'c_customer_jobs_section', 'c_customer_jobs_title', 'c_customer_jobs_is_shebao', 'c_customer_jobs_province', 'c_customer_jobs_city', 'c_customer_jobs_county', 'c_customer_jobs_detail_addr', 'c_customer_jobs_phone', 'c_other_people_relation', 'c_other_people_name', 'c_other_people_cellphone', 'c_bank', 'c_banknum'/*, 'c_banknum_owner'*/], 'required'],
            [['c_customer_id_card_endtime', 'c_customer_county', 'c_customer_city', 'c_customer_province', 'c_customer_gender', 'c_family_marital_status', 'c_family_house_info'/*, 'c_family_expenses'*/, 'c_family_income', 'c_kinship_relation', 'c_customer_addr_province', 'c_customer_addr_city', 'c_customer_addr_county', 'c_customer_jobs_industry', 'c_customer_jobs_type', 'c_customer_jobs_is_shebao', 'c_customer_jobs_province', 'c_customer_jobs_city', 'c_customer_jobs_county', 'c_other_people_relation', 'c_created_at', 'c_updated_at', 'c_status'], 'integer'],
            [['c_total_interest', 'c_total_money'], 'number'],
            [['c_customer_name'], 'string', 'max' => 5],
            [['c_customer_id_card'], 'string', 'max' => 18],

            [['c_customer_cellphone', 'c_family_marital_partner_cellphone', 'c_kinship_cellphone', 'c_other_people_cellphone'], 'match', 'pattern' => '/^(?=\d{11}$)^1(?:3\d|4[57]|5[^4\D]|7[^249\D]|8\d)\d{8}$/'],

            [['c_customer_idcard_detail_addr'], 'string', 'max' => 255],
            [['c_customer_qq'], 'string', 'max' => 13],
            [['c_customer_wechat'], 'string', 'max' => 30],
//            [['c_customer_email'], 'string', 'max' => 30],
            [['c_family_marital_partner_name', 'c_other_people_name'], 'string', 'max' => 10],
            [['c_kinship_name'], 'string', 'max' => 15],
            [[/*'c_kinship_addr', */'c_customer_addr_detail'], 'string', 'max' => 100],
            [['c_customer_jobs_company', 'c_customer_jobs_section', 'c_customer_jobs_title', 'c_customer_jobs_detail_addr'], 'string', 'max' => 20],

            [['c_created_at', 'c_updated_at', 'c_total_interest', 'c_status', 'c_total_borrow_times'], 'safe']
        ];
    }

    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert)){
            return $this->c_updated_at = $_SERVER['REQUEST_TIME'];
        }
        return false;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'c_id' => 'ID',
            'c_customer_name' => '客户姓名',
            'c_customer_id_card' => '客户身份证号码',
            'c_customer_cellphone' => '客户手机号码',
            'c_customer_id_card_endtime' => '客户身份证到期日',
            'c_customer_county' => '客户县/区',
            'c_customer_city' => '客户户籍-市',
            'c_customer_province' => '客户户籍-省',
            'c_customer_gender' => '客户性别：1男 0女',
            'c_customer_idcard_detail_addr' => '客户身份证详细地址',
            'c_customer_qq' => '客户qq号码',
            'c_customer_wechat' => '客户微信',
//            'c_customer_email' => '客户email',
            'c_family_marital_status' => '婚姻状况：1未婚 2已婚 3离异 4丧偶',
            'c_family_marital_partner_name' => '配偶姓名1',
            'c_family_marital_partner_cellphone' => '配偶电话',
            'c_family_house_info' => '住房情况：1自有 2租住 3父母房产 4单位提供福利',
            'c_family_expenses' => '每月总支出', // 这个去掉
            'c_family_income' => '每月总收入',
            'c_kinship_name' => '亲属姓名',
            'c_kinship_relation' => '亲属关系：1父亲 2母亲 3兄弟 4姐妹 5子女 6表兄弟 7表姐妹 8其他',
            'c_kinship_cellphone' => '亲属手机号',
//            'c_kinship_addr' => '亲属联系地址',
            'c_customer_addr_province' => '客户现居住省',
            'c_customer_addr_city' => '客户现居住市',
            'c_customer_addr_county' => '客户现居住县',
            'c_customer_addr_detail' => '客户现居住详细地址',
            'c_customer_jobs_company' => '客户工作单位',
            'c_customer_jobs_industry' => '客户单位行业：1餐饮 2娱乐 3金融【随便几个示例】',
            'c_customer_jobs_type' => '客户行业性质：1私有 2公有 3国企',
            'c_customer_jobs_section' => '客户任职部门，随便填',
            'c_customer_jobs_title' => '客户职务',
            'c_customer_jobs_is_shebao' => '是否购买社保：1是0否',
            'c_customer_jobs_province' => '客户单位省',
            'c_customer_jobs_city' => '客户单位市',
            'c_customer_jobs_county' => '客户单位县',
            'c_customer_jobs_detail_addr' => '客户单位详细地址，随便写',
            'c_customer_jobs_phone' => '客户单位座机',
            'c_other_people_relation' => '其他联系人关系：1同事 2朋友 3同学',
            'c_other_people_name' => '其他联系人姓名',
            'c_other_people_cellphone' => '其他联系人电话',
            'c_created_at' => 'C Created At',
            'c_updated_at' => 'C Updated At',
            'c_total_interest' => '总支付利息',
            'c_total_money' => '总借款数',
            'c_bank' => '银行名称',
            'c_banknum' => '银行卡号',
            'c_banknum_owner' => '总借款数',
            'c_status' => '客户状态 10正常 0拉黑',
        ];
    }


    public static function getOneDetail($c_id)
    {
        return self::find()->where(['c_id'=>$c_id])->asArray()->one();
    }


}
