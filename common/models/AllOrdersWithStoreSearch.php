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
use yii;

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
        if(!empty($param['AllOrdersWithStoreSearch']['o_status'])){
            $query->andWhere(['=', 'o_status', $param['AllOrdersWithStoreSearch']['o_status']]);
        }
        return $query;
    }

    //条件查询总订单
    public function totalOrder($id, $param = NULL)
    {
        $this->load($param);
        $query = Orders::find()->select(['sum(o_total_price-o_total_deposit+o_service_fee+o_inquiry_fee) as total_price'])->leftJoin(Customer::tableName(), 'o_customer_id=c_id')
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
        $query->andWhere(['=', 'o_status', Orders::STATUS_PAYING]);
        return $query;
    }

    /**
     * 商户逾期订单(笔数,金额)
     * 重构与2017年10月23日10:34:11
     * @param $id
     * @param null $param
     * @return $this
     * @author OneStep
     */
    public function totalOverdueIds($id, $param = NULL)
    {
        $this->load($param);
        if(!$this->validate()){
            return [];
        }

        $query = Repayment::find()
            ->leftJoin(Orders::tableName(), 'o_id=r_orders_id')
            ->leftJoin(Stores::tableName(),'s_id=o_store_id')
            ->where(['s_id'=>$id])
            ->andWhere(['o_status'=> Orders::STATUS_PAYING])
            ->andWhere(['>', 'r_overdue_day', 3])
            ->andWhere(['r_status'=>Repayment::STATUS_NOT_PAY])
            ->groupBy('r_orders_id')
            ->andFilterWhere(['like', 'c_customer_name', $this->username])
            ->andFilterWhere(['like', 'c_customer_cellphone', $this->phone]);
        if(!empty($this->s_time)){
            $this->s_time = strtotime($this->s_time . '00:00:00');
            $query = $query->andWhere(['>=','o_created_at', $this->s_time]);
        }
        if(!empty($this->e_time)){
            $this->e_time = strtotime($this->e_time . '23:59:59');
            $query = $query->andWhere(['<=', 'o_created_at', $this->e_time]);
        }
        return $query;
    }

    //条件查询逾期金额
    public function totalOverdue($ids, $param = NULL)
    {
        $ids = array_column($ids, 'o_id');
        $this->load($param);
        $query = Repayment::find()->select(['sum(r_principal) r_principal'])
            ->leftJoin(Orders::tableName(), 'o_customer_id=r_customer_id')
            ->leftJoin(Customer::tableName(), 'r_customer_id=c_id');
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
        if(!empty($ids)){
            $query->andWhere(['r_orders_id'=>$ids]);
        }
        return $query;
    }

}