<?php

/**
 * 现金贷业务控制器
 * @Author: MuMu
 * @Date:   2017-11-16 09:38:35
 * @Last Modified by:   MuMu
 * @Last Modified time: 2017-11-23 17:46:14
 */
namespace wechat\controllers;

use common\components\CustomCommonException;
use common\components\Helper;
use common\services\Cash;
use wechat\Tools\Wechat;
use Yii;
use yii\web\Response;

class CashController extends BaseController {
	// 创建订单
	public function actionCreateOrder() {
		$request = Yii::$app->request;
		$session = Yii::$app->session;

		if ($request->isAjax && $request->isPost) {
			Yii::$app->getResponse()->format = Response::FORMAT_JSON;

			$actionStep = $request->post('actionStep', 1);

			$params = $request->post();

			unset($params['actionStep']);

			$cashModel = new \common\models\Cash();
			$cashModel->load([
				'data' => $params,
			], 'data');

			$cashModel->scenario = 'orderInfo';

			if (false === $cashModel->validate()) {
				$msg = $cashModel->getFirstErrors();
				return ['status' => 0, 'message' => reset($msg)];
			}

			// 创建订单
			$params = [
				'customerID' => 0,
				'amount' => $request->post('loanAmount', 0),
				'cycle' => $request->post('installmentCycle', ''),
				'period' => $request->post('installmentPeriod', ''),
				'isFreePackFee' => $request->post('isProtectionFee', 0) ? 1 : 0,
				'isAddServiceFee' => $request->post('isVipServiceFee', 0) ? 1 : 0,
				'bankNumber' => $request->post('bankCardNo', ''),
				'customerName' => $request->post('realName', ''),
				'cardNumber' => $request->post('certNo', ''),
				'phone' => $request->post('mobileNo', ''),
				'bankPhone' => $request->post('bankMobileNo', ''),
				'address' => $request->post('address', ''),
			];

			try {
				$cash = new Cash;
				$res = $cash->createCashOrder($params);

				return ['status' => 1, 'message' => '提交成功', 'data' => [
					'orderId' => $res['id'],
				]];
			} catch (CustomCommonException $e) {
				return ['status' => 0, 'message' => $e->getMessage()];
			}
		} else {
			// 获取分期方式配置
			$installmentCycle = Yii::$app->params['installmentCycle'];
			$vipServiceFee = Yii::$app->params['vipServiceFee'];
			$protectionFee = Yii::$app->params['protectionFee'];

			// 渲染模板
			return $this->renderPartial('create', [
				'installmentCycle' => json_encode($installmentCycle, JSON_UNESCAPED_UNICODE),
				'vipServiceFee' => $vipServiceFee,
				'protectionFee' => $protectionFee,
			]);
		}
	}

	// 获取每期还款金额
	public function actionPayment() {
		$request = Yii::$app->request;

		if ($request->isAjax && $request->isPost) {
			Yii::$app->getResponse()->format = Response::FORMAT_JSON;

			$params = [
				'amount' => $request->post('loanAmount', 0),
				'cycle' => $request->post('installmentCycle', ''),
				'period' => $request->post('installmentPeriod', ''),
				'isFreePackFee' => intval($request->post('isProtectionFee', 0)),
				'isAddServiceFee' => intval($request->post('isVipServiceFee', 0)),
			];

			try {
				$cash = new Cash;
				$res = $cash->queryPayment($params);

				return ['status' => 1, 'message' => 'ok', 'data' => [
					'vipServiceFee' => Helper::currency($res['addServiceFee'], 2),
					'protectionFee' => Helper::currency($res['freePackFee'], 2),
					'periodAmount' => Helper::currency($res['periodAmount'], 2),
				]];
			} catch (CustomCommonException $e) {
				return ['status' => 0, 'message' => $e->getMessage()];
			}
		}
	}

	// 验证每一步数据是否合法
	public function actionCheckStep() {
		$request = Yii::$app->request;

		if ($request->isAjax && $request->isPost) {
			Yii::$app->getResponse()->format = Response::FORMAT_JSON;

			$actionStep = $request->post('actionStep', 1);

			$params = $request->post();

			unset($params['actionStep']);

			$cashModel = new \common\models\Cash();
			$cashModel->load([
				'data' => $params,
			], 'data');

			switch (intval($actionStep)) {
			case 1: // 检测贷款信息是否合法
				$cashModel->scenario = 'loanInfo';
				break;

			case 2: // 检测客户信息是否合法
				$cashModel->scenario = 'customerInfo';
				break;

			case 3: // 验证详细信息
				return ['status' => 1, 'message' => '验证成功'];
				break;

			case 4: // 验证联系人信息是否合法
				try {
					$cash = new Cash;
					$cash->checkContactInfo($params);
					return ['status' => 1, 'message' => '验证成功'];
				} catch (CustomCommonException $e) {
					return ['status' => 0, 'message' => $e->getMessage()];
				}
				break;

			default:
				return ['status' => 0, 'message' => '验证失败'];
				break;
			}

			if (false === $cashModel->validate()) {
				$msg = $cashModel->getFirstErrors();
				return ['status' => 0, 'message' => reset($msg)];
			}

			return ['status' => 1, 'message' => '验证成功'];
		}
	}

