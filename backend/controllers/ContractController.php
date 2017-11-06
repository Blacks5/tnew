<?php
/**
 * Created by PhpStorm.
 * Date: 2017/2/15
 * Time: 21:05
 * @author 涂鸿 <hayto@foxmail.com>
 */

namespace backend\controllers;
use common\models\CalInterest;
use common\models\User;
use kartik\mpdf\Pdf;
use yii;
use yii\web\Controller;
use common\models\Orders;
use common\models\Customer;
use common\models\Product;
use common\models\Stores;
use common\models\Goods;
use common\components\Helper;
class ContractController extends Controller
{
    /**
     * 借款合同
     * @return string
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionIndex()
    {
        $o_id = \Yii::$app->getRequest()->get('o_id');
        $data = Orders::find()->select('*')
            ->leftJoin(Customer::tableName(), 'c_id=o_customer_id')
            ->leftJoin(Product::tableName(), 'o_product_id=p_id')
            ->leftJoin(Stores::tableName(), 's_id=o_store_id')
            ->where('o_id=:o_id', [':o_id'=>$o_id])
            ->asArray()->one();
        $data['data_goods'] = Goods::find()->where(['g_order_id'=>$o_id])->asArray()->all();

        $total_borrow_money = $data['o_total_price']-$data['o_total_deposit'];
        $id_address = Helper::getAddrName($data['c_customer_province']). Helper::getAddrName($data['c_customer_city']). Helper::getAddrName($data['c_customer_county']). $data['c_customer_idcard_detail_addr'];
        $data['c_bank'] = Yii::$app->params['bank_list'][$data['c_bank']-1]['bank_name'];
        $data['c_customer_gender'] = Customer::getAllGender()[$data['c_customer_gender']];
//        var_dump(Yii::$app->params['marital_status'][$data['c_family_marital_status']-1]['marital_str']);die;
        $data['c_family_marital_status'] = Yii::$app->params['marital_status'][$data['c_family_marital_status']-1]['marital_str'];
//        var_dump(Yii::$app->params['kinship'][$data['c_kinship_relation']-1]['kinship_str']);die;
        $data['c_kinship_relation'] = Yii::$app->params['kinship'][$data['c_kinship_relation']-1]['kinship_str'];
        $now_address = Helper::getAddrName($data['c_customer_addr_province']). Helper::getAddrName($data['c_customer_addr_city']). Helper::getAddrName($data['c_customer_addr_county']). $data['c_customer_idcard_detail_addr'];
        $job_address = Helper::getAddrName($data['c_customer_jobs_province']). Helper::getAddrName($data['c_customer_jobs_city']). Helper::getAddrName($data['c_customer_jobs_county']). $data['c_customer_jobs_detail_addr'];

        $data['o_is_auto_pay'] = $data['o_is_auto_pay'] ==1?'是':'否';
        $data['c_customer_jobs_is_shebao'] = $data['c_customer_jobs_is_shebao'] ==1?'是':'否';
        $data['c_family_house_info'] = Yii::$app->params['house_info'][$data['c_family_house_info']-1]['house_info_str'];


        $data['c_customer_jobs_industry'] = Yii::$app->params['company_kind'][$data['c_customer_jobs_industry']-1]['company_kind_name'];

        $data['c_customer_jobs_type'] = Yii::$app->params['company_type'][$data['c_customer_jobs_type']-1]['company_type_name'];
        $data['o_user_id'] = User::find()->select('realname')->where(['id'=>$data['o_user_id']])->scalar();

        // 计算月供
        $all_total = $this->getFee($data);
        $total_money = $all_total['allTotal'];
        $data['o_inquiry_fee'] = $all_total['inquiryFee'];
        $data['o_service_fee'] = $all_total['service'];
        $every_month_repay = CalInterest::calEveryMonth($total_money, $data['p_period'], $data['p_month_rate']);
        //月供总额
        $data['total_all'] = $total_money;
        // 个人保障计划
        if ($data['o_is_add_service_fee'] == 1) {
            $every_month_repay += round($total_money * $data['p_add_service_fee']/100, 4);
        }
        // 贵宾服务包
        if ($data['o_is_free_pack_fee'] == 1) {
            $every_month_repay += $data['p_free_pack_fee'];
        }
        // 财务管理费
        $p_finance_mangemant_fee = round($total_money * $data['p_finance_mangemant_fee']/100, 4);
        // 客户管理费
        $p_customer_management = round($total_money * $data['p_customer_management']/100, 4);
        $every_month_repay += $p_finance_mangemant_fee + $p_customer_management;
        $data['first_repay_time'] = strtotime(date('Y-m-d H:i:s', $data['o_created_at']) . ' +1 month');


        $data['o_is_add_service_fee'] = $data['o_is_add_service_fee'] ==1?'是':'否';
        $data['o_is_free_pack_fee'] = $data['o_is_free_pack_fee'] ==1?'是':'否';

        // 处理商品信息
        foreach ($data['data_goods'] as $k=>&$v){
            $v['g_goods_type'] = Yii::$app->params['goods_type'][$v['g_goods_type']-1]['t_name'];
        }


        $html = $this->renderPartial('index',
            ['data'=>$data, 'now_address'=>$now_address,
            'total_borrow_money'=>$total_borrow_money,
            'id_address'=>$id_address, 'job_address'=>$job_address, 'every_month_repay'=>$every_month_repay]);

        if(isset($_GET['pdf'])){
//            Yii::$app->getResponse()->setDownloadHeaders('a.pdf', 'application/pdf')->send();
//            echo $html;
//            return $html;
//            $pdf = new Pdf();
//            $pdf->render();
            $pdf = new \mPDF('zh-CN');
            $pdf->WriteHTML($html);
            $pdf->SetDisplayMode('fullpage');
            $pdf->Output('贷款合同.pdf', 'D');
        }
        return $html;
    }

    public function actionPaymentdesc()
    {
        return $this->renderPartial('paymentdesc');
    }

    /**
     * 获取贷款总金额 (商品总金额 - 预付金额 + 查询服务费 + 商家服务费)
     * @param $orderInfo
     * @return int
     */
    public function getFee($orderInfo)
    {
        $service = 0; //返还给商家的服务费
        $total = $orderInfo['o_total_price'] - $orderInfo['o_total_deposit'];  //借出去的本金

        //常规商品返还给商家的费用
        if($orderInfo['p_is_promotional']==0){
//            if($orderInfo['p_period']>11 && $orderInfo['p_period'] < 15){
//                $service = $total * 0.01;
//            }elseif($orderInfo['p_period'] >= 15){
//                $service = $total * 0.035;
//            }
            $service = $total * 0.035;
        }elseif($orderInfo['p_is_promotional']==1){  //促销商品返回给商家的费用
//            if($orderInfo['p_period'] > 11 && $orderInfo['p_period'] < 15){
//                $service = $total * 0.03;
//            }elseif ($orderInfo['p_period'] >= 15){      
//                $service = $total *0.05;
//            }
            $service = $total * 0.02;
        }


        $allTotal = $total + $service + Yii::$app->params['inquiryFee'];
        $this->updateOrders($orderInfo, $service);
        return [
            'inquiryFee' => Yii::$app->params['inquiryFee'],
            'service' => $service,
            'allTotal' => $allTotal,
        ];
    }

    /**
     * 修改Orders 服务费和查询费
     * @param $orderInfo
     * @param $service
     * @return bool
     */
    protected function updateOrders($orderInfo, $service)
    {
        $order = Orders::find()->where(['o_id'=>$orderInfo['o_id']])->one();
        $order->o_service_fee = $service;
        $order->o_inquiry_fee = Yii::$app->params['inquiryFee'];
        if($order->save()){
            return true;
        }else{
            throw new CustomBackendException('修改服务费和查询费失败', 5);
        }
    }
}