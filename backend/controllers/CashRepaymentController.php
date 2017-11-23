<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2017/11/16
 * Time: 17:03
 */

namespace backend\controllers;


use backend\core\CoreBackendController;

class CashRepaymentController extends CoreBackendController
{
    public function actionWait()
    {
        return $this->render('index', ['repayment' => 'wait']);
    }

    public function actionOverdue()
    {
        return $this->render('index', ['repayment' => 'overdue']);
    }

    public function actionPaid()
    {
        return $this->render('index', ['repayment' => 'paid']);
    }

    public function actionPaidOff()
    {
        return $this->render('index', ['repayment' => 'paidOff']);
    }

    public function actionLists($orderID)
    {
        return $this->render('lists', ['orderID' => $orderID]);
    }
}