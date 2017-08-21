<?php
/**
 * Created by PhpStorm.
 * User: too
 * Date: 2017/8/13
 * Time: 19:14
 * @author too <hayto@foxmail.com>
 */

namespace wechat\controllers;

use common\components\CustomCommonException;
use wechat\Tools\Wechat;
use yii;
use common\services\Order;

class OrderController extends BaseController
{
    public function actionCreateOrder()
    {
        Wechat::Login(['manage/index']);
        try{
            Yii::$app->getResponse()->format = yii\web\Response::FORMAT_JSON;

            $request = \Yii::$app->getRequest();
            $params = $request->post();
            $orderServer = new Order();
            $orderServer->addOrder($params);
            return ['status'=>1, 'message'=>'提交成功'];
        }catch (CustomCommonException $e){
            return ['status'=>0, 'message'=>$e->getMessage()];
        }catch (\Exception $e){
            throw $e;
//            return ['status'=>0, 'message'=>$e->getMessage()];
            return ['status'=>0, 'message'=>'网络异常'];
        }
    }

    public function actionT()
    {
        $id = '510623198812250210';
        $cell = '18990232122';
        $name = "涂鸿";
        $bank_id = "6221532320005581447";
        $api = "sandboxapi.100credit.cn";
//        new
    }
}