<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\User */

?>
<div class="row ">
    <div class="col-sm-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>产品详情 ></h5>
            </div>
            <div class="ibox-content">
                <form class="form-horizontal m-t" id="myform">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">产品名：</label>
                        <div class="col-sm-8">
                            <p class="form-control-static"><?= $model['p_name']; ?></p>
                        </div>
                    </div>


                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">适用类型：</label>
                        <div class="col-sm-8">
                            <p class="form-control-static"><?= $model['p_type']; ?></p>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">产品期数：</label>
                        <div class="col-sm-8">
                            <p class="form-control-static"><?= $model['p_period']; ?></p>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">月利率(%)：</label>
                        <div class="col-sm-8">
                            <p class="form-control-static">
                                <?= $model['p_month_rate']; ?>
                            </p>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">增值服务费(%)：</label>
                        <div class="col-sm-8">
                            <p class="form-control-static"><?= $model['p_add_service_fee']; ?></p>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">随心包(元/每月)：</label>
                        <div class="col-sm-8">
                            <p class="form-control-static"><?= $model['p_free_pack_fee']; ?></p>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">财务管理费(%)：</label>
                        <div class="col-sm-8">
                            <p class="form-control-static"><?= $model['p_finance_mangemant_fee']; ?></p>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">客户管理费(%)：</label>
                        <div class="col-sm-8">
                            <p class="form-control-static"><?= $model['p_customer_management']; ?></p>
                        </div>
                    </div>

                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">锁定状态：</label>
                        <div class="col-sm-8">
                            <p class="form-control-static">
                                <?php if ($model['p_status'] === 10){?>
                                    <button type="button" class="btn btn-primary btn-xs">开启</button>
                                <?php }else{ ?>
                                    <button type="button" class="btn btn-default btn-xs">已锁定</button>
                                <?php }?>
                            </p>
                        </div>
                    </div>


                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <div class="col-sm-8 col-sm-offset-3">

                            <button class="btn btn-primary" type="button" name="btnbutton" onclick="history.go(-1)">
                                返回上一页
                            </button>
                        </div>
                    </div>
                </form>
            </div>


        </div>
    </div>
</div>
