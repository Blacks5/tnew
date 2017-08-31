<link href="/css/plugins/datapicker/datepicker3.css" rel="stylesheet">
<script src="/js/plugins/datapicker/bootstrap-datepicker.js"></script>

<div class="ibox-content">
    <div class="row">
        <div class="col-sm-12">
            <form method="get" class="row" action="">
                <div class="col-sm-3">
                    <div class="input-daterange input-group" id="datepicker">
                        <input type="text" class="form-control" name="DataSearch[start_time]"
                               value="<?= $sear['start_time']? date('Y-m-d', $sear['start_time']): ''; ?>" placeholder="开始时间">
                        <span class="input-group-addon ">到</span>
                        <input type="text" class="form-control" name="DataSearch[end_time]"
                               value="<?= $sear['end_time'] ? date('Y-m-d', $sear['end_time']):''; ?>" placeholder="结束时间">
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
        <div class="col-sm-4">
            <div class="panel panel-default">
                <header class="panel-heading">总计</header>
                <div class="panel-body">
                    <div class="list-group">
                        <a class="list-group-item">总单数<span class="badge"><?= $all['orderCount'] ?></span></a>
                        <a class="list-group-item">总金额<span class="badge"><?= $all['orderMoney'] ?></span></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="panel panel-default">
                <header class="panel-heading">逾期</header>
                <div class="panel-body">
                    <div class="list-group">
                        <a class="list-group-item">单数<span class="badge"><?=$all['overdueNum'] ?></span></a>
                        <a class="list-group-item">金额<span class="badge"><?=$all['overdueMoney'] ?></span></a>
                        <a class="list-group-item">逾期率<span class="badge"><?=$all['overdueRatio'] ?></span></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="panel panel-default">
                <header class="panel-heading">风控</header>
                <div class="panel-body">
                    <div class="list-group">
                        <a class="list-group-item">单数<span class="badge"></span></a>
                        <a class="list-group-item">金额<span class="badge"></span></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>