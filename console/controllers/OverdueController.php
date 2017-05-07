<?php
/**
 * Created by PhpStorm.
 * User: too
 * Date: 2016/9/29
 * Time: 10:14
 * @author 涂鸿 <hayto@foxmail.com>
 */

namespace console\controllers;

use common\models\Repayment;
use yii;
use yii\console\Controller;


/*
 * 所有 应还款时间 大于 当前时间 的 还款列表 取出来
 * 加行锁，计算逾期天数和逾期金额，更改后释放锁
 *
 * */


/**
 * 每天0点，处理已经逾期的还款，并计算违约金
 * 用法 php56 ./yii overdue/work
 * Class OverdueController
 * @package app\commands
 * @author 涂鸿 <hayto@foxmail.com>
 */
class OverdueController extends Controller
{
    /**
     * 获取所有已逾期的还款计划
     * @return array
     * @author 涂鸿 <hayto@foxmail.com>
     */
    private function getOverdueList()
    {
        $data = (new yii\db\Query())->from(Repayment::tableName())
            ->select(['r_overdue_day'])
            ->where(['<', 'r_pre_repay_date', $_SERVER['REQUEST_TIME']])->all();
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
        $result = [
            'r_overdue_day'=>0,
            'r_overdue_money'=>0
        ];
        foreach ($data as $v){
            $result['r_overdue_day'] = $data['r_overdue_day'] + 1;
            $result['r_overdue_money'] = $result['r_overdue_day'] * 3;
        }
        return $result;
    }

    /**
     * 每天违约金3块钱
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionWork()
    {
        $price = Yii::$app->params['overdue_money_everyday'];
        $sql = "update ". Repayment::tableName()." set r_overdue_day=r_overdue_day+1,r_overdue_money=r_overdue_day*". $price." where r_pre_repay_date > ". $_SERVER['REQUEST_TIME'];
        $num = Yii::$app->db->createCommand($sql)->execute();
        var_dump($num);
    }
}