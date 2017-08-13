<?php
/**
 * Created by PhpStorm.
 * User: too
 * Date: 2017/8/13
 * Time: 19:14
 * @author too <hayto@foxmail.com>
 */

namespace wechat\controllers;


use common\services\Order;

class OrderController
{
    public function actionCreateOrder()
    {
        $request = \Yii::$app->getRequest();
        $params = $request->post();
        $orderServer = new Order();
        $orderServer->addOrder($params);
    }
}