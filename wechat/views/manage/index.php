<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1,user-scalable=0">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta content="yes" name="apple-touch-fullscreen">
    <meta content="telephone=no,email=no" name="format-detection">
    <!--<link href="https://cdn.bootcss.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">-->
    <link rel="stylesheet" href="/wechat/style/weui.css"/>
    <title>首页</title>
</head>
<body>
<section class="ui-container">
    <div class="weui-cells">
        <a class="weui-cell weui-cell_access" href="<?= Yii::$app->getUrlManager()->createUrl(['manage/commitorder'])?>">
            <div class="weui-cell__hd"><img src="/wechat/img/order.png" alt="" style="width:35px;margin-right:15px;display:block"></div>
            <div class="weui-cell__bd">
                <p>提交订单</p>
            </div>
            <div class="weui-cell__ft"></div>
        </a>
        <a class="weui-cell weui-cell_access" href="javascript:;">
            <div class="weui-cell__hd"><img src="/wechat/img/photo_picker.png" alt="" style="width:35px;margin-right:15px;display:block"></div>
            <div class="weui-cell__bd">
                <p>影像采集</p>
            </div>
            <div class="weui-cell__ft"></div>
        </a>
        <a class="weui-cell weui-cell_access" href="javascript:;">
            <div class="weui-cell__hd"><img src="/wechat/img/msg.png" alt="" style="width:35px;margin-right:15px;display:block"></div>
            <div class="weui-cell__bd">
                <p>消息</p>
            </div>
            <div class="weui-cell__ft"></div>
        </a>
        <a class="weui-cell weui-cell_access" href="javascript:;">
            <div class="weui-cell__hd"><img src="/wechat/img/history_order.png" alt="" style="width:35px;margin-right:15px;display:block"></div>
            <div class="weui-cell__bd">
                <p>历史订单</p>
            </div>
            <div class="weui-cell__ft"></div>
        </a>
        <a class="weui-cell weui-cell_access" href="javascript:;">
            <div class="weui-cell__hd"><img src="/wechat/img/overdue_order.png" alt="" style="width:35px;margin-right:15px;display:block"></div>
            <div class="weui-cell__bd">
                <p>逾期列表</p>
            </div>
            <div class="weui-cell__ft"></div>
        </a>
    </div>
</section>

<script src="/wechat/js/weui.js"></script>
</body>
</html>