<?php
/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\widgets\Pjax;

$this->params['breadcrumbs'][] = $this->title;
?>

<div>
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox">
                <div class="ibox-content">

                    <div class="row">
                        <div class="col-sm-3">
                            <?php if (Yii::$app->getUser()->can(yii\helpers\Url::toRoute(['stores/addsale']))) { ?>
                                <a href="<?= Yii::$app->getUrlManager()->createUrl(['stores/addsale', 'ss_store_id' => Yii::$app->getRequest()->get('ss_store_id')]) ?>"
                                   class="btn btn-success">新增销售</a>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <!-- <span class="text-muted small pull-right">最后更新：<i class="fa fa-clock-o"></i> 2015-09-01 12:00</span>-->
                    <form class="row" method="get" action="">
                        <div class="col-sm-2">
                            <input type="text" name="realname" placeholder="销售姓名"
                                   value="<?php echo isset($sear['realname']) ? $sear['realname'] : ''; ?>"
                                   class="input form-control">
                        </div>
                        <div class="col-sm-3">
                            <span class="input-group-btn">
                                <input type="hidden" name="ss_store_id"
                                       value="<?php echo Yii::$app->getRequest()->get('ss_store_id'); ?>">
                                <button type="submit" class="btn btn-primary"> <i class="fa fa-search"></i> 搜索</button>
                            </span>
                        </div>
                    </form>

                    <div class="clients-list">
                        <div class="tab-content">
                            <div id="tab-1" class="tab-pane active">
                                <div class="slimScrollDiv" style="position: relative; width: auto; height: 100%;">
                                    <div class="full-height-scroll" style="width: auto; height: 100%;">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover">
                                                <thead>
                                                <tr>
                                                    <th class="client-avatar">编号</th>
                                                    <th><a data-toggle="tab" href="#contact-3"
                                                           class="client-link">销售姓名</a></th>
                                                    <!--<th>商户店名</th>
                                                    <th class="contact-type"></th>
                                                    <th>邮箱</th>
                                                    <th class="client-status">状态</th>
                                                    <th>添加时间</th>-->
                                                    <th>操作</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                foreach ($model as $_k => $_v) {
                                                    ?>

                                                    <tr>
                                                        <td class="client-avatar"><?= $_v['ss_id'] ?></td>
                                                        <td><a data-toggle="tab" href="#contact-3"
                                                               class="client-link"><?= $_v['realname'] ?></a></td>
                                                        <td>
                                                            <?php if (Yii::$app->getUser()->can(yii\helpers\Url::toRoute(['stores/deletesale']))) { ?>
                                                                <a class="btn btn-danger btn-xs"
                                                                   href="javascript:del('<?= $_v['realname'] ?>', <?= $_v['ss_id'] ?>, <?= $_v['ss_store_id'] ?>)"><i
                                                                            class="fa fa-close"></i> 删除</a>
                                                            <?php } ?>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                                <script>
                                                    function del(name, id, storeid) {
                                                        layer.confirm('是否删除此销售: ' + name, {
                                                            icon: 3,
                                                            title: '删除销售人员'
                                                        }, function (index) {
                                                            location.href = "<?= Yii::$app->getUrlManager()->createUrl(['stores/deletesale']); ?>" + "?ss_id=" + id + "&ss_store_id=" + storeid;
                                                            layer.close(index);
                                                        });

                                                    }
                                                </script>

                                                </tbody>
                                            </table>
                                        </div>

                                        <!--分页-->
                                        <div class="f-r">
                                            <?= LinkPager::widget([
                                                'pagination' => $totalpage,
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
            </div>
        </div>
        <?= Html::jsFile('@web/js/plugins/layer/layer.min.js') ?>
