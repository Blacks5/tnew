<!DOCTYPE html>
<html>
<head>
    <title>天牛金融管理</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <meta name="description" content="">
    <link rel="stylesheet" href="/wechat/lib/weui.min.css">
    <link rel="stylesheet" href="/wechat/css/jquery-weui.css">
    <link rel="stylesheet" href="/wechat/css/core.css">
</head>
<body ontouchstart>
<header class='demos-header'>
    <h1 class="demos-title">天牛金融</h1>
    <p class='demos-sub-title'>天牛金融微信管理服务平台</p>
</header>
<div class="weui-grids">
    <a href="<?= Yii::$app->getUrlManager()->createUrl(['manage/commitorder'])?>" class="weui-grid js_grid">
        <div class="weui-grid__icon">
            <img src="/wechat/images/order.png" alt="">
        </div>
        <p class="weui-grid__label">
            提交订单
        </p>
    </a>
    <a href="cell.html" class="weui-grid js_grid">
        <div class="weui-grid__icon">
            <img src="/wechat/images/photo_picker.png" alt="">
        </div>
        <p class="weui-grid__label">
            影像采集
        </p>
    </a>
    <a href="<?= Yii::$app->getUrlManager()->createUrl(['order/order-list'])?>" class="weui-grid js_grid">
        <div class="weui-grid__icon">
            <img src="/wechat/images/history_order.png" alt="">
        </div>
        <p class="weui-grid__label">
            历史订单
        </p>
    </a>
    <a href="toast.html" class="weui-grid js_grid">
        <div class="weui-grid__icon">
            <img src="/wechat/images/overdue_order.png" alt="">
        </div>
        <p class="weui-grid__label">
            逾期订单
        </p>
    </a>
    <a href="form.html" class="weui-grid js_grid">
        <div class="weui-grid__icon">
            <img src="/wechat/images/msg.png" alt="">
        </div>
        <p class="weui-grid__label">
            消息中心
        </p>
    </a>
    <a href="dialog.html" class="weui-grid js_grid">
        <div class="weui-grid__icon">
            <img src="/wechat/images/icon_nav_dialog.png" alt="">
        </div>
        <p class="weui-grid__label">
            我的二维码
        </p>
    </a>
</div>
<div class="weui-footer">
    <p class="weui-footer__links">
        <a href="http://jqweui.com" class="weui-footer__link">天牛金融</a>
    </p>
    <p class="weui-footer__text">Copyright © 2016 tnew.cn</p>
</div>
<script src="/wechat/lib/jquery-2.1.4.js"></script>
<script src="/wechat/lib/fastclick.js"></script>
<script src="/wechat/js/jquery-weui.js"></script>
<script>
    $(function() {
        FastClick.attach(document.body);
    });
</script>
</body>
</html>