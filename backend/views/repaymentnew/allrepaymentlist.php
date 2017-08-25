<?php

use yii\helpers\Url;

?>

<!--<link rel="stylesheet" href="/statics/css/animate.min.css">-->

<div class="">
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox">
                <div class="ibox-title">
                    <h2><?= $model[0]['c_customer_name']; ?><small>的还款计划</small><div class="pull-right">
                            <button class="btn btn-sm btn-danger" onclick="window.history.back()">返回</button>
                        </div></h2>

                </div>
                <div class="ibox-content">

                    <!--<div class="row">
                        <div class="col-sm-3">
                            <button class="btn btn-xs btn-danger" onclick="window.history.back()">返回</button>
                        </div>
                    </div>-->
                    <!--                    <div class="hr-line-dashed"></div>-->
                                       <!-- <span class="text-muted small pull-right">最后更新：<i class="fa fa-clock-o"></i> 2015-09-01 12:00</span>-->

                    <div class="clients-list">
                        <div class="tab-content">
                            <div id="tab-1" class="tab-pane active">
                                <div class="slimScrollDiv" style="position: relative; width: auto; height: 100%;">
                                    <div class="full-height-scroll" style="width: auto; height: 100%;">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover">
                                                <thead>
                                                <tr>
                                                    <th class="client-avatar">订单编号</th>
                                                    <th><a data-toggle="tab" href="#contact-3"
                                                           class="client-link">客户姓名</a></th>
                                                    <th>客户电话</th>
                                                    <th>本月应还总金额</th>
                                                    <th>本金</th>
                                                    <th>利息</th>
                                                    <th>个人保障计划</th>
                                                    <th>贵宾服务包</th>
                                                    <th>财务管理费</th>
                                                    <th>客户管理费</th>
                                                    <th>期数</th>
                                                    <th>应还款时间</th>
                                                    <th>状态</th>
                                                    <th>逾期天数</th>
                                                    <th>逾期滞纳金</th>
                                                    <th>操作</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                foreach ($model as $_k => $_v) {

                                                    $notice_class = 'btn-danger';
                                                    $notice_msg = '未还';
                                                    if($_v['r_status'] === '10'){
                                                        $notice_class = 'btn-primary';
                                                        $notice_msg = '已还';
                                                    }
                                                    ?>

                                                    <tr>
                                                        <td class="client-avatar"><?= $_v['o_serial_id'] ?></td>
                                                        <td><?= $_v['c_customer_name'] ?></td>
                                                        <td><?= $_v['c_customer_cellphone'] ?></td>
                                                        <td class="client-status"><?= $_v['r_total_repay'] + 0; ?>元</td>
                                                        <td class="client-status"><?= $_v['r_principal']; ?>元</td>
                                                        <td class="client-status"><?= $_v['r_interest']; ?>元</td>
                                                        <td class="client-status"><?= $_v['r_add_service_fee']; ?>元</td>
                                                        <td class="client-status"><?= $_v['r_free_pack_fee']; ?>元</td>
                                                        <td class="client-status"><?= $_v['r_finance_mangemant_fee']; ?>
                                                            元
                                                        </td>
                                                        <td class="client-status"><?= $_v['r_customer_management']; ?>
                                                            元
                                                        </td>
                                                        <td class="client-status"><?= $_v['r_serial_no'] . '/' . $_v['r_serial_total']; ?></td>
                                                        <td class="client-status"><?= date("Y-m-d H:i:s", $_v['r_pre_repay_date']) ?></td>
                                                        <td class="client-status"><span class="btn <?=$notice_class;?> btn-xs"><?= $notice_msg; ?></span></td>
                                                        <td class="client-status"><?= $_v['r_overdue_day']; ?>天</td>
                                                        <td class="client-status"><?= round($_v['r_overdue_money'], 2); ?>元</td>
                                                        <td>
                                                            <?php if (Yii::$app->getUser()->can(yii\helpers\Url::toRoute(['borrow/view']))) { ?>
                                                                <a href="<?= Url::toRoute(['borrow/view', 'order_id' => $_v['o_id']]); ?>"
                                                                   class="btn btn-primary btn-xs"><i
                                                                        class="fa fa-folder"></i>
                                                                    详情</a>
                                                            <?php } ?>

                                                            <?php  if($_v['r_status'] !== '10') { /*未还款才显示*/ ?>
                                                                <?php if (Yii::$app->getUser()->can(yii\helpers\Url::toRoute(['repayment/repay']))) { ?>
                                                                    <button data-value="<?= $_v['r_id'] ?>"
                                                                            class="btn btn-info btn-xs repay"><i
                                                                            class="fa fa-folder"></i>
                                                                        还款
                                                                    </button>
                                                                <?php } ?>
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