<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model backend\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'realname')->textInput(['maxlength' => true])->label('真实姓名') ?>
    <?= $form->field($model, 'cellphone')->textInput(['maxlength' => true])->label('手机号码') ?>
    <input type="password" id="user-password_hash_fuck" name="User[password_hash]" style="display: none">
    <?= $form->field($model, 'password_hash')->passwordInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'password_hash_1')->passwordInput(['maxlength' => true])->label('重复密码') ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'department_id', ['options' => ['class' => 'form-group']])->dropDownList($all_departments)->label('请选择部门', ['class' => 'sr-only'])->label('所属部门') ?>
    <?= $form->field($model, 'job_id', ['options' => ['class' => 'form-group']])->dropDownList(['请选择职位'])->label('请选择职位', ['class' => 'sr-only']) ?>

    <?= $form->field($model, 'province', ['options' => ['class' => 'form-group']])->dropDownList($all_province)->label('请选择省', ['class' => 'sr-only'])->label('负责区域') ?>
    <?= $form->field($model, 'city', ['options' => ['class' => 'form-group']])->dropDownList(['请选择市'])->label('请选择城市', ['class' => 'sr-only']) ?>
    <?= $form->field($model, 'county', ['options' => ['class' => 'form-group']])->dropDownList(['请选择县/区'])->label('请选择城县/区', ['class' => 'sr-only']) ?>


    <?= $form->field($model1, 'name')->dropDownList($item)->label('角色') ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '新增' : '更新', ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
<?php
$this->registerJs('
    var url = "'.Url::toRoute(['user/get-sub-addr']).'"; // 获取子地区
    var url_jobs = "'.Url::toRoute(['user/get-jobs']).'"; // 获取职位
    
    // 部门变化
    $("#user-department_id").change(function(){
        var d_id = $(this).val();
        $.get(url_jobs, {d_id:d_id}, function(data){
            var dom  = createDom(data);
            $("#user-job_id").html(dom);
        });
    });
    
    // 省变化
    $("#user-province").change(function(){
        var province_id = $(this).val();
        $.get(url, {p_id:province_id}, function(data){
            var dom  = createDom(data);
            $("#user-city").html(dom);
            
            $("#user-city").trigger("change");
        });     
    });
    
    // 市变化
    $("#user-city").change(function(){
        var city_id = $(this).val();
        $.get(url, {p_id:city_id}, function(data){
            var dom  = createDom(data);
            $("#user-county").html(dom);
        });
    });
    
    // 专业造dom
    function createDom(data){
        var dom = "";
        $.each(data, function (k, v) {
            dom += "<option  value="+k+">"+v+"</option>";
        })
        return dom;
    }
    
    // 初始化
    $("#user-province").trigger("change");
    $("#user-department_id").trigger("change");
 ');
?>
