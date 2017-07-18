<?php
$loanlog_status = ['INIT'=>'待处理','REMITTANCE_DEALING'=>'代发处理中','REMITTANCE_FAIL'=>'代发失败','REMITTANCE_SUCCESS'=>'代发成功','PROFIT_SUCCESS'=>'分润成功'];


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
            <section class="content-header">
                <h2 class="center"><?= $this->title; ?></h2>

                <?php if($model){ ?>
                    <h3 class="center color-orange">回款记录详情</h3>
                    <div class="hr-line-dashed"></div>
                    <!--回款记录详情-->
                    <div class="form-group">
                        <div>
                            <label class="col-sm-2 control-label">订单编号：</label>
                            <div class="col-sm-2">
                                <p class="form-control-static"><?= $y_serial_id; ?></p>
                            </div>
                        </div>
                        <div>
                            <label class="col-sm-2 control-label">代发流水号：</label>
                            <div class="col-sm-2">
                                <p class="form-control-static"><?= $model['contractNo']; ?></p>
                            </div>
                        </div>
                        <div>
                            <label class="col-sm-2 control-label">用户姓名：</label>
                            <div class="col-sm-2">
                                <p class="form-control-static"><?= $model['customerRealName']; ?></p>
                            </div>
                        </div>
                        <div>
                            <label class="col-sm-2 control-label">银行编码：</label>
                            <div class="col-sm-2">
                                <p class="form-control-static"><?= $model['bankCode']; ?></p>
                            </div>
                        </div>
                        <div>
                            <label class="col-sm-2 control-label">银行卡号：</label>
                            <div class="col-sm-2">
                                <p class="form-control-static"><?= $model['bankCardNo']; ?></p>
                            </div>
                        </div>
                        <div>
                            <label class="col-sm-2 control-label">状态：</label>
                            <div class="col-sm-2">
                                <p class="form-control-static"><?= $loanlog_status[$model['status']]; ?></p>
                            </div>
                        </div>
                        <div>
                            <label class="col-sm-2 control-label">代发金额：</label>
                            <div class="col-sm-2">
                                <p class="form-control-static"><?= $model['amount']; ?></p>
                            </div>
                        </div>
                        <div>
                            <label class="col-sm-2 control-label">实际代发金额：</label>
                            <div class="col-sm-2">
                                <p class="form-control-static"><?= $model['realRemittanceAmount']; ?></p>
                            </div>
                        </div>
                        <div>
                            <label class="col-sm-2 control-label">代发手续费：</label>
                            <div class="col-sm-2">
                                <p class="form-control-static"><?= $model['chargeAmount']; ?></p>
                            </div>
                        </div>
                        <div>
                            <label class="col-sm-2 control-label">代发时间：</label>
                            <div class="col-sm-2">
                                <p class="form-control-static"></p>
                            </div>
                        </div>
                    </div>
                <?php }else{ ?>
                    暂无数据
                <?php } ?>
                <div class="form-group">
                    <div class="col-sm-8 col-sm-offset-3">
                        <button class="btn btn-xs btn-default" onclick="window.history.back()">返回上一页</button>
                    </div>
                </div>
        </div>
    </div>
</div>
