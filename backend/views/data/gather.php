<link href="/css/plugins/datapicker/datepicker3.css" rel="stylesheet">
<script src="/js/plugins/datapicker/bootstrap-datepicker.js"></script>
<div>
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox">
                <div class="ibox-content">
                    <form class="row" method="get" action="">
                        <!-- 暂时不用这2个输入框 by OneStep
                        <div class="col-sm-1">
                            <input type="text" name="DataSearch[username]" placeholder="用户名"
                                   value="" class="input form-control">
                        </div> -->
                        <div class="col-sm-1">
                            <input type="text" name="DataSearch[realname]" placeholder="真实姓名"
                                   value="<?= $sear['realname'] ?>" class="input form-control">
                        </div>

                        <div class="col-sm-2">
                            <div class="input-daterange input-group" id="datepicker">
                                <input type="text" class="form-control" name="DataSearch[start_time]"
                                       value="<?= $sear['start_time']?date('Y-m-d', $sear['start_time']):'' ?>" placeholder="开始时间">
                                <span class="input-group-addon ">到</span>
                                <input type="text" class="form-control" name="DataSearch[end_time]"
                                       value="<?= $sear['end_time']?date('Y-m-d', $sear['end_time']):'' ?>" placeholder="结束时间">
                            </div>
                        </div>

                        <div class="col-sm-1">
                            <select class="input form-control" name="DataSearch[province]" id="user-province"  >
                                <option value="">全部</option>
                                <?php foreach ($area as $key => $p){?>
                                    <option value="<?= $key?>" <?= $sear['province']==$key?'selected':'' ?>><?= $p ?></option>
                                <?php }?>
                            </select>
                        </div>

                        <div class="col-md-1">
                            <select class="input form-control" name="DataSearch[city]" id="user-city">

                            </select>
                        </div>
                        <div class="col-sm-1">
                            <select class="input form-control" name="DataSearch[county]" id="user-county">
                            </select>
                        </div>
                        <div class="col-sm-1">
                            <select class="input form-control" name="DataSearch[realname]" id="user-realname">
                                <option value="">全部</option>
                                <?php foreach ($users as $k => $v){ ?>
                                    <option value="<?= $v['realname'] ?>" <?= $sear['realname']==$v['realname']?'selected':'' ?>><?= $v['realname'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <script>

                            var url = "<?=\yii\helpers\Url::toRoute(['user/get-sub-addr'])?>"; // 获取子地区

                            // 省变化
                            $("#user-province").change(function(){
                                var province_id = $(this).val();
                                $.get(url, {p_id:province_id}, function(data){
                                    var dom = "<option value=''>选择市</option>";
                                    var t = "<?= $sear['city'] ?>";
                                    $.each(data, function (k, v) {
                                        dom += "<option "+((t==k)?'selected':'')+" value="+k+">"+v+"</option>";
                                    })
                                    $("#user-city").html(dom);

                                    $("#user-city").trigger("change");
                                });
                            });

                            // 市变化
                            $("#user-city").change(function(){
                                var city_id = $(this).val();
                                $.get(url, {p_id:city_id}, function(data){
                                    var dom = "<option value=''>选择县</option>";
                                    var t = "<?= $sear['county'] ?>";
                                    $.each(data, function (k, v) {
                                        dom += "<option "+((t==k)?'selected':'')+" value="+k+">"+v+"</option>";
                                    })
                                    $("#user-county").html(dom);
                                });
                            });
                            // 初始化
                            $("#user-province").trigger("change");
                            $("#user-city").trigger("change");



                            $('#datepicker').datepicker({
                                todayBtn: "linked",
                                keyboardNavigation: true,
                                forceParse: true,
                                autoclose:true,
                                format: "yyyy-mm-dd",
                                todayHighlight: true
                            });
                        </script>

                        <div class="col-sm-2">
                            <span class="input-group-btn">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> 搜索</button>
                            </span>
                        </div>
                    </form>
                    <!-- 下级销售数据汇总 by OneStep -->
                    <div class="row" style="margin-top: 20px;">
                        <div class="col-sm-4">
                            <div class="panel panel-success">
                                <header class="panel-heading">总放款</header>
                                <div class="panel-body">
                                    <div class="list-group">
                                        <a class="list-group-item">月供<span class="badge"><?= $data['repayTotal'] ?></span></a>
                                        <a class="list-group-item">本息(包含本金+利息+客户管理费+财务管理费)<span class="badge"><?= $data['total'] ?></span></a>
                                        <a class="list-group-item">客户管理费<span class="badge"><?= $data['customer'] ?></span></a>
                                        <a class="list-group-item">财务管理费<span class="badge"><?= $data['finance'] ?></span></a>
                                        <a class="list-group-item">利息<span class="badge"><?= $data['interest'] ?></span></a>
                                        <a class="list-group-item">本金<span class="badge"><?= $data['principal'] ?></span></a>
                                        <a class="list-group-item">滞纳金<span class="badge"><?= $data['overdue'] ?></span></a>
                                        <a class="list-group-item">个人保障计划<span class="badge"><?= $data['service'] ?></span></a>
                                        <a class="list-group-item">贵宾服务包<span class="badge"><?= $data['pack'] ?></span></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="panel panel-success">
                                <header class="panel-heading">总回收</header>
                                <div class="panel-body">
                                    <div class="list-group">
                                        <a class="list-group-item">月供<span class="badge"><?= $data['repay_repayTotal'] ?></span></a>
                                        <a class="list-group-item">本息(包含本金+利息+客户管理费+财务管理费)<span class="badge"><?= $data['repay_total'] ?></span></a>
                                        <a class="list-group-item">客户管理费<span class="badge"><?= $data['repay_customer'] ?></span></a>
                                        <a class="list-group-item">财务管理费<span class="badge"><?= $data['repay_finance'] ?></span></a>
                                        <a class="list-group-item">利息<span class="badge"><?= $data['repay_interest'] ?></span></a>
                                        <a class="list-group-item">本金<span class="badge"><?= $data['repay_principal'] ?></span></a>
                                        <a class="list-group-item">滞纳金<span class="badge"><?= $data['repay_overdue'] ?></span></a>
                                        <a class="list-group-item">个人保障计划<span class="badge"><?= $data['repay_service'] ?></span></a>
                                        <a class="list-group-item">贵宾服务包<span class="badge"><?= $data['repay_pack'] ?></span></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="panel panel-success">
                                <header class="panel-heading">未回收</header>
                                <div class="panel-body">
                                    <div class="list-group">
                                        <a class="list-group-item">月供<span class="badge"><?= $data['overdue_repayTotal'] ?></span></a>
                                        <a class="list-group-item">本息(包含本金+利息+客户管理费+财务管理费)<span class="badge"><?= $data['overdue_total'] ?></span></a>
                                        <a class="list-group-item">客户管理费<span class="badge"><?= $data['overdue_customer'] ?></span></a>
                                        <a class="list-group-item">财务管理费<span class="badge"><?= $data['overdue_finance'] ?></span></a>
                                        <a class="list-group-item">利息<span class="badge"><?= $data['overdue_interest'] ?></span></a>
                                        <a class="list-group-item">本金<span class="badge"><?= $data['overdue_principal'] ?></span></a>
                                        <a class="list-group-item">滞纳金<span class="badge"><?= $data['overdue_overdue'] ?></span></a>
                                        <a class="list-group-item">个人保障计划<span class="badge"><?= $data['overdue_service'] ?></span></a>
                                        <a class="list-group-item">贵宾服务包<span class="badge"><?= $data['overdue_pack'] ?></span></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="panel panel-success">
                                <header class="panel-heading">其他</header>
                                <div class="panel-body">
                                    <div class="list-group">
                                        <a class="list-group-item">商家服务费<span class="badge"><?= $data['serviceFee'] ?></span></a>
                                        <a class="list-group-item">查询费<span class="badge"><?= $data['inquiryFee'] ?></span></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>