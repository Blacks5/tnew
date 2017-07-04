<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\components\Helper;
/* @var $this yii\web\View */
/* @var $model backend\models\User */

?>
<div class="row ">
    <div class="col-sm-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>产品详情 ></h5>
            </div>
            <div class="ibox-content">
                <form class="form-horizontal m-t" id="myform">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">姓名：</label>
                        <div class="col-sm-8">
                            <p class="form-control-static"><?= $model['c_customer_name']; ?></p>
                        </div>
                    </div>


                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">身份证号码：</label>
                        <div class="col-sm-8">
                            <p class="form-control-static"><?= $model['c_customer_id_card']; ?></p>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">手机号码：</label>
                        <div class="col-sm-8">
                            <p class="form-control-static"><?= $model['c_customer_cellphone']; ?></p>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">总借款额度：</label>
                        <div class="col-sm-8">
                            <p class="form-control-static"><?= round($model['c_total_money']); ?>元</p>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">总借款次数：</label>
                        <div class="col-sm-8">
                            <p class="form-control-static"><?= $model['c_total_borrow_times']; ?>次</p>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">户籍地址：</label>
                        <div class="col-sm-8">
                            <p class="form-control-static">
                                <?= Helper::getAddrName($model['c_customer_province']). Helper::getAddrName($model['c_customer_city']). Helper::getAddrName($model['c_customer_county']); ?>
                            </p>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">现居住地址：</label>
                        <div class="col-sm-8">
                            <p class="form-control-static">
                                <?= Helper::getAddrName($model['c_customer_addr_province']). Helper::getAddrName($model['c_customer_addr_city']). Helper::getAddrName($model['c_customer_addr_county']). $model['c_customer_addr_detail']; ?></p>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">工作单位【部门,职位】：</label>
                        <div class="col-sm-8">
                            <p class="form-control-static"><?= $model['c_customer_jobs_company'].'-'. $model['c_customer_jobs_section']. '-'. $model['c_customer_jobs_title']; ?></p>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">是否购买社保：</label>
                        <div class="col-sm-8">
                            <p class="form-control-static"><?= ($model['c_customer_jobs_is_shebao']==1)?'是':'否'; ?></p>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">单位电话：</label>
                        <div class="col-sm-8">
                            <p class="form-control-static"><?= $model['c_customer_jobs_phone']; ?></p>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">单位地址：</label>
                        <div class="col-sm-8">
                            <p class="form-control-static">
                                <?= Helper::getAddrName($model['c_customer_jobs_province']). Helper::getAddrName($model['c_customer_jobs_city']). Helper::getAddrName($model['c_customer_jobs_county']). $model['c_customer_jobs_detail_addr']; ?></p>
                        </div>
                    </div>

                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">亲属：</label>
                        <div class="col-sm-8">
                            <p class="form-control-static">
                                <?= $model['c_kinship_name']. '-'. Helper::getKindShipString($model['c_kinship_relation']). '-'. $model['c_kinship_cellphone']/*. '-'. $model['c_kinship_addr']*/; ?></p>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">其他联系人：</label>
                        <div class="col-sm-8">
                            <p class="form-control-static">
                                <?= $model['c_other_people_name']. '-'.Helper::getKindShipString($model['c_other_people_relation']). '-'. $model['c_other_people_cellphone']/*. '-'. $model['c_kinship_addr'];*/ ?></p>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">客户状态：</label>
                        <div class="col-sm-8">
                            <p class="form-control-static">
                                <?php if ($model['c_status'] == 10){?>
                                    <button type="button" class="btn btn-primary btn-xs">开启</button>
                                <?php }else{ ?>
                                    <button type="button" class="btn btn-default btn-xs">已锁定</button>
                                <?php }?>
                            </p>
                        </div>
                    </div>


                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <div class="col-sm-8 col-sm-offset-3">

                            <button class="btn btn-primary" type="button" name="btnbutton" onclick="history.go(-1)">
                                返回上一页
                            </button>
                        </div>
                    </div>
                </form>
            </div>


        </div>
    </div>
</div>
