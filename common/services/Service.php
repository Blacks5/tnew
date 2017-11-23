<?php

/**
 * Description Content
 * @Author: MuMu
 * @Date:   2017-11-22 10:52:39
 * @Last Modified by:   MuMu
 * @Last Modified time: 2017-11-23 14:33:46
 */
namespace common\services;

use common\components\CustomCommonException;
use Yii;
use yii\httpclient\Client;

class Service {
	/**
	 * 发起HTTP POST请求
	 * @param  string $url    请求地址
	 * @param  array  $params 请求参数
	 * @return array
	 */
	protected function httpPost($url, $params = [], $tokenFrom = 'user') {
		if ($token = $this->getToken($tokenFrom)) {
			$httpClient = new Client();

			$response = $httpClient->post($url, $params, [
				'X-TOKEN' => $token,
			])->send();

			return $response->data;
		} else {
			throw new CustomCommonException('Service token failed to get.');
		}
	}

	/**
	 * 发起HTTP GET请求
	 * @param  string $url    请求地址
	 * @param  array  $params 请求参数
	 * @return array
	 */
	protected function httpGet($url, $params = [], $tokenFrom = 'user') {
		if ($token = $this->getToken($tokenFrom)) {
			$httpClient = new Client();

			$response = $httpClient->get($url, $params, [
				'X-TOKEN' => $token,
			])->send();

			return $response->data;
		} else {
			throw new CustomCommonException('Service token failed to get.');
		}
	}

	/**
	 * 发起HTTP PATCH请求
	 * @param  string $url    请求地址
	 * @param  array  $params 请求参数
	 * @return array
	 */
	protected function httpPatch($url, $params = [], $tokenFrom = 'user') {
		if ($token = $this->getToken($tokenFrom)) {
			$httpClient = new Client();

			$response = $httpClient->patch($url, $params, [
				'X-TOKEN' => $token,
			])->send();

			return $response->data;
		} else {
			throw new CustomCommonException('Service token failed to get.');
		}
	}

	/**
	 * 获取完整链接
	 * @param  string $router 路由地址
	 * @return string         完整链接
	 */
	protected function buildUrl($router, $replace = []) {
		if ($replace) {
			foreach ($replace as $key => $value) {
				$router = str_replace('{' . $key . '}', $value, $router);
			}
		}

		return rtrim($this->microServiceUrl, '/') . '/' . ltrim($router, '/');
	}

	/**
	 * 获取token
	 * @param  string $tokenFrom token来源
	 * @return mixed             token字符串
	 */
	protected function getToken($tokenFrom = 'user') {
		if ($tokenFrom == 'user') {
			if ($sysUser = Yii::$app->session->get('sys_user')) {
				// 获取用户ID
				if ($userId = $sysUser->id) {
					$tokenKey = 'user:' . $userId . ':accesstoken';

					// 获取token
					if ($token = Yii::$app->cache->get($tokenKey)) {
						return $token;
					}

					$url = 'http://users.devapi.tnew.cn/v1/users/' . $sysUser->username . '/tokens';
					$httpClient = new Client();
					$response = $httpClient->post($url, [], [
						'X-TOKEN' => Yii::$app->params['server_communicate_token'],
					])->send();

					$res = $response->data;

					if ($res['success']) {
						$token = $res['data']['token']['access_token'];

						Yii::$app->cache->set($tokenKey, $token, 7200);

						return $token;
					}
				}
			}

			return null;
		} else {
			return Yii::$app->params['server_communicate_token'];
		}
	}
}