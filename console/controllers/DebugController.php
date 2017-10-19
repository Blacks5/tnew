<?php
namespace console\controllers;

use yii;
use yii\console\Controller;
use WebSocket\Client;

class DebugController extends Controller
{

    /**
     * WebSocket 通知测试
     *
     * @return void
     */
    public function actionSocket()
    {
        $client = new Client(\Yii::$app->params['ws']);
        $string = '顾客:王小帅产生了测试';
        $data = [
            'cmd'=>'Orders:newOrderNotify',
            'data'=>[
                'message'=>$string,
                'order_id'=>0
            ]
        ];
        $jsonData = json_encode($data, JSON_UNESCAPED_UNICODE);
        $client->send($jsonData);
    }
}
