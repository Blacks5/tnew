<?php

/**
 * Description Content
 * @Author: MuMu
 * @Date:   2017-11-22 10:52:39
 * @Last Modified by:   MuMu
 * @Last Modified time: 2018-01-05 15:40:16
 */
namespace common\services;

use common\components\CustomCommonException;
use EasyWeChat\Foundation\Application;
use Exception;
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
			try {
				$httpClient = new Client();

				$response = $httpClient->post($url, $params, [
					'X-TOKEN' => $token,
				])->send();

				return $response->data;
			} catch (Exception $e) {
				throw new CustomCommonException('Remote Server Exception.');
			}
		} else {
			throw new CustomCommonException('Service Token Failed To Get.');
		}
	}

	/**
	 * 发起HTTP POST请求
	 * @param  string $url    请求地址
	 * @param  array  $params 请求参数
	 * @return array
	 */
	protected function httpUpload($url, $file, $tokenFrom = 'user') {
		if ($token = $this->getToken($tokenFrom)) {
			try {
				$httpClient = new Client();

				$response = $httpClient->createRequest()
					->setMethod('post')
					->setUrl($url)
					->addHeaders(['X-TOKEN' => $token])
					->addFile('image', $file)
					->addOptions(['timeout' => 10000])
					->send();

				return $response->data;
			} catch (Exception $e) {
				throw new CustomCommonException('Remote Server Exception.');
			}
		} else {
			throw new CustomCommonException('Service Token Failed To Get.');
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
	 * 发起HTTP DELETE请求
	 * @param  string $url    请求地址
	 * @param  array  $params 请求参数
	 * @return array
	 */
	protected function httpDelete($url, $params = [], $tokenFrom = 'user') {
		if ($token = $this->getToken($tokenFrom)) {
			try {
				$httpClient = new Client();

				$response = $httpClient->delete($url, $params, [
					'X-TOKEN' => $token,
				])->send();

				return $response->data;
			} catch (Exception $e) {
				throw new CustomCommonException('Remote Server Exception.');
			}
		} else {
			throw new CustomCommonException('Service Token Failed To Get.');
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

		if (isset(Yii::$app->params['server_running_env']) && Yii::$app->params['server_running_env'] == 'master') {
			$serviceUrl = $this->microServiceUrl;
		} else {
			$serviceUrl = $this->devMicroServiceUrl;
		}

		return rtrim($serviceUrl, '/') . '/' . ltrim($router, '/');
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
					// 当前环境
					$env = isset(Yii::$app->params['server_running_env']) ? Yii::$app->params['server_running_env'] : 'develop';

					$tokenKey = 'env:' . $env . ':user:' . $userId . ':accesstoken';

					// 获取token
					if ($token = Yii::$app->cache->get($tokenKey)) {
						return $token;
					}

					if (isset(Yii::$app->params['server_running_env']) && Yii::$app->params['server_running_env'] == 'master') {
						$url = 'http://users.api.tnew.cn/v1/users/' . $sysUser->username . '/tokens';
					} else {
						$url = 'http://users.devapi.tnew.cn/v1/users/' . $sysUser->username . '/tokens';
					}

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

	/**
	 * 拉取文件到本地
	 * @param  string $mediaid  微信临时素材
	 * @param  string $filename 文件名
	 * @return string           文件路径
	 */
	protected function pullMediaToLocal($mediaid, $filename = null) {
		$config = Yii::$app->params['wechat'];
		$app = new Application($config);

		// 临时素材
		$temporary = $app->material_temporary;

		// 获取素材内容
		if ($content = $temporary->getStream($mediaid)) {
			$filename = $filename ? $filename : uniqid() . '.jpg';

			$basePath = '../runtime/temp';

			is_dir($basePath) || @mkdir($basePath, 0777, true);

			$filepath = $basePath . '/' . $filename;

			$status = file_put_contents($filepath, $content);

			if ($status) {
				return $filepath;
			}
		}

		return null;
	}
}