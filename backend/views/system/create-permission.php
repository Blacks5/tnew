<?php
/**
 * Created by PhpStorm.
 * User: too
 * Date: 2016/10/10
 * Time: 17:17
 * @author 涂鸿 <hayto@foxmail.com>
 */
use \yii\helpers\Html;
use \yii\helpers\Url;

?>
<div class="wrapper wrapper-content">
<div class="row">
    <div class="col-sm-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>菜单添加 >
                    <small>只给开发人员使用</small>
                </h5>
                <div style="float:right;margin-right: 20px;">
                    <a onclick="javascript:history.back();">返回上一页</a>
                </div>

            </div>
            <div class="ibox-content">
                <form action="" method="post" class="form-horizontal" id="myform">


                    <div class="form-group">

                        <div class="form-group">
                            <label class="col-sm-2 control-label">上级：</label>
                            <div class="col-sm-8">
                                <select class="input-sm form-control input-s-sm inline" name="parentid">
                                    <option value="0">作为菜单一级</option>

                                </select>

                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>

                        <label class="col-sm-2 control-label">名称：</label>
                        <div class="col-sm-8">
                            <input name="name" id="name" class="form-control" type="text" aria-required="true"
                                   aria-invalid="false" class="valid">

                            <span class="help-block m-b-none"><i class="fa fa-info-circle"></i> 2-20个字符,中文名称</span>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">应用：</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="module" id="module" value="app-backend"
                                   readonly="readonly">
                            <span class="help-block m-b-none"> 固定,不需要修改</span>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">控制器：</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="controller" id="controller">
                            <span class="help-block m-b-none">2-20个字符,字母必须都小写例如 user</span>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>


                    <div class="form-group">
                        <label class="col-sm-2 control-label">方法：</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="action" id="action">
                            <span class="help-block m-b-none">2~20个字符，字母/数字/,字母必须都小写，例如：index</span>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>


                    <div class="form-group">
                        <label class="col-sm-2 control-label">是否显示：</label>
                        <div class="col-sm-8">
                            <div class="radio">
                                <label>
                                    <input type="radio" name="status" value="1" checked>是
                                </label>
                                <label>
                                    <input type="radio" name="status" value="2">否
                                </label>
                            </div>

                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>

                    <div class="form-group">
                        <div class="col-sm-4 col-sm-offset-2">

                            <button class="btn btn-primary" type="submit" name="dosubmit">保 存</button>
                        </div>
                    </div>


                </form>

            </div>
        </div>
    </div>
</div>
    </div>