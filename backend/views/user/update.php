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
                <?php $form = ActiveForm::begin(['enableClientScript'=>false]); ?>

                <?= $form->field($model, 'id')->hiddenInput()->label('') ?>

                <?= $form->field($model, 'username')->textInput(['readonly' => true])->label('用户名') ?>

                <?= $form->field($model, 'status')->dropDownList([$model->status=>$user_status[$model->status]],['class'=>'form-control'])->label('状态'); ?>

                <?= $form->field($model, 'email')->textInput(['email' => true])->label('邮箱') ?>
                <?= $form->field($model, 'id_card_num')->textInput(['id_card_num' => true])->label('身份证号码') ?>
                <?= $form->field($model, 'address')->textInput(['address' => true])->label('联系地址') ?>
                <?= $form->field($model, 'cellphone')->textInput(['cellphone' => true])->label('手机号码') ?>

                <?= $form->field($model, 'department_id', ['options' => ['class' => 'form-group']])->dropDownList($all_departments)->label('请选择部门', ['class' => 'sr-only'])->label('所属部门') ?>
                <?= $form->field($model, 'job_id', ['options' => ['class' => 'form-group']])->dropDownList($all_jobs)->label('请选择职位', ['class' => 'sr-only']) ?>

                <?= $form->field($model, 'province', ['options' => ['class' => 'form-group']])->dropDownList($all_province)->label('请选择省', ['class' => 'sr-only']) ?>
                <?= $form->field($model, 'city', ['options' => ['class' => 'form-group']])->dropDownList($all_citys)->label('请选择城市', ['class' => 'sr-only']) ?>
                <?= $form->field($model, 'county', ['options' => ['class' => 'form-group']])->dropDownList($all_countys)->label('请选择城县/区', ['class' => 'sr-only']) ?>

                <?= $form->field($model, 'leader', ['options' => ['class' => 'from-group']])->dropDownList($leader)->label('请选择上级领导', ['class'=> 'sr-only']) ?>


                <?= $form->field($model, 'id_card_pic_one')->hiddenInput(['class'=>'form-group'])->label('');?>
                <div class="form-group field-stores-s_remark required">
                    <label class="control-label col-sm-2" for="stores-s_remark">身份证照片</label>
                    <div class="col-sm-9">
                        <div class="wraper">
                            <ul id="file-list-one" class="file-list">
                                <li>
