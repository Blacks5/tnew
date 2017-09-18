<?php
/**
 * Created by PhpStorm.
 * Date: 16/9/4
 * Time: 20:13
 * @author 涂鸿 <hayto@foxmail.com>
 */

namespace common\models;

use backend\models\YejiSearch;
use Carbon\Carbon;
use yii;
use backend\core\CoreBackendModel;
use common\models\User;
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
        $query = Repayment::find()
            ->select(['*'])
            ->leftJoin(Orders::tableName(), 'o_id=r_orders_id')
            ->leftJoin(Customer::tableName(), 'r_customer_id=c_id')
            ->leftJoin(Product::tableName(), 'o_product_id=p_id');
        $this->load($params);
        if(!$this->validate()){
            return $query->andwhere('1=2');
        }

        if(!yii::$app->session->get('sys_user')){
            if($user = User::getLowerForId()){
                $query->andWhere(['in', 'orders.o_user_id', $user]);
            }
        }

        $query->andFilterWhere(['like', 'c_customer_name', $this->c_customer_name])
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

        //var_dump($query->createCommand()->getRawSql());
        return $query->orderBy(['r_pre_repay_date' => SORT_ASC]);
    }

    /**
     * 取某个id的所有还款计划
     * @param $params
     * @return $this
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public static function repaymenlistbyorderid($order_id,$field='*')
    {
        $query = Repayment::find()->select([$field])->where(['r_orders_id'=>$order_id])
            ->leftJoin(Orders::tableName(), 'o_id=r_orders_id')
            ->leftJoin(Customer::tableName(), 'r_customer_id=c_id');
        return $query->orderBy(['r_pre_repay_date' => SORT_ASC]);
    }

    /**
     * 获取还款金额
     * 规则:
     * 还款小于3期 + 200; 大于三期不过没买贵宾包 +200
     * 有逾期的需加上逾期期间的利息和滞纳金
     * 属于本期应还的+本期利息
     * 需还本金+各种服务(除利息外)
     * @param $order_id 订单Id $num 还款期数
     * @return mixed
     * @author OneStep
     */
    public function getAdvanceMoney($order_id, $num)
    {
        $total['total'] = 0;    //总金额
        $total['num'] = [];    //期数
        $data = Repayment::find()->where(['r_orders_id'=>$order_id, 'r_status'=>1])->orderBy('r_pre_repay_date')->limit($num)->all();
        foreach ($data as $k => $d){
            $date = Carbon::createFromTimestamp($d['r_pre_repay_date']);
            $total['total'] += $d['r_total_repay'] - $d['r_interest']; //获取所有的 本金 + 各种服务费用(除利息,都得还)
            if($d['r_overdue_day']>3){    //如果逾期,加上逾期的利息和滞纳金
                $total['total'] += $d['r_interest'] + $d['r_overdue_money'];
            }
            if($date < Carbon::now()->addMonth()){ //如果是属于当期金额 需要还利息
                $total['total'] += $d['r_interest'];
            }
            array_push($total['num'], $d['r_id']);
        }
        $sql = Repayment::find()
            ->select('o_is_free_pack_fee')
            ->leftJoin(Orders::tableName(), 'o_id=r_orders_id')
            ->where(['r_orders_id'=>$order_id, 'r_status'=>10]);
        $repayCount = $sql->count();
        $isPack = $sql->asArray()->one();
        if($repayCount<3 || $isPack['o_is_free_pack_fee'] == 0){  //如果还款小于3期 或者 未购买贵宾包 +200
            $total['total'] += 200;
        }
        return $total;
    }

}