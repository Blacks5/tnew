<?php
/**
 * Created by PhpStorm.
 * User: too
 * Date: 2017/8/21
 * Time: 17:00
 * @author too <hayto@foxmail.com>
 */

namespace wechat\controllers;

use wechat\Tools\Wechat;
use Yii;
use yii\web\Controller;

class BaseController extends Controller {
	public $enableCsrfValidation = false;

	public function beforeAction($action) {
		parent::beforeAction($action);

		// 检测是否微信登录
		Wechat::Login(['site/index']);

		// 检测是否已经绑定
		$session = Yii::$app->session;

		if (!$session->get('sys_user')) {
			return $this->redirect(['login/bind']);
		}

		return true;
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
		]);
	}
}