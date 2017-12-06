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
<!-- <header class='demos-header'>
    <h1 class="demos-title">天牛金融</h1>
    <p class='demos-sub-title'>天牛金融微信管理服务平台</p>
</header> -->
<br />
<div class="weui-panel__bd weui-cell_access">
    <a href="javascript:void(0);" class="weui-media-box weui-media-box_appmsg" id="usercenter">
      <div class="weui-media-box__hd">
        <?php if($wechat_user && $wechat_user->avatar) { ?>
            <img src="<?=$wechat_user->avatar?>" class="weui-media-box__thumb" style="width: 60px;height: 60px;border-radius: 50%;" alt="">
        <?php }else{ ?>
            <img src="/wechat/images/tianniu.jpg" class="weui-media-box__thumb" style="width: 60px;height: 60px;border-radius: 50%;" alt="">
        <?php } ?>
      </div>
      <div class="weui-media-box__bd">
        <h4 class="weui-media-box__title"><?=$sys_user->realname?></h4>
        <p class="weui-media-box__desc">
            <?=$sys_user->username?>
            <br />
            <?php if($wechat_user && $wechat_user->areas) { ?>
                <?=implode('-' , $sys_user->areas)?>
            <?php } ?>
        </p>
      </div>
      <span class="weui-cell__ft"></span>
    </a>
</div>
<br />
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
<script src="/wechat/js/jquery-weui-extend.js"></script>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>
    // 退出登录
    var logoutUrl = "<?=Yii::$app->getUrlManager()->createUrl(['site/logout'])?>";
    // 解除绑定
    var unbindUrl = "<?=Yii::$app->getUrlManager()->createUrl(['site/unbind'])?>";

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

        $('#usercenter').bind('click' , function(){
            // 默认操作
            var defaultActions = {
                logout : {
                    text: "退出登录",
                    className: "color-warning",
                    onClick: function() {
                        $.confirm({
                            title: '确认要退出登录吗？',
                            text: '退出登录后微信浏览器将自动关闭。',
                            onOK: function (input) {
                                $.ajaxPost(logoutUrl , {} , function(res){
                                    if(res.status){
                                        $.toast(res.message, function(){
                                            WeixinJSBridge.call('closeWindow');
                                        });
                                    }else{
                                        $.toast(res.message, "text");
                                    }
                                });
                            }
                        });
                    }
                },
                unbind : {
                    text: "解除绑定",
                    className: "color-danger",
                    onClick: function() {
                        $.confirm({
                            title: '确认要解除绑定吗？',
                            text: '解除绑定后微信浏览器将自动关闭。',
                            onOK: function (input) {
                                $.ajaxPost(unbindUrl , {} , function(res){
                                    if(res.status){
                                        $.toast(res.message, function(){
                                            WeixinJSBridge.call('closeWindow');
                                        });
                                    }else{
                                        $.toast(res.message, "text");
                                    }
                                });
                            }
                        });
                    }
                }
            }

            // 绑定操作
            var actions = new Array;

            // 退出登录
            actions.push(defaultActions.logout);
            actions.push(defaultActions.unbind);

            $.actions({
                title: "操作",
                onClose: function() {},
                actions: actions
            });
        });
    });
</script>
</body>
</html>