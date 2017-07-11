<?php
namespace backend\controllers;
// tz
use common\models\Department;
use common\tools\yijifu\Loan;
use common\tools\yijifu\ReturnMoney;
use common\tools\yijifu\Sign;
use mdm\admin\models\searchs\User;
use WebSocket\Client;
use Yii;
use backend\core\CoreBackendController;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use backend\models\Menu;
use common\models\YijifuLoan;
use yii\db\Query;

/**
 * Site controller
 */
class SiteController extends CoreBackendController
{
    /**
     * @inheritdoc
     */
    /*public function behaviors111()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['get'],
                ],
            ],
        ];
    }*/

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        $user_id=Yii::$app->getUser()->getIdentity()->getId();
//        $user_info = Yii::$app->authManager->getRolesByUser($user_id);
        $d_id = User::find()->select(['department_id'])->where(['id'=>$user_id])->scalar();
        $user_info = Department::find()->select(['d_name'])->where(['d_id'=>$d_id])->scalar();
        $menu = new Menu();
//        p($user_id, $user_info);
        $menu = $menu->getLeftMenuList();
//        echo '<pre>';
//        var_dump($menu);die;
        return $this->render('index',[
            'menu' => $menu,
//            'user_info' => key($user_info)
                'user_info'=>$user_info
        ]);
    }

    public function actionList()
    {
        return $this->render('list');
    }


    public function actionTest()
    {
        $handle = new ReturnMoney();
        $ret = $handle->signContractWithCustomer(
            '袁琼莲',//'钟建蓉',
                '510623199904221120',//'510623197905114125',
            6228480498139638171,
            18990232122,
        'iPhone8',
        '5899',
        'http://php.net/images/to-top@2x.png',
        12,
        ''
            );
        var_dump($ret);
    }

    public function actionTest2()
    {
        $handle = new ReturnMoney();
        $ret = $handle->querySignedCustomer('5892-2');
        p($ret);
    }

    public function beforeAction($action)
    {
        if('async' === $action->id){
            $this->enableCsrfValidation = false;
        }
        return true;
    }

    public function actionAsync()
    {
        /*array(18) {
  ["bankCode"]=>
  string(3) "ABC"
  ["orderNo"]=>
  string(21) "149930990474369712682"
  ["bankCardNo"]=>
  string(19) "622848*********8171"
  ["notifyTime"]=>
  string(19) "2017-07-06 10:58:20"
  ["bankCardType"]=>
  string(10) "DEBIT_CARD"
  ["resultCode"]=>
  string(15) "EXECUTE_SUCCESS"  可能没有 EXECUTE_SUCCESS处理成功    EXECUTE_FAIL处理失败    EXECUTE_PROCESSING处理中
  ["sign"]=>
  string(32) "e8d95db65e0891e8d0eaa102c9ab3545"
  ["description"]=>
  string(0) ""
  ["bankName"]=>
  string(12) "农业银行"
  ["resultMessage"]=>
  string(6) "成功" 返回信息，可能没有
  ["version"]=>
  string(3) "1.0" 可能没有
  ["protocol"]=>
  string(8) "httpPost" 可能没有
  ["service"]=>
  string(8) "fastSign"
  ["success"]=>
  string(4) "true"
  ["merchOrderNo"]=>
  string(6) "5899-1"
  ["signType"]=>
  string(3) "MD5"
  ["partnerId"]=>
  string(20) "20160831020000752643"
  ["status"]=>
  string(12) "SIGN_SUCCESS"
SIGN_DEALING：签约处理中
SIGN_FAIL：签约失败
CHECK_NEEDED：待审核
CHECK_REJECT：审核驳回
SIGN_SUCCESS：签约成功

}*/
        $post = Yii::$app->getRequest()->post();


        ob_start();
        var_dump($post);
        file_put_contents('/dev.txt', ob_get_clean(), FILE_APPEND);

        echo "success";
    }

    /**
     * ws客户端页面，用于检查数据接收
     * @return string
     * @author too <hayto@foxmail.com>
     */
    public function actionWs()
    {
        return $this->renderPartial('ws');
    }


    /**
     * 像websocket发送数据
     * @param $a
     * @author too <hayto@foxmail.com>
     */
    public function actionSendws($a)
    {
//        $client = new Client("ws://119.23.15.90:8081");
        $client = new Client("ws://192.168.0.194:8888");
        /*$data = [
            ''
        ];
        $client->send($a);
        echo $client->receive();*/


        $data['controller_name'] = 'AppController';
        $data['method_name'] = 'test';
        $data['data1'] = $a;
        $data['data2'] = str_repeat('a', 1024*1024*1);
        $data['data3'] = ['a','b'];
        $jsonData = json_encode($data, JSON_UNESCAPED_UNICODE);

        $total_length = 4 + strlen($jsonData);
        $senddata = /*pack('N', $total_length) . */$jsonData;
        var_dump($senddata);
        $client->send($senddata);
    }

    /**
     * 放款测试
     */
    public function actionTestloan(){
        $order_id = Yii::$app->getRequest()->post('order_id');
        if(!$order_id){
            throw new CustomCommonException('系统错误!');
        }

        $_data = (new Query())->from(Orders::tableName())
            ->join('LEFT JOIN', 'stores', 'orders.o_store_id = stores.s_id')
            ->where(['orders.order_id'=>$order_id,'orders.o_status'=>10])
            ->one();

        if($_data === false){
            throw new CustomCommonException('系统错误!');
        }else{
            $Loan_model = new Loan();
            if($_data['s_bank_is_private'] == 1){
                //对私
/*                $post = Yii::$app->getRequest()->post();
                $amount = $post['amount'];
                $outOrderNo = $post['$outOrderNo'];
                $contractUrl = $post['$contractUrl'];
                $realName = $post['$realName'];
                $mobileNo = $post['$mobileNo'];
                $certNo = $post['$certNo'];
                $bankCardNo = $post['$bankCardNo'];*/


                $amount = '1000';
                $outOrderNo = '000500201g1zvaztwp06' . time();
                $contractUrl = 'http://php.net/images/to-top@2x.png';
                $realName = '李正周';
                $mobileNo = '15951215597';
                $certNo = '320382198909181037';
                $bankCardNo = '6228480413868991410';

                $return_data = $Loan_model->userLoan($amount,$outOrderNo,$contractUrl,$realName,$mobileNo,$certNo,$bankCardNo);
                var_dump($return_data);
            }else{
                //对公

                //todo 处理银行编码,银行名称等参数
                //对私
/*                $post = Yii::$app->getRequest()->post();
                $amount = $post['amount'];
                $outOrderNo = $post['outOrderNo'];
                $contractUrl = $post['contractUrl'];
                $realName = $post['realName'];
                $mobileNo = $post['mobileNo'];
                $certNo = $post['certNo'];
                $bankCardNo = $post['bankCardNo'];
                $bankCode = $post['bankCode'];
                $bankName = $post['bankName'];
                $sellerBankProvince = $post['sellerBankProvince'];
                $sellerBankCity = $post['sellerBankCity'];
                $sellerBankAddress = $post['sellerBankAddress'];*/


                $amount = '1000';
                $outOrderNo = '000500201g1zvaztwp06' . time();
                $contractUrl = 'http://php.net/images/to-top@2x.png';
                $realName = '李正周';
                $mobileNo = '15951215597';
                $certNo = '320382198909181037';
                $bankCardNo = '6228480413868991410';
                $bankCode = 'ABC';
                $bankName = '中国农业银行';
                $sellerBankProvince = '江苏省';
                $sellerBankCity = '常州市';
                $sellerBankAddress = '武进区支行';

                $return_data = $Loan_model->userLoan(
                    $amount,
                    $outOrderNo,
                    $contractUrl,
                    $realName,
                    $mobileNo,
                    $certNo,
                    $bankCardNo,
                    $bankCode,
                    $bankName,
                    $sellerBankProvince,
                    $sellerBankCity,
                    $sellerBankAddress
                );
            }

            //根据响应参数输出数据
            if($return_data['resultCode']=='EXECUTE_SUCCESS'){

            }else{

            }
        }

//同步响应参数
//        array(10) {
//            ["sign"]=> string(32) "6192ca15b4adb671d53528dc774bd0cd"
//            ["protocol"]=> string(8) "httpPost"
//            ["orderNo"]=> string(21) "149977776084089497780"
//            ["signType"]=> string(3) "MD5"
//            ["service"]=> string(20) "yxtQuicklyRemittance"
//            ["resultCode"]=> string(15) "EXECUTE_SUCCESS"
//            ["partnerId"]=> string(20) "20160831020000752643"
//            ["resultMessage"]=> string(6) "成功"
//            ["success"]=> bool(true)
//            ["version"]=> string(3) "1.0"
//        }

    }

    /**
     * 放款回调方法
     * @author lilaotou <liwansen@foxmail.com>
     */
    public function actionAsyncloan()
    {
        $post = Yii::$app->getRequest()->post();
        //获取放款记录
        $_data = (new Query())->from(YijifuLoan::tableName())
            ->where(['order_id'=>$post['outOrderNo']])
            ->one();

        //异步回调方法里写放款记录(根据回调信息,添加不同的状态,如果此订单的记录存在且status状态值为1未放款,就修改,如果没有数据就添加一条)
        if($post['status'] == 'REMITTANCE_SUCCESS'){
            $status = 2;
        }else{
            $status = 1;
        }
        $operator_id = 510;
        if($_data){
            if($_data['status'] == 1){
                //修改
                $_data['contractNo'] = $post['contractNo'];
                $_data['status'] = $status;
                $_data['operator_id'] = $operator_id;
                $_data['updated_at'] = $_SERVER['REQUEST_TIME'];
                \Yii::$app->getDb()->createCommand()->update(YijifuLoan::tableName(), $_data, ['id'=>$_data['id']])->execute();
            }
        }else{
            //新增
            $wait_inster_data = [
                'order_id'=>$post['outOrderNo'],
                'amount'=>$post['amount'],
                'realRemittanceAmount'=>$post['realRemittanceAmount'],
                'contractNo'=>$post['contractNo'],
                'status'=>$status, // 1未成功  2已成功
                'operator_id'=>$operator_id,
                'created_at'=>$_SERVER['REQUEST_TIME']
            ];
            \Yii::$app->getDb()->createCommand()->insert(YijifuLoan::tableName(), $wait_inster_data)->execute();
        }
        echo "success";

        /*异步响应参数
              array(21) {
                ["bankCode"]=>
                  string(3) "ABC"
                        ["realRemittanceAmount"]=>
                  string(7) "1000.00"
                        ["amount"]=>
                  string(7) "1000.00"
                        ["orderNo"]=>
                  string(21) "149978111235455705557"
                        ["bankCardNo"]=>
                  string(19) "622848*********1410"
                        ["contractNo"]=>
                  string(20) "000g03001h5uzrhms800"
                        ["notifyTime"]=>
                  string(19) "2017-07-11 21:52:08"
                        ["resultCode"]=>
                  string(15) "EXECUTE_SUCCESS"
                        ["sign"]=>
                  string(32) "6f13bcc14975f9dc5d4fbfd98223f86f"
                        ["memo"]=>
                  string(12) "代发成功"
                        ["resultMessage"]=>
                  string(6) "成功"
                        ["outOrderNo"]=>
                  string(30) "000500201g1zvaztwp061499781112"
                        ["version"]=>
                  string(3) "1.0"
                        ["realName"]=>
                  string(9) "李正周"
                        ["protocol"]=>
                  string(8) "httpPost"
                        ["service"]=>
                  string(20) "yxtQuicklyRemittance"
                        ["success"]=>
                  string(4) "true"
                        ["signType"]=>
                  string(3) "MD5"
                        ["chargeAmount"]=>
                  string(4) "1.00"
                        ["partnerId"]=>
                  string(20) "20160831020000752643"
                        ["status"]=>
                  string(18) "REMITTANCE_SUCCESS"
        }*/


    }
}
