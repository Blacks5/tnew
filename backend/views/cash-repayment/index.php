<script src="/js/vue.js"></script>
<script src="/js/vue-resource.js"></script>
<script src="/js/plugins/layDate/layDate.js"></script>
<script src="/js/plugins/layer/layer.min.js"></script>

<div class="ibox-content" id="list">
    <div class="row">
        <div class="col-sm-12">
            <div class="col-sm-1">
                <input type="text" class="form-control" name="name" :value="params['name']" placeholder="客户姓名">
            </div>
            <div class="col-sm-2">
                <input type="text" class="form-control" name="phone" :value="params['phone']" placeholder="客户电话">
            </div>
            <div class="col-sm-3">
                <div class="input-daterange input-group" id="datepicker">
                    <input type="text" class="form-control" name="sTime" id="sTime"
                           :value="params['sTime']" placeholder="开始时间">
                    <span class="input-group-addon ">到</span>
                    <input type="text" class="form-control" name="eTime" id="eTime"
                           :value="params['eTime']" placeholder="结束时间">
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-group">
                    <button type="input" class="btn btn-info" @click="toSearch"><i class="fa fa-search" ></i>查询</button>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="clients-list">
            <div class="tab-content">
                <div id="tab-1" class="tab-pane active">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                            <tr>
                                <th class="client-avatar">订单编号</th>
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
                                <td class="client-avatar">{{value.order_number}}</td>
                                <td>{{value.name}}</td>
                                <td>{{value.phone}}</td>
                                <td>{{value.total_repay}}</td>
                                <td class="client-status">{{component(value.component)['principal']}}</td>
                                <td class="client-status">{{component(value.component)['interest']}}</td>
                                <td class="client-status">{{component(value.component)['free_pack_fee']}}</td>
                                <td class="client-status">{{component(value.component)['add_server_fee']}}</td>
                                <td class="client-status">{{component(value.component)['finance_manage_fee']}}</td>
                                <td class="client-status">{{component(value.component)['customer_manage_fee']}}</td>
                                <td class="client-status">{{value.period_number+'/'+value.period_total}}</td>
                                <td class="client-status">{{value.due_date}}</td>
                                <td class="client-status">{{value.overdue_days}}</td>
                                <td class="client-status">{{value.overdue_fee}}</td>
                                <td class="client-status">
                                    <a class="btn btn-info btn-xs" @click="open(value.order_id)">详情</a>
                                    <a class="btn btn-info btn-xs" @click="repayLists(value.order_id)">还款计划</a>
                                    <a class="btn btn-danger btn-xs" v-if="value.status == 'pending' || value.status == 'aborted'" @click="deduct(value.order_id)">还款</a>
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
                    <ul class="pagination" v-if="pageCount != 1">
                        <li>
                            <a  aria-label="Previous" @click="pageLeft">
                                <span aria-hidden="true">&laquo;</span> 上一页
                            </a>
                        </li>
                        <li><a :value="pageIndex" >当前第 {{pageIndex}} 页</a></li>
                        <li><a>共 {{pageCount}} 页</a></li>
                        <li>
                            <a  aria-label="Next" @click="pageRight">
                                下一页 <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>

                    </ul>
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
            params: '',
            pageCount: 1,
            pageIndex:'',
            token: window.sessionStorage.getItem('V2_TOKEN'),
            baseUrl:"<?= Yii::$app->params['cashBaseUrl'] ?>"
        },
        created: function () {
            this.toSearch();
        },
        methods: {
            toSearch:function (){
                var url = this.baseUrl + "repayments/<?= $repayment ?>";
                var header = {
                    headers:{'X-TOKEN':this.token},
                    params: {
                        param:{
                            name:$('input[name=name]').val(),
                            phone:$('input[name=phone]').val(),
                            sTime:$('input[name=sTime]').val(),
                            eTime:$('input[name=eTime]').val()
                        },
                        page:this.pageIndex
                    }

                };
                this.$http.get(url,header).then(function (data){
                    var json = data.bodyText;
                    var usedData = JSON.parse(json);

                    this.dataList = usedData['data']['list']['data'];
                    this.params = usedData['data']['param'];
                    this.pageCount = usedData['data']['list']['last_page'];
                    this.pageIndex = usedData['data']['list']['current_page'];
                    console.info(this.dataList[0]['component'][0]['interest']);
                },function (response){
                    console.log(response['body']['errors']);
                    layer.msg(response['body']['errors'][0]['message'],{icon:2})
                })
            },
            component:function(data){
                return JSON.parse(data);
            },
            pageLeft:function(){
                if(this.pageIndex == 1 || this.pageIndex == 'null'){
                    layer.msg('已经是第一页了!',{icon:2});
                    return false;
                }
                this.pageIndex--;
                this.toSearch();
            },
            pageRight:function(){
                if(this.pageCount == this.pageIndex || this.pageIndex == 'null'){
                    layer.msg('已经是最后一页了!',{icon:2});
                    return false;
                }
                this.pageIndex ++;
                this.toSearch();
            },
            open:function(id){
                parent.layer.open({
                    type: 2,
                    title: false,
                    shadeClose:true,
                    shade: [0.8],
                    area: ['1200px', '800px'],
                    content: "<?= \yii\helpers\Url::toRoute('cash-examine/info') ?>" + "?id="+id
                })
            },
            repayLists:function(orderID){
                parent.layer.open({
                    type:2,
                    title:false,
                    shadeClose:true,
                    shade:[0.8],
                    area: ['1300px', '800px'],
                    content: "<?= \yii\helpers\Url::toRoute('cash-repayment/lists') ?>" + "?orderID="+orderID
                });
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
            postOrder: function (url, data){
                var loading = layer.load(0,{shade: false});
                var token = {headers:{'X-TOKEN':this.token}};
                this.$http.post(url, data,token).then(function(data){
                    layer.close(loading);
                    var json = data.bodyText;
                    var usedDatas = JSON.parse(json);
                    if(usedDatas['success']==true){
                        layer.msg(usedDatas['data']);
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
    laydate.render({
        elem:'#sTime'
    });
    laydate.render({
        elem:'#eTime'
    });
</script>