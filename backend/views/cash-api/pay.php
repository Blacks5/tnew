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
                                <th >订单号</th>
                                <th>签约ID</th>
                                <th><a data-toggle="tab" href="#contact-3" class="client-link">客户姓名</a></th>
                                <th v-if="url == 'loans' || url == 'deducts'">成功时间</th>
                                <th v-if="url == 'loans'">代发金额</th>
                                <th v-if="url == 'deducts'">代扣金额</th>
                                <th>状态</th>
                                <th>操作人</th>
                                <th>创建时间</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="value in lists">
                                <td>{{value.order_number}}</td>
                                <td>{{value.thirdparty_id}}</td>
                                <td>{{value.customerName}}</td>
                                <td v-if="url == 'loans'">{{value.paid_at ==null?'还未成功':value.paid_at }}</td>
                                <td v-if="url == 'deducts'">{{value.repaid_at ==null?'还未成功':value.repaid_at }}</td>
                                <td v-if="url == 'loans'">{{value.expected_amount}}</td>
                                <td v-if="url == 'deducts'">{{value.amount}}</td>
                                <td>
                                    <a class="btn btn-xs btn-info" v-if="value.status =='pending'">等待回调</a>
                                    <a class="btn btn-xs btn-success" v-if="value.status == 'check_need'">带审核</a>
                                    <a class="btn btn-xs btn-success" v-if="value.status == 'processing'">处理中</a>
                                    <a class="btn btn-xs btn-success" v-if="value.status == 'successful'">成功</a>
                                    <a class="btn btn-xs btn-danger" v-if="value.status == 'failed'">失败</a>
                                </td>
                                <td>{{value.name}}</td>
                                <td>{{value.created_at}}</td>
                                <td class="client-status">
                                    <a class="btn btn-info btn-xs" @click="info(value.id)">详情</a>
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
            pageCount:1,
            url:"<?= $url ?>"
        },
        created: function () {
            this.toSearch();
        },
        methods: {
            toSearch:function (){
                var url = this.baseUrl + this.url;
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
                layer.open({
                    type:2,
                    title:false,
                    shadeClose:true,
                    shade:[0.8],
                    area: ['1200px', '300px'],
                    content: "<?= \yii\helpers\Url::toRoute('cash-api/info') ?>" + "?id="+id +'&url=' +this.url

                })
            }
        }
    });
</script>