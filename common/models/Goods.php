<?php

namespace common\models;

use common\core\CoreCommonActiveRecord;

/**
 * This is the model class for table "goods".
 *
 * @property integer $g_id
 * @property integer $g_order_id
 * @property string $g_goods_name
 * @property string $g_goods_models
 * @property string $g_goods_price
 * @property integer $g_goods_type
 * @property string $g_goods_deposit
 */
class Goods extends CoreCommonActiveRecord {
	/**
	 * @inheritdoc
	 */
	public static function tableName() {
		return 'goods';
	}

	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			[['g_order_id'], 'required'],
			[['g_goods_name', 'g_goods_models', 'g_goods_price', 'g_goods_type', 'g_goods_deposit'], 'required'],
			[['g_goods_serial_no'], 'required' , 'on' => 'clientValidate2'],
			[['g_order_id'], 'integer'],
			[['g_goods_type'], 'integer'],
			[['g_goods_price', 'g_goods_deposit'], 'number'],
			[['g_goods_name'], 'string', 'max' => 20],
			[['g_goods_models'], 'string', 'max' => 20],
			[['g_goods_price'], 'compare', 'compareAttribute' => 'g_goods_deposit', 'operator' => '>'],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {
		return [
			'g_id' => 'ID',
			'g_order_id' => '关联订单id',
			'g_goods_name' => '商品名称',
			'g_goods_models' => '商品型号',
			'g_goods_price' => '商品价格',
			'g_goods_type' => '商品类型',
			'g_goods_deposit' => '预付订金',
			'g_goods_serial_no' => '商品串号',
		];
	}

	// 设置验证场景
	public function scenarios() {
		$scenarios = parent::scenarios();
		$scenarios['clientValidate'] = ['g_goods_type', 'g_goods_name', 'g_goods_models', 'g_goods_price', 'g_goods_deposit'];
		$scenarios['clientValidate2'] = ['g_goods_serial_no'];
		return $scenarios;
	}
}
