<?php

$this->title = '编辑产品';
use \yii\bootstrap\ActiveForm;
?>
<link rel="stylesheet" href="/statics/css/animate.min.css">
<link rel="stylesheet" href="/statics/css/style.min.css">
<div class="ibox float-e-margins">
    <div class="ibox-content">
        <h5>编辑产品</h5>
        <div class="hr-line-dashed"></div>
        <?php $form = ActiveForm::begin(['enableClientScript'=>false]); ?>
        <?= $form->field($model, 'p_type')->dropDownList($goods_types,['class'=>'form-control', 'default-value'=>$model->p_type])->label('适用类型'); ?>
        <?= $form->field($model, 'p_is_promotional')->dropDownList([1=>'促销',0=>'常规'])->label('是否促销'); ?>
        <?= $form->field($model, 'p_name')->textInput(['class'=>'form-control']); ?>
        <?= $form->field($model, 'p_period')->textInput(['class'=>'form-control']); ?>
        <?= $form->field($model, 'p_month_rate')->textInput(['class'=>'form-control']); ?>
        <?= $form->field($model, 'p_add_service_fee')->textInput(['class'=>'form-control']); ?>
        <?= $form->field($model, 'p_free_pack_fee')->textInput(['class'=>'form-control']); ?>
        <?= $form->field($model, 'p_finance_mangemant_fee')->textInput(['class'=>'form-control']); ?>
        <?= $form->field($model, 'p_customer_management')->textInput(['class'=>'form-control']); ?>
        <div class="form-group">
            <div class="row">
                <div class="col-sm-4">
                    <?= \yii\helpers\Html::submitButton('提交', ['class' => 'btn btn-primary']); ?>
                    <?= \yii\helpers\Html::a('返回', Yii::$app->getUrlManager()->createUrl(['product/index']),['class' => 'btn btn-default']); ?>
                </div>

            </div>

        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>