<!--                                    <p>身份证照片</p>-->
<!--                                    -->
                                    <?php
                                    $t = new \common\models\UploadFile();
                                    if($model->id_card_pic_one){ ?>
                                        <?= Html::img($t->getUrl($model->id_card_pic_one),['style'=>'max-width:500px;max-height:500px;']); ?>
                                    <?php }else{ ?>
                                        <?= Html::img('@web/img/image.png'); ?>
                                    <?php } ?>
                                </li>


                            </ul>
                            <div class="btn-wraper">
                                <input type="button" value="选择文件..." id="browse-one"/>
                                <button id="start_upload_one" type="button">开始上传</button>
                            </div>
                        </div>
                    </div>
                </div>

                <?php /*if ($model->username == 'admin'): */?><!--
                    <?/*= $form->field($model->usergroup, 'item_name')->dropDownList($item, ['disabled' => true])->label('用户组') */?>
                <?php /*else: */?>
                    <?/*= $form->field($model->usergroup, 'item_name')->dropDownList($item)->label('用户组') */?>
                --><?php /*endif; */?>
                <div class="form-group">
                    <?= Html::submitButton('保存', ['class' => 'btn btn-primary']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>

</div>

<?= \yii\bootstrap\Html::jsFile('@web/js/plugins/layer/layer.min.js') ?>
<?= \yii\bootstrap\Html::jsFile('@web/js/plugins/puupload/plupload.full.min.js') ?>
    <script>
        var loading = null;
        function loadinit($name) {

            var uploader = new plupload.Uploader({ //实例化一个plupload上传对象
                browse_button: 'browse-' + $name,
                url: '<?= Yii::$app->getUrlManager()->createUrl(['stores/upload']);?>',

                silverlight_xap_url: 'js/Moxie.xap',
                filters: {
                    mime_types: [ //只允许上传图片文件
                        {title: "图片文件", extensions: "jpg,gif,png"}
                    ]
                },
                multipart_params: {
                    '_csrf-backend': '<?= Yii::$app->getRequest()->getCsrfToken(); ?>'
                }
            });
            uploader.init(); //初始化

            //绑定文件添加进队列事件
            uploader.bind('FilesAdded', function (uploader, files) {
                for (var i = 0, len = files.length; i < len; i++) {
                    //构造html来更新UI
                    !function (i) {
                        previewImage(files[i], function (imgsrc) {
                            $('#file-list-' + $name + ' li img').replaceWith('<img src="' + imgsrc + '" />');
                        })
                    }(i);
                }
            });

            uploader.bind('FileUploaded', function (uploader, file, responseObject) {
                layer.close(loading);

                layer.msg('上传成功', {icon: 1});
                var key = responseObject.response;
                $('#user-id_card_pic_' + $name).val(key);
            });

            //plupload中为我们提供了mOxie对象
            //有关mOxie的介绍和说明请看：https://github.com/moxiecode/moxie/wiki/API
            //如果你不想了解那么多的话，那就照抄本示例的代码来得到预览的图片吧
            function previewImage(file, callback) {//file为plupload事件监听函数参数中的file对象,callback为预览图片准备完成的回调函数
                if (!file || !/image\//.test(file.type)) return; //确保文件是图片
                if (file.type == 'image/gif') {//gif使用FileReader进行预览,因为mOxie.Image只支持jpg和png
                    var fr = new mOxie.FileReader();
                    fr.onload = function () {
                        callback(fr.result);
                        fr.destroy();
                        fr = null;
                    }
                    fr.readAsDataURL(file.getSource());
                } else {
                    var preloader = new mOxie.Image();
                    preloader.onload = function () {
                        preloader.downsize(300, 300);//先压缩一下要预览的图片,宽300，高300
                        var imgsrc = preloader.type == 'image/jpeg' ? preloader.getAsDataURL('image/jpeg', 80) : preloader.getAsDataURL(); //得到图片src,实质为一个base64编码的数据
                        callback && callback(imgsrc); //callback传入的参数为预览图片的url
                        preloader.destroy();
                        preloader = null;
                    };
                    preloader.load(file.getSource());
                }
            }

            document.getElementById('start_upload_' + $name).onclick = function () {
                loading = layer.load(3);
                uploader.start(); //调用实例对象的start()方法开始上传文件，当然你也可以在其他地方调用该方法
            }
        }

        loadinit('one');
    </script>
<script>

    //如果是销售岗位显示上级主管下拉列表和地区
    $('#user-department_id').change(function(){

        if($('#user-department_id').val()==26){
            $('#user-leader').removeClass('hidden');
        }else{
            $('#user-leader').addClass('hidden');
            $('#user-province').addClass('hidden');
            $('#user-city').addClass('hidden');
            $('#user-county').addClass('hidden');
        }
    });
    //根据销售岗位选择省市区可选区域
    $('#user-job_id').change(function(){
        var job=$('#user-job_id').val();
        if(job==46){  //销售总监和大区经理 不用选择省市区
            $('#leader').addClass('hidden');
            $('#user-province').addClass('hidden');
            $('#user-city').addClass('hidden');
            $('#user-county').addClass('hidden');
        }else if(job==47){       //城市经理只用选择省
            $('#leader').removeClass('hidden');
            $('#user-province').removeClass('hidden');
            $('#user-city').addClass('hidden');
            $('#user-county').addClass('hidden');
        }else if(job==48 || job==49){     //销售经理需要选择省,市
            $('#leader').removeClass('hidden');
            $('#user-province').removeClass('hidden');
            $('#user-city').removeClass('hidden');
            $('#user-county').addClass('hidden');
        }else if(job>=50){       //销售人员需要选择省市区
            $('#leader').removeClass('hidden');
            $('#user-province').removeClass('hidden');
            $('#user-city').removeClass('hidden');
            $('#user-county').removeClass('hidden');
        }
    });
</script>

<?php
$this->registerJs('
    //$("#user-province").trigger("change");
    $("#user-department_id").trigger("change");
    $("#user-job_id").trigger("change");
    
    var url = "'.Url::toRoute(['user/get-sub-addr']).'"; // 获取子地区
    var url_jobs = "'.Url::toRoute(['user/get-jobs']).'"; // 获取职位
    var url_leader = "'.Url::toRoute(['user/get-leader']).'"; //获取上级
    
    // 部门变化
    $("#user-department_id").change(function(){
        var d_id = $(this).val();
        $.get(url_jobs, {d_id:d_id}, function(data){
            var dom  = "";
            $.each(data, function(k ,v){
                dom += "<option value="+k+">"+v+"</option>";
            })
            $("#user-job_id").html(dom);
            
            
        });
    });

    $("#user-job_id").click(function(){
        $("#user-province").val(1);
        $("#user-province").trigger("change");
    });
    
    
    // 省变化
    $("#user-province").change(function(){
        var province_id = $(this).val();
        $.get(url, {p_id:province_id}, function(data){
            var dom  = createDoms(data);
            $("#user-city").html(dom);
            getLeader("province",1); //大区经理上级
            $("#user-city").trigger("change");
        });     
    });
    
    // 市变化
    $("#user-city").change(function(){
        var city_id = $(this).val();
        $.get(url, {p_id:city_id}, function(data){
            var dom  = createDoms(data);
            $("#user-county").html(dom);
        });
        getLeader("province",$("#user-province").val());  //城市经理上级
    });
    
    //县变化
    $("#user-county").change(function(){
        if($("#user-job_id").val()<53){
            getLeader("city",$("#user-city").val());
        }else{
            getLeader("county",$("#user-county").val());
        }
    });
    
    //获取上级领导名称
    function getLeader(cityName,cityId){
        var level =$("#user-job_id").val();
        var postData={
            cityName:cityName,
            cityId:cityId,
            leader:level,
        };
        $.get(url_leader, postData, function(data){
            var dom =  createDom(data);
            $("#user-leader").html(dom);
        })
    }
    
    // 专业造dom
    function createDoms(data,t){
        var dom = "<option  value="+0+">全部</option>";
        $.each(data, function (k, v) {
            dom += "<option value="+k+">"+v+"</option>";
        })
        return dom;
    }
    
    function createDom(data){
        var dom = "";
        for(var l in data){
            dom += "<option value="+data[l].id+">"+data[l].realname+"</option>";
        }
            
        return dom;
    }
    
    // 初始化
    
 ');


?>