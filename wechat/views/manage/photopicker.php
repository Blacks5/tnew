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
                        <div class=weui-uploader__bd>
                            <ul class=weui-uploader__files id=uploaderFiles>

                            </ul>
                            <div class=weui-uploader__input-box>
                                <input id=uploaderInput class=weui-uploader__input type=file accept=image/* capture=camera multiple=""/>
                            </div>
                        </div>
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
    var uploader = Qiniu.uploader({
        runtimes: 'html5,flash,html4', //上传模式,依次退化
        browse_button: 'uploaderInput', //上传选择的点选按钮，**必需**
        uptoken:getTokenMessage().token,
    //  uptoken_url: getToken(), //Ajax请求upToken的Url，**强烈建议设置**（服务端提供）
    //  uptoken : '', //若未指定uptoken_url,则必须指定 uptoken ,uptoken由其他程序生成
    //  unique_names: true, // 默认 false，key为文件名。若开启该选项，SDK为自动生成上传成功后的key（文件名）。
    //  save_key: true, // 默认 false。若在服务端生成uptoken的上传策略中指定了 `sava_key`，则开启，SDK会忽略对key的处理
        domain: 'http://qiniu-plupload.qiniudn.com/', //bucket 域名，下载资源时用到，**必需**
        get_new_uptoken: false, //设置上传文件的时候是否每次都重新获取新的token
        container: '', //上传区域DOM ID，默认是browser_button的父元素，
        max_file_size: '100mb', //最大文件体积限制
        flash_swf_url: 'Moxie.swf', //引入flash,相对路径
        max_retries: 3, //上传失败最大重试次数
        dragdrop: true, //开启可拖曳上传
        drop_element: 'container', //拖曳上传区域元素的ID，拖曳文件或文件夹后可触发上传
        chunk_size: '4mb', //分块上传时，每片的体积
        auto_start: false, //选择文件后自动上传，若关闭需要自己绑定事件触发上传
        init: {
            'FilesAdded': function(up, files) {
                plupload.each(files, function(file) {
                    // 文件添加进队列后,处理相关的事情
                    console.log(file.name);
                });
            },
            'BeforeUpload': function(up, file) {
                // 每个文件上传前,处理相关的事情
            },
            'UploadProgress': function(up, file) {
                // 每个文件上传时,处理相关的事情
            },
            'FileUploaded': function(up, file, info) {
                // 每个文件上传成功后,处理相关的事情
                // 其中 info 是文件上传成功后，服务端返回的json，形式如
                // {
                // "hash": "Fh8xVqod2MQ1mocfI4S4KpRL6D98",
                // "key": "gogopher.jpg"
                // }
                // 参考http://developer.qiniu.com/docs/v6/api/overview/up/response/simple-response.html

                // var domain = up.getOption('domain');
                // var res = parseJSON(info);
                // var sourceLink = domain + res.key; 获取上传成功后的文件的Url
            },
            'Error': function(up, err, errTip) {
            //上传出错时,处理相关的事情
            },
            'UploadComplete': function() {
                //队列文件处理完毕后,处理相关的事情
            },
            'Key': function(up, file) {
                // 若想在前端对每个文件的key进行个性化处理，可以配置该函数
                // 该配置必须要在 unique_names: false , save_key: false 时才生效
                var key = "12.png";
                // do something with key here
                return key
            }
        }
    });

    // domain 为七牛空间（bucket)对应的域名，选择某个空间后，可通过"空间设置->基本设置->域名设置"查看获取
    // uploader 为一个plupload对象，继承了所有plupload的方法，参考http://plupload.com/docs
    function getTokenMessage() {
        var token = {token:'QWYn5TFQsLLU1pL5MFEmX3s5DmHdUThav9WyOWOm:-C98mj-EN_Vz2rH80uB9xP2ERkw=:eyJkZWxldGVBZnRlckRheXMiOjcsInNjb3BlIjoianNzZGsiLCJkZWFkbGluZSI6MTUwMzQxNzQzMn0='};
//        $.ajax({
//            url:'http://jssdk.demo.qiniu.io/token',
//            async:false,
//            success:function (data) {
//                var obj = JSON.parse(data);
//                token.token = obj.uploadToken;
//                token.filename = obj.filename;
//            }
//        });
        return token;
    }
    document.getElementById('uploadfiles').onclick = function() {
        uploader.start();
    };






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