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
    <div class="demo-item ">
        <!--商品信息-->
        <div class="weui-cells__title">商品信息</div>
        <div class="weui-cells weui-cells_form">

            <div class="weui-cell weui-cell_select weui-cell_select-after">
                <div class="weui-cell__hd">
                    <label for="" class="weui-label">商品类型</label>
                </div>
                <div class="weui-cell__bd">
                    <select class="weui-select" name="g_goods_type" id="g_goods_type">
                        <option value="1">手机</option>
                        <option value="2">电脑</option>
                        <option value="3">英国</option>
                    </select>
                </div>
            </div>
            <div class="weui-cell">
                <div class="weui-cell__hd"><label class="weui-label">商品品牌</label></div>
                <div class="weui-cell__bd">
                    <input class="weui-input" name="g_goods_name" id="g_goods_name" type="text" placeholder="请输入商品品牌"/>
                </div>
            </div>
            <div class="weui-cell">
                <div class="weui-cell__hd"><label class="weui-label">商品型号</label></div>
                <div class="weui-cell__bd">
                    <input class="weui-input" name="g_goods_models" id="g_goods_models" type="text" placeholder="请输入商品型号"/>
                </div>
            </div>
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

        <!--订单信息-->
        <div class="weui-cells__title">订单信息</div>
        <div class="weui-cells weui-cells_form">
            <div class="weui-cell weui-cell_select weui-cell_select-after">
                <div class="weui-cell__hd">
                    <label for="" class="weui-label">商铺</label>
                </div>
                <div class="weui-cell__bd">
                    <select class="weui-select" name="select2">
                        <option value="1">手机专卖店</option>
                        <option value="2">电脑专卖店</option>
                    </select>
                </div>
            </div>
            <div class="weui-cell weui-cell_select weui-cell_select-after">
                <div class="weui-cell__hd">
                    <label for="" class="weui-label">产品</label>
                </div>
                <div class="weui-cell__bd">
                    <select class="weui-select" name="select2">
                        <option value="1">手机</option>
                        <option value="2">电脑</option>
                    </select>
                </div>
            </div>
            <div class="weui-cell weui-cell_switch">
                <div class="weui-cell__bd">自动代扣</div>
                <div class="weui-cell__ft">
                    <input class="weui-switch" type="checkbox"/>
                </div>
            </div>
            <div class="weui-cell weui-cell_switch">
                <div class="weui-cell__bd">贵宾服务包</div>
                <div class="weui-cell__ft">
                    <input class="weui-switch" type="checkbox"/>
                </div>
            </div>
            <div class="weui-cell weui-cell_switch">
                <div class="weui-cell__bd">个人保障服务</div>
                <div class="weui-cell__ft">
                    <input class="weui-switch" type="checkbox"/>
                </div>
            </div>
            <div class="weui-cell">
                <div class="weui-cell__hd"><label class="weui-label">注释</label></div>
                <div class="weui-cell__bd">
                    <input class="weui-input" name="username" type="text" placeholder="请填写注释"/>
                </div>
            </div>
            <div class="weui-cell">
                <div class="weui-cell__hd"><label class="weui-label">商品价格</label></div>
                <div class="weui-cell__bd">
                    <input class="weui-input" name="username" type="text" placeholder="请输入商品价格"/>
                </div>
            </div>
            <div class="weui-cell">
                <div class="weui-cell__hd"><label class="weui-label">首付金额</label></div>
                <div class="weui-cell__bd">
                    <input class="weui-input" name="username" type="text" placeholder="请输入首付金额"/>
                </div>
            </div>
        </div>

        <!--客户基本信息-->
        <div class="weui-cells__title">客户基本信息</div>
        <div class="weui-cells weui-cells_form">
            <div class="weui-cell">
                <div class="weui-cell__hd"><label class="weui-label">开户银行名称</label></div>
                <div class="weui-cell__bd">
                    <input class="weui-input" name="username" type="text" placeholder="请输入开户银行名称"/>
                </div>
            </div>
            <div class="weui-cell">
                <div class="weui-cell__hd"><label class="weui-label">开户银行卡号</label></div>
                <div class="weui-cell__bd">
                    <input class="weui-input" name="username" type="number" pattern="[0-9]*" placeholder="请输入银行卡号"/>
                </div>
            </div>
            <div class="weui-cell">
                <div class="weui-cell__hd"><label class="weui-label">客户姓名</label></div>
                <div class="weui-cell__bd">
                    <input class="weui-input" name="username" type="text" placeholder="请填写客户姓名"/>
                </div>
            </div>
            <div class="weui-cell">
                <div class="weui-cell__hd"><label class="weui-label">客户手机号</label></div>
                <div class="weui-cell__bd">
                    <input class="weui-input" name="username" type="number" pattern="[0-9]*" placeholder="请输入客户手机号"/>
                </div>
            </div>
            <div class="weui-cell">
                <div class="weui-cell__hd"><label class="weui-label">客户身份证号</label></div>
                <div class="weui-cell__bd">
                    <input class="weui-input" name="username" type="text" placeholder="请输入身份证号"/>
                </div>
            </div>
            <div class="weui-cell">
                <div class="weui-cell__hd"><label class="weui-label">身份证过期时间</label></div>
                <div class="weui-cell__bd">
                    <input class="weui-input" id="dateIDendtime" name="username" type="text" placeholder="请点击选择时间"/>
                </div>
            </div>

            <div class="weui-cell weui-cell_switch">
                <div class="weui-cell__bd">身份证过期时间是否永久</div>
                <div class="weui-cell__ft">
                    <input class="weui-switch" type="checkbox" name="checkbox" id="IDperpetual" />
                </div>
            </div>
            <div class="weui-cell">
                <div class="weui-cell__hd"><label class="weui-label">客户身份证地址</label></div>
                <div class="weui-cell__bd">
                    <input class="weui-input" id="cascadePickerBtn" name="username" type="text" placeholder="请选择省市区"/>
                    <input type="hidden" class="province_id" id="c_customer_province" value="" />
                    <input type="hidden" class="city_id" id="c_customer_city" value="" />
                    <input type="hidden" class="country_id" id="c_customer_county" value="" />
                </div>
            </div>
            <div class="weui-cell">
                <div class="weui-cell__hd"><label class="weui-label">身份证详细地址</label></div>
                <div class="weui-cell__bd">
                    <input class="weui-input" name="username" type="text" placeholder="请输入详细地址"/>
                </div>
            </div>
            <div class="weui-cell weui-cell_select weui-cell_select-after">
                <div class="weui-cell__hd">
                    <label for="" class="weui-label">客户性别</label>
                </div>
                <div class="weui-cell__bd">
                    <select class="weui-select" name="select2">
                        <option value="1">男</option>
                        <option value="2">女</option>
                    </select>
                </div>
            </div>
            <div class="weui-cell">
                <div class="weui-cell__hd"><label class="weui-label">QQ</label></div>
                <div class="weui-cell__bd">
                    <input class="weui-input" name="username" type="text" placeholder="请输入QQ号码"/>
                </div>
            </div>
            <div class="weui-cell">
                <div class="weui-cell__hd"><label class="weui-label">微信</label></div>
                <div class="weui-cell__bd">
                    <input class="weui-input" name="username" type="text" placeholder="请输入微信号"/>
                </div>
            </div>
            <div class="weui-cell weui-cell_select weui-cell_select-after">
                <div class="weui-cell__hd">
                    <label for="" class="weui-label">婚姻状况</label>
                </div>
                <div class="weui-cell__bd">
                    <select class="weui-select" name="select2">
                        <option value="1">已婚</option>
                        <option value="2">未婚</option>
                    </select>
                </div>
            </div>
            <div class="weui-cell">
                <div class="weui-cell__hd"><label class="weui-label">配偶姓名</label></div>
                <div class="weui-cell__bd">
                    <input class="weui-input" name="username" type="text" placeholder="请输入配偶姓名"/>
                </div>
            </div>
            <div class="weui-cell">
                <div class="weui-cell__hd"><label class="weui-label">配偶电话</label></div>
                <div class="weui-cell__bd">
                    <input class="weui-input" name="username" type="text" placeholder="请输入配偶电话"/>
                </div>
            </div>
            <div class="weui-cell weui-cell_select weui-cell_select-after">
                <div class="weui-cell__hd">
                    <label for="" class="weui-label">住房情况</label>
                </div>
                <div class="weui-cell__bd">
                    <select class="weui-select" name="select2">
                        <option value="1">已婚</option>
                        <option value="2">未婚</option>
                    </select>
                </div>
            </div>
            <div class="weui-cell">
                <div class="weui-cell__hd"><label class="weui-label">个人月收入</label></div>
                <div class="weui-cell__bd">
                    <input class="weui-input" name="username" type="text" placeholder="请输入个人月收入"/>
                </div>
            </div>
            <div class="weui-cell">
                <div class="weui-cell__hd"><label class="weui-label">亲属姓名</label></div>
                <div class="weui-cell__bd">
                    <input class="weui-input" name="username" type="text" placeholder="请输入亲属姓名"/>
                </div>
            </div>
            <div class="weui-cell weui-cell_select weui-cell_select-after">
                <div class="weui-cell__hd">
                    <label for="" class="weui-label">亲属关系</label>
                </div>
                <div class="weui-cell__bd">
                    <select class="weui-select" name="select2">
                        <option value="1">已婚</option>
                        <option value="2">未婚</option>
                    </select>
                </div>
            </div>
            <div class="weui-cell">
                <div class="weui-cell__hd"><label class="weui-label">亲属电话</label></div>
                <div class="weui-cell__bd">
                    <input class="weui-input" name="username" type="text" placeholder="请输入亲属电话"/>
                </div>
            </div>
            <div class="weui-cell">
                <div class="weui-cell__hd"><label class="weui-label">客户现居地</label></div>
                <div class="weui-cell__bd">
                    <input class="weui-input" id="cascadePickerBtn_2" name="username" type="text" placeholder="请选择省市区"/>
                    <input type="hidden" class="province_id" id="c_customer_addr_province" value="" />
                    <input type="hidden" class="city_id" id="c_customer_addr_city" value="" />
                    <input type="hidden" class="country_id" id="c_customer_addr_county" value="" />
                </div>
            </div>
            <div class="weui-cell">
                <div class="weui-cell__hd"><label class="weui-label">详细地址</label></div>
                <div class="weui-cell__bd">
                    <input class="weui-input" name="username" type="text" placeholder="请输入客户现居地详细地址"/>
                </div>
            </div>
        </div>

        <!--客户单位信息-->
        <div class="weui-cells__title">客户单位信息</div>
        <div class="weui-cells weui-cells_form">
            <div class="weui-cell">
                <div class="weui-cell__hd"><label class="weui-label">单位名称</label></div>
                <div class="weui-cell__bd">
                    <input class="weui-input" name="username" type="text" placeholder="请输入单位名称"/>
                </div>
            </div>
            <div class="weui-cell weui-cell_select weui-cell_select-after">
                <div class="weui-cell__hd">
                    <label for="" class="weui-label">所属行业</label>
                </div>
                <div class="weui-cell__bd">
                    <select class="weui-select" name="select2">
                        <option value="1">已婚</option>
                        <option value="2">未婚</option>
                    </select>
                </div>
            </div>
            <div class="weui-cell weui-cell_select weui-cell_select-after">
                <div class="weui-cell__hd">
                    <label for="" class="weui-label">公司性质</label>
                </div>
                <div class="weui-cell__bd">
                    <select class="weui-select" name="select2">
                        <option value="1">已婚</option>
                        <option value="2">未婚</option>
                    </select>
                </div>
            </div>
            <div class="weui-cell">
                <div class="weui-cell__hd"><label class="weui-label">所属部门</label></div>
                <div class="weui-cell__bd">
                    <input class="weui-input" name="username" type="text" placeholder="请输入单位名称"/>
                </div>
            </div>
            <div class="weui-cell">
                <div class="weui-cell__hd"><label class="weui-label">所属职位</label></div>
                <div class="weui-cell__bd">
                    <input class="weui-input" name="username" type="text" placeholder="请输入单位名称"/>
                </div>
            </div>
            <div class="weui-cell weui-cell_select weui-cell_select-after">
                <div class="weui-cell__hd">
                    <label for="" class="weui-label">是否购买社保</label>
                </div>
                <div class="weui-cell__bd">
                    <select class="weui-select" name="select2">
                        <option value="1">是</option>
                        <option value="2">否</option>
                    </select>
                </div>
            </div>
            <div class="weui-cell">
                <div class="weui-cell__hd"><label class="weui-label">公司地址</label></div>
                <div class="weui-cell__bd">
                    <input class="weui-input" id="cascadePickerBtn_3" name="username" type="text" placeholder="请选择省市区"/>
                    <input type="hidden" class="province_id" id="c_customer_jobs_province" value="" />
                    <input type="hidden" class="city_id" id="c_customer_jobs_city" value="" />
                    <input type="hidden" class="country_id" id="c_customer_jobs_county" value="" />
                </div>
            </div>
            <div class="weui-cell">
                <div class="weui-cell__hd"><label class="weui-label">公司详细地址</label></div>
                <div class="weui-cell__bd">
                    <input class="weui-input" name="c_customer_jobs_detail_addr" type="text" placeholder="请输入详细地址"/>
                </div>
            </div>
            <div class="weui-cell">
                <div class="weui-cell__hd"><label class="weui-label">公司座机</label></div>
                <div class="weui-cell__bd">
                    <input class="weui-input" name="c_customer_jobs_detail_addr" type="text" placeholder="请输入公司座机"/>
                </div>
            </div>
        </div>

        <!--客户其他联系人信息-->
        <div class="weui-cells__title">客户其他联系人信息</div>
        <div class="weui-cells weui-cells_form">
            <div class="weui-cell weui-cell_select weui-cell_select-after">
                <div class="weui-cell__hd">
                    <label for="" class="weui-label">其他联系人关系</label>
                </div>
                <div class="weui-cell__bd">
                    <select class="weui-select" name="select2">
                        <option value="1">是</option>
                        <option value="2">否</option>
                    </select>
                </div>
            </div>
            <div class="weui-cell">
                <div class="weui-cell__hd"><label class="weui-label">联系人姓名</label></div>
                <div class="weui-cell__bd">
                    <input class="weui-input" name="c_customer_jobs_detail_addr" type="text" placeholder="请输入联系人姓名"/>
                </div>
            </div>
            <div class="weui-cell">
                <div class="weui-cell__hd"><label class="weui-label">联系人电话</label></div>
                <div class="weui-cell__bd">
                    <input class="weui-input" name="c_customer_jobs_detail_addr" type="text" placeholder="请输入联系人电话"/>
                </div>
            </div>
        </div>
    </div>
