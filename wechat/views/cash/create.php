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
        <div class="swiper-container commit-order-container">
            <div class="swiper-wrapper">
                <!--商品信息begin-->
                <div class="swiper-slide swiper-no-swiping">
                    <form id="formStep1" action="<?=Yii::$app->getUrlManager()->createUrl(['cash/check-step'])?>">
                        <header class='demos-header'>
                            <h1 class="demos-title">提交订单</h1>
                        </header>
                        <input type="hidden" name="actionStep" value="1">
                        <div class="weui-cells__title">贷款信息</div>
                        <div class="weui-cell">
                            <div class="weui-cell__hd"><label class="weui-label">贷款金额</label></div>
                            <div class="weui-cell__bd">
                                <input class="weui-input" type="text" name="loanAmount" placeholder="请输入贷款金额(元)">
                            </div>
                        </div>
                        <div class="weui-cell weui-cell_select-picker">
                            <div class="weui-cell__hd">
                                <label class="weui-label">分期方式</label>
                            </div>
                            <div class="weui-cell__bd">
                                <input class="weui-input" id="InstallmentType" type="text" value="请选择分期方式">
                            </div>
                            <input type="hidden" name="installmentCycle">
                        </div>
                        <div class="weui-cell weui-cell_select-picker">
                            <div class="weui-cell__hd">
                                <label class="weui-label">分期时长</label>
                            </div>
                            <div class="weui-cell__bd">
                                <input class="weui-input" id="InstallmentPeriod" type="text" value="请选择分期时长">
                            </div>
                            <input type="hidden" name="installmentPeriod">
                        </div>
                        <div class="weui-cell weui-cell_switch">
                            <div class="weui-cell__bd">个人保障服务<br><span id="protectionFee"></span></div>
                            <div class="weui-cell__ft">
                                <input class="weui-switch" type="checkbox" name="isProtectionFee" checked="checked">
                            </div>
                        </div>
                        <div class="weui-cell weui-cell_switch">
                            <div class="weui-cell__bd">贵宾服务包<br><span id="vipServiceFee"></span></div>
                            <div class="weui-cell__ft">
                                <input class="weui-switch" type="checkbox" name="isVipServiceFee" checked="checked">
                            </div>
                        </div>
                        <div class="weui-cell">
                            <div class="weui-cell__hd"><label class="weui-label">每期还款</label></div>
                            <div class="weui-cell__bd weui-cell__ft" id="paymentContent">0元x0<br>(含可选服务费0元)</div>
                        </div>
                    </form>
                </div>
                <!--商品信息end-->

                <!--订单信息begin-->
                <div class="swiper-slide swiper-no-swiping">
                    <form id="formStep2" action="<?=Yii::$app->getUrlManager()->createUrl(['cash/check-step'])?>">
                        <header class='demos-header'>
                            <h1 class="demos-title">客户信息</h1>
                        </header>
                        <input type="hidden" name="actionStep" value="2">
                        
                        <div class="weui-cell">
                            <div class="weui-cell__hd"><label class="weui-label">客户姓名</label></div>
                            <div class="weui-cell__bd">
                                <input class="weui-input" type="text" name="realName" placeholder="请输入客户姓名">
                            </div>
                        </div>
                        <div class="weui-cell">
                            <div class="weui-cell__hd"><label class="weui-label">客户身份证号</label></div>
                            <div class="weui-cell__bd">
                                <input class="weui-input" type="text" name="certNo" placeholder="请输入身份证号">
                            </div>
                        </div>
                        <div class="weui-cell">
                            <div class="weui-cell__hd"><label class="weui-label">银行卡号</label></div>
                            <div class="weui-cell__bd">
                                <input class="weui-input" type="text" name="bankCardNo" placeholder="请输入银行卡号">
                            </div>
                        </div>
                        <div class="weui-cell">
                            <div class="weui-cell__hd"><label class="weui-label">预留手机号</label></div>
                            <div class="weui-cell__bd">
                                <input class="weui-input" type="text" name="bankMobileNo" placeholder="请输入银行预留手机号">
                            </div>
                        </div>
                        <div class="weui-cell">
                            <div class="weui-cell__hd"><label class="weui-label">联系手机号</label></div>
                            <div class="weui-cell__bd">
                                <input class="weui-input" type="text" name="mobileNo" placeholder="请输入联系手机号">
                            </div>
                        </div>
                        <div class="weui-cell">
                            <div class="weui-cell__hd"><label class="weui-label">调查地址</label></div>
                            <div class="weui-cell__bd">
                                <input class="weui-input" type="text" name="address" placeholder="请输入调查地址">
                            </div>
                        </div>
                    </form>
                </div>
                <!--订单信息end-->
            </div>
        </div>

        <div class="weui-navbar-bar">
            <div class="weui-navbar__item weui_bar__item_on" id="prevStep">上一步</div>
            <div class="weui-navbar__item" id="nextStep"><span id="tipsText">下一步</span>（<span id="currStep">1</span>/<span id="totalStep">1</span>）</div>
        </div>
    </div>
</body>
<script src="/wechat/lib/jquery-2.1.4.js"></script>
<script src="/wechat/lib/weui.js"></script>
<script src="/wechat/lib/fastclick.js"></script>
<script src="/wechat/js/jquery-weui.js"></script>
<script src="/wechat/js/swiper.js"></script>
<script src="/wechat/js/validform.min.js"></script>
<script src="/wechat/js/jquery-weui-extend.js"></script>
<script src="/wechat/js/cache.js"></script>
<script src="/wechat/src/cash.js"></script>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>
    // 获取每期还款URL
    var paymentUrl = "<?=Yii::$app->getUrlManager()->createUrl(['cash/payment'])?>";
    // 创建订单
    var createUrl = "<?=Yii::$app->getUrlManager()->createUrl(['cash/create-order'])?>";
    // 创建订单成功
    var successUrl = "<?=Yii::$app->getUrlManager()->createUrl(['cash/success'])?>";
    // 分期周期
    var installmentCycle = <?=$installmentCycle?>;
    // 贵宾服务包
    var vipServiceFee = <?=$vipServiceFee?>;
    // 个人保障计划
    var protectionFee = <?=$protectionFee?>;

    $(function() {
        // 现金贷业务
        var cash = new Cash({
            paymentUrl : paymentUrl,
            createUrl : createUrl,
            successUrl : successUrl,
            installmentCycle : installmentCycle,
            vipServiceFee : vipServiceFee,
            protectionFee : protectionFee
        });
        cash.init();
    });
</script>
</html>