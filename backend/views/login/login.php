<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/*问候语句*/
$time_str = [
    '卧槽这么晚还上班，必须涨工资啊' =>[0, 6], // 0点到6点
    '早上好啊，吃过饭没？' =>[7, 8],
    '现在是上午，请搬砖' =>[9, 11],
    '中午了，快去吃饭' =>[12, 13],
    '下午啦，好好搬砖' =>[14, 17],
    '晚上要好好吃饭哟' =>[18, 19],
    '一天结束啦，是不是该看看书？' =>[20, 21],
    '差不多就去睡觉' =>[22, 23],
];
$now = (new \DateTime())->format('H');
$end_str = '遭了，现在几点啦？';
foreach ($time_str as $key=>$value){
    if(($now >= reset($value)) && ($now <= end($value))){
        $end_str = $key;
        break;
    }
}

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="middle-box text-center loginscreen  animated fadeInDown">
    <div>
        <div>

            <h1 class="logo-name">TN</h1>

        </div>
        <h3><?=$end_str?></h3>


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
        <div class="">Copyright &copy; 2016-2017 <a href="#" target="_blank">天牛金融管理系统</a>
        </div>
    </div>
</div>



