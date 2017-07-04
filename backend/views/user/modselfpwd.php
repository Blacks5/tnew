<?php
use \yii\helpers\Html;
use yii\helpers\Url;
?>
<div class="row ">
    <div class="col-sm-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>管理员管理 > <small>可以对管理员进行密码修改操作</small></h5>
            </div>
            <div class="ibox-content">
                <form class="form-horizontal m-t" id="myform"  method="post" action="<?= Url::toRoute(['user/mod-self-pwd', 'id'=>$model->id])?>">
                    <input type="hidden" name="id" id="id" value="<?php echo 1; ?>" />
                    <div class="form-group">
                        <label class="col-sm-3 control-label">真实姓名：</label>
                        <div class="col-sm-8">
                            <p class="form-control-static"><?= Html::encode($model->realname); ?></p>
                        </div>
                    </div>


                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">登录名：</label>
                        <div class="col-sm-8">
                            <p class="form-control-static"><?= Html::encode($model->username); ?></p>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group <?php if($model->hasErrors('old_password')){ ?>has-error<?php }?>">
                        <label class="col-sm-3 control-label">原始密码：</label>
                        <div class="col-sm-8">
                            <input id="old_password" name="User[old_password]" class="form-control" type="password">
                            <?php if($model->hasErrors('old_password')){ ?>
                                <span class="help-block m-b-none"><i class="fa fa-info-circle"></i> <?= $model->getFirstError('old_password')?></span>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group <?php if($model->hasErrors('password_hash')){ ?>has-error<?php }?>">
                        <label class="col-sm-3 control-label">密码：</label>
                        <div class="col-sm-8">
                            <input id="password" name="User[password_hash]" class="form-control" type="password">
                            <?php if($model->hasErrors('password_hash')){ ?>
                                <span class="help-block m-b-none"><i class="fa fa-info-circle"></i> <?= $model->getFirstError('password_hash')?></span>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group <?php if($model->hasErrors('password_hash_1')){ ?>has-error<?php }?>">
                        <label class="col-sm-3 control-label">确认密码：</label>
                        <div class="col-sm-8">
                            <input id="confirm_password" name="User[password_hash_1]" class="form-control" type="password">
                            <?php if($model->hasErrors('password_hash_1')){ ?>
                            <span class="help-block m-b-none"><i class="fa fa-info-circle"></i><?= $model->getFirstError('password_hash')?></span>
                            <?php } ?>
                        </div>
                    </div>

                    <input type="hidden" name="_csrf-backend" value="<?=Yii::$app->getRequest()->csrfToken;?>">

                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <div class="col-sm-8 col-sm-offset-3">
                            <button class="btn btn-primary" type="submit" name="dosubmit">保存内容</button>
                        </div>
                    </div>
                </form>
            </div>



        </div>
    </div>
</div>