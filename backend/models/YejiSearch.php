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

    public function rules()
    {
        return [
            [['username', 'realname'], 'trim']
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
            ->filterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'realname', $this->realname]);

        $querytemp = clone $query;
        $totalcount = $querytemp->count();
        $pages = new Pagination(['totalCount' => $totalcount]);
        $pages->pageSize = 10;

        $userlist = $query->offset($pages->offset)->limit($pages->limit)
            ->asArray()->all();

        foreach ($userlist as $_k=>$_v){
            $orderinfo = Orders::find()->where(['o_user_id'=>$_v['id']])->andWhere(['!=', 'o_status', '2']);
            $t_ordercount = $orderinfo->count();

            $orderinfo = Orders::find()->where(['o_user_id'=>$_v['id'], 'o_status'=>10]);
            $s_ordercount = $orderinfo->count();
            $s_amount = $orderinfo->sum('o_total_price - o_total_deposit');

            $orderinfo = Orders::find()->where(['o_user_id'=>$_v['id'], 'o_status'=>10, 'o_is_add_service_fee'=>1]);
            $a_servicecount = $orderinfo->count();

            $orderinfo = Orders::find()->where(['o_user_id'=>$_v['id'], 'o_status'=>10, 'o_is_free_pack_fee'=>1]);
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