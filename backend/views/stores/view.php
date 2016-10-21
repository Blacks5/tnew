<?php
//app\assets\PccAsset::register($this);
//app\assets\MainAsset::register($this);
$this->title = $model->s_name;
$this->params['breadcrumbs'][] = ['label' => '商户列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<link rel="stylesheet" href="/statics/css/plugins/iCheck/custom.css">
<link rel="stylesheet" href="/statics/css/style.min.css">
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
                <label class="col-sm-3 control-label">负责人姓名：</label>
                <div class="col-sm-8">
                    <p class="form-control-static"><?= $model->s_owner_name; ?></p>
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-3 control-label">公司名：</label>
                <div class="col-sm-8">
                    <p class="form-control-static"><?= $model->s_name; ?></p>
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
                <label class="col-sm-3 control-label">负责区域：</label>
                <div class="col-sm-2">
                    <p class="form-control-static"><?= \app\helper\GetPcc::getAddName($model->s_province); ?>  <?= \app\helper\GetPcc::getAddName($model->s_city); ?>  <?= \app\helper\GetPcc::getAddName($model->s_county); ?></p>
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-3 control-label">销售人员：</label>
                <div class="col-sm-2">
                    <p>
                        <?php foreach ($data as $key => $val) { ?>
                            <?php if ($val['belong_stores_id'] == $s_id) {
                                echo '<span class="label">' . $val['realname'] . '</span>';
                            } ?>
                        <?php } ?>
                        <a style="margin-left: 20px;" data-toggle="modal" class="btn btn-primary btn-xs"
                           href="sales.php#modal-form">编辑人员</a>
                    </p>
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-3 control-label">状态：</label>
                <div class="col-sm-2">
                    <p class="form-control-static"><?= \app\models\Stores::getAllStatus()[$model->s_status]; ?></p>
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-3 control-label">图片：</label>
                <div class="col-sm-9">
                    <?php if ($model->s_photo_one) { ?>
                        <div class="">
                            <img src="<?= $model->s_photo_one ?>" alt="">
                        </div>
                    <?php } ?>
                    <?php if ($model->s_photo_two) { ?>
                        <div class="">
                            <img src="<?= $model->s_photo_two ?>" alt="">
                        </div>
                    <?php } ?>
                    <?php if ($model->s_photo_three) { ?>
                        <div class="">
                            <img src="<?= $model->s_photo_three ?>" alt="">
                        </div>
                    <?php } ?>
                    <?php if ($model->s_photo_four) { ?>
                        <div class="">
                            <img src="<?= $model->s_photo_four ?>" alt="">
                        </div>
                    <?php } ?>
                    <?php if ($model->s_photo_five) { ?>
                        <div class="">
                            <img src="<?= $model->s_photo_five ?>" alt="">
                        </div>
                    <?php } ?>
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

<div id="modal-form" class="modal fade" aria-hidden="true" style="padding-top: 100px;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <h3 class="m-t-none m-b">销售人员编辑</h3>
                <?php foreach ($data as $key => $val) { ?>
                    <label>
                        <input id="<?= $val['id'] ?>" class="i-checks"
                               type="checkbox" <?php if ($val['belong_stores_id'] == $s_id) {
                            echo 'checked';
                        } ?>><?php echo $val['realname']; ?>
                    </label>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<script src="/statics/plugins/layer/layer.js"></script>
<script src="/statics/js/plugins/iCheck/icheck.min.js"></script>
<script>
    $('.i-checks').on('ifChanged', function (event) {
        deal_sales(event.target.id);
    });
    $('.i-checks').iCheck({
        checkboxClass: 'icheckbox_square-green',
        radioClass: 'iradio_square-green'
    });
</script>
<script>
    function deal_sales(id) {
        $.ajax({
            url: "<?= Yii::$app->getUrlManager()->createUrl(['stores/sales', 'store_id' => $model->s_id]) ?>",
            data: {sales_id: id},
            type: 'post',
            dataType: 'json',
            success: function (data) {
                if (data.status == 1) {
                    layer.msg(data.message);
                    parent.location.reload();
                } else {
                    layer.msg(data.message);
                }
            },
            error: function () {
            }
        });
    }
</script>

