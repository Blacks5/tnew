<?php
/**
 * Created by PhpStorm.
 * User: too
 * Date: 2017/8/13
 * Time: 21:49
 * @author too <hayto@foxmail.com>
 */

namespace common\services;

use api\components\CustomApiException;
use common\components\CustomCommonException;
use common\models\Customer;
use common\models\Goods;
use common\models\OrderImages;
use common\models\Orders;
use common\models\Product;
use common\models\Stores;
use common\models\User;
use WebSocket\Client;
use Yii;
use yii\db\Query;

class Order {
	// 微信应用app
	public static $app = null;
	// 七牛上传token
	public static $uptoken = null;

	/**
	 * 创建订单服务
	 * @param  [type] $params 数据参数
	 * @return [type]         boolean
	 */
	public function createOrder($params) {
		$session = Yii::$app->session;

		// 获取其他数据
		$params['o_is_auto_pay'] = isset($params['o_is_auto_pay']) && $params['o_is_auto_pay'] == 'on' ? 1 : 0;
		$params['o_is_free_pack_fee'] = isset($params['o_is_free_pack_fee']) && $params['o_is_free_pack_fee'] == 'on' ? 1 : 0;
		$params['o_is_add_service_fee'] = isset($params['o_is_add_service_fee']) && $params['o_is_add_service_fee'] == 'on' ? 1 : 0;
		$params['c_customer_id_card_endtime'] = isset($params['c_customer_id_card_endtime']) ? strtotime($params['c_customer_id_card_endtime']) : 0;

		$data['data'] = $params;

		// 获取系统用户数据
		$sys_user = $session->get('sys_user');

		// 开启事务
		$trans = Yii::$app->getDb()->beginTransaction();

		// 1.写入订单图片信息
		$images_model = new OrderImages();
		$images_model->oi_user_id = $sys_user->id;
		if (!$images_model->save(false)) {
			$trans->rollBack();
			$msg = $images_model->getFirstErrors();
			throw new CustomCommonException(reset($msg));
		}

		// 2.写customer表
		if ($customerModel = Customer::findOne(['c_customer_id_card' => $params['c_customer_id_card']])) {
			if ($customerModel->c_status == 0) {
				if ($customerModel->c_forbidden_time > $_SERVER['REQUEST_TIME']) {
					$trans->rollBack();
					$msg = '提交的订单被拒绝过,在' . date('Y-m-d H:i:s', $customerModel->c_forbidden_time) . '后才能再次借款';
					throw new CustomCommonException($msg);
				} else {
					// 解除黑名单
					$customerModel->c_status = 10;
					$customerModel->c_forbidden_time = 0;
				}
			}
			$customerModel->c_total_borrow_times += 1;
		} else {
			$customerModel = new Customer();
			$customerModel->c_total_borrow_times = 1;
		}

		$customerModel->load($data, 'data');
		if (!$customerModel->validate()) {
			$trans->rollBack();
			$msg = $customerModel->getFirstErrors();
			throw new CustomCommonException(reset($msg));
		}

		// // 四要素验证
		list($status, $error) = $this->checkCustomerInfo(
			$params['c_customer_name'],
			$params['c_customer_cellphone'],
			$params['c_customer_id_card'],
			$params['c_banknum']
		);

		if (!$status) {
			$trans->rollBack();
			throw new CustomCommonException($error);
		}

		$customerModel->c_created_at = $_SERVER['REQUEST_TIME'];
		if (!$customerModel->save(false)) {
			$trans->rollBack();
			throw new CustomCommonException('操作失败');
		}

		// 3.写入订单信息
		if (!$serial_id = \common\models\Tools::generateId($params['c_customer_id_card'])) {
			$trans->rollBack();
			throw new CustomCommonException('生成订单号异常');
		}
		$ordersModel = new Orders();
		$ordersModel->load($data, 'data');
		$ordersModel->o_serial_id = $serial_id; // 订单号
		$ordersModel->o_total_deposit = $params['g_goods_deposit']; // 定金
		$ordersModel->o_total_price = $params['g_goods_price']; // 总价格
		$ordersModel->o_user_id = $sys_user->id; // 提单业务员
		$ordersModel->o_images_id = $images_model->oi_id;
		$ordersModel->o_goods_num = 1; // 历史遗留问题，现在一个订单只能有一个商品
		$ordersModel->o_created_at = $_SERVER['REQUEST_TIME'];
		$ordersModel->o_customer_id = $customerModel->c_id; // 客户id

		if (!$ordersModel->validate()) {
			$trans->rollBack();
			$msg = $ordersModel->getFirstErrors();
			throw new CustomCommonException(reset($msg));
		}
		if (!$ordersModel->save(false)) {
			$trans->rollBack();
			throw new CustomCommonException('订单写入失败');
		}

		// 4.写入商品信息
		if (false === (new \yii\db\Query())->from(Product::tableName())->where([
			'p_status' => Product::STATUS_OK,
			'p_type' => $params['g_goods_type'],
			'p_id' => $params['o_product_id'],
		])->exists()) {
			$trans->rollBack();
			throw new CustomApiException('商品和产品类型不匹配');
		}

		if (0 > $params['g_goods_price']) {
			$trans->rollBack();
			throw new CustomApiException('商品价格异常');
		}
		if (0 > $params['g_goods_deposit']) {
			$trans->rollBack();
			throw new CustomApiException('首付金额异常');
		}

		$goodsModel = new Goods();
		$goodsModel->load($data, 'data');
		$goodsModel->g_order_id = $ordersModel->o_id;
		if (false === $goodsModel->validate()) {
			$trans->rollBack();
			$msg = $goodsModel->getFirstErrors();
			throw new CustomCommonException(reset($msg));
		}
		if (false === $goodsModel->save(false)) {
			$trans->rollBack();
			throw new CustomCommonException('商品写入失败');
		}

		// 提交数据
		$trans->commit();

		// 推送消息到后台
		$this->sendToWs($customerModel->c_customer_name, $ordersModel->o_id);

		return $ordersModel->o_id;
	}

