<?php
/**
 * Created by PhpStorm.
 * User: Brucelee
 * Date: 2017/8/13
 * Time: 22:59
 * Author: lilaotou <liwansen@foxmail.com>
 */

namespace wechat\controllers;


use yii\web\Controller;

class ManageController extends Controller
{
    /**
     * @return string
     * @author lilaotou <liwansen@foxmail.com>
     * 首页
     */
    public function actionIndex(){
        return $this->renderPartial('index');
    }

    /**
     * @return string
     * @author lilaotou <liwansen@foxmail.com>
     * 登录
     */
    public function actionLogin(){
        return $this->renderPartial('login');
    }

    /**
     * @return string
     * @author lilaotou <liwansen@foxmail.com>
     * 提交订单
     */
    public function actionCommitorder(){
        return $this->renderPartial('commit_order');
    }

}