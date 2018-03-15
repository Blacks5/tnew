<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2017/8/30
 * Time: 15:31
 */

namespace backend\models;


use backend\core\CoreBackendModel;
use Carbon\Carbon;
use common\components\Helper;
use common\models\Customer;
use common\models\Goods;
use common\models\Orders;
use common\models\Product;
use common\models\Repayment;
use common\models\Stores;
use common\models\User;
use function GuzzleHttp\Promise\all;
use function GuzzleHttp\Psr7\str;
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
     * @param $type >= or <=
     * @return array
     * @author OneStep
     */
    public function getLoanTotal($param = null, $type)
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
            ->andWhere([$type, 'orders.o_created_at', strtotime('2017-08-02 00:00:00')])
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
        $listQuery = clone $query;  // 已还
        $overdueQuery = clone $query; // 未还
        $repayQuery = $listQuery->andFilterWhere(['>=', 'repayment.r_repay_date', $this->start_time])
            ->andFilterWhere(['<=', 'repayment.r_repay_date', $this->end_time]);

        $overdueQuery = $overdueQuery->andFilterWhere(['>=', 'repayment.r_pre_repay_date', $this->start_time])
            ->andFilterWhere(['<=', 'repayment.r_pre_repay_date', $this->end_time]);

        $totalQuery = $totalQuery->andFilterWhere(['>=', 'orders.o_created_at', $this->start_time])
            ->andFilterWhere(['<=', 'orders.o_created_at', $this->end_time]);


        $fee = Orders::find()
            ->select(['sum(orders.o_service_fee) as service,sum(orders.o_inquiry_fee) as inquiry'])
            ->where(['in', 'o_status', [Orders::STATUS_PAYING, Orders::STATUS_PAY_OVER]])
            ->andWhere(['in', 'o_user_id', $userInOrder])
            ->andWhere([$type, 'o_created_at', strtotime('2017-08-02 00:00:00')])
            ->andFilterWhere(['>=', 'o_created_at', $this->start_time])
            ->andFilterWhere(['<=', 'o_created_at', $this->end_time])
            ->asArray()->one();

        $data['serviceFee'] = round($fee['service'], 0);    //商家服务费
        $data['inquiryFee'] = round($fee['inquiry'], 0);     //查询费

        $total= $totalQuery->asArray()->one();

        //$repayQuery = clone $listQuery;
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
        $type = $this->logsType();
        $query = OperationLog::find()
            ->leftJoin(User::tableName(), 'user.id=operation_logs.operator_id')
            ->andFilterWhere(['like', 'realname', $this->realname])
            ->andFilterWhere(['like', 'type_tag', $this->typeTag])
            ->andFilterWhere(['>=', 'operation_logs.created_at', $this->start_time])
            ->andFilterWhere(['<=', 'operation_logs.created_at', $this->end_time])
            ->orderBy('operation_logs.created_at DESC')
            ->select('operation_logs.* , user.realname');

        $pageQuery = clone $query;
        $pages = new yii\data\Pagination(['totalCount' => $pageQuery->count()]);
        $pages->pageSize = '10';
        $data = $query->offset($pages->offset)->limit($pages->limit)->asArray()->all();


        return [
            'data' => $data,
            'type' => $type,
            'sear' => $this->getAttributes(),
            'pages'=>$pages,
        ];

    }

    /**
     * 日志的分类
     * @return array
     */
    public function logsType()
    {
        $type = [
          ['yijifu.deduct', '易极付扣款回调'],
            ['yijifu.sign', '易极付签约回调'],
            ['yijifu.borrownew-deduct', '易极付提前还款'],
            ['yijifu.loan-pay', '易极付商家放款'],
            ['junziqian.sign', '君子签签约'],
            ['borrow.view', '查看订单详情'],
            ['auditing', '审核订单'],
            ['borrownew.prepayment', '提前还款'],
            ['borrownew.cancel-vip-pack', '取消贵宾包'],
            ['borrownew.cancel-personal-protection', '取消个人保障'],
            ['loan.loan', '商家放款'],
            ['repaymentnew.repay', '发起扣款']
        ];

        return $type;
    }

    public function getCustomerOders($s_time, $e_time)
    {


        $orders = Orders::find()
            ->select('*')
            ->leftJoin(Customer::tableName(), 'c_id=o_customer_id')
            ->leftJoin(Stores::tableName(), 's_id=o_store_id')
            ->leftJoin(Product::tableName(), 'p_id=o_product_id')
            ->leftJoin(User::tableName(), 'id=o_user_id')
            ->leftJoin(Goods::tableName(), 'g_order_id=o_id')
            ->Where(['in', 'o_status', [Orders::STATUS_PAYING, Orders::STATUS_PAY_OVER]])
            ->andFilterWhere(['>=', 'orders.o_operator_date', $s_time])
            ->andFilterWhere(['<=', 'orders.o_operator_date', $e_time])
            ->asArray()->all();

        $data = [];
        $marital = [1 => '未婚', 2 => '已婚', 3 => '离异', 4 => '丧偶'];
        $kinship = [1 => '父亲', 2 => '母亲', 3 => '兄弟', 4 => '姐妹', 5 => '子女', 6 => '表兄弟', 7 => '表兄妹', 8 => '其他'];
        $other = [1 => '同事', 2 => '朋友', 3 => '同学'];
        foreach ($orders as $k => $v) {
            $repayment = Repayment::find()->select(['r_total_repay', 'r_pre_repay_date'])->where(['r_orders_id' => $v['o_id']])->orderBy('r_pre_repay_date')->asArray()->one();
            $repay_date = Carbon::createFromTimestamp($repayment['r_pre_repay_date'])->day;

            $operator = User::findOne($v['o_operator_id']);

            $data[$k]['o_operator_date'] = date('Y-m-d', $v['o_operator_date']);
            $data[$k]['o_serial_id'] = $v['o_serial_id'];
            $data[$k]['customer_name'] = $v['c_customer_name'];
            $data[$k]['customer_gender'] = $v['c_customer_gender'] == 1 ? '男' : '女';
            $data[$k]['customer_marital'] = $marital[$v['c_family_marital_status']];
            $data[$k]['customer_phone'] = $v['c_customer_cellphone'];
            $data[$k]['customer_card_type'] = '身份证';
            $data[$k]['customer_card'] = $v['c_customer_id_card'];
            $data[$k]['s_name'] = $v['s_name'];
            $data[$k]['s_phone'] = $v['s_owner_phone'];
            $data[$k]['p_name'] = $v['p_name'];
            $data[$k]['p_period'] = $v['p_period'];
            $data[$k]['g_goods_name'] = $v['g_goods_name'];
            $data[$k]['total'] = $v['o_total_price'];
            $data[$k]['interest'] = $v['o_total_price'] - $v['o_total_deposit'] + $v['o_service_fee'] + $v['o_inquiry_fee'];
            $data[$k]['repay_date'] = $repay_date;
            $data[$k]['r_total_repay'] = $repayment['r_total_repay'];
            $data[$k]['customer_bank'] = $v['c_bank'];
            $data[$k]['customer_bank_no'] = $v['c_banknum'];
            $data[$k]['server_fee'] = $v['o_is_add_service_fee'] == 1 ? '是' : '否';
            $data[$k]['free_pack'] = $v['o_is_free_pack_fee'] == 1 ? '是' : '否';
            $data[$k]['auto_pay'] = $v['o_is_auto_pay'] == 1 ? '是' : '否';
            $data[$k]['kinship'] = $kinship[$v['c_kinship_relation']] . '-' . $v['c_kinship_name'] . '-' . $v['c_kinship_cellphone'];
            $data[$k]['partner_phone'] = $v['c_family_marital_partner_cellphone'];
            $data[$k]['other'] = Yii::$app->params['kinship'][$v['c_other_people_relation']]['kinship_str'] . '-' . $v['c_other_people_name'] . '-' . $v['c_other_people_cellphone'];
            $data[$k]['customer_address'] = $v['c_customer_idcard_detail_addr'];
            $data[$k]['customer_detail_address'] = $v['c_customer_addr_detail'];
            $data[$k]['customer_work_address'] = $v['c_customer_jobs_detail_addr'];
            $data[$k]['customer_work_name'] = $v['c_customer_jobs_company'];
            $data[$k]['customer_work_phone'] = $v['c_customer_jobs_phone'];
            $data[$k]['user_name'] = $v['realname'];
            $data[$k]['operator_name'] = $operator->realname;
        }

        if(empty($data)){
            throw new yii\base\ErrorException('这期间没有订单');
        }
        $fileName = date('Y/m/d',$s_time).'-'. date('Y/m/d',$e_time) . '.csv';
        $this->downloadCsv($data, $fileName);
    }

    private function downloadCsv($parameter, $fileName)
    {
        if (is_array($parameter)) {
            $filename = $fileName;
            header('Content-Type: text/csv');
            header("Content-Disposition: attachment;filename={$filename}");
            $fp = fopen('php://output', 'w');
            fwrite($fp,chr(0xEF).chr(0xBB).chr(0xBF));
            if ( ! empty($parameter['header']) && is_array($parameter['header'])) {
                fputcsv($fp, $parameter['header']);
            }
            if (isset($parameter)) {
                foreach ($parameter as $row) {
                    fputcsv($fp, $row);
                }
            }return true;
        }
        throw new yii\web\HttpException(500, "Not a valid parameter!");
    }
}