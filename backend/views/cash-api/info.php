<script src="/js/vue.js"></script>
<script src="/js/vue-resource.js"></script>
<script src="/js/plugins/layer/layer.min.js"></script>
<style>
    body{background-color: white;}
    .height{height:60px;padding:20px 0;}
</style>
<div class="ibox float-e-margins" style="background-color: white">
    <div class="ibox-content">
        <div class="form-horizontal m-t" id="signupForm" novalidate="novalidate">
            <div class="container-f" id="list">
                <h1 class="text-center" v-if="url == 'deduct-signs'">{{json(data.bank_card)['user_name']}}的放款详情</h1>
                <h1 class="text-center" v-if="url == 'loans'">{{json(data.bank_card)['user_name']}}的放款详情</h1>
                <h1 class="text-center" v-if="url == 'deducts'">{{json(data.bank_card)['user_name']}}的放款详情</h1>
                <div class="row">
                    <div class="list-group">
                        <a class="list-group-item col-sm-4">订单编号<span class="badge">{{data.order_number}}</span></a>
                        <a class="list-group-item col-sm-4">第三方ID<span class="badge">{{data.thirdparty_id}}</span></a>
                        <a class="list-group-item col-sm-4">借款人姓名<span class="badge">{{json(data.bank_card)['user_name']}}</span></a>
                        <a class="list-group-item col-sm-4">身份证号<span class="badge">{{json(data.identification_card)['number']}}</span></a>
                        <a class="list-group-item col-sm-4">银行卡号码<span class="badge">{{json(data.bank_card)['number']}}</span></a>
                        <a class="list-group-item col-sm-4">手机号码<span class="badge">{{json(data.bank_card)['phone']}}</span></a>
                        <a class="list-group-item col-sm-4">操作人<span class="badge">{{data.name}}</span></a>
                        <a class="list-group-item col-sm-4">借款金额<span class="badge">{{data.accepted_amount}}</span></a>
                        <a class="list-group-item col-sm-4"
                            <?php if(Yii::$app->getUser()->can(yii\helpers\Url::toRoute('cash-api/change'))){ ?>
                           @click="change"
                           <?php } ?>
                        >状态<span class="badge">{{data.signText}}</span></a>
                    </div>
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
           baseUrl:"<?= Yii::$app->params['cashBaseUrl'] ?>",
           token: window.sessionStorage.getItem('V2_TOKEN'),
           data:[],
           url: "<?= $url ?>"
       },
       created: function (){
           var url = this.baseUrl + "<?= $id ?>/<?= $url ?>";
           var header = {
               headers:{'X-TOKEN':this.token},
               params: {

               }

           };

           this.$http.get(url, header).then(function (data) {
               var json = data.bodyText;
               var usedData = JSON.parse(json);

               this.data = usedData['data'];
           }, function (response) {
               console.log(response['body']['errors']);
               layer.msg(response['body']['errors'][0]['message'], {icon: 2})
           });
       },
        methods: {
           json: function (data){
               var isJson = typeof(data) == "object" && Object.prototype.toString.call(data).toLowerCase() == "[object object]" && !data.length;
               if(isJson){
                   return data;
               }else{
                   return JSON.parse(data);
               }
           },
           change: function ()
           {
               var __this = this;
               var url = this.baseUrl + "<?= $id ?>/status";
               var data = {action:"<?= $url ?>"};
               var token = {headers:{'X-TOKEN':this.token}};
               var index = layer.msg('确定修改状态么?',{
                   btn:['确定','取消'],
                   btn1:function (){
                       __this.$http.patch(url, data,token).then(function(data){
                           var json = data.bodyText;
                           var usedData = JSON.parse(json);

                           layer.msg(usedData['data'],{icon:1});
                       },function(response){
                           layer.close(loading);
                           var json = response.bodyText;
                           var usedData = JSON.parse(json);
                           layer.msg(usedData['errors'][0]['message'], {icon:2});
                       });
                   },
                   btn2:function (){
                       layer.close(index);
                   }
               })
           }
        }
    });
</script>