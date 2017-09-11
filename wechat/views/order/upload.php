<!DOCTYPE html>
<html>
<head>
    <title>天牛金融管理</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <meta name="description" content="">
    <link rel="stylesheet" href="/wechat/lib/weui.min.css">
    <link rel="stylesheet" href="/wechat/css/jquery-weui.css">
    <link rel="stylesheet" href="/wechat/css/core.css">
</head>
<body ontouchstart>
    <div class="weui-cells weui-cells_order">
        <div class="weui-order-preview">
            <div class="content-padded">
                <div class="weui-form-preview" data-o-id="<?=$order['o_id']?>" data-o-status="<?=$order['o_status']?>">
                    <div class="weui-form-preview__hd">
                        <label class="weui-form-preview__label"><?=$order['c_customer_name']?></label>
                        <em class="weui-form-preview__value"><?=$order['o_total_price']?>元/<?=$order['p_period']?>期</em>
                    </div>
                    <div class="weui-form-preview__bd">
                        <div class="weui-form-preview__item">
                            <label class="weui-form-preview__label">订单编号</label>
                            <span class="weui-form-preview__value"><?=$order['o_serial_id']?></span>
                        </div>
                        <div class="weui-form-preview__item">
                            <label class="weui-form-preview__label">总金额</label>
                            <span class="weui-form-preview__value"><?=$order['o_total_price']?>元</span>
                        </div>
                        <div class="weui-form-preview__item">
                            <label class="weui-form-preview__label">客户电话</label>
                            <span class="weui-form-preview__value"><?=$order['c_customer_cellphone']?></span>
                        </div>
                        <div class="weui-form-preview__item">
                            <label class="weui-form-preview__label">商品类型</label>
                            <span class="weui-form-preview__value"><?=$order['p_name']?></span>
                        </div>
                        <div class="weui-form-preview__item">
                            <label class="weui-form-preview__label">提交时间</label>
                            <span class="weui-form-preview__value"><?=$order['o_created_at']?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="weui-cells weui-cells_form weui-cells_upload">
            <div class="weui-cell">
                <div class="weui-cell__bd">
                    <?php if($order['o_status'] == \common\models\Orders::STATUS_NOT_COMPLETE) { ?>
                        <div class="weui-uploader">
                            <div class="weui-uploader__hd">
                                <p class="weui-uploader__title">身份证上传(正、背面)<span class="color-danger">*</span></p>
                                <div class="weui-uploader__info">0/2</div>
                            </div>
                            <div class="weui-progress">
                                <div class="weui-progress__bar">
                                    <div class="weui-progress__inner-bar js_progress"></div>
                                </div>
                            </div>
                            <br />
                            <div class="weui-uploader__bd">
                                <ul class="weui-uploader__files"></ul>
                                <div class="weui-uploader__input-box">
                                    <span class="weui-uploader__input" imgattr="oi_front_id"></span>
                                </div>
                                <div class="weui-uploader__input-box">
                                    <span class="weui-uploader__input" imgattr="oi_back_id"></span>
                                </div>
                            </div>
                        </div>
                        <div class="weui-uploader">
                            <div class="weui-uploader__hd">
                                <p class="weui-uploader__title">客户现场照上传<span class="color-danger">*</span></p>
                                <div class="weui-uploader__info">0/1</div>
                            </div>
                            <div class="weui-progress">
                                <div class="weui-progress__bar">
                                    <div class="weui-progress__inner-bar js_progress"></div>
                                </div>
                            </div>
                            <br />
                            <div class="weui-uploader__bd">
                                <ul class="weui-uploader__files"></ul>
                                <div class="weui-uploader__input-box">
                                    <span class="weui-uploader__input" imgattr="oi_customer"></span>
                                </div>
                            </div>
                        </div>
                        <div class="weui-uploader">
                            <div class="weui-uploader__hd">
                                <p class="weui-uploader__title">银行卡正面上传<span class="color-danger">*</span></p>
                                <div class="weui-uploader__info">0/1</div>
                            </div>
                            <div class="weui-progress">
                                <div class="weui-progress__bar">
                                    <div class="weui-progress__inner-bar js_progress"></div>
                                </div>
                            </div>
                            <br />
                            <div class="weui-uploader__bd">
                                <ul class="weui-uploader__files"></ul>
                                <div class="weui-uploader__input-box">
                                    <span class="weui-uploader__input" imgattr="oi_front_bank"></span>
                                </div>
                            </div>
                        </div>
                        <div class="weui-uploader">
                            <div class="weui-uploader__hd">
                                <p class="weui-uploader__title">授权书上传<span class="color-danger">*</span></p>
                                <div class="weui-uploader__info">0/1</div>
                            </div>
                            <div class="weui-progress">
                                <div class="weui-progress__bar">
                                    <div class="weui-progress__inner-bar js_progress"></div>
                                </div>
                            </div>
                            <br />
                            <div class="weui-uploader__bd">
                                <ul class="weui-uploader__files"></ul>
                                <div class="weui-uploader__input-box">
                                    <span class="weui-uploader__input" imgattr="oi_proxy_prove"></span>
                                </div>
                            </div>
                        </div>
                        <div class="weui-uploader">
                            <div class="weui-uploader__hd">
                                <p class="weui-uploader__title">户口本上传</p>
                                <div class="weui-uploader__info">0/2</div>
                            </div>
                            <div class="weui-progress">
                                <div class="weui-progress__bar">
                                    <div class="weui-progress__inner-bar js_progress"></div>
                                </div>
                            </div>
                            <br />
                            <div class="weui-uploader__bd">
                                <ul class="weui-uploader__files"></ul>
                                <div class="weui-uploader__input-box">
                                    <span class="weui-uploader__input" imgattr="oi_family_card_one"></span>
                                </div>
                                <div class="weui-uploader__input-box">
                                    <span class="weui-uploader__input" imgattr="oi_family_card_two"></span>
                                </div>
                            </div>
                        </div>
                        <div class="weui-uploader">
                            <div class="weui-uploader__hd">
                                <p class="weui-uploader__title">驾照上传</p>
                                <div class="weui-uploader__info">0/2</div>
                            </div>
                            <div class="weui-progress">
                                <div class="weui-progress__bar">
                                    <div class="weui-progress__inner-bar js_progress"></div>
                                </div>
                            </div>
                            <br />
                            <div class="weui-uploader__bd">
                                <ul class="weui-uploader__files"></ul>
                                <div class="weui-uploader__input-box">
                                    <span class="weui-uploader__input" imgattr="oi_driving_license_one"></span>
                                </div>
                                <div class="weui-uploader__input-box">
                                    <span class="weui-uploader__input" imgattr="oi_driving_license_two"></span>
                                </div>
                            </div>
                        </div>
                        <?php } else { ?>
                        <div class="weui-uploader">
                            <div class="weui-uploader__hd">
                                <p class="weui-uploader__title">合同上传<span class="color-danger">*</span></p>
                                <div class="weui-uploader__info">0/1</div>
                            </div>
                            <div class="weui-progress">
                                <div class="weui-progress__bar">
                                    <div class="weui-progress__inner-bar js_progress"></div>
                                </div>
                            </div>
                            <br />
                            <div class="weui-uploader__bd">
                                <ul class="weui-uploader__files"></ul>
                                <div class="weui-uploader__input-box">
                                    <span class="weui-uploader__input" imgattr="oi_after_contract"></span>
                                </div>
                            </div>
                        </div>
                        <div class="weui-uploader">
                            <div class="weui-uploader__hd">
                                <p class="weui-uploader__title">提货照上传<span class="color-danger">*</span></p>
                                <div class="weui-uploader__info">0/1</div>
                            </div>
                            <div class="weui-progress">
                                <div class="weui-progress__bar">
                                    <div class="weui-progress__inner-bar js_progress"></div>
                                </div>
                            </div>
                            <br />
                            <div class="weui-uploader__bd">
                                <ul class="weui-uploader__files"></ul>
                                <div class="weui-uploader__input-box">
                                    <span class="weui-uploader__input" imgattr="oi_pick_goods"></span>
                                </div>
                            </div>
                        </div>
                        <div class="weui-uploader">
                            <div class="weui-uploader__hd">
                                <p class="weui-uploader__title">串码照上传<span class="color-danger">*</span></p>
                                <div class="weui-uploader__info">0/1</div>
                            </div>
                            <div class="weui-progress">
                                <div class="weui-progress__bar">
                                    <div class="weui-progress__inner-bar js_progress"></div>
                                </div>
                            </div>
                            <br />
                            <div class="weui-uploader__bd">
                                <ul class="weui-uploader__files"></ul>
                                <div class="weui-uploader__input-box">
                                    <span class="weui-uploader__input" imgattr="oi_serial_num"></span>
                                </div>
                            </div>
                        </div>
                        <div class="weui-uploader">
                            <div class="weui-uploader__hd">
                                <p class="weui-uploader__title">商品串码<span class="color-danger">*</span></p>
                            </div>
                            <div class="weui-cell__bd">
                                <input class="weui-input" type="text" name="o_product_code" placeholder="请输入商品串码">
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="weui-cells_upload_btn">
        <div class="weui-btn-area">
          <a class="weui-btn weui-btn_primary" href="javascript:" id="submitBtn">提交保存</a>
        </div>
    </div>
</body>
<script src="/wechat/lib/jquery-2.1.4.js"></script>
<script src="/wechat/lib/fastclick.js"></script>
<script src="/wechat/js/jquery-weui.js"></script>
<script src="/wechat/js/jquery-weui-extend.js"></script>
<script src="https://res.wx.qq.com/open/js/jweixin-1.2.0.js"></script>
<script type="text/javascript">
$(function(){
    FastClick.attach(document.body);

    wx.config(<?php echo $js->config(['hideMenuItems' , 'chooseImage' , 'previewImage' , 'uploadImage' , 'downloadImage' , 'getLocalImgData']) ?>);

    // 订单状态
    const COMPLETE = <?=\common\models\Orders::STATUS_NOT_COMPLETE?>;
    const UPLOAD_AGAIN = <?=\common\models\Orders::STATUS_WAIT_APP_UPLOAD_AGAIN?>;

    // 提交保存接口
    var modifyUrl = "<?=Yii::$app->getUrlManager()->createUrl(['order/modify-order'])?>";

    wx.ready(function(){
        wx.hideMenuItems({
            menuList: [
                'menuItem:share:appMessage',
                'menuItem:share:timeline',
                'menuItem:share:qq',
                'menuItem:share:weiboApp',
                'menuItem:share:facebook',
                'menuItem:share:QZone',
                'menuItem:copyUrl',
                'menuItem:originPage',
                'menuItem:openWithQQBrowser',
                'menuItem:openWithSafari',
                'menuItem:share:email'
            ]
        });

        // 上传照片数据
        var post = {
            actionType : "upload",
            o_id : <?=$order['o_id']?>,
            o_status : <?=$order['o_status']?>,
            oi_front_id : "",
            oi_back_id : "",
            oi_customer : "",
            oi_front_bank : "",
            oi_family_card_one : "",
            oi_family_card_two : "",
            oi_driving_license_one : "",
            oi_driving_license_two : "",
            oi_pick_goods : "",
            oi_serial_num : "",
            oi_after_contract : "",
            oi_proxy_prove : "",
            o_product_code : ""
        };

        // 绑定上传图片
        $('.weui-uploader__input').bind('click' , function(){
            // 获取当前input
            var _that = $(this);
            // 获取uploader上传容器
            var uploaderContainer = _that.parents('.weui-uploader');
            // 获取图片显示容器
            var filesContainer = uploaderContainer.find('.weui-uploader__files');
            // 获取input容器
            var inputContainer = _that.parents('.weui-uploader__input-box');
            // 获取图片IDName
            var idName = _that.attr('imgattr');
            // 选取图片
            wx.chooseImage({
                count: 1,
                sizeType: ['original', 'compressed'],
                sourceType: ['camera'],
                success: function (res) {
                    // 隐藏当前input容器
                    inputContainer.hide();
                    // 插入到预览区  
                    var preview = $('<li class="weui-uploader__file weui-uploader__file_status"><img src="' + res.localIds[0] + '" style="width:100%;height:100%;"><div class="weui-uploader__file-content" style="font-size:12px">上传中</div></li>');
                    filesContainer.append(preview);

                    var currLocalIds = res.localIds[0];
                    
                    // 上传照片
                    wx.uploadImage({
                        localId: '' + res.localIds,
                        isShowProgressTips: 1,
                        success: function(res) {
                            $.toptip('上传成功', 'success');
                            post[idName] = res.serverId;
                            preview.removeClass('weui-uploader__file_status').find('.weui-uploader__file-content').remove();
                            preview.bind('click' , function(){
                                wx.previewImage({
                                    current: currLocalIds, // 当前显示图片的http链接
                                    urls: [currLocalIds] // 需要预览的图片http链接列表
                                });
                            });
                        },
                        fail : function(){
                            $.toptip('上传失败，稍后重试', 'warning');
                            preview.find('.weui-uploader__file-content').html('<i class="weui-icon-warn"></i>');
                        }
                    });
                },
                fail : function(){
                    $.toptip('上传失败，稍后重试', 'warning');
                }
            });
        });

        // 失去焦点绑定
        $('input[name=o_product_code]').bind('blur' , function(){
            post.o_product_code = $(this).val();
        });

        // 绑定提交
        $('#submitBtn').bind('click' , function(){
            if(post.o_status == COMPLETE || post.o_status == UPLOAD_AGAIN){
                $.ajaxPost(modifyUrl , post , function(res){
                    if(res.status){
                        $.toast(res.message, function(){
                            window.location = "<?= Yii::$app->getUrlManager()->createUrl(['order/wait-order-list'])?>"
                        });
                    }else{
                        $.toast(res.message, "text");
                    }
                });
            }else{
                $.toast('该订单不存在或已在审核', "text");
            }
        });
    });


    
    























    // function Page(){
    //     // 订单状态
    //     this.COMPLETE = <?=\common\models\Orders::STATUS_NOT_COMPLETE?>;
    //     this.UPLOAD_AGAIN = <?=\common\models\Orders::STATUS_WAIT_APP_UPLOAD_AGAIN?>;
    //     // 上传图片URL
    //     this.uploadUrl = "<?=Yii::$app->getUrlManager()->createUrl(['order/upload-image'])?>";
    //     // 提交保存
    //     this.modifyUrl = "<?=Yii::$app->getUrlManager()->createUrl(['order/modify-order'])?>";
    //     // 上传照片数据
    //     this.data = {
    //         actionType : "upload",
    //         o_id : <?=$order['o_id']?>,
    //         o_status : <?=$order['o_status']?>,
    //         oi_front_id : "<?=$order['oi_front_id']?>",
    //         oi_back_id : "<?=$order['oi_back_id']?>",
    //         oi_customer : "<?=$order['oi_customer']?>",
    //         oi_front_bank : "<?=$order['oi_front_bank']?>",
    //         oi_family_card_one : "<?=$order['oi_family_card_one']?>",
    //         oi_family_card_two : "<?=$order['oi_family_card_two']?>",
    //         oi_driving_license_one : "<?=$order['oi_driving_license_one']?>",
    //         oi_driving_license_two : "<?=$order['oi_driving_license_two']?>",
    //         oi_pick_goods : "<?=$order['oi_pick_goods']?>",
    //         oi_serial_num : "<?=$order['oi_serial_num']?>",
    //         oi_after_contract : "<?=$order['oi_after_contract']?>",
    //         oi_proxy_prove : "<?=$order['oi_proxy_prove']?>",
    //         o_product_code : ""
    //     };
    // }

    // Page.prototype.init = function(){
    //     var _this = this;
    //     // 图片上传绑定
    //     $('.weui-uploader__input').uploader({
    //         url : _this.url,
    //         allowTypes : ['image/jpg', 'image/jpeg', 'image/png', 'image/gif'],
    //         maxSize : 10*1024*1024,
    //         maxCount : 6,
    //         maxWidth : 320,
    //         inputName: 'image',
    //         data : { o_id : _this.data.o_id},
    //         upToken : "<?=$uptoken ?>",
    //         success : function(ele , res){
    //             var name = ele.attr('imgattr');
    //             _this.data[name] = res.key;
    //         },
    //         error : function(ele){
    //             console.log(ele);
    //         }
    //     });

    //     // 失去焦点绑定
    //     $('input[name=o_product_code]').bind('blur' , function(){
    //         _this.data.o_product_code = $(this).val();
    //     });

    //     // 绑定提交
    //     $('#submitBtn').bind('click' , function(){
    //         if(_this.data.o_status == _this.COMPLETE || _this.data.o_status == _this.UPLOAD_AGAIN){
    //             $.ajaxPost(_this.modifyUrl , _this.data , function(res){
    //                 if(res.status){
    //                     $.toast(res.message, function(){
    //                         window.location = "<?= Yii::$app->getUrlManager()->createUrl(['order/wait-order-list'])?>"
    //                     });
    //                 }else{
    //                     $.toast(res.message, "text");
    //                 }
    //             });
    //         }else{
    //             $.toast('该订单不存在或已在审核', "text");
    //         }
    //     });
    // }

    // // 实例化当前页面
    // var page = new Page;
    // page.init();
});
</script>
</html>