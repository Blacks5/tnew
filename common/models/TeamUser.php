<?php

namespace common\models;

use Yii;
use common\core\CoreCommonActiveRecord;
/**
 * This is the model class for table "team_user".
 *
 * @property integer $tu_id
 * @property integer $tu_tid
 * @property integer $tu_sale_id
 */
class TeamUser extends CoreCommonActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'team_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tu_tid', 'tu_sale_id'], 'required'],
            [['tu_tid', 'tu_sale_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tu_id' => 'Tu ID',
            'tu_tid' => '团队id',
            'tu_sale_id' => '销售人员id',
        ];
    }
}
