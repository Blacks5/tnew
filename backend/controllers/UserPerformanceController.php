<?php
/**
 * Created by PhpStorm.
 * User: too
 * Date: 2016/10/17
 * Time: 16:43
 * @author 涂鸿 <hayto@foxmail.com>
 */
namespace backend\controllers;
use backend\models\YejiSearch;
use common\components\Helper;
use yii;
use backend\core\CoreBackendController;


/**
 * 员工业绩 控制器
 * Class UserPerformanceController
 * @package backend\controllers
 * @author 涂鸿 <hayto@foxmail.com>
 */
class UserPerformanceController extends CoreBackendController
{
    /**
     * 员工业绩列表
     * @return string
     * @author OneStep 2017年8月28日17:03:28
     */
    public function actionIndex()
    {
        $yejidrv = new YejiSearch();
        $list = $yejidrv->search(\yii::$app->request->getQueryParams());
        $user = yii::$app->user->identity;
        $area = $yejidrv->getArea($user);


        return $this->render('index', [
            'data'=>$list,
            'user'=>$user,
            'users'=>$list['data'],
            'all'=>$list['all'],
            'sear'=>$list['sear'],
            'pages'=>$list['pages'],
            'area'=>$area,
            'total'=>$list['total'],
        ]);
    }
}