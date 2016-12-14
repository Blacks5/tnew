<?php
use \common\components\Helper;

$this->title = '借款详情';
?>
<?= \yii\helpers\Html::cssFile('@web/css/style.css') ?>
<?= \yii\helpers\Html::cssFile('@web/css/plugins/blueimp/css/blueimp-gallery.min.css') ?>
<style>
    .lightBoxGallery img {
        margin: 5px;
        width: 160px;
    }
</style>
    <div class="wrapper wrapper-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">

                    <div class="ibox-content">


                        <div class="lightBoxGallery">
                            <a href="http://obuoq9z60.bkt.clouddn.com/KdAVbWEYsLkld8Em2oCZP6d0pCsu-fHq" title="图片" data-gallery=""><img src="http://obuoq9z60.bkt.clouddn.com/KdAVbWEYsLkld8Em2oCZP6d0pCsu-fHq"></a>
                            <a href="http://obuoq9z60.bkt.clouddn.com/KdAVbWEYsLkld8Em2oCZP6d0pCsu-fHq" title="图片" data-gallery=""><img src="http://obuoq9z60.bkt.clouddn.com/KdAVbWEYsLkld8Em2oCZP6d0pCsu-fHq"></a>

                            <div id="blueimp-gallery" class="blueimp-gallery">
                                <div class="slides"></div>
                                <h3 class="title"></h3>
                                <a class="prev">‹</a>
                                <a class="next">›</a>
                                <a class="close">×</a>
                                <a class="play-pause"></a>
                                <ol class="indicator"></ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?//= \yii\helpers\Html::jsFile('@web/js/plugins/layer/layer.min.js') ?>
<?= \yii\helpers\Html::jsFile('@web/js/bootstrap.min.js') ?>
<?= \yii\helpers\Html::jsFile('@web/js/content.js') ?>
<?= \yii\helpers\Html::jsFile('@web/js/plugins/blueimp/jquery.blueimp-gallery.min.js') ?>
