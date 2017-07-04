<?php

use yii\helpers\Html;
//use yii\grid\GridView;
use kartik\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->params['breadcrumbs'][] = ['label' => '商户列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="animated fadeInRight">
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox">
                <div class="ibox-content">
                    <form class="row" method="get" action="">
                        <div class="col-sm-2">
                            <input type="text" name="AllOrdersWithStoreSearch[username]" placeholder="客户姓名"
                                   value="<?php echo $sear['username']; ?>" class="input form-control">
                        </div>
                        <div class="col-sm-2">
                            <input type="text" name="AllOrdersWithStoreSearch[phone]"
                                   value="<?php echo $sear['phone']; ?>" placeholder="客户电话" class="input form-control">
                        </div>
                        <div class="col-sm-4">
                                <div class="input-daterange input-group" id="datepicker">
                                    <input type="text" class="form-control" name="AllOrdersWithStoreSearch[s_time]"
                                           placeholder="下单开始时间"
                                           value="<?php echo $sear['s_time'] ? (date('Y-m-d', $sear['s_time'])) : ''; ?>">
                                    <span class="input-group-addon ">到</span>
                                    <input type="text" class="form-control" name="AllOrdersWithStoreSearch[e_time]"
                                           placeholder="下单结束时间"
                                           value="<?php echo $sear['e_time'] ? (date('Y-m-d', $sear['e_time'])) : ''; ?>">
                                </div>
                        </div>

                        <div class="col-sm-2 hidden">
                            <input type="text" name="id" value="<?php echo $_GET['id'];?>" placeholder="商铺ID" class="input form-control">
                        </div>
                        <div  class="col-sm-3">
                            <span class="input-group-btn">
                                <button type="submit" class="btn btn-primary"> <i class="fa fa-search"></i> 搜索</button>
                            </span>
                        </div>
                    </form>

                    <div class="clients-list">
                        <div class="tab-content">
                            <div id="tab-1" class="tab-pane active">
                                <div class="slimScrollDiv" style="position: relative; width: auto; height: 100%;"><div class="full-height-scroll" style="width: auto; height: 100%;">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover">
                                                <thead>
                                                <tr>
                                                    <th class="client-avatar">编号</th>
                                                    <th>订单使用的产品id</th>
                                                    <th>客户姓名</th>
                                                    <th>涉及产品数</th>
                                                    <th>总价格</th>
                                                    <th>总定金</th>
                                                    <th>利息</th>
                                                    <th>增值服务</th>
                                                    <th>随心包</th>
                                                    <th>审核人</th>
                                                    <th>审核时间</th>
                                                    <th>订单状态</th>
                                                    <th>创建时间</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php foreach ($model as $_k=>$_v){ ?>
                                                    <tr>
                                                        <td class="client-avatar"><?= $_v['o_id']?></td>
                                                        <td><?= $_v['o_product_id']?></td>
                                                        <td><?= $_v['c_customer_name'] ?></td>
                                                        <td><?= $_v['o_goods_num']?></td>
                                                        <td><?= $_v['o_total_price']?></td>
                                                        <td><?= $_v['o_total_deposit']?></td>
                                                        <td><?= $_v['o_total_interest']?></td>
                                                        <td><?= $_v['o_is_add_service_fee']?"是":"否"; ?></td>
                                                        <td><?= $_v['o_is_free_pack_fee']?"是":"否"; ?></td>
                                                        <td><?= $_v['o_operator_realname']?></td>
                                                        <td class="client-status"><?= date("Y-m-d H:i:s", $_v['o_operator_date'])?></td>
                                                        <td><button class="btn btn-xs btn-danger"><?= \common\models\Orders::getAllStatus()[$_v['o_status']]?></button></td>
                                                        <td class="client-status"><?= date("Y-m-d H:i:s", $_v['o_created_at'])?></td>
                                                    </tr>
                                                <?php }?>
                                                <script>
                                                    function del(name, id) {
                                                        layer.confirm('是否删除商户'+name, {icon: 3, title:'删除员商户'}, function(index){
                                                            location.href = "<?= Yii::$app->getUrlManager()->createUrl(['stores/delete']); ?>"+"?id="+id;
                                                            layer.close(index);
                                                        });

                                                    }
                                                </script>

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
        <?= Html::cssFile('@web/css/plugins/datapicker/datepicker3.css') ?>
        <?= Html::jsFile('@web/js/plugins/datapicker/bootstrap-datepicker.js') ?>

        <script>

            $('#datepicker').datepicker({
                todayBtn: "linked",
                keyboardNavigation: false,
                forceParse: false,
                calendarWeeks: true,
                autoclose:true,
                format: "yyyy-mm-dd",
                todayHighlight: true
            });
        </script>