<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use mdm\admin\components\MenuHelper;

?>
<div id="wrapper">
    <!--左侧导航开始-->
    <nav class="navbar-default navbar-static-side" role="navigation">
        <div class="nav-close"><i class="fa fa-times-circle"></i>
        </div>
        <div class="sidebar-collapse">
            <ul class="nav" id="side-menu">
                <li class="nav-header">
                    <div class="dropdown profile-element">
                        <span><img alt="image" width="64ps" height="64px" class="img-circle"
                                   src="<?php echo Url::to('@web/img/tianniu.jpg'); ?>"/></span>
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                <span class="clear">
                               <span class="block m-t-xs"><strong
                                           class="font-bold"><?= Yii::$app->user->identity->username ?></strong></span>
                                <span class="text-muted text-xs block"><?= $user_info ?><b class="caret"></b></span>
                                </span>
                        </a>
                        <ul class="dropdown-menu animated fadeInRight m-t-xs">
                            <li><a class="J_menuItem" href="<?= Url::toRoute(['user/mod-self-pwd']) ?>">修改密码</a>
                            </li>
                            <li><a class="J_menuItem"
                                   href="<?= Url::toRoute(['user/update', 'id' => Yii::$app->user->identity->getId()]) ?>">个人资料</a>
                            </li>
                            <li class="divider"></li>
                            <li><a href="<?= Url::toRoute('login/logout') ?>">安全退出</a>
                            </li>
                        </ul>
                    </div>
                    <div class="logo-element">W+
                    </div>
                </li>
                <?php foreach ($menu as $v1) { ?>
                    <?php $data = json_decode($v1['data'], true); ?>
                    <li><!--一级级菜单-->
                        <a href="#">
                            <i class="<?= $data['icon'] ?>"></i>
                            <span class="nav-label"><?= $v1['name'] ?></span>
                            <span class="fa arrow"></span>
                        </a>
                        <?php if (array_key_exists('_child', $v1)) { ?>
                            <ul class="nav nav-second-level">
                                <?php foreach ($v1['_child'] as $v2) { ?>
                                    <?php $data2 = json_decode($v2['data'], true); ?>
                                    <?php if (array_key_exists('_child', $v2)) { ?>
                                        <li><!--二级菜单-->
                                            <a href="#">
                                                <?php if ($data2['icon']): ?><i
                                                    class="<?= $data2['icon'] ?>"></i><?php endif; ?><?= $v2['name'] ?>
                                                <span class="fa arrow"></span>
                                            </a>
                                            <?php if (!empty($v2['_child'])) { ?>
                                                <ul class="nav nav-third-level collapse">
                                                    <?php foreach ($v2['_child'] as $v3) { ?>
                                                        <li><!--三级菜单-->
                                                            <a class="J_menuItem"
                                                               href="<?= Url::toRoute($v3['route']); ?>"
                                                               data-index="0"><?= $v3['name'] ?></a>
                                                        </li>
                                                    <?php } ?>
                                                </ul>
                                            <?php } ?>
                                        </li>
                                    <?php } else { ?>
                                        <li><!--二级菜单-->
                                            <a class="J_menuItem" href="<?= Url::toRoute($v2['route']); ?>"
                                               data-index="0"><?= $v2['name'] ?></a>
                                        </li>
                                    <?php } ?>
                                <?php } ?>
                            </ul>
                        <?php }; ?>

                    </li>
                <?php }; ?>

            </ul>
        </div>
    </nav>
    <!--左侧导航结束-->
    <!--右侧部分开始-->
    <div id="page-wrapper" class="gray-bg dashbard-1">
        <div class="row border-bottom">
            <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
                <div class="navbar-header"><a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i
                                class="fa fa-bars"></i> </a>
                    <form role="search" class="navbar-form-custom" method="post" action="#">
                        <div class="form-group">
                            <!--                            <input type="text" placeholder="请输入您需要查找的内容 …" class="form-control" name="top-search" id="top-search">-->
                            <h2>管理后台</h2>
                        </div>
                    </form>
                </div>
                <ul class="nav navbar-top-links navbar-right">
                    <li class="dropdown">
                        <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#" id="notifyx"  aria-expanded="false">
                            <i class="fa fa-bell"></i> <span class="label label-primary" id="noticeNum"></span>
                        </a>
                        <ul class="dropdown-menu dropdown-alerts">
                            <li id="newOrderli" style="display: none;">
                                <a class="J_menuItem" onclick="notify.hideNotify('newOrderNotify')" href="<?= Url::toRoute(['/borrow/list-wait-verify']) ?>" data-index="0" data-tagtitle="待审核">
                                    <div>
                                        <i class="fa fa-envelope fa-fw"></i>
                                        <span id="newOrderNotify">您有0条未读订单消息</span>
                                    </div>
                                </a>
                            </li>
                            <li class="divider" id="dividerNotice" style="display: none"></li>
                            <li id="newSignli" style="display: none;">
                                <a class="J_menuItem" onclick="notify.hideNotify('signNotify')" href="<?= Url::toRoute(['/borrow/list-wait-verify']) ?>" data-index="0" data-tagtitle="待审核">
                                    <div>
                                        <i class="fa fa-envelope fa-fw"></i>
                                        <span id="signNotify">您有0条未读签约消息</span>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown hidden-xs">
                        <a class="right-sidebar-toggle" aria-expanded="false">
                            <i class="fa fa-tasks"></i> 主题
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
        <div class="row content-tabs">
            <button class="roll-nav roll-left J_tabLeft"><i class="fa fa-backward"></i>
            </button>
            <nav class="page-tabs J_menuTabs">
                <div class="page-tabs-content">
                    <a href="javascript:;" class="active J_menuTab" data-id="0">首页</a>
                </div>
            </nav>
            <button class="roll-nav roll-right J_tabRight"><i class="fa fa-forward"></i>
            </button>
            <div class="btn-group roll-nav roll-right">
                <button class="dropdown J_tabClose" data-toggle="dropdown">关闭操作<span class="caret"></span>

                </button>
                <ul role="menu" class="dropdown-menu dropdown-menu-right">
                    <li class="J_tabShowActive"><a>定位当前选项卡</a>
                    </li>
                    <li class="divider"></li>
                    <li class="J_tabCloseAll"><a>关闭全部选项卡</a>
                    </li>
                    <li class="J_tabCloseOther"><a>关闭其他选项卡</a>
                    </li>
                </ul>
            </div>
            <a href="<?= Url::toRoute('login/logout') ?>" class="roll-nav roll-right J_tabExit"><i
                        class="fa fa fa-sign-out"></i> 退出</a>
        </div>
        <div class="row J_mainContent" id="content-main">
            <iframe class="J_iframe" name="iframe0" width="100%" height="100%"
                    src="<?= Url::toRoute('index/welcome') ?>" frameborder="0" data-id="0" seamless></iframe>
        </div>
        <div class="footer">
            <div class="pull-right">&copy; 2012-2016 <a href="#" target="_blank">W+</a>
            </div>
        </div>
    </div>
    <!--右侧部分结束-->
    <!--右侧边栏开始-->
    <div id="right-sidebar">
        <div class="sidebar-container">

            <ul class="nav nav-tabs navs-3">

                <li class="active">
                    <a data-toggle="tab" href="#tab-1">
                        <i class="fa fa-gear"></i> 主题
                    </a>
                </li>
            </ul>

            <div class="tab-content">
                <div id="tab-1" class="tab-pane active">
                    <div class="sidebar-title">
                        <h3><i class="fa fa-comments-o"></i> 主题设置</h3>
                        <small><i class="fa fa-tim"></i> 你可以从这里选择和预览主题的布局和样式，这些设置会被保存在本地，下次打开的时候会直接应用这些设置。</small>
                    </div>
                    <div class="skin-setttings">
                        <div class="title">主题设置</div>
                        <div class="setings-item">
                            <span>收起左侧菜单</span>
                            <div class="switch">
                                <div class="onoffswitch">
                                    <input type="checkbox" name="collapsemenu" class="onoffswitch-checkbox"
                                           id="collapsemenu">
                                    <label class="onoffswitch-label" for="collapsemenu">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="setings-item">
                            <span>固定顶部</span>

                            <div class="switch">
                                <div class="onoffswitch">
                                    <input type="checkbox" name="fixednavbar" class="onoffswitch-checkbox"
                                           id="fixednavbar">
                                    <label class="onoffswitch-label" for="fixednavbar">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="setings-item">
                                <span>
                        固定宽度
                    </span>

                            <div class="switch">
                                <div class="onoffswitch">
                                    <input type="checkbox" name="boxedlayout" class="onoffswitch-checkbox"
                                           id="boxedlayout">
                                    <label class="onoffswitch-label" for="boxedlayout">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="title">皮肤选择</div>
                        <div class="setings-item default-skin nb">
                                <span class="skin-name ">
                         <a href="#" class="s-skin-0">
                             默认皮肤
                         </a>
                    </span>
                        </div>
                        <div class="setings-item blue-skin nb">
                                <span class="skin-name ">
                        <a href="#" class="s-skin-1">
                            蓝色主题
                        </a>
                    </span>
                        </div>
                        <div class="setings-item yellow-skin nb">
                                <span class="skin-name ">
                        <a href="#" class="s-skin-3">
                            黄色/紫色主题
                        </a>
                    </span>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
    <!--右侧边栏结束-->

