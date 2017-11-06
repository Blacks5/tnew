<script src="/js/vue.js"></script>
<script src="/js/vue-resource.js"></script>
<script src="/js/plugins/layer/layer.min.js"></script>
<style>
    .height{height:60px;padding:20px 0;}
</style>
<div class="container-f" style="background-color: white;" id="list">
    <h1 class="text-center">{{order['name']}}的借款详情<span>【待上传照片】</span></h1>
    <div class="container">
        <h3 class="text-danger text-center">订单信息</h3>
        <div class="hr-line-dashed"></div>
        <div class="list-group">
            <a class="list-group-item col-sm-3">订单编号<span class="badge">{{order['orders_number']}}</span></a>
            <a class="list-group-item  col-sm-3">申请金额<span class="badge">{{order['expected_amount']}}</span></a>
            <a class="list-group-item  col-sm-3">还款期数<span class="badge">{{order['period_total']}}</span></a>
            <a class="list-group-item  col-sm-3">还款周期<span class="badge">{{order['repay_cycle'] == 'week'?'周':'月'}}</span></a>
            <a class="list-group-item  col-sm-3">个人保障计划<span class="badge">{{order['']}}</span></a>
            <a class="list-group-item  col-sm-3">贵宾服务包<span class="badge">{{order['is_add_service_fee']==1?'是':'否'}}</span></a>
            <a class="list-group-item  col-sm-3">备注<span class="badge">{{order['extended_data']}}</span></a>
            <a class="list-group-item  col-sm-3">图片<span class="badge">点击查看</span></a>
        </div>
    </div>
    <div class="container" v-if="order['status'] < 90">
        <div class="col-sm-12 height"><h3 class="text-danger text-center">贷款信息</h3></div>
        <hr/>
        <div class="list-group">
            <a class="list-group-item col-sm-3">{{order['repay_cycle'] == 'week'?'日':'月'}}利率<span class="badge">{{rate['rate']}}</span> </a>
            <a class="list-group-item col-sm-3">个人保障计划<span class="badge">{{amount['freePackFee']}}元</span> </a>
            <a class="list-group-item col-sm-3">贵宾服务包<span class="badge">{{amount['addServiceFee']}}元</span> </a>
            <a class="list-group-item col-sm-3">财务管理费<span class="badge">{{amount['financialManage']}}元</span> </a>
            <a class="list-group-item col-sm-3">客户管理费<span class="badge">{{amount['customerManage']}}</span> </a>
        </div>
    </div>
    <div class="container">
        <div class="col-sm-12 height"><h3 class="text-danger text-center">贷款信息</h3></div>
        <hr/>
        <div class="list-group" v-if="order['status'] > 90">
            <a class="list-group-item col-sm-3">{{order['repay_cycle'] == 'week'?'日':'月'}}利率<span class="badge">{{rate['rate']}}</span> </a>
            <a class="list-group-item col-sm-3">每期还款金额<span class="badge">{{order['total_repay']}}元</span> </a>
            <a class="list-group-item col-sm-3">本金<span class="badge">{{component['interest']}}元</span> </a>
            <a class="list-group-item col-sm-3">利息<span class="badge">{{component['principal']}}元</span> </a>
            <a class="list-group-item col-sm-3">个人保障计划<span class="badge">{{component['free_pack_fee']}}元</span> </a>
            <a class="list-group-item col-sm-3">贵宾服务包<span class="badge">{{component['add_server_fee']}}元</span> </a>
            <a class="list-group-item col-sm-3">财务管理费<span class="badge">{{component['finance_manage_fee']}}元</span> </a>
            <a class="list-group-item col-sm-3">客户管理费<span class="badge">{{component['customer_manage_fee']}}</span> </a>
        </div>
    </div>
    <div class="container">
        <div class="col-sm-12 height"><h3 class="text-danger text-center">客户信息</h3></div>
        <div class="hr-line-dashed"></div>
        <div class="list-group">
            <a class="list-group-item col-sm-3">客户姓名<span class="badge">{{order['name']}}</span> </a>
            <a class="list-group-item col-sm-3">客户电话<span class="badge">{{order['phone']}}</span> </a>
            <a class="list-group-item col-sm-3">身份证<span class="badge">{{identification['number']}}</span> </a>
            <a class="list-group-item col-sm-3">性别<span class="badge">{{order['gender']}}</span> </a>
            <a class="list-group-item col-sm-3">QQ号<span class="badge">{{order['qq']}}</span> </a>
            <a class="list-group-item col-sm-3">微信号<span class="badge">{{order['wechat_number']}}</span> </a>
            <a class="list-group-item col-sm-6">户籍地址<span class="badge">{{identification['address']}}</span> </a>
            <a class="list-group-item col-sm-3">工作单位<span class="badge">{{job['name']}}</span> </a>
            <a class="list-group-item col-sm-3">工作电话<span class="badge">{{job['phone']}}</span> </a>
            <a class="list-group-item col-sm-6">工作地址<span class="badge">{{job['address']}}</span> </a>

            <a class="list-group-item col-sm-4">婚姻状况<span class="badge">{{marital['status']}} - {{marital['spouse_name']}} - {{[marital['spouse_phone']]}}</span> </a>
            <a class="list-group-item col-sm-4" >其他联系人<span class="badge">{{contacts['name']}} - {{contacts['phone']}} - {{contacts['relation']}}</span> </a>
            <a class="list-group-item col-sm-4" >还款信息<span class="badge">{{bank['bank_code']}} - {{bank['number']}}</span> </a>

        </div>
    </div>
    <div class="container">
        <div class="col-sm-12 height"><h3 class="text-danger text-center">审核放款信息</h3></div>
        <div class="list-group">
            <a class="list-group-item col-sm-3">上门审核人员<span class="badge"></span></a>
        </div>
    </div>

</div>
<script>
    Vue.use(VueResource);
    new Vue ({
        el: '#list',
        data: {
            order: [],
            rate: [],
            amount:[],
            component:[],
            identification:[],
            marital:[],
            contacts:[],
            job:[],
            bank:[]

        },
        created: function () {
            this.toSearch();
        },
        methods: {
            toSearch: function () {
                var url = "http://cash.app/v1/orders/<?= $id ?>";
                var header = {
                    headers: {'X-TOKEN': '123123123123123'}
                };

                this.$http.get(url, header).then(function (data) {
                    var json = data.bodyText;
                    var usedData = JSON.parse(json);

                    this.order = usedData['data']['order'];
                    this.rate = usedData['data']['rate'];
                    this.amount = usedData['data']['amount'];
                    this.component = JSON.parse(this.order['component']);
                    this.identification = JSON.parse(this.order['identification_card']);
                    this.job = JSON.parse(this.order['job']);
                    this.marital = JSON.parse(this.order['marital']);
                    this.contacts = JSON.parse(this.order['contacts']);
                    this.bank = JSON.parse(this.order['bank_card']);
                }, function (response) {
                    console.log(response['body']['errors']);
                    layer.msg(response['body']['errors'][0]['message'], {icon: 2})
                });
            },
        }
    });
</script>