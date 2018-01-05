<?php
/**
 * 首页控制器
 */
namespace wechat\controllers;

use common\components\CustomCommonException;
use common\models\User;
use wechat\Tools\Wechat;
use Yii;

class SiteController extends BaseController {
	// 首页
	public function actionIndex() {
		// 获取系统用户
		$sys_user = Yii::$app->session->get('sys_user');

		if (!$sys_user) {
			return $this->redirect(['login/bind']);
		}

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

				// 销毁session
				Yii::$app->session->remove('sys_user');

				Yii::$app->session->remove('wechat_user');

				Yii::$app->session->destroy();

				if ($user) {
					$user->wechat_openid = '';

					$user->save(false);

					return ['status' => 1, 'message' => '解绑成功'];
				} else {
					return ['status' => 1, 'message' => '解绑成功'];
				}
			} else {
				return ['status' => 0, 'message' => '解绑失败'];
			}
		}
	}

	// 账号注册
	public function actionRegister() {
		$request = Yii::$app->request;

		if ($request->isAjax && $request->isPost) {
			Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

			// 获取系统用户
			$sys_user = Yii::$app->session->get('sys_user');

			// 创建订单
			$params = [
				'username' => $request->post('username', ''),
				'cellphone' => $request->post('username', ''),
				'password_hash' => $request->post('password', ''),
				'password_hash_1' => $request->post('password_confirm', ''),
				'realname' => $request->post('realName', ''),
				'id_card_num' => $request->post('certNo', ''),
				'certNoAddress' => $request->post('certNoAddress', ''),
				'address' => $request->post('address', ''),
				'email' => $request->post('email', ''),
			];

			// 默认参数
			$params['department_id'] = null;
			$params['job_id'] = null;
			$params['leader'] = 6;
			$params['level'] = 6;
			$params['id_card_pic_one'] = '';

			// 被邀请人所属省市县与当前用户保持一致
			$params['province'] = isset($sys_user->areas[0]) ? $sys_user->areas[0] : '';
			$params['city'] = isset($sys_user->areas[1]) ? $sys_user->areas[1] : '';
			$params['county'] = isset($sys_user->areas[2]) ? $sys_user->areas[2] : '';

			try {
				$user = new \common\services\User;
				$res = $user->createUser($params);

				if ($res) {
					return ['status' => 1, 'message' => '注册成功'];
				} else {
					return ['status' => 0, 'message' => '注册失败'];
				}
			} catch (CustomCommonException $e) {
				return ['status' => 0, 'message' => $e->getMessage()];
			} catch (\Exception $e) {
				return ['status' => 0, 'message' => '网络异常'];
			}
		} else {
			// 获取系统用户
			$sys_user = Yii::$app->session->get('sys_user');

			// 获取微信用户
			$wechat_user = Yii::$app->session->get('wechat_user');

			return $this->renderPartial('register', [
				'sys_user' => $sys_user,
				'wechat_user' => $wechat_user,
				'js' => Wechat::jssdk(),
			]);
		}
	}

	public function actionRegisterSuccess() {
		return $this->renderPartial('success');
	}
}
