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
                    <button type="input" class="btn btn-info" @click="toSearchBtn"><i class="fa fa-search" ></i>查询</button>
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
                                <?php if ($repayment == 'paid' or $repayment == 'paidOff') { ?>
                                <th>还款时间</th>
                                <?php } else { ?>
                                <th>应还时间</th>
                                <?php } ?>
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
                                <?php if ($repayment == 'paid' or $repayment == 'paidOff') { ?>
                                    <td class="client-status">{{value.repaid_at}}</td>
                                <?php } else { ?>
                                    <td class="client-status">{{value.due_date}}</td>
                                <?php } ?>
                                <td class="client-status">{{value.overdue_days}}</td>
                                <td class="client-status">{{value.overdue_fee}}</td>
                                <td class="client-status">
                                    <a class="btn btn-info btn-xs" @click="open(value.order_id)">详情</a>
                                    <a class="btn btn-info btn-xs" @click="repayLists(value.order_id)">还款计划</a>
                                    <?php if (Yii::$app->getUser()
                                    ->can(yii\helpers\Url::toRoute('cash-repayment/to-deduct'))) { ?>
                                    <a class="btn btn-danger btn-xs" 
                                    v-if="value.status == 'pending' || value.status == 'aborted'"
                                    @click="deduct(value.order_id)">还款</a>
                                    <?php } ?>
                                    <a class="btn btn-success btn-xs disabled" v-if="value.status == 'paying'">正在还款</a>
                                    <a class="btn btn-warning btn-xs disabled" v-if="value.status == 'paid'">已还</a>
                                    <a class="btn btn-default btn-xs" @click="addMemos(value.order_id)">备注</a>

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

    <el-dialog title="增加备注" :visible.sync="dialogFormVisible">
        <el-input placeholder="请输入备注内容" clearable v-model="memos">
        </el-input>
        <div slot="footer" class="dialog-footer">
            <el-button @click="dialogFormVisible = false">取 消</el-button>
            <el-button type="primary" @click="toSendMemos">确 定</el-button>
        </div>
    </el-dialog>

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
            baseUrl:"<?= Yii::$app->params['cashBaseUrl'] ?>",
            dialogFormVisible: false,
            memosID: 0,
            memos: ''
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
                    this.pageCount = usedData['data']['list']['total'];
                    this.pageIndex = usedData['data']['list']['current_page'];
                    console.info(this.dataList[0]['component'][0]['interest']);
                },function (response){
                    console.log(response['body']['errors']);
                    layer.msg(response['body']['errors'][0]['message'],{icon:2})
                })
            },
            toSearchBtn:function () {
                this.pageIndex = 1;
                this.toSearch()
            },
            component:function(data){
                return JSON.parse(data);
            },
            pageChange:function(val) {
                this.pageIndex = val;
                this.toSearch();
            },
            addMemos:function (id) {
                this.memosID = id;
                this.memos = '';
                this.dialogFormVisible = true;
            },
            toSendMemos:function () {
                var url = this.baseUrl + this.memosID +'/memos';
                var data = {content: this.memos};
                var token = {headers:{'X-TOKEN':this.token}};
                this.$http.post(url, data,token).then(function(data){
                    var json = data.bodyText;
                    var usedData = JSON.parse(json);

                    if (usedData['success'] == true) {
                        this.$message({message: '恭喜你，备注成功',type: 'success'});
                        this.dialogFormVisible = false;
                    }else{
                        layer.msg(usedDatas['data'],{icon:2});
                    }
                },function(response){
                    var json = response.bodyText;
                    var usedData = JSON.parse(json);
                    layer.msg(usedData['errors'][0]['message'], {icon:2});
                });
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
                    period: 1,
                    param:{
                        name:$('input[name=name]').val(),
                        phone:$('input[name=phone]').val(),
                        sTime:$('input[name=sTime]').val(),
                        eTime:$('input[name=eTime]').val()
                    },
                    page:this.pageIndex,
                    status: "<?= $repayment ?>"
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
                        parent.layer.msg('发起还款成功!',{icon:1});
                        this.dataList = usedDatas['data']['data'];
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