<?php
/**
 * Created by PhpStorm.
 * User: too
 * Date: 2017/7/7
 * Time: 11:28
 * @author too <hayto@foxmail.com>
 */
use Swoole\WebSocket\Server;
$server = new \Swoole\WebSocket\Server('0.0.0.0', 8585);
$server->set([
    'daemonize'=>0,
    'worker_num'=>2,
    'task_worker_num'     => 18,
//            'task_ipc_mode'=>2
]);
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
        $server->push($fd, $fd.$data);
    }
});

$server->on('finish', function ($serv , $task_id , $data){
    echo "Task {$task_id} finish\n";
    echo "Result: {$data}\n";
});

$server->start();