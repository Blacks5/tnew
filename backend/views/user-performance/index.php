<link href="/css/plugins/datapicker/datepicker3.css" rel="stylesheet">
<script src="/js/plugins/datapicker/bootstrap-datepicker.js"></script>
<div>
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox">
                <div class="ibox-content">
                    <form class="row" method="get" action="">
                        <div class="col-sm-1">
                            <input type="text" name="YejiSearch[username]" placeholder="用户名"
                                   value="<?php echo $sear['username']; ?>" class="input form-control">
                        </div>
                        <div class="col-sm-1">
                            <input type="text" name="YejiSearch[realname]" placeholder="真实姓名"
                                   value="<?php echo $sear['realname']; ?>" class="input form-control">
                        </div>

                        <div class="col-sm-2">
                            <div class="input-daterange input-group" id="datepicker">
                                <input type="text" class="form-control" name="YejiSearch[start_time]"
                                       value="<?= $sear['start_time']? date('Y-m-d', $sear['start_time']): ''; ?>" placeholder="开始时间">
                                <span class="input-group-addon ">到</span>
                                <input type="text" class="form-control" name="YejiSearch[end_time]"
                                       value="<?= $sear['end_time'] ? date('Y-m-d', $sear['end_time']):''; ?>" placeholder="结束时间">
                            </div>
                        </div>

                        <div class="col-sm-1">
                            <select class="input form-control" name="YejiSearch[province]" id="user-province">
                                <option value="">选择省</option>
                                <?php foreach ($provinces as $k=>$v){ ?>
                                    <option <?php if($sear['province'] == $k){ ?> selected <?php } ?>value="<?=$k?>"><?=$v?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-1">
                            <select class="input form-control" name="YejiSearch[city]" id="user-city">
                            </select>
                        </div>
                        <div class="col-sm-1">
                            <select class="input form-control" name="YejiSearch[county]" id="user-county">
                            </select>
                        </div>
                        <script>

                            var url = "<?=\yii\helpers\Url::toRoute(['user/get-sub-addr'])?>"; // 获取子地区

                            // 省变化
                            $("#user-province").change(function(){
                                var province_id = $(this).val();
                                $.get(url, {p_id:province_id}, function(data){
                                    var dom = "<option value=''>选择市</option>";
                                    var t = "<?=$sear['city']?>";
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
                                    var t = "<?=$sear['county']?>";
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

                    <div class="clients-list">
                        <div class="tab-content">
                            <div id="tab-1" class="tab-pane active">
                                <div class="slimScrollDiv" style="position: relative; width: auto; height: 100%;">
                                    <div class="full-height-scroll" style="width: auto; height: 100%;">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover">
                                                <thead>
                                                <tr>
                                                    <th class="client-avatar">id</th>
                                                    <th><a data-toggle="tab" href="#contact-3"
                                                           class="client-link">用户名</a></th>
                                                    <th><a data-toggle="tab" href="#contact-3"
                                                           class="client-link">真实姓名</a></th>
                                                    <th>个人保障计划捆绑率</th>
                                                    <th>贵宾服务包捆绑率</th>
                                                    <th class="client-status">总提单</th>
                                                    <th>成功提单</th>
                                                    <th>总借出金额</th>
                                                    <th>操作</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php foreach ($data['data'] as $_k => $_v) { ?>
                                                    <tr>
                                                        <td class="client-avatar"><?= $_v['id'] ?></td>
                                                        <td><a data-toggle="tab" href="#contact-3"
                                                               class="client-link"><?= $_v['username'] ?></a></td>
                                                        <td><a data-toggle="tab" href="#contact-3"
                                                               class="client-link"><?= $_v['realname'] ?></a></td>
                                                        <td><?= $_v['a_services'] ?></td>
                                                        <td><?= $_v['f_packcount'] ?></td>
                                                        <td class="client-status"><?= $_v['t_ordercount'] ?></td>
                                                        <td class="client-status"><?= $_v['s_ordercount'] ?></td>
                                                        <td class="client-status"><?= $_v['s_amount'] ?></td>
                                                        <td class="client-status">
                                                            <?php if (Yii::$app->getUser()->can(yii\helpers\Url::toRoute(['customer/index']))) { ?>
                                                                <a class="btn btn-primary btn-xs"
                                                                   href="<?= \yii\helpers\Url::toRoute(['customer/index', 'CustomerSearch[u_id]'=>$_v['id']]) ?>"><i
                                                                            class="fa fa-edit"></i>所有客户
                                                                </a>
                                                            <?php } ?>
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