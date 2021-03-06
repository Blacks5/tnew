<script src="/js/vue.js"></script>
<script src="/js/vue-resource.js"></script>
<script src="/js/plugins/laydate/laydate.js"></script>
<script src="/js/plugins/layer/layer.min.js"></script>
<link rel="stylesheet" href="https://unpkg.com/element-ui@2.0.10/lib/theme-chalk/index.css">
<script src="https://unpkg.com/element-ui@2.0.10/lib/index.js"></script>

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
                        <button type="input" class="btn btn-info"  @click="toSearchBtn"><i class="fa fa-search" ></i>查询</button>
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
                                    <th>借款类型</th>
                                    <th class="client-status">申请金额</th>
                                    <th class="client-status">审批金额</th>
                                    <th>申请期数</th>
                                    <th>还款周期</th>
                                    <th><?= $examine == 'pass'?'通过':'申请' ?>时间</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="value in dataList">
                                        <td class="client-avatar">{{value.order_number}}</td>
                                        <td>{{value.name}}</td>
                                        <td>{{value.phone}}</td>
                                        <td>{{ value.product_type ==1?'常规':'促销' }}</td>
                                        <td>{{value.expected_amount}}</td>
                                        <td>{{value.accepted_amount}}</td>
                                        <td class="client-status">{{value.period_total}}</td>
                                        <td class="client-status">{{value.repay_cycle == 'week'?'周':'月'}}</td>
                                        <td class="client-status" v-if="getPassTime(value.extended_data)">{{value.extended_data.pass_time}}</td>
                                        <td class="client-status" v-else>{{value.created_at}}</td>
                                        <td class="client-status">
                                            <a class="btn btn-info btn-xs" @click="open(value.id)">详情</a>
                                            <?php if(Yii::$app->getUser()->can(yii\helpers\Url::toRoute('cash-repayment/to-loan'))){ ?>
                                            <a class="btn btn-danger btn-xs" v-if="value.status == 90 || value.status == 115" @click="loan(value.id)">放款</a>
                                            <?php } ?>
                                            <a class="btn btn-success btn-xs disabled" v-if="value.status == 100">正在放款</a>
                                            <a class="btn btn-warning btn-xs disabled" v-if="value.status == 120">已代发</a>
                                            <a class="btn btn-info btn-xs" v-if="value.status == 120" @click="repayments(value.id)">还款计划</a>
                                        </td>
                                        <td>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                        <!--分页-->
                        <el-pagination
                                background
                                layout="prev, pager, next"
                                :total="pageCount" :page-size="15" @current-change="pageChange">
                        </el-pagination>
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
            saleID: "<?= $id ?? '' ?>",
            customer_id: "<?= $customer_id ?? '' ?>",
            token: window.sessionStorage.getItem('V2_TOKEN'),
            baseUrl:"<?= Yii::$app->params['cashBaseUrl'] ?>",
            region: "<?= $user['area'] ?? '' ?>",
            regionName: "<?= $user['area_value'] ?>",
            level: "<?= $user['level'] ?>",
            dep: "<?= $user['d_department_id'] ?>",
            createdAt: "<?= $user['created_at'] ?>"
        },
        created: function () {
            this.toSearch();
        },
        methods: {
            toSearch:function (){
                var url = this.baseUrl + "examine/<?= $examine ?>";
                var header = {
                    headers:{'X-TOKEN':this.token},
                    params: {
                        param:{
                            saleID: this.saleID,
                            customerID: this.customer_id,
                            name:$('input[name=name]').val(),
                            phone:$('input[name=phone]').val(),
                            sTime:$('input[name=sTime]').val(),
                            eTime:$('input[name=eTime]').val()
                        },
                        page: this.pageIndex
                    }

                };

                if (this.level > 1 ) {
                    header.params.param['region'] = this.region
                    header.params.param['regionName'] = this.regionName
                    if (this.dep == 26) {
                        header.params.param['createdAt'] = this.createdAt
                    }
                }

                this.$http.get(url,header).then(function (data){
                    var json = data.bodyText;
                    var usedData = JSON.parse(json);

                    this.dataList = usedData['data']['list']['data'];
                    this.params = usedData['data']['param'];
                    this.pageCount = usedData['data']['list']['total'];
                    this.pageIndex = usedData['data']['list']['current_page']
                },function (response){
                    console.log(response['body']['errors']);
                    layer.msg(response['body']['errors'][0]['message'],{icon:2})
                })
            },
            toSearchBtn:function () {
                this.pageIndex = 1;
                this.toSearch()
            },
            pageChange:function (val) {
                this.pageIndex = val
                this.toSearch()
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
            repayments:function(id){
                parent.layer.open({
                    type: 2,
                    title: false,
                    shadeClose:true,
                    shade:[0.8],
                    area:['1400px', '800px'],
                    content: "<?= \yii\helpers\Url::toRoute('cash-repayment/lists') ?>?orderID=" +id
                })
            },
            loan:function (id){
                var __this = this;
                var url = this.baseUrl + id + "/loans";
                var data = {param:this.param,status:'pass'};
                var index = layer.msg('确定发起放款么?',{
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
                        parent.layer.msg('放款成功', {icon:1});

                        this.dataList = usedDatas['data']['data']['data'];
                    }else{
                        layer.msg(usedDatas['data']['message'],{icon:2});
                    }
                },function(response){
                    layer.close(loading);
                    var json = response.bodyText;
                    var usedData = JSON.parse(json);
                    layer.msg(usedData['errors'][0]['message'], {icon:2});
                });
            },
            getPassTime: function (data) {
                try {
                    return data['pass_time'];
                } catch (error){
                    return false;
                }
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