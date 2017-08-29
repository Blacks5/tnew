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
use common\models\Orders;
use common\models\OrdersSearch;
use common\models\User;
use common\services\Order;
use wechat\Tools\Wechat;
use Yii;

class OrderController extends BaseController {
	/**
	 * 提交订单
	 * @return array
	 * @throws \Exception
	 * @author too <hayto@foxmail.com>
	 */
	public function actionCreateOrder() {
		$trans = Yii::$app->getDb()->beginTransaction();
		try {
			Yii::$app->getResponse()->format = yii\web\Response::FORMAT_JSON;

			$request = \Yii::$app->getRequest();
			$params = $request->post();
			$orderServer = new Order();
			$orderServer->addOrder($params);
//            $trans->rollBack();
			$trans->commit();
			return ['status' => 1, 'message' => '提交成功'];
		} catch (CustomCommonException $e) {
			$trans->rollBack();
			return ['status' => 0, 'message' => $e->getMessage()];
		} catch (\Exception $e) {
			$trans->rollBack();
			throw $e;
//            return ['status'=>0, 'message'=>$e->getMessage()];
			return ['status' => 0, 'message' => '网络异常'];
		}
	}

	// 历史订单
	public function actionOrderList() {
        $request = Yii::$app->request;

        if($request->isAjax && $request->isGet){
            $session = Yii::$app->session;

            // 关键字查询
            $keywords = $request->get('keywords');

            // 查询订单
            $model = new OrdersSearch();
            $query = $model->search([
                'OrdersSearch' => [
                    'customer_name' => $keywords,
                ]]);

            // 获取系统用户数据
            $sys_user = $session->get('sys_user');

            // 特定员工订单
            // $query = $query->andWhere(['o_user_id' => $sys_user->id]);

            // 获取筛选状态
            $screen_type = $request->get('screen_type');

            switch ($screen_type) {
            case 'near': // 近一月
                $query = $query->andWhere(['>=', 'o_created_at', strtotime(date('Y-m-d', strtotime('-1 Month')))]);
                break;

            case 'wait': // 待审核
                $query = $query->andWhere(['o_status' => [
                    Orders::STATUS_WAIT_CHECK,
                    Orders::STATUS_WAIT_CHECK_AGAIN,
                    Orders::STATUS_WAIT_APP_UPLOAD_AGAIN,
                ]]);
                break;

            case 'refuse': // 已拒绝
                $query = $query->andWhere(['o_status' => Orders::STATUS_REFUSE]);
                break;

            case 'revoke': // 已撤销
                $query = $query->andWhere(['o_status' => Orders::STATUS_REVOKE]);
                break;

            case 'cancel': // 已撤销
                $query = $query->andWhere(['o_status' => Orders::STATUS_CANCEL]);
                break;

            case 'paying': // 还款中
                $query = $query->andWhere(['o_status' => Orders::STATUS_PAYING]);
                break;

            case 'payover': // 已还清
                $query = $query->andWhere(['o_status' => Orders::STATUS_PAY_OVER]);
                break;

            default:
                $query = $query->andWhere(['o_status' => [
                    Orders::STATUS_WAIT_CHECK,
                    Orders::STATUS_WAIT_CHECK_AGAIN,
                    Orders::STATUS_WAIT_APP_UPLOAD_AGAIN,
                    Orders::STATUS_REFUSE,
                    Orders::STATUS_REVOKE,
                    Orders::STATUS_CANCEL,
                    Orders::STATUS_PAYING,
                    Orders::STATUS_PAY_OVER,
                ]]);
                break;
            }

            // 调取分页
            $pages = new \yii\data\Pagination(['totalCount' => $query->count()]);

            // 分页数据量
            $pages->pageSize = Yii::$app->params['page_size'];

            // 获取分页数据
            $data = $query->orderBy(['orders.o_created_at' => SORT_DESC])
                    ->offset($pages->offset)
                    ->limit($pages->limit)
                    ->asArray()
                    ->all();

            Yii::$app->getResponse()->format = yii\web\Response::FORMAT_JSON;

            $response = [];

            foreach ($data as $item) {
                $response[] = [
                    'o_id' => $item['o_id'],
                    'o_serial_id' => $item['o_serial_id'],
                    'p_period' => $item['p_period'],
                    'o_total_price' => round($item['o_total_price'], 2),
                    'o_total_deposit' => round($item['o_total_deposit'], 2),
                    'c_customer_name' => $item['c_customer_name'],
                    'c_customer_cellphone' => $item['c_customer_cellphone'],
                    'p_name' => $item['p_name'],
                    'o_created_at' => date('Y-m-d H:i:s', $item['o_created_at']),
                    'o_status' => $item['o_status'],
                ];
            }

            if ($response) {
                return ['status' => 1, 'message' => 'success', 'data' => [
                    'data' => $response, 
                    'page' => $pages->page
                ]];
            } else {
                return ['status' => 0, 'message' => 'no data' , 'data' => [
                    'data' => [], 
                    'page' => $pages->page
                ]];
            }
        }else{
            return $this->renderPartial('list');
        }
	}
}