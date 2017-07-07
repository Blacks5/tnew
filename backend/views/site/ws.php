<?php
/**
 * Created by PhpStorm.
 * User: too
 * Date: 2017/7/6
 * Time: 19:32
 * @author too <hayto@foxmail.com>
 */

?>
<?= \yii\bootstrap\Html::jsFile('@web/js/jquery.min.js') ?>
<script>

    $(function () {
        notify.init();
    })

    var config = {
        "server": "ws://119.23.15.90:8081"
    };


    var notify = {
        data: {
            server: null
        },
        init: function () {
            this.data.server = new WebSocket(config.server);
            this.open();
            this.message();
        },
        open: function () {
            this.data.server.onopen = function (event) {
                console.log("连接上了");
                console.log(event);
            }
        },
        message: function () {
            this.data.server.onmessage = function (event) {
                console.log("收到消息");
                console.log(event.data);
            }
        },
        close: function () {
            this.data.server.onclose = function (event) {
                console.log("服雾器消失了");
                console.log(event);
            }
        },
        error: function () {
            this.data.server.onerror = function (event) {
                console.log("莫名其妙的出错了");
                console.log(event);
            }
        }
    };



</script>
