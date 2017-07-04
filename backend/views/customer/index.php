<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use common\models\CustomerSearch;

?>
    <div class="wrapper wrapper-content">
        <div class="row">
            <div class="col-sm-12">
                <div class="ibox float-e-margins">

                    <div class="ibox-content">
                        <?php if($user){ ?>
                            <h1>【<?=$user['realname']. '】的客户'?></h1>
                        <?php } ?>
                        <form class="row" method="get" action="">
                            <?php if($user){ ?>
                                    <input type="hidden" name="CustomerSearch[u_id]" placeholder="uid"
                                           value="<?=$sear['u_id']; ?>" class="input form-control">
                            <?php } ?>

                            <div class="col-sm-1">
                                <input type="text" name="CustomerSearch[c_customer_name]" placeholder="姓名"
                                       value="<?=$sear['c_customer_name']; ?>" class="input form-control">
                            </div>
                            <div class="col-xs-2">
                                <input type="text" name="CustomerSearch[c_customer_cellphone]" value="<?=$sear['c_customer_cellphone']; ?>"
                                       placeholder="手机号" class="input form-control">
                            </div>
                            <div class="col-sm-2">
                                <input type="text" name="CustomerSearch[c_customer_id_card]" value="<?=$sear['c_customer_id_card']; ?>"
                                       placeholder="身份证" class="input form-control">
                            </div>

                            <div class="col-sm-2">
                                <select class="input form-control" name="CustomerSearch[borrow_status]" id="user-provincex">
                                        <option value="">借款状态</option>
                                        <option <?php if($sear['borrow_status'] == CustomerSearch::BORROW_STATUS_SUCCESS){ ?> selected <?php } ?>value="<?=CustomerSearch::BORROW_STATUS_SUCCESS?>">已通过</option>
                                        <option <?php if($sear['borrow_status'] == CustomerSearch::BORROW_STATUS_FAIL){ ?> selected <?php } ?>value="<?=CustomerSearch::BORROW_STATUS_FAIL?>">未通过</option>
                                </select>
                            </div>

                            <div class="col-sm-1">
                                <select class="input form-control" name="CustomerSearch[c_customer_province]" id="user-province">
                                    <option value="">选择省</option>
                                    <?php foreach ($provinces as $k=>$v){ ?>
                                        <option <?php if($sear['c_customer_province'] == $k){ ?> selected <?php } ?>value="<?=$k?>"><?=$v?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <select class="input form-control" name="CustomerSearch[c_customer_city]" id="user-city">
                                </select>
                            </div>
                            <div class="col-sm-1">
                                <select class="input form-control" name="CustomerSearch[c_customer_county]" id="user-county">
                                </select>
                            </div>

                            <div class="col-sm-3">
                                <span class="input-group-btn">
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> 搜索</button>
                                </span>
                            </div>

                        </form>

                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <!--<th>ID</th>-->
                                    <th>姓名</th>
                                    <th>身份证号</th>
                                    <th>手机号码</th>
                                    <th>地区</th>
                                    <th>总借款金额</th>
                                    <th>最近活动时间</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <script>

                                    var url = "<?=Url::toRoute(['user/get-sub-addr'])?>"; // 获取子地区

                                    // 省变化
                                    $("#user-province").change(function(){
                                        var province_id = $(this).val();
                                        $.get(url, {p_id:province_id}, function(data){
                                            var dom = "<option value=''>选择市</option>";
                                            var t = "<?=$sear['c_customer_city']?>";
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
                                            var t = "<?=$sear['c_customer_county']?>";
                                            $.each(data, function (k, v) {
                                                dom += "<option "+((t==k)?'selected':'')+" value="+k+">"+v+"</option>";
                                            })
                                            $("#user-county").html(dom);
                                        });
                                    });
                                    // 初始化
                                    $("#user-province").trigger("change");
                                    $("#user-city").trigger("change");

                                </script>
                                <tbody>
                                <?php foreach ($model as $vo) { ?>
                                    <tr>
                                        <td><?= $vo['c_customer_name']; ?></td>
                                        <td><?= $vo['c_customer_id_card']; ?></td>
                                        <td>
                                            <i class="fa fa-mobile" style="color: #00a2d4;"></i>
                                            <?= $vo['c_customer_cellphone']; ?>
                                        </td>
                                        <td>
                                            <?= \common\components\Helper::getAddrName($vo['c_customer_province']).'-', \common\components\Helper::getAddrName($vo['c_customer_city']). '-'. \common\components\Helper::getAddrName($vo['c_customer_county']) ?>
                                        </td>
                                        <td><?= $vo['c_total_money'] ?></td>
                                        <td><?= $vo['c_updated_at'] ?></td>
                                        <td>
                                            <?php if (Yii::$app->getUser()->can(yii\helpers\Url::toRoute(['customer/view']))) { ?>
                                                <a class="btn btn-primary btn-xs"
                                               href="<?= Url::toRoute(['customer/view', 'c_id' => $vo['c_id']]) ?>"><i
                                                    class="fa fa-edit"></i>客户资料
                                                </a>
                                            <?php } ?>

                                            <?php if (Yii::$app->getUser()->can(yii\helpers\Url::toRoute(['customer/get-all-orders-by-customer']))) { ?>
                                                <a class="btn btn-primary btn-xs"
                                                   href="<?= Url::toRoute(['customer/get-all-orders-by-customer', 'OrdersSearch[customer_id]' => $vo['c_id']]) ?>"><i
                                                            class="fa fa-edit"></i>所有订单
                                                </a>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                            <!--分页-->
                            <div class="f-r">
                                <?= LinkPager::widget([
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
