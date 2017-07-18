<?php

$sign_stauts = [
    1=>'回调签约成功',
    2=>'等待回调',
    3=>'接口调用失败',
    4=>'回调处理失败',
    5=>'回调审核驳回',
    6=>'回调签约失败',
    7=>'回调签约处理中',
    8=>'回调待审核'
];
//1回调签约成功 2等待回调 3接口调用失败 4回调处理失败 5回调审核驳回 6回调签约失败 7回调签约处理中 8回调待审核
?>

<link rel="stylesheet" href="/statics/css/style.min.css">


<div class="">

    <div class="row">
        <div class="col-sm-12">
            <div class="ibox">
                <div class="ibox-content">
                    <form class="row" method="get" action="">
                        <div class="col-sm-2">
                            <input type="text" name="o_serial_id" placeholder="订单编号"
                                   value="<?= $o_serial_id; ?>" class="input form-control">
                        </div>
                        <div class="col-sm-2">
                            <input type="text" name="merchOrderNo"
                                   value="<?= $merchOrderNo; ?>" placeholder="商户订单号"
                                   class="input form-control">
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
                                                <tr>
                                                    <th class="client-avatar">ID</th>
                                                    <th class="client-avatar">订单编号</th>
                                                    <th>商户签约订单号</th>
                                                    <th>流水号</th>
                                                    <th>操作人</th>
                                                    <th>状态</th>
                                                    <th>签约时间</th>
                                                    <th>操作</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                foreach ($model as $_k => $_v) {
                                                    ?>
                                                    <tr>
                                                        <td class="client-avatar"><?= $_v['id'] ?></td>
                                                        <td class="client-avatar"><?= $_v['o_serial_id'] ?></td>
                                                        <td><?= $_v['merchOrderNo'] ?></td>
                                                        <td><?= $_v['orderNo'] ?></td>
                                                        <td class="client-status"><?= $_v['operator_id']; ?></td>
                                                        <td class="client-status">
                                                            <?= $sign_stauts[$_v['status']]; ?>
                                                        </td>
                                                        <td class="client-status"><?= date("Y-m-d H:i:s", $_v['created_at']) ?></td>
                                                        <td>
                                                            <?php if (Yii::$app->getUser()->can(yii\helpers\Url::toRoute(['sign-return/view']))) { ?>
                                                                <a href="<?= Yii::$app->getUrlManager()->createUrl(['sign-return/signview', 'o_serial_id' => $_v['o_serial_id']]); ?>"
                                                                   class="btn btn-primary btn-xs">详情</a>
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
        <link href="/css/plugins/datapicker/datepicker3.css" rel="stylesheet">
        <script src="/js/plugins/datapicker/bootstrap-datepicker.js"></script>
