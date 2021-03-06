<script src="/js/vue.js"></script>
<script src="/js/vue-resource.js"></script>
<script src="/js/plugins/layer/layer.min.js"></script>
<link rel="stylesheet" href="https://unpkg.com/element-ui@2.0.10/lib/theme-chalk/index.css">
<script src="https://unpkg.com/element-ui@2.0.10/lib/index.js"></script>


<div class="ibox-content" id="list">
    <div class="row">
        <div class="col-sm-12">
            <div class="col-sm-3">
                <input type="text" class="form-control" name="order_number" v-model:value="params['order_number']" placeholder="订单编号">
            </div>
            <div class="col-sm-3">
                <input type="text" class="form-control" name="signID" v-model:value="params['signID']" placeholder="签约ID">
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
                                <th class="client-avatar">订单号</th>
                                <th>签约ID</th>
                                <th><a data-toggle="tab" href="#contact-3" class="client-link">客户姓名</a></th>
                                <th>签约时间</th>
                                <th>状态</th>
                                <th>操作人</th>
                                <th>创建时间</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="value in lists">
                                <td class="client-avatar">{{value.order_number}}</td>
                                <td>{{value.thirdparty_id}}</td>
                                <td>{{value.identification_card.name}}</td>
                                <td>{{value.signed_at ==null?'还未签约':value.signed_at }}</td>
                                <td>
                                    <a class="btn btn-xs btn-info" v-if="value.status =='pending'">等待签约</a>
                                    <a class="btn btn-xs btn-success" v-if="value.status == 'signed'">签约成功</a>
                                    <a class="btn btn-xs btn-danger" v-if="value.status == 'refused'">签约失败</a>
                                </td>
                                <td>{{value.name}}</td>
                                <td>{{value.created_at}}</td>
                                <td class="client-status">
                                    <a class="btn btn-info btn-xs" @click="info(value.order_id)">详情</a>
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
    Vue.use(VueResource);
    new Vue({
        el: '#list',
        data:{
            params: {
                order_number: '',
                signID: ''
            },
            lists:[],
            token: window.sessionStorage.getItem('V2_TOKEN'),
            baseUrl:"<?= Yii::$app->params['cashBaseUrl'] ?>",
            pageIndex:1,
            pageCount:1
        },
        created: function () {
            this.toSearch();
        },
        methods: {
            toSearch:function (){
                var url = this.baseUrl + "contract";
                var header = {
                    headers:{'X-TOKEN':this.token},
                    params: {
                        param:{
                            order_number:this.params['order_number'],
                            signID: this.params['signID'],
                        },
                        page: this.pageIndex
                    }

                };
                this.$http.get(url,header).then(function (data){
                    var json = data.bodyText;
                    var usedData = JSON.parse(json);

                    this.lists = usedData['data']['data'];
                    this.pageCount = usedData['data']['total'];
                    this.pageIndex = usedData['data']['current_page'];
                },function (response){
                    console.log(response['body']['errors']);
                    layer.msg(response['body']['errors'][0]['message'],{icon:2})
                })
            },
            toSearchBtn:function () {
                this.pageIndex = 1;
                this.toSearch();
            },
            info:function(id){
                var url = this.baseUrl + id +"/contract";
                var header ={headers:{'X-TOKEN':this.token}};
                this.$http.get(url,header).then(function (data){
                    var json =data.bodyText;
                    var usedData = JSON.parse(json);

                    window.open(usedData['data']['detailLink']);
                },function (response){
                    layer.msg(response['body']['errors'][0]['message'],{icon:2});
                })
            },
            pageChange:function (val) {
                this.pageIndex = val
                this.toSearch()
            }
        }
    });
</script>