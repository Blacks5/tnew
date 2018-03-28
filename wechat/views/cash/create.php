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
                <!--贷款信息begin-->
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
                                <label class="weui-label">借款用途</label>
                            </div>
                            <div class="weui-cell__bd">
                                <input class="weui-input" id="purposeType" type="text" value="请选择借款用途">
                            </div>
                            <input type="hidden" name="purpose">
                        </div>
                        <div class="weui-cell weui-cell_select-picker">
                            <div class="weui-cell__hd">
                                <label class="weui-label">产品类型</label>
                            </div>
                            <div class="weui-cell__bd">
                                <input class="weui-input" id="productType" type="text" value="请选择产品类型">
                            </div>
                            <input type="hidden" name="productType">
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
                <!--贷款信息end-->

                <!--客户信息begin-->
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
                        <div class="weui-cell">
                            <div class="weui-cell__hd"><label class="weui-label">备注</label></div>
                            <div class="weui-cell__bd">
                                <textarea class="weui-textarea" name="remark" placeholder="请输入备注" id="remarkTextArea"></textarea>
                                <div class="weui-textarea-counter"><span id="remarkCounter">0</span>/200</div>
                            </div>
                        </div>
                    </form>
                </div>
                <!--客户信息end-->

                <!--详细信息begin-->
                <div class="swiper-slide swiper-no-swiping">
                    <form id="formStep3" action="<?=Yii::$app->getUrlManager()->createUrl(['cash/check-step'])?>">
                        <header class='demos-header'>
                            <h1 class="demos-title">订单调查</h1>
                        </header>
                        <input type="hidden" name="actionStep" value="3">
                        <div class="weui-cells__title">详细信息</div>
                        <div class="weui-cell">
                            <div class="weui-cell__hd"><label class="weui-label">客户性别</label></div>
                            <div class="weui-cell__bd">
                                <div class="weui-cells weui-cells_radio weui-cells-radio">
                                    <label class="weui-cell weui-check__label" for="x11">
                                        <div class="weui-cell__bd">
                                            <p>男</p>
                                        </div>
                                        <div class="weui-cell__ft">
                                            <input type="radio" class="weui-check" name="gender" id="x11" value="男" checked="checked">
                                            <span class="weui-icon-checked"></span>
                                        </div>
                                    </label>
                                    <label class="weui-cell weui-check__label" for="x12">
                                        <div class="weui-cell__bd">
                                            <p>女</p>
                                        </div>
                                        <div class="weui-cell__ft">
                                            <input type="radio" name="gender" class="weui-check" id="x12" value="女">
                                            <span class="weui-icon-checked"></span>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="weui-cell weui-cell_select-picker">
                            <div class="weui-cell__hd">
                                <label class="weui-label">婚姻状况</label>
                            </div>
                            <div class="weui-cell__bd">
                                <input class="weui-input" id="maritalSituation" type="text" value="请选择婚姻状况">
                            </div>
                            <input type="hidden" name="marital">
                        </div>
                        <div class="weui-cell">
                            <div class="weui-cell__hd"><label class="weui-label">月收入</label></div>
                            <div class="weui-cell__bd">
                                <input class="weui-input" type="text" name="monthlyIncome" placeholder="请输入月收入">
                            </div>
                        </div>
                        <div class="weui-cell weui-cell_select-picker">
                            <div class="weui-cell__hd">
                                <label class="weui-label">房屋权属</label>
                            </div>
                            <div class="weui-cell__bd">
                                <input class="weui-input" id="houseProperty" type="text" value="请选择房屋权属">
                            </div>
                            <input type="hidden" name="houseProperty">
                        </div>
                        <div class="weui-cell">
                            <div class="weui-cell__hd"><label class="weui-label">户籍地址</label></div>
                            <div class="weui-cell__bd">
                                <input class="weui-input" type="text" name="cardAddress" placeholder="请输入户籍地址">
                            </div>
                        </div>
                        <div class="weui-cell">
                            <div class="weui-cell__hd"><label class="weui-label">居住地址</label></div>
                            <div class="weui-cell__bd">
                                <input class="weui-input" type="text" name="currentAddress" placeholder="请输入居住地址">
                            </div>
                        </div>
                        <div class="weui-cell">
                            <div class="weui-cell__hd"><label class="weui-label">工作单位</label></div>
                            <div class="weui-cell__bd">
                                <input class="weui-input" type="text" name="jobName" placeholder="请输入工作单位">
                            </div>
                        </div>
                        <div class="weui-cell">
                            <div class="weui-cell__hd"><label class="weui-label">单位地址</label></div>
                            <div class="weui-cell__bd">
                                <input class="weui-input" type="text" name="jobAddress" placeholder="请输入单位地址">
                            </div>
                        </div>
                        <div class="weui-cell">
                            <div class="weui-cell__hd"><label class="weui-label">单位电话</label></div>
                            <div class="weui-cell__bd">
                                <input class="weui-input" type="text" name="jobPhone" placeholder="请输入单位电话">
                            </div>
                        </div>
                        <div class="weui-cell">
                            <div class="weui-cell__hd"><label class="weui-label">微信账号</label></div>
                            <div class="weui-cell__bd">
                                <input class="weui-input" type="text" name="wechat" placeholder="请输入微信账号">
                            </div>
                        </div>
                        <div class="weui-cell">
                            <div class="weui-cell__hd"><label class="weui-label">QQ账号</label></div>
                            <div class="weui-cell__bd">
                                <input class="weui-input" type="text" name="qq" placeholder="请输入QQ账号">
                            </div>
                        </div>
                        <div class="weui-cell">
                            <div class="weui-cell__hd"><label class="weui-label">支付宝账号</label></div>
                            <div class="weui-cell__bd">
                                <input class="weui-input" type="text" name="alipay" placeholder="请输入支付宝账号">
                            </div>
                        </div>
                    </form>
                </div>
                <!--详细信息end-->

                <!--联系信息begin-->
                <div class="swiper-slide swiper-no-swiping">
                    <form id="formStep4" action="<?=Yii::$app->getUrlManager()->createUrl(['cash/check-step'])?>">
                        <header class='demos-header'>
                            <h1 class="demos-title">订单调查</h1>
                        </header>
                        <input type="hidden" name="actionStep" value="4">
                        <div class="weui-cells__title">联系人信息</div>
                        <a href="javascript:void(0);" class="weui-cell weui-cell_link" id="addContactBtn"><div class="weui-cell__bd">添加更多联系人信息</div></a>
                        <a href="javascript:void(0);" class="weui-cell weui-cell_link" id="delContactBtn"><div class="weui-cell__bd">删除一个联系人信息</div></a>
                    </form>
                </div>
                <!--联系信息end-->
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
    // 产品类型
    var cashProductType = <?=$cashProductType?>;
    // 借款用途
    var casePurpose = <?=$casePurpose?>;
    // 婚姻状况
    var maritalSituation = <?=$maritalSituation?>;
    // 联系人关系
    var contactRelationship = <?=$contactRelationship?>;
    // 房屋权属
    var houseProperty = <?=$houseProperty?>;

    $(function() {
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

        // 现金贷业务
        var cash = new Cash({
            paymentUrl : paymentUrl,
            createUrl : createUrl,
            successUrl : successUrl,
            installmentCycle : installmentCycle,
            cashProductType : cashProductType,
            casePurpose : casePurpose,
            maritalSituation : maritalSituation,
            contactRelationship : contactRelationship,
            houseProperty : houseProperty
        });
        cash.init();
    });
</script>
</html>