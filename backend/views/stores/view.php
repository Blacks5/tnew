<?php
//app\assets\PccAsset::register($this);
//app\assets\MainAsset::register($this);1
$this->title = $model->s_name;
$this->params['breadcrumbs'][] = ['label' => '商户列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ibox float-e-margins">
    <div class="ibox-content">
                <div class="row">
            <div class="col-sm-3">
                <a href="<?= Yii::$app->getUrlManager()->createUrl(['stores/salemanindex', 'ss_store_id' => $model->s_id]) ?>"
                   class="btn btn-success">销售人员</a>
            </div>
        </div>
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
                <label class="col-sm-3 control-label">结算账户的账户所有人姓名：</label>
                <div class="col-sm-8">
                    <p class="form-control-static"><?= $model->s_bank_people_name; ?></p>
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-3 control-label">结算账户所有人身份证号码：</label>
                <div class="col-sm-8">
                    <p class="form-control-static"><?= $model->s_idcard_num; ?></p>
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
                    <p class="form-control-static"><?= \common\models\Stores::getAllBankType()[$model->s_bank_is_private]; ?></p>
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
                <label class="col-sm-3 control-label">商铺真实经营地址：</label>
                <div class="col-sm-8">
                    <p class="form-control-static"><?= $model->s_real_addr; ?></p>
                </div>
            </div>
            <!--<div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-3 control-label">销售人员：</label>
                <div class="col-sm-2">
                    <p>
                        <?/*=implode('-', $all_sales);*/?>
                    </p>
                </div>
            </div>-->
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
                <div class="col-sm-6">
                    <div class="ibox float-e-margins">
                        <div class="ibox-content">
                            <div class="carousel slide" id="carousel1">
                                <div class="carousel-inner">
                                    <?php if ($model->s_photo_one) { ?>
                                        <div class="item active">
                                            <?= \yii\helpers\Html::img($model->s_photo_one,
                                                ['class' => 'img-responsive', 'alt' => '图片']) ?>
                                        </div>
                                    <?php } ?>
                                    <?php if ($model->s_photo_two) { ?>
                                        <div class="item">
                                            <?= \yii\helpers\Html::img($model->s_photo_two,
                                                ['class' => 'img-responsive', 'alt' => '图片']) ?>
                                        </div>
                                    <?php } ?>
                                    <?php if ($model->s_photo_three) { ?>
                                        <div class="item">
                                            <?= \yii\helpers\Html::img($model->s_photo_three,
                                                ['class' => 'img-responsive', 'alt' => '图片']) ?>
                                        </div>
                                    <?php } ?>
                                    <?php if ($model->s_photo_four) { ?>
                                        <div class="item">
                                            <?= \yii\helpers\Html::img($model->s_photo_four,
                                                ['class' => 'img-responsive', 'alt' => '图片']) ?>
                                        </div>
                                    <?php } ?>
                                    <?php if ($model->s_photo_five) { ?>
                                        <div class="item">
                                            <?= \yii\helpers\Html::img($model->s_photo_five,
                                                ['class' => 'img-responsive', 'alt' => '图片']) ?>
                                        </div>
                                    <?php } ?>
                                    <?php if ($model->s_photo_six) { ?>
                                        <div class="item">
                                            <?= \yii\helpers\Html::img($model->s_photo_six,
                                                ['class' => 'img-responsive', 'alt' => '图片']) ?>
                                        </div>
                                    <?php } ?>
                                    <?php if ($model->s_photo_seven) { ?>
                                        <div class="item">
                                            <?= \yii\helpers\Html::img($model->s_photo_seven,
                                                ['class' => 'img-responsive', 'alt' => '图片']) ?>
                                        </div>
                                    <?php } ?>
                                    <?php if ($model->s_photo_eight) { ?>
                                        <div class="item">
                                            <?= \yii\helpers\Html::img($model->s_photo_eight,
                                                ['class' => 'img-responsive', 'alt' => '图片']) ?>
                                        </div>
                                    <?php } ?>
                                    <?php if ($model->s_photo_nine) { ?>
                                        <div class="item">
                                            <?= \yii\helpers\Html::img($model->s_photo_nine,
                                                ['class' => 'img-responsive', 'alt' => '图片']) ?>
                                        </div>
                                    <?php } ?>
                                </div>
                                <a data-slide="prev" href="carousel.html#carousel1" class="left carousel-control">
                                    <span class="icon-prev"></span>
                                </a>
                                <a data-slide="next" href="carousel.html#carousel1" class="right carousel-control">
                                    <span class="icon-next"></span>
                                </a>
                            </div>
                        </div>
                    </div>
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
                    <?php if(($model->s_status == 3)||($model->s_status == 4)||($model->s_status == 1)){ ?>
                        <?php if (Yii::$app->getUser()->can(yii\helpers\Url::toRoute(['stores/activatestore']))) { ?>
                            <a class="btn btn-primary" id="activate_store" href="javascript:void(0);" data-value="<?= $model->s_id?>">激活</a>
                        <?php } ?>
                    <?php }elseif($model->s_status == 10){ ?>
                        <?php if (Yii::$app->getUser()->can(yii\helpers\Url::toRoute(['stores/blockedstore']))) { ?>
                            <a class="btn btn-primary" id="blocked_store" href="javascript:void(0);" data-value="<?= $model->s_id?>">冻结</a>
                        <?php } ?>
                    <?php } ?>
                    <?php if($model->s_status != 2){ ?>
                        <?php if (Yii::$app->getUser()->can(yii\helpers\Url::toRoute(['stores/closestore']))) { ?>
                            <a class="btn btn-primary" id="close_store" href="javascript:void(0);" data-value="<?= $model->s_id?>">关闭</a>
                        <?php } ?>
                    <?php } ?>
                    <a class="btn btn-default"
                       href="<?= Yii::$app->getUrlManager()->createUrl(['stores/index']) ?>">返回</a>
                </div>
            </div>

        </div>
    </div>
</div>
<?= \yii\bootstrap\Html::jsFile('@web/js-too/bootstrap.min.js') ?>
<?= \yii\bootstrap\Html::jsFile('@web/js-too/jquery.min.js') ?>
<?= \yii\bootstrap\Html::jsFile('@web/js-too/content.min.js') ?>
<?= \yii\helpers\Html::jsFile('@web/js/plugins/layer/layer.min.js') ?>

<script>
    //激活
    $("#activate_store").click(function(ev){
        var ev = ev;
        layer.confirm("确定要激活此商户？", {title:"激活提示", icon:3}, function(index){
            var loading = layer.load();
            var s_id = $(ev.target).attr("data-value");
            var url = "<?= \yii\helpers\Url::toRoute(['stores/activatestore']); ?>";
            $.ajax({
                url: url,
                type: "get",
                dataType: "json",
                data: {s_id:s_id},
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

    //冻结
    $("#blocked_store").click(function(ev){
        var ev = ev;
        layer.confirm("冻结门店需要解除所有销售代表,确定要冻结？", {title:"冻结提示", icon:3}, function(index){
            var loading = layer.load();
            var s_id = $(ev.target).attr("data-value");
            var url = "<?= \yii\helpers\Url::toRoute(['stores/blockedstore']); ?>";
            $.ajax({
                url: url,
                type: "get",
                dataType: "json",
                data: {s_id:s_id},
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

    //关闭门店
    $("#close_store").click(function(ev){
        var ev = ev;
        layer.confirm("关闭门店需要解除所有销售代表,确定要关闭？", {title:"关闭提示", icon:3}, function(index){
            var loading = layer.load();
            var s_id = $(ev.target).attr("data-value");
            var url = "<?= \yii\helpers\Url::toRoute(['stores/closestore']); ?>";
            $.ajax({
                url: url,
                type: "get",
                dataType: "json",
                data: {s_id:s_id},
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

</script>