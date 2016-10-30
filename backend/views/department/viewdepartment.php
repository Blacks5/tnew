<?php
use yii\helpers\Html;

$this->params['breadcrumbs'][] = ['label' => '部门列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="ibox float-e-margins">
    <div class="ibox-content">
        <div class="form-horizontal m-t" id="signupForm" novalidate="novalidate" action="" method="post">
            <div class="form-group">
                <div style="height: 30px;line-height: 30px;">
                    <strong class="col-sm-2  text-right">部门名称：</strong>
                    <span class="col-sm-10">
                        <span class="form-control-static label label-warning"><?= $model['d_name']; ?></span>
                    </span>
                </div>
                <div>
                    <strong class="col-sm-2 text-right">现有职位：</strong>
                    <span class="col-sm-10">
                        <?php if ($jobs) { ?>
                            <?php foreach ($jobs as $v) { ?>
                                <span class="form-control-static label label-info"><?= $v->j_name; ?></span>
                            <?php }
                        } else { ?>
                            <span class="form-control-static">暂无</span>
                        <?php } ?>
                    </span>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-8 col-sm-offset-3">
                    <!--<a class="btn btn-primary" href="<? /*=Yii::$app->getUrlManager()->createUrl(['department/update-department', 'd_id'=>$model['d_id']])*/ ?>">编辑职位</a>-->
                    <?php if (Yii::$app->getUser()->can(yii\helpers\Url::toRoute(['department/create-job_bak']))) { ?>
                        <a class="btn btn-sm btn-primary" href="javascript:void(0);"
                           onclick="test_l_job('<?= Yii::$app->getUrlManager()->createUrl(['department/create-job_bak', 'd_id' => $model['d_id']]) ?>');">添加职位</a>
                    <?php } ?>
                    <?php if (Yii::$app->getUser()->can(yii\helpers\Url::toRoute(['department/update-job_bak']))) { ?>
                        <a class="btn btn-sm btn-primary" href="javascript:void(0);"
                           onclick="job_list_alert();">编辑职位</a>
                    <?php } ?>
                    <a class="btn btn-sm btn-default"
                       href="<?= Yii::$app->getUrlManager()->createUrl(['department/index']) ?>">返回</a>
                </div>
            </div>
        </div>
    </div>
    <section id="job_list" style="display: none;">
        <div id="signupForm" class="form-horizontal m-t form-vertical">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <?php if ($jobs) { ?>
                        <?php foreach ($jobs as $v) { ?>
                            <input class="form-control-static"
                                   style="height: 20px; line-height: 20px;margin-bottom: 10px;font-size: 15px;"
                                   value="<?= $v->j_name; ?>" id="name_<?= $v->j_id; ?>"/>
                            <a href="javascript:void(0);" id="save_<?= $v->j_id; ?>"
                               onclick="save_job('<?= Yii::$app->getUrlManager()->createUrl(['department/update-job_bak', 'd_id' => $v->j_department_id, 'j_id' => $v->j_id]) ?>','<?= $v->j_id; ?>');"><i
                                    class="fa fa-floppy-o" style="font-size: 20px;"></i></a>
                            <a href="javascript:void(0);" id="del_<?= $v->j_id; ?>"
                               onclick="del_job('<?= Yii::$app->getUrlManager()->createUrl(['department/delete-job_bak', 'j_id' => $v->j_id]) ?>','<?= $v->j_id; ?>');"><i
                                    class="fa fa-trash-o" style="font-size: 20px;"></i></a>
                        <?php }
                    } else { ?>
                        <span class="form-control-static">暂无</span>
                    <?php } ?>
                </div>
            </div>
        </div>
    </section>

    <section id="creat_job" style="display: none;">
        <div id="signupForm" class="form-horizontal m-t form-vertical">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <div class="form-group field-department-d_name">
                        <label class="control-label col-sm-3" for="department-d_name">职位名称:</label>
                        <div class="col-sm-7">
                            <input type="text" id="job_name" class="form-control" name="job_name">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?= Html::jsFile('@web/js/plugins/layer/layer.min.js') ?>
    <script>
        //添加职位
        function test_l_job(url) {
            layer.open({
                type: 1,
                title: '添加职位',
                shadeClose: true,
                shade: 0.8,
                area: ['380px', '35%'],
                content: $('#creat_job'),
                btn: ['添加', '取消'],
                yes: function (index) {
                    var job_name_d = $('#job_name').val();
                    if (!job_name_d) {
                        layer.msg('请填写职位名称', {icon: 2, time: 2000});
                        return false;
                    }
                    $.ajax({
                        url: url,
                        data: {
                            j_name: job_name_d,
                            "<?= Yii::$app->getRequest()->csrfParam ?>": "<?= Yii::$app->getRequest()->getCsrfToken() ?>"
                        },
                        type: 'post',
                        dataType: 'json',
                        success: function (data) {
                            if (data.status == 1) {
                                layer.msg(data.message, {icon: 1, time: 2000}, function () {
                                    window.location.reload()
                                });
                            } else {
                                return layer.msg(data.message, {icon: 2, time: 2000});
                            }
                        },
                        error: function () {
                            return layer.msg('网络错误', {icon: 2, time: 2000});
                        },
                        complete: function (data) {
                            if (data.status == 403) {
                                return layer.msg(data.responseJSON.message, {icon: 2, time: 2000});
                            }
                        }

                    });
                }, cancel: function () {

                }
            });
        }
        //弹窗
        function job_list_alert() {
            console.log();
            layer.open({
                type: 1,
                title: '编辑职位',
                shadeClose: true,
                shade: 0.8,
                area: ['380px', '50%'],
                content: $('#job_list'),
                end: function () {
                    window.location.reload();
                }
            });
        }
        //保存职位
        function save_job(url, jname) {
            var jobname = $('#name_' + jname).val();
            if (!jobname) {
                layer.msg('请填写职位名称！');
                return false;
            }
            $.ajax({
                url: url,
                data: {
                    j_name: jobname,
                    "<?= Yii::$app->getRequest()->csrfParam ?>": "<?= Yii::$app->getRequest()->getCsrfToken() ?>"
                },
                type: 'post',
                dataType: 'json',
                success: function (data) {
                    if (data.status == 1) {
                        layer.msg(data.message, {icon: 1, time: 2000});
                    } else {
                        layer.msg(data.message, {icon: 2, time: 2000});
                    }
                },
                error: function () {
                    return layer.msg('网络错误', {icon: 2, time: 2000});
                },
                complete: function (data) {
                    if (data.status == 403) {
                        return layer.msg(data.responseJSON.message, {icon: 2, time: 2000});
                    }
                }
            });
        }
        //删除职位
        function del_job(url, job_id) {
            $.ajax({
                url: url,
                data: {"<?= Yii::$app->getRequest()->csrfParam ?>": "<?= Yii::$app->getRequest()->getCsrfToken() ?>"},
                type: 'post',
                dataType: 'json',
                success: function (data) {
                    if (data.status == 1) {
                        $('#name_' + job_id).hide();
                        $('#save_' + job_id).hide();
                        $('#del_' + job_id).hide();
                        layer.msg(data.message, {icon: 1, time: 2000});
                    } else {
                        return layer.alert(data.message);
                    }
                },
                error: function () {
                    return layer.msg('网络错误', {icon: 2, time: 2000});
                },
                complete: function (data) {
                    if (data.status == 403) {
                        return layer.msg(data.responseJSON.message, {icon: 2, time: 2000});
                    }
                }
            });
        }
    </script>