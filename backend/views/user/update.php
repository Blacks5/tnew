<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model backend\models\AuthItem */

$this->title = '更新用户 ';
?>
<div class="wrapper wrapper-content">
    <div class="ibox-content">
        <div class="row pd-10">
            <h1><?= Html::encode($this->title) ?></h1>
            <hr>
            <div class="auth-item-form col-sm-4">
                <?php $form = ActiveForm::begin(); ?>

                <?= $form->field($model, 'id')->hiddenInput()->label('') ?>

                <?= $form->field($model, 'username')->textInput(['readonly' => true])->label('用户名') ?>


                <?= $form->field($model, 'email')->textInput(['email' => true])->label('邮箱') ?>

                <?= $form->field($model, 'department_id', ['options' => ['class' => 'form-group']])->dropDownList($all_departments)->label('请选择部门', ['class' => 'sr-only'])->label('所属部门') ?>
                <?= $form->field($model, 'job_id', ['options' => ['class' => 'form-group']])->dropDownList($all_jobs)->label('请选择职位', ['class' => 'sr-only']) ?>

                <?= $form->field($model, 'province', ['options' => ['class' => 'form-group']])->dropDownList($all_province)->label('请选择省', ['class' => 'sr-only'])->label('负责区域') ?>
                <?= $form->field($model, 'city', ['options' => ['class' => 'form-group']])->dropDownList($all_citys)->label('请选择城市', ['class' => 'sr-only']) ?>
                <?= $form->field($model, 'county', ['options' => ['class' => 'form-group']])->dropDownList($all_countys)->label('请选择城县/区', ['class' => 'sr-only']) ?>

                <?php if ($model->username == 'admin'): ?>
                    <?= $form->field($model->usergroup, 'item_name')->dropDownList($item, ['disabled' => true])->label('用户组') ?>
                <?php else: ?>
                    <?= $form->field($model->usergroup, 'item_name')->dropDownList($item)->label('用户组') ?>
                <?php endif; ?>
                <div class="form-group">
                    <?= Html::submitButton('保存', ['class' => 'btn btn-primary']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>

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
//    $("#user-province").trigger("change");
//    $("#user-department_id").trigger("change");
 ');


?>