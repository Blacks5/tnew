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
use WebSocket\Client;
use yii\console\Controller;

class WebsocketController extends Controller
{

    public function actionA()
    {
        $server = new \Swoole\WebSocket\Server('0.0.0.0', 8585);
        $server->set([
            'daemonize'=>0,
            'worker_num'=>1,
            'task_worker_num'     => 1,
//            'task_ipc_mode'=>2
        ]);
        $server->on('workerstart', function (Server $server, $workerid){
            if($workerid >= $server->setting['worker_num']){
                cli_set_process_title('tasker'. $workerid);
            }else{
                cli_set_process_title('worker'. $workerid);
            }
        });
        $server->on('open', function (Server $server,  $request){
            echo "server: handshake success with fd{$request->fd}\n";
//            ob_start();
//            var_dump($server);
//            file_put_contents('/swoole_test.log', ob_get_clean(), FILE_APPEND);
        });
        $server->on('message', function (Server $server,  $frame){
            echo "接收到{$frame->fd}: {$frame->data}, opcode:{$frame->opcode}, fin:{$frame->finish}\n";
            $server->task($frame->data);
//            var_dump($server, $frame);

        });
        $server->on('close', function (Server $server,  $fd){
//            var_dump($server, $fd);
            echo "client {$fd} clesed\n";
        });
        $server->on('task', function (Server $server , $task_id , $from_id , $data){
            foreach ($server->connections as $fd){
                var_dump($fd);
//                $server->push($fd, $fd.$data);
            }
        });

        $server->on('finish', function ($serv , $task_id , $data){
            echo "Task {$task_id} finish\n";
            echo "Result: {$data}\n";
        });

        $server->start();
    }

    public function actionT()
    {
        $cli = new Client("ws://192.168.0.194:8585");
//        $cli->send($a. "-哈哈");
        /*$client = new \Swoole\Http\Client('192.168.0.194', 8585);

        $client->on('message', function ($_cli, $frame){
            var_dump($frame);
        });
        $client->upgrade('/', function ($_cli){
            echo $_cli->body;
        });*/


        /*$a = 0;
        while (1){
            if(($a++ % 10000) == 0){
                sleep(1);
            }
            $cli = new Client("ws://192.168.0.194:8585");
            $cli->send($a. "-哈哈");
//            $cli->receive();

        }*/


    }
}