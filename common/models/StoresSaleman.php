<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "stores_saleman".
 *
 * @property integer $ss_id
 * @property integer $ss_store_id
 * @property integer $ss_saleman_id
 */
class StoresSaleman extends \app\core\base\BaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'stores_saleman';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ss_store_id', 'ss_saleman_id'], 'required'],
            [['ss_store_id', 'ss_saleman_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ss_id' => 'Ss ID',
            'ss_store_id' => '商户id',
            'ss_saleman_id' => '销售人员id',
        ];
    }
}
