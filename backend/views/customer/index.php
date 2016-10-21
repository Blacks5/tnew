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
<!--                        <hr>-->
                        <form class="row" method="get" action="">
                            <div class="col-sm-2">
                                <input type="text" name="CustomerSearch[c_customer_name]" placeholder="姓名"
                                       value="<?=$sear['c_customer_name']; ?>" class="input form-control">
                            </div>
                            <div class="col-sm-2">
                                <input type="text" name="CustomerSearch[c_customer_cellphone]" value="<?=$sear['c_customer_cellphone']; ?>"
                                       placeholder="手机号" class="input form-control">
                            </div>
                            <div class="col-sm-2">
                                <input type="text" name="CustomerSearch[c_customer_id_card]" value="<?=$sear['c_customer_id_card']; ?>"
                                       placeholder="身份证" class="input form-control">
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
                                    <!--<th>ID</th>-->
                                    <th>姓名</th>
                                    <th>身份证号</th>
                                    <th>手机号码</th>
                                    <th>邮箱</th>
                                    <th>总借款金额</th>
                                    <th>最近借款时间</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($model as $vo) { ?>
                                    <tr>
                                        <td><?= $vo['c_customer_name']; ?></td>
                                        <td><?= $vo['c_customer_id_card']; ?></td>
                                        <td>
                                            <i class="fa fa-mobile" style="color: #00a2d4;"></i>
                                            <?= $vo['c_customer_cellphone']; ?>
                                        </td>
                                        <td>
                                            <i class="fa fa-envelope" style="color: #00a2d4;"></i>
                                            &nbsp;<?= $vo['c_customer_email']; ?>
                                        </td>
                                        <td><?= $vo['c_total_money'] ?></td>
                                        <td><?= $vo['c_updated_at'] ?></td>
                                        <td><a class="btn btn-primary btn-xs"
                                               href="<?= Url::toRoute(['customer/view', 'c_id' => $vo['c_id']]) ?>"><i
                                                    class="fa fa-edit"></i>查看
                                            </a>
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
