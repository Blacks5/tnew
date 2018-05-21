<script src="/js/vue.js"></script>
<script src="/js/vue-resource.js"></script>
<script src="/js/plugins/laydate/laydate.js"></script>
<script src="/js/plugins/layer/layer.min.js"></script>
<link rel="stylesheet" href="https://unpkg.com/element-ui@2.0.10/lib/theme-chalk/index.css">
<script src="https://unpkg.com/element-ui@2.0.10/lib/index.js"></script>
<div class="wrapper wrapper-content" id="agentList">
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <div class="row" style="margin: 10px 0px; ">
                        <div class="col-sm-2">
                            <el-input v-model="phone" placeholder="请输入手机号码" clearable></el-input>
                        </div>
                        <div class="col-sm-3">
                            <a class="btn btn-success" @click="toSearch">查询</a>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>用户编号</th>
                                <th>真实姓名</th>
                                <th>手机号码</th>
                                <th>创建时间</th>
                                <th>邀请者</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in lists">
                                    <td>{{ item.id }}</td>
                                    <td>{{ item.name }}</td>
                                    <td>{{ item.phone }}</td>
                                    <th>{{ item.created_at }}</th>
                                    <th>
                                        <span v-for="(i, index) in item.inviter_path" :key="index" v-if="item.inviter_path.length - 1 > index">
                                            {{i.name}} <i class="el-icon-arrow-right" v-if="index < item.inviter_path.length -2"></i>
                                        </span>
                                    </th>
                                    <th>
                                        <a class="btn btn-info btn-xs" @click="invitee(item.phone)">查看下级</a>
                                        <a class="btn btn-danger btn-xs" @click="getOrder(item.id)">查看订单</a>
                                    </th>
                                </tr>

                            </tbody>
                        </table>
                        <!--分页-->
                        <el-pagination
                                background
                                layout="prev, pager, next" :page-size="range" @current-change="pages"
                                :total="total">
                        </el-pagination>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>
<script>
    Vue.use(VueResource);
    new Vue({
        el: '#agentList',
        data: {
            baseUrl: "<?= Yii::$app->params['cashBaseUrl'] ?>",
            userUrl: "<?= Yii::$app->params['v2_user'] ?>",
            token: window.sessionStorage.getItem('V2_TOKEN'),
            phone: '',
            lists: [],
            name: '',
            selectPro: [],
            pageIndex: 1,
            total: 0,
            range: 15
        },
        created:function () {
            this.inviteeList();
        },
        methods:{
            inviteeList:function () {
                var url = this.userUrl + "users/all-invitee";
                var params = {
                    headers:{'X-TOKEN':this.token},
                    params: {
                        inviter_phone: this.phone,
                        offset: (this.pageIndex - 1) * this.range,
                        range: this.range
                    }
                };
                this.$http.get(url, params).then(function (response) {
                    this.lists = response.data.data.items;
                    this.total = response.data.data.total;
                },function (response) {

                });
            },
            toSearch:function () {
                this.pageIndex = 1;
                this.inviteeList();
            },
            invitee:function (phone) {
                this.phone = phone;
                this.inviteeList()
            },
            getOrder:function (id) {
                parent.layer.open({
                    type: 2,
                    title: false,
                    shadeClose:true,
                    shade: [0.8],
                    area: ['1200px', '800px'],
                    content: "<?= \yii\helpers\Url::toRoute('cash-examine/pass') ?>" + "?customer_id="+id
                })
            },

            pages: function (val) {
                this.pageIndex = val;
                this.inviteeList();
            }
        }
    })
</script>


