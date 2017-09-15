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
use common\models\Product;
use common\models\Repayment;
use common\models\RepaymentSearch;
use common\models\Stores;
use common\models\UploadFile;
use common\models\User;
use common\services\Order;
use wechat\Tools\Wechat;
use Yii;
use yii\db\Query;
use yii\web\Response;

class OrderController extends BaseController {
	// 创建订单
	public function actionCreateOrder() {
		$request = Yii::$app->request;
		$session = Yii::$app->session;

		if ($request->isAjax && $request->isPost) {
			Yii::$app->getResponse()->format = Response::FORMAT_JSON;
			try {
				$params = $request->post();
				$orderService = new Order();
				$o_id = $orderService->createOrder($params);
				return ['status' => 1, 'message' => '提交成功' , 'o_id' => $o_id];
			} catch (CustomCommonException $e) {
				return ['status' => 0, 'message' => $e->getMessage()];
			} catch (\Exception $e) {
				throw $e;
				return ['status' => 0, 'message' => '网络异常'];
			}
		} else {
			// 获取系统用户数据
			$sys_user = $session->get('sys_user');

			// 获取当前销售人员对应所有店铺信息
			$sub = (new Query())->from('stores_saleman')
				->select(['ss_store_id'])
				->where(['ss_saleman_id' => $sys_user->id]);

			// 可选商户 同一个区县
			$stores = (new Query())->select(['s_id', 's_name'])
				->from(Stores::tableName())
				->where(['s_status' => Stores::STATUS_ACTIVE, 's_county' => $sys_user['county'], 's_id' => $sub])
				->all();

			// 商品类型
			$goods_type = Yii::$app->params['goods_type'];

			// 可选产品
			$products = (new Query())->select(['p_id', 'p_name', 'p_month_rate', 'p_period', 'p_add_service_fee', 'p_free_pack_fee', 'p_finance_mangemant_fee', 'p_customer_management'])
				->from(Product::tableName())
				->where(['p_status' => Product::STATUS_OK])
				->all();

			//省市区JSON
			$data_json = $this->actionGetcity();

			return $this->renderPartial('create', [
				'data_json' => $data_json,
				'data' => [
					'stores' => $stores,
					'goods_type' => $goods_type,
					'products' => $products,
					'marital_status' => Yii::$app->params['marital_status'],
					'bank_list' => Yii::$app->params['bank_list'],
					'house_info' => Yii::$app->params['house_info'],
					'kinship' => Yii::$app->params['kinship'],
					'company_kind' => Yii::$app->params['company_kind'],
					'company_type' => Yii::$app->params['company_type'],
				],
				'js' => Wechat::jssdk(),
			]);
		}
	}

	// 验证每一步数据是否合法
	public function actionCheckStep() {
		$request = Yii::$app->request;

		if ($request->isAjax && $request->isPost) {
			Yii::$app->getResponse()->format = Response::FORMAT_JSON;

			$actionStep = $request->post('actionStep', 1);

			$orderService = new Order;

			$params = $request->post();

			unset($params['actionStep']);

			try {
				switch (intval($actionStep)) {
				case 1: // 检测订单商品信息是否合法
					$orderService->checkOrderGoods($params);
					break;

				case 2: // 检测订单信息是否合法
					$orderService->checkOrderInfo($params);
					break;

				case 3: // 检测客户信息是否合法
					$orderService->checkOrderCustomer($params, 'clientValidate1');
					break;

				case 4: // 检测客户单位信息是否合法
					$orderService->checkOrderCustomer($params, 'clientValidate2');
					break;

				case 5: // 检测客户单位信息是否合法
					$orderService->checkOrderCustomer($params, 'clientValidate3');
					break;

				default:
					return ['status' => 0, 'message' => '验证失败'];
					break;
				}

				return ['status' => 1, 'message' => '验证成功'];
			} catch (CustomCommonException $e) {
				return ['status' => 0, 'message' => $e->getMessage()];
			}
		}
	}

