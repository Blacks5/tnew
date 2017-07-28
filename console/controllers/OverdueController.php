<?php
/**
 * Created by PhpStorm.
 * User: too
 * Date: 2016/9/29
 * Time: 10:14
 * @author 涂鸿 <hayto@foxmail.com>
 */

namespace console\controllers;

use common\components\CustomCommonException;
use common\models\Repayment;
use yii;
use yii\console\Controller;


/*
 * 所有 应还款时间 大于 当前时间 的 还款列表 取出来
 * 加行锁，计算逾期天数和逾期金额，更改后释放锁
 *
 * */


/**
 * 每天0点5分开始，处理已经逾期的还款，并计算违约金
 * 用法 php /mnt/wcb_latest/yii overdue/work
 * Class OverdueController
 * @package app\commands
 * @author 涂鸿 <hayto@foxmail.com>
 */
class OverdueController extends Controller
{
    /**
     * 获取 所有已逾期 该处理(90天内) 的还款计划
     * @return array
     * @author 涂鸿 <hayto@foxmail.com>
     */
    private function getOverdueList()
    {
        $_time = strtotime(date('Y-m-d')); // 当天零点整的时间戳
//        $_time = strtotime(date('Y-m-d', strtotime('-3 days'))); // 过期3天内都不计算逾期
        $sql = "select * from repayment where r_pre_repay_date<=". $_time. " and r_status=". Repayment::STATUS_NOT_PAY. " and r_overdue_day < 90 order by r_pre_repay_date desc for update";
        $data = Yii::$app->getDb()->createCommand($sql)->queryAll();
        if(false === !empty($data)){
            throw new CustomCommonException('没有需要计算的逾期客户');
        }
        return $data;
    }

    /**
     * 计算逾期天数和逾期金额
     * @param $data
     * @return array
     * @author 涂鸿 <hayto@foxmail.com>
     */
    private function calOverdueData($data)
    {
        $result = [];
        foreach ($data as $v){
            $_t1 = (new \DateTime())->setTimestamp(strtotime(date('Y-m-d', $v['r_pre_repay_date']))); // 还款日的0点0时
            $r_overdue_day = $_t1->diff((new \DateTime()))->days; // 逾期天数
            // 小于等于3天的，都不计算逾期滞纳金
            if($r_overdue_day <= 3){
                $r_overdue_money = 0;
            }else{
                $r_overdue_money = round($v['r_total_repay'] * $r_overdue_day / 100, 2); // 滞纳金 = 月供* 逾期天数%
            }

            $result[$v['r_id']] = [
                'r_overdue_day'=>$r_overdue_day,
                'r_overdue_money'=>$r_overdue_money
            ];
        }
        return $result;
    }

    /**
     * @param $data
     * @return int 修改的还款计划行数
     * @throws CustomCommonException
     * @author too <hayto@foxmail.com>
     */
    private function updateRepayment($data)
    {
        $num = count($data); // 一共多少个用户
        $r_ids = implode(',', array_keys($data));

        $sql = "update repayment set r_overdue_day = case r_id";
        foreach ($data as $k=>$v){
            $sql .= " when ". $k. " then ". $v['r_overdue_day'];
        }
        $sql .= " end, r_overdue_money= case r_id";
        foreach ($data as $k=>$v){
            $sql .= " when ". $k. " then ". $v['r_overdue_money'];
        }
        $sql .= " end where r_id in (". $r_ids. ")";

        /*$int = */Yii::$app->getDb()->createCommand($sql)->execute();
        // 防止一天内多次运行时，$num和$int不相等
        /*if($num !== $int){
            throw new CustomCommonException("更新失败:用户 $num 人，只修改 $int 人");
        }*/
        return $num;
    }

    /**
     *
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionWork()
    {
        $error_log = date('Y-m-d H:i:s'). "任务日志\r\n";
        $log_file_path = Yii::$app->basePath. DIRECTORY_SEPARATOR. 'logs'. DIRECTORY_SEPARATOR. 'overdue_operating_record.csv';

        $trans = Yii::$app->getDb()->beginTransaction();
        try{
            $data = $this->getOverdueList();
            $result = $this->calOverdueData($data);
            $this->updateRepayment($result);
            $trans->commit();
            $error_log .= "operating success\r\n";
        }catch (CustomCommonException $e){
            $trans->rollBack();
            $error_log .= $e->getMessage()."\r\n";
        }catch (\Exception $e){
            $trans->rollBack();
            $error_log .= $e->getMessage()."\r\n";
        }finally{
            file_put_contents($log_file_path, $error_log, FILE_APPEND);
        }
    }
}