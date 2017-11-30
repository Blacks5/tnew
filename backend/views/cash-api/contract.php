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
                                <td class="client-avatar">{{value.order_id}}</td>
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
    Vue.use(VueResource);
    new Vue({
        el: '#list',
        data:{
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
                var header = {headers:{'X-TOKEN':this.token}};
                this.$http.get(url,header).then(function (data){
                    var json = data.bodyText;
                    var usedData = JSON.parse(json);

                    this.lists = usedData['data']['data'];
                    this.pageCount = usedData['data']['last_page'];
                    this.pageIndex = usedData['data']['current_page'];
                },function (response){
                    console.log(response['body']['errors']);
                    layer.msg(response['body']['errors'][0]['message'],{icon:2})
                })
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
            }
        }
    });
</script>