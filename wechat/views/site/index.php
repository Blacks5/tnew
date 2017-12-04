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
    <a href="<?= Yii::$app->getUrlManager()->createUrl(['order/create-order'])?>" class="weui-grid js_grid">
        <div class="weui-grid__icon">
            <img src="/wechat/images/order.png" alt="">
        </div>
        <p class="weui-grid__label">
            3C订单
        </p>
    </a>
    <a href="<?= Yii::$app->getUrlManager()->createUrl(['order/wait-order-list'])?>" class="weui-grid js_grid">
        <div class="weui-grid__icon">
            <img src="/wechat/images/photo_picker.png" alt="">
        </div>
        <p class="weui-grid__label">
            待审订单
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
    <a href="<?= Yii::$app->getUrlManager()->createUrl(['order/overdue-order-list'])?>" class="weui-grid js_grid">
        <div class="weui-grid__icon">
            <img src="/wechat/images/overdue_order.png" alt="">
        </div>
        <p class="weui-grid__label">
            逾期订单
        </p>
    </a>
    <a href="<?= Yii::$app->getUrlManager()->createUrl(['cash/create-order'])?>" class="weui-grid js_grid">
        <div class="weui-grid__icon">
            <img src="/wechat/images/msg.png" alt="">
        </div>
        <p class="weui-grid__label">
            现金贷订单
        </p>
    </a>
    <a href="<?= Yii::$app->getUrlManager()->createUrl(['cash/order-list'])?>" class="weui-grid js_grid">
        <div class="weui-grid__icon">
            <img src="/wechat/images/icon_nav_dialog.png" alt="">
        </div>
        <p class="weui-grid__label">
            现金贷审核
        </p>
    </a>
</div>
<div class="weui-msg__extra-area">
    <div class="weui-footer">
        <p class="weui-footer__links">
            <a href="http://tnew.cn" class="weui-footer__link">天牛金融</a>
        </p>
        <p class="weui-footer__text">Copyright © 2017 tnew.cn</p>
    </div>
</div>
<script src="/wechat/lib/jquery-2.1.4.js"></script>
<script src="/wechat/lib/fastclick.js"></script>
<script src="/wechat/js/jquery-weui.js"></script>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>
    $(function() {
        FastClick.attach(document.body);

        wx.config(<?php echo $js->config(['hideMenuItems']) ?>);

        wx.ready(function(){
            wx.hideMenuItems({
                menuList: [
                    'menuItem:share:appMessage',
                    'menuItem:share:timeline',
                    'menuItem:share:qq',
                    'menuItem:share:weiboApp',
                    'menuItem:share:facebook',
                    'menuItem:share:QZone',
                    'menuItem:copyUrl',
                    'menuItem:originPage',
                    'menuItem:openWithQQBrowser',
                    'menuItem:openWithSafari',
                    'menuItem:share:email'
                ]
            });
        });
    });
</script>
</body>
</html>