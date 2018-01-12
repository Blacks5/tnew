<link href="/css/plugins/datapicker/datepicker3.css" rel="stylesheet">
<script src="/js/plugins/datapicker/bootstrap-datepicker.js"></script>
<script src="/js/plugins/layer/layer.min.js"></script>
<div>
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox">
                <div class="ibox-content">
                    <form class="row" method="get" action="">
                        <!-- 暂时不用这2个输入框 by OneStep
                        <div class="col-sm-1">
                            <input type="text" name="YejiSearch[username]" placeholder="用户名"
                                   value="" class="input form-control">
                        </div>
                        <div class="col-sm-1">
                            <input type="text" name="YejiSearch[realname]" placeholder="真实姓名"
                                   value="" class="input form-control">
                        </div> -->

                        <div class="col-sm-2">
                            <div class="input-daterange input-group" id="datepicker">
                                <input type="text" class="form-control" name="start_time"
                                       value="<?= $sear['start_time'] ?? ''; ?>" placeholder="开始时间">
                                <span class="input-group-addon ">到</span>
                                <input type="text" class="form-control" name="end_time"
                                       value="<?= $sear['end_time'] ?? ''; ?>" placeholder="结束时间">
                            </div>
                        </div>

                        <div class="col-sm-1">
                            <select class="input form-control" name="province" id="user-province" <?= $user->level>1?'disabled':'';?> >
                                <?php if($user->level==1){ ?>
                                    <option value="" selected>全部</option>
                                <?php }?>
                                <?php foreach ($area['province'] ?? [] as $k=>$v){ ?>
                                    <option <?php if($sear['province']??'' == $k){ ?> selected <?php } ?>value="<?=$k?>"><?=$v?></option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="col-md-1">
                            <select class="input form-control" name="city" id="user-city" <?php echo $user->level>2?'disabled':'';?>>
                                <?php if($user->level>2){?>
                                    <option value="<?= $user->city?>" selected><?=$area['city']?></option>
                                <?php }?>
                            </select>
                        </div>
                        <div class="col-sm-1">
                            <select class="input form-control" name="county" id="user-county" <?php echo $user->level>3?'disabled':'';?>>
                                <?php if($user->level>3){?>
                                    <option value="<?= $user->county?>"><?=$area['county']?></option>
                                <?php }?>
                            </select>
                        </div>
                        <div class="col-sm-1">
                            <select class="input form-control" name="name" id="user-realname">
                                <option value="">全部</option>
                                <?php foreach ($data['list']['items'] ?? [] as $k => $v){ ?>
                                    <option value="<?= $v['name'] ?>"><?= $v['name'] ?></option>
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
                                    var t = "<?=$user->city?>";
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
                                    var t = "<?=$user->county?>";
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
                        <div class="list-group col-sm-3">
                            <a class="list-group-item">总放款<span class="badge"><?= round($list['allAcceptedAmount'], 0) ?></span> </a>
                            <a class="list-group-item">总提单<span class="badge"><?= $list['allOrderCount'] ?></span> </a>
                            <a class="list-group-item">成功提单<span class="badge"><?= $list['allAcceptedCount'] ?></span> </a>
                        </div>
                        <div class="list-group col-sm-3">
                            <a class="list-group-item">逾期率<span class="badge"><?= $list['allOverdueRadio'] ?>%</span> </a>
                            <a class="list-group-item">逾期单数<span class="badge"><?= $list['allOverdueCount'] ?></span> </a>
                            <a class="list-group-item">逾期金额<span class="badge"><?= round($list['allOverdueMoney'], 0) ?></span> </a>
                        </div>
                        <div class="list-group col-sm-3">
                            <a class="list-group-item">通过率<span class="badge"><?= $list['allAcceptedRadio'] ?>%</span> </a>
                            <a class="list-group-item">逾期金额比<span class="badge"><?= $list['allOverdueMoneyRadio'] ?>%</span> </a>
                            <a class="list-group-item">不良率<span class="badge"><?= $list['allBadRadio'] ?>%</span> </a>
                        </div>
                        <div class="list-group col-sm-3">
                            <a class="list-group-item">个人保障计划捆绑率<span class="badge"><?= $list['allServiceRadio'] ?>%</span> </a>
                            <a class="list-group-item">贵宾服务包捆绑率<span class="badge"><?= $list['allPackRadio'] ?>%</span> </a>
                        </div>

                    </div>

                    <div class="clients-list">
                        <div class="tab-content">
                            <div id="tab-1" class="tab-pane active">
                                <div class="slimScrollDiv" style="position: relative; width: auto; height: 100%;">
                                    <div class="full-height-scroll" style="width: auto; height: 100%;">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover">
                                                <thead>
                                                <tr>
                                                    <th><a data-toggle="tab" href="#contact-3"
                                                           class="client-link">真实姓名</a></th>
                                                    <th>邀请商户业绩</th>
                                                    <th>个人保障计划捆绑率</th>
                                                    <th>贵宾服务包捆绑率</th>
                                                    <th class="client-status">总提单</th>
                                                    <th>成功提单</th>
                                                    <th>总借出金额</th>
                                                    <th>逾期单数</th>
                                                    <th>逾期金额</th>
                                                    <th>逾期率</th>
                                                    <th>通过率</th>
                                                    <th>逾期金额比</th>
                                                    <th>不良率</th>
                                                    <th>操作</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php foreach ($data['list']['items'] ?? [] as $_k => $_v) { ?>
                                                    <tr>
                                                        <td><a data-toggle="tab" href="#contact-3"
                                                               class="client-link"><?= $_v['name'] ?></a></td>
                                                        <td><?= $_v['inviterAmount'] ?></td>
                                                        <td><?= $_v['packRadio'] ?>%</td>
                                                        <td class="client-status"><?= $_v['serviceRadio'] ?>%</td>
                                                        <td class="client-status"><?= $_v['orderCount'] ?></td>
                                                        <td class="client-status"><?= $_v['acceptedCount'] ?></td>
                                                        <td class="client-status"><?= $_v['acceptedAmount'] ?></td>
                                                        <td class="client-status"><?= $_v['overdueCount'] ?></td>
                                                        <td class="client-status"><?= $_v['overdueMoney'] ?></td>
                                                        <td class="client-status"><?= $_v['overdueRadio'] ?>%</td>
                                                        <td class="client-status"><?= $_v['acceptedRadio'] ?>%</td>
                                                        <td class="client-status"><?= $_v['overdueMoneyRadio'] ?>%</td>
                                                        <td class="client-status"><?= $_v['badRadio'] ?>%</td>
                                                        <td>
                                                            <a class="btn btn-success btn-xs" onclick="getOrder(<?= $_v['id'] ?>)">查看订单</a>
                                                            <a class="btn btn-danger btn-xs" onclick="getLeader(<?= $_v['id'] ?>)">查看下级</a>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <!--分页-->
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
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function getOrder(id) {
        parent.layer.open({
            type: 2,
            title: false,
            shadeClose:true,
            shade: [0.8],
            area: ['1200px', '800px'],
            content: "<?= \yii\helpers\Url::toRoute('cash-examine/pass') ?>" + "?id="+id
        })
    }

    function getLeader(id) {
        parent.layer.open({
            type: 2,
            title: false,
            shadeClose:true,
            shade: [0.8],
            area: ['1200px', '800px'],
            content: "<?= \yii\helpers\Url::toRoute('user/agent') ?>" + "?id="+id
        })
    }
</script>