<?php

/**
 * 用户服务
 * @Author: MuMu
 * @Date:   2017-11-22 10:48:34
 * @Last Modified by:   MuMu
 * @Last Modified time: 2017-11-22 14:42:53
 */

namespace common\services;

use common\components\CustomCommonException;

class User extends Service {
	// 微服务记录地址
	protected $microServiceUrl = 'http://users.devapi.tnew.cn/v1/';
	// 获取用户TOKEN路由
	private $queryUserTokenRouter = '/users/{login_name}/tokens';

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



	Yii::$app->cache->
	$cache->set('cache_data_key', $cacheData, 60*60); 

}
