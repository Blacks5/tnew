<?php

/* @var $this \yii\web\View */
/* @var $content string */

// use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;

// AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>

    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <title>Y+后台管理框架 - 主页</title>
    <meta name="keywords" content="Y+,后台管理,Yii2 RBAC,H+后台主题,后台bootstrap框架,会员中心,后台HTML,响应式后台">
    <meta name="description" content="Y+是Yii2 + Bootstrap搭建的后台管理系统，集成用户中心模块和RBAC权限管理模块。模板使用H+基于Bootstrap3最新版本开发的扁平化主题。">
    <!--[if lt IE 9]>
    <meta http-equiv="refresh" content="0;ie.html" />
    <![endif]-->
    <link rel="shortcut icon" href="favicon.ico">

    <?=Html::cssFile('@web/css/bootstrap.min14ed.css')?>
    <?=Html::cssFile('@web/css/font-awesome.min93e3.css')?>
    <?=Html::cssFile('@web/css/animate.min.css')?>
    <?=Html::cssFile('@web/css/style.min862f.css')?>
    <?=Html::cssFile('@web/css/site.css')?>

    <?=Html::jsFile('@web/js/jquery.min.js')?>
    <?php $this->head() ?>
</head>
<body class="fixed-sidebar full-height-layout gray-bg">
<?php $this->beginBody() ?>
<?= $content ?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
