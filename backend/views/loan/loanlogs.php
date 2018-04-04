<?php

$loan_stauts = [
  1=>'处理失败',
  2=>'处理中',
  3=>'代发失败',
  4=>'代发成功'
];
?>

<link rel="stylesheet" href="/statics/css/style.min.css">


<div class="">

    <div class="row">
        <div class="col-sm-12">
            <div class="ibox">
                <div class="ibox-content">
                    <form class="row" method="get" action="">
                        <div class="col-sm-2">
                            <input type="text" name="y_serial_id" placeholder="订单编号"
                                   value="<?= $y_serial_id; ?>" class="input form-control">
                        </div>
                        <div class="col-sm-2">
                            <input type="text" name="contractNo"
                                   value="<?= $contractNo; ?>" placeholder="代发流水号"
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
                                                    <th>代发流水号</th>
                                                    <th>代发金额</th>
                                                    <th>实际代发金额</th>
                                                    <th>代发手续费</th>
                                                    <th>操作人</th>
                                                    <th>状态</th>
                                                    <th>代发时间</th>
                                                    <th>操作</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                foreach ($model as $_k => $_v) {
                                                    ?>
                                                    <tr>
                                                        <td class="client-avatar"><?= $_v['id'] ?></td>
                                                        <td class="client-avatar"><?= $_v['y_serial_id'] ?></td>
                                                        <td><?= $_v['contractNo'] ?></td>
                                                        <td><?= $_v['amount'] ?></td>
                                                        <td><?= $_v['realRemittanceAmount'] ?></td>
                                                        <td class="client-status"><?= $_v['chargeAmount']; ?></td>
                                                        <td class="client-status"><?= $_v['y_operator_realname']; ?></td>
                                                        <td class="client-status">
                                                            <?= $loan_stauts[$_v['status']]; ?>
                                                        </td>
                                                        <td class="client-status"><?= date("Y-m-d H:i:s", $_v['created_at']) ?></td>
                                                        <td>
                                                            <?php if (Yii::$app->getUser()->can(yii\helpers\Url::toRoute(['loan/view']))) { ?>
                                                                <?php if($_v['status'] == 4){ ?>
                                                                    <a href="<?= Yii::$app->getUrlManager()->createUrl(['loan/view', 'y_serial_id' => $_v['y_serial_id']]); ?>"
                                                                       class="btn btn-primary btn-xs">详情</a>
                                                                    <a href="<?= Yii::$app->getUrlManager()->createUrl(['loan/voucher', 'order_no' => $_v['contractNo']]) ?>" class="btn btn-danger btn-xs">转账凭证</a>
                                                                <?php }else{ ?>
                                                                    <a class="btn btn-primary btn-xs">处理中</a>
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
        <link href="/css/plugins/datapicker/datepicker3.css" rel="stylesheet">
        <script src="/js/plugins/datapicker/bootstrap-datepicker.js"></script>
