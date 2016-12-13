<?php

namespace common\models;

use Yii;
use common\core\CoreCommonActiveRecord;

/**
 * This is the model class for table "order_images".
 *
 * @property integer $oi_id
 * @property string $oi_front_id
 * @property string $oi_back_id
 * @property string $oi_customer
 * @property string $oi_front_bank
 * @property string $oi_back_bank
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
class OrderImages extends CoreCommonActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order_images';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['oi_front_id', 'oi_back_id', 'oi_customer', 'oi_front_bank', 'oi_back_bank', 'oi_video'], 'required'],
            [['oi_front_id', 'oi_back_id', 'oi_customer', 'oi_front_bank', 'oi_back_bank', 'oi_family_card_one', 'oi_family_card_two', 'oi_driving_license_one', 'oi_driving_license_two'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'oi_id' => 'Oi ID',
            'oi_front_id' => '身份证正面',
            'oi_back_id' => '身份证背面',
            'oi_customer' => '客户现场照',
            'oi_front_bank' => '银行卡正面',
            'oi_back_bank' => '银行卡背面',
            'oi_family_card_one' => '户口本1',
            'oi_family_card_two' => '户口本2',
            'oi_driving_license_one' => '驾照1',
            'oi_driving_license_two' => '驾照2',

            'oi_after_contract' => '合同1',
            'oi_signature'=>'手写签名',
            'oi_video'=>'录制视频'
        ];
    }
}
