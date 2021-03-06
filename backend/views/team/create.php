<?php
$this->params['breadcrumbs'][] = ['label' => '所有团队', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$form = ActiveForm::begin(
    [
        'layout' => 'horizontal',
        'enableClientValidation' => false,
        'options' => ['class' => 'form-horizontal m-t', 'id' => 'signupForm'],
        'fieldConfig' => [
            'template' => "{label}\n<div class='col-sm-5'>{input}{error}</div>",
            'horizontalCssClasses' => [
                'label' => 'col-sm-3',
            ]
        ],


    ]
);
?>

    <style>
        .wraper {
            float: left;
            margin-left: 30px;
            text-align: center;
        }

        .file-list {
            padding: 0px;
        }

        .file-list li {
            list-style: none;
        }

        .file-list li img {
            width: 200px;
            height: 148px;
        }

        .file-list li p {
            text-align: center;
            margin: 0px;
        }
    </style>
    <div class="ibox float-e-margins">
        <div class="ibox-content">
            <?= $form->field($model, 't_name')->textInput(['class' => 'form-control'])/*->hint(' 这里', ['class'=>'fa fa-info-circle'])*/
            ; ?>
            <?= $form->field($model, 't_province')->dropDownList($provinces, ['class' => 'form-control getpcc']); ?>
            <?= $form->field($model, 't_city')->dropDownList([], ['class' => 'form-control getpcc']); ?>
            <?= $form->field($model, 't_county')->dropDownList([], ['class' => 'form-control getpcc']); ?>


            <div class="form-group">
                <div class="col-sm-8 col-sm-offset-3">
                    <?= \yii\helpers\Html::submitButton('提交', ['class' => 'btn btn-primary']); ?>
                    <?= \yii\helpers\Html::a('返回', Yii::$app->getUrlManager()->createUrl(['team/index']), ['class' => 'btn btn-default']); ?>
                </div>
            </div>
        </div>
    </div>
<?php ActiveForm::end(); ?>
<?= Html::jsFile('@web/js/plugins/puupload/plupload.full.min.js') ?>
<?php
$this->registerJs('
    var url = "' . \yii\helpers\Url::toRoute(['user/get-sub-addr']) . '"; // 获取子地区
    
    
    // 省变化
    $("#team-t_province").change(function(){
        var province_id = $(this).val();
        $.get(url, {p_id:province_id}, function(data){
            var dom  = createDom(data);
            $("#team-t_city").html(dom);
            
            $("#team-t_city").trigger("change");
        });     
    });
    
    // 市变化
    $("#team-t_city").change(function(){
        var city_id = $(this).val();
        $.get(url, {p_id:city_id}, function(data){
            var dom  = createDom(data);
            $("#team-t_county").html(dom);
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
    $("#team-t_province").trigger("change");
 ');
?>