</section>
<footer class="button-fixed">
    <a href="javascript:;" class="" id="commit-order">提交</a>
</footer>
<script src="/wechat/js/weui.js"></script>
<script src="/wechat/js/zepto.min.js"></script>
</body>
<script>
    $(function () {
        (function(){
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
                    $('#dateIDendtime').val('');
                    $('#dateIDendtime').attr('disabled','disabled');
                }else{
                    $('#dateIDendtime').removeAttr('disabled');
                }
            });
            // 级联选择器
            $('#cascadePickerBtn,#cascadePickerBtn_2').on('click', function () {
                var that  = $(this);
                weui.picker([
                    {
                        label: '广东',
                        value: 0,
                        children: [
                            {
                                label: '广州',
                                value: 0,
                                children: [
                                    {
                                        label: '海珠',
                                        value: 0
                                    }, {
                                        label: '番禺',
                                        value: 1
                                    }
                                ]
                            }, {
                                label: '佛山',
                                value: 1,
                                children: [
                                    {
                                        label: '禅城',
                                        value: 0
                                    }, {
                                        label: '南海',
                                        value: 1
                                    }
                                ]
                            }
                        ]
                    }, {
                        label: '广西',
                        value: 1,
                        children: [
                            {
                                label: '南宁',
                                value: 0,
                                children: [
                                    {
                                        label: '青秀',
                                        value: 0
                                    }, {
                                        label: '兴宁',
                                        value: 1
                                    }
                                ]
                            }, {
                                label: '桂林',
                                value: 1,
                                children: [
                                    {
                                        label: '象山',
                                        value: 0
                                    }, {
                                        label: '秀峰',
                                        value: 1
                                    }
                                ]
                            }
                        ]
                    }
                ], {
                    depth: 3,
                    defaultValue: [0, 1, 1],
                    onChange: function (result) {
                       // console.log(result);
                    },
                    onConfirm: function (result) {
                        console.log(result);
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
</script>
</html>