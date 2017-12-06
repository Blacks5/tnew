<?php
/**
 * 账号绑定
 */
namespace wechat\controllers;

use common\models\LoginForm;
use common\models\User;
use wechat\Tools\Wechat;
use Yii;
use yii\web\Controller;

class LoginController extends Controller {
	// 账号密码绑定
	public function actionBind() {
		$request = Yii::$app->request;

		// 绑定操作
		if ($request->isAjax && $request->isPost) {
			Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

			// 绑定账号
			$openid = $request->post('openid');

			// 获取微信用户
			$wechat_user = Yii::$app->session->get('wechat_user');

			// 检测参数是否一致
			if ($wechat_user->id != $openid) {
				return ['status' => 0, 'message' => '绑定失败'];
			}

			$model = new LoginForm();
			$data['data'] = $request->post();
			$model->load($data, 'data');

			// 登录成功后，写入openid
			if ($data = $model->login()) {
				// 判断该系统账号是否已经被绑定过了
				$sys_user = $model->getUser();

				if ($sys_user->wechat_openid) {
					return ['status' => 0, 'message' => '账号已绑定，请勿重复操作'];
				}

				// 绑定操作
				$state = Yii::$app->db->createCommand()
					->update(User::tableName(), ['wechat_openid' => $openid], ['id' => Yii::$app->getUser()->getId()])
					->execute();

				if (false !== $state) {
					// 获取所在位置
					$regions = [intval($sys_user->province), intval($sys_user->city), intval($sys_user->county)];

					// 加入地区数据
					$sys_user->areas = array_values(User::getAreas($regions));
					
					Yii::$app->session->set('sys_user', $sys_user);

					return ['status' => 1, 'message' => '绑定成功'];
				}
			}
			return ['status' => 0, 'message' => '绑定失败'];
		} else {
			// 检测是否微信登录
			Wechat::Login(['site/index']);

			// 获取微信用户
			$wechat_user = Yii::$app->session->get('wechat_user');

			return $this->renderPartial('login', [
				'openid' => $wechat_user->id,
				'avatar' => $wechat_user->avatar,
				'js' => Wechat::jssdk()
			]);
		}
	}

	// 账号密码登录
	public function actionLogin() {

	}
}