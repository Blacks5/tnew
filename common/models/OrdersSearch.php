<?php
/**
 * Created by PhpStorm.
 * User: too
 * Date: 2016/8/31
 * Time: 16:31
 * @author 涂鸿 <hayto@foxmail.com>
 */

namespace common\models;

use yii;
use common\core\CoreCommonModel;

class OrdersSearch extends CoreCommonModel
{

    /*public function scenarios()
    {
        $scens = parent::scenarios();
        $scens['search'] = [];
        return $scens;
    }*/
    public $customer_name;
    public $customer_cellphone;
    public $product_name;
    public $goods_name;
    public $start_time;
    public $end_time;
    public $c_customer_province;
    public $c_customer_city;
    public $c_customer_county;
    public $customer_id; // customer_id 客户ID
    public function rules()
    {
        return [
            [['customer_name', 'customer_cellphone', 'product_name', 'goods_name', 'start_time', 'end_time'], 'safe'],
            [['customer_id'], 'number'],
            [['c_customer_province', 'c_customer_city', 'c_customer_county'], 'safe']
        ];
    }

    public function search($param)
    {
        $select = ['orders.id as o_id', 'orders.total_price as o_total_price', 'orders.total_deposit as o_total_deposit', 'orders.created_at as o_created_at',
            'product.name as p_name', 'product.period as p_period'
            , 'customer.customer_name as c_customer_name', 'customer.customer_cellphone as c_customer_cellphone'
            /*, 'group_concat(goods.goods_name)'*/
        ];
        $select = ['orders.*', 'product.*', 'customer.*','status'=>new yii\db\Expression("MAX(yijifu_loan.status)"), 'total_repay_money'=>new yii\db\Expression("sum(r_total_repay)")];
        /*
         * 搜索条件：借款人姓名，借款人电话     产品名 商品名
         * */
//        $this->setScenario('search');
        $this->load($param);
        $query = Orders::find()->alias('orders')
            ->select($select)
            ->leftJoin(Product::tableName(). ' product', 'product.p_id=orders.o_product_id') // 关联产品
            ->leftJoin(Customer::tableName(). ' customer', 'customer.c_id=orders.o_customer_id') // 关联客户
            ->leftJoin(Repayment::tableName(). ' repayment', 'repayment.r_orders_id=orders.o_id') // 要统计总还款金额
            ->leftJoin(YijifuLoan::tableName(). ' yijifu_loan', 'yijifu_loan.y_serial_id=orders.o_serial_id') // 查询是否已成功放款给商户
        ;

        if($user = yii::$app->user->identity){
            if($userList = User::getLowerForId()){
                $query->andWhere(['in', 'orders.o_user_id', $userList]);
            }
        }

        if(!$this->validate()){
            return $query->where('1=2');
        }

        // 某个客户的订单
        $query->andFilterWhere(['orders.o_customer_id'=>$this->customer_id]);

        // 有商品名搜索才关联
        if($this->goods_name){
            $query->leftJoin(Goods::tableName(). ' goods', 'goods.g_order_id=orders.o_id'); // 关联商品
            $query->andFilterWhere(['like', 'goods.g_goods_name', $this->goods_name]);
        }

        $query->andFilterWhere(['like', 'customer.c_customer_name', $this->customer_name])
            ->andFilterWhere(['like', 'customer.c_customer_cellphone', $this->customer_cellphone])
            ->andFilterWhere(['like', 'product.p_name', $this->product_name])
            ->andFilterWhere(['c_customer_province'=>$this->c_customer_province])
            ->andFilterWhere(['c_customer_city'=>$this->c_customer_city])
            ->andFilterWhere(['c_customer_county'=>$this->c_customer_county])
        ;
        if($this->start_time) $query->andWhere(['>=', 'o_created_at', strtotime($this->start_time)]);
        if($this->end_time) $query->andWhere(['<=', 'o_created_at', strtotime($this->end_time)]);
        return $query->groupBy(['o_id']); // 避免对应多个商品出现多行
    }

    public function getOrderId()
    {
        $userList = User::getLowerForId();

        $orderId = Orders::find()->select(['o_serial_id'])->where(['in', 'o_user_id'], $userList)->asArray()->column();

        return $orderId;
    }
}