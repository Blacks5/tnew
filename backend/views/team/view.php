<?php
$this->title = $model->t_name;
$this->params['breadcrumbs'][] = ['label' => '团队列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<link href="/css/plugins/datapicker/datepicker3.css" rel="stylesheet">
<script src="/js/plugins/datapicker/bootstrap-datepicker.js"></script>
<?= \yii\helpers\Html::jsFile('@web/js/plugins/layer/layer.min.js') ?>
<div class="ibox float-e-margins">
    <div class="ibox-content">


        <div class="form-horizontal m-t" id="signupForm" novalidate="novalidate" action="" method="post">
            <div class="row">
                <div class="col-sm-2">
                    <div class="input-daterange input-group" id="datepicker">
                        <input type="text" class="form-control" name="start_time" placeholder="开始时间">
                        <span class="input-group-addon ">到</span>
                        <input type="text" class="form-control" name="end_time" placeholder="结束时间">
                    </div>
                </div>
                <div class="col-sm-2">
                                <span class="input-group-btn">
                                    <button id="calTeamYJ" type="submit" class="btn btn-primary"><i
                                                class="fa fa-search"></i> 统计团队业绩</button>
                                </span>
                </div>

            </div>
            <script>
                $('#datepicker').datepicker({
                    todayBtn: "linked",
                    keyboardNavigation: true,
                    forceParse: true,
                    autoclose: true,
                    format: "yyyy-mm-dd",
                    todayHighlight: true
                });


                $("#calTeamYJ").on('click', function () {
                    var index = layer.loading(3);

                    var st = $("input[name='start_time']").val();
                    var et = $("input[name='end_time']").val();
                    var teamid = "<?=$model->t_id;?>";
                    var url = "<?=\yii\helpers\Url::to(['team/cal-yj'])?>";
                    $.getJSON(url, {st: st, et: et, teamid: teamid}, function (res) {
                        if (1 == res.status) {
                            layer.close(index);
                            $("#o_is_add_service_fee").text(res.data.o_is_add_service_fee);
                            $("#o_is_free_pack_fee").text(res.data.o_is_free_pack_fee);
                            $("#total_orders").text(res.data.total_orders+" 笔");
                            $("#success_total_orders").text(res.data.success_total_orders+" 笔");
                            $("#total_borrow_money").text(res.data.total_borrow_money+" 元");
                            $("#calYj").show();
                        } else {
                            layer.error(res.message);
                        }
                    })
                });
            </script>
            <span id="calYj" style="display: none">
                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <strong class="col-sm-2 text-right">个人保证计划捆绑率：</strong>
                    <div class="col-sm-8">
                        <span class="form-control-static label label-success" id="o_is_add_service_fee">1</span>
                    </div>
                </div>
                <div class="form-group">
                    <strong class="col-sm-2 text-right">贵宾服务包捆绑率：</strong>
                    <div class="col-sm-8">
                        <span class="form-control-static label label-success" id="o_is_free_pack_fee">1</span>
                    </div>
                </div>
                <div class="form-group">
                    <strong class="col-sm-2 text-right">总提单：</strong>
                    <div class="col-sm-8">
                        <span class="form-control-static label label-success" id="total_orders">1</span>
                    </div>
                </div>
                <div class="form-group">
                    <strong class="col-sm-2 text-right">成功提单：</strong>
                    <div class="col-sm-8">
                        <span class="form-control-static label label-success" id="success_total_orders">1</span>
                    </div>
                </div>
                <div class="form-group">
                    <strong class="col-sm-2 text-right">总借出金额：</strong>
                    <div class="col-sm-8">
                        <span class="form-control-static label label-success" id="total_borrow_money">1</span>
                    </div>
                </div>
            </span>

            <div class="form-group">
                <strong class="col-sm-2 text-right">团队名：</strong>
                <div class="col-sm-8">
                    <span class="form-control-static label label-warning"><?= $model->t_name; ?></span>
                </div>
            </div>
            <div class="form-group">
                <strong class="col-sm-2 text-right">负责区域：</strong>
                <div class="col-sm-2">
                    <span class="form-control-static label label-info">
                        <?= \common\components\Helper::getAddrName($model->t_province); ?>
                    </span>
                    <span class="form-control-static label label-info">
                        <?= \common\components\Helper::getAddrName($model->t_city); ?>
                    </span>
                    <span class="form-control-static label label-info">
                        <?= \common\components\Helper::getAddrName($model->t_county); ?>
                    </span>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-3 col-sm-offset-2">
                    <!--<a class="btn btn-primary" href="<? /*=Yii::$app->getUrlManager()->createUrl(['team/update', 'id'=>$model->t_id])*/ ?>">编辑</a>-->
                    <a class="btn btn-default"
                       href="<?= Yii::$app->getUrlManager()->createUrl(['team/index']) ?>">返回</a>
                </div>
            </div>

        </div>
    </div>
</div>
