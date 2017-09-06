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
    <div class="swiper-container commit-order-container">
        <div class="swiper-wrapper">
            <!--商品信息begin-->
            <div class="swiper-slide swiper-no-swiping">
                <form id="formStep1" action="<?=Yii::$app->getUrlManager()->createUrl(['order/check-step'])?>">
                    <header class='demos-header'>
                        <h1 class="demos-title">提交订单</h1>
                    </header>
                    <input type="hidden" name="actionStep" value="1">
                    <div class="weui-cells__title">商品信息</div>
                    <div class="weui-cell weui-cell_select weui-cell_select-after">
                        <div class="weui-cell__hd">
                            <label class="weui-label">商品类型</label>
                        </div>
                        <div class="weui-cell__bd">
                            <select class="weui-select" name="g_goods_type">
                                <option value="">请选择商品类型</option>
                                <?php foreach ($data['goods_type'] as $k=>$v){ ?>
                                    <option value="<?= $v['t_id']; ?>"><?= $v['t_name']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="weui-cell">
                        <div class="weui-cell__hd"><label class="weui-label">商品品牌</label></div>
                        <div class="weui-cell__bd">
                            <input class="weui-input" type="text" name="g_goods_name" placeholder="请输入商品品牌">
                        </div>
                    </div>
                    <div class="weui-cell">
                        <div class="weui-cell__hd"><label class="weui-label">商品型号</label></div>
                        <div class="weui-cell__bd">
                            <input class="weui-input" type="text" name="g_goods_models" placeholder="请输入商品型号">
                        </div>
                    </div>
                    <div class="weui-cell">
                        <div class="weui-cell__hd"><label class="weui-label">商品序列号</label></div>
                        <div class="weui-cell__bd">
                            <input class="weui-input" type="text" name="g_goods_serial_no" placeholder="请输入商品序列号">
                        </div>
                    </div>
                    <div class="weui-cell">
                        <div class="weui-cell__hd"><label class="weui-label">商品价格</label></div>
                        <div class="weui-cell__bd">
                            <input class="weui-input" type="text" name="g_goods_price" placeholder="请输入商品价格">
                        </div>
                    </div>
                    <div class="weui-cell">
                        <div class="weui-cell__hd"><label class="weui-label">首付金额</label></div>
                        <div class="weui-cell__bd">
                            <input class="weui-input" type="text" name="g_goods_deposit" placeholder="请输入首付金额">
                        </div>
                    </div>
                </form>
            </div>
            <!--商品信息end-->

            <!--订单信息begin-->
            <div class="swiper-slide swiper-no-swiping">
                <form id="formStep2" action="<?=Yii::$app->getUrlManager()->createUrl(['order/check-step'])?>">
                    <header class='demos-header'>
                        <h1 class="demos-title">提交订单</h1>
                    </header>
                    <input type="hidden" name="actionStep" value="2">
                    <input type="hidden" name="g_goods_type" value="">
                    <div class="weui-cells__title">订单信息</div>
                    <div class="weui-cell weui-cell_select weui-cell_select-after">
                        <div class="weui-cell__hd"><label class="weui-label">商铺</label></div>
                        <div class="weui-cell__bd">
                            <select class="weui-select" name="o_store_id">
                                <option value="">请选择商铺</option>
                                <?php foreach ($data['stores'] as $k=>$v){ ?>
                                    <option value="<?= $v['s_id']; ?>"><?= $v['s_name']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="weui-cell weui-cell_select weui-cell_select-after">
                        <div class="weui-cell__hd"><label class="weui-label">产品</label></div>
                        <div class="weui-cell__bd">
                            <select class="weui-select" name="o_product_id">
                                <option value="">请选择产品</option>
                                <?php foreach ($data['products'] as $k=>$v){ ?>
                                    <option value="<?= $v['p_id']; ?>"><?= $v['p_name']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="weui-cell weui-cell_switch">
                        <div class="weui-cell__bd">自动代扣</div>
                        <div class="weui-cell__ft">
                            <input class="weui-switch" type="checkbox" name="o_is_auto_pay" checked="checked" disabled="disabled">
                        </div>
                    </div>
                    <div class="weui-cell weui-cell_switch">
                        <div class="weui-cell__bd">贵宾服务包</div>
                        <div class="weui-cell__ft">
                            <input class="weui-switch" type="checkbox" name="o_is_free_pack_fee" checked="checked">
                        </div>
                    </div>
                    <div class="weui-cell weui-cell_switch">
                        <div class="weui-cell__bd">个人保障服务</div>
                        <div class="weui-cell__ft">
                            <input class="weui-switch" type="checkbox" name="o_is_add_service_fee" checked="checked">
                        </div>
                    </div>
                    <div class="weui-cell">
                        <div class="weui-cell__hd"><label class="weui-label">备注</label></div>
                        <div class="weui-cell__bd">
                            <input class="weui-input" type="text" name="o_remark" placeholder="请输入备注">
                        </div>
                    </div>
                </form>
            </div>
            <!--订单信息end-->
            
            <!--客户基本信息begin-->
            <div class="swiper-slide swiper-no-swiping">
                <form id="formStep3" action="<?=Yii::$app->getUrlManager()->createUrl(['order/check-step'])?>">
                    <header class='demos-header'>
                        <h1 class="demos-title">提交订单</h1>
                    </header>
                    <input type="hidden" name="actionStep" value="3">
                    <div class="weui-cells__title">客户基本信息</div>
                    <div class="weui-cell weui-cell_select weui-cell_select-after">
                        <div class="weui-cell__hd"><label class="weui-label">开户银行</label></div>
                        <div class="weui-cell__bd">
                            <select class="weui-select" name="c_bank">
                                <option value="">请选择开户银行</option>
                                <?php foreach ($data['bank_list'] as $k=>$v){ ?>
                                    <option value="<?= $v['bank_id']; ?>"><?= $v['bank_name']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="weui-cell">
                        <div class="weui-cell__hd"><label class="weui-label">银行卡号</label></div>
                        <div class="weui-cell__bd">
                            <input class="weui-input" type="text" name="c_banknum" placeholder="请输入银行卡号">
                        </div>
                    </div>
                    <div class="weui-cell">
                        <div class="weui-cell__hd"><label class="weui-label">客户姓名</label></div>
                        <div class="weui-cell__bd">
                            <input class="weui-input" type="text" name="c_customer_name" placeholder="请输入客户姓名">
                        </div>
                    </div>
                    <div class="weui-cell">
                        <div class="weui-cell__hd"><label class="weui-label">客户手机号</label></div>
                        <div class="weui-cell__bd">
                            <input class="weui-input" type="text" name="c_customer_cellphone" placeholder="请输入客户手机号">
                        </div>
                    </div>
                    <div class="weui-cell">
                        <div class="weui-cell__hd"><label class="weui-label">客户身份证号</label></div>
                        <div class="weui-cell__bd">
                            <input class="weui-input" type="text" name="c_customer_id_card" placeholder="请输入身份证号">
                        </div>
                    </div>
                    <div class="weui-cell">
                        <div class="weui-cell__hd"><label class="weui-label">身份证过期时间</label></div>
                        <div class="weui-cell__bd">
                            <input class="weui-input" type="text" name="c_customer_id_card_endtime" placeholder="请输入身份证号">
                        </div>
                    </div>
<!--                     <div class="weui-cell weui-cell_switch">
                        <div class="weui-cell__bd">身份证过期时间是否永久</div>
                        <div class="weui-cell__ft">
                            <input class="weui-switch" type="checkbox" name="c_customer_id_card_endtime_status">
                        </div>
                    </div> -->
                    <div class="weui-cell weui-cell_switch">
                        <div class="weui-cell__bd">身份证地址</div>
                        <div class="weui-cell__ft">
                            <input class="weui-input" type="text" id="idcardAddress" readonly="readonly" placeholder="请选择省市区">
                            <input type="hidden" class="province_id" name="c_customer_province" value="" />
                            <input type="hidden" class="city_id" name="c_customer_city" value="" />
                            <input type="hidden" class="county_id" name="c_customer_county" value="" />
                        </div>
                    </div>
                    <div class="weui-cell weui-cell_switch">
                        <div class="weui-cell__bd">身份证详细地址</div>
                        <div class="weui-cell__ft">
                            <input class="weui-input" type="text" name="c_customer_idcard_detail_addr" placeholder="请输入身份证详细地址">
                        </div>
                    </div>
                    <div class="weui-cell weui-cell_select weui-cell_select-after">
                        <div class="weui-cell__hd">
                            <label class="weui-label">客户性别</label>
                        </div>
                        <div class="weui-cell__bd">
                            <select class="weui-select" name="c_customer_gender">
                                <option value="">请选择客户性别</option>
                                <option value="1">男</option>
                                <option value="0">女</option>
                            </select>
                        </div>
                    </div>
                    <div class="weui-cell weui-cell_switch">
                        <div class="weui-cell__bd">QQ</div>
                        <div class="weui-cell__ft">
                            <input class="weui-input" type="text" name="c_customer_qq" placeholder="请输入QQ号码">
                        </div>
                    </div>
                    <div class="weui-cell weui-cell_switch">
                        <div class="weui-cell__bd">微信</div>
                        <div class="weui-cell__ft">
                            <input class="weui-input" type="text" name="c_customer_wechat" placeholder="请输入微信号">
                        </div>
                    </div>
                    <div class="weui-cell weui-cell_select weui-cell_select-after">
                        <div class="weui-cell__hd"><label class="weui-label">婚姻状况</label></div>
                        <div class="weui-cell__bd">
                            <select class="weui-select" name="c_family_marital_status">
                                <option value="">请选择婚姻状况</option>
                                <?php foreach ($data['marital_status'] as $k=>$v){ ?>
                                    <option value="<?= $v['marital_id']; ?>"><?= $v['marital_str']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="weui-cell weui-cell_switch">
                        <div class="weui-cell__bd">配偶姓名</div>
                        <div class="weui-cell__ft">
                            <input class="weui-input" type="text" name="c_family_marital_partner_name" placeholder="请输入配偶姓名">
                        </div>
                    </div>
                    <div class="weui-cell weui-cell_switch">
                        <div class="weui-cell__bd">配偶电话</div>
                        <div class="weui-cell__ft">
                            <input class="weui-input" type="text" name="c_family_marital_partner_cellphone" placeholder="请输入配偶电话">
                        </div>
                    </div>
                    <div class="weui-cell weui-cell_select weui-cell_select-after">
                        <div class="weui-cell__hd"><label class="weui-label">住房情况</label></div>
                        <div class="weui-cell__bd">
                            <select class="weui-select" name="c_family_house_info">
                                <option value="">请选择住房状况</option>
                                <?php foreach ($data['house_info'] as $k=>$v){ ?>
                                    <option value="<?= $v['house_info_id']; ?>"><?= $v['house_info_str']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="weui-cell weui-cell_switch">
                        <div class="weui-cell__bd">个人月收入</div>
                        <div class="weui-cell__ft">
                            <input class="weui-input" type="text" name="c_family_income" placeholder="请输入个人月收入">
                        </div>
                    </div>
                    <div class="weui-cell weui-cell_switch">
                        <div class="weui-cell__bd">亲属姓名</div>
                        <div class="weui-cell__ft">
                            <input class="weui-input" type="text" name="c_kinship_name" placeholder="请输入亲属姓名">
                        </div>
                    </div>
                    <div class="weui-cell weui-cell_select weui-cell_select-after">
                        <div class="weui-cell__hd"><label class="weui-label">亲属关系</label></div>
                        <div class="weui-cell__bd">
                            <select class="weui-select" name="c_kinship_relation">
                                <option value="">请选择亲属关系</option>
                                <?php foreach ($data['kinship'] as $k=>$v){ ?>
                                    <option value="<?= $v['kinship_id']; ?>"><?= $v['kinship_str']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="weui-cell weui-cell_switch">
                        <div class="weui-cell__bd">亲属电话</div>
                        <div class="weui-cell__ft">
                            <input class="weui-input" type="text" name="c_kinship_cellphone" placeholder="请输入亲属电话">
                        </div>
                    </div>
                    <div class="weui-cell weui-cell_switch">
                        <div class="weui-cell__bd">客户现居地</div>
                        <div class="weui-cell__ft">
                            <input class="weui-input" type="text" id="customerAddress" readonly="readonly" placeholder="请选择省市区">
                            <input type="hidden" class="province_id" name="c_customer_addr_province" value="" />
                            <input type="hidden" class="city_id" name="c_customer_addr_city" value="" />
                            <input type="hidden" class="county_id" name="c_customer_addr_county" value="" />
                        </div>
                    </div>
                    <div class="weui-cell weui-cell_switch">
                        <div class="weui-cell__bd">详细地址</div>
                        <div class="weui-cell__ft">
                            <input class="weui-input" type="text" name="c_customer_addr_detail" placeholder="请输入客户现居地详细地址">
                        </div>
                    </div>
                </form>
            </div>
            <!--客户基本信息end-->

            <!--客户单位信息begin-->
            <div class="swiper-slide swiper-no-swiping">
                <form id="formStep4" action="<?=Yii::$app->getUrlManager()->createUrl(['order/check-step'])?>">
                    <header class='demos-header'>
                        <h1 class="demos-title">提交订单</h1>
                    </header>
                    <input type="hidden" name="actionStep" value="4">
                    <input type="hidden" name="c_customer_id_card" value="">
                    <div class="weui-cells__title">客户单位信息</div>
                    <div class="weui-cell weui-cell_switch">
                        <div class="weui-cell__bd">单位名称</div>
                        <div class="weui-cell__ft">
                            <input class="weui-input" type="text" name="c_customer_jobs_company" placeholder="请输入单位名称">
                        </div>
                    </div>
                    <div class="weui-cell weui-cell_select weui-cell_select-after">
                        <div class="weui-cell__hd"><label class="weui-label">所属行业</label></div>
                        <div class="weui-cell__bd">
                            <select class="weui-select" name="c_customer_jobs_industry">
                                <option value="">请选择所属行业</option>
                                <?php foreach ($data['company_kind'] as $k=>$v){ ?>
                                    <option value="<?= $v['company_kind_id']; ?>"><?= $v['company_kind_name']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="weui-cell weui-cell_select weui-cell_select-after">
                        <div class="weui-cell__hd"><label class="weui-label">公司性质</label></div>
                        <div class="weui-cell__bd">
                            <select class="weui-select" name="c_customer_jobs_type">
                                <option value="">请选择公司性质</option>
                                <?php foreach ($data['company_type'] as $k=>$v){ ?>
                                    <option value="<?= $v['company_type_id']; ?>"><?= $v['company_type_name']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="weui-cell weui-cell_switch">
                        <div class="weui-cell__bd">所属部门</div>
                        <div class="weui-cell__ft">
                            <input class="weui-input" type="text" name="c_customer_jobs_section" placeholder="请输入所属部门">
                        </div>
                    </div>
                    <div class="weui-cell weui-cell_switch">
                        <div class="weui-cell__bd">所属职位</div>
                        <div class="weui-cell__ft">
                            <input class="weui-input" type="text" name="c_customer_jobs_title" placeholder="请输入职位">
                        </div>
                    </div>
                    <div class="weui-cell weui-cell_select weui-cell_select-after">
                        <div class="weui-cell__hd"><label class="weui-label">是否购买社保</label></div>
                        <div class="weui-cell__bd">
                            <select class="weui-select" name="c_customer_jobs_is_shebao">
                                <option value="">请选择是否购买社保</option>
                                <option value="1">是</option>
                                <option value="0">否</option>
                            </select>
                        </div>
                    </div>
                    <div class="weui-cell weui-cell_switch">
                        <div class="weui-cell__bd">公司地址</div>
                        <div class="weui-cell__ft">
                            <input class="weui-input" type="text" id="customerJobsAddress" placeholder="请选择省市区">
                            <input type="hidden" class="province_id" name="c_customer_jobs_province" value="" />
                            <input type="hidden" class="city_id" name="c_customer_jobs_city" value="" />
                            <input type="hidden" class="county_id" name="c_customer_jobs_county" value="" />
                        </div>
                    </div>
                    <div class="weui-cell weui-cell_switch">
                        <div class="weui-cell__bd">详细地址</div>
                        <div class="weui-cell__ft">
                            <input class="weui-input" type="text" name="c_customer_jobs_detail_addr" placeholder="请输入客户现居地详细地址">
                        </div>
                    </div>
                    <div class="weui-cell weui-cell_switch">
                        <div class="weui-cell__bd">公司座机</div>
                        <div class="weui-cell__ft">
                            <input class="weui-input" type="text" name="c_customer_jobs_phone" placeholder="请输入公司座机">
                        </div>
                    </div>
                </form>
            </div>
            <!--客户单位信息end-->

            <!--客户其他联系人信息begin-->
            <div class="swiper-slide swiper-no-swiping">
                <form id="formStep5" action="<?=Yii::$app->getUrlManager()->createUrl(['order/check-step'])?>">
                    <header class='demos-header'>
                        <h1 class="demos-title">提交订单</h1>
                    </header>
                    <input type="hidden" name="actionStep" value="5">
                    <input type="hidden" name="c_customer_id_card" value="">
                    <div class="weui-cells__title">客户其他联系人信息</div>
                    <div class="weui-cell weui-cell_select weui-cell_select-after">
                        <div class="weui-cell__hd"><label class="weui-label">联系人关系</label></div>
                        <div class="weui-cell__bd">
                            <select class="weui-select" name="c_other_people_relation">
                                <option value="">请选择联系人关系</option>
                                <option value="1">同事</option>
                                <option value="2">朋友</option>
                                <option value="3">同学</option>
                            </select>
                        </div>
                    </div>
                    <div class="weui-cell">
                        <div class="weui-cell__bd">联系人姓名</div>
                        <div class="weui-cell__ft">
                            <input class="weui-input" type="text" name="c_other_people_name" placeholder="请输入联系人姓名">
                        </div>
                    </div>
                    <div class="weui-cell">
                        <div class="weui-cell__bd">联系人电话</div>
                        <div class="weui-cell__ft">
                            <input class="weui-input" type="text" name="c_other_people_cellphone" placeholder="请输入联系人电话">
                        </div>
                    </div>
                </form>
            </div>
            <!--客户其他联系人信息end-->
        </div>
    </div>

    <div class="weui-btn-next">
        <div class="weui-btn-area">
            <a class="weui-btn weui-btn_primary" href="javascript:" id="nextStep">下一步（<span id="currStep">1</span>/<span id="totalStep">1</span>）</a>
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
<script>
    $(function() {
        FastClick.attach(document.body);
        
        // document.body.addEventListener('touchmove' , function(e){
        //     e.preventDefault();
        // } , false);

        // city json数据包
        var city = <?= $data_json; ?>;

        // 当前页面类
        function Page(){
            this.swiper;
            this.createOrderUrl = "<?=Yii::$app->getUrlManager()->createUrl(['order/create-order'])?>";
            this.selects = [
                'g_goods_type',
                'o_store_id',
                'o_product_id',
                'c_bank',
                'c_customer_gender',
                'c_family_marital_status',
                'c_family_house_info',
                'c_kinship_relation',
                'c_customer_jobs_industry',
                'c_customer_jobs_type',
                'c_customer_jobs_is_shebao',
                'c_other_people_relation'
            ];
            this.checkboxs = [
                'o_is_auto_pay',
                'o_is_free_pack_fee',
                'o_is_add_service_fee',
                'c_customer_id_card_endtime_status'
            ];
            this.ids = [
                'idcardAddress',
                'customerAddress',
                'customerJobsAddress'
            ];
        }

        /**
         * 初始化
         * @return {[type]} [description]
         */
        Page.prototype.init = function(){
            var _this = this;
            // 初始化高度
            $('.commit-order-container').height($(document.body).height() - 70);
            // 实例化swiper
            this.swiper = new Swiper('.swiper-container' , {
                onSlideChangeEnd : function(swiper){
                    var currStep = swiper.activeIndex;
                    switch(currStep){
                        case 1:     // 第2步
                            _this.checkStep2(currStep);
                        break;

                        case 2:     // 第3步
                            _this.checkStep3(currStep);
                        break;

                        case 3:     // 第4步
                            _this.checkStep4(currStep);
                        break;

                        case 4:     // 第5步
                            _this.checkStep5(currStep);
                        break;
                    }
                },
                onInit : function(swiper){
                    // 获取总步骤数
                    $('#totalStep').html(swiper.slides.length);
                    $('#currStep').html(swiper.activeIndex + 1);
                    _this.checkStep1(swiper.activeIndex);
                }
            });
            // 绑定事件
            this.bind();

            // 获取初始化数据
            this.initVal();
        }

        /**
         * 绑定事件
         * @return {[type]} [description]
         */
        Page.prototype.bind = function(){
            var _this = this;
            // 绑定省市区域选择
            $('#idcardAddress,#customerAddress,#customerJobsAddress').bind('click' , function(){
                var that  = $(this);

                var provinceInput = that.siblings('input.province_id');
                var cityInput = that.siblings('input.city_id');
                var countryInput = that.siblings('input.county_id');

                // 初始化默认值
                var provinceValue = _this.localGet(provinceInput.attr('name'));
                var cityValue = _this.localGet(cityInput.attr('name'));
                var countryValue = _this.localGet(countryInput.attr('name'));

                var defaultValue = new Array;

                if(provinceValue && cityValue && countryValue){
                    defaultValue[0] = provinceValue;
                    defaultValue[1] = cityValue;
                    defaultValue[2] = countryValue;
                }else{
                    defaultValue = [24, 275, 2755];
                }

                weui.picker(city, {
                    depth: 3,
                    defaultValue: defaultValue,
                    onChange: function (result) {
                       // console.log(result);
                    },
                    onConfirm: function (result) {
                        var showValue = result[0].label + ' ' + result[1].label + ' ' +result[2].label;
                        that.val(showValue);

                        provinceInput.val(result[0].value);
                        cityInput.val(result[1].value);
                        countryInput.val(result[2].value);

                        _this.localSet(that.attr('id') , showValue);
                        _this.localSet(provinceInput.attr('name') , result[0].value);
                        _this.localSet(cityInput.attr('name') , result[1].value);
                        _this.localSet(countryInput.attr('name') , result[2].value);
                    }
                });
            });

            // 身份证过期时间为永久,自动清除过期时间
            $('input[name=c_customer_id_card_endtime_status]').bind('click' , function(){
                var _this = $(this);
                if(_this.is(':checked')){
                    $('input[name=c_customer_id_card_endtime]').val('9999999999').attr('disabled','disabled');
                    _this.localSet('c_customer_id_card_endtime' , '9999999999');
                }else{
                    $('input[name=c_customer_id_card_endtime]').val('').removeAttr('disabled');
                    _this.localSet('c_customer_id_card_endtime' , '');
                }
            });

            // 时间选择器
            var endtime = _this.localGet('c_customer_id_card_endtime');
                endtime =  endtime ? endtime : '2020-01-01';
            $('input[name=c_customer_id_card_endtime]').calendar({
                value : [endtime],
                onChange : function(p, values, displayValues){
                    $(this).val(values[0]);
                    _this.localSet('c_customer_id_card_endtime' , values[0]);
                }
            });

            // 监听商品类型变化
            $('select[name=g_goods_type]').bind('change' , function(){
                $('input[name=g_goods_type]').val($(this).find('option:selected').attr('value'));
            });

            // 监听数据变动
            $('input').bind('blur' , function(){
                var key = $(this).attr('name');
                var val = $(this).val();
                if(key == 'c_customer_id_card'){
                    $('input[name=c_customer_id_card]').val(val);
                }
                _this.localSet(key , val);
            });

            // 监听选择器变动
            $('select').bind('change' , function(){
                var key = $(this).attr('name');
                var val = $(this).find('option:selected').attr('value');
                _this.localSet(key , val);
            });

            // 监听checkbox变动
            $('input[type=checkbox]').bind('change' , function(){
                var key = $(this).attr('name');
                var val = $(this).is(':checked') ? 1 : 0;
                _this.localSet(key , val);
            });

            // 初始化
            _this.localSet('o_is_auto_pay' , 1);
        }

        // 初始化本地存储数据
        Page.prototype.initVal = function(){
            var data = this.localGetAll();
            for(var key in data){
                if(-1 !== $.inArray(key , this.selects)){
                    if(key == 'g_goods_type'){
                        $("input[name="+key+"]").val(data[key]);
                    }
                    $("select[name="+key+"]").find("option[value='"+data[key]+"']").attr("selected",true); 
                }else if(-1 !== $.inArray(key , this.checkboxs)){
                    if(data[key] == 1){
                        $("input[name="+key+"]").attr('checked' , true);
                    }else{
                        $("input[name="+key+"]").attr('checked' , false);
                    }
                }else if(-1 !== $.inArray(key , this.ids)){
                    $("#"+key).val(data[key]);
                }else{
                    $("input[name="+key+"]").val(data[key]);
                }
            }
        }

        // 验证第一步数据
        Page.prototype.checkStep1 = function(currStep){
            var _this = this;
            // 初始化
            _this.initStep(currStep);
            // 验证数据并提交
            var validator = $('#formStep1').validator({
                btnSubmit: '.nextStep1',
                ajaxPost: true,
                callback : function(res){
                    if(res.status){
                        _this.swiper.slideNext();
                    }else{
                        $.toast(res.message, "text");
                    }
                }
            }).addRule([{
                ele: "select[name=g_goods_type]",
                datatype: "n",
                nullmsg: "请选择商品类型",
                errormsg: "商品类型不合法"
            }, {
                ele: "input[name=g_goods_name]",
                datatype: 's2-20',
                nullmsg: "请输入商品品牌",
                errormsg: "商品品牌长度为2~20之间"
            }, {
                ele: "input[name=g_goods_models]",
                datatype: "s2-20",
                nullmsg: "请输入商品型号",
                errormsg: "商品型号长度为2~20之间"
            }, {
                ele: "input[name=g_goods_serial_no]",
                datatype: "s2-60",
                nullmsg: "请输入商品序列号",
                errormsg: "商品序列号长度为2~60之间"
            }, {
                ele: "input[name=g_goods_price]",
                datatype: "n",
                nullmsg: "请输入商品价格",
                errormsg: "商品价格不合法"
            }, {
                ele: "input[name=g_goods_deposit]",
                datatype: "n",
                nullmsg: "请输入首付金额",
                errormsg: "首付金额不合法"
            }]);
        }

        // 验证第二步数据
        Page.prototype.checkStep2 = function(currStep){
            var _this = this;
            // 初始化
            _this.initStep(currStep);
            // 验证数据并提交
            var validator = $('#formStep2').validator({
                btnSubmit: '.nextStep2',
                ajaxPost: true,
                callback : function(res){
                    if(res.status){
                        _this.swiper.slideNext();
                    }else{
                        $.toast(res.message, "text");
                    }
                }
            }).addRule([{
                ele: "select[name=o_store_id]",
                datatype: "n",
                nullmsg: "请选择商铺",
                errormsg: "选择商铺不合法"
            }, {
                ele: "select[name=o_product_id]",
                datatype: 'n',
                nullmsg: "请选择产品",
                errormsg: "选择产品不合法"
            }, {
                ele: "input[name=o_remark]",
                datatype: "s2-200",
                ignore: "ignore",
                errormsg: "备注信息长度为2~200之间"
            }]);
        }

        // 验证第三步数据
        Page.prototype.checkStep3 = function(currStep){
            var _this = this;
            // 初始化
            _this.initStep(currStep);
            // 验证数据并提交
            var validator = $('#formStep3').validator({
                btnSubmit: '.nextStep3',
                ajaxPost: true,
                callback : function(res){
                    if(res.status){
                        _this.swiper.slideNext();
                    }else{
                        $.toast(res.message, "text");
                    }
                }
            }).addRule([{
                ele: "select[name=c_bank]",
                datatype: "n",
                nullmsg: "请选择开户银行",
                errormsg: "选择开户银行不合法"
            }, {
                ele: "input[name=c_banknum]",
                datatype: 's16-19',
                nullmsg: "请输入银行卡号",
                errormsg: "银行卡号不合法"
            }, {
                ele: "input[name=c_customer_name]",
                datatype: "s1-5",
                nullmsg: "请输入客户姓名",
                errormsg: "客户姓名长度为1~5之间"
            }, {
                ele: "input[name=c_customer_cellphone]",
                datatype: "s11-11",
                nullmsg: "请输入客户手机号",
                errormsg: "客户手机号不合法"
            }, {
                ele: "input[name=c_customer_id_card]",
                datatype: /(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/,
                nullmsg: "请输入身份证号",
                errormsg: "身份证号不合法"
            }, {
                ele: "input[name=c_customer_id_card_endtime]",
                datatype: "*",
                ignore: "ignore",
                nullmsg: "请选择身份证过期时间",
                errormsg: "身份证过期时间不合法"
            },


            ]);
        }

        // 验证第三步数据
        Page.prototype.checkStep4 = function(currStep){
            var _this = this;
            // 初始化
            _this.initStep(currStep);
            // 验证数据并提交
            var validator = $('#formStep4').validator({
                btnSubmit: '.nextStep4',
                ajaxPost: true,
                callback : function(res){
                    if(res.status){
                        _this.swiper.slideNext();
                    }else{
                        $.toast(res.message, "text");
                    }
                }
            }).addRule([{
                ele: "select[name=c_bank]",
                datatype: "n",
                nullmsg: "请选择开户银行",
                errormsg: "选择开户银行不合法"
            }, {
                ele: "input[name=c_banknum]",
                datatype: 's16-19',
                nullmsg: "请输入银行卡号",
                errormsg: "银行卡号不合法"
            }, {
                ele: "input[name=c_customer_name]",
                datatype: "s1-5",
                nullmsg: "请输入客户姓名",
                errormsg: "客户姓名长度为1~5之间"
            }, {
                ele: "input[name=c_customer_cellphone]",
                datatype: "s11-11",
                nullmsg: "请输入客户手机号",
                errormsg: "客户手机号不合法"
            }, {
                ele: "input[name=c_customer_id_card]",
                datatype: /(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/,
                nullmsg: "请输入身份证号",
                errormsg: "身份证号不合法"
            }, {
                ele: "input[name=c_customer_id_card_endtime]",
                datatype: "*",
                ignore: "ignore",
                nullmsg: "请选择身份证过期时间",
                errormsg: "身份证过期时间不合法"
            },


            ]);
        }

        // 验证第三步数据
        Page.prototype.checkStep5 = function(currStep){
            var _this = this;
            // 初始化
            _this.initStep(currStep);
            // 验证数据并提交
            var validator = $('#formStep5').validator({
                btnSubmit: '.nextStep5',
                ajaxPost: true,
                callback : function(res){
                    if(res.status){
                        _this.ajaxCommit();
                        console.log('验证成功');
                        // _this.swiper.slideNext();
                    }else{
                        $.toast(res.message, "text");
                    }
                }
            }).addRule([{
                ele: "select[name=c_bank]",
                datatype: "n",
                nullmsg: "请选择开户银行",
                errormsg: "选择开户银行不合法"
            }, {
                ele: "input[name=c_banknum]",
                datatype: 's16-19',
                nullmsg: "请输入银行卡号",
                errormsg: "银行卡号不合法"
            }, {
                ele: "input[name=c_customer_name]",
                datatype: "s1-5",
                nullmsg: "请输入客户姓名",
                errormsg: "客户姓名长度为1~5之间"
            }, {
                ele: "input[name=c_customer_cellphone]",
                datatype: "s11-11",
                nullmsg: "请输入客户手机号",
                errormsg: "客户手机号不合法"
            }, {
                ele: "input[name=c_customer_id_card]",
                datatype: /(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/,
                nullmsg: "请输入身份证号",
                errormsg: "身份证号不合法"
            }, {
                ele: "input[name=c_customer_id_card_endtime]",
                datatype: "*",
                ignore: "ignore",
                nullmsg: "请选择身份证过期时间",
                errormsg: "身份证过期时间不合法"
            }]);
        }

        // 在每一步之前的操作
        Page.prototype.initStep = function(currStep){
            $('#currStep').html(currStep + 1);
            var removeClass = new Array;
            for (var i = 1; i <= 5; i++) {
                $('.nextStep' + i).unbind('click');
                removeClass.push('nextStep' + i);
            }
            $('#nextStep').removeClass(removeClass.join(' ')).addClass('nextStep' + (currStep + 1));
        }

        // ajax提交数据
        Page.prototype.ajaxCommit = function(){
            var _this = this;
            var data = $.extend(
                    {} , 
                    $('#formStep1').serializeObject() , 
                    $('#formStep2').serializeObject() , 
                    $('#formStep3').serializeObject() , 
                    $('#formStep4').serializeObject() , 
                    $('#formStep5').serializeObject()
                );

            console.log(data);
            $.ajaxPost(this.createOrderUrl , data , function(res){
                if(res.status){
                    $.toast(res.message, function(){
                        _this.localDelAll();
                        window.location = '/';
                    });
                }else{
                    $.toast(res.message, "text");
                }
            });
        }

        Page.prototype.localSet = function(key , val){
            var storage = window.localStorage || window.sessionStorage;
            return storage.setItem(key , val);
        }

        Page.prototype.localGet = function(key){
            var storage = window.localStorage || window.sessionStorage;
            return storage.getItem(key);
        }

        Page.prototype.localDel = function(key){
            var storage = window.localStorage || window.sessionStorage;
            storage.removeItem(key);
        }

        Page.prototype.localDelAll = function(){
            var storage = window.localStorage || window.sessionStorage;
            storage.clear();
        }

        Page.prototype.localGetAll = function(){
            var storage = window.localStorage || window.sessionStorage;
            var key;
            var container = {};
            for(var i = 0 ; i<storage.length ; i++){
                key = storage.key(i);
                container[key] = storage.getItem(key);
            }

            return container;
        }

        // 初始化
        var page = new Page;
        page.init();
    });
</script>
</html>