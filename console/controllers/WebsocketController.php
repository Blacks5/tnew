<?php
/**
 * Created by PhpStorm.
 * User: too
 * Date: 2017/7/6
 * Time: 19:10
 * @author too <hayto@foxmail.com>
 */

namespace console\controllers;


use Swoole\WebSocket\Server;
use yii\console\Controller;

class WebsocketController extends Controller
{
    public function actionA()
    {
        $client = [];
        $server = new \Swoole\WebSocket\Server('0.0.0.0', 8585);
        $server->set([
            'daemonize'=>0
        ]);
        $server->on('open', function (Server $server,  $request)use ($client){
            echo "server: handshake success with fd{$request->fd}\n";
            array_push($client, $request->fd);
        });
        $server->on('message', function (Server $server,  $frame)use ($client){
            echo "接收到{$frame->fd}: {$frame->data}, opcode:{$frame->opcode}, fin:{$frame->finish}\n";
            foreach ($server->connections as $fd){
                var_dump($fd);
                $server->push($fd, $fd.'歪，妖妖灵吗');
            }
        });
        $server->on('close', function (Server $server,  $fd){
            echo "client {$fd} clesed\n";
        });

        $server->start();
    }
}