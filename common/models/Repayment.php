<?php

namespace common\models;

use Yii;
use common\core\CoreCommonActiveRecord;

/**
 * This is the model class for table "repayment".
 *
 * @property integer $r_id
 * @property integer $r_customer_id
 * @property integer $r_orders_id
 * @property string $r_principal
 * @property string $r_interest
 * @property string $r_add_service_fee
 * @property string $r_free_pack_fee
 * @property string $r_finance_mangemant_fee
 * @property string $r_customer_management
 * @property integer $r_pre_repay_date
 * @property integer $r_repay_date
 * @property integer $r_status
 * @property integer $r_is_last
 * @property integer $r_serial_no
 * @property integer $r_operator_id
 * @property integer $r_operator_date
 * @property integer $c_customer_cellphone
 */
class Repayment extends CoreCommonActiveRecord
{

    const STATUS_ALREADY_PAY = 10; // 已还
    const STATUS_NOT_PAY = 1; // 未还
    const STATUS_OVERDUE = 2; // 已逾期【没用了】
    const OVERDUE = 1;
    const NOT_OVERDUE = 0;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'repayment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['r_customer_id', 'r_orders_id', 'r_principal', 'r_interest', 'r_add_service_fee', 'r_free_pack_fee', 'r_finance_mangemant_fee', 'r_customer_management', 'r_pre_repay_date', 'r_repay_date', 'r_status', 'r_is_last', 'r_serial_no', 'r_operator_id', 'r_operator_date'], 'required'],
            [['r_customer_id', 'r_orders_id', 'r_pre_repay_date', 'r_repay_date', 'r_status', 'r_is_last', 'r_serial_no', 'r_operator_id', 'r_operator_date'], 'integer'],
            [['r_principal', 'r_interest', 'r_add_service_fee', 'r_free_pack_fee', 'r_finance_mangemant_fee', 'r_customer_management'], 'number'],


        ];
    }

    public function scenarios()
    {
        $s = parent::scenarios();
//        $s['search'] = ['c_customer_name', 'c_customer_id_card', 'c_customer_cellphone'];
        return $s;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'r_id' => 'R ID',
            'r_customer_id' => '借款人（客户）',
            'r_orders_id' => '订单id',
            'r_principal' => '应还本金',
            'r_interest' => '应还利息',
            'r_add_service_fee' => '贵宾服务包',
            'r_free_pack_fee' => '随心包服务费',
            'r_finance_mangemant_fee' => '财务管理费',
            'r_customer_management' => '客户管理费',
            'r_pre_repay_date' => '应还时间',
            'r_repay_date' => '实际还款时间',
            'r_status' => '状态：1未还 2已还',
            'r_is_last' => '是否最后一期：1是 2不是',
            'r_serial_no' => '当前第几期',
            'r_operator_id' => '操作人id',
            'r_operator_date' => '操作时间',
            'c_customer_name' => '操作时间1',
        ];
    }

    /**
     * 订单的所有还款计划
     * @param $order_id
     * @return $this
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public static function getAllRepaymentByOrderid($order_id)
    {
        return self::find()->where(['r_orders_id'=>$order_id])->asArray();
    }

}
