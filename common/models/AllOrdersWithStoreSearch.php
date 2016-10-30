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
    public $phone;
    public $id;
    public $s_time;
    public $e_time;

    public function rules()
    {
        return [
            [['id'], 'trim'],
            [['username', 's_time', 'e_time', 'phone'], 'safe']
        ];
    }

    public function search($id, $param = NULL)
    {
        $this->load($param);

        $query = Orders::find()->select(['*'])->leftJoin(Customer::tableName(), 'o_customer_id=c_id')
            ->Where(['o_store_id' => $id])
        ;

        if(!$this->validate()){
            return $query->andWhere('1=2');
        }
        $query->andFilterWhere(['like', 'c_customer_name', $this->username]);
        $query->andFilterWhere(['like', 'c_customer_cellphone', $this->phone]);
        if (!empty($this->s_time)) {
            $this->s_time = strtotime($this->s_time . '00:00:00');
            $query->andWhere(['>=', 'o_created_at', $this->s_time]);
        }
        if (!empty($this->e_time)) {
            $this->e_time = strtotime($this->e_time . '23:59:59');
            $query->andWhere(['<=', 'o_created_at', $this->e_time]);
        }


        return $query;
    }
}