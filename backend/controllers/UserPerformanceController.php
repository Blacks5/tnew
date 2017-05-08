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
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionIndex()
    {
        $yejidrv = new YejiSearch();
        $list = $yejidrv->search(\yii::$app->request->getQueryParams());
        $provinces = Helper::getAllProvince();
        return $this->render('index', [
            'data'=>$list,
            'sear'=>$list['sear'],
            'pages'=>$list['pages'],
            'provinces'=>$provinces
        ]);
    }
}