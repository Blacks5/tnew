<?php
/**
 * Created by PhpStorm.
 * Date: 16/9/4
 * Time: 20:13
 * @author 涂鸿 <hayto@foxmail.com>
 */

namespace common\models;

use yii;
use backend\core\CoreBackendModel;
class RepaymentSearch extends CoreBackendModel
{
    public $c_customer_name;
    public $c_customer_id_card;
    public $c_customer_cellphone;
    public $s_time;
    public $e_time;
    public function rules()
    {
        return [[['c_customer_name', 'c_customer_id_card', 'c_customer_cellphone', 's_time', 'e_time'], 'safe']];
    }

    /**
     * 取最近30天的
     * @param $params
     * @return $this
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function repaymenlist($params)
    {
        $time = $_SERVER['REQUEST_TIME']+(3600*24*33); // 最近30天的
        $query = Repayment::find()->select(['*'])->where(['<=', 'r_pre_repay_date', $time])
            ->leftJoin(Orders::tableName(), 'o_id=r_orders_id')
            ->leftJoin(Customer::tableName(), 'r_customer_id=c_id');
        $this->load($params);
        if(!$this->validate()){
            return $query->andwhere('1=2');
        }

        $query
            ->andFilterWhere(['like', 'c_customer_name', $this->c_customer_name])
            ->andFilterWhere(['like', 'c_customer_id_card', $this->c_customer_id_card])
            ->andFilterWhere(['like', 'c_customer_cellphone', $this->c_customer_cellphone]);
        if (!empty($this->s_time)) {
            $this->s_time = strtotime($this->s_time . '00:00:00');
            $query->andWhere(['>=', 'r_pre_repay_date', $this->s_time]);
        }
        if (!empty($this->e_time)) {
            $this->e_time = strtotime($this->e_time . '23:59:59');
            $query->andWhere(['<=', 'r_pre_repay_date', $this->e_time]);
        }
        return $query->orderBy(['r_pre_repay_date' => SORT_ASC]);
    }

    /**
     * 取某个id的所有还款计划
     * @param $params
     * @return $this
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public static function repaymenlistbyorderid($order_id)
    {
        $query = Repayment::find()->select(['*'])->where(['r_orders_id'=>$order_id])
            ->leftJoin(Orders::tableName(), 'o_id=r_orders_id')
            ->leftJoin(Customer::tableName(), 'r_customer_id=c_id');
        return $query->orderBy(['r_pre_repay_date' => SORT_ASC]);
    }
}