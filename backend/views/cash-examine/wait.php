<script src="/js/vue.js"></script>
<script src="/js/vue-resource.js"></script>
<link href="/css/plugins/datapicker/datepicker3.css" rel="stylesheet">
<script src="/js/plugins/datapicker/bootstrap-datepicker.js"></script>
<script src="/js/plugins/layer/layer.min.js"></script>

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
                        <input type="text" class="form-control" name="sTime"
                               :value="params['sTime']" placeholder="开始时间">
                        <span class="input-group-addon ">到</span>
                        <input type="text" class="form-control" name="eTime"
                               :value="params['eTime']" placeholder="结束时间">
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group">
                        <button type="input" class="btn btn-info" @click="toSearch"><i class="fa fa-search" ></i>查询</button>
                    </div>
                </div>
            <script>
                $('#datepicker').datepicker({
                    todayBtn: "linked",
                    keyboardNavigation: true,
                    forceParse: true,
                    autoclose:true,
                    format: "yyyy-mm-dd",
                    todayHighlight: true
                });
            </script>
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
                                    <th class="client-status">申请金额</th>
                                    <th>申请期数</th>
                                    <th>还款周期</th>
                                    <th>申请时间</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="value in dataList">
                                        <td class="client-avatar">{{value.orders_number}}</td>
                                        <td>{{value.name}}</td>
                                        <td>{{value.phone}}</td>
                                        <td>{{value.expected_amount}}</td>
                                        <td class="client-status">{{value.period_total}}</td>
                                        <td class="client-status">{{value.repay_cycle == 'week'?'周':'月'}}</td>
                                        <td class="client-status">{{value.created_at}}</td>
                                        <td class="client-status"><a class="btn btn-info btn-xs" @click="open(value.id)">详情</a> </td>
                                        <td>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                        <!--分页-->
                        <ul class="pagination">
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
    var dataReturn;
    Vue.use(VueResource);
    new Vue ({
        el: '#list',
        data: {
            dataList: '',
            params: '',
            pageCount: 1,
            pageIndex:''
        },
        created: function () {
            this.toSearch();
        },
        methods: {
            toSearch:function (){
                var url = "http://cash.app/v1/orders/examine/<?= $examine ?>";
                var header = {
                    headers:{'X-TOKEN':'123123123123123'},
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
                    this.pageCount = usedData['data']['list']['last_page'];
                    this.pageIndex = usedData['data']['list']['current_page'];
                },function (response){
                    console.log(response['body']['errors']);
                    layer.msg(response['body']['errors'][0]['message'],{icon:2})
                })
            },
            pageLeft:function(){
                if(this.pageIndex == 1 || this.pageIndex == 'null'){
                    layer.msg('已经是第一页了!',{icon:2});
                    return false;
                }
                this.pageIndex--;
                this.toSearch();
            },
            pageRight:function(){
                if(this.pageCount == this.pageIndex || this.pageIndex == 'null'){
                    layer.msg('已经是最后一页了!',{icon:2});
                    return false;
                }
                this.pageIndex ++;
                this.toSearch();
            },
            open:function(id){
                layer.open({
                    type: 2,
                    title: false,
                    shadeClose:true,
                    shade: [0.8],
                    area: ['1000px', '800px'],
                    content: '/page'
                })
            }
        }
    });
</script>