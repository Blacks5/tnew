<?php
namespace backend\controllers;

use Yii;
use backend\core\CoreBackendController;
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
class SiteController extends CoreBackendController
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
        p(33333);
        if (yii::$app->getUser()->getIsGuest()) {
            return yii::$app->getResponse()->redirect(['site/login']);
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
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            p(2);
            return $this->goBack();
        } else {
            //p(11);
            return $this->render('login', [
                'model' => $model,
            ]);
        }
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
