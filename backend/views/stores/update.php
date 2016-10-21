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
            <?= $form->field($model, 's_bank_num')->textInput(['class'=>'form-control']); ?>
            <?= $form->field($model, 's_bank_sub')->textInput(['class'=>'form-control']); ?>
            <?= $form->field($model, 's_bank_addr')->textInput(['class'=>'form-control']); ?>
            <?= $form->field($model, 's_bank_name')->textInput(['class'=>'form-control']); ?>
            <?= $form->field($model, 's_bank_is_private')->dropDownList([1=>$is_private_bank[1],0=>$is_private_bank[0]])->label('是否对私账户'); ?>
            <?= $form->field($model, 's_gov_name')->textInput(['class'=>'form-control']); ?>
            <?= $form->field($model, 's_service_charge')->textInput(['class'=>'form-control']); ?>
            <?= $form->field($model, 's_addr')->textInput(['class'=>'form-control']); ?>
            <?= $form->field($model, 's_status')->dropDownList($store_status,['class'=>'form-control'])->label('状态'); ?>
            <?= $form->field($model, 's_province')->dropDownList($all_provinces, ['class'=>'form-control getpcc', 'data-id'=>2]); ?>
            <?= $form->field($model, 's_city')->dropDownList([], ['class'=>'form-control getpcc', 'default-value'=>$model->s_city]); ?>
            <?= $form->field($model, 's_county')->dropDownList([], ['class'=>'form-control getpcc', 'default-value'=>$model->s_county]); ?>
            <?= $form->field($model, 's_remark')->textarea(['class'=>'form-control']); ?>

            <?= $form->field($model, 's_photo_one')->hiddenInput(['class'=>'form-control'])->label('');; ?>
            <?= $form->field($model, 's_photo_two')->hiddenInput(['class'=>'form-control'])->label('');?>
            <?= $form->field($model, 's_photo_three')->hiddenInput(['class'=>'form-control'])->label('');?>
            <?= $form->field($model, 's_photo_four')->hiddenInput(['class'=>'form-control'])->label('');?>
            <?= $form->field($model, 's_photo_five')->hiddenInput(['class'=>'form-control'])->label('');?>



            <div class="form-group field-stores-s_remark required">
                <label class="control-label col-sm-3" for="stores-s_remark">证件照</label>
                <div class="col-sm-9">
                    <div class="wraper">
                        <ul id="file-list-one" class="file-list">
                            <li>
                                <p>身份证正面照</p>
                                <?php if($model->s_photo_one){ ?>
                                <img src="<?=$t->handle->getLink($model->s_photo_one);?>" alt="">
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
                                <p>身份证背面照</p>
                                <?php if($model->s_photo_two){ ?>
                                    <img src="<?=$t->handle->getLink($model->s_photo_two);?>" alt="">
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
                                <p>身份证背面照</p>
                                <?php if($model->s_photo_two){ ?>
                                    <img src="<?=$t->handle->getLink($model->s_photo_three);?>" alt="">
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
                        <ul id="file-list-four" class="file-list">
                            <li>
                                <p>身份证背面照</p>
                                <?php if($model->s_photo_two){ ?>
                                    <img src="<?=$t->handle->getLink($model->s_photo_four);?>" alt="">
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
                                <p>身份证背面照</p>
                                <?php if($model->s_photo_two){ ?>
                                    <img src="<?=$t->handle->getLink($model->s_photo_five);?>" alt="">
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
<script>
 /*   var test1 = {
        ajax: '/tools/get-pcc',
        select: '#stores-s_city',
        defVal: [5, 256, 257],
        dataReader: {name:'region_name', callcode:'region_id'}
    };
    var linkageSel = new LinkageSel(test1);*/
//    setTimeout(function(){
//        $('#stores-s_city').addClass('getpcc');
////        $('#stores-s_county').addClass('getpcc');
//    }, 1000);
//    setTimeout(function(){
////        $('#stores-s_city').addClass('getpcc');
//        $('#stores-s_county').addClass('getpcc');
//    }, 2000);

</script>
<script src="/statics/plugins/layer/layer.js"></script>
<script src="/statics/plugins/puupload/plupload.full.min.js"></script>

<script>
    function loadinit($name){
        var uploader = new plupload.Uploader({ //实例化一个plupload上传对象
            browse_button : 'browse-' + $name,
            url : '<?= Yii::$app->getUrlManager()->createUrl(['stores/upload']);?>',

            silverlight_xap_url : 'js/Moxie.xap',
            filters: {
                mime_types : [ //只允许上传图片文件
                    { title : "图片文件", extensions : "jpg,gif,png" }
                ]
            },
            multipart_params: {
                _csrf: '<?= Yii::$app->getRequest()->getCsrfToken(); ?>'
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
            uploader.start(); //调用实例对象的start()方法开始上传文件，当然你也可以在其他地方调用该方法
        }
    }

    loadinit('one');
    loadinit('two');
    loadinit('three');
    loadinit('four');
    loadinit('five');
</script>
