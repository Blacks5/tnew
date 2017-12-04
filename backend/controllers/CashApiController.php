<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2017/11/17
 * Time: 16:48
 */

namespace backend\controllers;


use backend\core\CoreBackendController;

class CashApiController extends CoreBackendController
{
    public function actionContract()
    {
        return $this->render('contract');
    }

    public function actionSign()
    {
        return $this->render('pay', ['url' => 'deduct-signs']);
    }

    public function actionLoan()
    {
        return $this->render('pay', ['url' => 'loans']);
    }

    public function actionDeduct()
    {
        return $this->render('pay', ['url' => 'deducts']);
    }

    public function actionInfo($id, $url)
    {
        return $this->render('info', ['id' => $id, 'url' => $url]);
    }
}