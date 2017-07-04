<?php
//app\assets\PccAsset::register($this);
app\assets\MainAsset::register($this);

$this->params['breadcrumbs'][] = ['label' => '所有产品', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="ibox float-e-margins">
    <div class="ibox-content">
        <div class="form-horizontal m-t" id="signupForm" novalidate="novalidate" action="" method="post">
            <div class="form-group">
                <div>
                    <label class="col-sm-3 control-label">部门名称：</label>
                    <div class="col-sm-3">
                        <p class="form-control-static"><?= $model['d_name'];?></p>
                    </div>
                </div>

            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <div class="col-sm-8 col-sm-offset-3">
                    <a class="btn btn-primary" href="<?=Yii::$app->getUrlManager()->createUrl(['department/update-department', 'd_id'=>$model['d_id']])?>">编辑</a>
                    <a class="btn btn-default" href="<?=Yii::$app->getUrlManager()->createUrl(['department/index'])?>">返回</a>
                </div>
            </div>

        </div>
    </div>
</div>
