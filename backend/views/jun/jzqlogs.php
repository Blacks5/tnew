<?php

$jzq_stauts = [
    0=>'未签',
    1=>'已签',
    2=>'拒签',
    3=>'已保全'
];
?>

<!--<link rel="stylesheet" href="/statics/css/style.min.css">-->


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
                            <input type="text" name="applyNo"
                                   value="<?= $applyNo; ?>" placeholder="签约编号"
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
                                                    <th>签约编号</th>
                                                    <th>签约人</th>
                                                    <th>签约时间</th>
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
                                                        <td><?= $_v['applyNo'] ?></td>
                                                        <td><?= $_v['fullName'] ?></td>
                                                        <td class="client-status">
                                                            <?php if($_v['signStatus'] == 0){ ?>
                                                                暂未签约
                                                            <?php }else{ ?>
                                                                <?= date("Y-m-d H:i:s", $_v['optTime']/1000); ?>
                                                            <?php }?>
                                                        </td>
                                                        <td class="client-status">
                                                            <?= $jzq_stauts[$_v['signStatus']]; ?>
                                                        </td>
                                                        <td class="client-status"><?= $_v['operator_realname']; ?></td>
                                                        <td class="client-status"><?= date("Y-m-d H:i:s", $_v['created_at']) ?></td>
                                                        <td>
                                                            <?php if (Yii::$app->getUser()->can(yii\helpers\Url::toRoute(['loan/view']))) { ?>
                                                                <?php if($_v['link']){ ?>
                                                                    <a target="_blank" href="<?= $_v['link']; ?>"
                                                                       class="btn btn-primary btn-xs">详情</a>
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
