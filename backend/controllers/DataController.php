<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2017/8/30
 * Time: 14:39
 */

namespace backend\controllers;


use backend\models\DataSearch;
use common\components\Helper;
use backend\core\CoreBackendController;
use yii;

class DataController extends CoreBackendController
{
    public function actionIndex()
    {

    }

    public function actionGather()
    {
        $data = new DataSearch();
        $list = $data->getLoanTotal(\yii::$app->request->getQueryParams());
        $province = Helper::getAllProvince();


        return $this->render('gather',[
            'data'=>$list,
            'users'=>$list['user'],
            'area' => $province,
            'sear' => $list['sear'],
        ]);
    }
}