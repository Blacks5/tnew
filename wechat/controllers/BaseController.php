<?php
/**
 * Created by PhpStorm.
 * User: too
 * Date: 2017/8/21
 * Time: 17:00
 * @author too <hayto@foxmail.com>
 */

namespace wechat\controllers;

use common\models\LoginForm;
use wechat\Tools\Wechat;
use Yii;
use yii\httpclient\Client;
use yii\web\Controller;
use common\services\User;

class BaseController extends Controller {
	public $enableCsrfValidation = false;

	public function beforeAction($action) {
		parent::beforeAction($action);

		// 检测是否微信登录
		Wechat::Login(['site/index']);

		// 检测是否已经绑定
		$sys_user = Yii::$app->session->get('sys_user');

		if (!$sys_user || !$sys_user->wechat_openid) {
			return $this->redirect(['login/bind']);
		}

		return true;
	}

	private function userSession() {
		$model = new LoginForm();
		$data['data'] = [
			'username' => 'chunlantian',
		];
		$model->load($data, 'data');

		$sys_user = $model->getUser();

		// 获取所在位置
		$regions = [intval($sys_user->province), intval($sys_user->city), intval($sys_user->county)];
		// 加入地区数据
		$sys_user->areas = array_values(\common\models\User::getAreas($regions));

		Yii::$app->session->set('sys_user', $sys_user);
	}

	/**
	 * 渲染错误页面
	 * @param  string $title 提示标题
	 * @param  string $desc  提示描述
	 * @return
	 */
	public function randerError($title, $desc = '') {
		return $this->renderPartial('/site/page', [
			'title' => $title,
			'desc' => $desc,
			'status' => 0,
			'js' => Wechat::jssdk(),
		]);
	}

	/**
	 * 渲染正确页面
	 * @param  string $title 提示标题
	 * @param  string $desc  提示描述
	 * @return
	 */
	public function randerSuccess($title, $desc = '') {
		return $this->renderPartial('/site/page', [
			'title' => $title,
			'desc' => $desc,
			'status' => 1,
			'js' => Wechat::jssdk(),
		]);
	}

	
}