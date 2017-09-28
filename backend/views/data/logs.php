<link href="/css/plugins/datapicker/datepicker3.css" rel="stylesheet">
<script src="/js/plugins/datapicker/bootstrap-datepicker.js"></script>

<div class="ibox-content">
    <div class="row">
        <div class="col-sm-12">
            <form method="get" class="row" action="">
                <div class="col-sm-1">
                    <input type="text" class="form-control" name="DataSearch[realname]" value="<?= $sear['realname'] ?>" placeholder="操作人姓名">
                </div>
                <div class="col-sm-2">
                    <select class="form-control" name="DataSearch[typeTag]">
                        <option value="">选择日志类型</option>
                        <?php foreach ($type as $k => $v){ ?>
                            <option value="<?= $v[0] ?>" <?= $v[0]==$sear['typeTag']?' selected':'' ?> ><?= $v[1] ?></option>
                        <?php }?>
                    </select>
                </div>
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
        <div class="list-group">
            <?php foreach ($data as $k => $v){ ?>
            <a class="list-group-item">
                <h4 class="list-group-item-heading"><?= $v['memo'] ?></h4>
                <p>操作员:<?=$v['realname']?> 操作时间:<?= $v['created_at']?> &nbsp;&nbsp;操作IP:<?= $v['ip']?> &nbsp;&nbsp; 关联订单ID:<?= $v['order_id']?></p>
                <pre style="display: none;"><?= $v['data'] ?></pre>
            </a>
            <?php }?>
        </div>
        <div class="f-r">
            <?= \yii\widgets\LinkPager::widget([
                'pagination' => $pages,
                'firstPageLabel' => '首页',
                'nextPageLabel' => '下一页',
                'prevPageLabel' => '上一页',
                'lastPageLabel' => '末页',
            ]) ?>
        </div>
    </div>
</div>
<script>
    $('.list-group-item').mousemove(function(){
        $(this).find('pre').show();
    })
    $('.list-group-item').mouseout(function(){
        $(this).find('pre').hide();
    })
</script>