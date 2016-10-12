<?php
namespace backend\controllers;

use Yii;
use common\core\CoreController;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use common\models\SignupForm;
use yii\helpers\Url;
use common\models\ContactForm;
use yii\web\User;
/**
 * Site controller
 */
class SiteController extends CoreController
{

    /**
     * @inheritdoc
     */
    /*    public function behaviors_bakkk()
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
                        'logout' => ['post'],
                    ],
                ],
            ];
        }*/
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        //p(2222);
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        if (yii::$app->getUser()->getIsGuest()) {
            return $this->redirect(['site/login']);
        }
        return $this->render('index');
    }

    public function actionTest()
    {
        return $this->render('test');
    }
    public function actionTest2()
    {
        return $this->render('test2');
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
//p(3);
        $model = new LoginForm();
        return $this->render('index');


    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