	/**
	 * @author lilaotou <liwansen@foxmail.com>
	 * 处理省市区
	 */
	public function actionGetcity() {
		$cache = Yii::$app->cache;

		if ($json = $cache->get('city_json_data')) {
			return $json;
		}

		$rows = (new \yii\db\Query())
			->select(['*'])
			->from('too_region')
			->where(['parent_id' => 1])
			->all();
		$data = [];
		foreach ($rows as $k => $v) {
			$data[$k]['label'] = $v['region_name'];
			$data[$k]['value'] = $v['region_id'];
			$rows_2 = (new \yii\db\Query())
				->select(['*'])
				->from('too_region')
				->where(['parent_id' => $v['region_id']])
				->all();
			if ($rows_2) {
				$children = [];
				foreach ($rows_2 as $k1 => $v1) {
					$children[$k1]['label'] = $v1['region_name'];
					$children[$k1]['value'] = $v1['region_id'];
					$rows_3 = (new \yii\db\Query())
						->select(['*'])
						->from('too_region')
						->where(['parent_id' => $v1['region_id']])
						->all();
					if ($rows_3) {
						$children2 = [];
						foreach ($rows_3 as $k2 => $v2) {
							$children2[$k2]['label'] = $v2['region_name'];
							$children2[$k2]['value'] = $v2['region_id'];
						}

						$children[$k1]['children'] = $children2;
					}
				}
				$data[$k]['children'] = $children;
			}
		}
		$json = json_encode($data);

		$cache->set('city_json_data', $json);

		return $json;
	}

