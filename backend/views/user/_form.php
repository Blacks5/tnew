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
    <?= $form->field($model, 'id_card_num')->textInput(['maxlength' => true])->label('身份证号码') ?>
    <?= $form->field($model, 'address')->textInput(['maxlength' => true])->label('联系地址') ?>
    <?= $form->field($model, 'cellphone')->textInput(['maxlength' => true])->label('手机号码') ?>
    <input type="password" id="user-password_hash_fuck" name="User[password_hash]" style="display: none">
    <?= $form->field($model, 'password_hash')->passwordInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'password_hash_1')->passwordInput(['maxlength' => true])->label('重复密码') ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
    <div class="row">
        <div class="col-sm-2">
            <?= $form->field($model, 'department_id', ['options' => ['class' => 'form-group']])->dropDownList($all_departments)->label('请选择部门', ['class' => 'sr-only']) ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'job_id', ['options' => ['class' => 'form-group']])->dropDownList(['请选择职位'])->label('请选择职位', ['class' => 'sr-only']) ?>
        </div>
        <div class="col-sm-1">
            <?= $form->field($model, 'province', ['options' => ['class' => 'form-group']])->dropDownList($all_province)->label('请选择省', ['class' => 'sr-only']) ?>
        </div>
        <div class="col-sm-1">
            <?= $form->field($model, 'city', ['options' => ['class' => 'form-group']])->dropDownList(['请选择市'])->label('请选择城市', ['class' => 'sr-only']) ?>
        </div>
        <div class="col-sm-1">
            <?= $form->field($model, 'county', ['options' => ['class' => 'form-group']])->dropDownList(['请选择县/区'])->label('请选择城县/区', ['class' => 'sr-only']) ?>
        </div>
        <div class="col-sm-1">
            <?= $form->field($model, 'leader')->dropDownList(['请选择直属领导'])->label('请选择直属领导', ['class' => 'sr-only']) ?>
        </div>
    </div>



    <?= $form->field($model, 'id_card_pic_one')->hiddenInput(['class'=>'form-group'])->label('');?>
    <div class="form-group field-stores-s_remark required">
        <label class="control-label col-sm-3" for="stores-s_remark">身份证照片</label>
        <div class="col-sm-9">
            <div class="wraper">
                <ul id="file-list-one" class="file-list">
                    <li>
                        <p>身份证照片</p>
                        <?= Html::img('@web/img/image.png'); ?>
                    </li>
                </ul>
                <div class="btn-wraper">
                    <input type="button" value="选择文件..." id="browse-one"/>
                    <button id="start_upload_one" type="button">开始上传</button>
                </div>
            </div>
        </div>
    </div>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '新增' : '更新', ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
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
       if(job==45){  //销售总监和大区经理 不用选择省市区
           $('#leader').addClass('hidden');
           $('#user-province').addClass('hidden');
           $('#user-city').addClass('hidden');
           $('#user-county').addClass('hidden');
       }else if(job==46){       //城市经理只用选择省
           $('#leader').removeClass('hidden');
           $('#user-province').removeClass('hidden');
           $('#user-city').addClass('hidden');
           $('#user-county').addClass('hidden');
       }else if(job==47 || job==48){     //销售经理需要选择省,市
           $('#leader').removeClass('hidden');
           $('#user-province').removeClass('hidden');
           $('#user-city').removeClass('hidden');
           $('#user-county').addClass('hidden');
       }else{       //销售人员需要选择省市区
           $('#leader').removeClass('hidden');
           $('#user-province').removeClass('hidden');
           $('#user-city').removeClass('hidden');
           $('#user-county').removeClass('hidden');
       }
    });
</script>

<?php
$this->registerJs('
    var url = "'.Url::toRoute(['user/get-sub-addr']).'"; // 获取子地区
    var url_jobs = "'.Url::toRoute(['user/get-jobs']).'"; // 获取职位
    var url_leader = "'.Url::toRoute(['user/get-leader']).'"; //获取上级
    
    // 部门变化
    $("#user-department_id").change(function(){
        var d_id = $(this).val();
        $.get(url_jobs, {d_id:d_id}, function(data){
            var dom  = "";
            $.each(data, function(k ,v){
                dom += "<option  value="+k+">"+v+"</option>";
            })
            $("#user-job_id").html(dom);
        });
    });
    
    // 省变化
    $("#user-province").change(function(){
        var province_id = $(this).val();
            if(province_id==1){
                $("#user-city").html("<option value="+0+">全部</option>");
            }else{
                $.get(url, {p_id:province_id}, function(data){  
                    var dom  = createDom(data);
                    $("#user-city").html(dom);
                });
                
               getLeader("province",1); //大区经理上级
                
            }
            $("#user-city").trigger("change");
    });
    
    // 市变化
    $("#user-city").change(function(){
        var city_id = $(this).val();
        if(city_id==0){
            $("#user-county").html("<option value="+0+">全部</option>");
        }else{
            $.get(url, {p_id:city_id}, function(data){
                var dom  = createDom(data);
                $("#user-county").html(dom);
            });
            
            getLeader("province",$("#user-province").val());  //城市经理上级
        }
    });
    
    //县变化
    $("#user-county").change(function(){
        if($("#user-job_id").val()<52){
        
            getLeader("city",$("#user-city").val());
        }else{
            getLeader("county",$("#user-county").val());
        }
    });
    
    //获取上级领导名称
    function getLeader(cityName,cityId){
        var leader =$("#user-job_id").val();
        var postData={
            cityName:cityName,
            cityId:cityId,
            leader:leader,
        };
        $.get(url_leader, postData, function(data){
            var dom =  createDoms(data);
            $("#user-leader").html(dom);
        })
    }
    
  
    function createDoms(data){
        var dom = "";
        for(var l in data){
            dom += "<option value="+data[l].id+">"+data[l].realname+"</option>";
        }
            
        return dom;
    }
    
    // 带默认选中非数据库项dom
    function createDom(data){
        var dom = "<option  value="+0+" selected>全部</option>";
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
