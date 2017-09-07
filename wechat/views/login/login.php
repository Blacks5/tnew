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
<section class="ui-container">
    <div class="login-item">
        <form id="thisForm" action="<?=Yii::$app->getUrlManager()->createUrl(['login/bind'])?>">
            <div class="weui-flex" style="padding-top: 20px;">
                <div class="weui-flex__item" style="text-align: center;">
                    <?php if($avatar) { ?>
                        <img src="<?=$avatar?>" style="width: 70px;height: 70px;border-radius: 35px;" alt="">
                    <?php }else{ ?>
                        <img src="/wechat/images/tianniu.jpg" style="width: 70px;height: 70px;border-radius: 35px;" alt="">
                    <?php } ?>
                </div>
            </div>
            <div class="weui-cells weui-cells_form">
                <input type="hidden" name="_csrf" value="<?=\Yii::$app->getRequest()->getCsrfToken();?>" />
                <div class="weui-cell">
                    <div class="weui-cell__hd"><label class="weui-label">用户名</label></div>
                    <div class="weui-cell__bd">
                        <input class="weui-input" name="username" type="text" placeholder="请输入用户名">
                    </div>
                </div>
                <div class="weui-cell">
                    <div class="weui-cell__hd"><label class="weui-label">密码</label></div>
                    <div class="weui-cell__bd">
                        <input class="weui-input" name="password" type="password" placeholder="请输入密码">
                        <input class="weui-input" name="openid" type="hidden" value="<?=$openid?>">
                    </div>
                </div>
            </div>
            <div class="weui-cells__tips">注：绑定账号的微信可直接登录系统</div>
            <div class="weui-btn-area">
                <a class="weui-btn weui-btn_primary" href="javascript:;" id="commit-form">绑定账号</a>
            </div>
        </form>
    </div>
</section>
<script src="/wechat/lib/jquery-2.1.4.js"></script>
<script src="/wechat/lib/fastclick.js"></script>
<script src="/wechat/js/jquery-weui.js"></script>
<script src="/wechat/js/validform.min.js"></script>
<script src="/wechat/js/jquery-weui-extend.js"></script>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>
    $(function() {
        FastClick.attach(document.body);

        // 通过下面这个API隐藏右上角按钮
        document.addEventListener('WeixinJSBridgeReady', function onBridgeReady() {
            WeixinJSBridge.call('hideOptionMenu');
        });

        // 验证数据并提交
        var validator = $('#thisForm').validator({
            btnSubmit: '#commit-form',
            ajaxPost: true,
            callback : function(res){
                if(res.status == 1){
                    $.toast(res.message, function(){
                        return location.href = "<?=Yii::$app->getUrlManager()->createUrl(['site/index'])?>";
                    });
                }else{
                    $.toast(res.message, "text");
                }
            }
        }).addRule([{
            ele: "input[name=username]",
            datatype: "s2-20",
            nullmsg: "请输入用户名",
            errormsg: "用户名不合法"
        }, {
            ele: "input[name=password]",
            datatype: 's2-20',
            nullmsg: "请输入密码",
            errormsg: "密码长度为2~20之间"
        }]);
    });
</script>
</body>
</html>