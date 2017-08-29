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
use common\models\Repayment;
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
        $query = $this->getUserList();

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

        $all_list['t_ordercount']   = 0;    //总提单数
        $all_list['s_amount']       = 0;    //总放款
        $all_list['s_ordercount']   = 0;    //成功提单
        $all_list['a_servicecount'] = 0;    //贵宾服务包
        $all_list['f_packcount']    = 0;    //个人保障计划
        $all_list['overdun_num']    = 0;    //逾期笔数
        $all_list['overdun_money']  = 0;    //逾期金额
        $all_list['overdun_count']  = 0;    //逾期率

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

            $orderinfo = Orders::find()->where(['o_user_id'=>$_v['id'], 'o_status'=>Orders::STATUS_PAYING]);
            $orderinfo->andFilterWhere(['>=', 'o_created_at', $this->start_time]);
            $orderinfo->andFilterWhere(['<=', 'o_created_at', $this->end_time]);
            $orderinfo->select('o_id');

            $overdun = $this->getOverdueNum($orderinfo->column());

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

            $userlist[$_k]['overdun_num'] = $overdun['count'];
            $userlist[$_k]['overdun_money'] = $overdun['money'];
            $userlist[$_k]['overdun_count'] = $overdun['count'] ? round($overdun['count']/$s_ordercount*100,3).'%':'0%';

            $all_list['t_ordercount'] +=$userlist[$_k]['t_ordercount'];
            $all_list['s_amount']+= $userlist[$_k]['s_amount'];
            $all_list['s_ordercount'] += $userlist[$_k]['s_ordercount'];
            $all_list['a_servicecount'] += $a_servicecount;
            $all_list['f_packcount'] += $f_packcount;

            //逾期统计
            $all_list['overdun_num'] += $overdun['count'];
            $all_list['overdun_money'] += $overdun['money'];

            //var_dump($userlist[$_k]['a_services']);die;
        }

        $all_list['a_services'] = $all_list['s_ordercount'] ? round($all_list['a_servicecount']/$all_list['s_ordercount']*100, 3).'%':'0%';
        $all_list['f_packcount'] = $all_list['s_ordercount'] ? round($all_list['f_packcount']/$all_list['s_ordercount']*100, 3).'%':'0%';
        $all_list['overdun_count'] = $all_list['overdun_num'] ? round($all_list['overdun_num']/$all_list['s_ordercount']*100, 3). '%': '0%';

        //var_dump($all_list['f_packcount']);die;

        return [
            'data' => $userlist,
            'sear' => $this->getAttributes(),
            'all'  =>$all_list,
            'totalcount' => $pages->pageCount,
            'pages'=>$pages
        ];
    }

    /**
     * 获取销售人员下级所有人员列表
     * @return $this
     * @author OneStep
     */
    public function getUserList()
    {
        $area = $this->getLower(); //获取用户等级

        $query =  $userlist = User::find()
            ->select(['user.id', 'user.username', 'user.realname','user.leader','user.level'])
            ->where(['!=', 'username', 'admin'])
            ->andwhere(['!=', 'status', \common\models\User::STATUS_DELETE])
            ->andWhere(['department_id'=>26]) // 只要销售部
            ->andWhere(['>','level',$area['level']])  //用户等级之下的销售
            ->filterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'realname', $this->realname])
            ->andFilterWhere(['user.province'=>$this->province])
            ->andFilterWhere(['user.city'=>$this->city])
            ->andFilterWhere(['user.county'=>$this->county]);

        if($area['level']>1){   //如果不是销售总监,只能查看管理地区范围内的销售数据
            $query->andWhere([$area['area']=>$area['area_value']]);
        }

        return $query;
    }

    /**
     * 查看销售人员负责的客户逾期单数和金额
     * @param $o_id
     * @return mixed
     * @author OneStep
     */
    public function getOverdueNum($o_id)
    {
        $orderinfo = Repayment::find()
            ->where(['in','r_orders_id',$o_id])
            ->andWhere(['>','r_overdue_money','0']);

        $overdun['count'] = $orderinfo->count();
        $overdun['money'] = $orderinfo->sum('r_overdue_money');

        return $overdun;
    }
}