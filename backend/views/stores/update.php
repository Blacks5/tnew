<?php
//app\assets\MainAsset::register($this);
$this->params['breadcrumbs'][] = ['label' => '商户列表', 'url' => ['index']];
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
$t = new \common\models\UploadFile();
?>
<style>
    .wraper{
        float: left;
        margin-left: 30px;
        text-align: center;
    }

    .file-list{
        padding: 0px;
    }

    .file-list li{
        list-style: none;
    }

    .file-list li img{
        width: 200px;
        height: 148px;
    }

    .file-list li p{
        text-align: center;
        margin:0px;
    }
</style>
    <link rel="stylesheet" href="/statics/css/animate.min.css">
    <link rel="stylesheet" href="/statics/css/style.min.css">
    <div class="ibox float-e-margins">
        <div class="ibox-content">
            <?= $form->field($model, 's_name')->textInput(['class'=>'form-control'])/*->hint(' 这里', ['class'=>'fa fa-info-circle'])*/; ?>
            <?= $form->field($model, 's_owner_name')->textInput(['class'=>'form-control']); ?>
            <?= $form->field($model, 's_owner_phone')->textInput(['class'=>'form-control']); ?>
            <?= $form->field($model, 's_owner_email')->textInput(['class'=>'form-control']); ?>
            <?= $form->field($model, 's_bank_people_name')->textInput(['class'=>'form-control']); ?>
            <?= $form->field($model, 's_idcard_num')->textInput(['class' => 'form-control']); ?>
            <?= $form->field($model, 's_bank_num')->textInput(['class'=>'form-control']); ?>
            <?= $form->field($model, 's_bank_sub')->textInput(['class'=>'form-control']); ?>
            <?= $form->field($model, 's_bank_addr')->textInput(['class'=>'form-control']); ?>
            <?= $form->field($model, 's_bank_name')->dropDownList($stores_banklist,['class'=>'form-control', 'default-value'=>$model->s_bank_name]); ?>
            <?= $form->field($model, 's_bank_is_private')->dropDownList([1=>$is_private_bank[1],0=>$is_private_bank[0]])->label('是否对私账户'); ?>
            <?= $form->field($model, 's_gov_name')->textInput(['class'=>'form-control']); ?>
            <?= $form->field($model, 's_service_charge')->textInput(['class'=>'form-control']); ?>
            <?= $form->field($model, 's_addr')->textInput(['class'=>'form-control']); ?>
            <?= $form->field($model, 's_real_addr')->textInput(['class'=>'form-control']); ?>
            <?php if(in_array($model->s_status,array(0,2,4))){ ?>
                <div class="form-group field-stores-s_addr required">
                    <label class="control-label col-sm-3" for="stores-s_addr">状态</label>
                    <div class="col-sm-5">
                        <input type="text" id="stores-s_status" class="form-control"  disabled="disabled" name="Stores[s_status]" value="<?php echo($store_status[$model->s_status]); ?>">
                        <div class="help-block help-block-error "></div>
                    </div>
                </div>
            <?php }else{ ?>
                <?= $form->field($model, 's_status')->dropDownList([3 => '待激活',1=>'审核拒绝',10=>'激活'])->label('状态'); ?>
            <?php } ?>
            <?= $form->field($model, 's_province')->dropDownList($all_provinces, ['class'=>'form-control getpcc', 'data-id'=>2]); ?>
            <?= $form->field($model, 's_city')->dropDownList($all_citys, ['class'=>'form-control getpcc', 'default-value'=>$model->s_city]); ?>
            <?= $form->field($model, 's_county')->dropDownList($all_countys, ['class'=>'form-control getpcc', 'default-value'=>$model->s_county]); ?>
            <?= $form->field($model, 's_remark')->textarea(['class'=>'form-control']); ?>

            <?= $form->field($model, 's_photo_one')->hiddenInput(['class'=>'form-control'])->label('');; ?>
            <?= $form->field($model, 's_photo_two')->hiddenInput(['class'=>'form-control'])->label('');?>
            <?= $form->field($model, 's_photo_three')->hiddenInput(['class'=>'form-control'])->label('');?>
            <?= $form->field($model, 's_photo_four')->hiddenInput(['class'=>'form-control'])->label('');?>
            <?= $form->field($model, 's_photo_five')->hiddenInput(['class'=>'form-control'])->label('');?>
            <?= $form->field($model, 's_photo_six')->hiddenInput(['class'=>'form-control'])->label('');?>
            <?= $form->field($model, 's_photo_seven')->hiddenInput(['class'=>'form-control'])->label('');?>
            <?= $form->field($model, 's_photo_eight')->hiddenInput(['class'=>'form-control'])->label('');?>
            <?= $form->field($model, 's_photo_nine')->hiddenInput(['class'=>'form-control'])->label('');?>



            <div class="form-group field-stores-s_remark required">
                <label class="control-label col-sm-3" for="stores-s_remark">证件照</label>
                <div class="col-sm-9">
                    <div class="wraper">
                        <ul id="file-list-one" class="file-list">
                            <li>
                                <p>营业执照</p>
                                <?php if($model->s_photo_one){ ?>
                                <img src="<?=$t->getUrl($model->s_photo_one);?>" alt="">
                                <?php }else{ ?>
                                    <img src="/statics/images/image.png" alt="">
                                <?php } ?>
                            </li>
                        </ul>
                        <div class="btn-wraper">
                            <input type="button" value="选择文件..." id="browse-one" />
                            <button id="start_upload_one" type="button">开始上传</button>
                        </div>
                    </div>

                    <div class="wraper">
                        <ul id="file-list-two" class="file-list">
                            <li>
                                <p>开户许可证</p>
                                <?php if($model->s_photo_two){ ?>
                                    <img src="<?=$t->getUrl($model->s_photo_two);?>" alt="">
                                <?php }else{ ?>
                                    <img src="/statics/images/image.png" alt="">
                                <?php } ?>
                            </li>
                        </ul>
                        <div class="btn-wraper">
                            <input type="button" value="选择文件..." id="browse-two" />
                            <button id="start_upload_two" type="button">开始上传</button>
                        </div>
                    </div>
                    <div class="wraper">
                        <ul id="file-list-three" class="file-list">
                            <li>
                                <p>法人身份证</p>
                                <?php if($model->s_photo_three){ ?>
                                    <img src="<?=$t->getUrl($model->s_photo_three);?>" alt="">
                                <?php }else{ ?>
                                    <img src="/statics/images/image.png" alt="">
                                <?php } ?>
                            </li>
                        </ul>
                        <div class="btn-wraper">
                            <input type="button" value="选择文件..." id="browse-three" />
                            <button id="start_upload_three" type="button">开始上传</button>
                        </div>
                    </div>
                    <div class="wraper">
                        <ul id="file-list-six" class="file-list">
                            <li>
                                <p>法人身份证反面</p>
                                <?php if($model->s_photo_six){ ?>
                                    <img src="<?=$t->getUrl($model->s_photo_six);?>" alt="">
                                <?php }else{ ?>
                                    <img src="/statics/images/image.png" alt="">
                                <?php } ?>
                            </li>
                        </ul>
                        <div class="btn-wraper">
                            <input type="button" value="选择文件..." id="browse-six" />
                            <button id="start_upload_six" type="button">开始上传</button>
                        </div>
                    </div>
                    <div class="wraper">
                        <ul id="file-list-seven" class="file-list">
                            <li>
                                <p>合同照</p>
                                <?php if($model->s_photo_seven){ ?>
                                    <img src="<?=$t->getUrl($model->s_photo_seven);?>" alt="">
                                <?php }else{ ?>
                                    <img src="/statics/images/image.png" alt="">
                                <?php } ?>
                            </li>
                        </ul>
                        <div class="btn-wraper">
                            <input type="button" value="选择文件..." id="browse-seven" />
                            <button id="start_upload_seven" type="button">开始上传</button>
                        </div>
                    </div>
                    <div class="wraper">
                        <ul id="file-list-four" class="file-list">
                            <li>
                                <p>门店照片</p>
                                <?php if($model->s_photo_four){ ?>
                                    <img src="<?=$t->getUrl($model->s_photo_four);?>" alt="">
                                <?php }else{ ?>
                                    <img src="/statics/images/image.png" alt="">
                                <?php } ?>
                            </li>
                        </ul>
                        <div class="btn-wraper">
                            <input type="button" value="选择文件..." id="browse-four" />
                            <button id="start_upload_four" type="button">开始上传</button>
                        </div>
                    </div>
                    <div class="wraper">
                        <ul id="file-list-five" class="file-list">
                            <li>
                                <p>店内照片</p>
                                <?php if($model->s_photo_five){ ?>
                                    <img src="<?=$t->getUrl($model->s_photo_five);?>" alt="">
                                <?php }else{ ?>
                                    <img src="/statics/images/image.png" alt="">
                                <?php } ?>
                            </li>
                        </ul>
                        <div class="btn-wraper">
                            <input type="button" value="选择文件..." id="browse-five" />
                            <button id="start_upload_five" type="button">开始上传</button>
                        </div>
                    </div>
                    <div class="wraper">
                        <ul id="file-list-eight" class="file-list">
                            <li>
                                <p>现场照</p>
                                <?php if($model->s_photo_eight){ ?>
                                    <img src="<?=$t->getUrl($model->s_photo_eight);?>" alt="">
                                <?php }else{ ?>
                                    <img src="/statics/images/image.png" alt="">
                                <?php } ?>
                            </li>
                        </ul>
                        <div class="btn-wraper">
                            <input type="button" value="选择文件..." id="browse-eight" />
                            <button id="start_upload_eight" type="button">开始上传</button>
                        </div>
                    </div>
                    <div class="wraper">
                        <ul id="file-list-nine" class="file-list">
                            <li>
                                <p>授权书</p>
                                <?php if($model->s_photo_nine){ ?>
                                    <img src="<?=$t->getUrl($model->s_photo_nine);?>" alt="">
                                <?php }else{ ?>
                                    <img src="/statics/images/image.png" alt="">
                                <?php } ?>
                            </li>
                        </ul>
                        <div class="btn-wraper">
                            <input type="button" value="选择文件..." id="browse-nine" />
                            <button id="start_upload_nine" type="button">开始上传</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-8 col-sm-offset-3">
                    <?= \yii\helpers\Html::submitButton('提交', ['class' => 'btn btn-primary']); ?>
                    <?= \yii\helpers\Html::a('返回', Yii::$app->getUrlManager()->createUrl(['stores/index']),['class' => 'btn btn-default']); ?>
                </div>
            </div>
        </div>
    </div>
