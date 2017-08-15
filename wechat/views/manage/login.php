<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1,user-scalable=0">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta content="yes" name="apple-touch-fullscreen">
    <meta content="telephone=no,email=no" name="format-detection">
    <link href="https://cdn.bootcss.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/wechat/style/weui.css"/>
    <link rel="stylesheet" href="/wechat/style/example.css">
    <link rel="stylesheet" href="/wechat/style/index.css">
    <title>登录</title>
</head>
<body>
<section class="ui-container">
    <div class="login-item">
        <form action="">
            <div class="weui-flex tn-logo-div" >
                <div class="weui-flex__item" style="text-align: center;">
                    <img src="/wechat/img/tianniu.jpg" style="width: 70px;height: 70px;border-radius: 35px;" alt="">
                </div>
            </div>
            <div class="weui-cells weui-cells_form">
                <div class="weui-cell">
                    <div class="weui-cell__hd"><label class="weui-label">用户名</label></div>
                    <div class="weui-cell__bd">
                        <input class="weui-input" id="username" name="username" type="text" placeholder="请输入用户名"/>
                    </div>
                </div>
                <div class="weui-cell">
                    <div class="weui-cell__hd"><label class="weui-label">密码</label></div>
                    <div class="weui-cell__bd">
                        <input class="weui-input" name="password" type="password" id="passwords" placeholder="请输入密码">
                    </div>
                </div>
            </div>

            <div class="weui-btn-area">
                <a class="weui-btn weui-btn_primary" href="javascript:;" id="commit-form">登录</a>
            </div>
            <!--<div class="page__bd page__bd_spacing commit-form-div">-->
                <!--<a href="javascript:;" class="weui-btn weui-btn_primary" id="commit-form">登录</a>-->
            <!--</div>-->
        </form>
    </div>
</section>
</body>
<script src="/wechat/js/zepto.min.js"></script>
<script src="/wechat/js/weui.js"></script>
<script type="text/javascript">
    //验证表单
    (function () {
        $("#commit-form").on('click',function(){
            var username = $("#username").val();
            var password = $("#passwords").val();
            if(username == ''){
                weui.topTips('请输入用户名', {
                    duration: 2000,
                    className: "custom-classname",
                    callback: function () {
                        //console.log('close');
                    }
                });
                return false;
            }else if(password == ''){
                weui.topTips('请输入密码', {
                    duration: 2000,
                    className: "custom-classname",
                    callback: function () {
                        //  console.log('close');
                    }
                });
                return false;
            }
            $.ajax({
                type: 'POST',
                url: '/',
                data: { username: username,password:password},
                dataType: 'json',
                timeout: 3000,
                context: $('body'),
                success: function(data){
                    console.log(data);
                },
                error: function(xhr, type){
                    alert('Ajax error!')
                }
            });

        });
    })(Zepto);

</script>
</html>