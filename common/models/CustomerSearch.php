<?php
/**
 * Created by PhpStorm.
 * Date: 16/9/1
 * Time: 22:06
 * @author 涂鸿 <hayto@foxmail.com>
 */

namespace common\models;

use backend\models\YejiSearch;
use common\models\Customer;
use yii;
use common\core\CoreCommonModel;
class CustomerSearch extends CoreCommonModel
{
    public $c_customer_name;
    public $c_customer_cellphone;
    public $c_customer_id_card;
    public $c_customer_province;
    public $c_customer_city;
    public $c_customer_county;
    public $u_id; // 销售人员id
    public $borrow_status; // 借款状态，只要有一笔通过，就算成功

    const BORROW_STATUS_SUCCESS = '1';  // 借款成功
    const BORROW_STATUS_FAIL = '2'; // 借款失败
    public function rules()
    {
        return [
            [['c_customer_name', 'c_customer_cellphone', 'c_customer_id_card', 'c_customer_province', 'c_customer_city', 'c_customer_county', 'u_id'], 'safe'],
            ['borrow_status', 'in', 'range'=>[self::BORROW_STATUS_SUCCESS, self::BORROW_STATUS_FAIL]]
        ];
    }

    /**
     * select * from customer where c_id in (select o_customer_id from orders where o_user_id=30 group by o_customer_id)
     * @param $params
     * @return $this|yii\db\ActiveQuery
     * @author too <hayto@foxmail.com>
     */
    public function search($params)
    {
        $query = Customer::find();
        $this->load($params);
        if(!$this->validate()){
            return $query->where('1=2');
        }
        $query->andFilterWhere(['like', 'c_customer_name', $this->c_customer_name])
            ->andFilterWhere(['like', 'c_customer_cellphone', $this->c_customer_cellphone])
            ->andFilterWhere(['like', 'c_customer_id_card', $this->c_customer_id_card]);

        $query->andFilterWhere(['c_customer_province'=>$this->c_customer_province]);
        $query->andFilterWhere(['c_customer_city'=>$this->c_customer_city]);
        $query->andFilterWhere(['c_customer_county'=>$this->c_customer_county]);

        if(!empty($this->u_id)){
            $subQuery = (new yii\db\Query())->select(['o_customer_id'])->from(Orders::tableName())->where(['o_user_id'=>$this->u_id])->groupBy('o_customer_id');
            $query->andWhere(['c_id'=>$subQuery]);
        }
        if(self::BORROW_STATUS_FAIL === $this->borrow_status){
            $query->andWhere(['c_total_money'=>0]);
        }else if(self::BORROW_STATUS_SUCCESS === $this->borrow_status){
            $query->andWhere(['>', 'c_total_money', 0]);
        }

        return $query;
    }

}