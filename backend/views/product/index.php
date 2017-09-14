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
                            <div class="col-sm-10">
                                <span class="col-sm-10">
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> 搜索</button>
                                </span>
                                <div class="col-sm-2">
                                    <select class="form-control p_filter" name="">
                                        <option value="100" <?php if($status == 100){ echo 'selected';} ?>>全&nbsp&nbsp&nbsp&nbsp部</option>
                                        <option value="1" <?php if($status == 1){ echo 'selected';} ?>>已冻结</option>
                                        <option value="10" <?php if($status == 10){ echo 'selected';} ?>>未冻结</option>
                                    </select>
                                    <input type="hidden" name="Product[p_status]" id="pro_status"/>
                                </div>
                            </div>
                        </form>

                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>产品名</th>
                                    <th>月利率(%)</th>
                                    <th>期数</th>
                                    <th>贵宾服务包(元/每月)</th>
                                    <th>个人保障计划(%)</th>
                                    <th>财务管理费(%)</th>
                                    <th>客户管理费(%)</th>
                                    <th>状态</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($model as $vo) { ?>
                                    <tr>
                                        <td><?= $vo['p_name'] ?><span class="text-danger"><?php if($vo['p_is_promotional'] == 1){echo '（促销）';}else{echo '（常规）';} ?></span></td>
                                        <td><?= $vo['p_month_rate'] ?></td>
                                        <td><?= $vo['p_period'] ?></td>
                                        <td><?= $vo['p_free_pack_fee'] ?></td>
                                        <td><?= $vo['p_add_service_fee'] ?></td>
                                        <td><?= $vo['p_finance_mangemant_fee'] ?></td>
                                        <td><?= $vo['p_customer_management'] ?></td>
                                        <td><?= \common\models\Product::getAllStatus()[$vo['p_status']]; ?></td>
                                        <td>
                                            <?php if (Yii::$app->getUser()->can(yii\helpers\Url::toRoute(['product/view']))) { ?>
                                                <a class="btn btn-primary btn-xs" href="<?= Url::toRoute(['product/view', 'id' => $vo['p_id']]) ?>"><i class="fa fa-edit"></i>查看</a>
                                            <?php } ?>
                                            <?php if (Yii::$app->getUser()->can(yii\helpers\Url::toRoute(['product/update']))) { ?>
                                                <a class="btn btn-primary btn-xs" href="<?= Url::toRoute(['product/update', 'id' => $vo['p_id']]) ?>"><i class="fa fa-edit"></i>编辑</a>
                                            <?php } ?>
                                            <?php if($vo['p_status'] == 10 && Yii::$app->getUser()->can(yii\helpers\Url::toRoute(['product/freeze']))){ ?>
                                                <button class="btn btn-success btn-xs freeze-product" data-value="<?= $vo['p_id'] ?>"><i class="fa fa-edit"></i>冻结</button>
                                            <?php }else if($vo['p_status'] == 1 && Yii::$app->getUser()->can(yii\helpers\Url::toRoute(['product/Thaw']))){ ?>
                                                <button class="btn btn-warning btn-xs thaw-product" data-value="<?= $vo['p_id'] ?>"><i class="fa fa-edit"></i>解冻</button>
                                            <?php } ?>
                                            <?php if (Yii::$app->getUser()->can(yii\helpers\Url::toRoute(['product/delete']))) { ?>
                                                <button class="btn btn-danger btn-xs del-product" data-value="<?= $vo['p_id'] ?>"><i class="fa fa-close"></i>删除</button>
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

$this->registerJs("
        var freeze_url = '" . Url::toRoute(["product/freeze"]) . "';
        $('.freeze-product').on('click', function(ev){
            layer.confirm('是否冻结产品?', {icon: 3, title:'冻结产品'}, function(index){
                  window.location.href=freeze_url+'?p_id='+$(ev.target).attr('data-value');
                  layer.close(index);
                  layer.close(index);
            });
        });
    ");

$this->registerJs("
        var thaw_url = '" . Url::toRoute(["product/thaw"]) . "';
        $('.thaw-product').on('click', function(ev){
            layer.confirm('是否解冻产品?', {icon: 3, title:'解冻产品'}, function(index){
                  window.location.href=thaw_url+'?p_id='+$(ev.target).attr('data-value');
                  layer.close(index);
                  layer.close(index);
            });
        });
    ");

$this->registerJs("
    $('.p_filter').on('change', function(){
        $('#pro_status').val($(this).val());
        $('form').submit();
    });
");
?>