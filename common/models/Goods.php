<?php

namespace common\models;

use Yii;
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
class Goods extends CoreCommonActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goods';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['g_order_id', 'g_goods_name', 'g_goods_models', 'g_goods_price', 'g_goods_type', 'g_goods_deposit'], 'required'],
            [['g_order_id', 'g_goods_type'], 'integer'],
            [['g_goods_price', 'g_goods_deposit'], 'number'],
            [['g_goods_name'], 'string', 'max' => 50],
            [['g_goods_models'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'g_id' => 'ID',
            'g_order_id' => '关联订单id',
            'g_goods_name' => '商品名称',
            'g_goods_models' => '商品型号',
            'g_goods_price' => '商品价格',
            'g_goods_type' => '商品类型',
            'g_goods_deposit' => '预付订金',
        ];
    }
}
