<?php
use \common\components\Helper;
$all_status = \common\models\Orders::getAllStatus();
$msg = $all_status[$model['o_status']];
$this->title = $model['c_customer_name'] . '借款详情【'. $msg. '】';
?>
<?= \yii\helpers\Html::cssFile('@web/css/style.css') ?>
<style>
    .center{
        text-align: center;
    }
    .color-orange{
        color: orangered;
    }

</style>
<div class="ibox float-e-margins">
    <div class="ibox-content">
        <div class="form-horizontal m-t" id="signupForm" novalidate="novalidate">
            <!--订单信息部分-->
            <section class="content-header">
                <h2 class="center"><?= $this->title; ?></h2>

                <h3 class="center color-orange">订单信息</h3>
                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <div>
                        <label class="col-sm-2 control-label">订单编号：</label>
                        <div class="col-sm-2">
                            <p class="form-control-static"><?= $model['o_serial_id']; ?></p>
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
                        <label class="col-sm-2 control-label">月供：</label>
                        <div class="col-sm-2">
                            <p class="form-control-static"
                               style="color: orangered"><?= round($model['month_repayment'], 2); ?> 元/月</p>
                        </div>
                    </div>

                    <div>
                        <label class="col-sm-2 control-label">个人保障计划：</label>
                        <div class="col-sm-2">
                            <p class="form-control-static"
                               style="color: orangered"><?= $model['o_is_add_service_fee']==1?"是":"否"; ?></p>
                        </div>
                    </div>
                    <div>
                        <label class="col-sm-2 control-label">贵宾服务包：</label>
                        <div class="col-sm-2">
                            <p class="form-control-static"
                               style="color: orangered"><?=$model['o_is_free_pack_fee']==1?"是":"否"; ?></p>
                        </div>
                    </div>
                    <div>
                        <label class="col-sm-2 control-label">是否代扣：</label>
                        <div class="col-sm-2">
                            <p class="form-control-static"
                               style="color: orangered"><?=$model['o_is_auto_pay']==1?"是":"否"; ?></p>
                        </div>
                    </div>

                    <div>
                        <label class="col-sm-2 control-label">备注：</label>
                        <div class="col-sm-2">
                            <p class="form-control-static"><?= $model['o_remark']?:"无"; ?></p>
                        </div>
                    </div>
                    <div>
                        <label class="col-sm-2 control-label">图片：</label>
                        <div class="col-sm-2">
                            <a href="/borrow/showpics?oid=<?= $model['o_id'] ?>"><p class="form-control-static">点击查看</p>
                            </a>
                        </div>
                    </div>

                    <?php if(($model['o_status'] == 3)||($model['o_status'] == 4)){ ?>
                        <div>
                            <label class="col-sm-2 control-label">
                                <?php if($model['o_status'] == 3){ ?>
                                    撤销原因：
                                <?php }elseif($model['o_status'] == 4){ ?>
                                    取消原因：
                                <?php } ?>
                            </label>
                            <div class="col-sm-2">
                                <p class="form-control-static"><?= $model['o_operator_remark']?:"无"; ?></p>
                            </div>
                        </div>
                    <?php } ?>
                </div>



                <h3 class="center color-orange">所用产品信息</h3>
                <div class="hr-line-dashed"></div>
                <div class="form-group">
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
                        <label class="col-sm-2 control-label">月利率(%)：</label>
                        <div class="col-sm-2">
                            <p class="form-control-static"><?= $model['p_month_rate']; ?></p>
                        </div>
                    </div>
                    <div>
                        <label class="col-sm-2 control-label">个人保障计划(元/每月)：</label>
                        <div class="col-sm-2">
                            <p class="form-control-static"><?= $model['o_is_add_service_fee']==1?$model['p_add_service_fee']:0; ?></p>
                        </div>
                    </div>
                    <div>
                        <label class="col-sm-2 control-label">贵宾服务包(%)：</label>
                        <div class="col-sm-2">
                            <p class="form-control-static"><?= $model['o_is_free_pack_fee']==1?$model['p_free_pack_fee']:0; ?></p>
                        </div>
                    </div>
                    <div>
                        <label class="col-sm-2 control-label">财务管理费(%)：</label>
                        <div class="col-sm-2">
                            <p class="form-control-static"><?= $model['p_finance_mangemant_fee']; ?></p>
                        </div>
                    </div>
                    <div>
                        <label class="col-sm-2 control-label">客户管理费(%)：</label>
                        <div class="col-sm-2">
                            <p class="form-control-static"><?= $model['p_customer_management']; ?></p>
                        </div>
                    </div>
                    <div>
                        <label class="col-sm-2 control-label">商户服务费：</label>
                        <div class="col-sm-2">
                            <p class="form-control-static"><?= $model['o_service_fee']; ?></p>
                        </div>
                    </div>
                    <div>
                        <label class="col-sm-2 control-label">查询费：</label>
                        <div class="col-sm-2">
                            <p class="form-control-static"><?= $model['o_inquiry_fee']; ?></p>
                        </div>
                    </div>
                </div>


                <h3 class="center color-orange">客户信息</h3>
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
                            <p class="form-control-static">
                                <a href="tel:<?= $model['c_customer_cellphone']; ?>"><?= $model['c_customer_cellphone']; ?></a>
                            </p>
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
                                Helper::getAddrName($model['c_customer_city']) . '-' . Helper::getAddrName($model['c_customer_county']) . '-' . $model['c_customer_idcard_detail_addr']; ?></p>
                        </div>
                    </div>
                    <div>
                        <label class="col-sm-2 control-label">性别：</label>
                        <div class="col-sm-2">
                            <p class="form-control-static"><?= $model['c_customer_gender'] == 1 ? '男' : '女'; ?></p>
                        </div>
                    </div>
                    <!--<div>
                        <label class="col-sm-2 control-label">还款银行：</label>
                        <div class="col-sm-2">
                            <p class="form-control-static"><?/*= Helper::getBankNameById($model['c_bank']); */?></p>
                        </div>
                    </div>
                    <div>
                        <label class="col-sm-2 control-label">还款银行卡号：</label>
                        <div class="col-sm-2">
                            <p class="form-control-static"><?/*= $model['c_banknum']; */?></p>
                        </div>
                    </div>-->
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
                    <!--<div>
                            <label class="col-sm-2 control-label">Email：</label>
                            <div class="col-sm-2">
                                <p class="form-control-static"><? /*= $model['c_customer_email']; */ ?></p>
                            </div>
                        </div>-->
                    <div>
                        <label class="col-sm-2 control-label">婚姻状况：</label>
                        <div class="col-sm-2">
                            <p class="form-control-static"><?= Helper::getMaritalStatusString($model['c_family_marital_status']); ?></p>
                        </div>
                    </div>

                    <?php /*已婚才显示这两个*/if(2==$model['c_family_marital_status']){ ?>
                        <div>
                            <label class="col-sm-2 control-label">配偶姓名：</label>
                            <div class="col-sm-2">
                                <p class="form-control-static"><?= $model['c_family_marital_partner_name']; ?></p>
                            </div>
                        </div>
                        <div>
                            <label class="col-sm-2 control-label">配偶电话：</label>
                            <div class="col-sm-2">
                                <p class="form-control-static">
                                    <a href="tel:<?= $model['c_family_marital_partner_cellphone']; ?>"><?= $model['c_family_marital_partner_cellphone']; ?></a>
                                </p>
                            </div>
                        </div>
                    <?php } ?>


                    <div>
                        <label class="col-sm-2 control-label">住房情况：</label>
                        <div class="col-sm-2">
                            <p class="form-control-static"><?= Helper::getHouseInfoString($model['c_family_house_info']); ?></p>
                        </div>
                    </div>
                    <div>
                        <label class="col-sm-2 control-label">亲属：</label>
                        <div class="col-sm-2">
                            <p class="form-control-static"><?= Helper::getKindShipString($model['c_kinship_relation']) . '-' . $model['c_kinship_name'] . '-' . $model['c_kinship_cellphone'] /*. '-' . $model['c_kinship_addr']*/
                                ; ?></p>
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

                    <div>
                        <label class="col-sm-2 control-label">还款卡号信息：</label>
                        <div class="col-sm-2">
                            <p id="bank_info" class="form-control-static">
                                <?= Helper::getBankNameById($model['c_bank']);/*银行名*/ ?><br>
                                <?=$model['c_banknum'] ; /*卡号*/ ?>
                            </p>

                            <?php if(Yii::$app->getUser()->can(Yii::$app->getUrlManager()->createUrl(['customer/change-bank-info']))){ ?>
                                <button id="changeBankInfo" class="btn btn-danger btn-xs">修改</button>
                            <?php } ?>
                        </div>
                    </div>
                    <div>
                        <label class="col-sm-2 control-label">工作单位：</label>
                        <div class="col-sm-2">
                            <p class="form-control-static">
                                <?= $model['c_customer_jobs_company'];/*公司名*/ ?><br>
                                <?=Helper::getCompanyIndustryString($model['c_customer_jobs_industry']) ; /*行业*/ ?><br>
                                <?=$model['c_customer_jobs_section'] ; /*部门*/ ?><br>
                                <?=$model['c_customer_jobs_title']; /*职位*/ ?><br>
                                <?=Helper::getCompanyTypeString($model['c_customer_jobs_type']); /*工作行业*/ ?> <br>
                                <a href="tel:<?=$model['c_customer_jobs_phone'];?>"><?=$model['c_customer_jobs_phone']; /*工作座机*/ ?></a>
                            </p>
                        </div>
                    </div>


                </div>

                <!--修改银行卡信息弹窗-->
                <div id="pop_change_bank_info" class="ibox float-e-margins" style="display: none">
                    <div class="ibox-content">
                        <form class="form-horizontal" id="pop_form_data">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">选择银行：</label>

                                <div class="col-sm-8">
                                    <select class="form-control" name="c_bank" id="">
                                        <?php foreach (Yii::$app->params['bank_list'] as $v){ ?>
                                            <option value="<?=$v['bank_id']?>"><?=$v['bank_name']?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">银行卡号：</label>

                                <div class="col-sm-8">
                                    <input type="text" placeholder="请输入银行卡号" class="form-control" name="c_banknum">
                                </div>
                            </div>

                            <!--上传银行卡照片-->
                            <div class="form-group">
                                <label class="col-sm-3 control-label">银行卡照片：</label>
                                <input type="hidden" name="oi_front_bank">
                                <div class="col-sm-8">
                                    <div class="wraper">
                                        <ul id="file-list-oi_front_bank" class="file-list">
                                            <!--                                            <li>-->
                                            <?php
                                            /*                                                $t = new \common\models\UploadFile();
                                                                                            if($model->id_card_pic_one){ */?><!--
                                                    <?/*= \yii\helpers\Html::img($t->getUrl($model->id_card_pic_one)); */?>
                                                <?php /*}else{ */?>
                                                    <?/*= \yii\helpers\Html::img('@web/img/image.png'); */?>
                                                --><?php /*} */?>
                                            <?= \yii\helpers\Html::img('@web/img/image.png'); ?>
                                            <!--                                            </li>-->


                                        </ul>
                                        <div class="btn-wraper">
                                            <input type="button" value="选择文件..." id="browse-oi_front_bank"/>
                                            <button id="start_upload_oi_front_bank" type="button">开始上传</button>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </form>



                    </div>
                </div>


                <h3 class="center color-orange">商户信息</h3>
                <div class="hr-line-dashed"></div>
                <!--商户信息-->
                <div class="form-group">
                    <div>
                        <label class="col-sm-2 control-label">商户名：</label>
                        <div class="col-sm-2">
                            <p class="form-control-static"><?= $model['s_name'] . '(' . "{$model['s_owner_phone']}" . ')'; ?></p>
                        </div>
                    </div>
                    <div>
                        <label class="col-sm-2 control-label">业务员：</label>
                        <div class="col-sm-2">
                            <p class="form-control-static"><?= $model['realname'] . '(' . "{$model['cellphone']}" . ')'; ?></p>
                        </div>
                    </div>
                    <div>
                        <label class="col-sm-2 control-label">审核人员：</label>
                        <div class="col-sm-2">
                            <p class="form-control-static"><?= $model['o_operator_realname'] ; ?></p>
                        </div>
                    </div>
                </div>

                <h3 class="center color-orange">商品信息</h3>
                <div class="hr-line-dashed"></div>

                <!--商品信息-->
                <?php 
                $goods_types = array_combine(array_column(\Yii::$app->params['goods_type'], 't_id') , array_column(\Yii::$app->params['goods_type'], 't_name'));
                ?>
                <?php foreach ($goods_data as $v) { ?>
                    <div class="form-group">
                        <div>
                            <label class="col-sm-2 control-label">商品类型：</label>
                            <div class="col-sm-2">
                                <p class="form-control-static"><?= $goods_types[$v['g_goods_type']]; ?></p>
                            </div>
                        </div>
                        <div>
                            <label class="col-sm-2 control-label">商品品牌型号：</label>
                            <div class="col-sm-2">
                                <p class="form-control-static"><?= $v['g_goods_name'] . '(' . "{$v['g_goods_models']}" . ')'; ?></p>
                            </div>
                        </div>
                        <div>
                            <label class="col-sm-2 control-label">商品代码：</label>
                            <div class="col-sm-2">
                                <p class="form-control-static" id="product_code"><?= $model['o_product_code'] ? $model['o_product_code'] : '暂未填写'; ?></p>
                                <p class="form-control-static"><button class="btn btn-info btn-xs" id="update-product">修改</button> </p>
                            </div>
                        </div>
                        <script>
                            $('#update-product').click(function(){
                                var value = $('#product_code').html();
                                var html = "<input type='text' class='form-control' id='o_product_code' value='"+value+"' autofocus>";
                                $(this).hide();
                                $('#product_code').html(html);
                            });
                            $(document).on('blur','#o_product_code',function(){
                                var url = "<?= \yii\helpers\Url::toRoute('/borrownew/update-product-code') ?>";
                                var value = $('#o_product_code').val();
                                var postData = {
                                    'o_product_code': $('#o_product_code').val(),
                                    'o_id':"<?= $model['o_id'] ?>"
                                };
                                $.post(url, postData, function(data){
                                    if(data.status==1){
                                        layer.msg('修改商品代码成功',{icon:1});
                                        $('#product_code').html(value);
                                        $('#update-product').show();
                                    }else{
                                        layer.msg('修改失败!',{icon:2});
                                    }
                                })
                            })
                        </script>
                    </div>
                <?php } ?>
                <?php if($loan_data){ ?>
                    <h3 class="center color-orange">代发记录详情</h3>
                    <div class="hr-line-dashed"></div>
                    <!--代发记录详情-->
                    <div class="form-group">
                        <div>
                            <label class="col-sm-2 control-label">代发流水号：</label>
                            <div class="col-sm-2">
                                <p class="form-control-static"><?= $loan_data['contractNo']; ?></p>
                            </div>
                        </div>
                        <div>
                            <label class="col-sm-2 control-label">代发金额：</label>
                            <div class="col-sm-2">
                                <p class="form-control-static"><?= $loan_data['amount']; ?></p>
                            </div>
                        </div>
                        <div>
                            <label class="col-sm-2 control-label">实际代发金额：</label>
                            <div class="col-sm-2">
                                <p class="form-control-static"><?= $loan_data['realRemittanceAmount']; ?></p>
                            </div>
                        </div>
                        <div>
                            <label class="col-sm-2 control-label">代发手续费：</label>
                            <div class="col-sm-2">
                                <p class="form-control-static"><?= $loan_data['chargeAmount']; ?></p>
                            </div>
                        </div>
                        <div>
                            <label class="col-sm-2 control-label">代发操作时间：</label>
                            <div class="col-sm-2">
                                <p class="form-control-static"><?= date('Y-d-m H:i:s',$loan_data['created_at']); ?></p>
                            </div>
                        </div>
                        <div>
                            <label class="col-sm-2 control-label">操作人员：</label>
                            <div class="col-sm-2">
                                <p class="form-control-static"><?= $loan_data['y_operator_realname'] ; ?></p>
                            </div>
                        </div>
                    </div>
                <?php } ?>

                <?php if ((int)$model['o_status'] === \common\models\Orders::STATUS_WAIT_CHECK) { ?>
                    <div class="form-group">
                        <div class="col-sm-8 col-sm-offset-3">
                            <button class="btn btn-xs btn-default" onclick="window.history.back()">返回上一页</button>
                            <?php if (Yii::$app->getUser()->can(yii\helpers\Url::toRoute(['borrow/verify-pass-first']))) { ?>
                                <button class="btn btn-success verify-first">初审通过</button>
                            <?php } ?>
                            <?php if (Yii::$app->getUser()->can(yii\helpers\Url::toRoute(['borrow/verify-cancel']))) { ?>
                                <button class="btn btn-info cancel">取消订单</button>
                            <?php } ?>
                            <?php if (Yii::$app->getUser()->can(yii\helpers\Url::toRoute(['borrow/verify-refuse']))) { ?>
                                <button class="btn btn-danger refuse">拒绝并拉黑</button>
                            <?php } ?>
                            <?php if (Yii::$app->getUser()->can(yii\helpers\Url::toRoute(['borrow/verify-failpic']))) { ?>
                                <button class="btn btn-danger failpic">照片不合格</button>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>

                <!--终审-->
                <?php if ((int)$model['o_status'] === \common\models\Orders::STATUS_WAIT_CHECK_AGAIN) { ?>
                    <div class="form-group">
                        <div class="col-sm-8 col-sm-offset-3">
                            <button class="btn btn-xs btn-default" onclick="window.history.back()">返回上一页</button>
                            <?php if (Yii::$app->getUser()->can(yii\helpers\Url::toRoute(['Jun/a']))) { ?>
                                <?php if(!isset($jzq_sign_log['applyNo'])){ ?>
                                    <button class="btn btn-success" id="jun-a">发起签约</button>
                                <?php } ?>
                            <?php } ?>
<!--                            --><?php //if (Yii::$app->getUser()->can(yii\helpers\Url::toRoute(['Jun/a7']))) { ?>
<!--                                --><?php //if($jzq_sign_log['signStatus'] == 0){ ?>
<!--                                    <button class="btn btn-success" id="jun-a7">发送签约短信</button>-->
<!--                                --><?php //} ?>
<!--                            --><?php //} ?>
                            <?php if (Yii::$app->getUser()->can(yii\helpers\Url::toRoute(['borrow/verify-pass']))) { ?>
                                <button class="btn btn-success verify-end">终审放款</button>
                            <?php } ?>
                            <?php if (Yii::$app->getUser()->can(yii\helpers\Url::toRoute(['borrow/verify-cancel']))) { ?>
                                <button class="btn btn-info cancel">取消订单</button>
                            <?php } ?>
                            <?php if (Yii::$app->getUser()->can(yii\helpers\Url::toRoute(['borrow/verify-refuse']))) { ?>
                                <button class="btn btn-danger refuse">拒绝并拉黑</button>
                            <?php } ?>
                            <?php if (Yii::$app->getUser()->can(yii\helpers\Url::toRoute(['borrow/verify-failpic']))) { ?>
                                <button class="btn btn-danger failpic">照片不合格</button>
                            <?php } ?>
                            <?php if (Yii::$app->getUser()->can(yii\helpers\Url::toRoute(['borrow/edit-product-code']))) { ?>

                            <?php } ?>
                        </div>
                    </div>

                <?php } ?>

                <?php if ((int)$model['o_status'] === \common\models\Orders::STATUS_PAYING){ ?>
                    <div class="form-group">
                        <div class="col-sm-8 col-sm-offset-3">
                            <?php if($model['o_status'] == 10 && $isRepayment == 0){ ?>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <select class="col-md-8 form-control" id="period_num">
                                            <?php if($isOverdue == 0){ ?>
                                                <?php for($i = 0;$i < $repayCount;$i++) { ?>
                                                    <option value="<?php echo $i+1; ?>">未还款的前<?php echo $i+1; ?>期</option>
                                                <?php } ?>
                                            <?php }else{ ?>
                                                <option value="<?php echo $repayCount; ?>">有逾期,共<?php echo $repayCount; ?>期需还</option>
                                            <?php } ?>
                                        </select>
                                        <span class="btn btn-info input-group-addon" id="calculation_residual_loan">计算金额</span>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="col-md-12 input-group">
                                        <input type="text" value="0" id="calculation_residual_loan_val" class="form-control" disabled/>
                                        <span class="input-group-addon" id="sizing-addon1">元</span>
                                    </div>
                                </div>
                                <div class="com-md-8">
                                    <button class="btn btn-danger" id="prepayment">提前还款</button>
                                    <?php }?>
                                    <?php if($model['o_is_add_service_fee'] == 1 && $canCancel == 1){ ?>
                                        <button class="btn btn-danger" id="cancel_personal_protection">取消个人保障计划</button>
                                    <?php }?>
                                    <?php if($model['o_is_free_pack_fee'] == 1 && $canCancel == 1){ ?>
                                        <button class="btn btn-danger" id="cancel_vip_pack">取消贵宾服务包</button>
                                    <?php }?>
                                </div>
                        </div>
                    </div>
                <?php } ?>
        </div>
    </div>
</div>
<!--弹窗内容-->
<style>
    #xx {
        width: 500px;
        height: 200px;
        max-width: 500px;
        max-height: 200px;
    }
</style>
<div class="form-group" id="remark-box" style="display: none">
    <textarea id="xx" name="remark" placeholder="选填"><?= $model['o_operator_remark'] ?></textarea>
</div>
<!--弹窗内容-->
<?php

$this->registerJs('
// 初审通过
$(".verify-first").click(function(){
       var index = layer.open({
        type: 1,
        title:"填写备注",
        area: ["auto", "auto"], //宽高
        content:$("#remark-box"),
        btn: ["确认", "取消"],
        btn1:function(){
            var loading = layer.load(4);
            var remark = $("#xx").val();
            $.ajax({
                url: "' . \yii\helpers\Url::toRoute(['borrow/verify-pass-first', 'order_id' => $model['o_id']]) . '",
                type: "post",
                dataType: "json",
                data: {remark: remark, "' . Yii::$app->getRequest()->csrfParam . '": "' . Yii::$app->getRequest()->getCsrfToken() . '"},
                success: function (data) {
                    if (data.status === 1) {
                        return layer.alert(data.message, {icon: data.status}, function(){return window.location.reload();});
                    }else{
                        return layer.alert(data.message, {icon: data.status});
                    }
                },
                error: function () {
                    layer.alert("噢，我崩溃啦", {title: "系统错误", icon: 5});
                },
                complete: function () {
                    layer.close(loading);
                },
            });
        },
        btn2:function(){
            layer.close(index);
        },
    }) 
});

// 终审放款
$(".verify-end").click(function(){
       var index = layer.open({
        type: 1,
        title:"填写备注",
        area: ["auto", "auto"], //宽高
        content:$("#remark-box"),
        btn: ["确认", "取消"],
        btn1:function(){
            var loading = layer.load(4);
            var remark = $("#xx").val();
            $.ajax({
                url: "' . \yii\helpers\Url::toRoute(['borrownew/verify-pass', 'order_id' => $model['o_id']]) . '",
                type: "post",
                dataType: "json",
                data: {remark: remark, "' . Yii::$app->getRequest()->csrfParam . '": "' . Yii::$app->getRequest()->getCsrfToken() . '"},
                success: function (data) {
                    if (data.status === 1) {
                        return layer.alert(data.message, {icon: data.status}, function(){
                            return window.location.href = "' . \yii\helpers\Url::toRoute(['borrow/list-wait-verify']) . '";
                        });
                    }else{
                        return layer.alert(data.message, {icon: data.status});
                    }
                },
                error: function () {
                    layer.alert("噢，我崩溃啦", {title: "系统错误", icon: 5});
                },
                complete: function () {
                    layer.close(loading);
                },
            });
        },
        btn2:function(){
            layer.close(index);
        },
    }) 
});

// 取消
$(".cancel").click(function(){
       var index = layer.open({
        type: 1,
        title:"填写备注",
        area: ["auto", "auto"], //宽高
        content:$("#remark-box"),
        btn: ["确认", "取消"],
        btn1:function(){
            var loading = layer.load(4);
            var remark = $("#xx").val();
            $.ajax({
                url: "' . \yii\helpers\Url::toRoute(['borrow/verify-cancel', 'order_id' => $model['o_id']]) . '",
                type: "post",
                dataType: "json",
                data: {remark: remark, "' . Yii::$app->getRequest()->csrfParam . '": "' . Yii::$app->getRequest()->getCsrfToken() . '"},
                success: function (data) {
                    if (data.status === 1) {
                        return layer.alert(data.message, {icon: data.status}, function(){return window.location.reload();});
                    }else{
                        return layer.alert(data.message, {icon: data.status});
                    }
                },
                error: function () {
                    layer.alert("噢，我崩溃啦", {title: "系统错误", icon: 5});
                },
                complete: function () {
                    layer.close(loading);
                },
            });
        },
        btn2:function(){
            layer.close(index);
        },
    }) 
});

// 拒绝
$(".refuse").click(function(){
       var index = layer.open({
        type: 1,
        title:"填写备注",
        area: ["auto", "auto"], //宽高
        content:$("#remark-box"),
        btn: ["确认", "取消"],
        btn1:function(){
            var loading = layer.load(4);
            var remark = $("#xx").val();
            $.ajax({
                url: "' . \yii\helpers\Url::toRoute(['borrow/verify-refuse', 'order_id' => $model['o_id']]) . '",
                type: "post",
                dataType: "json",
                data: {remark: remark, "' . Yii::$app->getRequest()->csrfParam . '": "' . Yii::$app->getRequest()->getCsrfToken() . '"},
                success: function (data) {
                    if (data.status === 1) {
                        return layer.alert(data.message, {icon: data.status}, function(){return window.location.reload();});
                    }else{
                        return layer.alert(data.message, {icon: data.status});
                    }
                },
                error: function () {
                    layer.alert("噢，我崩溃啦", {title: "系统错误", icon: 5});
                },
                complete: function () {
                    layer.close(loading);
                },
            });
        },
        btn2:function(){
            layer.close(index);
        },
    }) 
});

// 照片不合格
$(".failpic").click(function(){
       var index = layer.open({
        type: 1,
        title:"填写备注",
        area: ["auto", "auto"], //宽高
        content:$("#remark-box"),
        btn: ["确认", "取消"],
        btn1:function(){
            var loading = layer.load(4);
            var remark = $("#xx").val();
            $.ajax({
                url: "' . \yii\helpers\Url::toRoute(['borrow/verify-failpic', 'order_id' => $model['o_id']]) . '",
                type: "post",
                dataType: "json",
                data: {remark: remark, "' . Yii::$app->getRequest()->csrfParam . '": "' . Yii::$app->getRequest()->getCsrfToken() . '"},
                success: function (data) {
                    if (data.status === 1) {
                        return layer.alert(data.message, {icon: data.status}, function(){return window.location.reload();});
                    }else{
                        return layer.alert(data.message, {icon: data.status});
                    }
                },
                error: function () {
                    layer.alert("噢，我崩溃啦", {title: "系统错误", icon: 5});
                },
                complete: function () {
                    layer.close(loading);
                },
            });
        },
        btn2:function(){
            layer.close(index);
        },
    }) 
});



// 计算剩余应还款额
$("#calculation_residual_loan").click(function(){
    var period_num = $("#period_num").val();
    var postData = {
        "order_id":"'.$model["o_id"] .'",
        "expected":period_num,
    }
    $.ajax({
        url: "' . \yii\helpers\Url::toRoute('borrownew/calculation-residual-loan').'",
        type: "post",
        dataType: "json",
        data:postData,
        success: function (data) {
            if (data.status === 1) {
                $("#calculation_residual_loan_val").val(data.totalPrice);
            }
        }
    });
});

// 提前还款操作
$("#prepayment").click(function(){
    $("#calculation_residual_loan").trigger("click");
    var period_num = $("#period_num").val();
    var price = $("#calculation_residual_loan_val").val();
    var postData = {
        "order_id":"'. $model['o_id'].'",
        "expected":period_num,
        "price":price
    };
    layer.confirm("确定要提前还款" + period_num + "期吗？", {title:"确定提前还款", icon:3}, function(index){
        var loading = layer.load(4);
        $.ajax({
            url: "' . \yii\helpers\Url::toRoute(['borrownew/prepayment']) . '",
            type: "POST",
            dataType: "json",
            data:postData,
            success: function (data) {
                if (data.status === 1) {
                    return layer.alert(data.message, {icon: data.status}, function(){return window.location.reload();});
                }else{
                    return layer.alert(data.message, {icon: data.status});
                }
            },
            error: function () {
                layer.alert("噢，我崩溃啦", {title: "系统错误", icon: 5});
            },
            complete: function () {
                layer.close(loading);
            }
        });
    });
});

// 取消贵宾包服务
$("#cancel_vip_pack").click(function(){
    layer.confirm("确定要取消贵宾包服务吗？", {title:"取消贵宾包服务", icon:3}, function(index){
        var loading = layer.load(4);
        $.ajax({
            url: "' . \yii\helpers\Url::toRoute(['borrownew/cancel-vip-pack', 'order_id' => $model['o_id']]) . '",
            type: "post",
            dataType: "json",
            success: function (data) {
                if (data.status === 1) {
                    return layer.alert(data.message, {icon: data.status}, function(){return window.location.reload();});
                }else{
                    return layer.alert(data.message, {icon: data.status});
                }
            },
            error: function () {
                layer.alert("噢，我崩溃啦", {title: "系统错误", icon: 5});
            },
            complete: function () {
                layer.close(loading);
            }
        });
    });
});

// 取消个人保障服务
$("#cancel_personal_protection").click(function(){
    layer.confirm("确定要取消个人保障服务吗？", {title:"取消个人保障服务", icon:3}, function(index){
        var loading = layer.load(4);
        $.ajax({
            url: "' . \yii\helpers\Url::toRoute(['borrownew/cancel-personal-protection', 'order_id' => $model['o_id']]) . '",
            type: "post",
            dataType: "json",
            success: function (data) {
                if (data.status === 1) {
                    return layer.alert(data.message, {icon: data.status}, function(){return window.location.reload();});
                }else{
                    return layer.alert(data.message, {icon: data.status});
                }
            }
            ,
            error: function () {
                layer.alert("噢，我崩溃啦", {title: "系统错误", icon: 5});
            },
            complete: function () {
                layer.close(loading);
            }
        });
    });
});
');
?>

<?= \yii\helpers\Html::jsFile('@web/js/plugins/layer/layer.min.js') ?>

<script>
    $("#changeBankInfo").on('click', function () {
        var params = {
            type: 1,
            title: '修改银行卡信息',
            area: '500px',
            content: $('#pop_change_bank_info'),
            btn: ['确认', '取消'],
            yes: function (index) {
                // layer.close(index);
                var c_banknum = $("input[name='c_banknum']").val(); // 卡号
                var oi_front_bank = $("input[name='oi_front_bank']").val(); // picture

                var _csrfparam = "<?=Yii::$app->getRequest()->csrfParam?>";
                var _csrfvalue = "<?=Yii::$app->getRequest()->getCsrfToken()?>";
                var c_bank = $("select[name=c_bank]").val();
                if ('' == c_bank) {
                    return layer.warn("请选择银行");
                }
                if ('' == c_banknum) {
                    return layer.warn("请填写银行卡号");
                }
                if ('' == oi_front_bank) {
                    return layer.warn("请上传银行卡照片");
                }
                var customer_id = "<?=$model['o_customer_id']?>";
                var o_images_id = "<?=$model['o_images_id']?>";
                var data = {customer_id: customer_id, c_bank: c_bank, c_banknum: c_banknum, oi_front_bank: oi_front_bank, o_images_id: o_images_id};
                data[_csrfparam] = _csrfvalue;
                var params = {
                    url: "<?= Yii::$app->getUrlManager()->createUrl(['customer/change-bank-info']);?>",
                    dataType: "json",
                    type: "post",
                    data: data,
                    success: function (res) {
                        if (res.status == 1) {
                            layer.close(index);
                            var $_ = $("select[name=c_bank]").find('option:selected').text()+'<br>'+ c_banknum;
                            $("#bank_info").html($_);
                            return layer.alert(res.message);
                        } else {
                            // 弹出错误信息
                            return layer.error(res.message);
                        }
                    },
                    error: function () {
                        that.showErrorNotice();
                    },
                    complete: function () {
                        that.hideLoading();
                    },
                };
                $.ajax(params);
            }
        };
        layer.open(params);
    });
</script>

<?= \yii\bootstrap\Html::jsFile('@web/js/plugins/puupload/plupload.full.min.js') ?>
<script>
    var loading = null;
    function loadinit($name) {

        var uploader = new plupload.Uploader({ //实例化一个plupload上传对象
            browse_button: 'browse-' + $name,
            url: '<?= Yii::$app->getUrlManager()->createUrl(['customer/upload']);?>',

            silverlight_xap_url: 'js/Moxie.xap',
            filters: {
                mime_types: [ //只允许上传图片文件
                    {title: "图片文件", extensions: "jpg,gif,png"}
                ]
            },
            multipart_params: {
                '_csrf-backend': '<?= Yii::$app->getRequest()->getCsrfToken(); ?>'
            }
        });
        uploader.init(); //初始化

        //绑定文件添加进队列事件
        uploader.bind('FilesAdded', function (uploader, files) {
            for (var i = 0, len = files.length; i < len; i++) {
                //构造html来更新UI
                !function (i) {
                    previewImage(files[i], function (imgsrc) {
                        $('#file-list-' + $name + '  img').replaceWith('<img src="' + imgsrc + '" />');
                    })
                }(i);
            }
        });

        uploader.bind('FileUploaded', function (uploader, file, responseObject) {
            layer.close(loading);

            layer.msg('上传成功', {icon: 1});
            var key = responseObject.response;
            $("input[name='" + $name+"']").val(key);
        });

        //plupload中为我们提供了mOxie对象
        //有关mOxie的介绍和说明请看：https://github.com/moxiecode/moxie/wiki/API
        //如果你不想了解那么多的话，那就照抄本示例的代码来得到预览的图片吧
        function previewImage(file, callback) {//file为plupload事件监听函数参数中的file对象,callback为预览图片准备完成的回调函数
            if (!file || !/image\//.test(file.type)) return; //确保文件是图片
            if (file.type == 'image/gif') {//gif使用FileReader进行预览,因为mOxie.Image只支持jpg和png
                var fr = new mOxie.FileReader();
                fr.onload = function () {
                    callback(fr.result);
                    fr.destroy();
                    fr = null;
                }
                fr.readAsDataURL(file.getSource());
            } else {
                var preloader = new mOxie.Image();
                preloader.onload = function () {
                    preloader.downsize(300, 300);//先压缩一下要预览的图片,宽300，高300
                    var imgsrc = preloader.type == 'image/jpeg' ? preloader.getAsDataURL('image/jpeg', 80) : preloader.getAsDataURL(); //得到图片src,实质为一个base64编码的数据
                    callback && callback(imgsrc); //callback传入的参数为预览图片的url
                    preloader.destroy();
                    preloader = null;
                };
                preloader.load(file.getSource());
            }
        }

        document.getElementById('start_upload_' + $name).onclick = function () {
            loading = layer.load(3);
            uploader.start(); //调用实例对象的start()方法开始上传文件，当然你也可以在其他地方调用该方法
        }
    }

    loadinit('oi_front_bank');
</script>

<script>

       // 上传合同
    $("#jun-a").click(function(){
        layer.confirm("确定要上传借款合同？", {title:"上传合同", icon:3}, function(index){
            var loading = layer.load();
            $.ajax({
                url: "<?= \yii\helpers\Url::toRoute(['jun/a', 'order_id' => $model['o_id']]) ?>",
                type: "get",
                dataType: "json",
                data: {},
                success: function (data) {
                    if (data.status === 1) {
                        return layer.alert(data.message, {icon: data.status}, function(){
//                            return window.location.reload();
                            $.ajax({
                                url: "<?= \yii\helpers\Url::toRoute(['jun/a7', 'order_id' => $model['o_id']]) ?>",
                                type: "get",
                                dataType: "json",
                                data: {},
                                success: function (data) {
                                    if (data.status === 1) {
                                        return layer.alert(data.message, {icon: data.status}, function(){
                                            return window.location.reload();
                                        });
                                    }else{
                                        return layer.alert(data.message, {icon: data.status});
                                    }
                                },
                                error: function () {
                                    layer.alert("噢，我崩溃啦", {title: "系统错误", icon: 5});
                                },
                                complete: function () {
                                    layer.close(loading);
                                },
                            });
                        });
                    }else{
                        return layer.alert(data.message, {icon: data.status});
                    }
                },
                error: function () {
                    layer.alert("噢，我崩溃啦", {title: "系统错误", icon: 5});
                },
                complete: function () {
                    layer.close(loading);
                }
            });
        });
    });

    //发送签约短信
    $("#jun-a7").click(function(){
        layer.confirm("确定要发送签约短信？", {title:"发送签约短信", icon:3}, function(index){
            var loading = layer.load();
            $.ajax({
                url: "<?= \yii\helpers\Url::toRoute(['jun/a7', 'order_id' => $model['o_id']]) ?>",
                type: "get",
                dataType: "json",
                data: {},
                success: function (data) {
                    if (data.status === 1) {
                        return layer.alert(data.message, {icon: data.status}, function(){
                            return window.location.reload();
                        });
                    }else{
                        return layer.alert(data.message, {icon: data.status});
                    }
                },
                error: function () {
                    layer.alert("噢，我崩溃啦", {title: "系统错误", icon: 5});
                },
                complete: function () {
                    layer.close(loading);
                },
            });
        });
    });


       layer.config({
           extend: 'extend/layer.ext.js'
       });
       // 添加(修改)商品代码
       $("#add_product_code").click(function(){
           var o_product_code_default = "<?= $model['o_product_code']; ?>";
           layer.prompt({title: '请输入商品代码，并确认',value: o_product_code_default, formType: 2}, function(o_product_code, index){
               $.ajax({
                   url: "<?= yii\helpers\Url::toRoute(['borrow/check-product-code']); ?>",
                   type: "post",
                   dataType: "json",
                   data: {o_product_code: o_product_code, "<?= Yii::$app->getRequest()->csrfParam . '": "' . Yii::$app->getRequest()->getCsrfToken(); ?>"},
                   success: function (data) {
                       if(data.status === 1){
                           if (data.status === 1) {
                               layer.alert(data.message, {title:'商品代码重复提示',icon: data.status}, function(){
                                   layer.confirm('您确定要添加此商品代码？', {
                                       btn: ['确认','取消'] //按钮
                                   }, function(){
                                       $.ajax({
                                           url: "<?= yii\helpers\Url::toRoute(['borrow/edit-product-code', 'order_id' => $model['o_id']]); ?>",
                                           type: "post",
                                           dataType: "json",
                                           data: {o_product_code: o_product_code, "<?= Yii::$app->getRequest()->csrfParam . '": "' . Yii::$app->getRequest()->getCsrfToken(); ?>"},
                                           success: function (data) {
                                               if (data.status === 1) {
                                                   return layer.alert(data.message, {icon: data.status}, function(){return window.location.reload();});
                                               }else{
                                                   return layer.alert(data.message, {icon: data.status});
                                               }
                                           },
                                           error: function () {
                                               layer.alert("噢，我崩溃啦", {title: "系统错误", icon: 5});
                                           },
                                           complete: function () {
                                               layer.close(loading);
                                               layer.close(index);
                                           },
                                       });
                                   }, function(){
                                       layer.close(loading);
                                       layer.close(index);
                                   });
                               });
                           }else{
                               return layer.alert(data.message, {icon: data.status});
                           }

                       }else{
                           return layer.alert(data.message, {icon: data.status});
                       }
                   },
                   error: function () {
                       layer.alert("噢，我崩溃啦", {title: "系统错误", icon: 5});
                   },
                   complete: function () {
                       layer.close(loading);
                       layer.close(index);
                   },
               });
           });

       });
</script>