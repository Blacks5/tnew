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
use function GuzzleHttp\Promise\all;
use yii\data\Pagination;
use yii;

class YejiSearch extends CoreBackendModel{
    public $username;
    public $realname;
    public $start_time;
    public $end_time;

    public $province;
    public $city;
    public $county;
    public $userid;

    public function rules()
    {
        return [
            [['username', 'realname', 'province', 'city', 'county', 'start_time', 'end_time','userid'], 'trim'],
            [['start_time', 'end_time'], 'date', 'format'=>'php:Y-m-d']
        ];
    }

    /**
     * 获取用户等级地区
     * @return yii\web\User
     * @author OneStep
     */
    public function getLower()
    {
        $user = yii::$app->getUser();
        $leader = [1=>'province', 2=>'province', 3=>'city', 4=>'county'];
        $area = array();

        foreach ($leader as $k => $l){
            if($user->identity->level==$k){
                $area['level'] = $user->identity->level;
                $area['area'] = $l;
                $area['area_value'] = $user->identity->$l;
            }
        }

        return $area;
    }

    public function search($param = NULL)
    {
        $this->load($param);
        if(!$this->validate()){
            return [];
        }
        $area = $this->getLower(); //获取用户等级
            $query =  $userlist = User::find()
                ->select(['user.id', 'user.username', 'user.realname','user.leader','user.level'])
                ->where(['!=', 'username', 'admin'])
                ->andwhere(['!=', 'status', \common\models\User::STATUS_DELETE])
                ->andWhere(['department_id'=>26]) // 只要销售部
                ->andWhere(['>','level',$area['level']])
                ->filterWhere(['like', 'username', $this->username])
                ->andFilterWhere(['like', 'realname', $this->realname])
                ->andFilterWhere(['user.province'=>$this->province])
                ->andFilterWhere(['user.city'=>$this->city])
                ->andFilterWhere(['user.county'=>$this->county]);
                if($area['level']>1){
                    $query->andWhere([$area['area']=>$area['area_value']]);
                }



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

        $all_list['t_ordercount'] = 0;  //总提单数
        $all_list['s_amount'] = 0;      //总放款
        $all_list['s_ordercount'] = 0;  //成功提单
        $all_list['a_servicecount'] = 0;    //贵宾服务包
        $all_list['f_packcount'] = 0;       //个人保障计划
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

            $all_list['t_ordercount'] +=$userlist[$_k]['t_ordercount'];
            $all_list['s_amount']+= $userlist[$_k]['s_amount'];
            $all_list['s_ordercount'] += $userlist[$_k]['s_ordercount'];
            $all_list['a_servicecount'] += $a_servicecount;
            $all_list['f_packcount'] += $f_packcount;


            //var_dump($userlist[$_k]['a_services']);die;
        }

        $all_list['a_services'] = $all_list['s_ordercount'] ? round($all_list['a_servicecount']/$all_list['s_ordercount']*100, 3).'%':'0%';
        $all_list['f_packcount'] = $all_list['s_ordercount'] ? round($all_list['f_packcount']/$all_list['s_ordercount']*100, 3).'%':'0%';

        //var_dump($all_list['f_packcount']);die;

        return [
            'data' => $userlist,
            'sear' => $this->getAttributes(),
            'all'  =>$all_list,
            'totalcount' => $pages->pageCount,
            'pages'=>$pages
        ];
    }
}