	/**
	 * 验证客户身份信息
	 * @param  [type] $realname   [description]
	 * @param  [type] $mobile     [description]
	 * @param  [type] $idcard     [description]
	 * @param  [type] $creditcard [description]
	 * @return [type]             [description]
	 */
	public function checkCustomerInfo($realname, $mobile, $idcard, $creditcard) {
		// 生成唯一key
		$key = md5($realname . ':' . $idcard . ':' . $mobile . ':' . $creditcard);

		// 读取缓存中的查询结果
		if ($result = \Yii::$app->cache->get($key)) {
			if ($result == 'success') {
				return [true, ''];
			} else {
				return [false, $result];
			}
		} else {
			$bair = \Yii::$app->bair;

			$status = $bair->check([
				'idcard' => $idcard,
				'mobile' => $mobile,
				'creditcard' => $creditcard,
				'realname' => $realname,
			]);

			if ($status) {
				\Yii::$app->cache->set($key, 'success', 3600);
				return [true, ''];
			} else {
				\Yii::$app->cache->set($key, $bair->getError(), 3600);
				return [false, $bair->getError()];
			}
		}
	}

	/**
	 * 推送订单信息到后台
	 * @param  [type] $customer_name [description]
	 * @param  [type] $order_id      [description]
	 * @return [type]                [description]
	 */
	private function sendToWs($customer_name, $order_id)
    {
        $client = new Client(\Yii::$app->params['ws']);
        $string = '顾客:'. $customer_name. '产生了新订单';
        $data = [
            'cmd'=>'Orders:newOrderNotify',
            'data'=>[
                'message'=>$string,
                'order_id'=>$order_id
            ]
        ];
        $jsonData = json_encode($data, JSON_UNESCAPED_UNICODE);
        $client->send($jsonData);
    }

