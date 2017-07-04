<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/9/10
 * Time: 15:46
 */

namespace backend\models;

use backend\core\CoreBackendModel;
use common\models\Orders;
use yii\data\Pagination;

class YejiSearch extends CoreBackendModel{
    public $username;
    public $realname;
    public $start_time;
    public $end_time;

    public $province;
    public $city;
    public $county;

    public function rules()
    {
        return [
            [['username', 'realname', 'province', 'city', 'county', 'start_time', 'end_time'], 'trim'],
            [['start_time', 'end_time'], 'date', 'format'=>'php:Y-m-d']
        ];
    }

    public function search($param = NULL)
    {
        $this->load($param);
        if(!$this->validate()){
            return [];
        }

        $query =  $userlist = User::find()
            ->select(['user.id', 'user.username', 'user.realname'])
            ->where(['!=', 'username', 'admin'])
            ->andwhere(['!=', 'status', \common\models\User::STATUS_DELETE])
            ->andWhere(['department_id'=>26]) // 只要销售部
            ->filterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'realname', $this->realname])
            ->andFilterWhere(['user.province'=>$this->province])
            ->andFilterWhere(['user.city'=>$this->city])
            ->andFilterWhere(['user.county'=>$this->county]);


        $querytemp = clone $query;
        $totalcount = $querytemp->count();
        $pages = new Pagination(['totalCount' => $totalcount]);
        $pages->pageSize = \Yii::$app->params['page_size'];

        $userlist = $query->offset($pages->offset)->limit($pages->limit)
            ->asArray()->all();

        if(!empty($this->start_time)){
            $this->start_time = strtotime($this->start_time);
        }
        if(!empty($this->end_time)){
            $this->end_time = strtotime($this->end_time);
        }
        foreach ($userlist as $_k=>$_v){
            $orderinfo = Orders::find()->where(['o_user_id'=>$_v['id']])->andWhere(['!=', 'o_status', Orders::STATUS_NOT_COMPLETE]);
            $orderinfo->andFilterWhere(['>=', 'o_created_at', $this->start_time]);
            $orderinfo->andFilterWhere(['<=', 'o_created_at', $this->end_time]);
            $t_ordercount = $orderinfo->count();

            $orderinfo = Orders::find()->where(['o_user_id'=>$_v['id'], 'o_status'=>Orders::STATUS_PAYING]);
            $orderinfo->andFilterWhere(['>=', 'o_created_at', $this->start_time]);
            $orderinfo->andFilterWhere(['<=', 'o_created_at', $this->end_time]);
            $s_ordercount = $orderinfo->count();
            $s_amount = $orderinfo->sum('o_total_price - o_total_deposit');

            $orderinfo = Orders::find()->where(['o_user_id'=>$_v['id'], 'o_status'=>Orders::STATUS_PAYING, 'o_is_add_service_fee'=>1]);
            $orderinfo->andFilterWhere(['>=', 'o_created_at', $this->start_time]);
            $orderinfo->andFilterWhere(['<=', 'o_created_at', $this->end_time]);
            $a_servicecount = $orderinfo->count();

            $orderinfo = Orders::find()->where(['o_user_id'=>$_v['id'], 'o_status'=>Orders::STATUS_PAYING, 'o_is_free_pack_fee'=>1]);
            $orderinfo->andFilterWhere(['>=', 'o_created_at', $this->start_time]);
            $orderinfo->andFilterWhere(['<=', 'o_created_at', $this->end_time]);
            $f_packcount = $orderinfo->count();

            $userlist[$_k]['t_ordercount'] = $t_ordercount;
            $userlist[$_k]['s_amount'] = round($s_amount, 3);
            $userlist[$_k]['s_ordercount'] = $s_ordercount;
            $userlist[$_k]['a_services'] = $s_ordercount ? round($a_servicecount/$s_ordercount*100, 3).'%' : '0%';
            $userlist[$_k]['f_packcount'] = $s_ordercount ? round($f_packcount/$s_ordercount*100, 3).'%' : '0%';
        }

        return [
            'data' => $userlist,
            'sear' => $this->getAttributes(),
            'totalcount' => $pages->pageCount,
            'pages'=>$pages
        ];
    }
}