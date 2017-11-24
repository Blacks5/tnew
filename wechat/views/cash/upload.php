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
                <div class="weui-form-preview">
                    <div class="weui-form-preview__hd">
                        <label class="weui-form-preview__label"><?=$order['card']['name']?></label>
                        <em class="weui-form-preview__value"><?=\common\components\Helper::currency($order['periodAmount'], 2)?>元/<?=$order['period']?><?php echo $order['cycle'] == 'week' ? '周' : '月' ?></em>
                    </div>
                    <div class="weui-form-preview__bd">
                        <div class="weui-form-preview__item">
                            <label class="weui-form-preview__label">订单编号</label>
                            <span class="weui-form-preview__value"><?=$order['orderNumber']?></span>
                        </div>
                        <div class="weui-form-preview__item">
                            <label class="weui-form-preview__label">申请金额</label>
                            <span class="weui-form-preview__value"><?=\common\components\Helper::currency($order['expectedAmount'], 2)?>元</span>
                        </div>
                        <div class="weui-form-preview__item">
                            <label class="weui-form-preview__label">放款金额</label>
                            <span class="weui-form-preview__value">
                                <?php echo $order['acceptedAmount'] ? \common\components\Helper::currency($order['acceptedAmount'], 2) : '-' ?>元
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="weui-cells weui-cells_form weui-cells_upload">
            <div class="weui-cell">
                <div class="weui-cell__bd">
                    <?php if (in_array($order['orderStatus'], [2, 5])) {?>
                        <div class="weui-uploader">
                            <div class="weui-uploader__hd">
                                <p class="weui-uploader__title">身份证照片(正、反面)<span class="color-danger">*</span></p>
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
                                <div class="weui-uploader-box">
                                    <div class="weui-uploader__input-box">
                                        <span class="weui-uploader__input" imageType="1"></span>
                                    </div>
                                    <span class="uploader-tips">正面</span>
                                </div>
                                <div class="weui-uploader-box">
                                    <div class="weui-uploader__input-box">
                                        <span class="weui-uploader__input" imageType="2"></span>
                                    </div>
                                    <span class="uploader-tips">反面</span>
                                </div>
                            </div>
                        </div>
                        <div class="weui-uploader">
                            <div class="weui-uploader__hd">
                                <p class="weui-uploader__title">户口本照片(户主页、贷款人页)<span class="color-danger">*</span></p>
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
                                <div class="weui-uploader-box">
                                    <div class="weui-uploader__input-box">
                                        <span class="weui-uploader__input" imageType="3"></span>
                                    </div>
                                    <span class="uploader-tips">户主页</span>
                                </div>
                                <div class="weui-uploader-box">
                                    <div class="weui-uploader__input-box">
                                        <span class="weui-uploader__input" imageType="4"></span>
                                    </div>
                                    <span class="uploader-tips">贷款人页</span>
                                </div>
                            </div>
                        </div>
                        <div class="weui-uploader">
                            <div class="weui-uploader__hd">
                                <p class="weui-uploader__title">其它证件<span class="color-danger">*</span></p>
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
                                <div class="weui-uploader-box">
                                    <div class="weui-uploader__input-box">
                                        <span class="weui-uploader__input" imageType="5"></span>
                                    </div>
                                    <span class="uploader-tips">房产证或房屋租赁合同</span>
                                </div>
                                <div class="weui-uploader-box">
                                    <div class="weui-uploader__input-box">
                                        <span class="weui-uploader__input" imageType="6"></span>
                                    </div>
                                    <span class="uploader-tips">结婚证</span>
                                </div>
                            </div>
                        </div>
                        <div class="weui-uploader">
                            <div class="weui-uploader__hd">
                                <p class="weui-uploader__title">住房照片<span class="color-danger">*</span></p>
                                <div class="weui-uploader__info">0/6</div>
                            </div>
                            <div class="weui-progress">
                                <div class="weui-progress__bar">
                                    <div class="weui-progress__inner-bar js_progress"></div>
                                </div>
                            </div>
                            <br />
                            <div class="weui-uploader__bd">
                                <ul class="weui-uploader__files"></ul>
                                <div class="weui-uploader-box">
                                    <div class="weui-uploader__input-box">
                                        <span class="weui-uploader__input" imageType="7"></span>
                                    </div>
                                    <span class="uploader-tips">合照</span>
                                </div>
                                <div class="weui-uploader-box">
                                    <div class="weui-uploader__input-box">
                                        <span class="weui-uploader__input" imageType="8"></span>
                                    </div>
                                    <span class="uploader-tips">门牌照</span>
                                </div>
                                <div class="weui-uploader-box">
                                    <div class="weui-uploader__input-box">
                                        <span class="weui-uploader__input" imageType="9"></span>
                                    </div>
                                    <span class="uploader-tips">室内照1</span>
                                </div>
                                <div class="weui-uploader-box">
                                    <div class="weui-uploader__input-box">
                                        <span class="weui-uploader__input" imageType="10"></span>
                                    </div>
                                    <span class="uploader-tips">室内照2</span>
                                </div>
                                <div class="weui-uploader-box">
                                    <div class="weui-uploader__input-box">
                                        <span class="weui-uploader__input" imageType="11"></span>
                                    </div>
                                    <span class="uploader-tips">室内照3</span>
                                </div>
                                <div class="weui-uploader-box">
                                    <div class="weui-uploader__input-box">
                                        <span class="weui-uploader__input" imageType="12"></span>
                                    </div>
                                    <span class="uploader-tips">室内照4</span>
                                </div>
                            </div>
                        </div>
                        <div class="weui-uploader">
                            <div class="weui-uploader__hd">
                                <p class="weui-uploader__title">补充照片</p>
                                <div class="weui-uploader__info">0/4</div>
                            </div>
                            <div class="weui-progress">
                                <div class="weui-progress__bar">
                                    <div class="weui-progress__inner-bar js_progress"></div>
                                </div>
                            </div>
                            <br />
                            <div class="weui-uploader__bd">
                                <ul class="weui-uploader__files"></ul>
                                <div class="weui-uploader-box">
                                    <div class="weui-uploader__input-box">
                                        <span class="weui-uploader__input" imageType="13"></span>
                                    </div>
                                    <span class="uploader-tips">照片1</span>
                                </div>
                                <div class="weui-uploader-box">
                                    <div class="weui-uploader__input-box">
                                        <span class="weui-uploader__input" imageType="14"></span>
                                    </div>
                                    <span class="uploader-tips">照片2</span>
                                </div>
                                <div class="weui-uploader-box">
                                    <div class="weui-uploader__input-box">
                                        <span class="weui-uploader__input" imageType="15"></span>
                                    </div>
                                    <span class="uploader-tips">照片3</span>
                                </div>
                                <div class="weui-uploader-box">
                                    <div class="weui-uploader__input-box">
                                        <span class="weui-uploader__input" imageType="16"></span>
                                    </div>
                                    <span class="uploader-tips">照片4</span>
                                </div>
                            </div>
                        </div>
                        <?php } else {?>
                        <div class="weui-uploader">
                            <div class="weui-uploader__hd">
                                <p class="weui-uploader__title">还款小贴士<span class="color-danger">*</span></p>
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
                                <div class="weui-uploader-box">
                                    <div class="weui-uploader__input-box">
                                        <span class="weui-uploader__input" imageType="17"></span>
                                    </div>
                                    <span class="uploader-tips">签字照</span>
                                </div>
                                <div class="weui-uploader-box">
                                    <div class="weui-uploader__input-box">
                                        <span class="weui-uploader__input" imageType="18"></span>
                                    </div>
                                    <span class="uploader-tips">还款小贴士</span>
                                </div>
                            </div>
                        </div>
                    <?php }?>
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
    wx.config(<?php echo $js->config(['hideMenuItems', 'chooseImage', 'previewImage', 'uploadImage', 'downloadImage', 'getLocalImgData']) ?>);

    // 订单ID
    var orderId = <?=$orderId?>;
    // 当前订单状态
    var orderStatus = <?=$order['orderStatus']?>;

    // 待一审上传照片
    var ORDER_STATUS_FIRST_UPLOAD = 2;
    // 一审拒绝，重新上传
    var ORDER_STATUS_FIRST_REFUSE = 5;
    // 一审通过，待上传二审照片
    var ORDER_STATUS_SECOND_UPLOAD = 4;
    // 二审拒绝，重新上传
    var ORDER_STATUS_SECOND_REFUSE = 7;

    // 提交保存接口
    var uploadOrderImgUrl = "<?=Yii::$app->getUrlManager()->createUrl(['cash/upload-order-img'])?>";
    // 上传照片接口
    var uploadUrl = "<?=Yii::$app->getUrlManager()->createUrl(['cash/upload'])?>";
    // 上传成功
    var successUrl = "<?=Yii::$app->getUrlManager()->createUrl(['cash/success'])?>";

    // 一审必需上传的文件
    var firstPost = [
        {imageType : 1 , isMust : 1 , descText : '身份证正面'},
        {imageType : 2 , isMust : 1 , descText : '身份证反面'},
        {imageType : 3 , isMust : 1 , descText : '户口本户主页'},
        {imageType : 4 , isMust : 1 , descText : '户口本贷款人页'},
        {imageType : 5 , isMust : 1 , descText : '房产证或房屋租赁合同'},
        {imageType : 6 , isMust : 1 , descText : '结婚证'},
        {imageType : 7 , isMust : 1 , descText : '住房合照'},
        {imageType : 8 , isMust : 1 , descText : '住房门牌照'},
        {imageType : 9 , isMust : 1 , descText : '住房室内照1'},
        {imageType : 10 , isMust : 1 , descText : '住房室内照2'},
        {imageType : 11 , isMust : 1 , descText : '住房室内照3'},
        {imageType : 12 , isMust : 1 , descText : '住房室内照4'},
        {imageType : 13 , isMust : 1 , descText : '补充照片1'},
        {imageType : 14 , isMust : 1 , descText : '补充照片2'},
        {imageType : 15 , isMust : 1 , descText : '补充照片3'},
        {imageType : 16 , isMust : 1 , descText : '补充照片4'},
    ];
    // 二审必需上传的文件
    var secondPost = [
        {imageType : 17 , isMust : 1 , descText : '签字照'},
        {imageType : 18 , isMust : 1 , descText : '还款小贴士'},
    ];

    // 上传照片数据
    var imagesArr = new Array;

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

        // 绑定上传图片
        $('.weui-uploader__input').bind('click' , function(){
            // 获取当前input
            var _that = $(this);
            // 获取uploader上传容器
            var uploaderContainer = _that.parents('.weui-uploader');
            // 获取图片显示容器
            var filesContainer = uploaderContainer.find('.weui-uploader__files');
            // 获取input容器
            var inputContainer = _that.parents('.weui-uploader-box');
            // 获取图片数量容器
            var numContainer = uploaderContainer.find('.weui-uploader__info');
            // 获取最大图片上传数
            var maxCount = parseInt(numContainer.text().split('/')[1]);
            // 获取图片IDName
            var imageType = _that.attr('imageType');
            // 选取图片
            wx.chooseImage({
                count: 1,
                sizeType: ['compressed'],
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
                            // 请求网络
                            $.ajaxPost(uploadUrl , {mediaId:res.serverId} , function(res){
                                if(res.status){
                                    $.toptip('上传成功', 'success');

                                    // 临时存储上传文件
                                    imagesArr[imageType] = res.data.uuid;

                                    preview.removeClass('weui-uploader__file_status').find('.weui-uploader__file-content').remove();
                                    // 当前数量
                                    var currNum = parseInt(numContainer.text().split('/')[0]);
                                    numContainer.text((currNum+1) + '/' + maxCount);
                                    // 绑定查看
                                    preview.bind('click' , function(){
                                        wx.previewImage({
                                            current: currLocalIds, // 当前显示图片的http链接
                                            urls: [currLocalIds] // 需要预览的图片http链接列表
                                        });
                                    });
                                }else{
                                    $.toptip(res.message, 'warning');
                                    preview.find('.weui-uploader__file-content').html('<i class="weui-icon-warn"></i>');
                                }
                            } , 120000);
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

        // 绑定提交
        $('#submitBtn').bind('click' , function(){
            if(orderStatus == ORDER_STATUS_FIRST_UPLOAD || orderStatus == ORDER_STATUS_FIRST_REFUSE || orderStatus == ORDER_STATUS_SECOND_UPLOAD || orderStatus == ORDER_STATUS_SECOND_REFUSE){
                var post = [];

                // 一审上传检测
                if(orderStatus == ORDER_STATUS_FIRST_UPLOAD || orderStatus == ORDER_STATUS_FIRST_REFUSE){
                    for(var i = 0 ; i < firstPost.length ; i++){
                        var item = firstPost[i];
                        var imageType = item.imageType;
                        var isMust = item.isMust;
                        var descText = item.descText;

                        if(isMust && !imagesArr[imageType]){
                            $.toast('请上传' + descText, 'text');
                            return;
                        }
                        // 加入待上传的数据中
                        post.push(imageType + ':' + imagesArr[imageType]);
                    }
                }

                // 二审上传检测
                if(orderStatus == ORDER_STATUS_SECOND_UPLOAD || orderStatus == ORDER_STATUS_SECOND_REFUSE){
                    for(var i = 0 ; i < secondPost.length ; i++){
                        var item = secondPost[i];
                        var imageType = item.imageType;
                        var isMust = item.isMust;
                        var descText = item.descText;

                        if(isMust && !imagesArr[imageType]){
                            $.toast('请上传' + descText, 'text');
                            return;
                        }
                        // 加入待上传的数据中
                        post.push(imageType + ':' + imagesArr[imageType]);
                    }
                }

                $.ajaxPost(uploadOrderImgUrl , {orderId : orderId, images : post.join(',')} , function(res){
                    if(res.status){
                        $.toast(res.message, function(){
                            window.location = successUrl + "?orderId=" + orderId;
                        });
                    }else{
                        $.toast(res.message, "text");
                    }
                } , 120000);
            }else{
                $.toast('该订单不存在或已在审核', "text");
            }
        });
    });
});
</script>
</html>