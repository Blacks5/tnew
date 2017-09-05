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
                                    <input class="weui-uploader__input" type="file" accept="image/*" multiple="" imgattr="oi_front_id">
                                </div>
                                <div class="weui-uploader__input-box">
                                    <input class="weui-uploader__input" type="file" accept="image/*" multiple="" imgattr="oi_back_id">
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
                                    <input class="weui-uploader__input" type="file" accept="image/*" multiple="" imgattr="oi_customer">
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
                                    <input class="weui-uploader__input" type="file" accept="image/*" multiple="" imgattr="oi_front_bank">
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
                                    <input class="weui-uploader__input" type="file" accept="image/*" multiple="" imgattr="oi_proxy_prove">
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
                                    <input class="weui-uploader__input" type="file" accept="image/*" multiple="" imgattr="oi_after_contract">
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
                                    <input class="weui-uploader__input" type="file" accept="image/*" multiple="" imgattr="oi_pick_goods">
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
                                    <input class="weui-uploader__input" type="file" accept="image/*" multiple="" imgattr="oi_serial_num">
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
                                    <input class="weui-uploader__input" type="file" accept="image/*" multiple="" imgattr="oi_family_card_one">
                                </div>
                                <div class="weui-uploader__input-box">
                                    <input class="weui-uploader__input" type="file" accept="image/*" multiple="" imgattr="oi_family_card_two">
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
                                    <input class="weui-uploader__input" type="file" accept="image/*" multiple="" imgattr="oi_driving_license_one">
                                </div>
                                <div class="weui-uploader__input-box">
                                    <input class="weui-uploader__input" type="file" accept="image/*" multiple="" imgattr="oi_driving_license_two">
                                </div>
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
<script type="text/javascript">
$(function(){

    function Page(){
        // 订单状态
        this.COMPLETE = <?=\common\models\Orders::STATUS_NOT_COMPLETE?>;
        this.UPLOAD_AGAIN = <?=\common\models\Orders::STATUS_WAIT_APP_UPLOAD_AGAIN?>;
        // 上传图片URL
        this.uploadUrl = "<?=Yii::$app->getUrlManager()->createUrl(['order/upload-image'])?>";
        // 提交保存
        this.modifyUrl = "<?=Yii::$app->getUrlManager()->createUrl(['order/modify-order'])?>";
        // 上传照片数据
        this.data = {
            actionType : "upload",
            o_id : <?=$order['o_id']?>,
            o_status : <?=$order['o_status']?>,
            oi_front_id : "<?=$order['oi_front_id']?>",
            oi_back_id : "<?=$order['oi_back_id']?>",
            oi_customer : "<?=$order['oi_customer']?>",
            oi_front_bank : "<?=$order['oi_front_bank']?>",
            oi_family_card_one : "<?=$order['oi_family_card_one']?>",
            oi_family_card_two : "<?=$order['oi_family_card_two']?>",
            oi_driving_license_one : "<?=$order['oi_driving_license_one']?>",
            oi_driving_license_two : "<?=$order['oi_driving_license_two']?>",
            oi_pick_goods : "<?=$order['oi_pick_goods']?>",
            oi_serial_num : "<?=$order['oi_serial_num']?>",
            oi_after_contract : "<?=$order['oi_after_contract']?>",
            oi_proxy_prove : "<?=$order['oi_proxy_prove']?>",
        };
    }

    Page.prototype.init = function(){
        var _this = this;
        // 图片上传绑定
        $('.weui-uploader__input').uploader({
            url : _this.url,
            allowTypes : ['image/jpg', 'image/jpeg', 'image/png', 'image/gif'],
            maxSize : 10*1024*1024,
            maxCount : 6,
            maxWidth : 320,
            inputName: 'image',
            data : { o_id : _this.data.o_id},
            upToken : "<?=$uptoken ?>",
            success : function(ele , res){
                var name = ele.attr('imgattr');
                _this.data[name] = res.key;
            },
            error : function(ele){
                console.log(ele);
            }
        });

        // 绑定提交
        $('#submitBtn').bind('click' , function(){
            if(_this.data.o_status == _this.COMPLETE || _this.data.o_status == _this.UPLOAD_AGAIN){
                $.ajaxPost(_this.modifyUrl , _this.data , function(res){
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
    }

    // 实例化当前页面
    var page = new Page;
    page.init();
});
</script>
</html>