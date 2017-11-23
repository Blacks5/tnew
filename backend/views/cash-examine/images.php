<script src="/js/vue.js"></script>
<script src="/js/vue-resource.js"></script>
<script src="/js/plugins/layer/layer.min.js"></script>
<style>
    body{background-color: white;}
    .height{height:60px;padding:20px 0;}
</style>
<div class="ibox float-e-margins">
    <div class="ibox-content">
        <div class="form-horizontal m-t" id="signupForm" novalidate="novalidate">
            <div class="container-f" id="list">
                <div class="row" >
                    <div class="col-sm-3" v-for="value in lists">
                        <a class="thumbnail" @click="show(value.url)">
                            <img :src="value.url" :rel="value.name">
                            <div class="caption">
                                <h3 class="text-center">{{value.name}}</h3>
                            </div>
                        </a>
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
           baseUrl: "<?= Yii::$app->params['cashBaseUrl'] ?>",
           token:window.sessionStorage.getItem('V2_TOKEN'),
           lists:[],
            loading:''
        },
        created: function () {
            this.loading = layer.loading(0,{shade: false});
            this.toSearch();
        },
        methods: {
            toSearch:function(){
                var url = this.baseUrl + "<?= $orderID ?>/images";
                var header = {headers:{'X-TOKEN':this.token}};

                this.$http.get(url, header).then(function (data) {
                    var json = data.bodyText;
                    var usedData = JSON.parse(json);

                    this.lists = usedData['data'];
                    layer.close(this.loading);
                }, function (response) {
                    console.log(response['body']['errors']);
                    layer.msg(response['body']['errors'][0]['message'], {icon: 2})
                });
            },
            show:function (url){
                layer.open({
                    type: 1,
                    title: false,
                    shadeClose:true,
                    shade: [0.8],
                    content: "<img src='"+url+"'>"

                })
            }
        }


    })
</script>