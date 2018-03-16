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
    <div class="register-item register-form">
        <header class='demos-header'>
            <h1 class="demos-title">邀请注册</h1>
        </header>
        <form id="thisForm" action="<?=Yii::$app->getUrlManager()->createUrl(['site/register'])?>">
            <div class="weui-cells weui-cells_form">
                <input type="hidden" name="_csrf" value="<?=\Yii::$app->getRequest()->getCsrfToken();?>" />
                <div class="weui-cell">
                    <div class="weui-cell__hd"><label class="weui-label">邀请人</label></div>
                    <div class="weui-cell__bd">
                        <input class="weui-input" name="invite" disabled="disabled" type="text" value="<?=$sys_user->username?>">
                    </div>
                </div>
                <div class="weui-cell">
                    <div class="weui-cell__hd"><label class="weui-label">登录账号</label></div>
                    <div class="weui-cell__bd">
                        <input class="weui-input" name="username" type="text" placeholder="请输入登录账号(手机号)">
                    </div>
                </div>
                <div class="weui-cell">
                    <div class="weui-cell__hd"><label class="weui-label">登录密码</label></div>
                    <div class="weui-cell__bd">
                        <input class="weui-input" name="password" type="password" placeholder="请输入登录密码">
                    </div>
                </div>
                <div class="weui-cell">
                    <div class="weui-cell__hd"><label class="weui-label">确认密码</label></div>
                    <div class="weui-cell__bd">
                        <input class="weui-input" name="password_confirm" type="password" placeholder="请输入确认密码">
                    </div>
                </div>
                <div class="weui-cell">
                    <div class="weui-cell__hd"><label class="weui-label">真实姓名</label></div>
                    <div class="weui-cell__bd">
                        <input class="weui-input" name="realName" type="text" placeholder="请输入真实姓名">
                    </div>
                </div>
                <div class="weui-cell">
                    <div class="weui-cell__hd"><label class="weui-label">身份证号</label></div>
                    <div class="weui-cell__bd">
                        <input class="weui-input" name="certNo" type="text" placeholder="请输入身份证号">
                    </div>
                </div>
                <div class="weui-cell">
                    <div class="weui-cell__hd"><label class="weui-label">所属区域</label></div>
                    <div class="weui-cell__bd">
                        <input class="weui-input" id="invitedAddress" type="text" readonly="readonly" placeholder="请选择所属区域">
                        <input type="hidden" class="province" name="province" value="" />
                        <input type="hidden" class="city" name="city" value="" />
                        <input type="hidden" class="county" name="county" value="" />
                        <input type="hidden" class="province_id" name="province_id" value="" />
                        <input type="hidden" class="city_id" name="city_id" value="" />
                        <input type="hidden" class="county_id" name="county_id" value="" />
                    </div>
                </div>
                <div class="weui-cell">
                    <div class="weui-cell__hd"><label class="weui-label">联系地址</label></div>
                    <div class="weui-cell__bd">
                        <input class="weui-input" name="address" type="text" placeholder="请输入联系地址">
                    </div>
                </div>
                <div class="weui-cell">
                    <div class="weui-cell__hd"><label class="weui-label">联系邮箱</label></div>
                    <div class="weui-cell__bd">
                        <input class="weui-input" name="email" type="text" placeholder="请输入联系邮箱">
                    </div>
                </div>
            </div>
            <div class="weui-cells__tips">注：注册成功后可绑定微信登录</div>
            <div class="weui-btn-area">
                <a class="weui-btn weui-btn_primary" href="javascript:;" id="sure-form">确定注册</a>
            </div>
            <div class="weui-btn-area">
                <a class="weui-btn weui-btn_default" href="<?=Yii::$app->getUrlManager()->createUrl(['site/index'])?>">返回主页</a>
            </div>
        </form>
    </div>
