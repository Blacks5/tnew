<?php
/**
 * 首页控制器
 */
namespace wechat\controllers;

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
		return $this->renderPartial('index');
	}
}
