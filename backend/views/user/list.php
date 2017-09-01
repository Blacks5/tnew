<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\widgets\LinkPager;

?>
    <div class="wrapper wrapper-content">
        <div class="row">
            <div class="col-sm-12">
                <div class="ibox float-e-margins">

                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-sm-3">
                                <?php if (Yii::$app->getUser()->can(yii\helpers\Url::toRoute(['user/create']))) { ?>
                                    <a class="btn btn-info btn-sm" href="<?= Url::toRoute('user/create') ?>">新增用户</a>
                                <?php } ?>
                            </div>
                        </div>
                        <hr>

                        <form class="row" method="get" action="">
                            <div class="col-sm-1">
                                <input type="text" name="UserSearch[username]" placeholder="用户名"
                                       value="<?php echo $sear['username']; ?>" class="input form-control">
                            </div>
                            <div class=" col-sm-1">
                                <input type="text" name="UserSearch[realname]" value="<?php echo $sear['realname']; ?>"
                                       placeholder="真实姓名" class="input form-control">
                            </div>
                            <div class="col-sm-2">
                                <input type="text" name="UserSearch[email]" value="<?php echo $sear['email']; ?>"
                                       placeholder="邮箱" class="input form-control">
                            </div>


                            <div class="col-sm-1" style="width: 9.5%">
                                <select class="input form-control" name="UserSearch[province]" id="user-province">
                                    <option value="">选择省</option>
                                    <?php foreach ($provinces as $k=>$v){ ?>
                                        <option <?php if($sear['province'] == $k){ ?> selected <?php } ?>value="<?=$k?>"><?=$v?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-1" style="width: 9.5%">
                                <select class="input form-control" name="UserSearch[city]" id="user-city">
                                </select>
                            </div>
                            <div class="col-sm-1" style="width: 9.5%">
                                <select class="input form-control" name="UserSearch[county]" id="user-county">
                                </select>
                            </div>


                            <script>

                                var url = "<?=Url::toRoute(['user/get-sub-addr'])?>"; // 获取子地区

                                // 省变化
                                $("#user-province").change(function(){
                                    var province_id = $(this).val();
                                    $.get(url, {p_id:province_id}, function(data){
                                        var dom = "<option value=''>选择市</option>";
                                        var t = "<?=$sear['city']?>";
                                        $.each(data, function (k, v) {
                                            dom += "<option "+((t==k)?'selected':'')+" value="+k+">"+v+"</option>";
                                        })
                                        $("#user-city").html(dom);

                                        $("#user-city").trigger("change");
                                    });
                                });

                                // 市变化
                                $("#user-city").change(function(){
                                    var city_id = $(this).val();
                                    $.get(url, {p_id:city_id}, function(data){
                                        var dom = "<option value=''>选择县</option>";
                                        var t = "<?=$sear['county']?>";
                                        $.each(data, function (k, v) {
                                            dom += "<option "+((t==k)?'selected':'')+" value="+k+">"+v+"</option>";
                                        })
                                        $("#user-county").html(dom);
                                    });
                                });
                                // 初始化
                                $("#user-province").trigger("change");
                                $("#user-city").trigger("change");

                            </script>
                            <div class="col-sm-3">
                            <span class="input-group-btn">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> 搜索</button>
                            </span>
                            </div>
                        </form>

                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>员工编号</th>
                                    <th>用户名</th>
                                    <th>真实姓名</th>
                                    <th>所属部门</th>
                                    <th>手机号码</th>
                                    <th>邮箱</th>
                                    <th>地区</th>
                                    <th>状态</th>
                                    <th>创建时间</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($user as $vo) { ?>
                                    <tr>
                                        <td><?= $vo['id'] ?></td>
                                        <td><?= $vo['username'] ?></td>
                                        <td><?= $vo['realname'] ?></td>
                                        <!--<td><? /*= $vo['usergroup']['item_name'] */ ?></td>-->
                                        <td><?= $vo['d_name'].'-'.$vo['j_name'] ?></td>
                                        <td><i class="fa fa-mobile"
                                               style="color: #00a2d4;"></i>&nbsp;<?= $vo['cellphone'] ?></td>
                                        <td><i class="fa fa-envelope"
                                               style="color: #00a2d4;"></i>&nbsp;<?= $vo['email'] ?></td>
                                        <td><?= \common\components\Helper::getAddrName($vo['province']).'-', \common\components\Helper::getAddrName($vo['city']). '-'. \common\components\Helper::getAddrName($vo['county']) ?></td>

                                        <td><?= $user_status[$vo['status']] ?></td>
                                        <td><?= date('Y-m-d H:i:s', $vo['created_at']) ?></td>
                                        <td>

                                            <?php if (Yii::$app->getUser()->can(yii\helpers\Url::toRoute(['user/view']))) { ?>
                                                <a class="btn btn-primary btn-xs"
                                                   href="<?= Url::toRoute(['user/view', 'id' => $vo['id']]) ?>"><i
                                                            class="fa fa-edit"></i>查看
                                                </a>
                                            <?php } ?>


                                            <?php if (Yii::$app->getUser()->can(yii\helpers\Url::toRoute(['user/update']), ['id' => $vo['id']])) { ?>
                                                <a class="btn btn-primary btn-xs"
                                                   href="<?= Url::toRoute(['user/update', 'id' => $vo['id']]) ?>"><i
                                                            class="fa fa-edit"></i>编辑
                                                </a>
                                            <?php } ?>

                                            <?php if (Yii::$app->getUser()->can(yii\helpers\Url::toRoute(['user/mod-pwd']))) { ?>
                                                <a class="btn btn-primary btn-xs"
                                                   href="<?= Url::toRoute(['user/mod-pwd', 'id' => $vo['id']]) ?>"><i
                                                            class="fa fa-edit"></i>重置密码
                                                </a>
                                            <?php } ?>

                                            <?php if(($vo['status'] == 1)||($vo['status'] == 2)){ ?>
                                                <?php if (Yii::$app->getUser()->can(yii\helpers\Url::toRoute(['user/activateuser']))) { ?>
                                                    <a class="btn btn-primary btn-xs activate_user" href="javascript:void(0);" data-id="<?= $vo['id']; ?>">激活</a>
                                                <?php } ?>
                                            <?php }elseif($vo['status'] == 10){ ?>
                                                <?php if (Yii::$app->getUser()->can(yii\helpers\Url::toRoute(['user/blockeduser']))) { ?>
                                                    <a class="btn btn-primary btn-xs blocked_user" href="javascript:void(0);" data-id="<?= $vo['id']; ?>">冻结</a>
                                                <?php } ?>
                                            <?php } ?>

                                            <?php if($vo['status'] != 2){ ?>
                                                <?php if (Yii::$app->getUser()->can(yii\helpers\Url::toRoute(['user/leaveuser']))) { ?>
                                                    <a class="btn btn-primary btn-xs leave_user" href="javascript:void(0);" data-id="<?= $vo['id']; ?>">离职</a>
                                                <?php } ?>
                                            <?php } ?>
                                            <?php if (Yii::$app->getUser()->can(yii\helpers\Url::toRoute(['user/delete']))) { ?>
                                                <button class="btn btn-danger btn-xs del-user"
                                                        data-value="<?= $vo['id'] ?>"><i
                                                            class="fa fa-close"></i>删除
                                                </button>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                            <!--分页-->
                            <div class="f-r">
                                <?= LinkPager::widget([
                                    'pagination' => $pages,
                                    'firstPageLabel' => '首页',
                                    'nextPageLabel' => '下一页',
                                    'prevPageLabel' => '上一页',
                                    'lastPageLabel' => '末页',
                                ]) ?>
                            </div>

                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
<!--弹窗内容-->
<style>
    #xx {
        width: 500px;
        height: 175px;
        max-width: 500px;
        max-height: 175px;
        padding:10px;
    }
</style>
<div class="form-group" id="remark-box" style="display: none;padding: 5px;">
    <textarea id="xx" name="remark" placeholder="请填写冻结原因"></textarea>
</div>
<!--弹窗内容-->
<?= Html::jsFile('@web/js/plugins/layer/layer.min.js') ?>
<?php
$this->registerJs("
        var del_url = '" . Url::toRoute(["user/delete"]) . "';
        $('.del-user').on('click', function(ev){
            layer.confirm('是否删除用户?', {icon: 3, title:'删除用户'}, function(index){
                  window.location.href=del_url+'?id='+$(ev.target).attr('data-value');
                  layer.close(index);
            });
        });
    ");
?>

<script>

    // 激活
    $(".activate_user").click(function(){
        var that = $(this);
        layer.confirm("确定要激活此员工？", {title:"系统提示", icon:3}, function(index){
            var loading = layer.load(4);
            var userid = that.data('id');
            var remark = $("#xx").val();

            $.ajax({
                url: "<?= \yii\helpers\Url::toRoute(['user/activateuser']); ?>",
                type: "post",
                dataType: "json",
                data: {id:userid,remark: remark, "<?= Yii::$app->getRequest()->csrfParam; ?>": "<?= Yii::$app->getRequest()->getCsrfToken(); ?>"},
                success: function (data) {
                    if (data.status === 1) {
                        return layer.alert(data.message, {icon: data.status}, function(){return window.location.reload();});
                    }else{
                        return layer.alert(data.message, {icon: data.status});
                    }
                },
                error: function () {
                    layer.alert("噢，我崩溃啦", {title: "系统错误", icon: 5});
                },
                complete: function () {
                    layer.close(loading);
                },
            });
        });
    });

    // 冻结
    $(".blocked_user").click(function(){
        var that = $(this);
        var index = layer.open({
            type: 1,
            title:"填写原因",
            area: ["auto", "300px"], //宽高
            content:$("#remark-box"),
            btn: ["确认", "取消"],
            btn1:function(){
                //var loading = layer.load(4);
                var userid = that.data('id');
                var remark = $("#xx").val();

                $.ajax({
                    url: "<?= \yii\helpers\Url::toRoute(['user/blockeduser']); ?>",
                    type: "post",
                    dataType: "json",
                    data: {id:userid,remark: remark, "<?= Yii::$app->getRequest()->csrfParam; ?>": "<?= Yii::$app->getRequest()->getCsrfToken(); ?>"},
                    success: function (data) {
                        if (data.status === 1) {
                            return layer.alert(data.message, {icon: data.status}, function(){return window.location.reload();});
                        }else{
                            return layer.alert(data.message, {icon: data.status});
                        }
                    },
                    error: function () {
                        layer.alert("噢，我崩溃啦", {title: "系统错误", icon: 5});
                    },
                    complete: function () {
                        layer.close(loading);
                    },
                });
            },
            btn2:function(){
                layer.close(index);
            },
        })
    });

    // 离职
    $(".leave_user").click(function(){
        var that = $(this);
        layer.confirm("确定要操作此员工？", {title:"系统提示", icon:3}, function(index){
            var loading = layer.load(4);
            var userid = that.data('id');
            $.ajax({
                url: "<?= \yii\helpers\Url::toRoute(['user/leaveuser']); ?>",
                type: "post",
                dataType: "json",
                data: {id:userid, "<?= Yii::$app->getRequest()->csrfParam; ?>": "<?= Yii::$app->getRequest()->getCsrfToken(); ?>"},
                success: function (data) {
                    if (data.status === 1) {
                        return layer.alert(data.message, {icon: data.status}, function(){return window.location.reload();});
                    }else{
                        return layer.alert(data.message, {icon: data.status});
                    }
                },
                error: function () {
                    layer.alert("噢，我崩溃啦", {title: "系统错误", icon: 5});
                },
                complete: function () {
                    layer.close(loading);
                },
            });
        });
    });
</script>
