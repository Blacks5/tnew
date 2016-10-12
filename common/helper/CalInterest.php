<?php
/**
 * Created by PhpStorm.
 * User: too
 * Date: 2016/8/31
 * Time: 14:17
 * @author 涂鸿 <hayto@foxmail.com>
 */

namespace common\helper;

use app\models\Orders;
use app\models\Product;
use app\models\Repayment;
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
        return $month_benjinTotal = $total_money * $rate_month * pow(1 + $rate_month, $total_months) / (pow(1 + $rate_month, $total_months) - 1); //每月还款金额
    }

    public static function genRefundPlan($order_id)
    {
        /*
         * 读product表，获取月利率和借款月数，以及各种附加费用
         * 读orders表，获取总的借款金额
         * 修改orders表的状态，总利息，审核人id，审核人真实姓名，审核时间  插入repayment表
         * */
        $userinfo = Yii::$app->getUser()->getIdentity();
        $order_info = Orders::find()->select(['*'])->leftJoin(Product::tableName(), 'o_product_id=p_id')->where(['o_id' => $order_id])->asArray()->one();
        $columns = [
            'r_customer_id', 'r_orders_id', 'r_total_repay', 'r_interest', 'r_principal', 'r_add_service_fee', 'r_free_pack_fee', 'r_finance_mangemant_fee', 'r_customer_management',
            'r_pre_repay_date', 'r_is_last', 'r_serial_no', 'r_operator_id', 'r_operator_date'
        ];
        $total_borrow_money = $order_info['o_total_price'] - $order_info['o_total_deposit']; // 一共借了多少钱
        $month_benjinTotal = self::calEveryMonth($total_borrow_money, $order_info['p_period'], $order_info['p_month_rate']); //每月还款金额（本金+利息）
        $Total_interest = 0; //总利息

        // 是否有随心包和增值服务费
        $p_add_service_fee = 0;
        $p_free_pack_fee = 0;
        if ((int)$order_info['o_is_add_service_fee'] === 1) {
            $p_add_service_fee = $order_info['p_add_service_fee'];
        }
        if ((int)$order_info['o_is_free_pack_fee'] === 1) {
            $p_free_pack_fee = $order_info['p_free_pack_fee'];
        }


        for ($i = 0; $i < $order_info['p_period']; $i++) {
            $_temp['r_customer_id'] = $order_info['o_customer_id']; // 客户id
            $_temp['r_orders_id'] = $order_info['o_id']; // 订单id
            $_temp['r_total_repay'] = $month_benjinTotal + $p_add_service_fee + $p_free_pack_fee + $order_info['p_finance_mangemant_fee'] + $order_info['p_customer_management']; // 每月总额
            $_temp['r_interest'] = round($total_borrow_money * $order_info['p_month_rate'], 3); // 每月利息
            $_temp['r_principal'] = round($month_benjinTotal - $_temp['r_interest'], 3); // 每月本金
            $_temp['r_add_service_fee'] = $p_add_service_fee; // 增值服务费
            $_temp['r_free_pack_fee'] = $p_free_pack_fee;// 随心包服务费
            $_temp['r_finance_mangemant_fee'] = $order_info['p_finance_mangemant_fee']; // 财务管理费
            $_temp['r_customer_management'] = $order_info['p_customer_management']; // 客户管理费
            $_temp['r_pre_repay_date'] = strtotime('+' . $i + 1 . 'months'); // 下个月的明天
            $_temp['r_is_last'] = ($i + 1 == $order_info['p_period']) ? 1 : 2; // 1是 2不是最后一期
            $_temp['r_serial_no'] = $i + 1;
            $_temp['r_operator_id'] = $userinfo->id;
            $_temp['r_operator_date'] = $_SERVER['REQUEST_TIME'];
            $data[] = $_temp;

            $total_borrow_money -= $_temp['r_principal']; // 借款本金依次减少
            $Total_interest = $Total_interest + $_temp['r_interest']; // 总产生利息
        }
//        p($data);
        $trans = Yii::$app->db->beginTransaction();
        try {
            // 查询是否是待审核状态
            $order = Orders::findBySql('select * from orders where o_id=:order_id limit 1 for update', [':order_id' => $order_id])->asArray()->one();
            if ($order['o_status'] !== '0') {
                throw new CustomException('订单已放款');
            }
            // 插入的条数与期数不同就回滚
            if (Yii::$app->db->createCommand()->batchInsert(Repayment::tableName(), $columns, $data)->execute() != $order_info['p_period']) {
                throw new CustomException('还款计划生成错误，请重试');
            }
            $attrs = [
                'o_operator_id' => $userinfo->id, 'o_operator_realname' => $userinfo->realname, 'o_operator_date' => $_SERVER['REQUEST_TIME'],
                'o_total_interest' => $Total_interest, 'o_status' => Orders::STATUS_PAYING
            ];
            if (Orders::updateAll($attrs, ['o_id' => $order_id]) <= 0) {
                throw new CustomException('订单状态修改失败，请重试');
            }
            $trans->commit();
            return true;
        } catch (CustomException $e) {
            $trans->rollBack();
            throw $e;
        } catch (yii\base\Exception $e) {
            $trans->rollBack();
            throw $e;
        }
    }
}