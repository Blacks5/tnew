<?php
use \common\components\Helper;

$this->title = $model['c_customer_name'] . '借款订单';
$plist = Yii::$app->getRequest()->get('plist');
$this->params['breadcrumbs'][] = ($plist == 1) ? (['label' => '待审核借款订单', 'url' => ['wait-checklist']]) : (($plist == 2) ? (['label' => '已拒绝借款订单', 'url' => ['refuselist']]) : (['label' => '已通过借款订单', 'url' => ['index']]));
$this->params['breadcrumbs'][] = $this->title;
?>
<?= \yii\helpers\Html::cssFile('@web/css/style.css') ?>
<div class="ibox float-e-margins">
    <div class="ibox-content">
        <div class="form-horizontal m-t" id="signupForm" novalidate="novalidate">
            <!--订单信息部分-->
            <section class="content-header">
                <h2><?= $this->title; ?></h2>
                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <div>
                        <label class="col-sm-2 control-label">订单号：</label>
                        <div class="col-sm-2">
                            <p class="form-control-static"><?= $model['o_serial_id']; ?></p>
                        </div>
                    </div>
                    <div>
                        <label class="col-sm-2 control-label">使用产品：</label>
                        <div class="col-sm-2">
                            <p class="form-control-static"><?= $model['p_name']; ?></p>
                        </div>
                    </div>
                    <div>
                        <label class="col-sm-2 control-label">期数：</label>
                        <div class="col-sm-2">
                            <p class="form-control-static"><?= $model['p_period']; ?></p>
                        </div>
                    </div>
                    <div>
                        <label class="col-sm-2 control-label">月利率：</label>
                        <div class="col-sm-2">
                            <p class="form-control-static"><?= $model['p_month_rate']; ?></p>
                        </div>
                    </div>
                    <div>
                        <label class="col-sm-2 control-label">增值服务费：</label>
                        <div class="col-sm-2">
                            <p class="form-control-static"><?= $model['p_add_service_fee']; ?></p>
                        </div>
                    </div>
                    <div>
                        <label class="col-sm-2 control-label">随心包服务费：</label>
                        <div class="col-sm-2">
                            <p class="form-control-static"><?= $model['p_free_pack_fee']; ?></p>
                        </div>
                    </div>
                    <div>
                        <label class="col-sm-2 control-label">财务管理费：</label>
                        <div class="col-sm-2">
                            <p class="form-control-static"><?= $model['p_finance_mangemant_fee']; ?></p>
                        </div>
                    </div>
                    <div>
                        <label class="col-sm-2 control-label">客户管理费：</label>
                        <div class="col-sm-2">
                            <p class="form-control-static"><?= $model['p_customer_management']; ?></p>
                        </div>
                    </div>
                    <div>
                        <label class="col-sm-2 control-label">订单总价格：</label>
                        <div class="col-sm-2">
                            <p class="form-control-static"><?= $model['o_total_price']; ?></p>
                        </div>
                    </div>
                    <div>
                        <label class="col-sm-2 control-label">贷款总金额：</label>
                        <div class="col-sm-2">
                            <p class="form-control-static"><?= $model['o_total_price'] - $model['o_total_deposit']; ?></p>
                        </div>
                    </div>
                    <div>
                        <label class="col-sm-2 control-label">注释：</label>
                        <div class="col-sm-2">
                            <p class="form-control-static"><?= $model['o_remark']; ?></p>
                        </div>
                    </div>
                </div>


                <div class="hr-line-dashed"></div>
                <!--客户信息部分-->
                <div class="form-group">
                    <div>
                        <label class="col-sm-2 control-label">客户姓名：</label>
                        <div class="col-sm-2">
                            <p class="form-control-static"><?= $model['c_customer_name']; ?></p>
                        </div>
                    </div>
                    <div>
                        <label class="col-sm-2 control-label">客户电话：</label>
                        <div class="col-sm-2">
                            <p class="form-control-static"><?= $model['c_customer_cellphone']; ?></p>
                        </div>
                    </div>
                    <div>
                        <label class="col-sm-2 control-label">客户身份证：</label>
                        <div class="col-sm-2">
                            <p class="form-control-static"><?= $model['c_customer_id_card']; ?></p>
                        </div>
                    </div>
                    <div>
                        <label class="col-sm-2 control-label">户籍地址：</label>
                        <div class="col-sm-2">
                            <p class="form-control-static">
                                <?= Helper::getAddrName($model['c_customer_province']) . '-' .
                                Helper::getAddrName($model['c_customer_city']) . '-' . Helper::getAddrName($model['c_customer_county']); ?></p>
                        </div>
                    </div>
                    <div>
                        <label class="col-sm-2 control-label">性别：</label>
                        <div class="col-sm-2">
                            <p class="form-control-static"><?= $model['c_customer_gender'] == 1 ? '男' : '女'; ?></p>
                        </div>
                    </div>
                    <div>
                        <label class="col-sm-2 control-label">还款银行：</label>
                        <div class="col-sm-2">
                            <p class="form-control-static"><?= Helper::getBankNameById($model['c_bank']); ?></p>
                        </div>
                    </div>
                    <div>
                        <label class="col-sm-2 control-label">还款银行卡号：</label>
                        <div class="col-sm-2">
                            <p class="form-control-static"><?= $model['c_banknum']; ?></p>
                        </div>
                    </div>
                    <div>
                        <label class="col-sm-2 control-label">QQ号：</label>
                        <div class="col-sm-2">
                            <p class="form-control-static"><?= $model['c_customer_qq']; ?></p>
                        </div>
                    </div>
                    <div>
                        <label class="col-sm-2 control-label">微信号：</label>
                        <div class="col-sm-2">
                            <p class="form-control-static"><?= $model['c_customer_wechat']; ?></p>
                        </div>
                    </div>
                    <div>
                        <label class="col-sm-2 control-label">Email：</label>
                        <div class="col-sm-2">
                            <p class="form-control-static"><?= $model['c_customer_email']; ?></p>
                        </div>
                    </div>
                    <div>
                        <label class="col-sm-2 control-label">婚姻状况：</label>
                        <div class="col-sm-2">
                            <p class="form-control-static"><?= Helper::getMaritalStatusString($model['c_family_marital_status']); ?></p>
                        </div>
                    </div>
                    <div>
                        <label class="col-sm-2 control-label">配偶姓名：</label>
                        <div class="col-sm-2">
                            <p class="form-control-static"><?= $model['c_family_marital_partner_name']; ?></p>
                        </div>
                    </div>
                    <div>
                        <label class="col-sm-2 control-label">配偶电话：</label>
                        <div class="col-sm-2">
                            <p class="form-control-static"><?= $model['c_family_marital_partner_cellphone']; ?></p>
                        </div>
                    </div>
                    <div>
                        <label class="col-sm-2 control-label">住房情况：</label>
                        <div class="col-sm-2">
                            <p class="form-control-static"><?= Helper::getHouseInfoString($model['c_family_house_info']); ?></p>
                        </div>
                    </div>
                    <div>
                        <label class="col-sm-2 control-label">亲属：</label>
                        <div class="col-sm-2">
                            <p class="form-control-static"><?= Helper::getKindShipString($model['c_kinship_relation']) . '-' . $model['c_kinship_name'] . '-' . $model['c_kinship_cellphone'] . '-' . $model['c_kinship_addr']; ?></p>
                        </div>
                    </div>
                    <div>
                        <label class="col-sm-2 control-label">现居住地址：</label>
                        <div class="col-sm-2">
                            <p class="form-control-static"><?= Helper::getAddrName($model['c_customer_addr_province']) . Helper::getAddrName($model['c_customer_addr_city']) . '-' . Helper::getAddrName($model['c_customer_addr_county']) . '-' . $model['c_customer_addr_detail']; ?></p>
                        </div>
                    </div>
                    <div>
                        <label class="col-sm-2 control-label">单位地址：</label>
                        <div class="col-sm-2">
                            <p class="form-control-static"><?= Helper::getAddrName($model['c_customer_jobs_province']) . '-' . Helper::getAddrName($model['c_customer_jobs_city']) . '-' . Helper::getAddrName($model['c_customer_jobs_county']) . '-' . $model['c_customer_jobs_detail_addr']; ?></p>
                        </div>
                    </div>
                    <div>
                        <label class="col-sm-2 control-label">工作单位：</label>
                        <div class="col-sm-2">
                            <p class="form-control-static"><?= $model['c_customer_jobs_company'] . '-' . Helper::getCompanyIndustryString($model['c_customer_jobs_industry']) . '-' . $model['c_customer_jobs_section'] . '-' . $model['c_customer_jobs_title'] . '-' . Helper::getCompanyTypeString($model['c_customer_jobs_type']); ?></p>
                        </div>
                    </div>
                    <div>
                        <label class="col-sm-2 control-label">其他联系人：</label>
                        <div class="col-sm-2">
                            <p class="form-control-static"><?= Helper::getKindShipString($model['c_other_people_relation']) . '-' . $model['c_other_people_name'] . '-' . $model['c_other_people_cellphone']; ?></p>
                        </div>
                    </div>
                    <div>
                        <label class="col-sm-2 control-label">总借款次数：</label>
                        <div class="col-sm-2">
                            <p class="form-control-static"><?= $model['c_total_borrow_times']; ?></p>
                        </div>
                    </div>
                    <div>
                        <label class="col-sm-2 control-label">总借款金额：</label>
                        <div class="col-sm-2">
                            <p class="form-control-static"><?= $model['c_total_money']; ?></p>
                        </div>
                    </div>
                </div>


                <div class="hr-line-dashed"></div>


                <?php if ((int)$model['o_status'] === \common\models\Orders::STATUS_WAIT_CHECK) { ?>
                    <div class="form-group">
                        <div class="col-sm-8 col-sm-offset-3">
                            <button class="btn btn-success" onclick="verify()">通过</button>
                            <button class="btn btn-info" onclick="verifycancel()">取消</button>
                            <button class="btn btn-danger" onclick="verifyRefuse()">拒绝</button>
                        </div>
                    </div>
                <?php } ?>
        </div>
    </div>
