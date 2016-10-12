<?php
//app\assets\PccAsset::register($this);
$this->params['breadcrumbs'][] = ['label' => '所有部门', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
//use \yii\bootstrap\ActiveForm;
use kartik\form\ActiveForm;

$form = ActiveForm::begin(
    [
//        'layout' => 'horizontal',
        'enableClientValidation' => false,
        'options' => ['class' => 'form-horizontal m-t', 'id' => 'signupForm'],
//        'type' => ActiveForm::TYPE_VERTICAL,
        'formConfig' => ['labelSpan' => 3, 'deviceSize' => ActiveForm::SIZE_MEDIUM],
        'fieldConfig' => [
//            'options'=>['class'=>''],
            'template' => "{label}<div class='col-sm-7'>{input}{error}</div>",
        ],


    ]
);
?>
<link rel="stylesheet" href="/statics/css/animate.min.css">
<link rel="stylesheet" href="/statics/css/style.min.css">
<div class="ibox float-e-margins">
    <div class="ibox-content">
        <?= $form->field($model, 'd_name')->textInput() ?>

        <div class="form-group">
            <div class="col-sm-8 col-sm-offset-3">
                <?= \yii\helpers\Html::submitButton('提交', ['class' => 'btn btn-primary']); ?>
                <?= \yii\helpers\Html::a('返回', \yii\helpers\Url::toRoute(['department/index']), ['class' => 'btn btn-default']); ?>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>



