<?php
?>
<link href="/css/plugins/datapicker/datepicker3.css" rel="stylesheet">
<script src="/js/plugins/datapicker/bootstrap-datepicker.js"></script>
<?= \yii\helpers\Html::jsFile('@web/js/plugins/layer/layer.min.js') ?>
<div class="ibox float-e-margins">
    <div class="ibox-content">


        <div class="form-horizontal m-t" id="signupForm" novalidate="novalidate" action="" method="post">
            <div class="row">
                <div class="col-sm-2">
                    <div class="input-daterange input-group" id="datepicker">
                        <input type="text" class="form-control" name="start_time" placeholder="选择时间">
                    </div>
                </div>

            </div>
            <script>
                $('#datepicker').datepicker({
                    todayBtn: "linked",
                    keyboardNavigation: true,
                    forceParse: true,
                    autoclose: true,
                    format: "yyyymmdd",
                    todayHighlight: true
                });
            </script>
            <div class="form-group">
                <div class="col-sm-3 col-sm-offset-2">
                    <!--<a class="btn btn-primary" href="<? /*=Yii::$app->getUrlManager()->createUrl(['team/update', 'id'=>$model->t_id])*/ ?>">编辑</a>-->
                    <a class="btn btn-default downloadBill">下载</a>
                </div>
            </div>

        </div>
    </div>
</div>
<script>

    $(".downloadBill").on('click', function () {
        var day = $("input[name=start_time]").val() || '';
        $.getJSON('/sign-return/download-bill', {day: day}, function (res) {
            if(false == res.success){
                return layer.alert(res.resultMessage);
            }
        })
    });

</script>
