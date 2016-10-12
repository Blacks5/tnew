<?php
/**
 * Created by PhpStorm.
 * Date: 16/9/3
 * Time: 19:47
 * @author 涂鸿 <hayto@foxmail.com>
 */

namespace common\helper;

use crazyfd\qiniu\Qiniu;
use yii;

class UploadFile extends BaseUploadFile
{
    public $oi_front_id;
    public $oi_back_id;
    public $oi_customer;
    public $oi_front_bank;
    public $oi_back_bank;

    public $pic;
    public $type;
    public $oid;
    public $key;

    /*
     * `oi_front_id` varchar(100) NOT NULL COMMENT '身份证正面',
  `oi_back_id` varchar(100) NOT NULL COMMENT '身份证背面',
  `oi_customer` varchar(100) NOT NULL COMMENT '客户现场照',
  `oi_front_bank` varchar(100) NOT NULL COMMENT '银行卡正面',
  `oi_back_bank` varchar(100) NOT NULL COMMENT '银行卡背面',

    // 下面四张可选
  `oi_family_card_one` varchar(100) NOT NULL DEFAULT '' COMMENT '户口本1',
  `oi_family_card_two` varchar(100) NOT NULL DEFAULT '' COMMENT '户口本2',
  `oi_driving_license_one` varchar(100) NOT NULL DEFAULT '' COMMENT '驾照1',
  `oi_driving_license_two` varchar(100) NOT NULL DEFAULT '' COMMENT '驾照2',
     * */


    public function scenarios()
    {
        $scen = parent::scenarios();
        $scen['upload'] = ['pic', 'type', 'oid'];
        $scen['delete'] = ['type', 'oid', 'key'];
        return $scen;
    }

    public function rules()
    {
        return [
            [['pic'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png,jpg,jpeg', 'maxSize' => 1024 * 1024 * 10, 'tooBig' => '图片过大', 'wrongExtension' => '图片格式错误'], // 最大10m
            [['type'], 'in', 'range' => ['oi_front_id', 'oi_back_id', 'oi_customer', 'oi_front_bank', 'oi_back_bank']],
            [['oid', 'key'], 'required']
        ];
    }

    /**
     * 上传图片
     * @return string
     * @throws yii\web\HttpException
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function upload()
    {
        $key = Yii::$app->getSecurity()->generateRandomString();
        $this->handle->uploadFile($this->pic->tempName, $key);
        return $key;
    }

    /**
     * 获取图片外链
     * @param $key
     * @return string
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function getPicUrl($key)
    {
        return $this->handle->getLink($key);
    }

    public function delePic($key)
    {
        return $this->handle->delete($key);
    }
}