	/**
	 * 客户端提交订单，成功返回true，失败直接抛异常
	 * 调用此方法的控制器，需要得到微信的授权，还需要开启事物
	 * @param $params
	 * @return bool
	 * @throws CustomCommonException
	 * @author too <hayto@foxmail.com>
	 */
	public function addOrder($params) {
		$data['data'] = $params;

//        throw new CustomCommonException('通过');
		// 判断验证码【不再需要短信验证码2017-08-21】
		/*$verify = new Sms();
	        if(!$verify->verify($params['c_customer_cellphone'], $params['verify_code'])){
	            throw new CustomCommonException('验证码错误');
*/
		$user = \Yii::$app->getSession()->get('wechat_user'); // 微信资料
		$sys_user = (new \yii\db\Query())->from(User::tableName())->where(['wechat_openid' => $user->id])->one(); // 系统资料
		// 1写order_images表
		$images_model = new OrderImages();
		$images_model->oi_user_id = $sys_user['id'];
		if (!$images_model->save(false)) {
			$msg = $images_model->getFirstErrors();
			throw new CustomCommonException(reset($msg));
		}

		// 2写customer表
		if ($customerModel = Customer::findOne(['c_customer_id_card' => $params['c_customer_id_card']])) {
			if ($customerModel->c_status == 0) {
				if ($customerModel->c_forbidden_time > $_SERVER['REQUEST_TIME']) {
					throw new CustomCommonException('提交的订单被拒绝过,在' . date('Y-m-d H:i:s', $customerModel->c_forbidden_time) . '后才能再次借款');
				} else {
					// 解除黑名单
					$customerModel->c_status = 10;
					$customerModel->c_forbidden_time = 0;
				}
			}
			$customerModel->c_total_borrow_times += 1;
		} else {
			$customerModel = new Customer();
			$customerModel->c_total_borrow_times = 1;
		}
		$customerModel->load($data, 'data');
//        var_dump($customerModel->getAttributes());die;
		if (!$customerModel->validate()) {
			$msg = $customerModel->getFirstErrors();
			throw new CustomCommonException(reset($msg));
		}
//            $customerModel->c_total_money += $total_price - $total_deposit; //加上原来的借款总额  放到终审去做2017-01-02
		$customerModel->c_customer_addr_province = $params['c_customer_addr_province']; //
		//            $customerModel->c_total_borrow_times += 1;// 总借款次数 放到终审去做
		$customerModel->c_created_at = $_SERVER['REQUEST_TIME']; //

//        var_dump($customerModel->getAttributes());die;
		if (!$customerModel->save(false)) {
			throw new CustomCommonException('用户写入失败');
		}

		// 3写orders表
		$ordersModel = new Orders();
		$ordersModel->load($data, 'data');
		if (!$serial_id = \common\models\Tools::generateId($params['c_customer_id_card'])) {
			throw new CustomCommonException('生成订单号异常');
		}
		$ordersModel->o_serial_id = $serial_id; // 订单号
		$ordersModel->o_total_deposit = $params['g_goods_deposit']; // 定金
		$ordersModel->o_total_price = $params['g_goods_price']; // 总价格
		$ordersModel->o_user_id = $sys_user['id']; // 提单业务员
		$ordersModel->o_images_id = $images_model->oi_id;
		$ordersModel->o_goods_num = 1; // 历史遗留问题，现在一个订单只能有一个商品
		$ordersModel->o_created_at = $_SERVER['REQUEST_TIME'];
		$ordersModel->o_customer_id = $customerModel->c_id; // 客户id
		$ordersModel->o_is_auto_pay = $params['o_is_auto_pay']; // 银行代扣
		if (!$ordersModel->validate()) {
			$msg = $ordersModel->getFirstErrors();
			throw new CustomCommonException(reset($msg));
		}
		if (!$ordersModel->save(false)) {
			throw new CustomCommonException('订单写入失败');
		}
		// 4写goods表
		if (false === (new \yii\db\Query())->from(Product::tableName())->where(
			[
				'p_status' => Product::STATUS_OK, 'p_type' => $params['g_goods_type'],
				'p_id' => $params['o_product_id'],
			])->exists()) {
			throw new CustomApiException('商品和产品类型不匹配');
		}

		if (0 > $params['g_goods_price']) {
			throw new CustomApiException('商品价格异常');
		}
		if (0 > $params['g_goods_deposit']) {
			throw new CustomApiException('首付金额异常');
		}

		$goodsModel = new Goods();
		$goodsModel->load($data, 'data');
		$goodsModel->g_order_id = $ordersModel->o_id;
		if (false === $goodsModel->validate()) {
			$msg = $goodsModel->getFirstErrors();
			throw new CustomCommonException(reset($msg));
		}
		if (false === $goodsModel->save(false)) {
			throw new CustomCommonException('商品写入失败');
		}
		return true;
	}

