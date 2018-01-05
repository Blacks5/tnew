<?php

/**
 * 用户服务
 * @Author: MuMu
 * @Date:   2017-11-22 10:48:34
 * @Last Modified by:   MuMu
 * @Last Modified time: 2018-01-05 15:35:41
 */

namespace common\services;

use backend\components\CustomBackendException;
use common\components\CustomCommonException;
use Yii;

class User extends Service {
	// 微服务地址
	protected $microServiceUrl = 'http://users.api.tnew.cn/v1/';
	// 微服务地址【开发】
	protected $devMicroServiceUrl = 'http://users.devapi.tnew.cn/v1/';
	// 获取用户TOKEN路由
	private $queryUserTokenRouter = '/users/{login_name}/tokens';
	// 创建用户
	private $createUserRouter = '/users';

	/**
	 * 获取用户TOKEN
	 * @param  string $username 用户名称
	 * @return string           token信息
	 */
	public function queryUserToken($username) {
		// 获取请求地址
		$url = $this->buildUrl($this->queryUserTokenRouter, [
			'login_name' => $username,
		]);

		$res = $this->httpPost($url);

		if ($res['success']) {
			return $res['data']['token']['access_token'];
		} else {
			throw new CustomCommonException($res['errors'][0]['message']);
		}
	}

	/**
	 * 创建用户
	 * @param  [type] $params [description]
	 * @return [type]         [description]
	 */
	public function createUser($params) {
		try {
			$user = new \common\models\User;

			$params['status'] = \common\models\User::STATUS_ACTIVE;

			if (!$user->createUser(['User' => $params])) {
				return false;
			}
		} catch (CustomBackendException $e) {
			throw new CustomCommonException($e->getMessage());
		}

		try {
			// 获取系统用户
			$sys_user = Yii::$app->session->get('sys_user');

			// 获取请求地址
			$url = $this->buildUrl($this->createUserRouter);

			$res = $this->httpPost($url, [
				'type' => 'agent',
				'v1_id' => $user->id,
				'inviter' => $sys_user->id,
				'phone' => $params['cellphone'],
				'name' => $params['realname'],
				'login_name' => $params['username'],
				'password' => $params['password_hash'],
			]);

			if ($res['success']) {
				return true;
			} else {
				$user->delete();
				throw new CustomCommonException($res['errors'][0]['message']);
			}
		} catch (CustomCommonException $e) {
			$user->delete();
			throw new CustomCommonException($e->getMessage());
		}
	}
}
