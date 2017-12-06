<?php
/**
 * 首页控制器
 */
namespace wechat\controllers;

use common\models\User;
use wechat\Tools\Wechat;
use Yii;

class SiteController extends BaseController {
	/**
	 * @inheritdoc
	 */
	public function actions() {
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

	// 首页
	public function actionIndex() {
		// 获取系统用户
		$sys_user = Yii::$app->session->get('sys_user');

		// 获取微信用户
		$wechat_user = Yii::$app->session->get('wechat_user');

		return $this->renderPartial('index', [
			'js' => Wechat::jssdk(),
			'wechat_user' => $wechat_user,
			'sys_user' => $sys_user,
		]);
	}

	// 退出登录
	public function actionLogout() {
		if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
			// 销毁session
			Yii::$app->session->remove('sys_user');

			Yii::$app->session->remove('wechat_user');

			Yii::$app->session->destroy();

			Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

			return ['status' => 1, 'message' => '登出成功'];
		}
	}

	// 解除绑定
	public function actionUnbind() {
		if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {

			Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

			// 获取系统用户
			$sys_user = Yii::$app->session->get('sys_user');

			// 获取微信用户
			$wechat_user = Yii::$app->session->get('wechat_user');

			if ($sys_user && $wechat_user) {
				$user = User::findOne(['wechat_openid' => $wechat_user->id, 'id' => $sys_user->id]);

				if ($user) {
					$user->wechat_openid = '';

					$user->save(false);

					// 销毁session
					Yii::$app->session->remove('sys_user');

					Yii::$app->session->remove('wechat_user');

					Yii::$app->session->destroy();

					return ['status' => 1, 'message' => '解绑成功'];
				} else {
					return ['status' => 0, 'message' => '解绑失败'];
				}
			} else {
				return ['status' => 0, 'message' => '解绑失败'];
			}
		}
	}
}
