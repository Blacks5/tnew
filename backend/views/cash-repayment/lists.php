<script src="/js/vue.js"></script>
<script src="/js/vue-resource.js"></script>
<script src="/js/plugins/layer/layer.min.js"></script>

<div class="ibox-content" id="list">
    <div class="row">
        <div class="clients-list">
            <div class="tab-content">
                <div id="tab-1" class="tab-pane active">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                            <tr>
                                <th>手动还款</th>
                                <th class="client-avatar">编号</th>
                                <th><a data-toggle="tab" href="#contact-3" class="client-link">客户姓名</a></th>
                                <th>客户电话</th>
                                <th class="client-status">本期应还金额</th>
                                <th>本金(元)</th>
                                <th>利息(元)</th>
                                <th>个人保障计划(元)</th>
                                <th>贵宾服务包(元)</th>
                                <th>客户管理费(元)</th>
                                <th>财务管理费(元)</th>
                                <th>期数</th>
                                <th>应还时间</th>
                                <th>逾期天数</th>
                                <th>滞纳金</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="value in dataList">
                                <td>
                                    <?php if(Yii::$app->getUser()->can(yii\helpers\Url::toRoute('cash-repayment/to-deduct'))){ ?>
                                        <a class="btn btn-danger btn-xs" v-if="value.status == 'pending' || value.status == 'aborted'" @click="mDeduct(value.id)">手动还款</a>
                                    <?php } ?>
                                </td>
                                <td class="client-avatar">{{value.id}}</td>
                                <td>{{value.name}}</td>
                                <td>{{value.phone}}</td>
                                <td>{{value.total_repay}}</td>
                                <td class="client-status">{{value.component['principal']}}</td>
                                <td class="client-status">{{value.component['interest']}}</td>
                                <td class="client-status">{{value.component['free_pack_fee']}}</td>
                                <td class="client-status">{{value.component['add_server_fee']}}</td>
                                <td class="client-status">{{value.component['finance_manage_fee']}}</td>
                                <td class="client-status">{{value.component['customer_manage_fee']}}</td>
                                <td class="client-status">{{value.period_number+'/'+value.period_total}}</td>
                                <td class="client-status">{{value.due_date}}</td>
                                <td class="client-status">{{value.overdue_days}}</td>
                                <td class="client-status">{{value.overdue_fee}}</td>
                                <td class="client-status">
                                    <a class="btn btn-info btn-xs" @click="open(value.order_id)">详情</a>
                                    <?php if(Yii::$app->getUser()->can(yii\helpers\Url::toRoute('cash-repayment/to-deduct'))){ ?>
                                    <a class="btn btn-danger btn-xs" v-if="value.status == 'pending' || value.status == 'aborted'" @click="deduct(value.order_id)">还款</a>
                                    <?php } ?>
                                    <a class="btn btn-success btn-xs disabled" v-if="value.status == 'paying'">正在还款</a>
                                    <a class="btn btn-warning btn-xs disabled" v-if="value.status == 'paid'">已还</a>
                                </td>
                                <td>
                                </td>
                            </tr>

                            </tbody>
                        </table>
                    </div>
                    <!--分页-->
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    var dataReturn;
    Vue.use(VueResource);
    new Vue ({
        el: '#list',
        data: {
            dataList: '',
            token: window.sessionStorage.getItem('V2_TOKEN'),
            baseUrl:"<?= Yii::$app->params['cashBaseUrl'] ?>"
        },
        created: function () {
            this.toSearch();
        },
        methods: {
            toSearch:function (){
                var url = this.baseUrl + "<?= $orderID ?>/repayments";
                var header = {
                    headers:{'X-TOKEN':this.token},
                };
                this.$http.get(url,header).then(function (data){
                    var json = data.bodyText;
                    var usedData = JSON.parse(json);

                    this.dataList = usedData['data'];
                    console.info(this.dataList)
                },function (response){
                    console.log(response['body']['errors']);
                    layer.msg(response['body']['errors'][0]['message'],{icon:2})
                })
            },
            component:function(data){
                return JSON.parse(data);
            },
            open:function(id){
                layer.open({
                    type: 2,
                    title: false,
                    shadeClose:true,
                    shade: [0.8],
                    area: ['1000px', '800px'],
                    content: "<?= \yii\helpers\Url::toRoute('cash-examine/info') ?>" + "?id="+id
                })
            },
            deduct:function (id){
                var __this = this;
                var url = this.baseUrl + id + "/repayments";
                var data = {
                    period: 1
                };
                var index = layer.msg('确定发起还款么?',{
                    btn:['确定','取消'],
                    btn1:function (){
                        __this.postOrder(url,data);
                    },
                    btn2:function (){
                        layer.close(index);
                    }
                })
            },
            mDeduct:function (id){
                var __this = this;
                var url = this.baseUrl + id + "/manual";
                var data = {
                    period: 1
                };
                var index = layer.msg('确定手动还款么?不经过银行哟~~~',{
                    btn:['确定','取消'],
                    btn1:function (){
                        __this.postOrder(url,data);
                    },
                    btn2:function (){
                        layer.close(index);
                    }
                })
            },
            postOrder: function (url, data){
                var loading = layer.load(0,{shade: false});
                var token = {headers:{'X-TOKEN':this.token}};
                this.$http.post(url, data,token).then(function(data){
                    layer.close(loading);
                    var json = data.bodyText;
                    var usedDatas = JSON.parse(json);
                    if(usedDatas['success']==true){
                        layer.msg('发起还款成功',{icon:1});
                        setTimeout("window.location.reload()", 1000)
                    }else{
                        layer.msg(usedDatas['data'],{icon:2});
                    }
                },function(response){
                    layer.close(loading);
                    var json = response.bodyText;
                    var usedData = JSON.parse(json);
                    layer.msg(usedData['errors'][0]['message'], {icon:2});
                });
            }
        }
    });
</script>