	// 历史订单
	public function actionOrderList() {
		$request = Yii::$app->request;

		if ($request->isAjax && $request->isGet) {
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
			$query = $query->andWhere(['o_user_id' => $sys_user->id]);

			// 获取筛选状态
			$screen_type = $request->get('screen_type');

			switch ($screen_type) {
			case 'near': // 近一月
				$query = $query->andWhere(['>=', 'o_created_at', strtotime(date('Y-m-d', strtotime('-1 Month')))]);
				$query = $query->andWhere(['o_status' => [
					Orders::STATUS_REFUSE,
					Orders::STATUS_REVOKE,
					Orders::STATUS_CANCEL,
					Orders::STATUS_PAYING,
					Orders::STATUS_PAY_OVER,
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

			Yii::$app->getResponse()->format = Response::FORMAT_JSON;

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
					'o_operator_remark' => $item['o_operator_remark'],
				];
			}

			if ($response) {
				return ['status' => 1, 'message' => 'success', 'data' => [
					'data' => $response,
					'page' => $pages->page,
				]];
			} else {
				return ['status' => 0, 'message' => 'no data', 'data' => [
					'data' => [],
					'page' => $pages->page,
				]];
			}
		} else {
			return $this->renderPartial('list' , [
				'js' => Wechat::jssdk(),
			]);
		}
	}

	// 待审订单
	public function actionWaitOrderList() {
		$request = Yii::$app->request;

		if ($request->isAjax && $request->isGet) {
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
			$query = $query->andWhere(['o_user_id' => $sys_user->id]);

			// 获取筛选状态
			$screen_type = $request->get('screen_type');

			switch ($screen_type) {
			case 'upload': // 等待上传照片
				$query = $query->andWhere(['o_status' => Orders::STATUS_NOT_COMPLETE]);
				break;

			case 'wait': // 信息已完成后，待一审
				$query = $query->andWhere(['o_status' => Orders::STATUS_WAIT_CHECK]);
				break;

			case 'again_upload': // 等待二次上传照片
				$query = $query->andWhere(['o_status' => Orders::STATUS_WAIT_APP_UPLOAD_AGAIN]);
				break;

			case 'again_wait': // 待二审
				$query = $query->andWhere(['o_status' => Orders::STATUS_WAIT_CHECK_AGAIN]);
				break;

			default:
				$query = $query->andWhere(['o_status' => [
					Orders::STATUS_WAIT_CHECK,
					Orders::STATUS_WAIT_CHECK_AGAIN,
					Orders::STATUS_WAIT_APP_UPLOAD_AGAIN,
					Orders::STATUS_NOT_COMPLETE,
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

			Yii::$app->getResponse()->format = Response::FORMAT_JSON;

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
					'o_operator_remark' => $item['o_operator_remark'],
				];
			}

			if ($response) {
				return ['status' => 1, 'message' => 'success', 'data' => [
					'data' => $response,
					'page' => $pages->page,
				]];
			} else {
				return ['status' => 0, 'message' => 'no data', 'data' => [
					'data' => [],
					'page' => $pages->page,
				]];
			}
		} else {
			return $this->renderPartial('wait_list' , [
				'js' => Wechat::jssdk(),
			]);
		}
	}

	// 逾期订单
	public function actionOverdueOrderList() {
		$request = Yii::$app->request;

		if ($request->isAjax && $request->isGet) {
			$session = Yii::$app->session;

			// 关键字查询
			$keywords = $request->get('keywords');

			// 查询订单
			$model = new RepaymentSearch();
			$query = $model->repaymenlist([
				'RepaymentSearch' => [
					'customer_name' => $keywords,
				]]);

			$query->andWhere(['>', 'r_overdue_day', 0]);
			$query->andWhere(['r_status' => Repayment::STATUS_NOT_PAY]);

			// 获取系统用户数据
			$sys_user = $session->get('sys_user');

			// 特定员工订单
			$query->andWhere(['o_user_id' => $sys_user->id]);

			// 获取筛选状态
			$screen_type = $request->get('screen_type');

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

			Yii::$app->getResponse()->format = Response::FORMAT_JSON;

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
					'r_overdue_day' => $item['r_overdue_day'],
					'r_overdue_money' => round($item['r_overdue_money'], 2),
					'r_total_repay' => round($item['r_total_repay'], 2),
					'r_principal' => round($item['r_principal'], 2),
					'r_interest' => round($item['r_interest'], 2),
					'r_serial_no' => $item['r_serial_no'],
					'r_add_service_fee' => round($item['r_add_service_fee'], 2),
					'r_free_pack_fee' => round($item['r_free_pack_fee'], 2),
					'r_finance_mangemant_fee' => round($item['r_finance_mangemant_fee'], 2),
					'r_customer_management' => round($item['r_customer_management'], 2),
					'o_status' => $item['o_status'],
				];
			}

			if ($response) {
				return ['status' => 1, 'message' => 'success', 'data' => [
					'data' => $response,
					'page' => $pages->page,
				]];
			} else {
				return ['status' => 0, 'message' => 'no data', 'data' => [
					'data' => [],
					'page' => $pages->page,
				]];
			}
		} else {
			return $this->renderPartial('overdue_list' , [
				'js' => Wechat::jssdk(),
			]);
		}
	}

	// 取消订单
	public function actionCancelOrder() {
		$request = Yii::$app->request;

		if ($request->isAjax && $request->isPost) {
			Yii::$app->response->format = Response::FORMAT_JSON;
			// 获取取消参数
			$o_id = intval($request->post('o_id', 0));
			$remark = trim($request->post('remark', ''));
			// 参数不合法
			if (!$o_id) {
				return ['status' => 0, 'message' => '缺少必要参数'];
			}

			if (!$remark) {
				return ['status' => 0, 'message' => '请填写取消原因'];
			}

			$sys_user = Yii::$app->session->get('sys_user');

			// 获取订单相关信息
			$orderModel = Orders::findOne([
				'o_id' => $o_id,
				'o_user_id' => $sys_user->id,
				'o_status' => [
					Orders::STATUS_WAIT_CHECK,
					Orders::STATUS_WAIT_CHECK_AGAIN,
					Orders::STATUS_NOT_COMPLETE,
					Orders::STATUS_WAIT_APP_UPLOAD_AGAIN,
				],
			]);

			// 订单
			if ($orderModel) {
				$orderModel->o_status = Orders::STATUS_CANCEL;
				$orderModel->o_operator_id = $sys_user->id;
				$orderModel->o_operator_realname = $sys_user->realname;
				$orderModel->o_operator_date = $_SERVER['REQUEST_TIME'];
				$orderModel->o_operator_remark = $remark;

				// 更新当担信息
				if (!$orderModel->save(false)) {
					return ['status' => 0, 'message' => '操作失败'];
				}

				return ['status' => 1, 'message' => '操作成功'];
			} else {
				return ['status' => 0, 'message' => '该订单不存在'];
			}
		}
	}

	// 上传照片
	public function actionUploadImage() {
		$request = Yii::$app->request;

		if ($request->isAjax && $request->isPost) {
			// 设置响应格式
			Yii::$app->response->format = Response::FORMAT_JSON;

			// 获取base64图片数据
			$base64 = $request->post('image');

			return ['status' => 1, 'message' => '操作成功'];
		} else {
			$config = \Yii::$app->params['wechat'];

			// 获取删除参数
			$o_id = intval($request->get('o_id', 0));
			// 参数不合法
			if (!$o_id) {
				return $this->randerError('缺少必要参数', '缺少必要参数，不能进行必要操作');
			}

			$sys_user = Yii::$app->session->get('sys_user');

			// 获取订单相关信息
			$order = (new Order)->getOrder([
				'o_id' => $o_id,
				'o_user_id' => $sys_user->id,
				'o_status' => [
					Orders::STATUS_NOT_COMPLETE,
					Orders::STATUS_WAIT_APP_UPLOAD_AGAIN,
				],
			]);

			// 订单
			if ($order) {
				return $this->renderPartial('upload', [
					'order' => $order,
					'uptoken' => (new UploadFile)->genToken(),
					'js' => Wechat::jssdk(),
				]);
			} else {
				return $this->randerError('订单不存在', '该订单不存在或已取消，不能上传照片');
			}
		}
	}

	/**
	 * 客户端获取七牛上传token
	 * @return array
	 * @author 涂鸿 <hayto@foxmail.com>
	 */
	public function actionGetQntoken() {
		$model = new UploadFile();
		$token = $model->genToken();
		return ['status' => 1, 'message' => 'ok', 'data' => $token];
	}

	// 修改订单
	public function actionModifyOrder() {
		$request = Yii::$app->request;

		if ($request->isAjax && $request->isPost) {
			Yii::$app->response->format = Response::FORMAT_JSON;
			// 获取取消参数
			$o_id = intval($request->post('o_id', 0));
			// 动作类型
			$actionType = $request->post('actionType', '');

			// 参数不合法
			if (!$o_id) {
				return ['status' => 0, 'message' => '缺少必要参数'];
			}

			// 操作类型不合法
			if ($actionType != 'upload' && $actionType != 'modify') {
				return ['status' => 0, 'message' => '缺少必要参数'];
			}

			try {
				$orderService = new Order;

				if ($actionType == 'upload') {
					$orderService->modifyOrderImage([
						'o_id' => $o_id,
						'oi_front_id' => $request->post('oi_front_id', ''),
						'oi_back_id' => $request->post('oi_back_id', ''),
						'oi_customer' => $request->post('oi_customer', ''),
						'oi_front_bank' => $request->post('oi_front_bank', ''),
						'oi_family_card_one' => $request->post('oi_family_card_one', ''),
						'oi_family_card_two' => $request->post('oi_family_card_two', ''),
						'oi_driving_license_one' => $request->post('oi_driving_license_one', ''),
						'oi_driving_license_two' => $request->post('oi_driving_license_two', ''),
						'oi_pick_goods' => $request->post('oi_pick_goods', ''),
						'oi_serial_num' => $request->post('oi_serial_num', ''),
						'oi_after_contract' => $request->post('oi_after_contract', ''),
						'oi_proxy_prove' => $request->post('oi_proxy_prove', ''),
						'o_product_code' => $request->post('o_product_code', ''),
						'oi_other_1_1' => $request->post('oi_other_1_1', ''),
						'oi_other_1_2' => $request->post('oi_other_1_2', ''),
						'oi_other_1_3' => $request->post('oi_other_1_3', ''),
						'oi_other_1_4' => $request->post('oi_other_1_4', ''),
						'oi_other_2_1' => $request->post('oi_other_2_1', ''),
						'oi_other_2_2' => $request->post('oi_other_2_2', ''),
						'oi_other_2_3' => $request->post('oi_other_2_3', ''),
						'oi_other_2_4' => $request->post('oi_other_2_4', ''),
					]);
				} else if ($actionType == 'modify') {

				}

				return ['status' => 1, 'message' => '保存成功'];
			} catch (CustomCommonException $e) {
				return ['status' => 0, 'message' => $e->getMessage()];
			}
		} else {

		}
	}

}