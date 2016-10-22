<?php

use yii\helpers\Html;
//use yii\grid\GridView;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

//app\assets\LayerAsset::register($this);
//app\assets\MainAsset::register($this);
$this->params['breadcrumbs'][] = $this->title;
?>

<!--<link rel="stylesheet" href="/statics/css/animate.min.css">-->
<link rel="stylesheet" href="/statics/css/style.min.css">

<div>
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox">
                <div class="ibox-content">

                    <div class="row">
                        <div class="col-sm-3">
                            <a href="<?= Yii::$app->getUrlManager()->createUrl(['stores/create']) ?>"
                               class="btn btn-success">新增商户</a>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <!--                    <span class="text-muted small pull-right">最后更新：<i class="fa fa-clock-o"></i> 2015-09-01 12:00</span>-->
                    <form class="row" method="get" action="">
                        <div class="col-sm-2">
                            <input type="text" name="Stores[s_owner_name]" placeholder="负责人姓名"
                                   value="<?php echo $sear['s_owner_name']; ?>" class="input form-control">
                        </div>
                        <div class="col-sm-2">
                            <input type="text" name="Stores[s_owner_phone]"
                                   value="<?php echo $sear['s_owner_phone']; ?>" placeholder="负责人电话"
                                   class="input form-control">
                        </div>
                        <div class="col-sm-2">
                            <input type="text" name="Stores[s_owner_email]"
                                   value="<?php echo $sear['s_owner_email']; ?>" placeholder="负责人邮箱"
                                   class="input form-control">
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
                                                           class="client-link">负责人姓名</a></th>
                                                    <th>商户店名</th>
                                                    <th class="contact-type"></th>
                                                    <th>邮箱</th>
                                                    <th class="client-status">状态</th>
                                                    <th>添加时间</th>
                                                    <th>操作</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                foreach ($model as $_k => $_v) {
                                                    ?>

                                                    <tr>
                                                        <td class="client-avatar"><?= $_v['s_id'] ?></td>
                                                        <td><a data-toggle="tab" href="#contact-3"
                                                               class="client-link"><?= $_v['s_owner_name'] ?></a></td>
                                                        <td><?= $_v['s_name'] ?></td>
                                                        <td class="contact-type"><i class="fa fa-envelope"> </i></td>
                                                        <td><?= $_v['s_owner_email'] ?></td>
                                                        <td class="client-status"><?= $_v['s_status']; ?></td>
                                                        <td class="client-status"><?= date("Y-m-d H:i:s", $_v['s_created_at']) ?></td>
                                                        <td>
                                                            <a href="<?= Yii::$app->getUrlManager()->createUrl(['stores/allorders', 'id' => $_v['s_id']]); ?>"
                                                               class="btn btn-primary btn-xs">订单</a>
                                                            <a href="<?= Yii::$app->getUrlManager()->createUrl(['stores/view', 'id' => $_v['s_id']]); ?>"
                                                               class="btn btn-primary btn-xs">详情</a>
                                                            <a class="btn-xs btn btn-primary"
                                                               href="<?= Yii::$app->getUrlManager()->createUrl(['stores/update', 'id' => $_v['s_id']]); ?>">编辑</a>
                                                            <button class="btn-xs btn btn-danger del-stores"
                                                                    data-value="<?= $_v['s_id'] ?>">删除</button>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                                <script>
                                                    function del(name, id) {
                                                        layer.confirm('是否删除商户' + name, {
                                                            icon: 3,
                                                            title: '删除员商户'
                                                        }, function (index) {
                                                            location.href = "<?= Yii::$app->getUrlManager()->createUrl(['stores/delete']); ?>" + "?id=" + id;
                                                            layer.close(index);
                                                        });

                                                    }
                                                </script>

                                                </tbody>
                                            </table>
                                        </div>
                                        <!--分页-->
                                        <div class="f-r">
                                            <?= \yii\widgets\LinkPager::widget([
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
            </div>
        </div>
<?= Html::jsFile('@web/js/plugins/layer/layer.min.js') ?>
<?php
$this->registerJs("
        var del_url = '" . \yii\helpers\Url::toRoute(["stores/delete"]) . "';
        $('.del-stores').on('click', function(ev){
            layer.confirm('是否删除商户?', {icon: 3, title:'删除商户'}, function(index){
                  window.location.href=del_url+'?id='+$(ev.target).attr('data-value');
                  layer.close(index);
            });
        });
    ");
?>