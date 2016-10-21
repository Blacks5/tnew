<?php

use yii\helpers\Html;
//use yii\grid\GridView;
use kartik\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

//app\assets\LayerAsset::register($this);
//app\assets\MainAsset::register($this);
$this->params['breadcrumbs'][] = ['label' => '商户列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<link rel="stylesheet" href="/statics/css/animate.min.css">
<link rel="stylesheet" href="/statics/css/style.min.css">


<div class="animated fadeInRight">
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox">
                <div class="ibox-content">
                    <form class="row" method="get" action="">
                        <div class="col-sm-2">
                            <input type="text" name="Stores[s_owner_name]" placeholder="负责人姓名" value="<?php //echo $sear['s_owner_name'];  ?>" class="input form-control">
                        </div>
                        <div class="col-sm-2">
                            <input type="text" name="Stores[s_owner_phone]" value="<?php //echo $sear['s_owner_phone'];  ?>" placeholder="负责人电话" class="input form-control">
                        </div>
                        <div class="col-sm-4">
                                <div class="input-daterange input-group" id="datepicker">
                                    <input type="text" class="form-control" name="start" placeholder="开始时间">
                                    <span class="input-group-addon ">到</span>
                                    <input type="text" class="form-control" name="end" placeholder="结束时间">
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
                                                    <th class="client-avatar">id</th>
                                                    <th>订单使用的产品id</th>
                                                    <th class="client-status">客户id</th>
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
                                                        <td><?= $_v['o_customer_id']?></td>
                                                        <td><?= $_v['o_goods_num']?></td>
                                                        <td><?= $_v['o_total_price']?></td>
                                                        <td><?= $_v['o_total_deposit']?></td>
                                                        <td><?= $_v['o_total_interest']?></td>
                                                        <td><?= $_v['o_is_add_service_fee']?"是":"否"; ?></td>
                                                        <td><?= $_v['o_is_free_pack_fee']?"是":"否"; ?></td>
                                                        <td><?= $_v['o_operator_realname']?></td>
                                                        <td class="client-status"><?= date("Y-m-d H:i:s", $_v['o_operator_date'])?></td>
                                                        <td><?= $_v['o_status']?></td>
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
                                        <div id="page11"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <script src="/statics/plugins/layer/layer.js"></script>
        <link rel="stylesheet" href="/statics/plugins/laypage/skin/laypage.css">
        <script src="/statics/plugins/laypage/laypage.js"></script>

        <link href="/statics/css/plugins/datapicker/datepicker3.css" rel="stylesheet">
        <script src="/statics/js/plugins/datapicker/bootstrap-datepicker.js"></script>


        <script>
            function initPage(){
                laypage({
                    cont: 'page11',
                    pages: <?= $totalpage;?>, //可以叫服务端把总页数放在某一个隐藏域，再获取。假设我们获取到的是18
                    curr: function(){ //通过url获取当前页，也可以同上（pages）方式获取
                        var page = location.search.match(/page=(\d+)/);
                        return page ? page[1] : 1;
                    }(),
                    skip: true,
                    jump: function(e, first){ //触发分页后的回调
                        if(!first){ //一定要加此判断，否则初始时会无限刷新
                            var search = location.search;
                            var n = location.href.indexOf('page=');

                            if(n < 0){
                                var url = location.href+(search ? "&page=" : "?page=");
                            }else{
                                var url = location.href.substr(0, n)+(search ? "page=" : "?page=");
                            }

                            location.href = url+e.curr;
                        }
                    }
                });
            };
            initPage();

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