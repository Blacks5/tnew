<?php
//app\assets\PccAsset::register($this);
//app\assets\MainAsset::register($this);
$this->title = $model->s_name;
$this->params['breadcrumbs'][] = ['label' => '商户列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ibox float-e-margins">
    <div class="ibox-content">
        <!--        <div class="row">
            <div class="col-sm-3">
                <a href="<? /*= Yii::$app->getUrlManager()->createUrl(['stores/sales', 'store_id'=>$model->s_id]) */ ?>" class="btn btn-success">销售人员</a>
            </div>
        </div>-->
        <div class="hr-line-dashed"></div>
        <div class="form-horizontal m-t" id="signupForm" novalidate="novalidate" action="" method="post">


            <div class="form-group">
                <label class="col-sm-3 control-label">商铺招牌名：</label>
                <div class="col-sm-8">
                    <p class="form-control-static"><?= $model->s_name; ?></p>
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-3 control-label">工商局注册名：</label>
                <div class="col-sm-8">
                    <p class="form-control-static"><?= $model->s_gov_name; ?></p>
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-3 control-label">负责人姓名：</label>
                <div class="col-sm-8">
                    <p class="form-control-static"><?= $model->s_owner_name; ?></p>
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-3 control-label">负责人电话：</label>
                <div class="col-sm-8">
                    <p class="form-control-static"><?= $model->s_owner_phone; ?></p>
                </div>
            </div>

            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-3 control-label">结算账户卡号：</label>
                <div class="col-sm-8">
                    <p class="form-control-static"><?= $model->s_bank_num; ?></p>
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-3 control-label">结算账户银行：</label>
                <div class="col-sm-8">
                    <p class="form-control-static"><?= $model->s_bank_name. '-'. $model->s_bank_addr. '-'. $model->s_bank_sub; ?></p>
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-3 control-label">是否对私账户：</label>
                <div class="col-sm-8">
                    <p class="form-control-static"><?= $model->s_bank_is_private; ?></p>
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-3 control-label">门店服务费：</label>
                <div class="col-sm-8">
                    <p class="form-control-static"><?= $model->s_service_charge; ?></p>
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-3 control-label">所在省市县/区：</label>
                <div class="col-sm-2">
                    <p class="form-control-static"><?= $model->s_province; ?>  <?= $model->s_city; ?>  <?= $model->s_county; ?></p>
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-3 control-label">商铺地址：</label>
                <div class="col-sm-8">
                    <p class="form-control-static"><?= $model->s_addr; ?></p>
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-3 control-label">销售人员：</label>
                <div class="col-sm-2">
                    <p>
                        <?=implode('-', $all_sales);?>
                    </p>
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-3 control-label">状态：</label>
                <div class="col-sm-2">
                    <p class="form-control-static"><?= \common\models\Stores::getAllStatus()[$model->s_status]; ?></p>
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-3 control-label">图片：</label>
                <div class="col-sm-9">
                    <?php if ($model->s_photo_one) { ?>
                        <div class="">
                            <?=\yii\helpers\Html::img($model->s_photo_one,
                                ['class'=>'img-circle', 'alt'=>'图片'])?>
                        </div>
                    <?php } ?>
                    <?php if ($model->s_photo_two) { ?>
                        <div class="">
                            <?=\yii\helpers\Html::img($model->s_photo_two,
                                ['class'=>'img-circle', 'alt'=>'图片'])?>
                        </div>
                    <?php } ?>
                    <?php if ($model->s_photo_three) { ?>
                        <div class="">
                            <?=\yii\helpers\Html::img($model->s_photo_three,
                                ['class'=>'img-circle', 'alt'=>'图片'])?>
                        </div>
                    <?php } ?>
                    <?php if ($model->s_photo_four) { ?>
                        <div class="">
                            <?=\yii\helpers\Html::img($model->s_photo_four,
                                ['class'=>'img-circle', 'alt'=>'图片'])?>
                        </div>
                    <?php } ?>
                    <?php if ($model->s_photo_five) { ?>
                        <div class="">
                            <?=\yii\helpers\Html::img($model->s_photo_five,
                                ['class'=>'img-circle', 'alt'=>'图片'])?>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-3 control-label">商铺添加人：</label>
                <div class="col-sm-8">
                    <p class="form-control-static"><?= $model->s_add_user_name; ?></p>
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-3 control-label">添加时间：</label>
                <div class="col-sm-8">
                    <p class="form-control-static"><?= date('Y-m-d H:i:s', $model->s_created_at); ?></p>
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <div class="col-sm-8 col-sm-offset-2">
                    <a class="btn btn-primary"
                       href="<?= Yii::$app->getUrlManager()->createUrl(['stores/update', 'id' => $model->s_id]) ?>">编辑</a>
                    <a class="btn btn-default"
                       href="<?= Yii::$app->getUrlManager()->createUrl(['stores/index']) ?>">返回</a>
                </div>
            </div>

        </div>
    </div>
</div>

