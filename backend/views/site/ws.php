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
       // notify.heartbeat();
    });


    var config = {
        "server": "ws://119.23.15.90:8081"
    };

    var dataSend = {
        "controller_name":"AppController",
        "method_name":"keepAlive"
    };

    var notify = {
        data: {
            server: null,
            defaultData: []
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
                console.log(JSON.parse(event.data).type);
                this.saveData(event);
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
        },
        heartbeat: function () {
            var that = this;
            setInterval(function(){
                that.data.server.send(JSON.stringify(dataSend));
            },100000);
        },
        saveData:function (event) {
            var dataRes = JSON.parse(event.data);
            var dataRes = {"type":"newOrder","data":"顾客:李大爷产生了新订单"};
            var key = dataRes.type;
            var dataStorage = JSON.parse(localStorage.getItem(key));
            if(dataStorage){
                dataStorage.push(dataRes);
                localStorage.setItem(key,JSON.stringify(dataStorage));
            }else{
                var dataRes_arr = [dataRes];
                localStorage.setItem(key,JSON.stringify(dataRes_arr));
            }

            //判断消息类型,根据消息类型创建dom
            var newdataStorage = JSON.parse(localStorage.getItem(key));
            if(key == 'newOrder'){
                textDetail = '';
                var numOrder =  newdataStorage ? newdataStorage.length : 0;
                if(numOrder){
                    textDetail = "您有"+ numOrder +"条未读订单消息";
                    $('#newOrder').text(textDetail);
                    $('#newOrderli').show();
                    $('#dividerNotice').show();
                }else{
                    $('#newOrderli').hide();
                }
            }else if(key == 'newSign'){
                textDetail = '';
                var numSign =  newdataStorage ? newdataStorage.length : 0;
                if(numSign){
                    textDetail = "您有"+ numSign +"条未读签约消息";
                    $('#newSign').text(textDetail);
                    $('#newSignli').show();
                }else{
                    $('#newSignli').hide();
                }
            }
            $('#noticeNum').text(parseInt(numOrder ? numOrder : 0) + parseInt(numSign ? numSign : 0));
        },
        remove:function (obj) {
            //当点击某条未读消息时,清除对应的本地数据
            var idName = obj.attr('id');
            if((idName == 'newOrder')||(idName == 'newSign')){
                obj.on('click',function () {
                    if(localStorage.getItem(idName)){
                        localStorage.removeItem(idName);
                    }
                }
            }
        }
    };
</script>