	/**
	 * 获取订单信息
	 * @param  [type] $condition 查询条件
	 * @return [type]            [description]
	 */
	public function getOrder($condition) {
		return Orders::find()->select('*')
			->leftJoin(OrderImages::tableName(), 'o_images_id=oi_id')
			->leftJoin(Customer::tableName(), 'o_customer_id=c_id')
			->leftJoin(Product::tableName(), 'p_id=o_product_id')
			->leftJoin(Stores::tableName(), 'o_store_id=s_id')
			->leftJoin(User::tableName(), 'id=o_user_id')
			->andWhere($condition)
			->asArray()
			->one();
	}

	/**
	 * 修改订单图片信息
	 * @param  [type] $params 参数信息
	 * @return [type]         mixed
	 */
	public function modifyOrderImage($params) {
		$sys_user = Yii::$app->session->get('sys_user');

		// 获取订单相关信息
		$orderModel = Orders::findOne([
			'o_id' => $params['o_id'],
			'o_user_id' => $sys_user->id,
			'o_status' => [
				Orders::STATUS_NOT_COMPLETE,
				Orders::STATUS_WAIT_APP_UPLOAD_AGAIN,
			],
		]);

		$oi = new OrderImages;

		// 订单不存在
		if (!$orderModel) {
			throw new CustomCommonException('该订单不存在或已在审核');
		}

		$must_upload_1 = ['oi_front_id', 'oi_back_id', 'oi_customer', 'oi_front_bank', 'oi_proxy_prove'];

		$other_upload_1 = ['oi_other_1_1', 'oi_other_1_2', 'oi_other_1_3', 'oi_other_1_4'];

		$must_upload_2 = ['oi_pick_goods', 'oi_serial_num', 'oi_after_contract'];

		$other_upload_2 = ['oi_other_2_1', 'oi_other_2_2', 'oi_other_2_3', 'oi_other_2_4'];

		// 一审参数
		if ($orderModel->o_status == Orders::STATUS_NOT_COMPLETE) {
			// 其它图片
			$other_1 = [];
			// 开始上传
			foreach ($params as $k => $v) {
				if (in_array($k, $must_upload_1)) {
					if ($v) {
						if ($hash = $this->pullWxServerImagesToQiniu($v)) {
							$params[$k] = $hash;
							continue;
						} else {
							throw new CustomCommonException($oi->attributeLabels()[$k] . '上传失败');
						}
					} else {
						throw new CustomCommonException('请上传' . $oi->attributeLabels()[$k]);
					}
				} else if (in_array($k, $other_upload_1)) {
					if ($v) {
						if ($hash = $this->pullWxServerImagesToQiniu($v)) {
							$other_1[] = $hash;
						}
					}
					unset($params[$k]);
				}
			}
			// 拼装其它图片
			if ($other_1) {
				$params['oi_other_1'] = json_encode($other_1);
			}
		}

		// 二审参数
		if ($orderModel->o_status == Orders::STATUS_WAIT_APP_UPLOAD_AGAIN) {
			// 其它图片
			$other_2 = [];
			// 开始上传
			foreach ($params as $k => $v) {
				if (in_array($k, $must_upload_2)) {
					if ($v) {
						if ($hash = $this->pullWxServerImagesToQiniu($v)) {
							$params[$k] = $hash;
							continue;
						} else {
							throw new CustomCommonException($oi->attributeLabels()[$k] . '上传失败');
						}
					} else {
						throw new CustomCommonException('请上传' . $oi->attributeLabels()[$k]);
					}
				} else if (in_array($k, $other_upload_2)) {
					if ($v) {
						if ($hash = $this->pullWxServerImagesToQiniu($v)) {
							$other_2[] = $hash;
						}
					}
					unset($params[$k]);
				}
			}

			// 拼装其它图片
			if ($other_2) {
				$params['oi_other_2'] = json_encode($other_2);
			}
		}

		// 开启事务
		$trans = Yii::$app->getDb()->beginTransaction();

		$orderImagesModel = OrderImages::findOne([
			'oi_id' => $orderModel->o_images_id,
		]);

		// 一审参数
		if ($orderModel->o_status == Orders::STATUS_NOT_COMPLETE) {
			// 检测是否存在orderimages数据
			if (!$orderImagesModel) {
				$orderImagesModel = new OrderImages;
			}

			$data = ['data' => [
				'o_id' => $params['o_id'],
				'oi_front_id' => $params['oi_front_id'],
				'oi_back_id' => $params['oi_back_id'],
				'oi_customer' => $params['oi_customer'],
				'oi_front_bank' => $params['oi_front_bank'],
				'oi_proxy_prove' => $params['oi_proxy_prove'],
			]];

			isset($params['oi_other_1']) && $data['data']['oi_other_1'] = $params['oi_other_1'];

			$orderImagesModel->scenario = 'uploadFirst';

			// 设置订单状态为等待一审
			$orderModel->o_status = Orders::STATUS_WAIT_CHECK;
		}

		// 二审参数
		if ($orderModel->o_status == Orders::STATUS_WAIT_APP_UPLOAD_AGAIN) {
			if (!$orderImagesModel) {
				$trans->rollBack();
				throw new CustomCommonException('该订单异常');
			}

			$orderModel->scenario = 'clientValidate2';
			$orderModel->load(['data' => [
				'o_product_code' => $params['o_product_code'],
			]], 'data');
			if (!$orderModel->validate()) {
				$trans->rollBack();
				$msg = $orderModel->getFirstErrors();
				throw new CustomCommonException(reset($msg));
			}

			$data = ['data' => [
				'o_id' => $params['o_id'],
				'oi_pick_goods' => $params['oi_pick_goods'],
				'oi_serial_num' => $params['oi_serial_num'],
				'oi_after_contract' => $params['oi_after_contract'],
			]];

			isset($params['oi_other_2']) && $data['data']['oi_other_2'] = $params['oi_other_2'];

			$orderImagesModel->scenario = 'uploadAgain';

			// 设置订单状态为等待二审
			$orderModel->o_status = Orders::STATUS_WAIT_CHECK_AGAIN;
		}

		// 验证数据
		$orderImagesModel->load($data, 'data');
		if (!$orderImagesModel->validate()) {
			$trans->rollBack();
			$msg = $orderImagesModel->getFirstErrors();
			throw new CustomCommonException(reset($msg));
		}

		// 写入数据
		if (!$orderImagesModel->save(false)) {
			$trans->rollBack();
			throw new CustomCommonException('保存失败');
		}

		// 修改订单状态
		if (!$orderModel->save(false)) {
			$trans->rollBack();
			throw new CustomCommonException('保存失败');
		}

		// 提交数据
		$trans->commit();

		return true;
	}

