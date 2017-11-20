<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/9/10
 * Time: 15:46
 */

namespace backend\models;

use backend\core\CoreBackendModel;
use common\components\Helper;
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

    private  $repay_day = 3; //逾期多少天算逾期

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
        $level = [1=>'province', 2=>'province', 3=>'city', 4=>'county' ,5=>'county', 6=>'county'];
        $area = [
            'level' => 6,
            'area'  => 'province',
            'area_value' => '24',
        ];

        foreach ($level as $k => $l){
            if($user->identity->level==$k){
                $area['id'] = $user->identity->id;
                $area['level'] = $user->identity->level;
                $area['area'] = $l;
                $area['area_value'] = $user->identity->$l;
            }
        }

        return $area;
    }

    /**
     * 根据用户获取所属区域
     * @param $user
     * @return array
     * @author OneStep
     */
    public function getArea($user)
    {
        $area = [];
        if($user->level ==1 ){
            $area['province'] = Helper::getAllProvince();
        }

        if($user->level >1 ){
            $area['province'] = Helper::getProvinceByProvinceId($user->province);
            if($user->level >2){
                $area['city'] = Helper::getAddrName($user->city);
                if($user->level > 3){
                    $area['county'] = Helper::getAddrName($user->county);
                }
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
        $allUserQuery = clone $query;
        $totalcount = $querytemp->count();
        $pages = new Pagination(['totalCount' => $totalcount]);
        $pages->pageSize = \Yii::$app->params['page_size'];

        $allUser = $allUserQuery->asArray()->all();
        $userlist = $query->offset($pages->offset)->limit($pages->limit)
            ->asArray()->all();

        if(!empty($this->start_time)){
            $this->start_time = strtotime($this->start_time . ' 00:00:00');
        }

        if(!empty($this->end_time)){
            $this->end_time = strtotime($this->end_time. ' 23:59:59');
        }

        $all_list['t_ordercount']   = 0;    //总提单数
        $all_list['s_amount']       = 0;    //总放款
        $all_list['s_ordercount']   = 0;    //成功提单
        $all_list['a_servicecount'] = 0;    //贵宾服务包
        $all_list['f_packcount']    = 0;    //个人保障计划
        $all_list['overdue_count']  = 0;    //总单数
        $all_list['overdue_num']    = 0;    //逾期笔数
        $all_list['overdue_money']  = 0;    //逾期金额
        $all_list['overdue_ratio']  = 0;    //逾期率
        $all_list['serial_no']      = array();  //逾期期数
        $all_list['undesirable']    = 0;    //不良笔数

        foreach ($userlist as $_k=>$_v){
            $orderinfo = Orders::find()->where(['o_user_id'=>$_v['id']])->andWhere(['!=', 'o_status', Orders::STATUS_NOT_COMPLETE]);
            $orderinfo->andFilterWhere(['>=', 'o_created_at', $this->start_time]);
            $orderinfo->andFilterWhere(['<=', 'o_created_at', $this->end_time]);
            $t_ordercount = $orderinfo->count();  //总提单

            $orderinfo = Orders::find()->where(['o_user_id'=>$_v['id']])
            ->andWhere(['in', 'o_status', [Orders::STATUS_PAYING, Orders::STATUS_PAY_OVER]]);
            $orderinfo->andFilterWhere(['>=', 'o_created_at', $this->start_time]);
            $orderinfo->andFilterWhere(['<=', 'o_created_at', $this->end_time]);

            $service = clone $orderinfo;
            $pack = clone $orderinfo;

            $undesirable = Orders::find()
                ->where(['o_user_id'=>$_v['id']])
                ->andWhere(['o_is_undesirable'=>1])
                ->andFilterWhere(['>=', 'o_created_at', $this->start_time])
                ->andFilterWhere(['<=', 'o_created_at', $this->end_time])
                ->count();  //标记为不良的订单数量
            $undesirable += Orders::find()
                ->select('r_orders_id')
                ->leftJoin(Repayment::tableName(), 'r_orders_id=o_id')
                ->where(['o_user_id'=>$_v['id']])
                ->andWhere(['o_status'=>[Orders::STATUS_PAYING]])
                ->andWhere(['r_status'=>Repayment::STATUS_NOT_PAY])
                ->andFilterWhere(['>=', 'o_created_at', $this->start_time])
                ->andFilterWhere(['<=', 'o_created_at', $this->end_time])
                ->andWhere(['>','r_overdue_day', 30])
                ->groupBy('r_orders_id')
                ->count();  //逾期超过30天的不良订单数量

            $overdue = Orders::find()
                ->leftJoin(Repayment::tableName(), 'r_orders_id=o_id')
                ->where(['o_user_id'=>$_v['id']])
                ->andWhere(['in', 'o_status', [Orders::STATUS_PAYING, Orders::STATUS_PAY_OVER]])
                ->andFilterWhere(['>=', 'o_created_at', $this->start_time])
                ->andFilterWhere(['<=', 'o_created_at', $this->end_time])
                ->andWhere(['>', 'r_overdue_day', 3])
                ->andWhere(['r_status'=> Repayment::STATUS_NOT_PAY])->select('r_orders_id')->groupBy('r_orders_id');


            $s_ordercount = $orderinfo->count();  //成功提单
            $s_amount = $orderinfo->sum('o_total_price - o_total_deposit');  //放款金额
            $a_servicecount = $service->andWhere(['o_is_add_service_fee'=>1])->count();  //个人保障计划
            $f_packcount = $pack->andWhere(['o_is_free_pack_fee'=>1])->count(); //贵宾服务包


            $overdue_num = clone  $overdue;
            $overdue_money = clone $overdue;

            $userlist[$_k]['t_ordercount'] = $t_ordercount;
            $userlist[$_k]['s_amount'] = round($s_amount, 3);
            $userlist[$_k]['s_ordercount'] = $s_ordercount;
            $userlist[$_k]['a_services'] = $s_ordercount ? round($a_servicecount/$s_ordercount*100, 3).'%' : '0%';
            $userlist[$_k]['f_packcount'] = $s_ordercount ? round($f_packcount/$s_ordercount*100, 3).'%' : '0%';

            //销售人员的客户逾期数量
            $userlist[$_k]['overdue_count'] = $overdue_num->count();

            $overdue_orders =   empty($overdue_money->asArray()->column())?'0':$overdue_money->asArray()->column();
            //逾期金额
            $userlist[$_k]['overdue_money'] = round(Repayment::find()->where(['in', 'r_orders_id', $overdue_orders])->andWhere(['r_repay_date'=>0])->sum('r_principal'),2);
            //逾期率
            $userlist[$_k]['overdue_ratio'] =  $userlist[$_k]['overdue_money']?round($userlist[$_k]['overdue_count']/$s_ordercount*100,3). "%":'0%';
            //通过率
            $userlist[$_k]['adopt_ratio'] = $userlist[$_k]['s_ordercount'] ==0 ?'0%':round($userlist[$_k]['s_ordercount']/ $userlist[$_k]['t_ordercount']*100, 2). '%';
            //不良率
            $userlist[$_k]['undesirable_ratio'] = $undesirable==0?'0%':round($undesirable/$userlist[$_k]['s_ordercount']*100,2).'%';  //不良率

            //逾期金额比
            $userlist[$_k]['overdueMoney_ratio'] = $userlist[$_k]['overdue_money'] == 0? '0%':round($userlist[$_k]['overdue_money']/$s_amount*100,2).'%';

            $all_list['t_ordercount'] +=$userlist[$_k]['t_ordercount'];
            $all_list['s_amount']+= $userlist[$_k]['s_amount'];
            $all_list['s_ordercount'] += $userlist[$_k]['s_ordercount'];
            $all_list['a_servicecount'] += $a_servicecount;
            $all_list['f_packcount'] += $f_packcount;
            $all_list['undesirable'] += $undesirable;

            //总逾期统计
            $all_list['overdue_num'] += $userlist[$_k]['overdue_count'];
            $all_list['overdue_money'] += round($userlist[$_k]['overdue_money'],3);

            //var_dump($userlist[$_k]['a_services']);die;
        }
        //分页数据统计
        $all_list['a_services'] = $all_list['s_ordercount'] ? round($all_list['a_servicecount']/$all_list['s_ordercount']*100, 3).'%':'0%';
        $all_list['f_packcount'] = $all_list['s_ordercount'] ? round($all_list['f_packcount']/$all_list['s_ordercount']*100, 3).'%':'0%';
        $all_list['overdue_ratio'] = $all_list['overdue_num'] ? round($all_list['overdue_num']/$all_list['s_ordercount']*100, 3). '%': '0%';
        //分页通过率
        $all_list['adopt_ratio'] = $all_list['s_ordercount'] == 0 ?'0%':round($all_list['s_ordercount']/$all_list['t_ordercount']*100, 2). '%';
        //分页逾期金额比
        $all_list['overdueMoney_ratio'] = $all_list['overdue_money'] == 0 ?'0%':round($all_list['overdue_money']/$all_list['s_amount']*100,2).'%';
        //不良率
        $all_list['undesirable_ratio'] = $all_list['undesirable'] == 0 ?'0%':round($all_list['undesirable']/$all_list['s_ordercount']*100,2).'%';
        $all_list['risk_num'] = $all_list['serial_no']? $this->getRisk($all_list['serial_no'],$all_list['s_ordercount']). '%':'0%';

        //总数据
        $total = $this->getTotal();
        //var_dump($all_list['risk_num']);die;

        return [
            'data' => $userlist,
            'sear' => $this->getAttributes(),
            'all'  =>$all_list,
            'totalcount' => $pages->pageCount,
            'total' => $total,
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
            ->where(['!=', 'username', 'admin'])
            //->andwhere(['!=', 'status', \common\models\User::STATUS_DELETE])
             // 只要销售部
            ->andWhere(['>=','level',$area['level']])  //用户等级之下的销售
            ->filterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'realname', $this->realname])
            ->andFilterWhere(['user.province'=>$this->province])
            ->andFilterWhere(['user.city'=>$this->city])
            ->andFilterWhere(['user.county'=>$this->county]);

        if($area['level']>1){   //如果不是销售总监,只能查看管理地区范围内的销售数据
            $query->andWhere([$area['area']=>$area['area_value']])
            ->andWhere(['department_id'=>26]);
        }

        return $query;
    }

    /**
     * 获取总数据
     * @return mixed
     * @author OneStep
     */
    public function getTotal()
    {
        $userQuery = $this->getUserList();
        $userList = $userQuery->select('id')->column();

        $orderQuery = Orders::find()
            ->andWhere(['in', 'o_user_id', $userList])
            ->andFilterWhere(['>=', 'o_created_at', $this->start_time])
            ->andFilterWhere(['<=', 'o_created_at', $this->end_time]);

        $a_orderQuery = clone $orderQuery;
        $undesirableQuery = clone $orderQuery;
        $repayUnder = clone $orderQuery;
        $total['undesirable'] = $undesirableQuery->andWhere(['o_is_undesirable'=>1])->count();   //标记为不良的订单数量
        $total['undesirable'] += $repayUnder
            ->leftJoin(Repayment::tableName(), 'r_orders_id=o_id')
            ->select('r_orders_id')
            ->andWhere(['o_is_undesirable'=>0])
            ->andWhere(['>', 'r_overdue_day', 30])
            ->andWhere(['r_status'=>Repayment::STATUS_NOT_PAY])
            ->groupBy('r_orders_id')
            ->count();   //超过30天未还的 不良

        $s_orderQuery = $orderQuery->andWhere(['in', 'o_status', [Orders::STATUS_PAYING, Orders::STATUS_PAY_OVER]]);
        $service = clone $s_orderQuery;
        $pack = clone $s_orderQuery;
        $overdue_num = clone $s_orderQuery;
        $total['a_orderCount'] = $a_orderQuery->andWhere(['!=','o_status',Orders::STATUS_NOT_COMPLETE])->count('o_id'); //总提单
        $total['s_orderCount'] = $s_orderQuery->count('o_id');    //成功提单数量
        $total['s_orderMoney'] = $s_orderQuery->sum('o_total_price-o_total_deposit+o_service_fee+o_inquiry_fee'); //提单金额
        $total['service']   = $service->andWhere(['o_is_add_service_fee'=>1])->count(); //个人保障
        $total['pack']      = $pack->andWhere(['o_is_free_pack_fee'=>1])->count();  //贵宾服务
        $total['overdue_num'] = $overdue_num
            ->leftJoin(Repayment::tableName(), 'r_orders_id=o_id')
            ->select('r_orders_id')->andWhere(['r_repay_date'=>0])
            ->andWhere(['>', 'r_overdue_day', 3])
            ->andWhere(['r_status'=> Repayment::STATUS_NOT_PAY])
            ->groupBy('r_orders_id')->count();
        $overdue_order = $overdue_num->column();
        $total['overdue_money'] = Repayment::find()->where(['in', 'r_orders_id', $overdue_order])->andWhere(['r_repay_date'=>0])->sum('r_principal');

        $total['overdue_numRatio'] = empty($total['overdue_num'])?'0%':round($total['overdue_num']/$total['s_orderCount']*100, 2).'%';
        $total['overdue_moneyRatio'] =empty($total['overdue_money'])?'0%':round($total['overdue_money']/$total['s_orderMoney']*100, 2). '%';
        $total['service_ratio'] = empty($total['service'])?'0%':round($total['service']/$total['s_orderCount']*100, 2).'%';
        $total['pack_ratio'] = empty($total['pack'])?'0%':round($total['pack']/$total['s_orderCount']*100,2).'%';
        $total['adopt_ratio'] = empty($total['s_orderCount'])?'0%':round($total['s_orderCount'] / $total['a_orderCount']*100, 2). '%';  //通过率
        $total['undesirable_ratio'] = empty($total['undesirable'])?'0%':round($total['undesirable'] / $total['a_orderCount']*100, 2). '%'; //不良率

        return $total;
    }

}