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
use common\models\User;
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
    //logs param
    public $typeTag;

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
            [['username','realname','start_time','end_time','province','city','county', 'typeTag'], 'trim'],
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
            ->where(['!=', 'username', 'admin'])
            //->andWhere(['department_id'=> 26])
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
     */
    /*public function getLoanTotal($param = NULL)
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
            'principal'   => round($this->principal, 0),
            'interest'    => round($this->interest, 0),
            'repay_pri'   => round($this->repay_pri, 0),
            'repay_int'   => round($this->repay_int, 0),
            'overdue'     => round($this->overdue, 0),
            'repay_ove'   => round($this->repay_ove, 0),
            'freePack'    => round($this->freePack, 0),
            'service'   => round($this->service, 0),
            'user'      => $userList,
            'sear'      => $this->getAttributes(),
        ];

    }*/
    /**
     * 获取平台信息(重构的)
     * @param null $param
     * @return array
     * @author OneStep
     */
    public function getLoanTotal($param = null)
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

        $userQuery = $this->getUserList();
        $userInOrder = $userQuery->select('id')->column();

        $query = Orders::find()
            ->leftJoin(User::tableName(),'id=o_user_id')
            ->leftJoin(Repayment::tableName(), 'r_orders_id=o_id')
            ->where(['in', 'orders.o_status', [Orders::STATUS_PAYING, Orders::STATUS_PAY_OVER, ]])
            ->andWhere(['in', 'orders.o_user_id', $userInOrder])
            ->select(['
                sum(r_total_repay) as total,
                sum(r_finance_mangemant_fee) as finance,
                sum(r_customer_management) as customer,
                sum(r_add_service_fee) as service,
                sum(r_free_pack_fee) as pack,
                sum(r_principal) as principal,
                sum(r_interest) as interest,
                sum(r_overdue_money) as overdue'
            ]);
         $totalQuery = clone $query;
         $listQuery = clone $query;
         $listQuery = $listQuery->andFilterWhere(['>=', 'repayment.r_pre_repay_date', $this->start_time])
             ->andFilterWhere(['<=', 'repayment.r_pre_repay_date', $this->end_time]);

        $totalQuery = $totalQuery->andFilterWhere(['>=', 'orders.o_created_at', $this->start_time])
            ->andFilterWhere(['<=', 'orders.o_created_at', $this->end_time]);


        $fee = Orders::find()
            ->select(['sum(orders.o_service_fee) as service,sum(orders.o_inquiry_fee) as inquiry'])
            ->where(['in', 'o_status', [Orders::STATUS_PAYING, Orders::STATUS_PAY_OVER]])
            ->andWhere(['in', 'o_user_id', $userInOrder])
            ->andFilterWhere(['>=', 'o_created_at', $this->start_time])
            ->andFilterWhere(['<=', 'o_created_at', $this->end_time])
            ->asArray()->one();
        $data['serviceFee'] = round($fee['service'], 0);    //商家服务费
        $data['inquiryFee'] = round($fee['inquiry'], 0);     //查询费

        $total= $totalQuery->asArray()->one();
        $overdueQuery = clone $listQuery;
        $repayQuery = clone $listQuery;
        $data['repayTotal']      = round($total['total'], 0);        //所有月供
        $data['principal']  = round($total['principal'], 0);   //本金
        $data['interest']   = round($total['interest'], 0);    //利息
        $data['finance']    = round($total['finance'], 0);      //财务管理费
        $data['customer']   = round($total['customer'], 0);     //客户管理费
        $data['service']    = round($total['service'], 0 );     //贵宾服务包
        $data['pack']       = round($total['pack'], 0);         //个人保障计划
        $data['overdue']    = round($total['overdue'], 0);      //滞纳金
        $data['total'] = $data['principal'] + $data['interest'] + $data['finance'] + $data['customer']; //本息


        $repay = $repayQuery->andWhere(['repayment.r_status'=> 10])->asArray()->one();

        $data['repay_repayTotal'] = round($repay['total'], 0);       //已回收月供
        $data['repay_service']    = round($repay['service'], 0);      //已回收个人服务包金额
        $data['repay_pack']    = round($repay['pack'], 0);            //已回收个人保障金额
        $data['repay_overdue']   =   round($repay['overdue'], 0);  //已回收滞纳金
        $data['repay_principal']    = round($repay['principal'], 0);    //已还本金
        $data['repay_interest']     = round($repay['interest'], 0);     //已还利息
        $data['repay_finance'] = round($repay['finance'],0);        //已回收财务管理费
        $data['repay_customer'] = round($repay['customer'],0);      //已回收客户管理费
        $data['repay_total'] = $data['repay_principal'] + $data['repay_interest'] + $data['repay_finance'] + $data['repay_customer'];  //已回收本息

        $overdue = $overdueQuery->andWhere(['repayment.r_status'=>1])->asArray()->one();

        $data['overdue_repayTotal']        = round($overdue['total'], 0);       //未还月供
        $data['overdue_principal']  = round($overdue['principal'], 0);        //未回收本金
        $data['overdue_interest']   = round($overdue['interest'], 0);           //  未还利息
        $data['overdue_service']    = round($overdue['service'], 0);        //未还服务包
        $data['overdue_pack']       = round($overdue['pack'], 0);           //未还保障计划
        $data['overdue_overdue']    = round($overdue['overdue'], 0);        //滞纳金
        $data['overdue_finance']    = round($overdue['finance'], 0);        //未回收财务管理费
        $data['overdue_customer']   = round($overdue['customer'], 0);       //未回收客户管理费
        $data['overdue_total'] = $data['overdue_principal'] + $data['overdue_interest'] + $data['overdue_finance'] + $data['overdue_customer']; //未还本息


        return [
          'data' => $data,
          'user' => $userQuery->select('realname')->all(),
          'sear' => $this->getAttributes(),
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

    public function getLogs($param)
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
        $type = $this->logsType();
        $data = OperationLog::find()
            ->leftJoin(User::tableName(), 'user.id=operation_logs.operator_id')
            ->andFilterWhere(['like', 'realname', $this->realname])
            ->andFilterWhere(['like', 'type_tag', $this->typeTag])
            ->andFilterWhere(['>=', 'operation_logs.created_at', $this->start_time])
            ->andFilterWhere(['<=', 'operation_logs.created_at', $this->end_time])
            ->orderBy('operation_logs.created_at DESC')
            ->select('operation_logs.* , user.realname')
            ->asArray()->all();

        return [
            'data' => $data,
            'type' => $type,
            'sear' => $this->getAttributes(),
        ];

    }

    /**
     * 日志的分类
     * @return array
     */
    public function logsType()
    {
        $type = [
          ['customer/view', '查看客户'],
            ['borrownew/deduct-callback', '易极付扣款回调(新)'],
            ['borrow/deduct-callback', '易极付-扣款回调'],
            ['borrownew/deduct-callback', '易极付-提前还款扣款'],
            ['loan/async', '易极付-给商家放款'],
            ['jun/callback', '君子签-签约']
        ];

        return $type;
    }
}