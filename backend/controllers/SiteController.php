<?php
namespace backend\controllers;
// tz
use common\models\Department;
use common\tools\Tools;
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
use common\models\Orders;
use common\components\CustomCommonException;
use common\components\Helper;

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

    public function actionT()
    {
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
  //      $client = new Client("ws://192.168.0.194:8081");
        $client = new Client(Yii::$app->params['ws']);
        $data = [
            'cmd'=>'Orders:loanNotify',
            'data'=>[
                'message'=>"李大爷创建了新订单",
                'order_id'=>5
            ]
        ];
        $jsonData = json_encode($data, JSON_UNESCAPED_UNICODE);
        var_dump($jsonData);
        $client->send($jsonData);
    }

}