	// 订单列表
	public function actionOrderList() {
		$request = Yii::$app->request;

		if ($request->isAjax && $request->isPost) {
			Yii::$app->getResponse()->format = Response::FORMAT_JSON;
			// 关键字查询
			$keywords = $request->post('keywords');
			// 获取当前页码
			$page = intval($request->post('page', 1));
			// 获取订单状态
			$status = intval($request->post('status', 0));

			try {
				$cash = new Cash;
				list($page, $list) = $cash->queryOrderList($page, $status);

				return ['status' => 1, 'message' => 'ok', 'data' => [
					'data' => $list,
					'page' => $page,
				]];
			} catch (CustomCommonException $e) {
				return ['status' => 0, 'message' => $e->getMessage()];
			}

		} else {
			return $this->renderPartial('list', [
				'js' => Wechat::jssdk(),
			]);
		}
	}

	// 操作成功
	public function actionSuccess() {
		$request = Yii::$app->request;

		$orderId = intval($request->post('orderId', 0));
		return $this->renderPartial('success', [
			'orderId' => $orderId,
		]);
	}

	// 编辑订单
	public function actionEditOrder() {
		$request = Yii::$app->request;

		if ($request->isAjax && $request->isPost) {
			Yii::$app->getResponse()->format = Response::FORMAT_JSON;

			$orderId = intval($request->post('orderId', 0));

			$post = $request->post();

			unset($post['orderId']);

			// $cashModel = new \common\models\Cash();
			// $cashModel->load([
			// 	'data' => $params,
			// ], 'data');

			// $cashModel->scenario = 'orderInfo';

			// if (false === $cashModel->validate()) {
			// 	$msg = $cashModel->getFirstErrors();
			// 	return ['status' => 0, 'message' => reset($msg)];
			// }

			// 订单订单
			$params = [
				'gender' => $request->post('gender', '男'),
				'marital' => $request->post('marital', ''),
				'cardAddress' => $request->post('cardAddress', ''),
				'currentAddress' => $request->post('currentAddress', ''),
				'jobName' => $request->post('jobName', ''),
				'jobAddress' => $request->post('jobAddress', ''),
				'jobPhone' => $request->post('jobPhone', ''),
				'wechat' => $request->post('wechat', ''),
				'qq' => $request->post('qq', ''),
				'alipay' => $request->post('alipay', ''),
			];

			try {
				$cash = new Cash;

				// 验证联系人信息
				$contacts = $cash->checkContactInfo($post);

				// 重新组装联系人信息
				foreach ($contacts as $k => $item) {
					$contacts[$k] = [
						'name' => $item['contactName'],
						'relation' => $item['contactRelation'],
						'phone' => $item['contactPhone'],
					];
				}

				// 封装联系人信息进数据
				$params['contact'] = json_encode($contacts, JSON_UNESCAPED_UNICODE);

				// 查询订单详情
				$res = $cash->queryOrder($orderId);

				// 获取必要数据
				$params['id'] = isset($res['orderID']) ? $res['orderID'] : $orderId;
				$params['customerName'] = isset($res['card']['name']) ? $res['card']['name'] : '';
				$params['address'] = isset($res['address']) ? $res['address'] : '';
				$params['bankNumber'] = isset($res['bank']['number']) ? $res['bank']['number'] : '';
				$params['bankPhone'] = isset($res['bank']['phone']) ? $res['bank']['phone'] : '';
				$params['phone'] = isset($res['phone']) ? $res['phone'] : '';
				$params['cardNumber'] = isset($res['card']['number']) ? $res['card']['number'] : '';

				$res = $cash->editOrder($orderId, $params);

				return ['status' => 1, 'message' => '提交成功', 'data' => [
					'orderId' => $res['id'],
				]];
			} catch (CustomCommonException $e) {
				return ['status' => 0, 'message' => $e->getMessage()];
			}
		} else {
			$orderId = intval($request->get('orderId', 0));

			// 获取婚姻状况配置
			$maritalSituation = Yii::$app->params['maritalSituation'];
			$contactRelationship = Yii::$app->params['contactRelationship'];

			return $this->renderPartial('edit', [
				'maritalSituation' => json_encode($maritalSituation, JSON_UNESCAPED_UNICODE),
				'contactRelationship' => json_encode($contactRelationship, JSON_UNESCAPED_UNICODE),
				'orderId' => $orderId,
				'js' => Wechat::jssdk(),
			]);
		}
	}

	public function actionUploadOrderImg() {
		$request = Yii::$app->request;

		if ($request->isAjax && $request->isPost) {

		} else {
			$orderId = intval($request->get('orderId', 0));

			try {
				// 查询订单详情
				$cash = new Cash;
				$order = $cash->queryOrder($orderId);

				if (in_array($order['orderStatus'], [2, 4, 5, 7])) {
					return $this->renderPartial('upload', [
						'orderId' => $orderId,
						'order' => $order,
						'js' => Wechat::jssdk(),
					]);
				}
			} catch (CustomCommonException $e) {
				return $this->renderPartial('fail');
			}
		}
	}

	/**
	 * 上传照片
	 * @return void
	 */
	public function actionUpload() {
		$request = Yii::$app->request;

		if ($request->isAjax && $request->isPost) {
			// 获取meidaID
			$mediaId = $request->post('mediaId', '');

			if ($mediaId) {
				try {
					// 上传
					$files = new Files;
					$res = $files->upload($mediaId);

					return ['status' => 1, 'message' => '上传成功', 'data' => [
						'uuid' => $res['uuid'],
					]];
				} catch (CustomCommonException $e) {
					return ['status' => 0, 'message' => '上传失败'];
				}
			}

			return ['status' => 0, 'message' => '上传失败'];
		}
	}

	// 操作成功
	public function actionCancle() {
		return $this->renderPartial('fail');
	}
}