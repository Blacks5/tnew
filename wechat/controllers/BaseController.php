<?php
/**
 * Created by PhpStorm.
 * User: too
 * Date: 2017/8/21
 * Time: 17:00
 * @author too <hayto@foxmail.com>
 */

namespace wechat\controllers;


use yii\web\Controller;

class BaseController extends Controller
{
    public $enableCsrfValidation = false;
}