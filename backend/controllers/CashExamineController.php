<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2017/11/3
 * Time: 9:32
 */

namespace backend\controllers;


use backend\core\CoreBackendController;

class CashExamineController extends CoreBackendController
{
    public function actionWait()
    {
        $examine = 'wait';
        return $this->render('wait',['examine' => $examine]);
    }

    public function actionRevoke()
    {
        $examine = 'revoke';
        return $this->render('wait',['examine' => $examine]);
    }

    public function actionPass()
    {
        $examine = 'pass';
        return $this->render('wait', ['examine' => $examine]);
    }

    public function actionCancel()
    {
        $examine = 'cancel';
        return $this->render('wait', ['examine' => $examine]);
    }

    public function actionInfo($id)
    {
        return $this->render('info', ['id' => $id]);
    }

    public function actionImages($orderID)
    {
        return $this->render('images', ['orderID' => $orderID]);
    }
}