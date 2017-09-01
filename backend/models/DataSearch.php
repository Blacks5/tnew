<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2017/8/30
 * Time: 15:31
 */

namespace backend\models;


use backend\core\CoreBackendModel;
use common\components\Helper;
use common\models\Orders;
use common\models\Repayment;
use function GuzzleHttp\Promise\all;
use yii;

class DataSearch extends CoreBackendModel
{
    /**
     * 搜索参数
     * @var $username $realname $start_time $end_time $province $city $county $userId
     */
    public $username;
    public $realname;
    public $start_time;
    public $end_time;
    public $province;
    public $city;
    public $county;
    public $userId;

    /**
     * @var 统计数据汇总
     */
    public $principal = 0;     //借出本金
    public $interest  = 0;     //借出利息
    public $repay_pri = 0;     //归还本金
    public $repay_int = 0;     //归还利息
    public $overdue   = 0;     //逾期金额
    public $repay_ove = 0; //归还逾期金额
    public $freePack  = 0;     //个人保障计划收入
    public $service   = 0;     //服务包收入

    /**
     * 构造搜索参数
     * DataSearch constructor.
     * @author OneStep
     */
    /*public function __construct()
    {
        $request = yii::$app->request;

        $this->userName = $request->get('userName');
        $this->realName = $request->get('realName');
        $this->start_time = $request->get('start_time');
        $this->end_tiem = $request->get('end_time');
        $this->province = $request->get('province');
        $this->city     = $request->get('city');
        $this->county   = $request->get('county');
        $this->userId   = $request->get('userID');

        if(isset($this->start_time)){
            $this->start_time = strtotime($this->start_time);
        }else{
            $nowTime = strtotime(date('Y-m-d'));
            $this->start_time =strtotime(date('Y-m-d', strtotime(date('Y',$nowTime).'-'.(date('m',$nowTime)-1).'-01')));
        }

        if(isset($this->end_tiem)){
            $this->end_tiem = strtotime($this->end_tiem);
        }else{
            $this->end_tiem =strtotime(date('Y-m-d'));
        }

        //var_dump($this->end_tiem); var_dump($this->start_time);die;
    }*/

    public function rules()
    {
        return [
            [['username','realname','start_time','end_time','province','city','county'], 'trim'],
            [['start_time','end_time'], 'date', 'format'=> 'php:Y-m-d']
        ];
    }

    /**
     * 获取所有省份
     * @return array
     * @author OneStep
     */
    public function getProvince()
    {
        return Helper::getAllProvince();
    }

