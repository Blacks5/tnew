<?php
use \common\components\Helper;
$all_status = \common\models\Orders::getAllStatus();
$msg = $all_status[$model['o_status']];
$this->title = $model['c_customer_name'] . '借款详情【'. $msg. '】';
?>
<?= \yii\helpers\Html::cssFile('@web/css/style.css') ?>
<style>
    .center{
        text-align: center;
    }
    .color-orange{
        color: orangered;
    }

</style>
<div class="ibox float-e-margins">
    <div class="ibox-content">
        <div class="form-horizontal m-t" id="signupForm" novalidate="novalidate">
            <!--订单信息部分-->
            <section class="content-header">
                <h2 class="center"><?= $this->title; ?></h2>

                <h3 class="center color-orange">订单信息</h3>
                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <div>
                        <label class="col-sm-2 control-label">订单编号：</label>
                        <div class="col-sm-2">
                            <p class="form-control-static"><?= $model['o_serial_id']; ?></p>
                        </div>
                    </div>

                    <div>
                        <label class="col-sm-2 control-label">订单总价格：</label>
                        <div class="col-sm-2">
                            <p class="form-control-static"><?= $model['o_total_price']; ?></p>
                        </div>
                    </div>
                    <div>
                        <label class="col-sm-2 control-label">贷款总金额：</label>
                        <div class="col-sm-2">
                            <p class="form-control-static"><?= $model['o_total_price'] - $model['o_total_deposit']; ?></p>
                        </div>
                    </div>
                    <div>
                        <label class="col-sm-2 control-label">月供：</label>
                        <div class="col-sm-2">
                            <p class="form-control-static"
                               style="color: orangered"><?= round($model['month_repayment'], 2); ?> 元/月</p>
                        </div>
                    </div>

                    <div>
                        <label class="col-sm-2 control-label">个人保障计划：</label>
                        <div class="col-sm-2">
                            <p class="form-control-static"
                               style="color: orangered"><?= $model['o_is_add_service_fee']==1?"是":"否"; ?></p>
                        </div>
                    </div>
                    <div>
                        <label class="col-sm-2 control-label">贵宾服务包：</label>
                        <div class="col-sm-2">
                            <p class="form-control-static"
                               style="color: orangered"><?=$model['o_is_free_pack_fee']==1?"是":"否"; ?></p>
                        </div>
                    </div>
                    <div>
                        <label class="col-sm-2 control-label">是否代扣：</label>
                        <div class="col-sm-2">
                            <p class="form-control-static"
                               style="color: orangered"><?=$model['o_is_auto_pay']==1?"是":"否"; ?></p>
                        </div>
                    </div>

                    <div>
                        <label class="col-sm-2 control-label">备注：</label>
                        <div class="col-sm-2">
                            <p class="form-control-static"><?= $model['o_remark']?:"无"; ?></p>
                        </div>
                    </div>
                    <div>
                        <label class="col-sm-2 control-label">图片：</label>
                        <div class="col-sm-2">
                            <a href="/borrow/showpics?oid=<?= $model['o_id'] ?>"><p class="form-control-static">点击查看</p>
                            </a>
                        </div>
                    </div>
                </div>



                <h3 class="center color-orange">所用产品信息</h3>
                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <div>
                        <label class="col-sm-2 control-label">使用产品：</label>
                        <div class="col-sm-2">
                            <p class="form-control-static"><?= $model['p_name']; ?></p>
                        </div>
                    </div>
                    <div>
                        <label class="col-sm-2 control-label">期数：</label>
                        <div class="col-sm-2">
                            <p class="form-control-static"><?= $model['p_period']; ?></p>
                        </div>
                    </div>
                    <div>
                        <label class="col-sm-2 control-label">月利率(%)：</label>
                        <div class="col-sm-2">
                            <p class="form-control-static"><?= $model['p_month_rate']; ?></p>
                        </div>
                    </div>
                    <div>
                        <label class="col-sm-2 control-label">个人保障计划(元/每月)：</label>
                        <div class="col-sm-2">
                            <p class="form-control-static"><?= $model['p_add_service_fee']; ?></p>
                        </div>
                    </div>
                    <div>
                        <label class="col-sm-2 control-label">贵宾服务包(%)：</label>
                        <div class="col-sm-2">
                            <p class="form-control-static"><?= $model['p_free_pack_fee']; ?></p>
                        </div>
                    </div>
                    <div>
                        <label class="col-sm-2 control-label">财务管理费(%)：</label>
                        <div class="col-sm-2">
                            <p class="form-control-static"><?= $model['p_finance_mangemant_fee']; ?></p>
                        </div>
                    </div>
                    <div>
                        <label class="col-sm-2 control-label">客户管理费(%)：</label>
                        <div class="col-sm-2">
                            <p class="form-control-static"><?= $model['p_customer_management']; ?></p>
                        </div>
                    </div>
                </div>


                <h3 class="center color-orange">客户信息</h3>
                <div class="hr-line-dashed"></div>
                <!--客户信息部分-->
                <div class="form-group">
                    <div>
                        <label class="col-sm-2 control-label">客户姓名：</label>
                        <div class="col-sm-2">
                            <p class="form-control-static"><?= $model['c_customer_name']; ?></p>
                        </div>
                    </div>
                    <div>
                        <label class="col-sm-2 control-label">客户电话：</label>
                        <div class="col-sm-2">
                            <p class="form-control-static">
                                <a href="tel:<?= $model['c_customer_cellphone']; ?>"><?= $model['c_customer_cellphone']; ?></a>
                            </p>
                        </div>
                    </div>
                    <div>
                        <label class="col-sm-2 control-label">客户身份证：</label>
                        <div class="col-sm-2">
                            <p class="form-control-static"><?= $model['c_customer_id_card']; ?></p>
                        </div>
                    </div>
                    <div>
                        <label class="col-sm-2 control-label">户籍地址：</label>
                        <div class="col-sm-2">
                            <p class="form-control-static">
                                <?= Helper::getAddrName($model['c_customer_province']) . '-' .
                                Helper::getAddrName($model['c_customer_city']) . '-' . Helper::getAddrName($model['c_customer_county']) . '-' . $model['c_customer_idcard_detail_addr']; ?></p>
                        </div>
                    </div>
                    <div>
                        <label class="col-sm-2 control-label">性别：</label>
                        <div class="col-sm-2">
                            <p class="form-control-static"><?= $model['c_customer_gender'] == 1 ? '男' : '女'; ?></p>
                        </div>
                    </div>
                    <div>
                        <label class="col-sm-2 control-label">还款银行：</label>
                        <div class="col-sm-2">
                            <p class="form-control-static"><?= Helper::getBankNameById($model['c_bank']); ?></p>
                        </div>
                    </div>
                    <div>
                        <label class="col-sm-2 control-label">还款银行卡号：</label>
                        <div class="col-sm-2">
                            <p class="form-control-static"><?= $model['c_banknum']; ?></p>
                        </div>
                    </div>
                    <div>
                        <label class="col-sm-2 control-label">QQ号：</label>
                        <div class="col-sm-2">
                            <p class="form-control-static"><?= $model['c_customer_qq']; ?></p>
                        </div>
                    </div>
                    <div>
                        <label class="col-sm-2 control-label">微信号：</label>
                        <div class="col-sm-2">
                            <p class="form-control-static"><?= $model['c_customer_wechat']; ?></p>
                        </div>
                    </div>
                    <!--<div>
                            <label class="col-sm-2 control-label">Email：</label>
                            <div class="col-sm-2">
                                <p class="form-control-static"><? /*= $model['c_customer_email']; */ ?></p>
                            </div>
                        </div>-->
                    <div>
                        <label class="col-sm-2 control-label">婚姻状况：</label>
                        <div class="col-sm-2">
                            <p class="form-control-static"><?= Helper::getMaritalStatusString($model['c_family_marital_status']); ?></p>
                        </div>
                    </div>

                    <?php /*已婚才显示这两个*/if(2==$model['c_family_marital_status']){ ?>
                        <div>
                            <label class="col-sm-2 control-label">配偶姓名：</label>
                            <div class="col-sm-2">
                                <p class="form-control-static"><?= $model['c_family_marital_partner_name']; ?></p>
                            </div>
                        </div>
                        <div>
                            <label class="col-sm-2 control-label">配偶电话：</label>
                            <div class="col-sm-2">
                                <p class="form-control-static">
                                    <a href="tel:<?= $model['c_family_marital_partner_cellphone']; ?>"><?= $model['c_family_marital_partner_cellphone']; ?></a>
                                </p>
                            </div>
                        </div>
                    <?php } ?>


                    <div>
                        <label class="col-sm-2 control-label">住房情况：</label>
                        <div class="col-sm-2">
                            <p class="form-control-static"><?= Helper::getHouseInfoString($model['c_family_house_info']); ?></p>
                        </div>
                    </div>
                    <div>
                        <label class="col-sm-2 control-label">亲属：</label>
                        <div class="col-sm-2">
                            <p class="form-control-static"><?= Helper::getKindShipString($model['c_kinship_relation']) . '-' . $model['c_kinship_name'] . '-' . $model['c_kinship_cellphone'] /*. '-' . $model['c_kinship_addr']*/
                                ; ?></p>
                        </div>
                    </div>
                    <div>
                        <label class="col-sm-2 control-label">现居住地址：</label>
                        <div class="col-sm-2">
                            <p class="form-control-static"><?= Helper::getAddrName($model['c_customer_addr_province']) . Helper::getAddrName($model['c_customer_addr_city']) . '-' . Helper::getAddrName($model['c_customer_addr_county']) . '-' . $model['c_customer_addr_detail']; ?></p>
                        </div>
                    </div>
                    <div>
                        <label class="col-sm-2 control-label">工作单位：</label>
                        <div class="col-sm-2">
                            <p class="form-control-static">
                                <?= $model['c_customer_jobs_company'];/*公司名*/ ?><br>
                                <?=Helper::getCompanyIndustryString($model['c_customer_jobs_industry']) ; /*行业*/ ?><br>
                                <?=$model['c_customer_jobs_section'] ; /*部门*/ ?><br>
                                <?=$model['c_customer_jobs_title']; /*职位*/ ?><br>
                                <?=Helper::getCompanyTypeString($model['c_customer_jobs_type']); /*工作行业*/ ?> <br>
                                <a href="tel:<?=$model['c_customer_jobs_phone'];?>"><?=$model['c_customer_jobs_phone']; /*工作座机*/ ?></a>
                            </p>
                        </div>
                    </div>
                    <div>
                        <label class="col-sm-2 control-label">单位地址：</label>
                        <div class="col-sm-2">
                            <p class="form-control-static"><?= Helper::getAddrName($model['c_customer_jobs_province']) . '-' . Helper::getAddrName($model['c_customer_jobs_city']) . '-' . Helper::getAddrName($model['c_customer_jobs_county']) . '-' . $model['c_customer_jobs_detail_addr']; ?></p>
                        </div>
                    </div>

                    <div>
                        <label class="col-sm-2 control-label">其他联系人：</label>
                        <div class="col-sm-2">
                            <p class="form-control-static"><?= Helper::getKindShipString($model['c_other_people_relation']) . '-' . $model['c_other_people_name'] . '-' . $model['c_other_people_cellphone']; ?></p>
                        </div>
                    </div>
                    <div>
                        <label class="col-sm-2 control-label">总借款次数：</label>
                        <div class="col-sm-2">
                            <p class="form-control-static"><?= $model['c_total_borrow_times']; ?></p>
                        </div>
                    </div>
                    <div>
                        <label class="col-sm-2 control-label">总借款金额：</label>
                        <div class="col-sm-2">
                            <p class="form-control-static"><?= $model['c_total_money']; ?></p>
                        </div>
                    </div>
                </div>


                <h3 class="center color-orange">商户信息</h3>
                <div class="hr-line-dashed"></div>
                <!--商户信息-->
                <div class="form-group">
                    <div>
                        <label class="col-sm-2 control-label">商户名：</label>
                        <div class="col-sm-2">
                            <p class="form-control-static"><?= $model['s_name'] . '(' . "{$model['s_owner_phone']}" . ')'; ?></p>
                        </div>
                    </div>
                    <div>
                        <label class="col-sm-2 control-label">业务员：</label>
                        <div class="col-sm-2">
                            <p class="form-control-static"><?= $model['realname'] . '(' . "{$model['cellphone']}" . ')'; ?></p>
                        </div>
                    </div>
                    <div>
                        <label class="col-sm-2 control-label">审核人员：</label>
                        <div class="col-sm-2">
                            <p class="form-control-static"><?= $model['o_operator_realname'] ; ?></p>
                        </div>
                    </div>
                </div>






                <h3 class="center color-orange">商品信息</h3>
                <div class="hr-line-dashed"></div>

                <!--商品信息-->
                <?php foreach ($goods_data as $v) { ?>
                    <div class="form-group">
                        <div>
                            <label class="col-sm-2 control-label">商品类型：</label>
                            <div class="col-sm-2">
                                <p class="form-control-static"><?= Yii::$app->params['goods_type'][$v['g_goods_type'] - 1]['t_name']; ?></p>
                            </div>
                        </div>
                        <div>
                            <label class="col-sm-2 control-label">商品品牌型号：</label>
                            <div class="col-sm-2">
                                <p class="form-control-static"><?= $v['g_goods_name'] . '(' . "{$v['g_goods_models']}" . ')'; ?></p>
                            </div>
                        </div>

                    </div>
                <?php } ?>


                <?php if ((int)$model['o_status'] === \common\models\Orders::STATUS_WAIT_CHECK) { ?>
                    <div class="form-group">
                        <div class="col-sm-8 col-sm-offset-3">
                            <button class="btn btn-xs btn-default" onclick="window.history.back()">返回上一页</button>
                            <?php if (Yii::$app->getUser()->can(yii\helpers\Url::toRoute(['borrow/verify-pass-first']))) { ?>
                                <button class="btn btn-success verify-first">初审通过</button>
                            <?php } ?>
                            <?php if (Yii::$app->getUser()->can(yii\helpers\Url::toRoute(['borrow/verify-cancel']))) { ?>
                                <button class="btn btn-info cancel">取消订单</button>
                            <?php } ?>
                            <?php if (Yii::$app->getUser()->can(yii\helpers\Url::toRoute(['borrow/verify-refuse']))) { ?>
                                <button class="btn btn-danger refuse">拒绝并拉黑</button>
                            <?php } ?>
                            <?php if (Yii::$app->getUser()->can(yii\helpers\Url::toRoute(['borrow/verify-failpic']))) { ?>
                                <button class="btn btn-danger failpic">照片不合格</button>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>

                <!--终审-->
                <?php if ((int)$model['o_status'] === \common\models\Orders::STATUS_WAIT_CHECK_AGAIN) { ?>
                    <div class="form-group">
                        <div class="col-sm-8 col-sm-offset-3">
                            <button class="btn btn-xs btn-default" onclick="window.history.back()">返回上一页</button>
                            <?php if (Yii::$app->getUser()->can(yii\helpers\Url::toRoute(['borrow/verify-pass']))) { ?>
                                <button class="btn btn-success verify-end">终审放款</button>
                            <?php } ?>
                            <?php if (Yii::$app->getUser()->can(yii\helpers\Url::toRoute(['borrow/verify-cancel']))) { ?>
                                <button class="btn btn-info cancel">取消订单</button>
                            <?php } ?>
                            <?php if (Yii::$app->getUser()->can(yii\helpers\Url::toRoute(['borrow/verify-refuse']))) { ?>
                                <button class="btn btn-danger refuse">拒绝并拉黑</button>
                            <?php } ?>
                            <?php if (Yii::$app->getUser()->can(yii\helpers\Url::toRoute(['borrow/verify-failpic']))) { ?>
                                <button class="btn btn-danger failpic">照片不合格</button>
                            <?php } ?>
                        </div>
                    </div>

                <?php } ?>
        </div>
    </div>
</div>


<!--弹窗内容-->
<style>
    #xx {
        width: 500px;
        height: 200px;
        max-width: 500px;
        max-height: 200px;
    }
</style>
<div class="form-group" id="remark-box" style="display: none">
    <textarea id="xx" name="remark" placeholder="选填"><?= $model['o_operator_remark'] ?></textarea>
</div>
<!--弹窗内容-->
<?php

$this->registerJs('
// 初审通过
$(".verify-first").click(function(){
       var index = layer.open({
        type: 1,
        title:"填写备注",
        area: ["auto", "auto"], //宽高
        content:$("#remark-box"),
        btn: ["确认", "取消"],
        btn1:function(){
            var loading = layer.load(4);
            var remark = $("#xx").val();
            $.ajax({
                url: "' . \yii\helpers\Url::toRoute(['borrow/verify-pass-first', 'order_id' => $model['o_id']]) . '",
                type: "post",
                dataType: "json",
                data: {remark: remark, "' . Yii::$app->getRequest()->csrfParam . '": "' . Yii::$app->getRequest()->getCsrfToken() . '"},
                success: function (data) {
                    if (data.status === 1) {
                        return layer.alert(data.message, {icon: data.status}, function(){return window.location.reload();});
                    }else{
                        return layer.alert(data.message, {icon: data.status});
                    }
                },
                error: function () {
                    layer.alert("噢，我崩溃啦", {title: "系统错误", icon: 5});
                },
                complete: function () {
                    layer.close(loading);
                },
            });
        },
        btn2:function(){
            layer.close(index);
        },
    }) 
});

// 终审放款
$(".verify-end").click(function(){
       var index = layer.open({
        type: 1,
        title:"填写备注",
        area: ["auto", "auto"], //宽高
        content:$("#remark-box"),
        btn: ["确认", "取消"],
        btn1:function(){
            var loading = layer.load(4);
            var remark = $("#xx").val();
            $.ajax({
                url: "' . \yii\helpers\Url::toRoute(['borrow/verify-pass', 'order_id' => $model['o_id']]) . '",
                type: "post",
                dataType: "json",
                data: {remark: remark, "' . Yii::$app->getRequest()->csrfParam . '": "' . Yii::$app->getRequest()->getCsrfToken() . '"},
                success: function (data) {
                    if (data.status === 1) {
                        return layer.alert(data.message, {icon: data.status}, function(){
                            return window.location.href = "' . \yii\helpers\Url::toRoute(['borrow/list-wait-verify']) . '";
                        });
                    }else{
                        return layer.alert(data.message, {icon: data.status});
                    }
                },
                error: function () {
                    layer.alert("噢，我崩溃啦", {title: "系统错误", icon: 5});
                },
                complete: function () {
                    layer.close(loading);
                },
            });
        },
        btn2:function(){
            layer.close(index);
        },
    }) 
});

// 取消
$(".cancel").click(function(){
       var index = layer.open({
        type: 1,
        title:"填写备注",
        area: ["auto", "auto"], //宽高
        content:$("#remark-box"),
        btn: ["确认", "取消"],
        btn1:function(){
            var loading = layer.load(4);
            var remark = $("#xx").val();
            $.ajax({
                url: "' . \yii\helpers\Url::toRoute(['borrow/verify-cancel', 'order_id' => $model['o_id']]) . '",
                type: "post",
                dataType: "json",
                data: {remark: remark, "' . Yii::$app->getRequest()->csrfParam . '": "' . Yii::$app->getRequest()->getCsrfToken() . '"},
                success: function (data) {
                    if (data.status === 1) {
                        return layer.alert(data.message, {icon: data.status}, function(){return window.location.reload();});
                    }else{
                        return layer.alert(data.message, {icon: data.status});
                    }
                },
                error: function () {
                    layer.alert("噢，我崩溃啦", {title: "系统错误", icon: 5});
                },
                complete: function () {
                    layer.close(loading);
                },
            });
        },
        btn2:function(){
            layer.close(index);
        },
    }) 
});

// 拒绝
$(".refuse").click(function(){
       var index = layer.open({
        type: 1,
        title:"填写备注",
        area: ["auto", "auto"], //宽高
        content:$("#remark-box"),
        btn: ["确认", "取消"],
        btn1:function(){
            var loading = layer.load(4);
            var remark = $("#xx").val();
            $.ajax({
                url: "' . \yii\helpers\Url::toRoute(['borrow/verify-refuse', 'order_id' => $model['o_id']]) . '",
                type: "post",
                dataType: "json",
                data: {remark: remark, "' . Yii::$app->getRequest()->csrfParam . '": "' . Yii::$app->getRequest()->getCsrfToken() . '"},
                success: function (data) {
                    if (data.status === 1) {
                        return layer.alert(data.message, {icon: data.status}, function(){return window.location.reload();});
                    }else{
                        return layer.alert(data.message, {icon: data.status});
                    }
                },
                error: function () {
                    layer.alert("噢，我崩溃啦", {title: "系统错误", icon: 5});
                },
                complete: function () {
                    layer.close(loading);
                },
            });
        },
        btn2:function(){
            layer.close(index);
        },
    }) 
});

// 照片不合格
$(".failpic").click(function(){
       var index = layer.open({
        type: 1,
        title:"填写备注",
        area: ["auto", "auto"], //宽高
        content:$("#remark-box"),
        btn: ["确认", "取消"],
        btn1:function(){
            var loading = layer.load(4);
            var remark = $("#xx").val();
            $.ajax({
                url: "' . \yii\helpers\Url::toRoute(['borrow/verify-failpic', 'order_id' => $model['o_id']]) . '",
                type: "post",
                dataType: "json",
                data: {remark: remark, "' . Yii::$app->getRequest()->csrfParam . '": "' . Yii::$app->getRequest()->getCsrfToken() . '"},
                success: function (data) {
                    if (data.status === 1) {
                        return layer.alert(data.message, {icon: data.status}, function(){return window.location.reload();});
                    }else{
                        return layer.alert(data.message, {icon: data.status});
                    }
                },
                error: function () {
                    layer.alert("噢，我崩溃啦", {title: "系统错误", icon: 5});
                },
                complete: function () {
                    layer.close(loading);
                },
            });
        },
        btn2:function(){
            layer.close(index);
        },
    }) 
});
');
?>
<?= \yii\helpers\Html::jsFile('@web/js/plugins/layer/layer.min.js') ?>

