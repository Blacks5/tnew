<link href="/css/plugins/datapicker/datepicker3.css" rel="stylesheet">
<script src="/js/plugins/datapicker/bootstrap-datepicker.js"></script>

<div class="ibox-content">
    <div class="row">
        <div class="col-sm-12">
            <form method="post" class="row" action="<?= \yii\helpers\Url::toRoute('data/download-customer-csv') ?>">
                <div class="col-sm-3">
                    <div class="input-daterange input-group" id="datepicker">
                        <input type="text" class="form-control" name="start_time"
                               value="" placeholder="开始时间" required>
                        <span class="input-group-addon ">到</span>
                        <input type="text" class="form-control" name="end_time"
                               value="" placeholder="结束时间" required>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group">
                        <button type="submit" class="btn btn-info"><i class="fa fa-search"></i>查询</button>
                    </div>
                </div>
            </form>
            <script>
                $('#datepicker').datepicker({
                    todayBtn: "linked",
                    keyboardNavigation: true,
                    forceParse: true,
                    autoclose:true,
                    format: "yyyy-mm-dd",
                    todayHighlight: true
                });
            </script>
        </div>
    </div>
    <div class="row">
        <div class="clients-list">
            <div class="tab-content">

            </div>

        </div>
    </div>
</div>
