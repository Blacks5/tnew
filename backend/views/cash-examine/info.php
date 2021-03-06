<script src="/js/vue.js"></script>
<script src="/js/vue-resource.js"></script>
<script src="/js/plugins/layer/layer.min.js"></script>
<link rel="stylesheet" href="https://unpkg.com/element-ui@2.0.10/lib/theme-chalk/index.css">
<script src="https://unpkg.com/element-ui@2.0.10/lib/index.js"></script>
<style>
    body{background-color: white;}
    .height{height:60px;padding:20px 0;}
</style>
<div class="ibox float-e-margins" id="list">
    <div class="ibox-content">
        <div class="form-horizontal m-t" id="signupForm" novalidate="novalidate">
            <div class="container-f">
                <h1 class="text-center">{{order['name']}}的借款详情<span>【{{getStatus(order.order_status)}}】</span> <span>【{{order.saleman_id ? '销售订单' : '客户订单'}}】</span></span></h1>
                <div class="container">
                    <h3 class="text-danger text-center">订单信息</h3>
                    <div class="hr-line-dashed"></div>
                    <div class="list-group">
                        <a class="list-group-item col-sm-3">订单编号<span class="badge">{{order['order_number']}}</span></a>
                        <a class="list-group-item  col-sm-3">申请金额<span class="badge">{{order['expected_amount']}}</span></a>
                        <a class="list-group-item  col-sm-3" v-if="order.status >= 40 && order.status != 200">审批金额<span class="badge">{{order['accepted_amount']}}</span></a>
                        <a class="list-group-item  col-sm-3">还款期数<span class="badge">{{order['period_total']}}</span></a>
                        <a class="list-group-item  col-sm-3">还款周期<span class="badge">{{order['repay_cycle'] == 'week'?'周':'月'}}</span></a>
                        <a class="list-group-item  col-sm-3">个人保障计划<span class="badge">{{order['is_free_pack_fee'] ==1 ?'是':'否'}}</span></a>
                        <a class="list-group-item  col-sm-3">贵宾服务包<span class="badge">{{order['is_add_service_fee']==1?'是':'否'}}</span></a>
                        <a class="list-group-item  col-sm-3" @click="images(order['id'])">图片<span class="badge">点击查看</span></a>
                        <a class="list-group-item  col-sm-2">产品类型<span class="badge">{{ order.product_type == 1?'常规':'促销'}}</span></a>
                        <a class="list-group-item  col-sm-2">邀请用户<span class="badge" @click="getInvitee(order.phone)">点击查看</span></a>
                        <a class="list-group-item  col-sm-8">备注<span class="badge" v-if="order.extended_data && order.extended_data.remark">{{ order.extended_data.remark }}</span></a>
                    </div>
                </div>
                <div class="container" v-if="order['status'] < 90 ">
                    <div class="col-sm-12 height"><h3 class="text-danger text-center">贷款信息</h3></div>
                    <hr/>
                    <div class="list-group">
                        <a class="list-group-item col-sm-3">{{order['repay_cycle'] == 'week'?'日':'月'}}利率<span class="badge">{{rate['rate']}}%</span> </a>
                        <a class="list-group-item col-sm-3">个人保障计划<span class="badge">{{amount['freePackFee']}}元/期</span> </a>
                        <a class="list-group-item col-sm-3">贵宾服务包<span class="badge">{{amount['addServiceFee']}}元</span> </a>
                        <a class="list-group-item col-sm-3">财务管理费<span class="badge">{{amount['financialManage']}}元</span> </a>
                        <a class="list-group-item col-sm-3">客户管理费<span class="badge">{{amount['customerManage']}}</span> </a>
                        <a class="list-group-item col-sm-3">预计月供<span class="badge">{{amount['periodAmount']}}元</span> </a>
                    </div>
                </div>
                <div class="container" v-if="order['status'] >= 120 && order.status!= 200">
                    <div class="col-sm-12 height"><h3 class="text-danger text-center">还款信息</h3></div>
                    <hr/>
                    <div class="list-group" >
                        <a class="list-group-item col-sm-3">{{order['repay_cycle'] == 'week'?'日':'月'}}利率<span class="badge">{{rate['rate']}}</span> </a>
                        <a class="list-group-item col-sm-3">每期还款金额<span class="badge">{{order['total_repay']}}元</span> </a>
                        <a class="list-group-item col-sm-3">本金<span class="badge">{{components['principal']}}元</span> </a>
                        <a class="list-group-item col-sm-3">利息<span class="badge">{{components['interest']}}元</span> </a>
                        <a class="list-group-item col-sm-3">个人保障计划<span class="badge">{{components['free_pack_fee']}}元</span> </a>
                        <a class="list-group-item col-sm-3">贵宾服务包<span class="badge">{{components['add_server_fee']}}元</span> </a>
                        <a class="list-group-item col-sm-3">财务管理费<span class="badge">{{components['finance_manage_fee']}}元</span> </a>
                        <a class="list-group-item col-sm-3">客户管理费<span class="badge">{{components['customer_manage_fee']}}</span> </a>
                    </div>
                </div>
                <!-- 备注信息 -->
                <div class="container" v-if="memos.length > 0">
                    <div class="col-sm-12 height"><h3 class="text-danger text-center">备注信息</h3></div>
                    <div class="hr-line-dashed"></div>
                    <div class="list-group">
                        <a class="list-group-item col-sm-6" v-for="item in memos">{{ item.content }}<span class="badge">{{ item.user_name }} - {{ item.created_at }}</span> </a>
                    </div>
                </div>
                <div class="container">
                    <div class="col-sm-12 height"><h3 class="text-danger text-center">客户信息</h3></div>
                    <div class="hr-line-dashed"></div>
                    <div class="list-group">
                        <a class="list-group-item col-sm-3">客户姓名<span class="badge">{{order['name']}}</span> </a>
                        <a class="list-group-item col-sm-3">客户电话<span class="badge">{{order['phone']}}</span> </a>
                        <a class="list-group-item col-sm-3">身份证<span class="badge">{{identification['number']}}</span> </a>
                        <a class="list-group-item col-sm-3">借款用途<span class="badge" v-if="">{{order.extended_data && order.extended_data.purpose ? order.extended_data.purpose: '未填'}}</span></a>

                        <a class="list-group-item col-sm-3">QQ号<span class="badge">{{order['qq']}}</span></a>
                        <a class="list-group-item col-sm-3">微信号<span class="badge">{{order['wechat_number']}}</span> </a>
                        <a class="list-group-item col-sm-6">户籍地址<span class="badge">{{identification['address']}}</span> </a>

                        <a class="list-group-item col-sm-3">工作单位<span class="badge">{{job['name']}}</span> </a>
                        <a class="list-group-item col-sm-3">工作电话<span class="badge">{{job['phone']}}</span> </a>
                        <a class="list-group-item col-sm-6">工作地址<span class="badge">{{job['address']}}</span> </a>

                        <a class="list-group-item col-sm-3">月收入<span class="badge" v-if="order.extended_data && order.extended_data.monthly_income" v-text="order.extended_data.monthly_income"></span> </a>
                        <a class="list-group-item col-sm-3">房屋权属<span class="badge" v-text="getHouse(order.extended_data)"></span> </a>
                        <a class="list-group-item col-sm-6" >现居地址<span class="badge">{{order.address}}</span> </a>

                        <a class="list-group-item col-sm-3">婚姻状况<span class="badge" v-if="marital != null">{{ getMarital(marital['status']) }}</span> </a>
                        <a class="list-group-item col-sm-3">性别<span class="badge">{{order['extended_data'] == null?'未填':order.extended_data['gender']}}</span> </a>

                        <a class="list-group-item col-sm-6">还款信息
                            <?php if (Yii::$app->getUser()->can(yii\helpers\Url::toRoute(['customer/change-bank-info']))) {?><button class="btn btn-xs btn-danger" @click="changeBank">修改</button><?php }?>
                            <span class="badge">{{bank['bank_name']}} - {{bank['number']}}</span></a>
                        <a class="list-group-item col-sm-4" v-for="c in contacts" v-if="c  != null">其他联系人<span class="badge">{{c['name']}} - {{c['phone']}} - {{ getContact(c['relation']) }}</span> </a>

                    </div>
                </div>
                <div class="container">
                    <div class="col-sm-12 height"><h3 class="text-danger text-center">审核信息</h3></div>
                    <div class="list-group" v-if="order.sale != null">
                        <a class="list-group-item col-sm-3" v-if="order.sale && order.sale.name">销售人员<span class="badge">{{order['sale']['name'] }} - {{order['sale']['phone']}}</span></a>
                        <a class="list-group-item col-sm-3" v-else>客户订单</a>
                    </div>
                    <div class="list-group" v-if="order['status'] >=20 && order.visitor && order.visitor.name">
                        <a class="list-group-item col-sm-3" >上门审核人员<span class="badge">{{order['visitor']['name']}} - {{order['visitor']['phone']}}</span></a>
                        <a class="list-group-item col-sm-3" >后台审核人员<span class="badge" v-if="order.auditor != null">{{order['auditor']['name']}}</span></a>
                    </div>
                </div>
                <div class="container" v-if="order.status == 120 || order.status == 130">
                    <div class="col-sm-12 height"><h3 class="text-danger text-center">代发信息</h3> </div>
                    <div class="list-group" >
                        <a class="list-group-item col-sm-4">代发ID<span class="badge">{{loan.thirdparty_id}}</span> </a>
                        <a class="list-group-item col-sm-3">代发金额<span class="badge">{{loan.expected_amount}}</span> </a>
                        <a class="list-group-item col-sm-2">代发操作人<span class="badge" v-if="loan.auditor && loan.auditor.name">{{loan.auditor['name']}}</span> </a>
                        <a class="list-group-item col-sm-3">代发时间<span class="badge">{{loan.created_at}}</span> </a>
                    </div>
                </div>
                <div class="container center" style="margin-top: 30px;">
                    <div class="row" >
                        <?php if(Yii::$app->getUser()->can(yii\helpers\Url::toRoute('cash-examine/examine'))){ ?>
                        <div class="col-sm-3 form-group" v-show="order['status'] == 15">
                            <select class="form-control"  name="visitorID">
                                <option v-for="v in visitor" :value="v['id']">{{v['name']}}</option>
                            </select>
                        </div>
                        <div class="col-sm-3" v-show="order['status'] == 10">
                            <button class="btn btn-info" type="button" @click="canVisitor">初审通过</button>
                        </div>
                        <div class="col-sm-3" v-show="order['status'] == 15">
                            <button class="btn btn-info" type="button" @click="setVisitor">分配上门审核人员</button>
                        </div>
                        <div class="col-sm-2" v-show="order['status'] == 30">
                            <button type="button" class="btn btn-info" @click="examine">一审</button>
                        </div>
                        <div class="col-sm-2" v-show="order['status'] == 50">
                            <button type="button" class="btn btn-info" @click="examineTwo">二审</button>
                        </div>
                        <div class="col-sm-2" v-show="order['status'] == 60">
                            <button type="button" class="btn btn-info" @click="contract">电子合同签约</button>
                        </div>
                        <div class="col-sm-2" v-show="order['status'] == 70">
                            <button type="button" class="btn btn-info" @click="sign">扣款签约</button>
                        </div>
                        <div class="col-sm-2" v-show="order['status'] < 100">
                            <button type="button" class="btn btn-warning" @click="revoke">拒绝订单</button>
                        </div>
                        <?php } ?>
                        <div class="row" v-if="order['status'] == 120">
                            <div class="col-sm-2">
                                <select class="form-control" id="repaySelect">
                                    <option v-for="n in (order.period_total - repay.count)" :value="n">未还的前 {{ n }} 期</option>
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <a class="btn btn-info" @click="getRepayAmount">提前还款计算</a>
                            </div>
                            <div class="col-md-3">
                                <div class="col-md-12 input-group">
                                    <input type="text" :value="repayAmount" id="calculation_residual_loan_val" class="form-control" disabled/>
                                    <span class="input-group-addon">元 需还款</span>
                                </div>
                                <div class="col-md-12 input-group">
                                    <input type="text" :value="overdueAmount" id="calculation_residual_loan_val" class="form-control" disabled/>
                                    <span class="input-group-addon">元 需催收</span>
                                </div>
                            </div>
                            <?php if (Yii::$app->getUser()->can(yii\helpers\Url::toRoute('borrownew/prepayment'))) { ?>
                            <div class="col-sm-2">
                                <a class="btn btn-danger" @click="repayMany">提前还款</a>
                                <a class="btn btn-info" @click="repayNoOverdue">催收还款</a>
                                <a class="btn btn-default" @click="prepayment">提前还款(不经过银行)</a>
                            </div>
                            <?php } ?>
                            <?php if (Yii::$app->getUser()->can(yii\helpers\Url::toRoute('borrownew/cancel-personal-protection'))) { ?>
                            <div class="col-sm-2" v-if="order.is_add_service_fee == 1">
                                <a class="btn btn-danger" @click="cService">取消贵宾服务包</a>
                            </div>
                            <div class="col-sm-1" v-if="order.is_free_pack_fee == 1">
                                <a class="btn btn-danger" @click="cPack">取消个人保障计划</a>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group" id="examineTwo" style="display:none">
        <div class="col-sm-12" style="margin-top: 20px;">
            <textarea class="form-control" placeholder="请属于理由" id="reasonTwo"></textarea>
        </div>
        <div class="col-sm-12">
            <label class="form-label">审批金额:</label>
            <input type="text" class="form-control" value="" id="acceptAmount">
        </div>

        <div class="col-sm-12">
            <label class="form-label">还款周期:</label>
            <select class="form-control" id="changePeriod">
                <option v-for="t in periodOptions[order.repay_cycle]" :value="t" :selected="t === order.period_total">{{ t }} 期</option>
            </select>
        </div>
    </div>
</div>
<div class="form-group" id="examine" style="display:none">
    <div class="col-sm-12" style="margin-top: 20px;">
        <textarea type="text" class="form-control" placeholder="请属于理由" id="reason"></textarea>
    </div>
</div>

<script>
    Vue.use(VueResource);
    var baseUrl = "<?= Yii::$app->params['cashBaseUrl'] ?>";
    new Vue ({
        el: '#list',
        data: {
            token:window.sessionStorage.getItem('V2_TOKEN'),
            order: [],
            rate: [],
            amount:[],
            components:[],
            identification:[],
            marital:[],
            contacts:[],
            job:[],
            bank:[],
            visitor:[],
            loan: [],
            memos: [],
            repay: [],
            period: 0,
            repayAmount: 0,
            overdueAmount: 0,
            periodOptions: {
                'week': [4, 8, 12, 24, 48],
                'month': [6, 9, 12, 18]
            }
        },

        created: function () {
            this.toSearch();
            this.getMemos();
        },
        methods: {
            getMemos:function () {
              var url = baseUrl + "<?= $id ?>/memos";
              var header = {headers:{'X-TOKEN': this.token}};

              this.$http.get(url, header).then(function (data) {
                  var json = data.bodyText;
                  var usedData = JSON.parse(json);

                  this.memos = usedData.data
              })

            },
            toSearch: function () {
                var url = baseUrl + "<?= $id ?>/detail";
                var header = {
                    headers:{'X-TOKEN':this.token},
                    params: {

                    }

                };

                this.$http.get(url, header).then(function (data) {
                    var json = data.bodyText;
                    var usedData = JSON.parse(json);

                    this.setDomData(usedData);

                }, function (response) {
                    console.log(response['body']['errors']);
                    layer.msg(response['body']['errors'][0]['message'], {icon: 2})
                });
            },
            setDomData:function(usedData){
                this.order = usedData['data']['order'];
                this.rate = usedData['data']['rate'];
                this.amount = usedData['data']['amount'];
                this.components = JSON.parse(this.order['component']);
                this.identification = this.order['identification_card'];
                this.job = this.order['job'];
                this.marital =this.order['marital'];
                this.contacts = this.order['contacts'];
                this.bank = this.order['bank_card'];
                this.visitor = usedData['data']['visitor']['items'];
                this.loan = usedData['data']['loan'];
                this.repay = usedData['data']['repay'];
            },
            setVisitor: function(){
                var url = baseUrl + "<?= $id ?>/visitor";
                var header = {
                    visitorID: $('select[name=visitorID]').val()

                };

                this.postOrder(url, header);
            },
            canVisitor: function () {
                var url = baseUrl + "<?= $id ?>/canVisitor";
                var header = [];
                this.postOrder(url, header);
            },
            examineTwo: function(){
                var url = baseUrl + "<?= $id ?>/examine/second";
                var __this = this;
                var index = layer.open({
                   type:1,
                   shade:0.2,
                   title:false,
                   content:$('#examine'),
                   btn:['通过','退回重填'],
                   btn1:function(){
                       var data = {
                           examine:1,
                           reason:$('#reason').val(),
                       };
                       __this.postOrder(url, data);
                       layer.close(index);
                   } ,
                    btn2:function(){
                        var data = {
                            examine:2,
                            reason:$('#reason').val(),
                        };
                       __this.postOrder(url, data);
                        layer.close(index);
                    }
                });

            },
            examine:function(){
                $('#acceptAmount').val(this.order['expected_amount']);
                var url = baseUrl + "<?= $id ?>/examine/first";

                var __this = this;
                var index = layer.open({
                    type:1,
                    shade:0.2,
                    title:false,
                    content:$('#examineTwo'),
                    btn:['通过','退回重填'],
                    btn1:function(){
                        if($('#acceptAmount').val() == ''){
                            layer.msg('终审金额不能为空!',{icon:2});return false;
                        }
                        var data = {
                            examine:1,
                            reason:$('#reasonTwo').val(),
                            acceptAmount:$('#acceptAmount').val(),
                            period: $('#changePeriod').val()
                        };
                        __this.postOrder(url, data);
                    },
                    btn2:function(){
                        var data = {
                            examine:2,
                            reason:$('#reasonTwo').val(),
                            acceptAmount:$('#acceptAmount').val()
                        };
                        __this.postOrder(url, data);
                        layer.close(index);
                    }
                });

            },
            contract:function(){
                var url = baseUrl + "<?= $id ?>/contract-signs";
                var data = [];
                var __this = this;
                var index = layer.msg('确定要发起签约么?',
                    {
                        btn:['确定','取消'],
                        btn1:function(){
                            __this.postOrder(url, data)
                        },
                        btn2:function(){
                            layer.close(index);
                        }

                    });
            },
            sign: function (){
                var url =baseUrl + "<?= $id ?>/deduct-signs";
                var data = [];
                var __this = this;
                var index = layer.msg('确定要发起签约么?',
                    {
                        btn:['确定','取消'],
                        btn1:function(){
                            __this.postOrder(url, data)
                        },
                        btn2:function(){
                            layer.close(index);
                        }

                    });
            },
            revoke:function (){
                var url = baseUrl + "<?= $id ?>/cancel";
                var data = 2;
                this.openDiv(url, data);
            },
            toDestroy:function(){
                var url = baseUrl + "<?= $id ?>/destroy";
                var examine = 3;
                this.openDiv(url, examine);
            },
            openDiv:function (url, examine){
                var __this = this;
                var index = layer.open({
                    type:1,
                    shade:0.2,
                    title:false,
                    content:$('#examine'),
                    btn:['确认','取消'],
                    btn1:function(){
                        var data = {
                            examine:examine,
                            reason:$('#reason').val()
                        };
                        __this.postOrder(url, data);
                    },
                    btn2:function(){
                        layer.close(index);
                    }
                });
            },
            images: function (id){
                var images = layer.open({
                    type: 2,
                    title: false,
                    shadeClose:true,
                    shade: [0.8],
                    area: ['1000px', '600px'],
                    content: "<?= \yii\helpers\Url::toRoute('cash-examine/images') ?>" + "?orderID="+id
                })
            },
            getInvitee: function (phone){
              var invitee = layer.open({
                  type: 2,
                  title: false,
                  shadeClose:true,
                  shade: [0.8],
                  area: ['1000px', '600px'],
                  content: "<?= \yii\helpers\Url::toRoute('user/invitee') ?>" + "?phone=" + phone
              })
            },
            postOrder:function (url, data){
                var loading = layer.load(0,{shade: false});
                var token = {headers:{'X-TOKEN':this.token}};
                this.$http.post(url, data,token).then(function(data){
                    layer.close(loading);
                    var json = data.bodyText;
                    var usedData = JSON.parse(json);
                    if(usedData['success']==true){
                        layer.msg(usedData['data']['message']);
                    }else{
                        layer.msg(usedData['data']['message'],{icon:2});
                    }
                    this.setDomData(usedData['data']);
                },function(response){
                    layer.close(loading);
                    var json = response.bodyText;
                    var usedData = JSON.parse(json);
                    layer.msg(usedData['errors'][0]['message'], {icon:2});
                });
            },
            getStatus: function(data){
                var status = {
                    1:'待初审',
                    2:'待上传一审照片',
                    5:'待上传一审照片',
                    3:'待一审',
                    4:'待上传二审照片',
                    7:'待上传二审照片',
                    6:'待二审',
                    8:'待签约',
                    9:'签约失败',
                    10:'正在还款',
                    11:'已还清',
                    12:'已拒绝',
                    14: '待分配外访人员',
                };
                return status[data];
            },
            getMarital: function(s) {
                var data = {
                    'married':'已婚',
                    'unmarried':'未婚',
                    'divorced':'离异',
                    'widowhood':'丧偶'
                };
                var m = '';
                if (s) {
                    m = data[s];
                }

                return m;
            },
            getContact: function (c) {
                var data = {
                    'family': '家人-其他',
                    'parent':'父母',
                    'spouse':'配偶',
                    'brothers':'兄弟姐妹',
                    'workmate':'同事',
                    'friend':'朋友',
                    'other':'其它'
                };
                var m = '';
                if(c) {
                    m = data[c];
                }
                return m;
            },
            getHouse: function (h) {
                try {
                    var house =  h['house_property'];
                    if (house == 'owned'){
                        return '自有'
                    }else if (house == 'rented') {
                        return '租住'
                    }
                    return '未填'
                } catch (error) {
                    return '未填'
                }
            },
            getRepayAmount:function () {
                var url = baseUrl + "<?= $id ?>/repayments/amount";
                var header = {
                    headers:{'X-TOKEN': this.token},
                    params: {period: $('#repaySelect').val()}
                };

                this.$http.get(url, header).then(function (data) {
                    var json = data.bodyText;
                    var usedData = JSON.parse(json);

                    this.repayAmount = usedData.data.total;
                    this.overdueAmount = this.repayAmount - usedData.data.overdueAmount;
                })
            },
            repayMany:function () {
                if (this.repayAmount == 0) {
                    layer.msg('请先计算还款金额!', {icon:2});
                    return false;
                }
                var url = baseUrl + "<?= $id ?>/repayments";
                var data = {period: $('#repaySelect').val()};

                var token = {headers:{'X-TOKEN':this.token}};
                var __this = this;
                var index = layer.confirm('确定要提前还款么', {
                    btn: ['确定','取消'] //按钮
                }, function(){
                    var loading = layer.load(0,{shade: false});
                    __this.$http.post(url, data,token).then(function(data){
                        layer.close(loading);
                        var json = data.bodyText;
                        var usedData = JSON.parse(json);
                        if(usedData['success']==true){
                            layer.msg('提前还款成功!', {icon:1});
                        }else{
                            layer.msg(usedData['data']['message'],{icon:2});
                        }
                        this.setDomData(usedData['data']);
                    },function(response){
                        layer.close(loading);
                        var json = response.bodyText;
                        var usedData = JSON.parse(json);
                        layer.msg(usedData['errors'][0]['message'], {icon:2});
                    });
                },function() {
                    layer.close(index);
                });

            },
            prepayment: function () {
                if (this.repayAmount == 0) {
                    layer.msg('请先计算还款金额!', {icon: 2});
                    return false;
                }
                var url = baseUrl + "<?= $id ?>/prepayments";
                var data = { total: $('#repaySelect').val()};

                var token = {headers: {'X-TOKEN': this.token}};
                var __this = this;
                var index = layer.confirm('真的要提前还款么?不经过银行哟!!!!!!!!', {
                    btn: ['确定', '取消']
                }, function () {
                    var loading =  layer.load(0, {shade: false});
                    __this.$http.post(url, data, token).then(function (res) {
                        layer.close(loading);
                        var json = res.bodyText;
                        var usedData = JSON.parse(json);
                        if (usedData['success']==true) {
                            layer.msg('提前还款成功!', {icon:1});
                        }
                    }, function (res) {
                        layer.close(loading);
                        var json = res.bodyText;
                        var usedData = JSON.parse(json);
                        layer.msg(usedData['errors'][0]['message'], {icon:2});
                    })
                })
            },
            repayNoOverdue:function () {
                if (this.repayAmount == 0) {
                    layer.msg('请先计算还款金额!', {icon:2});
                }
                var url = baseUrl + "<?= $id ?>/collection";
                var data = { total: $('#repaySelect').val()};

                var token = {headers: {'X-TOKEN': this.token}};
                var __this = this;
                var index = layer.confirm('真的要催收还款么? 没有滞纳金也不经过银行哟~~~~~~~', {
                    btn: ['确定', '取消']
                }, function () {
                    var loading =  layer.load(0, {shade: false});
                    __this.$http.post(url, data, token).then(function (res) {
                        layer.close(loading);
                        var json = res.bodyText;
                        var usedData = JSON.parse(json);
                        if (usedData['success']==true) {
                            layer.msg('催收还款成功!', {icon:1});
                        }
                    }, function (res) {
                        layer.close(loading);
                        var json = res.bodyText;
                        var usedData = JSON.parse(json);
                        layer.msg(usedData['errors'][0]['message'], {icon:2});
                    })
                })
            },
            cService:function() {
                var url = baseUrl + "<?= $id ?>/service";
                var token = {headers:{'X-TOKEN':this.token}};
                var __this = this;
                var index = layer.confirm('确定要取消贵宾服务包么', {
                    btn: ['确定','取消'] //按钮
                }, function(){
                    var loading = layer.load(0,{shade: false});
                    __this.$http.delete(url, token).then(function(data){
                        layer.close(loading);
                        var json = data.bodyText;
                        var usedData = JSON.parse(json);
                        if(usedData['success']==true){
                            layer.msg('取消成功!', {icon:1});
                        }else{
                            layer.msg(usedData['data']['message'],{icon:2});
                        }
                        this.setDomData(usedData['data']);
                    },function(response){
                        layer.close(loading);
                        var json = response.bodyText;
                        var usedData = JSON.parse(json);
                        layer.msg(usedData['errors'][0]['message'], {icon:2});
                    });
                },function() {
                    layer.close(index);
                });
            },
            cPack:function() {
                var url = baseUrl + "<?= $id ?>/freePack";
                var token = {headers:{'X-TOKEN':this.token}};
                var __this = this;
                var index = layer.confirm('确定要取消个人保障计划么', {
                    btn: ['确定','取消'] //按钮
                }, function(){
                    var loading = layer.load(0,{shade: false});
                    __this.$http.delete(url, token).then(function(data){
                        layer.close(loading);
                        var json = data.bodyText;
                        var usedData = JSON.parse(json);
                        if(usedData['success']==true){
                            layer.msg('取消成功!', {icon:1});
                        }else{
                            layer.msg(usedData['data']['message'],{icon:2});
                        }
                        this.setDomData(usedData['data']);
                    },function(response){
                        layer.close(loading);
                        var json = response.bodyText;
                        var usedData = JSON.parse(json);
                        layer.msg(usedData['errors'][0]['message'], {icon:2});
                    });
                },function() {
                    layer.close(index);
                });
            },
            changeBank:function () {
                var __this =  this;

                if (this.order.status < 90) {
                    this.$message({
                        type: 'error',
                        message: '还没有完成签约,不能修改银行卡'
                    });
                    return false;
                }

                var url = baseUrl + "<?= $id ?>/bank";
                this.$prompt('请输入银行卡', '修改银行卡', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                }).then(function (value) {
                    var loading = layer.load(0, {shade:false});
                    var token = {headers:{'X-TOKEN':__this.token}};
                    var data = {bankNumber:value.value};
                    __this.$http.patch(url, data, token).then(function (response) {
                        layer.close(loading);
                        console.info(response);
                        __this.$message({
                            type: 'success',
                            message: '修改成功'
                        });
                        var json = response.bodyText;
                        var usedData = JSON.parse(json);
                        __this.bank.bank_name =usedData.data.bankName;
                        __this.bank.number = value.value;
                    },function(response) {
                        layer.close(loading);
                        var json = response.bodyText;
                        var usedData = JSON.parse(json);
                        layer.msg(usedData['errors'][0]['message'], {icon:2});
                    })
                })
            }
        }
    });
</script>