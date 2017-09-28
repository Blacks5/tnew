<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2017/8/30
 * Time: 14:39
 */

namespace backend\controllers;


use backend\models\DataSearch;
use backend\models\YejiSearch;
use common\components\Helper;
use backend\core\CoreBackendController;
use yii;

class DataController extends CoreBackendController
{
    public function actionIndex()
    {

    }

    /**
     * 获取平台总数据
     * @return string
     * @author OneStep
     */
    public function actionGather()
    {
        $data = new DataSearch();
        $list = $data->getLoanTotal(\yii::$app->request->getQueryParams());
        $province = Helper::getAllProvince();

        return $this->render('gather',[
            'data'=>$list['data'],
            'users'=>$list['user'],
            'area' => $province,
            'sear' => $list['sear'],
        ]);
    }

    /**
     * 获取审核数据
     * @return string
     * @author OneStep
     */
    public function actionVerify()
    {
        $data = new DataSearch();
        $list = $data->verify(\yii::$app->request->getQueryParams());

        return $this->render('verify', [
          'all' => $list['all'],
          'sear'=> $list['sear'],
        ]);
    }

    public function actionLogs()
    {
        $data = new DataSearch();
        $list = $data->getLogs(\yii::$app->request->getQueryParams());
        return $this->render('logs',[
                'data' => $list['data'],
                'sear' => $list['sear'],
                'type' => $list['type'],
                'pages'=> $list['pages'],
            ]);
    }
}