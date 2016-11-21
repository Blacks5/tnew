<?php

/**
 * Created by PhpStorm.
 * User: too
 * Date: 2016/8/29
 * Time: 13:46
 * @author 涂鸿 <hayto@foxmail.com>
 */
namespace api\models;

use api\components\CustomApiException;
use common\models\Sms;
use common\models\Tools;
use common\models\Customer;
use common\models\Goods;
use common\models\Orders;
use yii\base\Exception;
use common\models\OrderImages;

class OrdersHelper
{
    public $store_id; // 商铺id
    public $product_id; // 产品id
    public $customer_id; // 客户id
/*
 * 1写image表 2写customer
 * order需要goods表的数据o_product_id     image表o_images_id  customer表o_customer_id
 * goods表,再写
 * */
    public function placeOrders($params)
    {
        // 首先验证码
        $verify = new Sms();
        if(!$verify->verify($params['c_customer_cellphone'], '1234')){
//            throw new CustomException('验证码错误');
        }
        $data['data'] = $params;
        // 1客户表 2订单表 3产品表

// 商品信息
        $length = count($params['g_goods_name']); // 判断有几个商品

        $total_price = 0; // 总金额
        $total_deposit = 0; // 总首付
        $goods_num = 0; // 订单包含的商品数
        // 构造商品信息数组 todo 差个order_id
        $columns_goods = ['g_goods_name', 'g_goods_models', 'g_goods_price', 'g_goods_type', 'g_goods_deposit', 'g_order_id'];
        for($n=0; $n<$length; $n++){
            if(!isset($params['g_goods_models'][$n], $params['g_goods_price'][$n], $params['g_goods_type'][$n], $params['g_goods_deposit'][$n])){
                continue;
            }
            $data_goods[$n][]  = $params['g_goods_name'][$n];
            $data_goods[$n][]  = $params['g_goods_models'][$n];
            $data_goods[$n][]  = $params['g_goods_price'][$n];
            $data_goods[$n][]  = $params['g_goods_type'][$n];
            $data_goods[$n][]  = $params['g_goods_deposit'][$n];

            // 计算订单总金额和总首付
            $total_price += $params['g_goods_price'][$n];
            $total_deposit += $params['g_goods_deposit'][$n];
            $goods_num += 1;
        }
            // 1写商品表

//p($data_goods);

        // 1客户信息
        $transaction = \Yii::$app->db->beginTransaction();
        try{
            if(!$serial_id = Tools::generateId()){
                throw new CustomApiException('订单错误');
            }
            $userid = \Yii::$app->getUser()->getIdentity()->getId();
            // 1写order_images表
            $images_model = new OrderImages();
            $images_model->oi_user_id = $userid;
            if(!$images_model->save(false)){
                throw new CustomApiException('images-errors');
            }
            // 2写customer表
            if($customerModel = Customer::findOne(['c_customer_id_card'=>$params['c_customer_id_card']])){
                if($customerModel->c_is_forbidden == 1){
                    if($customerModel->c_forbidden_time > $_SERVER['REQUEST_TIME']){
                        throw new CustomApiException('提交的订单被拒绝过,在'.date('Y-m-d H:i:s', $customerModel->c_forbidden_time). '后才能再次借款');
                    }else{
                        // 解除黑名单
                        $customerModel->c_is_forbidden = 0;
                        $customerModel->c_forbidden_time = 0;
                    }
                }
            }else{
                $customerModel = new Customer();
            }

            $customerModel->load($data, 'data');
            if(!$customerModel->validate()){
                var_dump($customerModel->errors);
                throw new CustomApiException('customer-errors');
            }
            $customerModel->c_total_money = $total_price;//
            $customerModel->c_customer_addr_province = $params['c_customer_addr_province'];//
            $customerModel->c_total_borrow_times += 1;// 总借款次数
            $customerModel->c_created_at = $_SERVER['REQUEST_TIME'];//
            if(!$customerModel->save(false)){
                throw new CustomApiException('用户写入失败');
            }

            // 3写orders表 todo 差个customer_id
            $ordersModel = new Orders();
            $ordersModel->load($data, 'data');
            $ordersModel->o_serial_id = $serial_id;
            $ordersModel->o_total_deposit = $total_deposit;
            $ordersModel->o_user_id = $userid;
            $ordersModel->o_total_price = $total_price;
            $ordersModel->o_images_id = $images_model->oi_id;
            $ordersModel->o_goods_num = $goods_num;
            $ordersModel->o_created_at = $_SERVER['REQUEST_TIME'];
            $ordersModel->o_customer_id = $customerModel->c_id;
            $ordersModel->o_is_auto_pay = $params['o_is_auto_pay']; // 银行代扣
            if(!$ordersModel->validate()){
                throw new CustomApiException('order-errors');
            }
            if(!$ordersModel->save(false)){
                throw new CustomApiException('订单写入失败');
            }


            // 4写goods表
            array_walk($data_goods, function(&$v, $k, $order_id){
                $v[] = $order_id; // 把store_id追加进数组
            }, $ordersModel->o_id);
            if(\Yii::$app->db->createCommand()
                    ->batchInsert(Goods::tableName(), $columns_goods, $data_goods)
                    ->execute() <=0 ){
                throw new CustomApiException('商品写入失败');
            }
            $transaction->commit();
            return ['status'=>1, 'message'=>'提交成功'];
        }catch(CustomApiException $e){
            $transaction->rollBack();
            throw $e;
        }catch(Exception $e){
            $transaction->rollBack();
            throw $e;
        }



    }
    /*
     * 1. 完整提交订单
     * 2. 根据订单生成还款计划[等额本息算法]
     * 2. 还款计划列表
     * 3. 安卓上传图片[周末]
     * 5.
     * */
}