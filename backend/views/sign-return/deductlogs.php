<?php
$deduct_stauts = [
        0=>'等待异步回调',
        1=>'待处理',
        2=>'代扣处理中',
        3=>'待审核',
        4=>'审核驳回',
        5=>'代扣失败',
        6=>'代扣成功',
        7=>'结算成功',
        8=>'接口调用失败'
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
                            <input type="text" name="o_serial_id" placeholder="订单编号"
                                   value="<?= $o_serial_id; ?>" class="input form-control">
                        </div>
                        <div class="col-sm-2">
                            <input type="text" name="merchOrderNo"
                                   value="<?= $merchOrderNo; ?>" placeholder="商户代扣订单号"
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
                                                    <th>商户代扣订单号</th>
                                                    <th>商户签约订单号</th>
                                                    <th>代扣金额</th>
                                                    <th>状态</th>
                                                    <th>操作人</th>
                                                    <th>创建时间</th>
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
                                                        <td><?= $_v['merchSignOrderNo'] ?></td>
                                                        <td><?= $_v['deductAmount'] ?></td>
                                                        <td class="client-status">
                                                            <?= $deduct_stauts[$_v['status']]; ?>
                                                        </td>
                                                        <td class="client-status"><?= $_v['realname']; ?></td>
                                                        <td class="client-status"><?= date("Y-m-d H:i:s", $_v['created_at']) ?></td>
                                                        <td>
                                                            <?php if (Yii::$app->getUser()->can(yii\helpers\Url::toRoute(['sign-return/deductview']))) { ?>
                                                                <a href="<?= Yii::$app->getUrlManager()->createUrl(['sign-return/deductview', 'o_serial_id' => $_v['o_serial_id']]); ?>"
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