</section>
<script src="/wechat/lib/jquery-2.1.4.js"></script>
<script src="/wechat/lib/weui.js"></script>
<script src="/wechat/lib/fastclick.js"></script>
<script src="/wechat/js/jquery-weui.js"></script>
<script src="/wechat/js/validform.min.js"></script>
<script src="/wechat/js/jquery-weui-extend.js"></script>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>
    var region = <?=$region?>;

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

        $('#invitedAddress').bind('click' , function(){
            var that  = $(this);

            var provinceInput = that.siblings('input.province');
            var cityInput = that.siblings('input.city');
            var countryInput = that.siblings('input.county');
            var provinceIdInput = that.siblings('input.province_id');
            var cityIdInput = that.siblings('input.city_id');
            var countryIdInput = that.siblings('input.county_id');

            var defaultValue = [24, 275, 2755];

            weui.picker(region, {
                depth: 3,
                defaultValue: defaultValue,
                onChange: function (result) {
                   // console.log(result);
                },
                onConfirm: function (result) {
                    var showValueArr = new Array;

                    var province = result[0] ? result[0].label : '';
                    var city = result[1] ? result[1].label : '';
                    var country = result[2] ? result[2].label : '';

                    var provinceId = result[0] ? result[0].value : '0';
                    var cityId = result[1] ? result[1].value : '0';
                    var countryId = result[2] ? result[2].value : '0';

                    showValueArr.push(province);
                    showValueArr.push(city);
                    showValueArr.push(country);

                    that.val(showValueArr.join(' '));

                    provinceInput.val(province);
                    cityInput.val(city);
                    countryInput.val(country);

                    provinceIdInput.val(provinceId);
                    cityIdInput.val(cityId);
                    countryIdInput.val(countryId);
                }
            });
        });

        // 验证数据并提交
        var validator = $('#thisForm').validator({
            btnSubmit: '#sure-form',
            ajaxPost: true,
            callback : function(res){
                if(res.status == 1){
                    $.toast(res.message, function(){
                        return location.href = "<?=Yii::$app->getUrlManager()->createUrl(['site/register-success'])?>";
                    });
                }else{
                    $.toast(res.message, "text");
                }
            }
        }).addRule([{
            ele: "input[name=username]",
            datatype: /^(0|86|17951)?(13[0-9]|15[012356789]|18[0-9]|17[0-9]|14[57])[0-9]{8}$/,
            nullmsg: "请输入用户名",
            errormsg: "用户名不合法"
        }, {
            ele: "input[name=password]",
            datatype: 's6-20',
            nullmsg: "请输入密码",
            errormsg: "密码长度为6~20之间"
        }, {
            ele: "input[name=password_confirm]",
            datatype: 's6-20',
            recheck:"password",
            nullmsg: "请输入确认密码",
            errormsg: "两次密码不一致"
        }, {
            ele: "input[name=realName]",
            datatype: 's1-5',
            nullmsg: "请输入真实姓名",
            errormsg: "真实姓名长度为1~5之间"
        }, {
            ele: "input[name=certNo]",
            datatype: /(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/,
            nullmsg: "请输入身份证号",
            errormsg: "身份证号不合法"
        }
        // , {
        //     ele: "input[name=address]",
        //     datatype: 's6-40',
        //     nullmsg: "请输入联系地址",
        //     errormsg: "联系地址长度为6~40之间"
        // }
        , {
            ele: "input[name=province]",
            datatype: 's1-20',
            nullmsg: "请选择所属区域",
            errormsg: "不支持的省"
        }, {
            ele: "input[name=city]",
            datatype: 's1-20',
            nullmsg: "请选择所属区域",
            errormsg: "不支持的市"
        }, {
            ele: "input[name=country]",
            datatype: 's1-20',
            nullmsg: "请选择所属区域",
            errormsg: "不支持的县/区"
        }, {
            ele: "input[name=email]",
            datatype: 'e',
            ignore: "ignore",
            nullmsg: "请输入联系邮箱",
            errormsg: "联系邮箱不合法"
        }]);
    });
</script>
</body>
</html>