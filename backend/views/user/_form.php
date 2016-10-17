<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model backend\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin();
    $model->city = 2; ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'auth_key')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>


    <?= $form->field($model, 'province', ['options' => ['class' => 'form-group']])->dropDownList($all_province)->label('请选择省', ['class' => 'sr-only'])->label('负责区域') ?>
    <?= $form->field($model, 'city', ['options' => ['class' => 'form-group']])->dropDownList(['请选择市'])->label('请选择城市', ['class' => 'sr-only']) ?>
    <?= $form->field($model, 'county', ['options' => ['class' => 'form-group']])->dropDownList(['请选择县/区'])->label('请选择城县/区', ['class' => 'sr-only']) ?>


    <?= $form->field($model1, 'name')->dropDownList($item)->label('用户组') ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '新增' : '更新', ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$this->registerJs('
    // 省变化
    $("#user-province").change(function(){
        var url = "'.Url::toRoute(['user/get-sub-addr']).'";
        var province_id = $(this).val();
        var dom = new String();
        $.get(url, {p_id:province_id}, function(data){
            dom  = createDom(data);
        });

        console.log(dom);
//        dom = "<option>222</option>";
        $("#user-city").append(dom);

    });
    function createDom(data){
        console.log(333);
        var dom = "";
        $.each(data, function (k, v) {
            dom += "<option  value=\"2\">2</option>";
        })
        return dom;
    }

    // 市变化
    $("#user-city").change(function(){
        console.log("市");
    });



    // 县变化
    $("#user-county").change(function(){
        console.log("县变了");
    });
 ');
?>
