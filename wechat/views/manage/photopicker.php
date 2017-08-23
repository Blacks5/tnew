<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1,user-scalable=0">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta content="yes" name="apple-touch-fullscreen">
    <meta content="telephone=no,email=no" name="format-detection">
    <!--<link href="https://cdn.bootcss.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">-->
    <link rel="stylesheet" href="/wechat/style/weui.css"/>
    <title>首页</title>
</head>
<body>
<section class="ui-container">
    <div class="">
        <p class=weui-cells__title>图片自动上传</p>
        <div class="weui-cells weui-cells_form" id=uploader>
            <div class=weui-cell>
                <div class=weui-cell__bd>
                    <div class=weui-uploader>
                        <div class=weui-uploader__hd><p class=weui-uploader__title>图片上传</p>
                        <!--<div class=weui-uploader__info><span id=uploadCount>0</span>/5</div>-->
                        </div>

                        <form id="testform" method="post" enctype="multipart/form-data">
                            <div class=weui-uploader__bd>
                                <ul class=weui-uploader__files id=uploaderFiles>

                                </ul>
                                <div class=weui-uploader__input-box>
                                    <input name="key" id="key" type="hidden" value="bttq0y8axx.png">
                                    <input name="token" type="hidden" value="QWYn5TFQsLLU1pL5MFEmX3s5DmHdUThav9WyOWOm:ssda7u_8Uc70Sv-Z-6iBrHs1XV4=:eyJkZWxldGVBZnRlckRheXMiOjcsInNjb3BlIjoianNzZGsiLCJkZWFkbGluZSI6MTUwMzQ5MjQ5MH0=">
                                    <input name="accept" type="hidden">
                                    <input id="userfile" class=weui-uploader__input type=file accept=image/* capture=camera multiple=""/>
                                </div>
                            </div>
                        </form>
                        <!-- upload info -->
                        <div class="selected-file"></div>
                        <div class="progress"></div>
                        <div class="uploaded-result"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script src="/wechat/js/weui.js"></script>
<script src="https://cdn.staticfile.org/jquery/2.1.0/jquery.js"></script>
<script src="http://cdn.staticfile.org/plupload/2.1.5/plupload.full.min.js"></script>
<script src="https://cdn.staticfile.org/qiniu-js-sdk/1.0.14-beta/qiniu.js"></script>
<script>
    var domain = "http://7j1xky.com2.z0.glb.qiniucdn.com/"; // you bucket domain  eg: http://xxx.bkt.clouddn.com

    $(function(){
        var $key = $('#key');  // file name    eg: the file is image.jpg,but $key='a.jpg', you will upload the file named 'a.jpg'
        var $userfile = $('#userfile');  // the file you selected

        // upload info
        var $selectedFile = $('.selected-file');
        var $progress = $(".progress");
        var $uploadedResult = $('.uploaded-result');
        var $uploaderFiles = $('#uploaderFiles');

        $("#userfile").change(function() {  // you can ues 'onchange' here to uplpad automatically after select a file
            $uploadedResult.html('');
            var selectedFile = $userfile.val();
            if (selectedFile) {
                // randomly generate the final file name
                var ramdomName = Math.random().toString(36).substr(2) + $userfile.val().match(/\.?[^.\/]+$/);
                $key.val(ramdomName);
                $selectedFile.html('文件：' + selectedFile);
                var li_str = '<li class="weui-uploader__file weui-uploader__file_status" data-id="2" style="background-image: url('+ selectedFile +');"> </li>';
                $uploaderFiles.html(li_str);
            } else {
                return false;
            }
            var f = new FormData(document.getElementById("testform"));
            $.ajax({
                url: 'http://upload.qiniu.com/',  // Different bucket zone has different upload url, you can get right url by the browser error massage when uploading a file with wrong upload url.
                type: 'POST',
                data: f,
                processData: false,
                contentType: false,
                xhr: function(){
                    myXhr = $.ajaxSettings.xhr();
                    if(myXhr.upload){
                        myXhr.upload.addEventListener('progress',function(e) {
                            // console.log(e);
                            if (e.lengthComputable) {
                                var percent = e.loaded/e.total*100;
                                $progress.html('上传：' + e.loaded + "/" + e.total+" bytes. " + percent.toFixed(2) + "%");
                            }
                        }, false);
                    }
                    return myXhr;
                },
                success: function(res) {
                    console.log("成功：" + JSON.stringify(res));
                    var str = '<span>已上传：' + res.key + '</span>';
                    if (res.key && res.key.match(/\.(jpg|jpeg|png|gif)$/)) {
                        str += '<img src="' + domain + res.key + '"/>';
                    }
                    $uploadedResult.html(str);
                },
                error: function(res) {
                    console.log("失败:" +  JSON.stringify(res));
                    $uploadedResult.html('上传失败：' + res.responseText);
                }
            });
            return false;
        });
    });



    /* 图片自动上传 */
