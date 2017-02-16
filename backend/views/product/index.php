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
                                <?php if (Yii::$app->getUser()->can(yii\helpers\Url::toRoute(['product/create']))) { ?>
                                <a class="btn btn-info btn-sm" href="<?= Url::toRoute('product/create') ?>">新增产品</a>
                                <?php } ?>
                            </div>
                        </div>
                        <hr>
                        <form class="row" method="get" action="">
                            <div class="col-sm-2">
                                <input type="text" name="Product[p_name]" placeholder="产品名"
                                       value="<?= $sear['p_name']; ?>" class="input form-control">
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
                                    <th>产品名</th>
                                    <th>月利率(%)</th>
                                    <th>期数</th>
                                    <th>贵宾服务包(%)</th>
                                    <th>随心包(元/每月)</th>
                                    <th>财务管理费(%)</th>
                                    <th>客户管理费(%)</th>
                                    <th>状态</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($model as $vo) { ?>
                                    <tr>
                                        <td><?= $vo['p_name'] ?></td>
                                        <td><?= $vo['p_month_rate'] ?></td>
                                        <td><?= $vo['p_period'] ?></td>
                                        <td><?= $vo['p_add_service_fee'] ?></td>
                                        <td><?= $vo['p_free_pack_fee'] ?></td>
                                        <td><?= $vo['p_finance_mangemant_fee'] ?></td>
                                        <td><?= $vo['p_customer_management'] ?></td>
                                        <td><?= $vo['p_status'] ?></td>
                                        <td>

                                            <?php if (Yii::$app->getUser()->can(yii\helpers\Url::toRoute(['product/view']))) { ?>
                                                <a class="btn btn-primary btn-xs"
                                               href="<?= Url::toRoute(['product/view', 'id' => $vo['p_id']]) ?>"><i
                                                    class="fa fa-edit"></i>查看
                                            </a>
                                            <?php } ?>
                                            <?php if (Yii::$app->getUser()->can(yii\helpers\Url::toRoute(['product/delete']))) { ?>
                                                <button class="btn btn-danger btn-xs del-product"
                                                        data-value="<?= $vo['p_id'] ?>"><i
                                                        class="fa fa-close"></i>删除
                                                </button>
                                            <?php } ?>
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
        var del_url = '" . Url::toRoute(["product/delete"]) . "';
        $('.del-product').on('click', function(ev){
            layer.confirm('是否删除产品?', {icon: 3, title:'删除产品'}, function(index){
                  window.location.href=del_url+'?p_id='+$(ev.target).attr('data-value');
                  layer.close(index);
                  layer.close(index);
            });
        });
    ");
?>