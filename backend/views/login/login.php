<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;


$this->params['breadcrumbs'][] = $this->title;
?>
<div class="middle-box text-center loginscreen  animated fadeInDown">
    <div>
        <div>

            <h1 class="logo-name">TN</h1>

        </div>
        <h3>欢迎使用</h3>


        <div class="row">
            <div class="">
                <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                <?= $form->field($model, 'username')->textInput(['autofocus' => true])->label('用户名') ?>
                <input type="hidden">
                <?= $form->field($model, 'password')->passwordInput()->label('密码') ?>

                <!--<? /*= $form->field($model, 'rememberMe')->checkbox()->label('记住我') */ ?>-->

                <div class="form-group">
                    <?= Html::submitButton('登录', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
    <div class="footer">
        <div class="">Copyright &copy; 2016-2017 <a href="#" target="_blank">管理系统</a>
        </div>
    </div>
</div>



