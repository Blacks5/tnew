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
    /**
     * 提交订单
     * @return array
     * @throws \Exception
     * @author too <hayto@foxmail.com>
     */
    public function actionCreateOrder()
    {
        Wechat::Login(['manage/index']);
        $trans = Yii::$app->getDb()->beginTransaction();
        try{
            Yii::$app->getResponse()->format = yii\web\Response::FORMAT_JSON;

            $request = \Yii::$app->getRequest();
            $params = $request->post();
            $orderServer = new Order();
            $orderServer->addOrder($params);
//            $trans->rollBack();
            $trans->commit();
            return ['status'=>1, 'message'=>'提交成功'];
        }catch (CustomCommonException $e){
            $trans->rollBack();
            return ['status'=>0, 'message'=>$e->getMessage()];
        }catch (\Exception $e){
            $trans->rollBack();
            throw $e;
//            return ['status'=>0, 'message'=>$e->getMessage()];
            return ['status'=>0, 'message'=>'网络异常'];
        }
    }


}