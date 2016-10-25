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
                            <a href="<?= Yii::$app->getUrlManager()->createUrl(['team/create']) ?>"
                               class="btn btn-success">新增团队</a>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <!--                    <span class="text-muted small pull-right">最后更新：<i class="fa fa-clock-o"></i> 2015-09-01 12:00</span>-->
                    <form class="row" method="get" action="">
                        <div class="col-sm-2">
                            <input type="text" name="Team[t_name]" placeholder="团队名"
                                   value="<?php echo $sear['t_name']; ?>" class="input form-control">
                        </div>
                        <div class="col-sm-3">
                            <span class="input-group-btn">
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
                                                    <th class="client-avatar">id</th>
                                                    <th><a data-toggle="tab" href="#contact-3"
                                                           class="client-link">团队名</a></th>
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
                                                        <td class="client-avatar"><?= $_v['t_id'] ?></td>
                                                        <td><a data-toggle="tab" href="#contact-3"
                                                               class="client-link"><?= $_v['t_name'] ?></a></td>
                                                        <td>
                                                            <a href="<?= Yii::$app->getUrlManager()->createUrl(['team/view', 't_id' => $_v['t_id']]); ?>"
                                                               class="btn btn-primary btn-xs">详情</a>
                                                            <a class="btn btn-primary btn-xs"
                                                               href="<?= Yii::$app->getUrlManager()->createUrl(['team/update', 't_id' => $_v['t_id']]); ?>">编辑</a>
                                                            <a class="btn btn-danger btn-xs"
                                                               href="javascript:del('<?= $_v['t_name'] ?>', <?= $_v['t_id'] ?>)">删除</a>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                                <script>
                                                    function del(name, id) {
                                                        layer.confirm('是否删除团队: ' + name, {
                                                            icon: 3,
                                                            title: '删除团队'
                                                        }, function (index) {
                                                            location.href = "<?= Yii::$app->getUrlManager()->createUrl(['team/delete']); ?>" + "?t_id=" + id;
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
