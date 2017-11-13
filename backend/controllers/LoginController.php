<?php
/**
 * Created by PhpStorm.
 * User: too
 * Date: 2016/10/14
 * Time: 17:38
 * @author 涂鸿 <hayto@foxmail.com>
 */
namespace backend\controllers;

use backend\events\LoginEvent;
use common\models\LoginForm;
use yii;
use common\core\CoreCommonController;
use yii\httpclient\Client as HttpClient;
use yii\httpclient\Request;
use yii\httpclient\RequestEvent;
use yii\log\FileTarget;

/**
 * 登录 退出 控制器
 * 唯一一个继承自CoreCommonController的控制器
 * Class LoginController
 * @package backend\controllers
 * @author 涂鸿 <hayto@foxmail.com>
 */
class LoginController extends CoreCommonController
{
    /**
     * 登录
     * @return string|yii\web\Response
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionLogin()
    {
        Yii::$app->getView()->title = '登录';
        if (!Yii::$app->getUser()->getIsGuest()) {
            return $this->goHome();
        }
        usleep(500);
        $model = new LoginForm();
        if ($model->load(Yii::$app->getRequest()->post())) {

            // 先綁定事件hander \yii\web\User里会trigger
            // 写登录日志
            Yii::$app->getUser()->on(yii\web\User::EVENT_AFTER_LOGIN, ['backend\events\LoginEvent', 'writeLoginLog']);
            // 然后执行
            $v = $model->login();
            if ($v) {
                Yii::$app->session->set('V2_TOKEN', $this->v2Token());
            }
            return $this->redirect(['site/index']);
        } else {
            // 解决页面跳转问题
            if ($referer = \Yii::$app->request->headers->get('referer')) {
                $url_arr = parse_url($referer);

                if(isset($url_arr['path']) && ltrim($url_arr['path'] , '/') != 'login/login'){
                    echo "<script>top.location.href='/login/login'</script>";
                    return;
                }
            }

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    private function v2Token()
    {
        $client = new HttpClient();
        $request = $client->createRequest()
            ->setMethod('post')
            ->setFormat(HttpClient::FORMAT_URLENCODED)
            ->setUrl(sprintf(
                '%susers/%s/tokens',
                Yii::$app->params['v2_user'],
                Yii::$app->getUser()->getIdentity()->username
            ))
            ->setHeaders(['X-TOKEN' => Yii::$app->params['v2_user_token']])
            ->setData($post);
        $token = '';
        $request->on(Request::EVENT_AFTER_SEND, function (RequestEvent $e) use (&$token) {
            $res = $e->response->getData();
            if ($res['success']) {
                $token = $res['data']['token']['access_token'];
            }
        });
        $request->send();
        return $token;
    }

    /**
     * 退出登录
     * @return yii\web\Response
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionLogout()
    {
        Yii::$app->getUser()->logout();
        return $this->goHome();
    }
}