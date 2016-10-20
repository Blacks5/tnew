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
                <form class="form-horizontal m-t" id="myform"   >
                    <input type="hidden" name="id" id="id" value="<?php echo 1; ?>" />
                    <div class="form-group">
                        <label class="col-sm-3 control-label">真实姓名：</label>
                        <div class="col-sm-8">
                            <p class="form-control-static"><?= $model['realname']; ?></p>
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
                                <?= $model['department_id'],"【{$model["job_id"]}】"; ?>
                            </p>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">负责区域：</label>
                        <div class="col-sm-1">
                            <p class="form-control-static">
                                <?= $model['province']; ?>
                            </p>
                        </div>
                        <div class="col-sm-1">
                            <p class="form-control-static">
                                <?= $model['city']; ?>
                            </p>
                        </div>
                        <div class="col-sm-1">
                            <p class="form-control-static">
                                <?= $model['county']; ?>
                            </p>
                        </div>
                    </div>

                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">锁定状态：</label>
                        <div class="col-sm-8">
                            <p class="form-control-static">
                                <?php if($model['status'] === '10'): ?>
                                    <button class="btn btn-primary btn-xs">开启</button>
                                <?php else: ?>
                                    <button type="button" class="btn btn-default btn-xs">已锁定</button>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">信息备注：</label>
                        <div class="col-sm-8">
                            <p class="form-control-static">
                                <?php echo 1; ?>
                            </p>
                        </div>
                    </div>


                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <div class="col-sm-8 col-sm-offset-3">

                            <button class="btn btn-primary" type="button" name="btnbutton" onclick="history.go(-1)">返回上一页</button>
                        </div>
                    </div>
                </form>
            </div>



        </div>
    </div>
</div>
