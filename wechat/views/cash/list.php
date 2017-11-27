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
    <div class="weui-top-bar">
        <!--搜索栏开始-->
        <div class="weui-search-bar" id="searchBar">
            <form class="weui-search-bar__form" id="searchForm">
                <div class="weui-search-bar__box">
                    <i class="weui-icon-search"></i>
                    <input type="search" class="weui-search-bar__input" id="searchInput" placeholder="搜索" required="">
                    <a href="javascript:" class="weui-icon-clear" id="searchClear"></a>
                </div>
                <label class="weui-search-bar__label" id="searchText">
                    <i class="weui-icon-search"></i>
                    <span>搜索</span>
                </label>
            </form>
            <a href="javascript:" class="weui-search-bar__cancel-btn" id="searchCancel">取消</a>
        </div>
        <!--搜索栏结束-->
        <div class="weui-cell weui-select-bar">
            <div class="weui-cell__bd">
                <input class="weui-input" id="picker" type="text" value="全部" readonly="">
            </div>
        </div>
    </div>
    
    <div class="weui-order-preview">
        <div class="weui-pull-to-refresh__layer">
            <div class='weui-pull-to-refresh__arrow'></div>
            <div class='weui-pull-to-refresh__preloader'></div>
            <div class="down">下拉刷新</div>
            <div class="up">释放刷新</div>
            <div class="refresh">正在刷新</div>
        </div>
        <div class="content-padded"></div>
        <div class="weui-loadmore">
            <i class="weui-loading"></i>
            <span class="weui-loadmore__tips">正在加载</span>
        </div>
    </div>
    <script src="/wechat/lib/jquery-2.1.4.js"></script>
    <script src="/wechat/lib/fastclick.js"></script>
    <script src="/wechat/js/jquery-weui.js"></script>
    <script src="/wechat/js/jquery-weui-extend.js"></script>
    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <script>
        // 订单列表请求地址
        var queryOrderListUrl = "<?=Yii::$app->getUrlManager()->createUrl(['cash/order-list'])?>";
        // 开始调查地址
        var editOrderInfoUrl = "<?=Yii::$app->getUrlManager()->createUrl(['cash/edit-order'])?>";
        // 上传订单照片
        var uploadOrderImgUrl = "<?=Yii::$app->getUrlManager()->createUrl(['cash/upload-order-img'])?>";
        // 取消订单
        var cancelOrderUrl = "<?=Yii::$app->getUrlManager()->createUrl(['cash/cancel-order'])?>";

        // 待一审上传照片
        var ORDER_STATUS_FIRST_UPLOAD = 2;
        // 一审拒绝，重新上传
        var ORDER_STATUS_FIRST_REFUSE = 5;
        // 一审通过，待上传二审照片
        var ORDER_STATUS_SECOND_UPLOAD = 4;
        // 二审拒绝，重新上传
        var ORDER_STATUS_SECOND_REFUSE = 7;

        $(function(){
            FastClick.attach(document.body);

            wx.config(<?php echo $js->config(['hideMenuItems']) ?>);

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
            });

            // 页面处理类
            function Pages(options){
                // 订单状态
                this.SUBMIT = 1;
                this.REVIEW = 2;
                this.REFUSE = 3;
                this.PASSED = 4;
                this.PAYOVER = 5;
                this.CANCEL = 6;
                // 网络请求URL
                this.queryOrderListUrl = options.queryOrderListUrl;
                // 编辑订单
                this.editOrderInfoUrl = options.editOrderInfoUrl;
                // 上传照片
                this.uploadOrderImgUrl = options.uploadOrderImgUrl;
                // 取消订单
                this.cancelOrderUrl = options.cancelOrderUrl;
                // 是否加载完毕
                this.isLoaded = false;
                // 当前页面
                this.currPage = 1;
                // 是否正在加载中
                this.isLoading = false;
            }

            // 初始化
            Pages.prototype.init = function(){
                // 发起首次网络请求
                this.doRequest({
                    page : this.currPage,
                    keywords : $('#searchInput').val(),
                    status : $('#picker').attr('data-values')
                });

                // 绑定事件
                this.bind();
            }

            /**
             * 绑定事件
             * @return {[type]} [description]
             */
            Pages.prototype.bind = function(){
                var _this = this;
                // 搜索提交
                $('#searchForm').bind('submit' , function(){
                    _this.currPage = 1;
                    // 请求数据
                    _this.doRequest({
                        page : _this.currPage,
                        keywords : $('#searchInput').val(),
                        status : $('#picker').attr('data-values')
                    });
                    return false;
                });

                // 筛选
                $("#picker").select({
                    title: "筛选类别",
                    onChange: function(d) {
                        _this.currPage = 1;
                        // 请求数据
                        _this.doRequest({
                            page : _this.currPage,
                            keywords : $('#searchInput').val(),
                            status : d.values
                        });
                    },
                    items: [{title: "全部",value: 0},
                        {title: "已提交",value: _this.SUBMIT},
                        {title: "待审核",value: _this.REVIEW},
                        {title: "已拒绝",value: _this.REFUSE},
                        {title: "已通过",value: _this.PASSED},
                        {title: "已还清",value: _this.PAYOVER},
                        {title: "已取消",value: _this.CANCEL},
                        ]
                });

                $(document.body).infinite(50).on("infinite" , function(){
                    if(_this.isLoaded) return;
                    if(_this.isLoading) return;
                    _this.isLoading = true;
                    $(".weui-loadmore").show();
                    // 下一页
                    _this.currPage++;
                    // 数据请求
                    _this.ajaxRequest({
                        page : _this.currPage,
                        keywords : $('#searchInput').val(),
                        status : $('#picker').attr('data-values')
                    } , function(html , realPage){
                        $(".weui-loadmore").hide();
                        if((realPage + 1) == _this.currPage){
                            $(".content-padded").append(html);
                            // 绑定相关操作
                            _this.bindAction();
                        }else{
                            _this.isLoaded = true;
                            $(".content-padded").append(_this.getNoDataHtml('加载完毕'));
                        }
                        _this.isLoading = false;
                    });
                });

                // 绑定下拉刷新
                $(document.body).pullToRefresh();
                $(document.body).on("pull-to-refresh", function() {
                    _this.currPage = 1;
                    // 请求数据
                    _this.doRequest({
                        page : _this.currPage,
                        keywords : $('#searchInput').val(),
                        status : $('#picker').attr('data-values')
                    } , function(){
                        $(document.body).pullToRefreshDone();
                    });
                });
            }

            /**
             * 获取暂无数据的HTML
             * @param  {[type]} msg 提示消息
             * @return {[type]}     html
             */
            Pages.prototype.getNoDataHtml = function(msg){
                msg = msg ? msg : '暂无数据';
                return '<div class="weui-loadmore weui-loadmore_line"><span class="weui-loadmore__tips">'+msg+'</span></div>'
            }

            /**
             * 发起网络请求
             * @param  {[type]} request 请求参数
             * @return {[type]}         null
             */
            Pages.prototype.doRequest = function(request , callback){
                var _this = this;
                this.isLoaded = false;
                $.showLoading();
                this.ajaxRequest(request , function(html , page){
                    setTimeout(function() {
                      $.hideLoading();
                      $(".weui-loadmore").hide();
                      // 回调
                      callback && callback();
                      // 是否有数据
                      if(html){
                        $(".content-padded").html(html);
                        // 绑定相关操作
                        _this.bindAction();
                      }else{
                        this.isLoaded = true;
                        $(".content-padded").html(_this.getNoDataHtml());
                      }
                    }, 500);
                });
            }

            /**
             * ajax网络请求数据
             * @param  {[type]}   request  请求参数
             * @param  {Function} callback 回调
             * @return {[type]}            null
             */
            Pages.prototype.ajaxRequest = function(request , callback){
                var _this = this;
                $.post(_this.url , request , function(res){
                    if(res.status){
                        var html = '';

                        for(var i in res.data.data){
                            var item = res.data.data[i];
                            html += '<div class="weui-form-preview" data-o-id="' + item.orderID +'" data-o-status="' +item.orderStatus+ '"><div class="weui-form-preview__hd"><label class="weui-form-preview__label">' + item.customerName + '</label><em class="weui-form-preview__value">　</em></div><div class="weui-form-preview__bd"><div class="weui-form-preview__item"><label class="weui-form-preview__label">订单编号</label><span class="weui-form-preview__value">' + item.orderNumber + '</span></div><div class="weui-form-preview__item"><label class="weui-form-preview__label">申请金额</label><span class="weui-form-preview__value">' + parseFloat(item.applyAmount).toFixed(2) + '元</span></div><div class="weui-form-preview__item"><label class="weui-form-preview__label">放款金额</label><span class="weui-form-preview__value">' + (item.acceptAmount ? parseFloat(item.acceptAmount).toFixed(2) + '元' : '-') + '</span></div><div class="weui-form-preview__item"><label class="weui-form-preview__label">客户电话</label><span class="weui-form-preview__value">' + item.orderPhone + '</span></div><div class="weui-form-preview__item"><label class="weui-form-preview__label">客户地址</label><span class="weui-form-preview__value">' + item.orderAddress + '</span></div><div class="weui-form-preview__item"><label class="weui-form-preview__label">申请时间</label><span class="weui-form-preview__value">' + (item.applyTime.date).split('.')[0] + '</span></div>';

                            switch(parseInt(item.status)){
                                case _this.SUBMIT:
                                    status  = '已提交';
                                break;

                                case _this.REVIEW:
                                    status  = '待审核';
                                break;

                                case _this.REFUSE:
                                    status  = '已拒绝';
                                break;

                                case _this.PASSED:
                                    status  = '已通过';
                                break;

                                case _this.PAYOVER:
                                    status  = '已还清';
                                break;

                                case _this.CANCEL:
                                    status  = '已取消';
                                break;
                            }
                            html += '</div><div class="weui-form-preview__ft"><a class="weui-form-preview__btn weui-form-preview__btn_default" href="javascript:">'+ status + '</a><button type="submit" class="weui-form-preview__btn weui-form-preview__btn_primary" href="javascript:">操作</button></div></div><br />';
                        }

                        callback(html , res.data.page);
                    }else{
                        callback(false);
                    }
                });
            }


            // 操作动作
            Pages.prototype.bindAction = function(){
                var _this = this;
                $('.weui-form-preview__btn_primary').bind('click' , function(){
                    // 父级preview
                    var preview = $(this).parents('.weui-form-preview');
                    // 获取订单ID和订单状态
                    var orderId = parseInt(preview.attr('data-o-id'));
                    var status = parseInt(preview.attr('data-o-status'));

                    // 默认操作
                    var defaultActions = {
                        edit : {
                            text: "开始调查",
                            className: "color-primary",
                            onClick: function() {
                                window.location = _this.editOrderInfoUrl + '?orderId=' + orderId;
                            }
                        },
                        cancel : {
                            text: "取消订单",
                            className: "color-danger",
                            onClick: function() {
                                $.prompt({
                                    title: '确认取消该订单？',
                                    text: '请填写取消原因后取消订单。',
                                    empty: false,
                                    onOK: function (input) {
                                        if(!input){
                                            $.toast('请填写取消原因', "text");return;
                                        }
                                        // 网络请求
                                        $.ajaxPost(_this.cancelOrderUrl , {orderId : orderId , reason : input} , function(res){
                                            if(res.status){
                                                $.toast(res.message, function(){
                                                    _this.doRequest({
                                                        page : _this.currPage,
                                                        keywords : $('#searchInput').val(),
                                                        status : $('#picker').attr('data-values')
                                                    });
                                                });
                                            }else{
                                                $.toast(res.message, "text");
                                            }
                                        });
                                    }
                                });
                            }
                        },
                        upload : {
                            text: "上传照片",
                            className: 'color-warning',
                            onClick: function() {
                                window.location = _this.uploadOrderImgUrl + '?orderId=' + orderId;
                            }
                        }
                    }

                    // 绑定操作
                    var actions = new Array;

                    // 上传照片
                    if(status == ORDER_STATUS_FIRST_UPLOAD || status == ORDER_STATUS_FIRST_REFUSE || status == ORDER_STATUS_SECOND_UPLOAD || status == ORDER_STATUS_SECOND_REFUSE){
                        actions.push(defaultActions.upload);
                    }

                    // 取消订单
                    if(status == ORDER_STATUS_SECOND_UPLOAD || status == ORDER_STATUS_SECOND_REFUSE){
                        actions.push(defaultActions.cancel);
                    }

                    // 开始调查
                    if(status == ORDER_STATUS_FIRST_UPLOAD){
                        actions.push(defaultActions.edit);
                    }

                    $.actions({
                        title: "操作",
                        onClose: function() {},
                        actions: actions
                    });
                });
            }

            // 初始化
            var options = {
                queryOrderListUrl:queryOrderListUrl,
                editOrderInfoUrl:editOrderInfoUrl,
                uploadOrderImgUrl:uploadOrderImgUrl,
                cancelOrderUrl:cancelOrderUrl
            };
            var page = new Pages(options);
            page.init();
        })
    </script>
</body>
</html>