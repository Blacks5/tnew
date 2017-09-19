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
     * 获取30天订单详情
     * @param $params
     * @return $this
     */
    public function repaymentListByOrders($params)
    {
        $query = Orders::find()
            ->select(['*'])
            ->leftJoin(Repayment::tableName(), 'r_orders_id=o_id')
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
     * 第一版规则:
     * 还款小于3期 + 200; 大于三期不过没买贵宾包 +200
     * 有逾期的需加上逾期期间的利息和滞纳金
     * 属于本期应还的+本期利息
     * 需还本金+各种服务(除利息外)
     * 第二版规则:
     * 还款小于3期 + 200; 大于三期不过没买贵宾包 +200
     * 有逾期的+ 月供和滞纳金
     * 本期+月供 (本期算法:应还款日-当日<4)
     * 全部还完只收之后本金,不然算月供
     * @param $order_id 订单Id $num 还款期数
     * @return mixed
     * @author OneStep
     */
    public function getAdvanceMoney($order_id, $num)
    {
        $total['total'] = 0;    //总金额
        $total['num'] = [];    //期数
        $sql = Repayment::find()->where(['r_orders_id'=>$order_id, 'r_status'=>1])->orderBy('r_pre_repay_date');
        $repayCount = $sql->count();
        $data = $sql->limit($num)->all();
        if($num == $repayCount){    //判断是否全部还完
            foreach ($data as $k => $d){
                $date = Carbon::createFromTimestamp($d['r_pre_repay_date']);
                $total['total'] += $d['r_principal']; //获取所有的 本金
                if($d['r_overdue_day']>3){    //如果逾期,加上除本金外的费用和滞纳金
                    $total['total'] += $d['r_total_repay']-$d['r_principal'] + $d['r_overdue_money'];
                }
                if($date->day - Carbon::now()->day > 3 && $date->month = Carbon::now()->month){ //如果是属于当期金额 需要还利息
                    $total['total'] += $d['r_total_repay']-$d['r_principal'];
                }
                array_push($total['num'], $d['r_id']);
            }
        }else{  // 没有还完所有期, 还款金额为月供 和 滞纳金
            foreach ($data as $k => $d){
                $total['total'] += $d['r_total_repay'] + $d['r_overdue_money'];
                array_push($total['num'], $d['r_id']);
            }
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