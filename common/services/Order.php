<?php
/**
 * Created by PhpStorm.
 * User: too
 * Date: 2017/8/13
 * Time: 21:49
 * @author too <hayto@foxmail.com>
 */

namespace common\services;


use api\components\CustomApiException;
use common\components\CustomCommonException;
use common\models\Customer;
use common\models\Goods;
use common\models\OrderImages;
use common\models\Orders;
use common\models\Product;
use common\models\Sms;
use common\models\User;

class Order
{
    /**
     * 客户端提交订单，成功返回true，失败直接抛异常
     * 调用此方法的控制器，需要得到微信的授权
     * @param $params
     * @return bool
     * @throws CustomCommonException
     * @author too <hayto@foxmail.com>
     */
    public function addOrder($params)
    {
        $data['data'] = $params;
        // 判断验证码【不再需要短信验证码2017-08-21】
        /*$verify = new Sms();
        if(!$verify->verify($params['c_customer_cellphone'], $params['verify_code'])){
            throw new CustomCommonException('验证码错误');
        }*/
//        $user = \Yii::$app->getSession()->get('wechat_user'); // 微信资料

        $sys_user = (new \yii\db\Query())->from(User::tableName())->where(['wechat_openid'=>"o9dGBv_lC9-6tzZPUNyXk-1AM3as"])->one(); // 系统资料
        // 1写order_images表
        $images_model = new OrderImages();
        $images_model->oi_user_id = $sys_user['id'];
        if(!$images_model->save(false)){
            $msg = $images_model->getFirstErrors();
            throw new CustomCommonException(reset($msg));
        }

        // 2写customer表
        if($customerModel = Customer::findOne(['c_customer_id_card'=>$params['c_customer_id_card']])){
            if($customerModel->c_status == 0){
                if($customerModel->c_forbidden_time > $_SERVER['REQUEST_TIME']){
                    throw new CustomCommonException('提交的订单被拒绝过,在'.date('Y-m-d H:i:s', $customerModel->c_forbidden_time). '后才能再次借款');
                }else{
                    // 解除黑名单
                    $customerModel->c_status = 10;
                    $customerModel->c_forbidden_time = 0;
                }
            }
            $customerModel->c_total_borrow_times += 1;
        }else{
            $customerModel = new Customer();
            $customerModel->c_total_borrow_times = 1;
        }
        $customerModel->load($data, 'data');
        if(!$customerModel->validate()){
            $msg = $customerModel->getFirstErrors();
            throw new CustomCommonException(reset($msg));
        }
//            $customerModel->c_total_money += $total_price - $total_deposit; //加上原来的借款总额  放到终审去做2017-01-02
        $customerModel->c_customer_addr_province = $params['c_customer_addr_province'];//
//            $customerModel->c_total_borrow_times += 1;// 总借款次数 放到终审去做
        $customerModel->c_created_at = $_SERVER['REQUEST_TIME'];//

//        var_dump($customerModel->getAttributes());die;
        if(!$customerModel->save(false)){
            throw new CustomCommonException('用户写入失败');
        }

        // 3写orders表
        $ordersModel = new Orders();
        $ordersModel->load($data, 'data');
        if(!$serial_id = \common\models\Tools::generateId($params['c_customer_id_card'])){
            throw new CustomCommonException('生成订单号异常');
        }
        $ordersModel->o_serial_id = $serial_id; // 订单号
        $ordersModel->o_total_deposit = $params['g_goods_deposit']; // 定金
        $ordersModel->o_total_price = $params['g_goods_price']; // 总价格
        $ordersModel->o_user_id = $sys_user['id']; // 提单业务员
        $ordersModel->o_images_id = $images_model->oi_id;
        $ordersModel->o_goods_num = 1; // 历史遗留问题，现在一个订单只能有一个商品
        $ordersModel->o_created_at = $_SERVER['REQUEST_TIME'];
        $ordersModel->o_customer_id = $customerModel->c_id; // 客户id
        $ordersModel->o_is_auto_pay = $params['o_is_auto_pay']; // 银行代扣
        if(!$ordersModel->validate()){
            $msg = $ordersModel->getFirstErrors();
            throw new CustomCommonException(reset($msg));
        }
        if(!$ordersModel->save(false)){
            throw new CustomCommonException('订单写入失败');
        }
        // 4写goods表
        if(false === (new \yii\db\Query())->from(Product::tableName())->where(
                [
                    'p_status' => Product::STATUS_OK, 'p_type' => $params['g_goods_type'],
                    'p_id'=>$params['o_product_id']
                ])->exists()){
            throw new CustomApiException('商品和产品类型不匹配');
        }

        if(0 > $params['g_goods_price']){
            throw new CustomApiException('商品价格异常');
        }
        if(0 > $params['g_goods_deposit']){
            throw new CustomApiException('首付金额异常');
        }


        $goodsModel = new Goods();
        $goodsModel->load($data, 'data');
        $goodsModel->g_order_id = $ordersModel->o_id;
        if(false === $goodsModel->validate()){
            $msg = $goodsModel->getFirstErrors();
            throw new CustomCommonException(reset($msg));
        }
        if(false === $goodsModel->save(false)){
            throw new CustomCommonException('商品写入失败');
        }
        return true;
    }

