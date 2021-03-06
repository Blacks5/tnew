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
                <!--                <a href="javascript:history.go(-1);" class="btn btn-info">返回</a>-->
                <div class="ibox-content">


                    <div class="lightBoxGallery">
                        <?php
                        $_notice_name = (new \common\models\OrderImages())->attributeLabels();
                        foreach ($data as $k=>$v) {
                            if (!empty($v)) {
                                ?>
                                <strong><?=$_notice_name[$k];?></strong>
                                <a href="<?php echo (new \common\models\UploadFile())->getUrl($v); ?>" title="图片"
                                   data-gallery="">
                                    <img height="200" width="200"
                                         src="<?php echo (new \common\models\UploadFile())->getUrl($v); ?>">
                                </a>
                                <?php
                            }
                        }
                        if($other1!=null) {
                            echo "</br><hr/><strong>一审其他资质文件</strong>";
                            foreach ($other1 as $k => $v) {
                                if (!empty($v)) {
                                    ?>

                                    <a href="<?php echo (new \common\models\UploadFile())->getUrl($v); ?>" title="图片"
                                       data-gallery="">
                                        <img height="200" width="200"
                                             src="<?php echo (new \common\models\UploadFile())->getUrl($v); ?>">
                                    </a>
                                    <?php
                                }
                            }
                        }
                        if($other2!=null) {
                            echo "</br><hr/><strong>二审其他资质文件</strong>";
                            foreach ($other2 as $k => $v) {
                                if (!empty($v)) {
                                    ?>
                                    <a href="<?php echo (new \common\models\UploadFile())->getUrl($v); ?>" title="图片"
                                       data-gallery="">
                                        <img height="200" width="200"
                                             src="<?php echo (new \common\models\UploadFile())->getUrl($v); ?>">
                                    </a>
                                    <?php
                                }
                            }
                        }

                        ?>


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

<? //= \yii\helpers\Html::jsFile('@web/js/plugins/layer/layer.min.js') ?>
<?= \yii\helpers\Html::jsFile('@web/js/bootstrap.min.js') ?>
<?= \yii\helpers\Html::jsFile('@web/js/content.js') ?>
<?= \yii\helpers\Html::jsFile('@web/js/plugins/blueimp/jquery.blueimp-gallery.min.js') ?>
