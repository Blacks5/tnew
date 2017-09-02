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
use Yii;
use yii\db\Query;

class Order {
	/**
	 * 创建订单服务
	 * @param  [type] $params 数据参数
	 * @return [type]         boolean
	 */
	public function createOrder($params) {
		$session = Yii::$app->session;

		// 获取其他数据
		$params['o_is_auto_pay'] = isset($params['o_is_auto_pay']) ? $params['o_is_auto_pay'] : 0;
		$params['o_is_free_pack_fee'] = isset($params['o_is_free_pack_fee']) ? $params['o_is_free_pack_fee'] : 0;
		$params['o_is_add_service_fee'] = isset($params['o_is_add_service_fee']) ? $params['o_is_add_service_fee'] : 0;
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

		$customerModel->c_created_at = $_SERVER['REQUEST_TIME'];
		if (!$customerModel->save(false)) {
			$trans->rollBack();
			throw new CustomCommonException('用户写入失败');
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
		return true;
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

		// 订单不存在
		if (!$orderModel) {
			throw new CustomCommonException('该订单不存在或已在审核');
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
			]];

			$orderImagesModel->scenario = 'uploadFirst';

			// 设置订单状态为等待一审
			$orderModel->o_status = Orders::STATUS_WAIT_CHECK;
		}

		// 二审参数
		if ($orderModel->o_status == Orders::STATUS_WAIT_APP_UPLOAD_AGAIN) {
			if (!$orderImagesModel) {
				throw new CustomCommonException('该订单异常');
			}

			$data = ['data' => [
				'o_id' => $params['o_id'],
				'oi_family_card_one' => $params['oi_family_card_one'],
				'oi_family_card_two' => $params['oi_family_card_two'],
				'oi_driving_license_one' => $params['oi_driving_license_one'],
				'oi_driving_license_two' => $params['oi_driving_license_two'],
				'oi_pick_goods' => $params['oi_pick_goods'],
				'oi_serial_num' => $params['oi_serial_num'],
				'oi_after_contract' => $params['oi_after_contract'],
			]];

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
		if(!$orderModel->save(false)){
			$trans->rollBack();
			throw new CustomCommonException('保存失败');
		}

		// 提交数据
		$trans->commit();

		return true;
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
		$params['o_is_auto_pay'] = isset($params['o_is_auto_pay']) ? $params['o_is_auto_pay'] : 0;
		$params['o_is_free_pack_fee'] = isset($params['o_is_free_pack_fee']) ? $params['o_is_free_pack_fee'] : 0;
		$params['o_is_add_service_fee'] = isset($params['o_is_add_service_fee']) ? $params['o_is_add_service_fee'] : 0;

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

		return true;
	}

	/*array(54) {
		  ["g_goods_type"]=>
		  string(1) "6"
		  ["g_goods_models"]=>
		  string(7) "6s plus"
		  ["g_goods_price"]=>
		  string(4) "6000"
		  ["g_goods_name"]=>
		  string(12) "apple6手机"
		  ["g_goods_deposit"]=>
		  string(3) "600"
		  ["c_customer_name"]=>
		  string(9) "李连杰"
		  ["c_customer_id_card"]=>
		  string(4) "5555"
		  ["c_customer_cellphone"]=>
		  string(11) "18890232122"
		  ["c_customer_id_card_endtime"]=>
		  string(10) "1111111111"
		  ["c_customer_county"]=>
		  string(3) "245"
		  ["c_customer_city"]=>
		  string(3) "343"
		  ["c_customer_province"]=>
		  string(2) "27"
		  ["c_customer_gender"]=>
		  string(1) "1"
		  ["c_customer_idcard_provider"]=>
		  string(18) "中江县公安局"
		  ["c_customer_qq"]=>
		  string(9) "466594257"
		  ["c_customer_wechat"]=>
		  string(6) "haytoo"
		  ["c_family_marital_status"]=>
		  string(1) "1"
		  ["c_family_marital_partner_name"]=>
		  string(6) "白云"
		  ["c_family_marital_partner_cellphone"]=>
		  string(11) "15888888888"
		  ["c_family_house_info"]=>
		  string(1) "1"
		  ["c_family_expenses"]=>
		  string(4) "1500"
		  ["c_family_income"]=>
		  string(5) "25000"
		  ["c_kinship_name"]=>
		  string(9) "张大爷"
		  ["c_kinship_relation"]=>
		  string(1) "7"
		  ["c_kinship_cellphone"]=>
		  string(11) "18999999999"
		  ["c_kinship_addr"]=>
		  string(18) "金牛区酷炫路"
		  ["c_customer_addr_province"]=>
		  string(2) "12"
		  ["c_customer_addr_city"]=>
		  string(2) "33"
		  ["c_customer_addr_county"]=>
		  string(2) "34"
		  ["c_customer_addr_detail"]=>
		  string(21) "金牛区来聊聊路"
		  ["c_customer_jobs_company"]=>
		  string(6) "腾讯"
		  ["c_customer_jobs_industry"]=>
		  string(1) "5"
		  ["c_customer_jobs_type"]=>
		  string(1) "1"
		  ["c_customer_jobs_section"]=>
		  string(9) "研发部"
		  ["c_customer_jobs_title"]=>
		  string(3) "CTO"
		  ["c_customer_jobs_is_shebao"]=>
		  string(1) "1"
		  ["c_customer_jobs_province"]=>
		  string(3) "123"
		  ["c_customer_jobs_city"]=>
		  string(3) "345"
		  ["c_customer_jobs_county"]=>
		  string(3) "234"
		  ["c_customer_jobs_detail_addr"]=>
		  string(21) "高新区有点酷路"
		  ["c_customer_jobs_phone"]=>
		  string(11) "02888888888"
		  ["c_other_people_relation"]=>
		  string(1) "2"
		  ["c_other_people_name"]=>
		  string(9) "周杰伦"
		  ["c_other_people_cellphone"]=>
		  string(11) "15999999999"
		  ["c_banknum"]=>
		  string(11) "62222222222"
		  ["c_bank"]=>
		  string(1) "1"
		  ["c_banknum_owner"]=>
		  string(6) "李逵"
		  ["o_is_auto_pay"]=>
		  string(1) "1"
		  ["o_store_id"]=>
		  string(2) "25"
		  ["o_remark"]=>
		  string(14) "我是sa注释"
		  ["o_product_id"]=>
		  string(2) "28"
		  ["o_user_id"]=>
		  string(2) "11"
		  ["verify_code"]=>
		  string(4) "1234"
		  ["c_customer_idcard_detail_addr"]=>
		  string(12) "的说法分"
		}
	*/

}