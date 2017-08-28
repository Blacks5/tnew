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
            <form class="weui-search-bar__form">
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
        <div class="content-padded">
            <?php foreach ($list as $item) { ?>
                <div class="weui-form-preview">
                    <div class="weui-form-preview__hd">
                        <label class="weui-form-preview__label"><?=$item['c_customer_name']?></label>
                        <em class="weui-form-preview__value"><?=round($item['o_total_price']-$item['o_total_deposit'] , 2);?>元/<?= $item['p_period'];?>期</em>
                    </div>
                    <div class="weui-form-preview__bd">
                        <div class="weui-form-preview__item">
                            <label class="weui-form-preview__label">订单编号</label>
                            <span class="weui-form-preview__value"><?=$item['o_serial_id']?></span>
                        </div>
                        <div class="weui-form-preview__item">
                            <label class="weui-form-preview__label">总金额</label>
                            <span class="weui-form-preview__value"><?=round($item['o_total_price']+0 , 2);?>元</span>
                        </div>
                        <div class="weui-form-preview__item">
                            <label class="weui-form-preview__label">客户电话</label>
                            <span class="weui-form-preview__value"><?=$item['c_customer_cellphone']?></span>
                        </div>
                        <div class="weui-form-preview__item">
                            <label class="weui-form-preview__label">商品类型</label>
                            <span class="weui-form-preview__value"><?=$item['p_name']?></span>
                        </div>
                        <div class="weui-form-preview__item">
                            <label class="weui-form-preview__label">提交时间</label>
                            <span class="weui-form-preview__value"><?=date('Y-m-d H:i:s' , $item['o_created_at'])?></span>
                        </div>
                    </div>
                    <div class="weui-form-preview__ft">
                        <a class="weui-form-preview__btn weui-form-preview__btn_default" href="javascript:">
                        <?php switch ($item['o_status']) {
                            case \common\models\Orders::STATUS_WAIT_CHECK:
                            case \common\models\Orders::STATUS_WAIT_CHECK_AGAIN:
                            case \common\models\Orders::STATUS_WAIT_APP_UPLOAD_AGAIN:
                                echo '待审核';
                                break;

                            case \common\models\Orders::STATUS_REFUSE:
                                echo '已拒绝';
                                break;

                            case \common\models\Orders::STATUS_REVOKE:
                                echo '已撤销';
                                break;

                            case \common\models\Orders::STATUS_CANCEL:
                                echo '已取消';
                                break;

                            case \common\models\Orders::STATUS_PAYING:
                                echo '还款中';
                                break;

                            case \common\models\Orders::STATUS_PAY_OVER:
                                echo '已还清';
                                break;
                        } ?>
                        </a>
                        <button type="submit" class="weui-form-preview__btn weui-form-preview__btn_primary" href="javascript:">操作</button>
                    </div>
                </div>
                <br />
            <?php } ?>
        </div>
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

            // 订单状态
            const WAIT_CHECK = <?=\common\models\Orders::STATUS_WAIT_CHECK?>;
            const WAIT_CHECK_AGAIN = <?=\common\models\Orders::STATUS_WAIT_CHECK_AGAIN?>;
            const WAIT_APP_UPLOAD_AGAIN = <?=\common\models\Orders::STATUS_WAIT_APP_UPLOAD_AGAIN?>;
            const REFUSE = <?=\common\models\Orders::STATUS_REFUSE?>;
            const REVOKE = <?=\common\models\Orders::STATUS_REVOKE?>;
            const CANCEL = <?=\common\models\Orders::STATUS_CANCEL?>;
            const PAYING = <?=\common\models\Orders::STATUS_PAYING?>;
            const PAY_OVER = <?=\common\models\Orders::STATUS_PAY_OVER?>;


            // 加载更多
            var isOver = false;
            var page = 1;
            var loading = false;
            $(document.body).infinite(100).on("infinite" , function(){
                if(!isOver){
                    if(loading) return;
                    loading = true;

                    page = page + 1;

                    // 数据请求
                    ajaxRequest({
                        page : page,
                        keywords : $('#searchInput').val(),
                        screen_type : $('#picker').attr('data-values')
                    } , function(html){
                        $(".content-padded").append(html);
                        loading = false;
                    });
                }
            });

            // 筛选
            $("#picker").select({
                title: "筛选类别",
                onChange: function(d) {
                    page = 1;
                    ajaxRequest({
                        page : page,
                        keywords : $('#searchInput').val(),
                        screen_type : d.values
                    } , function(html){
                        console.log(html);
                        $(".content-padded").html(html);
                    });
                },
                items: [
                    {title: "全部",value: "all"},
                    {title: "近一月",value: "near"},
                    {title: "待审核",value: "wait"},
                    {title: "还款中",value: "paying"},
                    {title: "已逾期",value: "003"},
                    {title: "已取消",value: "cancel"},
                    {title: "已拒绝",value: "refuse"},
                    {title: "已还清",value: "payover"}
                ]
            });

            /**
             * Ajax数据请求
             * @param  {[type]}   request  请求参数
             * @param  {Function} callback 回调函数
             * @return {[type]}            null
             */
            function ajaxRequest(request , callback){
                // 发起请求
                $.get("<?=Yii::$app->getUrlManager()->createUrl(['order/order-list'])?>" , request , function(res){
                    if(res.status){
                        var html = '';

                        for(var i in res.data){
                            var item = res.data[i];
                            html += '<div class="weui-form-preview"><div class="weui-form-preview__hd"><label class="weui-form-preview__label">' + item.c_customer_name + '</label><em class="weui-form-preview__value">' + item.o_total_price + '元/' + item.p_period + '期</em></div><div class="weui-form-preview__bd"><div class="weui-form-preview__item"><label class="weui-form-preview__label">订单编号</label><span class="weui-form-preview__value">' + item.o_serial_id + '</span></div><div class="weui-form-preview__item"><label class="weui-form-preview__label">总金额</label><span class="weui-form-preview__value">' + item.o_total_price + '元</span></div><div class="weui-form-preview__item"><label class="weui-form-preview__label">客户电话</label><span class="weui-form-preview__value">' + item.c_customer_cellphone + '</span></div><div class="weui-form-preview__item"><label class="weui-form-preview__label">商品类型</label><span class="weui-form-preview__value">' + item.p_name + '</span></div><div class="weui-form-preview__item"><label class="weui-form-preview__label">提交时间</label><span class="weui-form-preview__value">' + item.o_created_at + '</span></div></div><div class="weui-form-preview__ft"><a class="weui-form-preview__btn weui-form-preview__btn_default" href="javascript:">';

                            switch(parseInt(item.o_status)){
                                case WAIT_CHECK:
                                case WAIT_CHECK_AGAIN:
                                case WAIT_APP_UPLOAD_AGAIN:
                                    html  += '待审核';
                                break;

                                case REFUSE:
                                    html  += '已拒绝';
                                break;

                                case REVOKE:
                                    html  += '已撤销';
                                break;

                                case CANCEL:
                                    html  += '已取消';
                                break;

                                case PAYING:
                                    html  += '还款中';
                                break;

                                case PAY_OVER:
                                    html  += '已还清';
                                break;
                            }
                            html += '</a><button type="submit" class="weui-form-preview__btn weui-form-preview__btn_primary" href="javascript:">操作</button></div></div><br />';
                        }

                        callback(html);
                    }else{
                        callback(false);
                    }
                });
            }
        })
    </script>
</body>
</html>