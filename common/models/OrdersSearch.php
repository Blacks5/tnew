<?php
/**
 * Created by PhpStorm.
 * User: too
 * Date: 2016/8/31
 * Time: 16:31
 * @author 涂鸿 <hayto@foxmail.com>
 */

namespace app\models;

use yii;
use app\core\base\BaseModel;

class OrdersSearch extends BaseModel
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
    public function rules()
    {
        return [
            [['customer_name', 'customer_cellphone', 'product_name', 'goods_name', 'start_time', 'end_time'], 'safe']
        ];
    }

    public function search($param)
    {
        $select = ['orders.id as o_id', 'orders.total_price as o_total_price', 'orders.total_deposit as o_total_deposit', 'orders.created_at as o_created_at',
            'product.name as p_name', 'product.period as p_period'
            , 'customer.customer_name as c_customer_name', 'customer.customer_cellphone as c_customer_cellphone'
            /*, 'group_concat(goods.goods_name)'*/
        ];
        $select = ['orders.*', 'product.*', 'customer.*'];
        /*
         * 搜索条件：借款人姓名，借款人电话     产品名 商品名
         * */
//        $this->setScenario('search');
        $this->load($param);
        $query = Orders::find()->alias('orders')
            ->select($select)
            ->leftJoin(Product::tableName(). ' product', 'product.p_id=orders.o_product_id') // 关联产品
            ->leftJoin(Customer::tableName(). ' customer', 'customer.c_id=orders.o_customer_id') // 关联客户
        ;
        if(!$this->validate()){
            return $query->where('1=2');
        }
        // 有商品名搜索才关联
        if($this->goods_name){
            $query->leftJoin(Goods::tableName(). ' goods', 'goods.g_order_id=orders.o_id'); // 关联商品
            $query->andFilterWhere(['like', 'goods.g_goods_name', $this->goods_name]);
        }

        $query->andFilterWhere(['like', 'customer.c_customer_name', $this->customer_name])
            ->andFilterWhere(['like', 'customer.c_customer_cellphone', $this->customer_cellphone])
            ->andFilterWhere(['like', 'product.p_name', $this->product_name]);
        if($this->start_time) $query->andWhere(['>=', 'o_created_at', strtotime($this->start_time)]);
        if($this->end_time) $query->andWhere(['<=', 'o_created_at', strtotime($this->end_time)]);
        return $query->groupBy(['o_id']); // 避免对应多个商品出现多行
    }
}