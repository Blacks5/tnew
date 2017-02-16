<?php

namespace common\models;

use Yii;
use common\core\CoreCommonActiveRecord;
/**
 * This is the model class for table "product".
 *
 * @property integer $p_id
 * @property string $p_name
 * @property integer $p_period
 * @property double $p_month_rate
 * @property integer $p_add_service_fee
 * @property integer $p_free_pack_fee
 * @property integer $p_finance_mangemant_fee
 * @property integer $p_customer_management
 * @property integer $p_status
 * @property integer $p_type
 * @property integer $p_created_at
 * @property integer $p_updated_at
 */

class Product extends CoreCommonActiveRecord
{
    const STATUS_OK = 10;
    const STATUS_STOP = 1;
    const STATUS_DEL = 0;
    public static function getAllStatus()
    {
        return [
            self::STATUS_OK=>'正常',
            self::STATUS_STOP=>'禁用',
            self::STATUS_DEL=>'删除'
        ];
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['p_name', 'p_type', 'p_period', 'p_month_rate', 'p_add_service_fee', 'p_free_pack_fee', 'p_finance_mangemant_fee', 'p_customer_management'], 'required', 'except'=>'search'],
            [['p_period', 'p_free_pack_fee', 'p_status', 'p_created_at', 'p_updated_at'], 'integer'],
            [['p_month_rate', 'p_add_service_fee', 'p_finance_mangemant_fee', 'p_customer_management'], 'number', 'max'=>'100', 'min'=>'0.0001'],
            [['p_name'], 'unique', 'message' => '{attribute}不能重复'],
            [['p_status'], 'in', 'range'=>[10, 1]],

            ['p_type', 'in', 'range'=>array_column(Yii::$app->params['goods_type'], 't_id')]
        ];
    }


    public function scenarios()
    {
        $scen = parent::scenarios();
        $scen['search'] = ['p_name'];
        return $scen;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'p_id' => 'ID',
            'p_name' => '产品名',
            'p_period' => '产品期数',
            'p_month_rate' => '月利率',
            'p_add_service_fee' => '贵宾服务包',
            'p_free_pack_fee' => '随心包服务费',
            'p_finance_mangemant_fee' => '财务管理费',
            'p_customer_management' => '客户管理费',
            'p_status' => '状态 10 正常 1 禁用',
            'p_created_at' => '添加时间',
            'p_updated_at' => '编辑时间',
        ];
    }

    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert)){
            return $this->p_updated_at = time();
        }
        return false;
    }

    public function createProduct()
    {
        if(!$this->validate()){
//            p($this->errors);
            return false;
        }
        $this->p_created_at = $_SERVER['REQUEST_TIME'];
//        $this->p_month_rate /= 100;
        return $this->save(false) ? $this : null;
    }


    public function updateProduct($param)
    {
        $this->load($param);
        if($this->validate()){
            return $this->save(false) ? $this :null;
        }
    }


    public function search($param)
    {
        $this->setScenario('search');
        $this->load($param);
        $query = self::find()->where(['!=', 'p_status', self::STATUS_DEL]);
        if(!$this->validate()){
            return $query->where('1=2');
        }

        $query->andFilterWhere(['like', 'p_name', $this->p_name]);
        return $query;
    }
}
