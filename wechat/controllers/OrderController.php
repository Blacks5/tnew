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
		$session = Yii::$app->session;
		$request = Yii::$app->request;

		// 获取系统用户数据
		$sys_user = $session->get('sys_user');

        $r_data = $request->getQueryParams();

		// 查询订单
		$model = new OrdersSearch();
		$query = $model->search($r_data);

		// 特定员工订单
		// $condition['o_user_id'] = $sys_user->id;

		// 获取筛选状态
		$screen_type = $request->get('screen_type');

		switch ($screen_type) {
		case 'wait': // 待审核
			$condition['o_status'] = [
				Orders::STATUS_WAIT_CHECK,
				Orders::STATUS_WAIT_CHECK_AGAIN,
				Orders::STATUS_WAIT_APP_UPLOAD_AGAIN,
			];
			break;

		case 'refuse': // 已拒绝
			$condition['o_status'] = Orders::STATUS_REFUSE;
			break;

		case 'revoke': // 已撤销
			$condition['o_status'] = Orders::STATUS_REVOKE;
			break;

		case 'cancel': // 已撤销
			$condition['o_status'] = Orders::STATUS_CANCEL;
			break;

		case 'paying': // 还款中
			$condition['o_status'] = Orders::STATUS_PAYING;
			break;

		case 'payover': // 已还清
			$condition['o_status'] = Orders::STATUS_PAY_OVER;
			break;

		default:
			$condition['o_status'] = [
				Orders::STATUS_WAIT_CHECK,
				Orders::STATUS_WAIT_CHECK_AGAIN,
				Orders::STATUS_WAIT_APP_UPLOAD_AGAIN,
				Orders::STATUS_REFUSE,
				Orders::STATUS_REVOKE,
				Orders::STATUS_CANCEL,
				Orders::STATUS_PAYING,
				Orders::STATUS_PAY_OVER,
			];
			break;
		}

		// 获取订单状态
		$query = $query->andWhere($condition);

		$querycount = clone $query;

		$pages = new \yii\data\Pagination(['totalCount' => $querycount->count()]);

		// 分页数据量
		$pages->pageSize = Yii::$app->params['page_size'];

		// 获取分页数据
		$data = $query->orderBy(['orders.o_created_at' => SORT_DESC])
			->offset($pages->offset)
			->limit($pages->limit)
			->asArray()
			->all();

		if ($request->isAjax && $request->isGet) {
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
				return ['status' => 1, 'message' => 'success', 'data' => $response];
			} else {
				return ['status' => 0, 'message' => 'no data'];
			}
		} else {
			return $this->renderPartial('list', [
				'list' => $data,
			]);
		}
	}
}