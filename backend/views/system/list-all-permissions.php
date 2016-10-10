<?php
/**
 * Created by PhpStorm.
 * User: too
 * Date: 2016/10/10
 * Time: 16:20
 * @author 涂鸿 <hayto@foxmail.com>
 */
use \yii\helpers\Html;
use \yii\helpers\Url;

?>


<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-sm-4">
        <h2>权限</h2>
        <ol class="breadcrumb">
            <li>
                <a href="#">主页</a>
            </li>
            <li>
                <strong>面包屑导航</strong>
            </li>
        </ol>
    </div>
    <div class="col-sm-8">
        <div class="title-action">
            <a href="<?= Url::toRoute(['system/create-permission'])?>" class="btn btn-primary fa fa-plus">添加菜单</a>
        </div>
    </div>
</div>

<div class="wrapper wrapper-content">
    <div class="row">
        <div class="col-sm-12">

            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                            <tr>

                                <!--<th  width="15%">排序</th>-->
                                <th width="15%">id</th>
                                <th width="25%">菜单名称</th>
                                <th width="25%">添加时间</th>
                                <th width="45%" >管理操作</th>
                            </tr>
                            </thead>
                            <tbody>

                            <?php foreach ($data as $v){ ?>
                                <tr>
                                    <td><?= $v['permission_id']; ?></td>
                                    <td><?= $v['permission_name']; ?></td>
                                    <td><?= date('Y-m-d', $v['permission_create_at']); ?></td>
                                    <td>
                                        <a href="">添加子菜单</a>&nbsp;|&nbsp;
                                        <a href="">修改</a>&nbsp;|&nbsp;
                                        <a href="">删除</a>
                                    </td>
                                </tr>
                            <?php } ?>

                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

