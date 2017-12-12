<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\User */

?>
<div class="row ">
    <div class="col-sm-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>员工资料 ></h5>
            </div>
            <div class="ibox-content">
                <form class="form-horizontal m-t" id="myform">
                    <input type="hidden" name="id" id="id" value="<?php echo 1; ?>"/>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">真实姓名：</label>
                        <div class="col-sm-8">
                            <p class="form-control-static"><?= $model['realname']; ?></p>
                        </div>
                    </div>

                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">手机号码：</label>
                        <div class="col-sm-8">
                            <p class="form-control-static"><?= $model['cellphone']; ?></p>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">身份证号码：</label>
                        <div class="col-sm-8">
                            <p class="form-control-static"><?= $model['id_card_num']; ?></p>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">联系地址：</label>
                        <div class="col-sm-8">
                            <p class="form-control-static"><?= $model['address']; ?></p>
                        </div>
                    </div>

                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">登录名：</label>
                        <div class="col-sm-8">
                            <p class="form-control-static"><?= $model['username']; ?></p>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">联系邮箱：</label>
                        <div class="col-sm-8">
                            <p class="form-control-static"><?= $model['email']; ?></p>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">所属部门/职位：</label>
                        <div class="col-sm-8">
                            <p class="form-control-static">
                                <?= $model['d_name'], "【{$model["j_name"]}】"; ?>
                            </p>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">负责区域：</label>
                        <div class="col-sm-8">
                            <button class="btn btn-xs btn-primary">
                                <?= $model['province']; ?>
                            </button>
                            <button class="btn btn-xs btn-primary">
                                <?= $model['city']; ?>
                            </button>
                            <button class="btn btn-xs btn-primary">
                                <?= $model['county']; ?>
                            </button>
                        </div>
                    </div>

                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">身份证照片：</label>
                        <div class="col-sm-8">
                            <p class="form-control-static">
                                <img src="<?=$model["id_card_pic_one"]?>" alt="员工身份证照片" style="max-width:300px;max-height:300px;">
                            </p>
                        </div>
                    </div>

                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">锁定状态：</label>
                        <div class="col-sm-8">
                            <p class="form-control-static">
                                <?php if ($model['status'] === '10'): ?>
                                    <button type="button" class="btn btn-primary btn-xs">开启</button>
                                <?php elseif ($model['status'] === '1'): ?>
                                    <button type="button" class="btn btn-primary btn-xs">冻结</button>
                                <?php elseif ($model['status'] === '2'): ?>
                                    <button type="button" class="btn btn-primary btn-xs">离职</button>
                                <?php else: ?>
                                    <button type="button" class="btn btn-default btn-xs">已锁定</button>
                                <?php endif; ?>
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
