<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/9/10
 * Time: 15:46
 */

namespace common\models;

use common\core\CoreCommonModel;
use yii\data\Pagination;

class AllOrdersWithStoreSearch extends CoreCommonModel{
    public $username;
    public $realname;
    public $id;

    public function rules()
    {
        return [
            [['id'], 'trim']
        ];
    }

    public function search($id, $param = NULL)
    {
        $this->load($param);

        $query = Orders::find()
            ->Where(['o_store_id' => $id])
        ;

        if(!$this->validate()){
            return $query->andWhere('1=2');
        }

        return $query;
    }
}