//    var uploadCount = 0, uploadList = [];
//    var uploadCountDom = document.getElementById("uploadCount");
//    weui.uploader('#uploader', {
//        url: 'http://' + location.hostname + ':8002/upload',
//        auto: true,
//        type: 'file',
//        fileVal: 'fileVal',
//        compress: {
//            width: 1600,
//            height: 1600,
//            quality: .8
//        },
//        onBeforeQueued: function(files) {
//            if(["image/jpg", "image/jpeg", "image/png", "image/gif"].indexOf(this.type) < 0){
//                weui.alert('请上传图片');
//                return false;
//            }
//            if(this.size > 10 * 1024 * 1024){
//                weui.alert('请上传不超过10M的图片');
//                return false;
//            }
//            if (files.length > 5) { // 防止一下子选中过多文件
//                weui.alert('最多只能上传5张图片，请重新选择');
//                return false;
//            }
//            if (uploadCount + 1 > 5) {
//                weui.alert('最多只能上传5张图片');
//                return false;
//            }
//
//            ++uploadCount;
//            uploadCountDom.innerHTML = uploadCount;
//        },
//        onQueued: function(){
//            uploadList.push(this);
//            console.log(this);
//        },
//        onBeforeSend: function(data, headers){
//            console.log(this, data, headers);
//            // $.extend(data, { test: 1 }); // 可以扩展此对象来控制上传参数
//            // $.extend(headers, { Origin: 'http://127.0.0.1' }); // 可以扩展此对象来控制上传头部
//
//            // return false; // 阻止文件上传
//        },
//        onProgress: function(procent){
//            console.log(this, procent);
//        },
//        onSuccess: function (ret) {
//            console.log(this, ret);
//        },
//        onError: function(err){
//            console.log(this, err);
//        }
//    });

    // 缩略图预览
//    document.querySelector('#uploaderFiles').addEventListener('click', function(e){
//        var target = e.target;
//
//        while(!target.classList.contains('weui-uploader__file') && target){
//            target = target.parentNode;
//        }
//        if(!target) return;
//
//        var url = target.getAttribute('style') || '';
//        var id = target.getAttribute('data-id');
//
//        if(url){
//            url = url.match(/url\((.*?)\)/)[1].replace(/"/g, '');
//        }
//        var gallery = weui.gallery(url, {
//            className: 'custom-name',
//            onDelete: function(){
//                weui.confirm('确定删除该图片？', function(){
//                    --uploadCount;
//                    uploadCountDom.innerHTML = uploadCount;
//
//
//                    for (var i = 0, len = uploadList.length; i < len; ++i) {
//                        var file = uploadList[i];
//                        if(file.id == id){
//                            file.stop();
//                            break;
//                        }
//                    }
//                    target.remove();
//                    gallery.hide();
//                });
//            }
//        });
//    });
</script>
</body>
</html>