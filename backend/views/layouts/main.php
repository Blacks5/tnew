<?php
use \yii\helpers\Html;
?>
<!DOCTYPE html>
<html>
<head>

    <!-- Meta -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="renderer" content="webkit">
    <title>后台首页</title>

    <!-- CSS文件 -->
    <?= Html::cssFile('@web/static/css/bootstrap.min.css'); ?>
    <?= Html::cssFile('@web/static/css/font-awesome.css'); ?>
    <?= Html::cssFile('@web/static/css/animate.css'); ?>
    <?= Html::cssFile('@web/static/css/style.css'); ?>
</head>
<body class="fixed-sidebar full-height-layout gray-bg" style="overflow:hidden">

<!--全局js-->
<?= Html::jsFile('@web/static/js/jquery.min.js?v=2.1.4') ?>
<?= Html::jsFile('@web/static/js/bootstrap.min.js?v=3.3.6') ?>

<?= $content; ?>

</body>
</html>
