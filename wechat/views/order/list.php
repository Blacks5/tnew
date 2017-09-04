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
        <div class="content-padded"></div>
        <div class="weui-loadmore">
            <i class="weui-loading"></i>
            <span class="weui-loadmore__tips">正在加载</span>
        </div>
    </div>
    <script src="/wechat/lib/jquery-2.1.4.js"></script>
    <script src="/wechat/lib/fastclick.js"></script>
    <script src="/wechat/js/jquery-weui.js"></script>
    <script>
        $(function(){
            FastClick.attach(document.body);

            // 页面处理类
            function Pages(url){
                // 订单状态
                this.REFUSE = <?=\common\models\Orders::STATUS_REFUSE?>;
                this.REVOKE = <?=\common\models\Orders::STATUS_REVOKE?>;
                this.CANCEL = <?=\common\models\Orders::STATUS_CANCEL?>;
                this.PAYING = <?=\common\models\Orders::STATUS_PAYING?>;
                this.PAY_OVER = <?=\common\models\Orders::STATUS_PAY_OVER?>;
                // 网络请求URL
                this.url = url
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
                    screen_type : $('#picker').attr('data-values')
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
                        screen_type : $('#picker').attr('data-values')
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
                            screen_type : d.values
                        });
                    },
                    items: [{title: "全部",value: "all"},
                        {title: "近一月",value: "near"},
                        {title: "还款中",value: "paying"},
                        {title: "已逾期",value: "003"},
                        {title: "已取消",value: "cancel"},
                        {title: "已拒绝",value: "refuse"},
                        {title: "已还清",value: "payover"}]
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
                        screen_type : $('#picker').attr('data-values')
                    } , function(html , realPage){
                        console.log(realPage);
                        console.log(_this.currPage);
                        $(".weui-loadmore").hide();
                        if((realPage + 1) == _this.currPage){
                            $(".content-padded").append(html);
                        }else{
                            _this.isLoaded = true;
                            $(".content-padded").append(_this.getNoDataHtml('加载完毕'));
                        }
                        _this.isLoading = false;
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
            Pages.prototype.doRequest = function(request){
                var _this = this;
                this.isLoaded = false;
                $.showLoading();
                this.ajaxRequest(request , function(html , page){
                    setTimeout(function() {
                      $.hideLoading();
                      $(".weui-loadmore").hide();

                      // 是否有数据
                      if(html){
                        $(".content-padded").html(html);
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
                $.get(_this.url , request , function(res){
                    if(res.status){
                        var html = '';

                        for(var i in res.data.data){
                            var item = res.data.data[i];
                            html += '<div class="weui-form-preview"><div class="weui-form-preview__hd"><label class="weui-form-preview__label">' + item.c_customer_name + '</label><em class="weui-form-preview__value">' + item.o_total_price + '元/' + item.p_period + '期</em></div><div class="weui-form-preview__bd"><div class="weui-form-preview__item"><label class="weui-form-preview__label">订单编号</label><span class="weui-form-preview__value">' + item.o_serial_id + '</span></div><div class="weui-form-preview__item"><label class="weui-form-preview__label">总金额</label><span class="weui-form-preview__value">' + item.o_total_price + '元</span></div><div class="weui-form-preview__item"><label class="weui-form-preview__label">客户电话</label><span class="weui-form-preview__value">' + item.c_customer_cellphone + '</span></div><div class="weui-form-preview__item"><label class="weui-form-preview__label">商品类型</label><span class="weui-form-preview__value">' + item.p_name + '</span></div><div class="weui-form-preview__item"><label class="weui-form-preview__label">提交时间</label><span class="weui-form-preview__value">' + item.o_created_at + '</span></div></div><div class="weui-form-preview__ft"><a class="weui-form-preview__btn weui-form-preview__btn_default" href="javascript:">';

                            switch(parseInt(item.o_status)){
                                case _this.REFUSE:
                                    html  += '已拒绝';
                                break;

                                case _this.REVOKE:
                                    html  += '已撤销';
                                break;

                                case _this.CANCEL:
                                    html  += '已取消';
                                break;

                                case _this.PAYING:
                                    html  += '还款中';
                                break;

                                case _this.PAY_OVER:
                                    html  += '已还清';
                                break;
                            }
                            html += '</a><button type="submit" class="weui-form-preview__btn weui-form-preview__btn_primary" href="javascript:">操作</button></div></div><br />';
                        }

                        callback(html , res.data.page);
                    }else{
                        callback(false);
                    }
                });
            }

            // 请求地址
            var url = "<?=Yii::$app->getUrlManager()->createUrl(['order/order-list'])?>";

            // 初始化
            (new Pages(url)).init();
        })
    </script>
</body>
</html>