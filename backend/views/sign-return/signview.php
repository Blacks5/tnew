<?php
$signlog_status = ['SIGN_SUCCESS'=>'签约成功','SIGN_DEALING'=>'签约处理中','SIGN_FAIL'=>'签约失败','CHECK_REJECT'=>'审核驳回','CHECK_NEEDED'=>'待审核'];
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
                    <h3 class="center color-orange">签约记录详情</h3>
                    <div class="hr-line-dashed"></div>
                    <!--签约记录详情-->
                    <div class="form-group">
                        <div>
                            <label class="col-sm-2 control-label">订单编号：</label>
                            <div class="col-sm-2">
                                <p class="form-control-static"><?= $o_serial_id; ?></p>
                            </div>
                        </div>
                        <div>
                            <label class="col-sm-2 control-label">商户签约合同号：</label>
                            <div class="col-sm-2">
                                <p class="form-control-static"><?= $model['merchContractNo']; ?></p>
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
                            <label class="col-sm-2 control-label">借款人银行卡号：</label>
                            <div class="col-sm-2">
                                <p class="form-control-static"><?= $model['bankCardNo']; ?></p>
                            </div>
                        </div>
                        <div>
                            <label class="col-sm-2 control-label">签约银行卡银行编码：</label>
                            <div class="col-sm-2">
                                <p class="form-control-static"><?= $model['bankCode']; ?></p>
                            </div>
                        </div>
                        <div>
                            <label class="col-sm-2 control-label">签约银行卡银行名称：</label>
                            <div class="col-sm-2">
                                <p class="form-control-static"><?= $model['bankName']; ?></p>
                            </div>
                        </div>
                        <div>
                            <label class="col-sm-2 control-label">签约银行卡银行卡类型：</label>
                            <div class="col-sm-2">
                                <p class="form-control-static"><?= $model['bankCardType']; ?></p>
                            </div>
                        </div>
                        <div>
                            <label class="col-sm-2 control-label">借款人手机号：</label>
                            <div class="col-sm-2">
                                <p class="form-control-static"><?= $model['mobileNo']; ?></p>
                            </div>
                        </div>
                        <div>
                            <label class="col-sm-2 control-label">产品名称：</label>
                            <div class="col-sm-2">
                                <p class="form-control-static"><?= $model['productName']; ?></p>
                            </div>
                        </div>
                        <div>
                            <label class="col-sm-2 control-label">借款金额：</label>
                            <div class="col-sm-2">
                                <p class="form-control-static"><?= $model['loanAmount']; ?></p>
                            </div>
                        </div>
                        <div>
                            <label class="col-sm-2 control-label">应还总金额：</label>
                            <div class="col-sm-2">
                                <p class="form-control-static"><?= $model['totalRepayAmount']; ?></p>
                            </div>
                        </div>
                        <div>
                            <label class="col-sm-2 control-label">状态：</label>
                            <div class="col-sm-2">
                                <p class="form-control-static"><?= $signlog_status[$model['status']]; ?></p>
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
