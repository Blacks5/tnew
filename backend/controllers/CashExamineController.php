<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2017/11/3
 * Time: 9:32
 */

namespace backend\controllers;


use backend\core\CoreBackendController;
use common\models\User;

class CashExamineController extends CoreBackendController
{

    public function actionWait()
    {
        $examine = 'wait';
        $user = new User();
        $area = $user->getUserArea();
        return $this->render('wait',['examine' => $examine, 'user' => $area]);
    }

    public function actionFirst()
    {
        $examine = 'first';
        $user = new User();
        $area = $user->getUserArea();
        return $this->render('wait', ['examine' => $examine, 'user' => $area]);
    }

    public function actionRevoke()
    {
        $examine = 'revoke';
        $user = new User();
        $area = $user->getUserArea();
        return $this->render('wait',['examine' => $examine, 'user' => $area]);
    }

    public function actionPass($id = null, $customer_id = null)
    {
        $examine = 'pass';
        $user = new User();
        $area = $user->getUserArea();
        return $this->render('wait', ['examine' => $examine, 'id' => $id, 'user' => $area, 'customer_id' => $customer_id]);
    }

    public function actionCancel()
    {
        $examine = 'cancel';
        $user = new User();
        $area = $user->getUserArea();
        return $this->render('wait', ['examine' => $examine, 'user' => $area]);
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