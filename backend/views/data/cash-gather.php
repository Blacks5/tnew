<script src="/js/vue.js"></script>
<script src="/js/vue-resource.js"></script>
<script src="/js/plugins/laydate/laydate.js"></script>
<script src="/js/plugins/layer/layer.min.js"></script>
<link rel="stylesheet" href="https://unpkg.com/element-ui@2.0.10/lib/theme-chalk/index.css">
<script src="https://unpkg.com/element-ui@2.0.10/lib/index.js"></script>

<div class="ibox-content" id="list">
    <div class="row" style="margin: 10px 0px; ">
        <div class="col-sm-2">
            <el-input v-model="username" placeholder="请输入姓名" clearable></el-input>
        </div>
        <div class="col-sm-2">
            <el-date-picker v-model="sTime" type="date" placeholder="选择开始日期" value-format="yyyy-MM-dd"></el-date-picker>
        </div>
        <div class="col-sm-2">
            <el-date-picker v-model="eTime" type="date" placeholder="选择结束日期" value-format="yyyy-MM-dd"></el-date-picker>
        </div>
        <div class="col-sm-2">
            <el-cascader expand-trigger="click" :props="selectP" :options="provinces" v-model="selectPro" change-on-select placeholder="请选择地区">
            </el-cascader>
        </div>
        <div class="col-sm-3">
            <a class="btn btn-success" @click="getData">查询</a>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4">
            <div class="panel panel-success">
                <header class="panel-heading">总放款</header>
                <div class="panel-body">
                    <div class="list-group">
                        <a class="list-group-item">月供<span class="badge" v-text="total.totalRepay"></span></a>
                        <a class="list-group-item">本息(包含本金+利息+客户管理费+财务管理费)<span class="badge" v-text="total.total"></span></a>
                        <a class="list-group-item">客户管理费<span class="badge" v-text="total.customer"></span></a>
                        <a class="list-group-item">财务管理费<span class="badge" v-text="total.finance"></span></a>
                        <a class="list-group-item">利息<span class="badge" v-text="total.interest"></span></a>
                        <a class="list-group-item">本金<span class="badge" v-text="total.principal"></span></a>
                        <a class="list-group-item">滞纳金<span class="badge" v-text="total.overdueFee"></span></a>
                        <a class="list-group-item">个人保障计划<span class="badge" v-text="total.addServerFee"></span></a>
                        <a class="list-group-item">贵宾服务包<span class="badge" v-text="total.freePackFee"></span></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="panel panel-success">
                <header class="panel-heading">总回收</header>
                <div class="panel-body">
                    <div class="list-group">
                        <a class="list-group-item">月供<span class="badge" v-text="repay.totalRepay"></span></a>
                        <a class="list-group-item">本息(包含本金+利息+客户管理费+财务管理费)<span class="badge" v-text="repay.total"></span></a>
                        <a class="list-group-item">客户管理费<span class="badge" v-text="repay.customer"></span></a>
                        <a class="list-group-item">财务管理费<span class="badge" v-text="repay.finance"></span></a>
                        <a class="list-group-item">利息<span class="badge" v-text="repay.interest"></span></a>
                        <a class="list-group-item">本金<span class="badge" v-text="repay.principal"></span></a>
                        <a class="list-group-item">滞纳金<span class="badge" v-text="repay.overdueFee"></span></a>
                        <a class="list-group-item">个人保障计划<span class="badge" v-text="repay.addServerFee"></span></a>
                        <a class="list-group-item">贵宾服务包<span class="badge" v-text="repay.freePackFee"></span></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="panel panel-success">
                <header class="panel-heading">未回收</header>
                <div class="panel-body">
                    <div class="list-group">
                        <a class="list-group-item">月供<span class="badge" v-text="pay.totalRepay"></span></a>
                        <a class="list-group-item">本息(包含本金+利息+客户管理费+财务管理费)<span class="badge" v-text="pay.total"></span></a>
                        <a class="list-group-item">客户管理费<span class="badge" v-text="pay.customer"></span></a>
                        <a class="list-group-item">财务管理费<span class="badge" v-text="pay.finance"></span></a>
                        <a class="list-group-item">利息<span class="badge" v-text="pay.interest"></span></a>
                        <a class="list-group-item">本金<span class="badge" v-text="pay.principal"></span></a>
                        <a class="list-group-item">滞纳金<span class="badge" v-text="pay.overdueFee"></span></a>
                        <a class="list-group-item">个人保障计划<span class="badge" v-text="pay.addServerFee"></span></a>
                        <a class="list-group-item">贵宾服务包<span class="badge" v-text="pay.freePackFee"></span></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    Vue.use(VueResource)
    new Vue({
        el: '#list',
        data: {
            baseUrl: "<?= Yii::$app->params['cashBaseUrl'] ?>",
            userUrl: "<?= Yii::$app->params['v2_user'] ?>",
            token: window.sessionStorage.getItem('V2_TOKEN'),
            username: '',
            sTime: '',
            eTime: '',
            provinces: '',
            selectPro: [],
            total: '',
            repay: '',
            pay: '',
            selectP: {
                value: 'region_name',
                label: 'region_name',
                children: 'all_child'
            }
        },
        created:function () {
            if (this.provinces == '') {
                this.getProvinces()
            }
            this.getData()
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
            getData:function() {
                console.log(this.sTime)
                var url = this.baseUrl + 'allCount';
                var params = {
                    headers:{'X-TOKEN':this.token},
                    params: {
                        terms:{
                            username: this.username,
                            sTime: this.sTime,
                            eTime: this.eTime,
                            province: this.selectPro[0],
                            city: this.selectPro[1],
                            county: this.selectPro[2]
                        }
                    }
                };
                console.log(params);
                this.$http.get(url, params).then(function (response) {
                    this.total = response.body.data.all;
                    this.repay = response.body.data.repay;
                    this.pay = response.body.data.pay;
                })
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
