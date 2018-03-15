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
                    <div class="row">
                        <ol class="breadcrumb">
                            <li><a @click="toSearch(0,'<?= $id ??''?>')">首页</a></li>
                            <li v-for="(u, index) in leader"><a  @click="toSearch(index + 1, u.id)">{{ u.name }}</a></li>
                            <li class="active">详细信息</li>
                        </ol>
                    </div>
                    <div class="row" style="margin: 10px 0px; ">
                        <div class="col-sm-2">
                            <el-input v-model="name" placeholder="请输入姓名" clearable></el-input>
                        </div>
                        <div class="col-sm-3">
                            <el-cascader expand-trigger="click" :props="selectP" :options="provinces" v-model="selectPro" change-on-select>
                            </el-cascader>
                        </div>
                        <div class="col-sm-3">
                            <a class="btn btn-success" @click="getAgent">查询</a>
                        </div>
                        <div class="col-sm-3" v-if="user">
                            <el-badge :value="user.subordinate_amount" class="item">
                                <el-button size="small">邀请者: {{ user.name }}</el-button>
                            </el-badge>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>员工编号</th>
                                <th>真实姓名</th>
                                <th>手机号码</th>
                                <th>地区</th>
                                <th>下级业绩</th>
                                <th>创建时间</th>
                                <th>邀请者</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in lists">
                                    <td>{{ item.user && item.user.id ? item.user.id: '' }}</td>
                                    <td>{{ item.user && item.user.name ? item.user.name: '' }}</td>
                                    <td>{{ item.user && item.user.phone ? item.user.phone: '' }}</td>
                                    <th>{{ item.region && item.region.province }} - {{ item.region && item.region.city }} - {{ item.region && item.region.county }}</th>
                                    <th>{{ item.subordinate_amount }}</th>
                                    <th>{{ item.created_at }}</th>
                                    <th>{{ item.nearest_inviter && item.nearest_inviter.name ? item.nearest_inviter.name: '没找到' }}</th>
                                    <th>
                                        <a class="btn btn-info btn-xs" @click="getLeader(item.user)">查看下级</a>
                                        <a class="btn btn-danger btn-xs" @click="getOrder(item.user)">查看订单</a>
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
            lists: [],
            name: '',
            inviter: "<?= $id ?>",
            provinces: '',
            leader: [],
            selectPro: [],
            pageIndex: 1,
            total: 0,
            range: 15,
            selectP: {
                value: 'region_name',
                label: 'region_name',
                children: 'all_child'
            },
            user: ''
        },
        created:function () {
            if (this.provinces =='') {
                this.getProvinces();
            }
            this.getAgent();
        },
        methods:{
            getProvinces:function () {
                var url = this.baseUrl + 'region';
                var params = {headers:{'X-TOKEN':this.token}};
                this.$http.get(url, params).then(function (response) {
                    this.provinces = response.data.data;
                },function (response) {

                })
            },
            getAgent:function () {
                var url = this.userUrl + "agents";
                var params = {
                    headers:{'X-TOKEN':this.token},
                    params: {
                        terms:{
                            name: this.name,
                            province: this.selectPro[0],
                            city: this.selectPro[1],
                            county: this.selectPro[2],
                            inviter: this.inviter
                        },
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
            getLeader:function (user) {
                this.leader.push(user);
                this.inviter = user.id;
                this.user = user;
                this.getAgent();
            },
            toSearch:function (key, id) {
                this.leader = this.leader.slice(0, key);
                this.inviter = id;
                this.user = this.leader[0];
                this.getAgent();
            },
            getOrder:function (user) {
                parent.layer.open({
                    type: 2,
                    title: false,
                    shadeClose:true,
                    shade: [0.8],
                    area: ['1200px', '800px'],
                    content: "<?= \yii\helpers\Url::toRoute('cash-examine/pass') ?>" + "?id="+user.id
                })
            },

            pages: function (val) {
                this.pageIndex = val;
                this.toSearch();
            }
        }
    })
</script>


