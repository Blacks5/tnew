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
    <title>订单提交</title>
</head>
<body>
<section class="ui-container" style="margin-bottom: 60px;">
    <form class="demo-item" id="form">
        <!--商品信息-->
        <div class="commit-order-step product-info">
            <div class="weui-cells__title">商品信息</div>
            <div class="weui-cells weui-cells_form">

                <div class="weui-cell weui-cell_select weui-cell_select-after">
                    <div class="weui-cell__hd">
                        <label for="" class="weui-label" >商品类型</label>
                    </div>
                    <div class="weui-cell__bd">
                        <select class="weui-select" name="g_goods_type" id="g_goods_type" onchange="saveDate.getProduct(this.value);">
                            <?php foreach ($data['goods_type'] as $k=>$v){ ?>
                                <option value="<?= $v['t_id']; ?>"><?= $v['t_name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="weui-cell">
                    <div class="weui-cell__hd"><label class="weui-label">商品品牌</label></div>
                    <div class="weui-cell__bd">
                        <input class="weui-input" name="g_goods_name" id="g_goods_name" type="text" placeholder="请输入商品品牌" />
                    </div>
                </div>
                <div class="weui-cell weui-cell_warn warning-div"></div>
                <div class="weui-cell">
                    <div class="weui-cell__hd"><label class="weui-label">商品型号</label></div>
                    <div class="weui-cell__bd">
                        <input class="weui-input" name="g_goods_models" id="g_goods_models" type="text" placeholder="请输入商品型号"/>
                    </div>
                </div>
                <div class="weui-cell weui-cell_warn warning-div"></div>
                <div class="weui-cell">
                    <div class="weui-cell__hd"><label class="weui-label">商品价格</label></div>
                    <div class="weui-cell__bd">
                        <input class="weui-input" name="g_goods_price" id="g_goods_price" type="text" placeholder="请输入商品价格"/>
                    </div>
                </div>
                <div class="weui-cell">
                    <div class="weui-cell__hd"><label class="weui-label">首付金额</label></div>
                    <div class="weui-cell__bd">
                        <input class="weui-input" name="g_goods_deposit" id="g_goods_deposit" type="text" placeholder="请输入首付金额"/>
                    </div>
                </div>
            </div>
        </div>

        <!--订单信息-->
        <div class="commit-order-step order-info">
            <div class="weui-cells__title">订单信息</div>
            <div class="weui-cells weui-cells_form">
                <div class="weui-cell weui-cell_select weui-cell_select-after">
                    <div class="weui-cell__hd">
                        <label for="" class="weui-label">商铺</label>
                    </div>
                    <div class="weui-cell__bd">
                        <select class="weui-select" name="o_store_id" id="o_store_id">
                            <?php foreach ($data['stores'] as $k=>$v){ ?>
                                <option value="<?= $v['s_id']; ?>"><?= $v['s_name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="weui-cell weui-cell_select weui-cell_select-after">
                    <div class="weui-cell__hd">
                        <label for="" class="weui-label">产品</label>
                    </div>
                    <div class="weui-cell__bd">
                        <select class="weui-select" name="o_product_id" id="o_product_id">

                        </select>
                    </div>
                </div>
                <div class="weui-cell weui-cell_switch">
                    <div class="weui-cell__bd">自动代扣</div>
                    <div class="weui-cell__ft">
                        <input class="weui-switch" id="o_is_auto_pay" name="o_is_auto_pay" type="checkbox"/>
                    </div>
                </div>
                <div class="weui-cell weui-cell_switch">
                    <div class="weui-cell__bd">贵宾服务包</div>
                    <div class="weui-cell__ft">
                        <input class="weui-switch" id="o_is_free_pack_fee"  name="o_is_free_pack_fee" type="checkbox"/>
                    </div>
                </div>
                <div class="weui-cell weui-cell_switch">
                    <div class="weui-cell__bd">个人保障服务</div>
                    <div class="weui-cell__ft">
                        <input class="weui-switch" id="o_is_add_service_fee" name="o_is_add_service_fee" type="checkbox"/>
                    </div>
                </div>
                <div class="weui-cell">
                    <div class="weui-cell__hd"><label class="weui-label">注释</label></div>
                    <div class="weui-cell__bd">
                        <input class="weui-input" name="o_remark" id="o_remark" type="text" placeholder="请填写注释"/>
                    </div>
                </div>
            </div>
        </div>

        <!--客户基本信息-->
        <div class="commit-order-step customer-info">
            <div class="weui-cells__title">客户基本信息</div>
            <div class="weui-cells weui-cells_form">
                <div class="weui-cell weui-cell_select weui-cell_select-after">
                    <div class="weui-cell__hd">
                        <label for="" class="weui-label">开户银行</label>
                    </div>
                    <div class="weui-cell__bd">
                        <select class="weui-select" name="c_bank" id="c_bank">
                            <?php foreach ($data['bank_list'] as $k=>$v){ ?>
                                <option value="<?= $v['bank_id']; ?>"><?= $v['bank_name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="weui-cell">
                    <div class="weui-cell__hd"><label class="weui-label">银行卡号</label></div>
                    <div class="weui-cell__bd">
                        <input class="weui-input" name="c_banknum" id="c_banknum" type="number" pattern="[0-9]*" placeholder="请输入银行卡号"/>
                    </div>
                </div>
                <div class="weui-cell">
                    <div class="weui-cell__hd"><label class="weui-label">客户姓名</label></div>
                    <div class="weui-cell__bd">
                        <input class="weui-input" name="c_customer_name" id="c_customer_name" type="text" placeholder="请填写客户姓名"/>
                    </div>
                </div>
                <div class="weui-cell">
                    <div class="weui-cell__hd"><label class="weui-label">客户手机号</label></div>
                    <div class="weui-cell__bd">
                        <input class="weui-input" name="c_customer_cellphone" id="c_customer_cellphone" type="number" pattern="[0-9]*" placeholder="请输入客户手机号"/>
                    </div>
                </div>
                <div class="weui-cell">
                    <div class="weui-cell__hd"><label class="weui-label">客户身份证号</label></div>
                    <div class="weui-cell__bd">
                        <input class="weui-input" name="c_customer_id_card" id="c_customer_id_card" type="text" placeholder="请输入身份证号"/>
                    </div>
                </div>
                <div class="weui-cell">
                    <div class="weui-cell__hd"><label class="weui-label">身份证过期时间</label></div>
                    <div class="weui-cell__bd">
                        <input class="weui-input" id="dateIDendtime" name="c_customer_id_card_endtime" type="text" placeholder="请点击选择时间"/>
                    </div>
                </div>

                <div class="weui-cell weui-cell_switch">
                    <div class="weui-cell__bd">身份证过期时间是否永久</div>
                    <div class="weui-cell__ft">
                        <input class="weui-switch" type="checkbox" name="c_customer_id_card_endtime_status" id="IDperpetual" />
                    </div>
                </div>
                <div class="weui-cell">
                    <div class="weui-cell__hd"><label class="weui-label">客户身份证地址</label></div>
                    <div class="weui-cell__bd">
                        <input class="weui-input" id="cascadePickerBtn" name="idcard_addr" type="text" placeholder="请选择省市区"/>
                        <input type="hidden" class="province_id" id="c_customer_province" value="" />
                        <input type="hidden" class="city_id" id="c_customer_city" value="" />
                        <input type="hidden" class="country_id" id="c_customer_county" value="" />
                    </div>
                </div>
                <div class="weui-cell">
                    <div class="weui-cell__hd"><label class="weui-label">身份证详细地址</label></div>
                    <div class="weui-cell__bd">
                        <input class="weui-input" name="c_customer_idcard_detail_addr" id="c_customer_idcard_detail_addr" type="text" placeholder="请输入详细地址"/>
                    </div>
                </div>
                <div class="weui-cell weui-cell_select weui-cell_select-after">
                    <div class="weui-cell__hd">
                        <label for="" class="weui-label">客户性别</label>
                    </div>
                    <div class="weui-cell__bd">
                        <select class="weui-select" name="c_customer_gender" id="c_customer_gender">
                            <option value="1">男</option>
                            <option value="0">女</option>
                        </select>
                    </div>
                </div>
                <div class="weui-cell">
                    <div class="weui-cell__hd"><label class="weui-label">QQ</label></div>
                    <div class="weui-cell__bd">
                        <input class="weui-input" name="c_customer_qq" id="c_customer_qq" type="text" placeholder="请输入QQ号码"/>
                    </div>
                </div>
                <div class="weui-cell">
                    <div class="weui-cell__hd"><label class="weui-label">微信</label></div>
                    <div class="weui-cell__bd">
                        <input class="weui-input" name="c_customer_wechat" id="c_customer_wechat" type="text" placeholder="请输入微信号"/>
                    </div>
                </div>
                <div class="weui-cell weui-cell_select weui-cell_select-after">
                    <div class="weui-cell__hd">
                        <label for="" class="weui-label">婚姻状况</label>
                    </div>
                    <div class="weui-cell__bd">
                        <select class="weui-select" name="c_family_marital_status">
                            <?php foreach ($data['marital_status'] as $k=>$v){ ?>
                                <option value="<?= $v['marital_id']; ?>"><?= $v['marital_str']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="weui-cell">
                    <div class="weui-cell__hd"><label class="weui-label">配偶姓名</label></div>
                    <div class="weui-cell__bd">
                        <input class="weui-input" name="c_family_marital_partner_name" id="c_family_marital_partner_name" type="text" placeholder="请输入配偶姓名"/>
                    </div>
                </div>
                <div class="weui-cell">
                    <div class="weui-cell__hd"><label class="weui-label">配偶电话</label></div>
                    <div class="weui-cell__bd">
                        <input class="weui-input" name="c_family_marital_partner_cellphone" id="c_family_marital_partner_cellphone" type="text" placeholder="请输入配偶电话"/>
                    </div>
                </div>
                <div class="weui-cell weui-cell_select weui-cell_select-after">
                    <div class="weui-cell__hd">
                        <label for="" class="weui-label">住房情况</label>
                    </div>
                    <div class="weui-cell__bd">
                        <select class="weui-select" name="c_family_house_info" id="c_family_house_info">
                            <?php foreach ($data['house_info'] as $k=>$v){ ?>
                                <option value="<?= $v['house_info_id']; ?>"><?= $v['house_info_str']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="weui-cell">
                    <div class="weui-cell__hd"><label class="weui-label">个人月收入</label></div>
                    <div class="weui-cell__bd">
                        <input class="weui-input" name="c_family_income" id="c_family_income" type="text" placeholder="请输入个人月收入"/>
                    </div>
                </div>
                <div class="weui-cell">
                    <div class="weui-cell__hd"><label class="weui-label">亲属姓名</label></div>
                    <div class="weui-cell__bd">
                        <input class="weui-input" name="c_kinship_name" id="c_kinship_name" type="text" placeholder="请输入亲属姓名"/>
                    </div>
                </div>
                <div class="weui-cell weui-cell_select weui-cell_select-after">
                    <div class="weui-cell__hd">
                        <label for="" class="weui-label">亲属关系</label>
                    </div>
                    <div class="weui-cell__bd">
                        <select class="weui-select" name="c_kinship_relation" id="c_kinship_relation">
                            <?php foreach ($data['kinship'] as $k=>$v){ ?>
                                <option value="<?= $v['kinship_id']; ?>"><?= $v['kinship_str']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="weui-cell">
                    <div class="weui-cell__hd"><label class="weui-label">亲属电话</label></div>
                    <div class="weui-cell__bd">
                        <input class="weui-input" name="c_kinship_cellphone" id="c_kinship_cellphone" type="text" placeholder="请输入亲属电话"/>
                    </div>
                </div>
                <div class="weui-cell">
                    <div class="weui-cell__hd"><label class="weui-label">客户现居地</label></div>
                    <div class="weui-cell__bd">
                        <input class="weui-input" id="cascadePickerBtn_2" type="text" placeholder="请选择省市区"/>
                        <input type="hidden" class="province_id" id="c_customer_addr_province" value="" />
                        <input type="hidden" class="city_id" id="c_customer_addr_city" value="" />
                        <input type="hidden" class="country_id" id="c_customer_addr_county" value="" />
                    </div>
                </div>
                <div class="weui-cell">
                    <div class="weui-cell__hd"><label class="weui-label">详细地址</label></div>
                    <div class="weui-cell__bd">
                        <input class="weui-input" name="c_customer_addr_detail" id="c_customer_addr_detail" type="text" placeholder="请输入客户现居地详细地址"/>
                    </div>
                </div>
            </div>
        </div>

        <!--客户单位信息-->
        <div class="commit-order-step customer-jobs-info">
            <div class="weui-cells__title">客户单位信息</div>
            <div class="weui-cells weui-cells_form">
                <div class="weui-cell">
                    <div class="weui-cell__hd"><label class="weui-label">单位名称</label></div>
                    <div class="weui-cell__bd">
                        <input class="weui-input" name="c_customer_jobs_company" id="c_customer_jobs_company" type="text" placeholder="请输入单位名称"/>
                    </div>
                </div>
                <div class="weui-cell weui-cell_select weui-cell_select-after">
                    <div class="weui-cell__hd">
                        <label for="" class="weui-label">所属行业</label>
                    </div>
                    <div class="weui-cell__bd">
                        <select class="weui-select" name="c_customer_jobs_industry" id="c_customer_jobs_industry">
                            <?php foreach ($data['company_kind'] as $k=>$v){ ?>
                                <option value="<?= $v['company_kind_id']; ?>"><?= $v['company_kind_name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="weui-cell weui-cell_select weui-cell_select-after">
                    <div class="weui-cell__hd">
                        <label for="" class="weui-label">公司性质</label>
                    </div>
                    <div class="weui-cell__bd">
                        <select class="weui-select" name="c_customer_jobs_type" id="c_customer_jobs_type">
                            <?php foreach ($data['company_type'] as $k=>$v){ ?>
                                <option value="<?= $v['company_type_id']; ?>"><?= $v['company_type_name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="weui-cell">
                    <div class="weui-cell__hd"><label class="weui-label">所属部门</label></div>
                    <div class="weui-cell__bd">
                        <input class="weui-input" name="c_customer_jobs_section" id="c_customer_jobs_section" type="text" placeholder="请输入所属部门"/>
                    </div>
                </div>
                <div class="weui-cell">
                    <div class="weui-cell__hd"><label class="weui-label">所属职位</label></div>
                    <div class="weui-cell__bd">
                        <input class="weui-input" name="c_customer_jobs_title" id="c_customer_jobs_title" type="text" placeholder="请输入职位"/>
                    </div>
                </div>
                <div class="weui-cell weui-cell_select weui-cell_select-after">
                    <div class="weui-cell__hd">
                        <label for="" class="weui-label">是否购买社保</label>
                    </div>
                    <div class="weui-cell__bd">
                        <select class="weui-select" name="c_customer_jobs_is_shebao" id="c_customer_jobs_is_shebao">
                            <option value="1">是</option>
                            <option value="0">否</option>
                        </select>
                    </div>
                </div>
                <div class="weui-cell">
                    <div class="weui-cell__hd"><label class="weui-label">公司地址</label></div>
                    <div class="weui-cell__bd">
                        <input class="weui-input" id="cascadePickerBtn_3"  type="text" placeholder="请选择省市区"/>
                        <input type="hidden" class="province_id" id="c_customer_jobs_province" value="" />
                        <input type="hidden" class="city_id" id="c_customer_jobs_city" value="" />
                        <input type="hidden" class="country_id" id="c_customer_jobs_county" value="" />
                    </div>
                </div>
                <div class="weui-cell">
                    <div class="weui-cell__hd"><label class="weui-label">公司详细地址</label></div>
                    <div class="weui-cell__bd">
                        <input class="weui-input" name="c_customer_jobs_detail_addr" id="c_customer_jobs_detail_addr" type="text" placeholder="请输入详细地址"/>
                    </div>
                </div>
                <div class="weui-cell">
                    <div class="weui-cell__hd"><label class="weui-label">公司座机</label></div>
                    <div class="weui-cell__bd">
                        <input class="weui-input" name="c_customer_jobs_phone" id="c_customer_jobs_phone" type="text" placeholder="请输入公司座机"/>
                    </div>
                </div>
            </div>
        </div>

        <!--客户其他联系人信息-->
        <div class="commit-order-step customer-others-info">
            <div class="weui-cells__title">客户其他联系人信息</div>
            <div class="weui-cells weui-cells_form">
                <div class="weui-cell weui-cell_select weui-cell_select-after">
                    <div class="weui-cell__hd">
                        <label for="" class="weui-label">其他联系人关系</label>
                    </div>
                    <div class="weui-cell__bd">
                        <select class="weui-select" name="c_other_people_relation" id="c_other_people_relation">
                            <option value="1">同事</option>
                            <option value="2">朋友</option>
                            <option value="3">同学</option>
                        </select>
                    </div>
                </div>
                <div class="weui-cell">
                    <div class="weui-cell__hd"><label class="weui-label">联系人姓名</label></div>
                    <div class="weui-cell__bd">
                        <input class="weui-input" name="c_other_people_name" id="c_other_people_name" type="text" placeholder="请输入联系人姓名"/>
                    </div>
                </div>
                <div class="weui-cell">
                    <div class="weui-cell__hd"><label class="weui-label">联系人电话</label></div>
                    <div class="weui-cell__bd">
                        <input class="weui-input" name="c_other_people_cellphone" id="c_other_people_cellphone" type="text" placeholder="请输入联系人电话"/>
                    </div>
                </div>
            </div>
        </div>
    </form>
</section>
<div class="weui-flex button-fixed">
    <div class="weui-flex__item prev-step" onclick="saveDate.commitOrder();">提交</div>
</div>
<!--<footer class="button-fixed">-->
<!--    <a href="javascript:;" class="" id="commit-order">下一步</a>-->
<!--</footer>-->
<script src="/wechat/js/weui.js"></script>
<script src="/wechat/js/zepto.min.js"></script>
</body>
<script>
    $(function () {
        (function(){

            //初始化数据
            saveDate.initval();
            // 时间选择器
            $('#dateIDendtime').on('click', function () {
                var that = $(this);
                weui.datePicker({
                    start: '2016-12-29',
                    end: '2080-12-29',
                    defaultValue: [2017, 8, 11],
                    onChange: function (result) {
                        //console.log(result);
                    },
                    onConfirm: function (result) {
                        //console.log($(this));
                        var val_str = result[0].value + '-' + result[1].value + '-' +result[2].value;
                        that.val(val_str);
                    }
                });
            });
            //身份证过期时间为永久,自动清除过期时间
            $('#IDperpetual').on('click', function () {
                var that = $(this);
                if(that.is(":checked")){
                    $('#dateIDendtime').val('9999-12-12');
                    $('#dateIDendtime').attr('disabled','disabled');
                }else{
                    $('#dateIDendtime').removeAttr('disabled');
                }
            });
            // 级联选择器
            $('#cascadePickerBtn,#cascadePickerBtn_2,#cascadePickerBtn_3').on('click', function () {
                var that  = $(this);
                weui.picker(<?= $data_json; ?>, {
                    depth: 3,
                    defaultValue: [24, 275, 2755],
                    onChange: function (result) {
                       // console.log(result);
                    },
                    onConfirm: function (result) {
                        //console.log(result);
                        var val_str = result[0].label + ' ' + result[1].label + ' ' +result[2].label;
                        that.val(val_str);
                        that.siblings('input.province_id').val(result[0].value);
                        that.siblings('input.city_id').val(result[1].value);
                        that.siblings('input.country_id').val(result[2].value);
                    },
                    id: 'cascadePicker'
                });
            });
        })(Zepto);
    });


    //失去焦点存储数据
    $("input,select").blur(function () {
        saveDate.savelocal();
        saveDate.savejson();
    });
    var saveDate = {
        //ajax提交订单
        commitOrder:function () {
            this.validateform();
            var goodsjson = JSON.parse(localStorage.getItem("goodsjson"));
            var orderjson = JSON.parse(localStorage.getItem("orderjson"));
            var customerjson = JSON.parse(localStorage.getItem("customerjson"));
            $.ajax({
                type: 'POST',
                url: "<?= Yii::$app->getUrlManager()->createUrl(['order/create-order'])?>",
                data: { goodsjson: goodsjson,orderjson: orderjson,customerjson:customerjson},
                dataType: 'json',
                timeout: 5000,
                success: function(data){
                    if(data.status == 1) {
                        console.log(data);
                    }else{
                        weui.alert(data.message, function () {
                            //console.log('ok')
                        }, {
                            title: '系统提示'
                        });
                    }
                },
                error: function(xhr, type){
                    weui.alert('系统错误', function () {
                        //console.log('ok')
                    }, {
                        title: '系统提示'
                    });
                }
            });
        },
        //初始化
        initval:function () {

            //商品信息
            localStorage.g_goods_type ? $('#g_goods_type').val(localStorage.g_goods_type) : '';
            (localStorage.g_goods_name != undefined) ? $('#g_goods_name').val(localStorage.g_goods_name) : '';
            (localStorage.g_goods_models != undefined) ? $('#g_goods_models').val(localStorage.g_goods_models) : '';
            (localStorage.g_goods_price != undefined) ? $('#g_goods_price').val(localStorage.g_goods_price) : '';
            (localStorage.g_goods_deposit != undefined) ? $('#g_goods_deposit').val(localStorage.g_goods_deposit) : '';

            //订单信息
            (localStorage.o_store_id!= '') ? $('#o_store_id').val(localStorage.o_store_id) : '';
            (localStorage.o_product_id!= '') ? $('#o_product_id').val(localStorage.o_product_id) : '';
            (localStorage.o_is_auto_pay=='true') ? $('#o_is_auto_pay').attr('checked','checked') : '';
            (localStorage.o_is_free_pack_fee=='true')  ? $('#o_is_free_pack_fee').attr('checked','checked') : '';
            (localStorage.o_is_add_service_fee=='true')  ? $('#o_is_add_service_fee').attr('checked','checked') : '';
            (localStorage.o_remark!= undefined) ? $('#o_remark').val(localStorage.o_remark) : '';

            //客户基本信息
            (localStorage.c_bank!= '') ? $('#c_bank').val(localStorage.c_bank) : '';
            (localStorage.c_banknum!= undefined) ? $('#c_banknum').val(localStorage.c_banknum) : '';
            (localStorage.c_customer_name!= undefined) ? $('#c_customer_name').val(localStorage.c_customer_name) : '';
            (localStorage.c_customer_cellphone!= undefined) ? $('#c_customer_cellphone').val(localStorage.c_customer_cellphone) : '';
            (localStorage.c_customer_id_card!= undefined) ? $('#c_customer_id_card').val(localStorage.c_customer_id_card) : '';
            (localStorage.c_customer_id_card_endtime!= undefined) ? $('#dateIDendtime').val(localStorage.c_customer_id_card_endtime) : '';
            (localStorage.c_customer_id_card_endtime_status=='true')  ? $('#c_customer_id_card_endtime_status').attr('checked','checked') : '';
            (localStorage.cascadePickerBtn!= undefined) ? $('#cascadePickerBtn').val(localStorage.cascadePickerBtn) : '';
            (localStorage.c_customer_province!= undefined) ? $('#c_customer_province').val(localStorage.c_customer_province) : '';
            (localStorage.c_customer_city!= undefined) ? $('#c_customer_city').val(localStorage.c_customer_city) : '';
            (localStorage.c_customer_county!= undefined) ? $('#c_customer_county').val(localStorage.c_customer_county) : '';
            (localStorage.c_customer_idcard_detail_addr!= undefined) ? $('#c_customer_idcard_detail_addr').val(localStorage.c_customer_idcard_detail_addr) : '';
            (localStorage.c_customer_gender!= '') ? $('#c_customer_gender').val(localStorage.c_customer_gender) : '';
            (localStorage.c_customer_qq!= undefined) ? $('#c_customer_qq').val(localStorage.c_customer_qq) : '';
            (localStorage.c_customer_wechat!= undefined) ? $('#c_customer_wechat').val(localStorage.c_customer_wechat) : '';
            (localStorage.c_family_marital_status!= '') ? $('#c_family_marital_status').val(localStorage.c_family_marital_status) : '';
            (localStorage.c_family_marital_partner_name!= undefined) ? $('#c_family_marital_partner_name').val(localStorage.c_family_marital_partner_name) : '';
            (localStorage.c_family_marital_partner_cellphone!= undefined) ? $('#c_family_marital_partner_cellphone').val(localStorage.c_family_marital_partner_cellphone) : '';
            (localStorage.c_family_house_info!= '') ? $('#c_family_house_info').val(localStorage.c_family_house_info) : '';
            (localStorage.c_family_income!= undefined) ?  $('#c_family_income').val(localStorage.c_family_income) : '';
            (localStorage.c_kinship_relation!= '') ?  $('#c_kinship_relation').val(localStorage.c_kinship_relation) : '';
            (localStorage.c_kinship_name!= undefined) ? $('#c_kinship_name').val(localStorage.c_kinship_name) : '';
            (localStorage.cascadePickerBtn_2!= undefined) ? $('#cascadePickerBtn_2').val(localStorage.cascadePickerBtn_2) : '';
            (localStorage.c_customer_addr_province!= undefined) ? $('#c_customer_addr_province').val(localStorage.c_customer_addr_province) : '';
            (localStorage.c_customer_addr_city!= undefined) ? $('#c_customer_addr_city').val(localStorage.c_customer_addr_city) : '';
            (localStorage.c_customer_addr_county!= undefined) ? $('#c_customer_addr_county').val(localStorage.c_customer_addr_county) : '';
            (localStorage.c_customer_addr_detail!= undefined) ? $('#c_customer_addr_detail').val(localStorage.c_customer_addr_detail) : '';

            //客户单位信息
            (localStorage.c_customer_jobs_company!= undefined) ? $('#c_customer_jobs_company').val(localStorage.c_customer_jobs_company) : '';
            (localStorage.c_customer_jobs_industry!= undefined) ? $('#c_customer_jobs_industry').val(localStorage.c_customer_jobs_industry) : '';
            (localStorage.c_customer_jobs_type!= undefined)? $('#c_customer_jobs_type').val(localStorage.c_customer_jobs_type) : '';
            (localStorage.c_customer_jobs_section!= undefined) ? $('#c_customer_jobs_section').val(localStorage.c_customer_jobs_section) : '';
            (localStorage.c_customer_jobs_is_shebao!= undefined) ? $('#c_customer_jobs_is_shebao').val(localStorage.c_customer_jobs_is_shebao) : '';
            (localStorage.cascadePickerBtn_3!= undefined) ? $('#cascadePickerBtn_3').val(localStorage.cascadePickerBtn_3) : '';
            (localStorage.c_customer_jobs_province!= undefined) ? $('#c_customer_jobs_province').val(localStorage.c_customer_jobs_province) : '';
            (localStorage.c_customer_jobs_city!= undefined) ? $('#c_customer_jobs_city').val(localStorage.c_customer_jobs_city) : '';
            (localStorage.c_customer_jobs_county!= undefined) ? $('#c_customer_jobs_county').val(localStorage.c_customer_jobs_county) : '';
            (localStorage.c_customer_jobs_detail_addr!= undefined) ? $('#c_customer_jobs_detail_addr').val(localStorage.c_customer_jobs_detail_addr) : '';
            (localStorage.c_customer_jobs_phone!= undefined)? $('#c_customer_jobs_phone').val(localStorage.c_customer_jobs_phone) : '';

            //其他联系人信息
            (localStorage.c_other_people_relation!= undefined) ? $('#c_other_people_relation').val(localStorage.c_other_people_relation) : '';
            (localStorage.c_other_people_name!= undefined) ? $('#c_other_people_name').val(localStorage.c_other_people_name) : '';
            (localStorage.c_other_people_cellphone!= undefined) ? $('#c_other_people_cellphone').val(localStorage.c_other_people_cellphone) : '';
        },
        //存储数据
        savelocal:function(){
            //商品信息
            localStorage.g_goods_type = $('#g_goods_type').val();
            localStorage.g_goods_name = $('#g_goods_name').val();
            localStorage.g_goods_models = $('#g_goods_models').val();
            localStorage.g_goods_price = $('#g_goods_price').val();
            localStorage.g_goods_deposit = $('#g_goods_deposit').val();
            //订单信息
            localStorage.o_store_id = $('#o_store_id').val();
            localStorage.o_product_id = $('#o_product_id').val();
            localStorage.o_is_auto_pay = $('#o_is_auto_pay').is(':checked');
            localStorage.o_is_free_pack_fee = $('#o_is_free_pack_fee').is(':checked');
            localStorage.o_is_add_service_fee = $('#o_is_add_service_fee').is(':checked');
            localStorage.o_remark = $('#o_remark').val();

            //客户基本信息
            localStorage.c_bank = $('#c_bank').val();
            localStorage.c_banknum = $('#c_banknum').val();
            localStorage.c_customer_name = $('#c_customer_name').val();
            localStorage.c_customer_cellphone = $('#c_customer_cellphone').val();
            localStorage.c_customer_id_card = $('#c_customer_id_card').val();
            localStorage.c_customer_id_card_endtime = $('#dateIDendtime').val();
            localStorage.c_customer_id_card_endtime_status = $('#c_customer_id_card_endtime_status').is(':checked');
            localStorage.cascadePickerBtn = $('#cascadePickerBtn').val();
            localStorage.c_customer_province = $('#c_customer_province').val();
            localStorage.c_customer_city = $('#c_customer_city').val();
            localStorage.c_customer_county = $('#c_customer_county').val();
            localStorage.c_customer_idcard_detail_addr = $('#c_customer_idcard_detail_addr').val();
            localStorage.c_customer_gender = $('#c_customer_gender').val();
            localStorage.c_customer_qq = $('#c_customer_qq').val();
            localStorage.c_customer_wechat = $('#c_customer_wechat').val();
            localStorage.c_family_marital_status = $('#c_family_marital_status').val();
            localStorage.c_family_marital_partner_name = $('#c_family_marital_partner_name').val();
            localStorage.c_family_marital_partner_cellphone = $('#c_family_marital_partner_cellphone').val();
            localStorage.c_family_house_info = $('#c_family_house_info').val();
            localStorage.c_family_income = $('#c_family_income').val();
            localStorage.c_kinship_relation = $('#c_kinship_relation').val();
            localStorage.c_kinship_name = $('#c_kinship_name').val();
            localStorage.cascadePickerBtn_2 = $('#cascadePickerBtn_2').val();
            localStorage.c_customer_addr_province = $('#c_customer_addr_province').val();
            localStorage.c_customer_addr_city = $('#c_customer_addr_city').val();
            localStorage.c_customer_addr_county = $('#c_customer_addr_county').val();
            localStorage.c_customer_addr_detail = $('#c_customer_addr_detail').val();

            //客户单位信息
            localStorage.c_customer_jobs_company = $('#c_customer_jobs_company').val();
            localStorage.c_customer_jobs_industry = $('#c_customer_jobs_industry').val();
            localStorage.c_customer_jobs_type = $('#c_customer_jobs_type').val();
            localStorage.c_customer_jobs_section = $('#c_customer_jobs_section').val();
            localStorage.c_customer_jobs_is_shebao = $('#c_customer_jobs_is_shebao').val();
            localStorage.cascadePickerBtn_3 = $('#cascadePickerBtn_3').val();
            localStorage.c_customer_jobs_province = $('#c_customer_jobs_province').val();
            localStorage.c_customer_jobs_city = $('#c_customer_jobs_city').val();
            localStorage.c_customer_jobs_county = $('#c_customer_jobs_county').val();
            localStorage.c_customer_jobs_detail_addr = $('#c_customer_jobs_detail_addr').val();
            localStorage.c_customer_jobs_phone = $('#c_customer_jobs_phone').val();

            //其他联系人信息
            localStorage.c_other_people_relation = $('#c_other_people_relation').val();
            localStorage.c_other_people_name = $('#c_other_people_name').val();
            localStorage.c_other_people_cellphone = $('#c_other_people_cellphone').val();
        },
        savejson:function () {
            var goodsjson = {
                g_goods_type:$('#g_goods_type').val(),
                g_goods_name:$('#g_goods_name').val(),
                g_goods_models:$('#g_goods_models').val(),
                g_goods_price:$('#g_goods_price').val(),
                g_goods_deposit:$('#g_goods_deposit').val()
            };
            goodsjson = JSON.stringify(goodsjson);//将JSON对象转化成字符串
            localStorage.setItem("goodsjson",goodsjson);//用localStorage保存转化好的的字符串

            var orderjson = {
                o_store_id:$('#o_store_id').val(),
                o_product_id:$('#o_product_id').val(),
                o_is_auto_pay:($('#o_is_auto_pay').is(':checked')=='true') ? 1 : 0,
                o_is_free_pack_fee:($('#o_is_free_pack_fee').is(':checked')=='true') ? 1 : 0,
                o_is_add_service_fee:($('#o_is_add_service_fee').is(':checked')=='true') ? 1 : 0,
                o_remark:$('#o_remark').val()
            };
            orderjson = JSON.stringify(orderjson);//将JSON对象转化成字符串
            localStorage.setItem("orderjson",orderjson);//用localStorage保存转化好的的字符串

            var customerjson = {
                c_bank:$('#c_bank').val(),
                c_banknum:$('#c_banknum').val(),
                c_customer_name:$('#c_customer_name').val(),
                c_customer_cellphone:$('#c_customer_cellphone').val(),
                c_customer_id_card:$('#c_customer_id_card').val(),
                c_customer_id_card_endtime:$('#dateIDendtime').val(),
                c_customer_id_card_endtime_status:($('#c_customer_id_card_endtime_status').is(':checked')=='true') ? 1 : 0,
               // cascadePickerBtn:$('#cascadePickerBtn').val(),
                c_customer_province:$('#c_customer_province').val(),
                c_customer_city:$('#c_customer_city').val(),
                c_customer_county:$('#c_customer_county').val(),
                c_customer_idcard_detail_addr:$('#c_customer_idcard_detail_addr').val(),
                c_customer_gender:$('#c_customer_gender').val(),
                c_customer_qq:$('#c_customer_qq').val(),
                c_customer_wechat:$('#c_customer_wechat').val(),
                c_family_marital_status:$('#c_family_marital_status').val(),
                c_family_marital_partner_name:$('#c_family_marital_partner_name').val(),
                c_family_marital_partner_cellphone:$('#c_family_marital_partner_cellphone').val(),
                c_family_house_info:$('#c_family_house_info').val(),
                c_family_income:$('#c_family_income').val(),
                c_kinship_relation:$('#c_kinship_relation').val(),
                c_kinship_name:$('#c_kinship_name').val(),
                //cascadePickerBtn_2:$('#cascadePickerBtn_2').val(),
                c_customer_addr_province:$('#c_customer_addr_province').val(),
                c_customer_addr_city:$('#c_customer_addr_city').val(),
                c_customer_addr_county:$('#c_customer_addr_county').val(),
                c_customer_addr_detail:$('#c_customer_addr_detail').val(),

                //客户单位信息
                c_customer_jobs_company:$('#c_customer_jobs_company').val(),
                c_customer_jobs_industry:$('#c_customer_jobs_industry').val(),
                c_customer_jobs_type:$('#c_customer_jobs_type').val(),
                c_customer_jobs_section:$('#c_customer_jobs_section').val(),
                c_customer_jobs_is_shebao:$('#c_customer_jobs_is_shebao').val(),
                //cascadePickerBtn_3:$('#cascadePickerBtn_3').val(),
                c_customer_jobs_province:$('#c_customer_jobs_province').val(),
                c_customer_jobs_city:$('#c_customer_jobs_city').val(),
                c_customer_jobs_county:$('#c_customer_jobs_county').val(),
                c_customer_jobs_detail_addr:$('#c_customer_jobs_detail_addr').val(),
                c_customer_jobs_phone:$('#c_customer_jobs_phone').val(),

                //其他联系人信息
                c_other_people_relation:$('#c_other_people_relation').val(),
                c_other_people_name:$('#c_other_people_name').val(),
                c_other_people_cellphone:$('#c_other_people_cellphone').val()
            };
            customerjson = JSON.stringify(customerjson);//将JSON对象转化成字符串
            localStorage.setItem("customerjson",customerjson);//用localStorage保存转化好的的字符串
        },
        //验证数据
        validateform:function () {
//            if(!$('#g_goods_name').val()){
//                this.notice_dom($('#g_goods_name'),'商品名称不能为空');
//                return false;
//            }else{
//                this.del_notice_dom($('#g_goods_name'));
//            }
//            if(!$('#g_goods_models').val()){
//                this.notice_dom($('#g_goods_models'),'商品类型不能为空');
//                return false;
//            }else{
//                this.del_notice_dom($('#g_goods_models'));
//            }
        },
        //添加错误提示
        notice_dom:function (dom,text) {
            dom.parents('div.weui-cell').addClass('weui-cell_warn');
            dom.parents('div.weui-cell').append('<div class="weui-cell__ft"><i class="weui-icon-warn"></i></div>');
            dom.parents('div.weui-cell').next("div.warning-div").show().text(text);
        },
        //清楚错误提示
        del_notice_dom:function (dom) {
            if(dom.parents('div.weui-cell').hasClass('weui-cell_warn')){
                dom.parents('div.weui-cell').removeClass('weui-cell_warn');
            }
            dom.parent().siblings('div.weui-cell__ft').remove();
            dom.parents('div.weui-cell').next("div.warning-div").hide();
        },
        //根据商品类型获取商品
        getProduct:function (p_type) {
            $.ajax({
                type: 'GET',
                url: "<?= Yii::$app->getUrlManager()->createUrl(['manage/getproductsbytype'])?>",
                data: { p_type: p_type },
                dataType: 'json',
                timeout: 5000,
                success: function(data){
                    if(data.status == 1) {
                        $('#o_product_id').html(data.data);
                    }else{
                        weui.alert(data.message, function () {
                            //console.log('ok')
                        }, {
                            title: '系统提示'
                        });
                    }
                },
                error: function(xhr, type){
                    weui.alert('系统错误', function () {
                        //console.log('ok')
                    }, {
                        title: '系统提示'
                    });
                }
            });
        }
    }



</script>
</html>