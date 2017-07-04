<?php
namespace backend\controllers;

use common\models\Department;
use common\tools\yijifu\Sign;
use mdm\admin\models\searchs\User;
use Yii;
use backend\core\CoreBackendController;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use backend\models\Menu;

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
//        Yii::$app->mailer->compose()->setFrom('a')
        return $this->render('list');
    }


    public function actionTest()
    {
        $data = [
            'merchOrderNo'=>'1234',// 商户订单号
            'merchContractNo'=>'1234', // 商户签约合同号
            'merchContractImage'=> "http://php.net/images/to-top@2x.png", // 签约合同照片 支持jpg jpeg bmp png pdf'
            'realName'=>'涂鸿', // 借款人真实姓名
            'bankCardNo'=>'6217003601940585537', // 借款人银行卡号
            'certNo'=>'510623198812250210', // 借款人身份证号
            'mobileNo'=>'18990232122', // 借款人手机，用于发通知短信
            'productName'=>'分期一号', //  // 产品名称，将显示在用户短信中
            'loanAmount'=>12, // 借款金额 【可不填】显示在用户短信中
            'totalRepayAmount'=>10, // 应还总金额 包括所有的各种费用
        ];
        $handle = new Sign();
        $ret = $handle->signUser($data);
        var_dump($ret);
    }
}
