<?php
/**
 * Created by PhpStorm.
 * User: too
 * Date: 2016/10/20
 * Time: 14:31
 * @author 涂鸿 <hayto@foxmail.com>
 */
namespace backend\controllers;

use WebSocket\Client;
use yii;
use backend\core\CoreBackendController;
use backend\services\YijifuDebugger;
use common\tools\yijifu\Sign;

class ToolsController extends CoreBackendController
{
    public function actionYijifuTest($customer, $amount)
    {
        $debugger = new YijifuDebugger();
        $r = $debugger->sign($customer, $amount);
    }

    public function actionYijifuDeduct(string $order,int $amount)
    {
        $debugger = new YijifuDebugger();
        $r = $debugger->deduct($order, $amount);
    }

    public function actionYijifuQuerySigned($order)
    {
        $sign = new Sign();
        $r = $sign->querySignedUser($order);
        var_dump($r);
    }

    public function actionYijifunotify()
    {
        $data = [
            'post' => $_POST,
            'get'  => $_GET,
        ];
        $data = date('Y-m-d H:i:s --') . json_encode($data) . PHP_EOL;
        file_put_contents(Yii::$app->runtimePath . '/yijifu-debug.txt', $data, FILE_APPEND);
        echo 'success';
    }
}


