<link href="/css/plugins/datapicker/datepicker3.css" rel="stylesheet">
<script src="/js/plugins/datapicker/bootstrap-datepicker.js"></script>

<div class="container-fluid">
    <div class="row">
        <form action="" method="post">
            <div class="form-group">
                <div class="col-xs-6">
                    <input class="form-control" type="text" name="o_update_time" id="o_update_time" value="<?= date('Y-m-d', $data['r_pre_repay_date'])?>">
                    <input class="hidden" type="text" name="o_order_id" value="<?= $data['r_orders_id'] ?>">
                    <input class="hidden" type="text" name="r_serial_no" value="<?= $data['r_serial_no'] ?>">
                </div>
                <div class="col-xs-1">
                    <button class="btn btn-info" type="button" id="change">修改</button>
                </div>
            </div>
        </form>
    </div>
</div>
<?= \yii\helpers\Html::jsFile('@web/js/plugins/layer/layer.min.js') ?>
<script>
    $('#o_update_time').datepicker({
        todayBtn: "linked",
        keyboardNavigation: true,
        forceParse: true,
        autoclose:true,
        format: "yyyy-mm-dd",
        todayHighlight: true,
        startDate:"<?= date('Y-m-d', $data['r_pre_repay_date'])?>",
        endDate:"<?= date('Y-m-d', strtotime('+15 day',$data['r_pre_repay_date'])) ?>"
    });
    $('#change').click(function (){
        var postData = {
            'o_update_time':$('input[name=o_update_time]').val(),
            'r_order_id':$('input[name=o_order_id]').val(),
            'r_serial_no':$('input[name=r_serial_no]').val(),
        }

       $.ajax({
           url:"<?= \yii\helpers\Url::toRoute(['/repayment/update-repay-time','order_id'=>$data['r_orders_id']]) ?>",
           type:"POST",
           dateType:"JSON",
           data:postData,
           success:function (msg) {
               if(msg.status==1){
                   layer.msg(msg.message,{icon:1});
                   history.go(-1);
               }
           },
           error:function (e) {
               layer.msg('修改失败',{icon:2});
           }
       })
    });
    //$('#o_update_time').datepicker('option', 'minDate', new Date(2017, 1 - 1, 1));
</script>
