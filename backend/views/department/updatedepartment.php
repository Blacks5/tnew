<?php
//app\assets\MainAsset::register($this);
$this->params['breadcrumbs'][] = ['label' => '部门列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
//use \yii\bootstrap\ActiveForm;
//use kartik\form\ActiveForm;
use \yii\bootstrap\ActiveForm;
$form = ActiveForm::begin(
    [
        'layout' => 'horizontal',
        'enableClientValidation'=>false,
        'options'=>['class'=>'form-horizontal m-t', 'id'=>'department'],
        'fieldConfig'=>[
            'template'=>"{label}\n<div class='col-sm-5'>{input}{error}</div>",
            'horizontalCssClasses'=>[
                'label'=>'col-sm-3',
            ]
        ],
    ]
);
?>
<link rel="stylesheet" href="/statics/css/animate.min.css">
<link rel="stylesheet" href="/statics/css/style.min.css">
<div class="ibox float-e-margins">
    <div class="ibox-content">
        <!--<div class="row">
            <div class="col-sm-3">
                <a href="<?/*= Yii::$app->getUrlManager()->createUrl(['department/create-job', 'd_id'=>$model->d_id]) */?>" class="btn btn-success">添加职位</a>
            </div>
        </div>
        <div class="hr-line-dashed"></div>-->
        <?= $form->field($model, 'd_name')->textInput() ?>

        <div class="form-group">
            <div class="col-sm-8 col-sm-offset-3">
                <?= \yii\helpers\Html::submitButton('提交', ['class' => 'btn btn-primary']); ?>
                <?= \yii\helpers\Html::a('返回', Yii::$app->getUrlManager()->createUrl(['department/index']),['class' => 'btn btn-default']); ?>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>



