<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2017/11/16
 * Time: 17:03
 */

namespace backend\controllers;


use backend\core\CoreBackendController;
use common\models\User;

class CashRepaymentController extends CoreBackendController
{
    public function actionWait()
    {
        $user = new User();
        $area = $user->getUserArea();
        return $this->render('index', ['repayment' => 'wait', 'user' => $area]);
    }

    public function actionOverdue()
    {
        $user = new User();
        $area = $user->getUserArea();
        return $this->render('index', ['repayment' => 'overdue', 'user' => $area]);
    }

    public function actionPaid()
    {
        $user = new User();
        $area = $user->getUserArea();
        return $this->render('index', ['repayment' => 'paid', 'user' => $area]);
    }

    public function actionPaidOff()
    {
        $user = new User();
        $area = $user->getUserArea();
        return $this->render('index', ['repayment' => 'paidOff', 'user' => $area]);
    }

    public function actionLists($orderID)
    {
        return $this->render('lists', ['orderID' => $orderID]);
    }
}