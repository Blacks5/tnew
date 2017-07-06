<?php
/**
 * Created by PhpStorm.
 * User: too
 * Date: 2017/7/6
 * Time: 19:32
 * @author too <hayto@foxmail.com>
 */

?>

<script>
    var ws = new WebSocket("ws://192.168.1.8:8585");
    ws.onopen = function (event) {
        console.log('连接上');
        ws.send("哈喽");
        console.log(event);

        ws.onmessage = function (event) {
            console.log('收到消息');
            console.log(event);
        }
    }

    ws.onmessage = function (event) {
        console.log('收到消息');
        console.log(event);
    }


</script>
