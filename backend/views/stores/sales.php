<?php

$this->params['breadcrumbs'][] = ['label' => '商户列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $s_name, 'url' => ['view', 'id'=>$s_id]];
$this->params['breadcrumbs'][] = $this->title;
use \yii\bootstrap\ActiveForm;
$form = ActiveForm::begin(
    [
        'layout' => 'horizontal',
        'enableClientValidation'=>false,
        'options'=>['class'=>'form-horizontal m-t', 'id'=>'signupForm'],
        'fieldConfig'=>[
            'template'=>"{label}\n<div class='col-sm-5'>{input}{error}</div>",
            'horizontalCssClasses'=>[
                'label'=>'col-sm-3',
            ]
        ],


    ]
);
?>

<!--<link rel="stylesheet" href="/statics/css/animate.min.css">-->
<link rel="stylesheet" href="/statics/css/style.min.css">
<link rel="stylesheet" href="/statics/css/plugins/iCheck/custom.css">


<div>
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox">
                <div class="ibox-content" style="height: auto">

                    <div class="form-group">
                        <label class="col-sm-3 control-label">可选销售人员：</label>
                        <?php foreach($model as $v){ ?>
                            <div class="col-sm-2">
                                <label class="checkbox-inline i-checks">
                                    <div class="icheckbox_square-green" style="position: relative;">
                                        <input class="xx" name="id[]" type="checkbox" value="<?=$v['id'];?>" style="position: absolute; opacity: 0;">
                                        <ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);"></ins>
                                    </div>
                                    <?=$v['realname']?>
                                </label>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">已有销售人员：</label>
                        <?php foreach($already as $v){ ?>
                            <div class="col-sm-2">
                                <label class="checkbox-inline i-checks " >
                                    <div class="icheckbox_square-green " style="position: relative;">
                                        <input class="xx" type="checkbox" name="cancel_id[]" value="<?=$v['id'];?>" style="position: absolute; opacity: 0;" checked>
                                        <ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);"></ins>
                                    </div>
                                    <?=$v['realname']?>
                                </label>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-8 col-sm-offset-3">
                            <input type="hidden" name="_csrf" value="<?= yii::$app->getRequest()->getCsrfToken(); ?>">
                            <button class="btn btn-primary" type="submit">提交</button>
                            <a class="btn btn-default" href="<?=Yii::$app->getUrlManager()->createUrl(['stores/view', 'id'=>$s_id])?>">返回</a>
                        </div>
                    </div>


                </div>
            </div>
        </div>


    </div>
</div>
<?php ActiveForm::end(); ?>
        <script src="/statics/plugins/layer/layer.js"></script>
        <link rel="stylesheet" href="/statics/plugins/laypage/skin/laypage.css">
        <script src="/statics/plugins/laypage/laypage.js"></script>
        <script src="/statics/js/plugins/iCheck/icheck.min.js"></script>


        <script>
            $('.i-checks').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
            });


            <?php if($msg = Yii::$app->getSession()->getFlash('msg')){ ?>
                layer.alert('<?=$msg; ?>');
            <?php  } ?>
        </script>