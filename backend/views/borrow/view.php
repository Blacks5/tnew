<?php
//app\assets\PccAsset::register($this);
app\assets\MainAsset::register($this);
$this->title = $model['c_customer_name'] . '借款订单';
$plist = Yii::$app->getRequest()->get('plist');
$this->params['breadcrumbs'][] = ($plist == 1) ? (['label' => '待审核借款订单', 'url' => ['wait-checklist']]) : (($plist == 2) ? (['label' => '已拒绝借款订单', 'url' => ['refuselist']]) : (['label' => '已通过借款订单', 'url' => ['index']]));
$this->params['breadcrumbs'][] = $this->title;
?>
<link rel="stylesheet" href="/statics/css/style.min.css">
<div class="ibox float-e-margins">
    <div class="ibox-content">
        <div class="form-horizontal m-t" id="signupForm" novalidate="novalidate" action="" method="post">

            <div class="form-group">
                <div>
                    <label class="col-sm-3 control-label">客户姓名：</label>
                    <div class="col-sm-2">
                        <p class="form-control-static"><?= $model['c_customer_name']; ?></p>
                    </div>
                </div>
                <div>
                    <label class="col-sm-3 control-label">客户电话：</label>
                    <div class="col-sm-2">
                        <p class="form-control-static"><?= $model['c_customer_cellphone']; ?></p>
                    </div>
                </div>
            </div>

            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <div>
                    <label class="col-sm-3 control-label">使用产品：</label>
                    <div class="col-sm-2">
                        <p class="form-control-static"><?= $model['p_name']; ?></p>
                    </div>
                </div>
                <div>
                    <label class="col-sm-3 control-label">借款金额：</label>
                    <div class="col-sm-2">
                        <p class="form-control-static"><?= $model['o_total_price']; ?></p>
                    </div>
                </div>
            </div>

            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <div>
                    <label class="col-sm-3 control-label">应还本金：</label>
                    <div class="col-sm-2">
                        <p class="form-control-static"><?= $model['r_principal'] ? $model['r_principal'] : 0; ?></p>
                    </div>
                </div>
                <div>
                    <label class="col-sm-3 control-label">应还利息：</label>
                    <div class="col-sm-2">
                        <p class="form-control-static"><?= $model['r_interest'] ? $model['r_interest'] : 0; ?></p>
                    </div>
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <div>
                    <label class="col-sm-3 control-label">增值服务费：</label>
                    <div class="col-sm-2">
                        <p class="form-control-static"><?= $model['r_add_service_fee'] ? $model['r_add_service_fee'] : 0; ?></p>
                    </div>
                </div>
                <div>
                    <label class="col-sm-3 control-label">随心包服务费：</label>
                    <div class="col-sm-2">
                        <p class="form-control-static"><?= $model['r_free_pack_fee'] ? $model['r_free_pack_fee'] : 0; ?></p>
                    </div>
                </div>
            </div>

            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <div>
                    <label class="col-sm-3 control-label">财务管理费：</label>
                    <div class="col-sm-2">
                        <p class="form-control-static"><?= $model['r_finance_mangemant_fee'] ? $model['r_finance_mangemant_fee'] : 0; ?></p>
                    </div>
                </div>
                <div>
                    <label class="col-sm-3 control-label">客户管理费：</label>
                    <div class="col-sm-2">
                        <p class="form-control-static"><?= $model['r_customer_management'] ? $model['r_customer_management'] : 0; ?></p>
                    </div>
                </div>
            </div>

            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <div>
                    <label class="col-sm-3 control-label">逾期天数：</label>
                    <div class="col-sm-2">
                        <p class="form-control-static"><?= $model['r_overdue_day'] ? $model['r_overdue_day'] : 0; ?></p>
                    </div>
                </div>
                <div>
                    <label class="col-sm-3 control-label">逾期金额：</label>
                    <div class="col-sm-2">
                        <p class="form-control-static"><?= $model['r_overdue_money'] ? $model['r_overdue_money'] : 0; ?></p>
                    </div>
                </div>
            </div>

            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <div>
                    <label class="col-sm-3 control-label">首付金额：</label>
                    <div class="col-sm-2">
                        <p class="form-control-static"><?= $model['o_total_deposit']; ?></p>
                    </div>
                </div>
                <div>
                    <label class="col-sm-3 text-right">订单状态：</label>
                    <div class="col-sm-2">
                        <p class="form-control-static label label-warning"><?= \app\models\Orders::getAllStatus()[$model['o_status']]; ?></p>
                    </div>
                </div>
            </div>


            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-3 control-label">sa注释：</label>
                <div class="col-sm-2">
                    <p class="form-control-static"><?= $model['o_remark']; ?></p>
                </div>
            </div>
            <?php if((int)$model['o_status'] === \app\models\Orders::STATUS_WAIT_CHECK){ ?>
                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <div class="col-sm-8 col-sm-offset-3">
                        <button class="btn btn-success" onclick="verify()">通过</button>
                        <button class="btn btn-info" onclick="verifycancel()">取消</button>
                        <button class="btn btn-danger" onclick="verifyRefuse()">拒绝</button>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
<script src="/statics/plugins/layer/layer.js"></script>
<script>
    /**
     * 取消
     */
    function verifycancel()
    {
        layer.confirm('确认取消该借款订单？',
            {
                icon: 3, btn: ['确定', '取消'],
                btn1: function (index) {
                    var loading = layer.load();
                    $.ajax({
                        url: "<?=Yii::$app->getUrlManager()->createUrl(['borrowlist/verify-cancel', 'order_id' => $model['o_id']])?>",
                        type: 'post',
                        dataType: 'json',
                        success: function (data) {
                            if (data.status == 1) {
                                return layer.msg(data.message, {icon: 2, time: 2000}, function () {
                                    window.location.reload()
                                });
                            }
                            return layer.msg(data.message, {icon: 5});
                        },
                        error: function () {
                            return layer.alert('噢，我崩溃啦', {title: '系统错误', icon: 5});
                        },
                        complete: function () {
                            layer.close(loading);
                        },
                    });
                },
                btn2: function (index) {
                    layer.close(index)
                },
            });
    }
    /**
     * 通过
     */
    function verify() {
        layer.confirm('确认通过该借款订单？',
            {
                icon: 3, btn: ['确定', '取消'],
                btn1: function (index) {
                    var loadind = layer.load();
                    $.ajax({
                        url: "<?=Yii::$app->getUrlManager()->createUrl(['borrowlist/verify-pass', 'order_id' => $model['o_id']])?>",
                        type: 'post',
                        dataType: 'json',
                        data: {_csrf: "<?= Yii::$app->getRequest()->getCsrfToken();?>"},
                        success: function (data) {
                            switch (data.status) {
                                case 0:
                                    return layer.msg(data.message, {icon: 2, time: 2000}, function () {
                                        window.location.reload();
                                    });
                                case 1:
                                    return layer.msg(data.message, {icon: 1, time: 2000}, function () {
                                        window.location.reload();
                                    });
                            }
                        },
                        error: function () {
                            layer.alert('噢，我崩溃啦', {title: '系统错误', icon: 5});
                        },
                        complete: function () {
                            layer.close(loadind);
                        },
                    });
                },
                btn2: function (index) {
                    layer.close(index)
                },
            });
    }
    /**
     * 拒绝
     */
    function verifyRefuse()
    {
        layer.prompt({
            formType: 2,
            title: '请填写拒绝原因'
        }, function(value, index, elem){
            var loading = layer.load();
            $.ajax({
                url: "<?=Yii::$app->getUrlManager()->createUrl(['borrowlist/refuse', 'o_id' => $model['o_id']])?>",
                type: 'post',
                dataType: 'json',
                data: {remark: value, _csrf: "<?= Yii::$app->getRequest()->getCsrfToken();?>"},
                success: function (data) {
                    if(data.status === 0){
                        return layer.alert(data.message, {icon: 2});
                    }
                    if(data.status === 1){
                        return layer.confirm(data.message,
                            {icon: 1, btn: ['继续审核', '确认'],
                                btn1:function(index){
                                    window.location.href = "<?=Yii::$app->getUrlManager()->createUrl(['borrowlist/wait-checklist'])?>";
                                    layer.close(index)
                                },
                                btn2:function(index){
                                    return window.location.reload();
                                },
                            }
                        );
                    }
                },
                error: function () {
                    layer.alert('噢，我崩溃啦', {title:'系统错误', icon:5});
                },
                complete: function () {
                    layer.close(loading);
                },
            });
            layer.close(index);
        });

    }
</script>