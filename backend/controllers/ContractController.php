<?php
/**
 * Created by PhpStorm.
 * Date: 2017/2/15
 * Time: 21:05
 * @author 涂鸿 <hayto@foxmail.com>
 */

namespace backend\controllers;

use yii\web\Controller;
class ContractController extends Controller
{
    /**
     * 借款合同
     * @return string
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionIndex()
    {
        return $this->renderPartial('index');
    }

    public function actionPaymentdesc()
    {
        return $this->renderPartial('paymentdesc');
    }
}