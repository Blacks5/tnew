<?php
namespace backend\controllers;

use common\models\Department;
use common\tools\yijifu\ReturnMoney;
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
        '5898',
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
        $data = file_get_contents("php://input");
        ob_start();
        var_dump($data);
        file_put_contents('/dev.txt', ob_get_clean(), FILE_APPEND);
    }
}
