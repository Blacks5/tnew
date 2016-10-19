<?php
/**
 * Created by PhpStorm.
 * Date: 16/9/4
 * Time: 20:13
 * @author 涂鸿 <hayto@foxmail.com>
 */

namespace app\models;

use yii;
use app\core\base\BaseModel;
class RepaymentSearch extends BaseModel
{
    public $c_customer_name;
    public $c_customer_id_card;
    public $c_customer_cellphone;
    public function rules()
    {
        return [[['c_customer_name', 'c_customer_id_card', 'c_customer_cellphone'], 'safe']];
    }

    public function repaymenlist($params)
    {
//        $time = $_SERVER['REQUEST_TIME']+(3600*24*30); // 最近一个月的
//        $query = Repayment::find()->select(['*'])->leftJoin(Customer::tableName(), 'r_customer_id=c_id')->where(['<=', 'r_pre_repay_date', $time]);
        $query = Repayment::find()->select(['*'])
            ->leftJoin(Orders::tableName(), 'o_id=r_orders_id')
            ->leftJoin(Customer::tableName(), 'r_customer_id=c_id');
//        $this->scenario = 'search';
        $this->load($params);
        if(!$this->validate()){
            return $query->andwhere('1=2');
        }

        $query
            ->andFilterWhere(['like', 'c_customer_name', $this->c_customer_name])
            ->andFilterWhere(['like', 'c_customer_id_card', $this->c_customer_id_card])
            ->andFilterWhere(['like', 'c_customer_cellphone', $this->c_customer_cellphone]);
        return $query->orderBy(['r_pre_repay_date' => SORT_ASC]);
    }
}