    /**
     * 获取所有销售模型
     * @return $this|array
     * @author OneStep
     */
    public function getUserList()
    {
        $userList = User::find()
            ->select(['user.id', 'user.realname'])
            ->where(['!=', 'username', 'admin'])
            ->andWhere(['!=', 'status', \common\models\User::STATUS_DELETE])
            ->andWhere(['department_id'=> 26])
            ->filterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'realname' ,$this->realname])
            ->andFilterWhere(['user.province'=> $this->province])
            ->andFilterWhere(['user.city'=> $this->city])
            ->andFilterWhere(['user.county'=> $this->county]);

        return $userList;
    }

    /**
     * 获取平台信息
     * @return array
     * @author OneStep
     */
    public function getLoanTotal($param = NULL)
    {
        $this->load($param);
        if(!$this->validate()){
            return [];
        }
        //var_dump($this->start_time);
        //var_dump($param);die;
        $userQuery = $this->getUserList();
        $userList = $userQuery->asArray()->all();


        if(!empty($this->start_time)){
            $this->start_time = strtotime($this->start_time);
        }else{
            $this->start_time = strtotime(date('Y-m-d', strtotime("-30 day")));
        }

        if (!empty($this->end_time)){
            $this->end_time = strtotime($this->end_time);
        }else{
            $this->end_time = strtotime(date('Y-m-d', time()));
        }

        foreach ($userList as $_k => $_v){
            $query = Orders::find()->select(['o_id'])->filterWhere(['o_user_id'=>$_v['id']])
                ->andFilterWhere(['in', 'o_status', [Orders::STATUS_PAYING, Orders::STATUS_PAY_OVER, Orders::STATUS_REVOKE]])
                ->andFilterWhere(['>=', 'o_created_at', $this->start_time])
                ->andFilterWhere(['<=', 'o_created_at', $this->end_time]);

            //var_dump($query->createCommand()->getRawSql());die;

            $loanAll = $this->getLoanAll($query->asArray()->all());

            //var_dump($loanAll);die;

        }

        return [
            'principal'   => $this->principal,
            'interest'    => $this->interest,
            'repay_pri'   => $this->repay_pri,
            'repay_int'   => $this->repay_int,
            'overdue'     => $this->overdue,
            'repay_ove'   => $this->repay_ove,
            'freePack'    => $this->freePack,
            'service'   => $this->service,
            'user'      => $userList,
            'sear'      => $this->getAttributes(),
        ];

    }

    /**
     * 根据订单ID获取放款数据
     * @param $order
     * @return array
     * @author OneStep
     */
    public function getLoanAll($order)
    {
        foreach ($order as $k => $o){
            $query = Repayment::find()->where(['r_orders_id'=>$o['o_id']]);

            $all = $query->select([
                    'sum(r_principal) as pri',
                    'sum(r_interest) as inter',
                    'sum(r_overdue_money) as overdue'
                ])->asArray()->one();
            $this->principal+= $all['pri'];
            $this->interest += $all['inter'];
            $this->overdue  += $all['overdue'];

            $repay = $query->select([
                    'sum(r_principal) as pri',
                    'sum(r_interest) as inter',
                    'sum(r_overdue_money) as overdue',
                    'sum(r_free_pack_fee) as free',
                    'sum(r_add_service_fee) as service',
                 ])->andWhere(['!=', 'r_repay_date', '0'])->asArray()->one();
            $this->repay_pri += $repay['pri'];
            $this->repay_int += $repay['inter'];
            $this->repay_ove += $repay['overdue'];
            $this->freePack  += $repay['free'];
            $this->service   += $repay['service'];

            //$sql = clone  $repay_pri;
            //$sql->createCommand()->getRawSql();
        }

        return [
          'principal'   => $this->principal,
          'interest'    => $this->interest,
          'repay_pri'   => $this->repay_pri,
          'repay_int'   => $this->repay_int,
          'overdue'     => $this->overdue,
          'repay_ove'   => $this->repay_ove,
          'freePack'    => $this->freePack,
            'service'   => $this->service,
        ];
    }

    /**
     * 获取审计人员数据统计
     * @param null $param
     * @return array
     * @author OneStep
     */
    public function verify($param = null)
    {
        $this->load($param);
        if(!$this->validate()){
            return [];
        }
        if(!empty($this->start_time)){
            $this->start_time = strtotime($this->start_time);
        }

        if(!empty($this->end_time)){
            $this->end_time = strtotime($this->end_time);
        }

        $user = yii::$app->user->id;

        $query = Orders::find()->where(['o_operator_id'=> $user])
            ->andFilterWhere(['in', 'o_status', [Orders::STATUS_NOT_COMPLETE, Orders::STATUS_PAYING, Orders::STATUS_PAY_OVER]])
            ->andFilterWhere(['>=', 'o_created_at', $this->start_time])
            ->andFilterWhere(['<=', 'o_created_at', $this->end_time]);
        $verify['orderCount'] = $query->count();
        $verify['orderMoney'] = $query->sum('o_total_price - o_total_deposit');
        $verify['overdueMoney'] = 0;
        $verify['overdueNum'] = 0;
        foreach ($query->select(['o_id'])->asArray()->column() as $_k => $v){
            $repayQuery = Repayment::find()->where(['r_orders_id'=>$v]);
            $isOverdue= $repayQuery->andWhere(['>', 'r_overdue_day', '3'])->andWhere(['=', 'r_repay_date' ,'0'])->count()>0?1:0;
            if($isOverdue==1){
                $verify['overdueMoney'] += Repayment::find()->where(['r_orders_id'=>$v])->andWhere(['r_repay_date'=>0])->sum('r_principal');
            }
            $verify['overdueNum'] += $isOverdue;

        }

        $verify['overdueRatio'] = $verify['overdueNum']? round($verify['overdueNum']/$verify['orderCount']*100, 3). '%':'0%';

        return [
            'all'   => $verify,
            'sear'  => $this->getAttributes(),
        ];

    }
}