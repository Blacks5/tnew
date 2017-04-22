<?php

?>
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>添加产品
                        <small>管理</small>
                    </h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                        <a class="dropdown-toggle" data-toggle="dropdown" href="form_basic.html#">
                            <i class="fa fa-wrench"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-user">
                            <li><a href="form_basic.html#">选项1</a>
                            </li>
                            <li><a href="form_basic.html#">选项2</a>
                            </li>
                        </ul>
                        <a class="close-link">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <form method="post" class="form-horizontal">

                        <div class="form-group">
                            <label class="col-sm-2 control-label">适用类型</label>
                            <div class="col-sm-4">
                                <select class="form-control m-b" name="p_type">
                                    <?php foreach ($goods_type as $v) { ?>
                                        <option value="<?= $v['t_id']; ?>"><?= $v['t_name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <!--<div class="hr-line-dashed"></div>-->
                        <div class="form-group <?php if ($msg = $model->getFirstError('p_name')) {
                            echo 'has-error';
                        } ?>">
                            <label class="col-sm-2 control-label">名称</label>

                            <div class="col-sm-4">
                                <input type="text" class="form-control" name="p_name" value="<?=$model->p_name?>">
                                <?php if ($msg) { ?>
                                    <span class="help-block m-b-none"><?= $msg ?></span>
                                <?php } ?>
                            </div>
                        </div>

                        <div class="hr-line-dashed"></div>
                        <div class="form-group <?php if ($model->hasErrors('p_period')) {
                            echo 'has-error';
                        } ?>">
                            <label class="col-sm-2 control-label">期数</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" name="p_period" value="<?=$model->p_period?>">
                                <?php if ($msg = $model->getFirstError('p_period')) { ?>
                                    <span class="help-block m-b-none"><?= $msg ?></span>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group <?php if ($model->hasErrors('p_month_rate')) {
                            echo 'has-error';
                        } ?>">
                            <label class="col-sm-2 control-label">月利率(%)</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" name="p_month_rate" value="<?=$model->p_month_rate?>">
                                <?php if ($msg = $model->getFirstError('p_month_rate')) { ?>
                                    <span class="help-block m-b-none"><?= $msg ?></span>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group <?php if ($model->hasErrors('p_add_service_fee')) {
                            echo 'has-error';
                        } ?>">
                            <label class="col-sm-2 control-label">个人保障计划(%)</label>

                            <div class="col-sm-4">
                                <input type="text" class="form-control" name="p_add_service_fee" value="<?=$model->p_add_service_fee?>">
                                <?php if ($msg = $model->getFirstError('p_add_service_fee')) { ?>
                                    <span class="help-block m-b-none"><?= $msg ?></span>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group <?php if ($model->hasErrors('p_free_pack_fee')) {
                            echo 'has-error';
                        } ?>">
                            <label class="col-sm-2 control-label">贵宾服务包(元/每月)</label>

                            <div class="col-sm-4">
                                <input type="text" class="form-control" name="p_free_pack_fee" value="<?=$model->p_free_pack_fee?>">
                                <?php if ($msg = $model->getFirstError('p_free_pack_fee')) { ?>
                                    <span class="help-block m-b-none"><?= $msg ?></span>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group <?php if ($model->hasErrors('p_finance_mangemant_fee')) {
                            echo 'has-error';
                        } ?>">
                            <label class="col-sm-2 control-label">财务管理费(%)</label>

                            <div class="col-sm-4">
                                <input type="text" class="form-control" name="p_finance_mangemant_fee" value="<?=$model->p_finance_mangemant_fee?>">
                                <?php if ($msg = $model->getFirstError('p_finance_mangemant_fee')) { ?>
                                    <span class="help-block m-b-none"><?= $msg ?></span>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group <?php if ($model->hasErrors('p_customer_management')) {
                            echo 'has-error';
                        } ?>">
                            <label class="col-sm-2 control-label">客户管理费(%)</label>

                            <div class="col-sm-4">
                                <input type="text" class="form-control" name="p_customer_management" value="<?=$model->p_customer_management?>">
                                <?php if ($msg = $model->getFirstError('p_customer_management')) { ?>
                                    <span class="help-block m-b-none"><?= $msg ?></span>
                                <?php } ?>
                            </div>
                        </div>
                        <input type="hidden" name="_csrf-backend"
                               value="<?= Yii::$app->getRequest()->getCsrfToken() ?>">
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <div class="col-sm-4 col-sm-offset-2">
                                <button class="btn btn-primary" type="submit">保存内容</button>
                                <button class="btn btn-white" type="submit">取消</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- iCheck -->
<?= \yii\bootstrap\Html::jsFile('@web/js/plugins/iCheck/icheck.min.js') ?>
<?php $this->registerJs("
        $('.i-checks').iCheck({
            checkboxClass: 'icheckbox_square-green',
            radioClass: 'iradio_square-green',
        });;
", \yii\web\View::PH_BODY_BEGIN); ?>