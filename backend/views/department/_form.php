<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use \yii\helpers\Url;
$form = ActiveForm::begin();
?>
<div class="user-form">
    <?= $form->field($model, 'd_name')->textInput()->textInput(['maxlength' => true]) ?>
    <div class="form-group">
        <?= \yii\helpers\Html::submitButton('提交', ['class' => 'btn btn-primary']); ?>
        <?= \yii\helpers\Html::a('返回', \yii\helpers\Url::toRoute(['department/index']),['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-primary']); ?>
    </div>
</div>
<?php ActiveForm::end(); ?>



