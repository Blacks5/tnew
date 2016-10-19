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
                                <a class="btn btn-info btn-sm" href="<?= Url::toRoute('user/create') ?>">新增用户</a>
                            </div>
                        </div>
                        <hr>
                        <form class="row" method="get" action="">
                            <div class="col-sm-2">
                                <input type="text" name="UserSearch[username]" placeholder="用户名"
                                       value="<?php echo $sear['username']; ?>" class="input form-control">
                            </div>
                            <div class="col-sm-2">
                                <input type="text" name="UserSearch[realname]" value="<?php echo $sear['realname']; ?>"
                                       placeholder="真实姓名" class="input form-control">
                            </div>
                            <div class="col-sm-2">
                                <input type="text" name="UserSearch[email]" value="<?php echo $sear['email']; ?>"
                                       placeholder="邮箱" class="input form-control">
                            </div>
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
                                    <th>ID</th>
                                    <th>用户名</th>
                                    <th>所属部门</th>
                                    <th>手机号码</th>
                                    <th>邮箱</th>
                                    <th>创建时间</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($user as $vo) { ?>
                                    <tr>
                                        <td><?= $vo['id'] ?></td>
                                        <td><?= $vo['username'] ?></td>
                                        <td><?= $vo['usergroup']['item_name'] ?></td>
                                        <td><i class="fa fa-mobile"
                                               style="color: #00a2d4;"></i>&nbsp;<?= $vo['cellphone'] ?></td>
                                        <td><i class="fa fa-envelope"
                                               style="color: #00a2d4;"></i>&nbsp;<?= $vo['email'] ?></td>
                                        <td><?= date('Y-m-d H:i:s', $vo['created_at']) ?></td>
                                        <td><a class="btn btn-primary btn-xs"
                                               href="<?= Url::toRoute(['user/view', 'id' => $vo['id']]) ?>"><i
                                                    class="fa fa-edit"></i>查看
                                            </a>
                                            <a class="btn btn-primary btn-xs"
                                               href="<?= Url::toRoute(['user/update', 'item_name' => $vo['usergroup']['item_name'], 'id' => $vo['id']]) ?>"><i
                                                    class="fa fa-edit"></i>编辑
                                            </a>
                                            <a class="btn btn-primary btn-xs"
                                               href="<?= Url::toRoute(['user/mod-pwd', 'id' => $vo['id']]) ?>"><i
                                                    class="fa fa-edit"></i>重置密码
                                            </a>
                                            <?php if ($vo['usergroup']['item_name'] != '超级管理员') { ?>
                                                <button class="btn btn-danger btn-xs del-user"
                                                        data-value="<?= $vo['id'] ?>"><i
                                                        class="fa fa-close"></i>删除
                                                </button>
                                            <?php } ?></td>
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
<?= Html::jsFile('@web/js/plugins/layer/layer.min.js') ?>
<?php
$this->registerJs("
        var del_url = '" . Url::toRoute(["user/delete", "id" => $vo["id"]]) . "';
        $('.del-user').on('click', function(ev){
            layer.confirm('是否删除用户?', {icon: 3, title:'删除用户'}, function(index){
                  console.log($(this).attr('data-value'));

                  layer.close(index);
            });
        });
    ");
?>