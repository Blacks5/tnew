<?php
/**
 * Created by PhpStorm.
 * User: too
 * Date: 2016/10/20
 * Time: 14:31
 * @author 涂鸿 <hayto@foxmail.com>
 */
namespace backend\controllers;

use WebSocket\Client;
use yii;
use backend\core\CoreBackendController;
use \backend\services\YijifuDebugger;

class ToolsController extends CoreBackendController
{
    public function actionYijifuTest()
    {
        $debugger = new YijifuDebugger();
        $r = $debugger->sign('wwj');
    }
}


