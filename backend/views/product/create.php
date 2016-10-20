<?php

?>
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>添加产品
                        <small>管理</small>
                    </h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                        <a class="dropdown-toggle" data-toggle="dropdown" href="form_basic.html#">
                            <i class="fa fa-wrench"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-user">
                            <li><a href="form_basic.html#">选项1</a>
                            </li>
                            <li><a href="form_basic.html#">选项2</a>
                            </li>
                        </ul>
                        <a class="close-link">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <form method="get" class="form-horizontal">

                        <div class="form-group">
                            <label class="col-sm-2 control-label">类型</label>
                            <div class="col-sm-4">
                                <select class="form-control m-b" name="p_type">
                                    <option>选项 1</option>
                                    <option>选项 2</option>
                                    <option>选项 3</option>
                                    <option>选项 4</option>
                                </select>
                            </div>
                        </div>
                        <!--<div class="hr-line-dashed"></div>-->
                        <div class="form-group">
                            <label class="col-sm-2 control-label">名称</label>

                            <div class="col-sm-4">
                                <input type="text" class="form-control" name="p_name">
                                <span class="help-block m-b-none">帮助文本</span>
                            </div>
                        </div>

                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">期数</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control">
                                <span class="help-block m-b-none">帮助文本</span>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">月利率</label>

                            <div class="col-sm-4">
                                <input type="password" class="form-control" name="password">
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">增值服务费</label>

                            <div class="col-sm-4">
                                <input type="password" class="form-control" name="password">
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">随心包服务费</label>

                            <div class="col-sm-4">
                                <input type="password" class="form-control" name="password">
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">财务管理费</label>

                            <div class="col-sm-4">
                                <input type="password" class="form-control" name="password">
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">客户管理费</label>

                            <div class="col-sm-4">
                                <input type="password" class="form-control" name="password">
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <div class="col-sm-4 col-sm-offset-2">
                                <button class="btn btn-primary" type="submit">保存内容</button>
                                <button class="btn btn-white" type="submit">取消</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- iCheck -->
<?= \yii\bootstrap\Html::jsFile('@web/js/plugins/iCheck/icheck.min.js') ?>
<?php $this->registerJs("
        $('.i-checks').iCheck({
            checkboxClass: 'icheckbox_square-green',
            radioClass: 'iradio_square-green',
        });;
", \yii\web\View::PH_BODY_BEGIN); ?>