</div>

<?= Html::jsFile('@web/js/jquery.min.js') ?>
<?= Html::jsFile('@web/js/bootstrap.min.js') ?>
<?= Html::jsFile('@web/js/plugins/metisMenu/jquery.metisMenu.js') ?>
<?= Html::jsFile('@web/js/plugins/slimscroll/jquery.slimscroll.min.js') ?>
<?= Html::jsFile('@web/js/plugins/layer/layer.min.js') ?>
<?= Html::jsFile('@web/js/hplus.min.js') ?>
<?= Html::jsFile('@web/js/contabs.min.js') ?>
<?= Html::jsFile('@web/js/plugins/pace/pace.min.js') ?>

<script>


    $(function () {
        //获取通知权限
        Notification.requestPermission(function(status) {
        // var permission = Notification.permission;
        //console.log('permission: ' + permission);
        });
        notify.init();
        // notify.heartbeat();
    });


    var config = {
        "server": "ws://119.23.15.90:8081"
//        "server": "ws://119.23.15.90:8888"
    };

    var dataSend = {
        "controller_name":"AppController",
        "method_name":"keepAlive"
    };

    var notify = {
        data: {
            server: null,
            defaultData: []
        },
        hideNotify: function (typename) {
            $("#notifyx").trigger('click');
            //localStorage.removeItem('newOrderNotify');
            localStorage.removeItem(typename);
            $("#noticeNum").text('');
        },
        init: function () {
            this.data.server = new WebSocket(config.server);
            this.open();
            this.message();
        },
        open: function () {
            this.data.server.onopen = function (event) {
                console.log("连接上了");
//                console.log(event);
            }
        },
        message: function () {
            var self = this;
            this.data.server.onmessage = function (event) {
//                console.log("收到消息");
                //console.log(event.data);
//                console.log(JSON.parse(event.data).type);
                if(JSON.parse(event.data).type == 'loanNotify') {
                    var n = new Notification("放款通知:", {
                        icon: '<?php echo Url::to('@web/img/notice_icon.png'); ?>',
                        body: JSON.parse(event.data).message
                    });
                    n.onshow = function () {
                        //console.log('显示通知信息');
                    };
                    n.onclick = function () {
                        //alert('打开相关视图');
                        n.close();
                    };
                }else{
                    self.saveData(event);
                }
            }
        },
        close: function () {
            this.data.server.onclose = function (event) {
//                console.log("服雾器消失了");
                console.log(event);
            }
        },
        error: function () {
            this.data.server.onerror = function (event) {
//                console.log("莫名其妙的出错了");
                console.log(event);
            }
        },
        heartbeat: function () {
            var that = this;
            setInterval(function(){
                that.data.server.send(JSON.stringify(dataSend));
            },100000);
        },
        saveData:function (event) {
            var dataRes = JSON.parse(event.data);
            //var dataRes = {"message":"李大爷创建了新订单","order_id":5,"type":"newOrderNotify"};
            //{"message":"订单:170300000012475签约签约成功","order_id":53,"type":"signNotify"}
            var key = dataRes.type;
            var dataStorage = JSON.parse(localStorage.getItem(key));
            if(dataStorage){
                dataStorage.push(dataRes);
                localStorage.setItem(key,JSON.stringify(dataStorage));
            }else{
                var dataRes_arr = [dataRes];
                localStorage.setItem(key,JSON.stringify(dataRes_arr));
            }

            //判断消息类型,根据消息类型创建dom
            var newdataStorage = JSON.parse(localStorage.getItem(key));
            if(key == 'newOrderNotify'){
                textDetail = '';
                var numOrder =  newdataStorage ? newdataStorage.length : 0;
                if(numOrder){
                    textDetail = "您有"+ numOrder +"条未读订单消息";
                    $('#newOrderNotify').text(textDetail);
                    $('#newOrderli').show();
                    $('#dividerNotice').show();
                }else{
                    $('#newOrderli').hide();
                }
            }else if(key == 'signNotify'){
                textDetail = '';
                var numSign =  newdataStorage ? newdataStorage.length : 0;
                if(numSign){
                    textDetail = "您有"+ numSign +"条未读签约消息";
                    $('#signNotify').text(textDetail);
                    $('#newSignli').show();
                }else{
                    $('#newSignli').hide();
                }
            }
            $('#noticeNum').text(parseInt(numOrder ? numOrder : 0) + parseInt(numSign ? numSign : 0));
        },
        remove:function () {
            //当点击某条未读消息时,清除对应的本地数据
            obj = $('li div span');
            var idName = obj.attr('id');
            if((idName == 'newOrderNotify')||(idName == 'signNotify')){
                obj.on('click',function () {
                    if(localStorage.getItem(idName)){
                        localStorage.removeItem(idName);
                    }
                });
            }
        }
    };
    var t = {
        "cmd":"Test:keelAlive",
        "data":""
    };
    var x1 = JSON.stringify(t);
    setInterval(function(){
        notify.data.server.send(x1);
    },100000);
    //aaa
</script>