    /*array(54) {
  ["g_goods_type"]=>
  string(1) "6"
  ["g_goods_models"]=>
  string(7) "6s plus"
  ["g_goods_price"]=>
  string(4) "6000"
  ["g_goods_name"]=>
  string(12) "apple6手机"
  ["g_goods_deposit"]=>
  string(3) "600"
  ["c_customer_name"]=>
  string(9) "李连杰"
  ["c_customer_id_card"]=>
  string(4) "5555"
  ["c_customer_cellphone"]=>
  string(11) "18890232122"
  ["c_customer_id_card_endtime"]=>
  string(10) "1111111111"
  ["c_customer_county"]=>
  string(3) "245"
  ["c_customer_city"]=>
  string(3) "343"
  ["c_customer_province"]=>
  string(2) "27"
  ["c_customer_gender"]=>
  string(1) "1"
  ["c_customer_idcard_provider"]=>
  string(18) "中江县公安局"
  ["c_customer_qq"]=>
  string(9) "466594257"
  ["c_customer_wechat"]=>
  string(6) "haytoo"
  ["c_family_marital_status"]=>
  string(1) "1"
  ["c_family_marital_partner_name"]=>
  string(6) "白云"
  ["c_family_marital_partner_cellphone"]=>
  string(11) "15888888888"
  ["c_family_house_info"]=>
  string(1) "1"
  ["c_family_expenses"]=>
  string(4) "1500"
  ["c_family_income"]=>
  string(5) "25000"
  ["c_kinship_name"]=>
  string(9) "张大爷"
  ["c_kinship_relation"]=>
  string(1) "7"
  ["c_kinship_cellphone"]=>
  string(11) "18999999999"
  ["c_kinship_addr"]=>
  string(18) "金牛区酷炫路"
  ["c_customer_addr_province"]=>
  string(2) "12"
  ["c_customer_addr_city"]=>
  string(2) "33"
  ["c_customer_addr_county"]=>
  string(2) "34"
  ["c_customer_addr_detail"]=>
  string(21) "金牛区来聊聊路"
  ["c_customer_jobs_company"]=>
  string(6) "腾讯"
  ["c_customer_jobs_industry"]=>
  string(1) "5"
  ["c_customer_jobs_type"]=>
  string(1) "1"
  ["c_customer_jobs_section"]=>
  string(9) "研发部"
  ["c_customer_jobs_title"]=>
  string(3) "CTO"
  ["c_customer_jobs_is_shebao"]=>
  string(1) "1"
  ["c_customer_jobs_province"]=>
  string(3) "123"
  ["c_customer_jobs_city"]=>
  string(3) "345"
  ["c_customer_jobs_county"]=>
  string(3) "234"
  ["c_customer_jobs_detail_addr"]=>
  string(21) "高新区有点酷路"
  ["c_customer_jobs_phone"]=>
  string(11) "02888888888"
  ["c_other_people_relation"]=>
  string(1) "2"
  ["c_other_people_name"]=>
  string(9) "周杰伦"
  ["c_other_people_cellphone"]=>
  string(11) "15999999999"
  ["c_banknum"]=>
  string(11) "62222222222"
  ["c_bank"]=>
  string(1) "1"
  ["c_banknum_owner"]=>
  string(6) "李逵"
  ["o_is_auto_pay"]=>
  string(1) "1"
  ["o_store_id"]=>
  string(2) "25"
  ["o_remark"]=>
  string(14) "我是sa注释"
  ["o_product_id"]=>
  string(2) "28"
  ["o_user_id"]=>
  string(2) "11"
  ["verify_code"]=>
  string(4) "1234"
  ["c_customer_idcard_detail_addr"]=>
  string(12) "的说法分"
}
*/


}