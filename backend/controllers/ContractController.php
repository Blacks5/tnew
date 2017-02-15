<?php
/**
 * Created by PhpStorm.
 * Date: 2017/2/15
 * Time: 21:05
 * @author 涂鸿 <hayto@foxmail.com>
 */

namespace backend\controllers;

use yii\web\Controller;
class ContractController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
}