	/**
	 * 拉取微信服务器图片到七牛服务器
	 * @param  [type] $mediaid [description]
	 * @return [type]          [description]
	 */
	public function pullWxServerImagesToQiniu($mediaid) {
		if (!static::$app) {
			$config = \Yii::$app->params['wechat'];
			static::$app = new \EasyWeChat\Foundation\Application($config);
		}

		// 获取uptoken
		if (!static::$uptoken) {
			static::$uptoken = (new \common\models\UploadFile)->genToken();
		}

		// 临时素材
		$temporary = static::$app->material_temporary;

		// 获取内容
		if ($content = $temporary->getStream($mediaid)) {
			$remote_server = 'http://up-z2.qiniu.com/putb64/-1';

			$base64 = chunk_split(base64_encode($content));

			try {
				$response = $this->postRequestQiniu($remote_server, static::$uptoken, $base64);

				if ($response) {
					if ($response = json_decode($response, true)) {
						return isset($response['key']) ? $response['key'] : false;
					}
				}

				return false;
			} catch (\EasyWeChat\Core\Exceptions\HttpException $e) {
				return false;
			}
		}

		return false;
	}

	/**
	 * base64上传到七牛云
	 * @param  [type] $remote_server [description]
	 * @param  [type] $uptoken       [description]
	 * @param  [type] $post          [description]
	 * @return [type]                [description]
	 */
	private function postRequestQiniu($remote_server, $uptoken, $post) {
		$headers[] = 'Content-Type:image/png';
		$headers[] = 'Authorization:UpToken ' . $uptoken;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $remote_server);
		//curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		//curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($ch, CURLOPT_TIMEOUT, 120);
		$data = curl_exec($ch);
		curl_close($ch);

