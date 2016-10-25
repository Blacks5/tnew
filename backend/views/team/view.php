<?php
$this->title = $model->t_name;
$this->params['breadcrumbs'][] = ['label' => '团队列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ibox float-e-margins">
    <div class="ibox-content">
        <div class="form-horizontal m-t" id="signupForm" novalidate="novalidate" action="" method="post">
            <div class="form-group">
                <strong class="col-sm-3 text-right">团队名：</strong>
                <div class="col-sm-8">
                    <span class="form-control-static label label-warning"><?= $model->t_name; ?></span>
                </div>
            </div>
            <div class="form-group">
                <strong class="col-sm-3 text-right">负责区域：</strong>
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
                <div class="col-sm-8 col-sm-offset-3">
                    <!--<a class="btn btn-primary" href="<? /*=Yii::$app->getUrlManager()->createUrl(['team/update', 'id'=>$model->t_id])*/ ?>">编辑</a>-->
                    <a class="btn btn-default"
                       href="<?= Yii::$app->getUrlManager()->createUrl(['team/index']) ?>">返回</a>
                </div>
            </div>

        </div>
    </div>
</div>