<?php ActiveForm::end(); ?>
<?= \yii\bootstrap\Html::jsFile('@web/js/plugins/layer/layer.min.js') ?>
<?=\yii\bootstrap\Html::jsFile('@web/js/plugins/puupload/plupload.full.min.js')?>

<script>
    var loading = null;
    function loadinit($name){

        var uploader = new plupload.Uploader({ //实例化一个plupload上传对象
            browse_button : 'browse-' + $name,
            url : '<?= Yii::$app->getUrlManager()->createUrl(['stores/upload']);?>',

            silverlight_xap_url : 'js/Moxie.xap',
            filters: {
                mime_types : [ //只允许上传图片文件
                    { title : "图片文件", extensions : "jpg,gif,png,jpeg" }
                ]
            },
            multipart_params: {
                '_csrf-backend': '<?= Yii::$app->getRequest()->getCsrfToken(); ?>'
            }
        });
        uploader.init(); //初始化

        //绑定文件添加进队列事件
        uploader.bind('FilesAdded',function(uploader,files){
            for(var i = 0, len = files.length; i<len; i++){
                //构造html来更新UI
                !function(i){
                    previewImage(files[i],function(imgsrc){
                        $('#file-list-'+$name+' li img').replaceWith('<img src="'+ imgsrc +'" />');
                    })
                }(i);
            }
        });

        uploader.bind('FileUploaded',function(uploader,file,responseObject){
            layer.close(loading);

            layer.msg('上传成功', {icon: 1});
            var key = responseObject.response;
            $('#stores-s_photo_'+$name).val(key);
        });

        //plupload中为我们提供了mOxie对象
        //有关mOxie的介绍和说明请看：https://github.com/moxiecode/moxie/wiki/API
        //如果你不想了解那么多的话，那就照抄本示例的代码来得到预览的图片吧
        function previewImage(file,callback){//file为plupload事件监听函数参数中的file对象,callback为预览图片准备完成的回调函数
            if(!file || !/image\//.test(file.type)) return; //确保文件是图片
            if(file.type=='image/gif'){//gif使用FileReader进行预览,因为mOxie.Image只支持jpg和png
                var fr = new mOxie.FileReader();
                fr.onload = function(){
                    callback(fr.result);
                    fr.destroy();
                    fr = null;
                }
                fr.readAsDataURL(file.getSource());
            }else{
                var preloader = new mOxie.Image();
                preloader.onload = function() {
                    preloader.downsize( 300, 300 );//先压缩一下要预览的图片,宽300，高300
                    var imgsrc = preloader.type=='image/jpeg' ? preloader.getAsDataURL('image/jpeg',80) : preloader.getAsDataURL(); //得到图片src,实质为一个base64编码的数据
                    callback && callback(imgsrc); //callback传入的参数为预览图片的url
                    preloader.destroy();
                    preloader = null;
                };
                preloader.load( file.getSource() );
            }
        }

        document.getElementById('start_upload_'+$name).onclick = function(){
            loading = layer.load(3);
            uploader.start(); //调用实例对象的start()方法开始上传文件，当然你也可以在其他地方调用该方法
        }
    }

    loadinit('one');
    loadinit('two');
    loadinit('three');
    loadinit('four');
    loadinit('five');
    loadinit('six');
    loadinit('seven');
    loadinit('eight');
    loadinit('nine');
</script>
<?php
$this->registerJs('
    var url = "'.\yii\helpers\Url::toRoute(['user/get-sub-addr']).'"; // 获取子地区


    // 省变化
    $("#stores-s_province").change(function(){
        var province_id = $(this).val();
        $.get(url, {p_id:province_id}, function(data){
            var dom  = createDom(data);
            $("#stores-s_city").html(dom);

            $("#stores-s_city").trigger("change");
        });
    });

    // 市变化
    $("#stores-s_city").change(function(){
        var city_id = $(this).val();
        $.get(url, {p_id:city_id}, function(data){
            var dom  = createDom(data);
            $("#stores-s_county").html(dom);
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
//    $("#stores-s_province").trigger("change");
 ');
?>