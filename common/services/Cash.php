<?php

/**
 * Description Content
 * @Author: MuMu
 * @Date:   2017-11-17 16:43:52
 * @Last Modified by:   MuMu
 * @Last Modified time: 2017-12-11 17:16:08
 */
namespace common\services;

use common\components\CustomCommonException;

class Cash extends Service {
	// 微服务地址
	protected $microServiceUrl = 'http://cash.api.tnew.cn/v1/';
	// 微服务地址【开发】
	protected $devMicroServiceUrl = 'http://cash.devapi.tnew.cn/v1/';
	// 获取每期还款详情路由
	private $queryPaymentRouter = '/orders/amount';
	// 创建现金贷路由
	private $createOrderRouter = '/orders/';
	// 获取现金贷列表
	private $queryOrderListsRouter = '/users/my';
	// 获取订单详情
	private $queryOrderRouter = '/orders/{id}';
	// 修改订单
	private $editOrderRouter = '/orders/{id}';
	// 保存上传图片
	private $saveOrderImageRouter = '/orders/{id}/images';
	// 取消订单
	private $cancelOrderRouter = '/orders/{id}';
	// 四要素验证
	private $queryFourFactorRouter = '/orders/factory';
	// 每一页显示的数据量
	private $range = 15;

	/**
	 * 获取查询收据
	 * @param  [type] $params [description]
	 * @return [type]         [description]
	 */
	public function queryPayment($params) {
		$url = $this->buildUrl($this->queryPaymentRouter);

		$res = $this->httpPost($url, $params);

		if ($res['success']) {
			return $res['data'];
		} else {
			throw new CustomCommonException($res['errors'][0]['message'], $res['errors'][0]['code']);
		}
	}

	/**
	 * 创建订单
	 * @param  array $params 订单参数
	 * @return [type]        订单相关数据
	 */
	public function createCashOrder($params) {
		$url = $this->buildUrl($this->createOrderRouter);

		$res = $this->httpPost($url, $params);

		if ($res['success']) {
			return $res['data'];
		} else {
			throw new CustomCommonException($res['errors'][0]['message']);
		}
	}

	/**
	 * 获取订单列表
	 * @param  integer $page        当前页码
	 * @param  integer $orderStatus 订单状态['1 已提交’,’2 待审核’,’3 审核拒绝’,’4 已通过’,’5 已还清’, ‘6 已取消’]
	 * @return array                订单数据
	 */
	public function queryOrderList($page, $orderStatus) {
		$url = $this->buildUrl($this->queryOrderListsRouter);

		$offset = ($page - 1) * $this->range;

		$params = [
			'range' => $this->range,
			'offset' => $offset,
			'terms' => [
				'status' => $orderStatus,
			],
		];

		$res = $this->httpGet($url, $params);

		if ($res['success']) {
			$page = $res['data']['offset'] / $res['data']['range'] + 1;
			$list = isset($res['data']['list']) ? $res['data']['list'] : [];
			return [
				$page,
				$list,
			];
		} else {
			throw new CustomCommonException($res['errors'][0]['message']);
		}
	}

	/**
	 * 检测联系人信息是否合法
	 * @param  array  $params 联系人参数
	 * @return array
	 */
	public function checkContactInfo(array $params) {
		$data = [];

		$cashModel = new \common\models\Cash();
		$cashModel->scenario = 'contactInfo';

		for ($k = 1; $k <= 1000; $k++) {
			if (!isset($params['serial' . $k])) {
				break;
			}

			$temp = [
				'contactName' => $params['contactName' . $k],
				'contactRelation' => $params['contactRelation' . $k],
				'contactPhone' => $params['contactPhone' . $k],
			];

			$data[] = $temp;

			$cashModel->load([
				'data' => $temp,
			], 'data');

			if (false === $cashModel->validate()) {
				$msg = $cashModel->getFirstErrors();
				throw new CustomCommonException(reset($msg));
			}
		}

		return $data;
	}

	/**
	 * 获取订单数据
	 * @param  integer $orderId 订单ID
	 * @return array            订单数据
	 */
	public function queryOrder($orderId) {
		$url = $this->buildUrl($this->queryOrderRouter, ['id' => $orderId]);

		$res = $this->httpGet($url);

		if ($res['success']) {
			return $res['data'];
		} else {
			throw new CustomCommonException($res['errors'][0]['message']);
		}
	}

	/**
	 * 四要素验证
	 * @param  array $params 四要素参数
	 * @return boolean       验证结果
	 */
	public function queryFourFactor($params) {
		$url = $this->buildUrl($this->queryFourFactorRouter);
		print_r($url);
		print_r($params);
		$res = $this->httpPost($url , $params);
		print_r($res);
		if ($res['success']) {
			return true;
		} else {
			throw new CustomCommonException($res['errors'][0]['message']);
		}
	}

	/**
	 * 编辑订单
	 * @param  array $params 修改订单的数据
	 * @return array        订单数据
	 */
	public function editOrder($orderId, $params) {
		$url = $this->buildUrl($this->editOrderRouter, ['id' => $orderId]);

		$res = $this->httpPatch($url, $params);

		if ($res['success']) {
			return $res['data'];
		} else {
			throw new CustomCommonException($res['errors'][0]['message']);
		}
	}

	/**
	 * 保存上传图片
	 * @param  string $orderId 订单ID
	 * @param  array $params   图片参数
	 * @return array           订单数据
	 */
	public function saveOrderImage($orderId, $params) {
		$url = $this->buildUrl($this->saveOrderImageRouter, ['id' => $orderId]);

		$res = $this->httpPost($url, $params);

		if ($res['success']) {
			return $res['data'];
		} else {
			throw new CustomCommonException($res['errors'][0]['message']);
		}
	}

	/**
	 * 取消订单
	 * @param  array $params 订单参数
	 * @return [type]        订单相关数据
	 */
	public function cancelCashOrder($params) {
		$url = $this->buildUrl($this->cancelOrderRouter, ['id' => $params['orderID']]);

		$res = $this->httpDelete($url, $params);

		if ($res['success']) {
			return $res['data'];
		} else {
			throw new CustomCommonException($res['errors'][0]['message']);
		}
	}
}