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
                            <h1 class="demos-title">订单调查</h1>
                        </header>
                        <input type="hidden" name="actionStep" value="3">
                        <input type="hidden" name="orderId" value="<?=$orderId?>">
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
                <!--商品信息end-->

                <!--订单信息begin-->
                <div class="swiper-slide swiper-no-swiping">
                    <form id="formStep2" action="<?=Yii::$app->getUrlManager()->createUrl(['cash/check-step'])?>">
                        <header class='demos-header'>
                            <h1 class="demos-title">订单调查</h1>
                        </header>
                        <input type="hidden" name="actionStep" value="4">
                        <div class="weui-cells__title">联系人信息</div>
                        <a href="javascript:void(0);" class="weui-cell weui-cell_link" id="addContactBtn"><div class="weui-cell__bd">添加更多联系人信息</div></a>
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
<script src="/wechat/src/cash-edit.js"></script>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>
    // 创建订单
    var editUrl = "<?=Yii::$app->getUrlManager()->createUrl(['cash/edit-order'])?>";
    // 创建订单成功
    var successUrl = "<?=Yii::$app->getUrlManager()->createUrl(['cash/success'])?>";
    // 婚姻状况
    var maritalSituation = <?=$maritalSituation?>;
    // 联系人关系
    var contactRelationship = <?=$contactRelationship?>;

    $(function() {
        // 现金贷业务
        var cash = new Cash({
            editUrl : editUrl,
            successUrl : successUrl,
            maritalSituation : maritalSituation,
            contactRelationship : contactRelationship
        });
        cash.init();
    });
</script>
</html>