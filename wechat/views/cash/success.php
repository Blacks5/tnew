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
    <div class="main-box">
        <div class="weui-msg">
            <div class="weui-msg__icon-area"><i class="weui-icon-success weui-icon_msg"></i></div>
            <div class="weui-msg__text-area">
                <h2 class="weui-msg__title">提交成功</h2>
                <p class="weui-msg__desc">订单已提交成功，请耐心等待。<br />如需了解审核进度，请<a href="<?= Yii::$app->getUrlManager()->createUrl(['case/detail'])?>">点击查看</a>订单详情</p>
            </div>
            <div class="weui-msg__opr-area">
                <p class="weui-btn-area">
                    <a href="<?= Yii::$app->getUrlManager()->createUrl(['site/index'])?>" class="weui-btn weui-btn_primary">返回菜单</a>
                    <a href="<?= Yii::$app->getUrlManager()->createUrl(['cash/create-order'])?>" class="weui-btn weui-btn_default">再次提单</a>
                </p>
            </div>
            <div class="weui-msg__extra-area">
                <div class="weui-footer">
                    <p class="weui-footer__links">
                        <a href="http://tnew.cn" class="weui-footer__link">天牛金融</a>
                    </p>
                    <p class="weui-footer__text">Copyright © 2017 tnew.cn</p>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="/wechat/lib/jquery-2.1.4.js"></script>
<script src="/wechat/lib/weui.js"></script>
<script src="/wechat/lib/fastclick.js"></script>
<script src="/wechat/js/jquery-weui.js"></script>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>
</script>
</html>