		return $data;
	}

	/**
	 * 检测订单商品
	 * @param  [type] $params 商品信息
	 * @return [type]         返回结果
	 */
	public function checkOrderGoods($params) {
		$goodsModel = new Goods();
		$goodsModel->setAttributes($params, false);
		$goodsModel->scenario = 'clientValidate';
		if (false === $goodsModel->validate()) {
			$msg = $goodsModel->getFirstErrors();
			throw new CustomCommonException(reset($msg));
		}

		if (0 > $params['g_goods_price']) {
			throw new CustomCommonException('商品价格异常');
		}
		if (0 > $params['g_goods_deposit']) {
			throw new CustomCommonException('首付金额异常');
		}

		return true;
	}

	/**
	 * 检测订单信息是否合法
	 * @param  [type] $params 订单信息
	 * @return [type]         返回结果
	 */
	public function checkOrderInfo($params) {
		// 检测商品与产品类型是否匹配
		$match = (new \yii\db\Query())->from(Product::tableName())->where([
			'p_status' => Product::STATUS_OK,
			'p_type' => $params['g_goods_type'],
			'p_id' => $params['o_product_id']])->exists();

		if (false === $match) {
			throw new CustomCommonException('商品和产品类型不匹配');
		}

		// 获取其他数据
		$params['o_is_auto_pay'] = isset($params['o_is_auto_pay']) && $params['o_is_auto_pay'] == 'on' ? 1 : 0;
		$params['o_is_free_pack_fee'] = isset($params['o_is_free_pack_fee']) && $params['o_is_free_pack_fee'] == 'on' ? 1 : 0;
		$params['o_is_add_service_fee'] = isset($params['o_is_add_service_fee']) && $params['o_is_add_service_fee'] == 'on' ? 1 : 0;

		// 验证其他数据
		$ordersModel = new Orders();
		$ordersModel->setAttributes($params, false);
		$ordersModel->scenario = 'clientValidate';
		if (false === $ordersModel->validate()) {
			$msg = $ordersModel->getFirstErrors();
			throw new CustomCommonException(reset($msg));
		}

		return true;
	}

	/**
	 * 检测客户信息是否合法
	 * @param  [type] $params 	客户信息
	 * @return [type] $scenario 返回结果
	 * @return [type]         	返回结果
	 */
	public function checkOrderCustomer($params, $scenario = 'clientValidate1') {
		if ($customerModel = Customer::findOne(['c_customer_id_card' => $params['c_customer_id_card']])) {
			if ($customerModel->c_status == 0) {
				if ($customerModel->c_forbidden_time > $_SERVER['REQUEST_TIME']) {
					throw new CustomCommonException('提交的订单被拒绝过,在' . date('Y-m-d H:i:s', $customerModel->c_forbidden_time) . '后才能再次借款');
				} else {
					// 解除黑名单
					$customerModel->c_status = 10;
					$customerModel->c_forbidden_time = 0;
				}
			}
			$customerModel->c_total_borrow_times += 1;
		} else {
			$customerModel = new Customer();
			$customerModel->c_total_borrow_times = 1;
		}

		$params['c_customer_id_card_endtime'] = isset($params['c_customer_id_card_endtime']) ? strtotime($params['c_customer_id_card_endtime']) : 0;
		$customerModel->scenario = $scenario;

		$customerModel->setAttributes($params, false);
		if (false === $customerModel->validate()) {
			$msg = $customerModel->getFirstErrors();
			throw new CustomCommonException(reset($msg));
		}

		// 四要素验证
		if ($scenario == 'clientValidate1') {
			list($status, $error) = $this->checkCustomerInfo(
				$params['c_customer_name'],
				$params['c_customer_cellphone'],
				$params['c_customer_id_card'],
				$params['c_banknum']
			);

			if (!$status) {
				throw new CustomCommonException($error);
			}
		}

		return true;
	}
}