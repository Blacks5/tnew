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
<div class="ibox float-e-margins" onClick="$('.listcontent').hide()">
    <div class="ibox-content">
        <div class="form-group <?php if ($model->hasErrors('p_period')) {
            echo 'has-error';
        } ?>">
            <label class="col-sm-2 control-label">员工姓名</label>
            <div class="col-sm-4">
                <input type="hidden" class="form-control" name="ss_saleman_id" id="ss_saleman_id" value=""/>
                <input type="text" class="form-control" id="realname" name="realname" value=""
                       onKeyUp="find_customer();">
                <div class="dropdown listcontent">
                    <ul id="user_data" class="dropdown-menu pull-left">

                    </ul>
                </div>
            </div>
        </div>


        <div class="form-group">
            <div class="col-sm-8 col-sm-offset-3">
                <?= \yii\helpers\Html::submitButton('提交', ['class' => 'btn btn-primary']); ?>
                <?= \yii\helpers\Html::a('返回', Yii::$app->getUrlManager()->createUrl(['stores/index']), ['class' => 'btn btn-default']); ?>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
<?= Html::jsFile('@web/js/plugins/puupload/plupload.full.min.js') ?>

<script>
    function find_customer() {
        //检测y用户是否存在
        var realname = $('#realname').val();
        $.get('<?php echo Yii::$app->urlManager->createUrl(['team/getusers']);?>', {realname: realname}, function (msg) {
            if (msg.status == 1) {
                $('.listcontent').show();
                $('.dropdown-menu').show();
                $('#user_data').html(msg.name);
            } else {
                $('.listcontent').hide();
            }
        }, "JSON");
    }
    function select_one(a, b) {
        $('#ss_saleman_id').val(a);
        $('#realname').val(b);
        $('.listcontent').hide();
        $('#user_data').html('');
    }
</script>
