<?php
/**
 * Created by PhpStorm.
 * User: too
 * Date: 2016/8/31
 * Time: 14:17
 * @author 涂鸿 <hayto@foxmail.com>
 */

namespace common\models;

use common\components\CustomCommonException;
use common\models\Orders;
use common\models\Product;
use common\models\Repayment;
use yii;

class CalInterest
{
    /**
     * 等额本息法：计算每月还款金额
     * @param $total_money
     * @param $total_months
     * @param $rate_month
     * @return float
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public static function calEveryMonth($total_money, $total_months, $rate_month)
    {
        /*
         *  每月月供额=〔贷款本金×月利率×(1＋月利率)＾还款月数〕÷〔(1＋月利率)＾还款月数-1〕
         */
        $real_rate_month = $rate_month/100;
        // 每月月供【仅包括本金+纯利息，不包含各种增值费用】

        // 传说中的0利息，还本金就行了
        if(0>= $real_rate_month){
            return round($total_money/$total_months, 1);
//            throw new CustomCommonException('月利率不能为0');
        }

        $monthly_payment = ($total_money * $real_rate_month * pow(1 + $real_rate_month, $total_months)) / (pow(1 + $real_rate_month, $total_months) - 1);
        return $monthly_payment;

    }

    /**
     * 计算月供【包含了各种管理费之后】
     * @param $total_money
     * @param $product_id
     * @param $p_add_service_fee
     * @param $p_free_pack_fee
     * @return float|mixed
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public static function calRepayment($total_money, $product_id, $p_add_service_fee, $p_free_pack_fee)
    {
        if (!$total_money || !$product_id) {
            throw new CustomApiException('请求错误');
        }
        $select = ['p_period', 'p_month_rate', 'p_add_service_fee', 'p_free_pack_fee', 'p_finance_mangemant_fee', 'p_customer_management'];
        if (!$data = Product::find()->select($select)->where(['p_id' => $product_id])->asArray()->one()) {
            throw new CustomApiException('请求错误');
        }
        $every_month_repay = CalInterest::calEveryMonth($total_money, $data['p_period'], $data['p_month_rate']);
        // 个人保障计划
        if ($p_add_service_fee == 1) {
            $every_month_repay += round($total_money * $data['p_add_service_fee']/100, 4);
        }
        // 贵宾服务包
        if ($p_free_pack_fee == 1) {
            $every_month_repay += $data['p_free_pack_fee'];
        }
        // 财务管理费
        $p_finance_mangemant_fee = round($total_money * $data['p_finance_mangemant_fee']/100, 4);
        // 客户管理费
        $p_customer_management = round($total_money * $data['p_customer_management']/100, 4);
        $every_month_repay += $p_finance_mangemant_fee + $p_customer_management;
        return $every_month_repay;
    }

    /**
     * 二审（放款）+生成还款计划
     * 加了for update ，但是没有开启事务，需要外部调用者开启事务
     * @param $order_id
     * @return bool
     * @throws CustomCommonException
     * @throws yii\base\Exception
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public static function genRefundPlan($order_id)
    {
        /*
         * 读product表，获取月利率和借款月数，以及各种附加费用
         * 读orders表，获取总的借款金额
         * 修改orders表的状态，总利息，审核人id，审核人真实姓名，审核时间  插入repayment表
         * */
        $userinfo = Yii::$app->getUser()->getIdentity();
        $order_info = Orders::findBySql("select * from orders left join product on o_product_id=p_id where o_id=:o_id limit 1 for update", [':o_id' => $order_id])->asArray()->one();
        $columns = [
            'r_customer_id', 'r_orders_id', 'r_total_repay', 'r_interest', 'r_principal', 'r_balance', 'r_add_service_fee', 'r_free_pack_fee', 'r_finance_mangemant_fee', 'r_customer_management',
            'r_pre_repay_date', 'r_is_last', 'r_serial_no', 'r_serial_total', 'r_operator_id', 'r_operator_date'
        ];
        $total_borrow_money = $order_info['o_total_price'] - $order_info['o_total_deposit']; // 一共借了多少钱

        $month_benjinTotal = self::calEveryMonth($total_borrow_money, $order_info['p_period'], $order_info['p_month_rate']); //每月还款金额（每月应还本金+每月应还利息）
        $Total_interest = 0; //总利息

        $p_add_service_fee = 0;
        $p_free_pack_fee = 0;
        // 个人保障计划 百分比
        if ((int)$order_info['o_is_add_service_fee'] === 1) {
            $p_add_service_fee = round($total_borrow_money * $order_info['p_add_service_fee'] / 100, 4); // 弄大点给下面再舍弃
        }
        // 计算贵宾服务包
        if ((int)$order_info['o_is_free_pack_fee'] === 1) {
            $p_free_pack_fee = $order_info['p_free_pack_fee'];
        }
        // 财务管理费
        $p_finance_mangemant_fee = round($total_borrow_money * $order_info['p_finance_mangemant_fee']/100, 4);
        // 客户管理费
        $p_customer_management = round($total_borrow_money * $order_info['p_customer_management']/100, 4);


        for ($i = 0; $i < $order_info['p_period']; $i++) {
            $_temp['r_customer_id'] = $order_info['o_customer_id']; // 客户id
            $_temp['r_orders_id'] = $order_info['o_id']; // 订单id


            $_temp['r_total_repay'] = round($month_benjinTotal + $p_add_service_fee + $p_free_pack_fee + $p_finance_mangemant_fee + $p_customer_management, 4); // 每月需还款的总额

            $_temp['r_interest'] = round($total_borrow_money * $order_info['p_month_rate'] / 100, 4); // 每月利息
            $_temp['r_principal'] = round($month_benjinTotal - $_temp['r_interest'], 4); // 每月还的本金
            $_temp['r_balance'] = abs(round($total_borrow_money - $_temp['r_principal'], 4)); // 期末余额，误差会导致负数所以abs
            $_temp['r_add_service_fee'] = $p_add_service_fee; // 个人保证计划
            $_temp['r_free_pack_fee'] = $p_free_pack_fee;// 贵宾服务包
            $_temp['r_finance_mangemant_fee'] = $p_finance_mangemant_fee; // 财务管理费
            $_temp['r_customer_management'] = $p_customer_management; // 客户管理费
            $_temp['r_pre_repay_date'] = strtotime('+' . $i + 1 . 'months'); // 下个月的明天
            $_temp['r_is_last'] = ($i + 1 == $order_info['p_period']) ? 1 : 2; // 1是 2不是最后一期
            $_temp['r_serial_no'] = $i + 1;
            $_temp['r_serial_total'] = $order_info['p_period'];
            $_temp['r_operator_id'] = $userinfo->id;
            $_temp['r_operator_date'] = $_SERVER['REQUEST_TIME'];
            $data[] = $_temp;

            $total_borrow_money -= $_temp['r_principal']; // 借款本金依次减少
            $Total_interest = $Total_interest + $_temp['r_interest']; // 总产生利息
        }
        try {
//            echo Yii::$app->db->createCommand()->batchInsert(Repayment::tableName(), $columns, $data)->getRawSql();die;
            // 插入的条数与期数不同就回滚
            if (Yii::$app->db->createCommand()->batchInsert(Repayment::tableName(), $columns, $data)->execute() != $order_info['p_period']) {
                throw new CustomCommonException('还款计划生成错误，请重试', 5);
            }
            return true;
        } catch (CustomCommonException $e) {
            throw $e;
        } catch (yii\base\Exception $e) {
            throw $e;
        }
    }
}