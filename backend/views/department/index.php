<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\widgets\LinkPager;

$this->params['breadcrumbs'][] = $this->title;
?>

<div class="wrapper wrapper-content">
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox">
                <div class="ibox-content">
                    <div class="row">
                        <div class="col-sm-3">
                            <a class="btn btn-info btn-sm" href="<?= Yii::$app->getUrlManager()->createUrl(['department/create-department']) ?>">新增部门</a>
                        </div>
                    </div>
                    <hr/>

<!--                    <span class="text-muted small pull-right">最后更新：<i class="fa fa-clock-o"></i> 2015-09-01 12:00</span>
                    <form class="row" method="get" action="">
                        <div class="col-sm-2">
                            <input type="text" name="Department[d_name]" placeholder="名称"
                                   value="<?php /*echo $sear['d_name']; */?>" class="input form-control">
                        </div>
                        <div class="col-sm-2">
                            <input type="text" name="Department[d_status]" value="<?php /*echo $sear['d_status']; */?>"
                                   placeholder="状态" class="input form-control">
                        </div>
                        <div class="col-sm-2">
                            <input type="text" name="Product[owner_email]" value="<?php /*echo $sear['owner_email']; */?>"
                                   placeholder="负责人邮箱" class="input form-control">
                        </div>
                        <div class="col-sm-3">
                            <span class="input-group-btn">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> 搜索</button>
                            </span>
                        </div>
                    </form>-->

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>部门名</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($model as $_k => $_v) { ?>
                                <tr>
                                    <td><?= $_v['d_id'] ?></td>
                                    <td><?= $_v['d_name'] ?></td>
                                    <td>
                                        <a href="<?= Yii::$app->getUrlManager()->createUrl(['department/view-department', 'd_id' => $_v['d_id']]); ?>"
                                           class="btn btn-primary btn-xs">详情</a>
                                        <!--<a class="button" href="<? /*= Yii::$app->getUrlManager()->createUrl(['department/create-job','d_id'=>$_v['d_id']]); */
                                        ?>">添加职位</a>-->
                                        <a class="btn btn-primary btn-xs"
                                           href="<?= Yii::$app->getUrlManager()->createUrl(['department/update-department', 'd_id' => $_v['d_id']]); ?>">编辑</a>
                                        <a class="btn btn-danger btn-xs"
                                           href="javascript:del('<?= $_v['d_name'] ?>', <?= $_v['d_id'] ?>)">删除</a>
                                    </td>
                                </tr>
                            <?php } ?>
                            <script>
                                function del(name, id) {
                                    layer.confirm('是否删除部门' + name, {icon: 3, title: '删除部门'}, function (index) {
                                        location.href = "<?= Yii::$app->getUrlManager()->createUrl(['department/delete-department']); ?>" + "?d_id=" + id;
                                        layer.close(index);
                                    });

                                }
                            </script>
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
        <?= Html::jsFile('@web/js/plugins/layer/layer.min.js') ?>
        <section id="creat_de" style="display: none;">
            <div id="signupForm" class="form-horizontal m-t form-vertical">
                <div class="ibox float-e-margins">
                    <div class="ibox-content">
                        <div class="form-group field-department-d_name">
                            <label class="control-label col-sm-3" for="department-d_name">部门名称:</label>
                            <div class="col-sm-7">
                                <input type="text" id="department_d_name" class="form-control"
                                       name="Department[d_name]">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>