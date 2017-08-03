<?php

?>

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
                            <input type="text" name="OrdersSearch[customer_name]" placeholder="客户姓名"
                                   value="<?php echo $sear['customer_name']; ?>" class="input form-control">
                        </div>
                        <div class="col-sm-2">
                            <input type="text" name="OrdersSearch[customer_cellphone]"
                                   value="<?php echo $sear['customer_cellphone']; ?>" placeholder="客户电话"
                                   class="input form-control">
                        </div>
                        <div class="col-sm-2">
                            <input type="text" name="OrdersSearch[product_name]"
                                   value="<?php echo $sear['product_name']; ?>" placeholder="产品名"
                                   class="input form-control">
                        </div>
                        <div class="col-sm-4">
                            <div class="input-daterange input-group" id="datepicker">
                                <input type="text" class="form-control" name="OrdersSearch[start_time]"
                                       value="<?= $sear['start_time']; ?>" placeholder="开始时间">
                                <span class="input-group-addon ">到</span>
                                <input type="text" class="form-control" name="OrdersSearch[end_time]"
                                       value="<?= $sear['end_time']; ?>" placeholder="结束时间">
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
                                                    <th colspan="2" class="text-danger" style="text-align: center;">统计(单位:元)</th>
                                                    <th class="text-danger" >消费总金额:<?= $stat_data['o_total_price'] + 0; ?></th>
                                                    <th class="text-danger" >贷款总金额:<?= $stat_data['o_total_price'] - $stat_data['o_total_deposit']; ?></th>
                                                </tr>
                                                <tr>
                                                    <th class="client-avatar">订单编号</th>
                                                    <th><a data-toggle="tab" href="#contact-3"
                                                           class="client-link">客户姓名</a></th>
                                                    <th>客户电话</th>
                                                    <th>产品名</th>
                                                    <th class="client-status">期数</th>
                                                    <th>总金额</th>
                                                    <th>贷款金额</th>
                                                    <th>借款次数</th>
                                                    <th>提交时间</th>
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
                                                        <td><?= $_v['p_name'] ?></td>
                                                        <td class="client-status"><?= $_v['p_period']; ?></td>
                                                        <td class="client-status"><?= $_v['o_total_price'] + 0; ?>元</td>
                                                        <td class="client-status"><?= $_v['o_total_price'] - $_v['o_total_deposit']; ?>
                                                            元
                                                        </td>
                                                        <td class="client-status"><?= $_v['c_total_borrow_times']; ?>次
                                                        </td>
                                                        <td class="client-status"><?= date("Y-m-d H:i:s", $_v['o_created_at']) ?></td>
                                                        <td>
                                                            <?php if (Yii::$app->getUser()->can(yii\helpers\Url::toRoute(['borrow/view']))) { ?>
                                                                <a href="<?= Yii::$app->getUrlManager()->createUrl(['borrow/view', 'order_id' => $_v['o_id']]); ?>"
                                                                   class="btn btn-primary btn-xs">详情</a>
                                                            <?php } ?>
                                                            <?php if (Yii::$app->getUser()->can(yii\helpers\Url::toRoute(['borrow/revoke']))) { ?>
                                                                <a class="btn btn-danger btn-xs revoke"
                                                                   data-value="<?= $_v['o_id']; ?>">撤销订单</a>
                                                            <?php } ?>
                                                            <?php if (Yii::$app->getUser()->can(yii\helpers\Url::toRoute(['repayment/all-repayment-list']))) { ?>
                                                                <a class="btn btn-danger btn-xs"
                                                                   href="<?= \yii\helpers\Url::toRoute(['repayment/all-repayment-list', 'order_id' => $_v['o_id']]) ?>">还款计划</a>
                                                            <?php } ?>
                                                            <?php if (Yii::$app->getUser()->can(yii\helpers\Url::toRoute(['loan/loan']))) { ?>
                                                                <?php if($_v['status'] == 1){ ?>
                                                                    <a class="btn btn-danger btn-xs loan" data-value="<?= $_v['o_serial_id'];?>">快捷代发</a>
                                                                <?php }elseif($_v['status'] == 2){ ?>
                                                                    <a class="btn btn-danger btn-xs">处理中</a>
                                                                <?php }elseif($_v['status'] == 3){ ?>
                                                                    <a class="btn btn-danger btn-xs loan" data-value="<?= $_v['o_serial_id'];?>">快捷代发</a>
                                                                <?php }elseif($_v['status'] == 4){ ?>
                                                                    <a class="btn btn-danger btn-xs">已代发</a>
                                                                <?php }else{ ?>
                                                                    <a class="btn btn-danger btn-xs loan" data-value="<?= $_v['o_serial_id']; ?>">快捷代发</a>
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
        <?php
        $this->registerJs('
    $(".revoke").click(function(ev){
        var ev = ev;
        layer.confirm("确定要撤销订单？", {title:"撤销订单", icon:3}, function(index){
            var loading = layer.load();

            var order_id = $(ev.target).attr("data-value");
            var url = "' . \yii\helpers\Url::toRoute(['borrow/revoke']) . '";
            $.ajax({
                url: url,
                type: "get",
                dataType: "json",
                data: {o_id:order_id},
                success: function(data){
                    if(data.status==1){
                        return layer.alert(data.message, {icon: data.status}, function(){return window.location.reload();});
                    }else{
                        return layer.alert(data.message, {icon: data.status});
                    }
                },
                error: function(){
                    layer.alert("噢，我崩溃啦", {title: "系统错误", icon: 5});
                },
                complete: function(){
                    layer.close(loading);
                },
            });
        });
    });
    
    // 放款给商户

    $(".loan").click(function(ev){
        var ev = ev;
        layer.confirm("确定要快捷代发给商户？", {title:"快捷代发", icon:3}, function(index){
            var loading = layer.load();

            var o_serial_id = $(ev.target).attr("data-value");
            var url = "' . \yii\helpers\Url::toRoute(['loan/loan']) . '";
            $.ajax({
                url: url,
                type: "post",
                dataType: "json",
                data: {o_serial_id:o_serial_id ,"' . Yii::$app->getRequest()->csrfParam . '": "' . Yii::$app->getRequest()->getCsrfToken() . '"},
                success: function(data){
                    if(data.status==1){
                        return layer.alert(data.message, {icon: data.status}, function(){return window.location.reload();});
                    }else{
                        return layer.alert(data.message, {icon: data.status});
                    }
                },
                error: function(){
                    layer.alert("噢，我崩溃啦", {title: "系统错误", icon: 5});
                },
                complete: function(){
                    layer.close(loading);
                },
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
             autoclose:true,
             format: "yyyy-mm-dd",
             todayHighlight: true
             });
        </script>