<?php

/**
 * 文件服务
 * @Author: MuMu
 * @Date:   2017-11-23 16:43:28
 * @Last Modified by:   MuMu
 * @Last Modified time: 2017-11-23 16:47:01
 */

namespace common\services;

use common\components\CustomCommonException;

class Files extends Service {
	// 微服务记录地址
	protected $microServiceUrl = 'http://file.devapi.tnew.cn/v1/';
	// 上传照片
	private $uploadRouter = '/files';

	/**
	 * 上传照片
	 * @param  string $mediaId 媒体资源ID
	 * @return array           响应结果
	 */
	public function upload($mediaId) {
		$url = $this->buildUrl($this->uploadRouter);

		$res = $this->httpPost($url, [
			'image' => $mediaId,
		]);

		if ($res['success']) {
			return $res['data'];
		} else {
			throw new CustomCommonException($res['errors'][0]['message']);
		}
	}
}
