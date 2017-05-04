<?php
/**
 * Created by PhpStorm.
 * Date: 16/9/1
 * Time: 22:06
 * @author 涂鸿 <hayto@foxmail.com>
 */

namespace common\models;

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
    public function rules()
    {
        return [
            [['c_customer_name', 'c_customer_cellphone', 'c_customer_id_card', 'c_customer_province', 'c_customer_city', 'c_customer_county'], 'safe']
        ];
    }

    public function search($params)
    {
        $query = Customer::find();
        $this->load($params);
//        p($this->attributes, $params);
        if(!$this->validate()){
            return $query->where('1=2');
        }
//p($this->getAttributes());
        $query->andFilterWhere(['like', 'c_customer_name', $this->c_customer_name])
            ->andFilterWhere(['like', 'c_customer_cellphone', $this->c_customer_cellphone])
            ->andFilterWhere(['like', 'c_customer_id_card', $this->c_customer_id_card]);

        $query->andFilterWhere(['c_customer_province'=>$this->c_customer_province]);
        $query->andFilterWhere(['c_customer_city'=>$this->c_customer_city]);
        $query->andFilterWhere(['c_customer_county'=>$this->c_customer_county]);

        return $query;
    }
}