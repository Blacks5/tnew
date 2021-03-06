<?php

use yii\helpers\Url;

?>

<!--<link rel="stylesheet" href="/statics/css/animate.min.css">-->
<link rel="stylesheet" href="/statics/css/style.min.css">

<div class="">
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox">
                <div class="ibox-content">

                    <!--<div class="row">
                        <div class="col-sm-3">
                            <a href="<? /*= Yii::$app->getUrlManager()->createUrl(['stores/create']) */ ?>" class="btn btn-success">新增商户</a>
                        </div>
                    </div>-->
                    <!--                    <div class="hr-line-dashed"></div>-->
                    <!--                    <span class="text-muted small pull-right">最后更新：<i class="fa fa-clock-o"></i> 2015-09-01 12:00</span>-->
                    <form class="row" method="get" action="">
                        <div class="col-sm-2">
                            <input type="text" name="RepaymentSearch[c_customer_name]" placeholder="客户姓名"
                                   value="<?php echo $sear['c_customer_name']; ?>" class="input form-control">
                        </div>
                        <div class="col-sm-2">
                            <input type="text" name="RepaymentSearch[c_customer_cellphone]"
                                   value="<?php echo $sear['c_customer_cellphone']; ?>" placeholder="客户电话"
                                   class="input form-control">
                        </div>
                        <div class="col-sm-2">
                            <input type="text" name="RepaymentSearch[c_customer_id_card]"
                                   value="<?php echo $sear['c_customer_id_card']; ?>" placeholder="客户身份证号码"
                                   class="input form-control">
                        </div>
                        <div class="col-sm-4">
                            <div class="input-daterange input-group" id="datepicker">
                                <input type="text" class="form-control" name="RepaymentSearch[s_time]"
                                       placeholder="应还款开始时间"
                                       value="<?php echo $sear['s_time'] ? (date('Y-m-d', $sear['s_time'])) : ''; ?>">
                                <span class="input-group-addon ">到</span>
                                <input type="text" class="form-control" name="RepaymentSearch[e_time]"
                                       value="<?php echo $sear['e_time'] ? (date('Y-m-d', $sear['e_time'])) : ''; ?>"
                                       placeholder="应还款结束时间">
                            </div>
                        </div>
                        <div class="col-sm-1">
                            <span class="input-group-btn">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> 搜索</button>
                            </span>
                        </div>
                    </form>

                    <div class="clients-list">
                        <div class="tab-content">
                            <div id="tab-1" class="tab-pane active">
                                <div class="slimScrollDiv" style="position: relative; width: auto; height: 100%;">
                                    <div class="full-height-scroll" style="width: auto; height: 100%;">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover">
                                                <thead>
                                                <tr style="font-size: 18px;">
                                                    <th class="text-danger" colspan="3" style="text-align: center">统计(单位:元)</th>
                                                    <th class="text-danger"><?= $stat_data['r_total_repay']; ?></th>
                                                    <th class="text-danger"><?= $stat_data['r_principal']  ?></th>
                                                    <th class="text-danger"><?= $stat_data['r_interest']  ?></th>
                                                    <th class="text-danger"><?= $stat_data['r_add_service_fee']  ?></th>
                                                    <th class="text-danger"><?= $stat_data['r_free_pack_fee']  ?></th>
                                                    <th class="text-danger"><?= $stat_data['r_finance_mangemant_fee']  ?></th>
                                                    <th class="text-danger"><?= $stat_data['r_customer_management']  ?></th>
                                                    <th class="text-danger"></th>
                                                    <th class="text-danger"></th>
                                                    <th class="text-danger"></th>
                                                    <th colspan="2" class="text-danger"><?= $stat_data['r_overdue_money']  ?>元</th>
                                                </tr>
                                                <tr>
                                                    <th class="client-avatar">订单编号</th>
                                                    <th><a data-toggle="tab" href="#contact-3"
                                                           class="client-link">客户姓名</a></th>
                                                    <th>客户电话</th>
                                                    <th>本月已还总金额</th>
                                                    <th>本金(元)</th>
                                                    <th>利息(元)</th>
                                                    <th>贵宾服务包(元)</th>
                                                    <th>随心包服务费(元)</th>
                                                    <th>财务管理费(元)</th>
                                                    <th>客户管理费(元)</th>
                                                    <th>期数</th>
                                                    <th>还款时间</th>
                                                    <th>逾期天数</th>
                                                    <th>逾期滞纳金(元)</th>
                                                    <th>操作</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                foreach ($model as $_k => $_v) {
                                                    ?>

                                                    <tr>
                                                        <td class="client-avatar"><?= $_v['o_serial_id'] ?></td>
                                                        <td><?= $_v['c_customer_name'] ?></td>
                                                        <td><?= $_v['c_customer_cellphone'] ?></td>
                                                        <td class="client-status"><?= $_v['r_total_repay'] + 0; ?>元</td>
                                                        <td class="client-status"><?= $_v['r_principal']; ?></td>
                                                        <td class="client-status"><?= $_v['r_interest']; ?></td>
                                                        <td class="client-status"><?= $_v['r_add_service_fee']; ?>元</td>
                                                        <td class="client-status"><?= $_v['r_free_pack_fee']; ?></td>
                                                        <td class="client-status"><?= $_v['r_finance_mangemant_fee']; ?>
                                                            元
                                                        </td>
                                                        <td class="client-status"><?= $_v['r_customer_management']; ?>
                                                            元
                                                        </td>
                                                        <td class="client-status"><?= $_v['r_serial_no'] . '/' . $_v['r_serial_total']; ?></td>
                                                        <td class="client-status"><?= date("Y-m-d H:i:s", $_v['r_repay_date']) ?></td>
                                                        <td class="client-status"><?= $_v['r_overdue_day']; ?></td>
                                                        <td class="client-status"><?= $_v['r_overdue_money']; ?></td>
                                                        <td>
                                                            <?php if (Yii::$app->getUser()->can(yii\helpers\Url::toRoute(['borrow/view']))) { ?>
                                                                <a href="<?= Url::toRoute(['borrow/view', 'order_id' => $_v['o_id']]); ?>"
                                                                   class="btn btn-primary btn-xs"><i
                                                                        class="fa fa-folder"></i>
                                                                    详情</a>
                                                            <?php } ?>

                                                            <?php if (Yii::$app->getUser()->can(yii\helpers\Url::toRoute(['repayment/all-repayment-list']), ['order_id' => $_v['o_id']])) { ?>
                                                                <a href="<?= Url::toRoute(['repayment/all-repayment-list', 'order_id' => $_v['o_id']]); ?>"
                                                                   class="btn btn-primary btn-xs"><i
                                                                            class="fa fa-folder"></i>
                                                                    所有期数</a>
                                                            <?php } ?>
                                                        </td>
                                                    </tr>
                                                <?php } ?>

                                                </tbody>
                                            </table>
                                        </div>
                                        <!--分页-->
                                        <div class="f-r">
                                            <?= \yii\widgets\LinkPager::widget([
                                                'pagination' => $pages,
                                                'firstPageLabel' => '首页',
                                                'nextPageLabel' => '下一页',
                                                'prevPageLabel' => '上一页',
                                                'lastPageLabel' => '末页',
                                            ]) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <?= \yii\helpers\Html::cssFile('@web/css/plugins/datapicker/datepicker3.css') ?>
        <?= \yii\helpers\Html::jsFile('@web/js/plugins/datapicker/bootstrap-datepicker.js') ?>
        <?= \yii\helpers\Html::jsFile('@web/js/plugins/layer/layer.min.js') ?>
        <?php
        $this->registerJs('
$(".repay").click(function(env){
    var url = "' . Url::toRoute(['repayment/repay']) . '";
    var r_id = $(env.target).attr("data-value");
    layer.confirm("确定要进行还款操作吗？", {title:"还款操作", icon:3}, function(index){
        var loading = layer.load(4);
        $.ajax({
            url: url,
            type: "get",
            dataType: "json",
            data: {refund_id: r_id},
            success: function (data) {
                if (data.status === 1) {
                    return layer.msg(data.message, {icon: data.status}, function(){$("#" + r_id + "Btn").attr("disabled",true);});
                }else{
                    return layer.msg(data.message, {icon: data.status});
                }
            },
            error: function () {
                layer.alert("噢，我崩溃啦", {title: "系统错误", icon: 5});
            },
            complete: function () {
                layer.close(loading);
            }
    });
    });
    
});
');
        ?>
        <script>
            $('#datepicker').datepicker({
                todayBtn: "linked",
                keyboardNavigation: true,
                forceParse: true,
                autoclose: true,
                format: "yyyy-mm-dd",
                todayHighlight: true
            });
        </script>