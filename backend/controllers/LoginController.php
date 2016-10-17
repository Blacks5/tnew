<?php
/**
 * Created by PhpStorm.
 * User: too
 * Date: 2016/10/14
 * Time: 17:38
 * @author 涂鸿 <hayto@foxmail.com>
 */
namespace backend\controllers;

use common\core\CoreCommonController;

class LoginController extends CoreCommonController
{
    public function actionLogin()
    {
        p('login');
    }

    public function actionLogout()
    {
        p('logout');
    }
}