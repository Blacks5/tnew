<?php

namespace common\models;

use common\core\CoreCommonActiveRecord;

/**
 * This is the model class for table "order_images".
 *
 * @property integer $oi_id
 * @property string $oi_front_id
 * @property string $oi_back_id
 * @property string $oi_customer
 * @property string $oi_front_bank
 * @property string $oi_family_card_one
 * @property string $oi_family_card_two
 * @property string $oi_driving_license_one
 * @property string $oi_driving_license_two
 * @property string $oi_signature
 * @property string $oi_video
 *
 * 二审合同相关图片
 * @property string $oi_after_contract
 */
class OrderImages extends CoreCommonActiveRecord {
	/**
	 * @inheritdoc
	 */
	public static function tableName() {
		return 'order_images';
	}

	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			[['oi_front_id', 'oi_back_id', 'oi_customer', 'oi_front_bank', 'oi_proxy_prove', 'oi_after_contract', 'oi_pick_goods', 'oi_serial_num'], 'required'],
			[['oi_pick_goods', 'oi_serial_num'], 'safe'],
			[['oi_front_id', 'oi_back_id', 'oi_customer', 'oi_front_bank', 'oi_family_card_one', 'oi_family_card_two', 'oi_driving_license_one', 'oi_driving_license_two', 'oi_after_contract', 'oi_proxy_prove', 'oi_video'], 'string', 'max' => 60],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {
		return [
			'oi_id' => 'Oi ID',
			'oi_front_id' => '身份证正面',
			'oi_back_id' => '身份证背面',
			'oi_customer' => '客户现场照',
			'oi_front_bank' => '银行卡正面',
			'oi_family_card_one' => '户口本1',
			'oi_family_card_two' => '户口本2',
			'oi_driving_license_one' => '驾照1',
			'oi_driving_license_two' => '驾照2',
			'oi_after_contract' => '合同',
			'oi_signature' => '手写签名',
			'oi_video' => '录制视频',
			'oi_pick_goods' => '提货照',
			'oi_serial_num' => '串码照',
			'oi_proxy_prove' => '授权书',
		];
	}

	// 设置验证场景
	public function scenarios() {
		$scenarios = parent::scenarios();
		$scenarios['uploadFirst'] = [
			'oi_front_id',
			'oi_back_id',
			'oi_customer',
			'oi_front_bank',
			'oi_proxy_prove',
		];
		$scenarios['uploadAgain'] = [
			'oi_after_contract',
			'oi_pick_goods',
			'oi_serial_num',
			'oi_family_card_one',
			'oi_family_card_two',
			'oi_driving_license_one',
			'oi_driving_license_two',
		];
		return $scenarios;
	}
}
