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
    <div class="weui-msg">
        <div class="weui-msg__icon-area">
            <?php if($status){ ?>
                <i class="weui-icon-success weui-icon_msg"></i>
            <?php }else{ ?>
                <i class="weui-icon-warn weui-icon_msg"></i>
            <?php } ?>
        </div>
        <div class="weui-msg__text-area">
            <h2 class="weui-msg__title"><?=$title?></h2>
            <p class="weui-msg__desc"><?=$desc?></p>
        </div>
        <div class="weui-msg__opr-area">
            <p class="weui-btn-area">
                <a href="javascript:;" class="weui-btn weui-btn_primary reback-index">返回首页</a>
                <a href="javascript:;" class="weui-btn weui-btn_default reback-prev">返回上一页</a>
            </p>
        </div>
        <div class="weui-msg__extra-area">
            <div class="weui-footer">
                <p class="weui-footer__links">
                    <a href="http://tnew.cn" class="weui-footer__link">天牛金融</a>
                </p>
                <p class="weui-footer__text">Copyright © 2016 tnew.cn</p>
            </div>
        </div>
    </div>
</body>
<script src="/wechat/lib/jquery-2.1.4.js"></script>
<script src="/wechat/lib/fastclick.js"></script>
<script src="/wechat/js/jquery-weui.js"></script>
<script src="/wechat/js/jquery-weui-extend.js"></script>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>
    $(function(){
        FastClick.attach(document.body);

        wx.config(<?php echo $js->config(['hideMenuItems'], true) ?>);

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

        // 返回首页
        $('.reback-index').bind('click' , function(){
            window.location = '/';
        });

        // 返回上一页
        $('.reback-prev').bind('click' , function(){
            history.go(-1);
        });
    });
</script>
</html>