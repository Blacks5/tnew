<?php
$deductlog_status = ['INIT'=>'待处理','WITHHOLD_DEALING'=>'代扣处理中','CHECK_NEEDED'=>'待审核','CHECK_REJECT'=>'审核驳回','WITHHOLD_FAIL'=>'代扣失败','SETTLE_SUCCESS'=>'结算成功'];
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
                    <h3 class="center color-orange">代扣记录详情</h3>
                    <div class="hr-line-dashed"></div>
                    <!--代扣记录详情-->
                    <div class="form-group">
                        <div>
                            <label class="col-sm-2 control-label">商户代扣订单号：</label>
                            <div class="col-sm-2">
                                <p class="form-control-static"><?= $model['merchOrderNo']; ?></p>
                            </div>
                        </div>
                        <div>
                            <label class="col-sm-2 control-label">商户签约订单号：</label>
                            <div class="col-sm-2">
                                <p class="form-control-static"><?= $model['merchSignOrderNo']; ?></p>
                            </div>
                        </div>
                        <div>
                            <label class="col-sm-2 control-label">借款人姓名：</label>
                            <div class="col-sm-2">
                                <p class="form-control-static"><?= $model['realName']; ?></p>
                            </div>
                        </div>
                        <div>
                            <label class="col-sm-2 control-label">借款人身份证号：</label>
                            <div class="col-sm-2">
                                <p class="form-control-static"><?= $model['certNo']; ?></p>
                            </div>
                        </div>
                        <div>
                            <label class="col-sm-2 control-label">借款人手机号：</label>
                            <div class="col-sm-2">
                                <p class="form-control-static"><?= $model['mobileNo']; ?></p>
                            </div>
                        </div>
                        <div>
                            <label class="col-sm-2 control-label">扣款卡银行：</label>
                            <div class="col-sm-2">
                                <p class="form-control-static"><?= $model['bankName'] . $model['bankCode']; ?></p>
                            </div>
                        </div>
                        <div>
                            <label class="col-sm-2 control-label">银行卡号：</label>
                            <div class="col-sm-2">
                                <p class="form-control-static"><?= $model['bankCardNo']; ?></p>
                            </div>
                        </div>
                        <div>
                            <label class="col-sm-2 control-label">代发金额：</label>
                            <div class="col-sm-2">
                                <p class="form-control-static"><?= $model['deductAmount']; ?></p>
                            </div>
                        </div>
                        <div>
                            <label class="col-sm-2 control-label">状态：</label>
                            <div class="col-sm-2">
                                <p class="form-control-static"><?= $deductlog_status[$model['status']]; ?></p>
                            </div>
                        </div>
                        <div>
                            <label class="col-sm-2 control-label">实际还款时间：</label>
                            <div class="col-sm-2">
                                <p class="form-control-static"><?= isset($model['realRepayTime']) ? $model['realRepayTime'] : '暂无'; ?></p>
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
