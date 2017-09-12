<?php

namespace backend\controllers;
use backend\core\CoreBackendController;
use backend\models\Log;
use backend\models\Menu;
use backend\models\PasswordForm;
use common\models\Customer;
use common\models\Orders;
use yii\data\Pagination;

use Yii;
use yii\db\Expression;
use yii\db\Query;

//class IndexController extends \yii\web\Controller
class IndexController extends CoreBackendController
{
    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        return $this->render('index');
    }


    public function actionWelcome()
    {
        //最近登录记录
        $log = Log::find()->limit(Yii::$app->params['page_size'])->orderBy('id desc')->asArray()->all();
        $customer = $this->getCustomers();
        $order = $this->getOrdres();

        $total_borrow_money = number_format($this->getTotalMoney());

        return $this->render('welcome',[
            'log' => $log,
            'customer'=>$customer,
            'order'=>$order,
            'total_borrow_money'=>$total_borrow_money
        ]);
    }


    /**
     * 总共借出去的金额
     * @return float
     * @author too <hayto@foxmail.com>
     */
    private function getTotalMoney()
    {
        // select (sum(o_total_price)-sum(o_total_deposit))as total from orders where o_status=10
        $total_money = (new Query())->select(['total_money'=>new Expression('sum(o_total_price)-sum(o_total_deposit)')])->from(Orders::tableName())
            ->where(['in', 'o_status',[Orders::STATUS_PAYING,Orders::STATUS_PAY_OVER]])->scalar();
        return round($total_money, 2);
    }
    /**
     * 获取客户相关
     * @author 涂鸿 <hayto@foxmail.com>
     */
    private function getCustomers()
    {
        // 今日新增客户数
        $today_s = strtotime(date('Y-m-d 00:00:00'));
        $today_e = strtotime(date('Y-m-d 23:59:59'));
        $todayNewlCustomer = Customer::find()
            ->where(['c_status'=>Customer::STATUS_OK])
            ->andWhere(['between', 'c_created_at', $today_s, $today_e])
            ->count();

        // 总客户数
        $totalCustomer = Customer::find()->where(['c_status'=>Customer::STATUS_OK])->count();
        return [
            'newCustomer'=>$todayNewlCustomer,
            'totalCustomer'=>$totalCustomer,
            'incrRate'=>round($todayNewlCustomer/$totalCustomer*100, 2)
        ];
    }

    private function getOrdres()
    {
        // 今日新增客户数
        $today_s = strtotime(date('Y-m-d 00:00:00'));
        $today_e = strtotime(date('Y-m-d 23:59:59'));
        $where = [Orders::STATUS_PAYING, Orders::STATUS_NOT_COMPLETE, Orders::STATUS_PAY_OVER, Orders::STATUS_WAIT_CHECK,Orders::STATUS_WAIT_CHECK_AGAIN];
        $todayNewlCustomer = Orders::find()
            ->where(['o_status'=>$where])
            ->andWhere(['between', 'o_created_at', $today_s, $today_e])
            ->count();

        // 总客户数
        $totalCustomer = Orders::find()->where(['o_status'=>$where])->count();
        return [
            'newOrder'=>$todayNewlCustomer,
            'totalOrder'=>$totalCustomer,
            'incrRate'=>round($todayNewlCustomer/$totalCustomer*100, 2)
        ];
    }
}

