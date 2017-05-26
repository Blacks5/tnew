<?php

namespace common\models;

use Yii;
use common\core\CoreCommonActiveRecord;
/**
 * This is the model class for table "orders".
 *
 * @property integer $o_id
 * @property integer $o_store_id
 * @property integer $o_product_id
 * @property integer $o_images_id
 * @property integer $o_customer_id
 * @property integer $o_goods_num
 * @property string $o_total_price
 * @property string $o_total_deposit
 * @property string $o_remark
 * @property integer $o_status
 * @property integer $o_created_at
 * @property integer $o_total_interest
 * @property integer $o_operator_id
 * @property integer $o_operator_realname
 * @property integer $o_operator_date
 * @property integer $o_user_id
 * @property integer $o_operator_remark
 * @property integer $o_is_add_service_fee
 * @property integer $o_is_free_pack_fee
 * @property integer $o_serial_id
 */
class Orders extends CoreCommonActiveRecord
{
    const STATUS_NOT_COMPLETE = 2; // 不完整
    const STATUS_REFUSE = 1; // 拒绝
    const STATUS_WAIT_CHECK = 0; // 待审核
    const STATUS_WAIT_CHECK_AGAIN = 6; // 待二审
    const STATUS_WAIT_APP_UPLOAD_AGAIN = 7; // 待APP再次上传
    const STATUS_PAYING = 10; // 还款中
    const STATUS_PAY_OVER = 5; // 已还清
    const STATUS_CANCEL = 4; // 取消
    const STATUS_REVOKE = 3; // 还款中

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'orders';
    }
    public static function getAllStatus()
    {
        return [
            self::STATUS_WAIT_CHECK=>'待审核',
            self::STATUS_WAIT_CHECK_AGAIN=>'初审通过', // 待二审
            self::STATUS_REFUSE=>'已拒绝',
            self::STATUS_PAYING=>'还款中',
            self::STATUS_PAY_OVER=>'已还清',
            self::STATUS_CANCEL=>'已取消',
            self::STATUS_REVOKE=>'已撤销',
            self::STATUS_NOT_COMPLETE=>'未上传图片',
            self::STATUS_WAIT_APP_UPLOAD_AGAIN=>'待APP再次上传',
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['o_store_id', 'o_customer_id', 'o_goods_num', 'o_total_price', 'o_total_deposit', 'o_product_id', 'o_serial_id'], 'required'],
            [['o_store_id', 'o_images_id', 'o_customer_id', 'o_goods_num', 'o_status', 'o_product_id'], 'integer'],
            [['o_total_price', 'o_total_deposit', 'o_total_interest'], 'number'],

            [['o_status', 'o_images_id', 'o_remark', 'o_operator_id', 'o_operator_realname', 'o_operator_date', 'o_user_id', 'o_operator_remark'], 'safe'],

            [['o_product_id'], 'exist', 'targetClass'=>'common\models\Product', 'targetAttribute'=>'p_id', 'message'=>'产品不存在'],
            [['o_store_id'], 'exist', 'targetClass'=>'common\models\Stores', 'targetAttribute'=>'s_id', 'message'=>'商户不存在'],

            [['o_is_add_service_fee', 'o_is_free_pack_fee'], 'in', 'range'=>[1,0]]
        ];
    }

    /**
     * @inheritdoc1
     */
    public function attributeLabels()
    {
        return [
            'o_id' => 'ID',
            'o_store_id' => '商铺id',
            'o_product_id' => '订单使用的产品id',
            'o_images_id' => 'Images ID',
            'o_customer_id' => '客户id',
            'o_goods_num' => '订单涉及几个商品',
            'o_total_price' => '订单总价格',
            'o_total_deposit' => '订单总订金',
            'o_total_interest' => '订单总利息',
            'o_status' => '订单状态 10还款中 0待审核 1拒绝',
        ];
    }

    /**
     * 取一个完整的借款 包含 product，customer，order,store
     * @param $order_id
     * @return array|null|\yii\db\ActiveRecord
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public static function getOne($order_id)
    {
        return self::find()->select('*')
            ->leftJoin(Customer::tableName(), 'o_customer_id=c_id')
            ->leftJoin(Product::tableName(), 'p_id=o_product_id')
            ->leftJoin(Stores::tableName(), 'o_store_id=s_id') // 商户
            ->leftJoin(User::tableName(), 'id=o_user_id') // 读业务员
            ->where('o_id=:o_id', [':o_id'=>$order_id])
//            ->andWhere(['o_status'=>[Orders::STATUS_PAY_OVER, Orders::STATUS_PAYING]]) // 只读已还清和还款中的订单
            ->asArray()->one();
//        $select = ['*'];
//        $data = self::find()->alias('orders')->select($select)
//            ->leftJoin(Product::tableName(). ' product', 'product.p_id=orders.o_product_id') // 关联产品
//            ->leftJoin(Customer::tableName(). ' customer', 'customer.c_id=orders.o_customer_id') // 关联客户->where([''])
//            ->leftJoin(Repayment::tableName(), 'repayment.r_orders_id=orders.o_id')//关联还款
//            ->where(['orders.o_id'=>$order_id])
//            ->asArray()->one();
//        return $data;
    }

}