</div>
<?= \yii\helpers\Html::jsFile('@web/js/plugins/layer/layer.min.js') ?>
<script>
    /**
     * 取消
     */
    function verifycancel() {
        layer.confirm('确认取消该借款订单？',
            {
                icon: 3, btn: ['确定', '取消'],
                btn1: function (index) {
                    var loading = layer.load();
                    $.ajax({
                        url: "<?=Yii::$app->getUrlManager()->createUrl(['borrowlist/verify-cancel', 'order_id' => $model['o_id']])?>",
                        type: 'post',
                        dataType: 'json',
                        success: function (data) {
                            if (data.status == 1) {
                                return layer.msg(data.message, {icon: 2, time: 2000}, function () {
                                    window.location.reload()
                                });
                            }
                            return layer.msg(data.message, {icon: 5});
                        },
                        error: function () {
                            return layer.alert('噢，我崩溃啦', {title: '系统错误', icon: 5});
                        },
                        complete: function () {
                            layer.close(loading);
                        },
                    });
                },
                btn2: function (index) {
                    layer.close(index)
                },
            });
    }
    /**
     * 通过
     */
    function verify() {
        layer.confirm('确认通过该借款订单？',
            {
                icon: 3, btn: ['确定', '取消'],
                btn1: function (index) {
                    var loadind = layer.load();
                    var url = "<?= \yii\helpers\Url::toRoute(['borrow/verift-pass', 'order_id' => $model['o_id']])?>";
                    window.location.href = url;
                },
                btn2: function (index) {
                    layer.close(index)
                },
            });
    }
    /**
     * 拒绝
     */
    function verifyRefuse() {
        layer.prompt({
            formType: 2,
            title: '请填写拒绝原因'
        }, function (value, index, elem) {
            var loading = layer.load();
            $.ajax({
                url: "<?=Yii::$app->getUrlManager()->createUrl(['borrowlist/refuse', 'o_id' => $model['o_id']])?>",
                type: 'post',
                dataType: 'json',
                data: {remark: value, _csrf: "<?= Yii::$app->getRequest()->getCsrfToken();?>"},
                success: function (data) {
                    if (data.status === 0) {
                        return layer.alert(data.message, {icon: 2});
                    }
                    if (data.status === 1) {
                        return layer.confirm(data.message,
                            {
                                icon: 1, btn: ['继续审核', '确认'],
                                btn1: function (index) {
                                    window.location.href = "<?=Yii::$app->getUrlManager()->createUrl(['borrowlist/wait-checklist'])?>";
                                    layer.close(index)
                                },
                                btn2: function (index) {
                                    return window.location.reload();
                                },
                            }
                        );
                    }
                },
                error: function () {
                    layer.alert('噢，我崩溃啦', {title: '系统错误', icon: 5});
                },
                complete: function () {
                    layer.close(loading);
                },
            });
            layer.close(index);
        });

    }
</script>