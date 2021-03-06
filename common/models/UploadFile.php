<?php
/**
 * Created by PhpStorm.
 * Date: 16/9/3
 * Time: 19:47
 * @author 涂鸿 <hayto@foxmail.com>
 */

namespace common\models;

use crazyfd\qiniu\Qiniu;
use yii;
class UploadFile extends BaseUploadFile
{
    public $oi_front_id;
    public $oi_back_id;
    public $oi_customer;
    public $oi_front_bank;
//    public $oi_back_bank;

    // 以下几张是可选图片
    public $oi_family_card_one;
    public $oi_family_card_two;
    public $oi_driving_license_one;
    public $oi_driving_license_two;
    public $oi_after_contract;
    public $oi_pick_goods; // 提货照片
    public $oi_serial_num; // 串码照片
    public $oi_video; // 视频

//    public $pic;
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
        $scen['upload'] = ['key', 'type', 'oid'];
        $scen['delete'] = ['type', 'oid', 'key'];
        return $scen;
    }

    public function rules()
    {
        return [
//            [['oi_video'], 'safe'],
//            [['pic'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png,jpg,jpeg,mp4,3gp', 'maxSize'=>1024*1024*1000, 'tooBig'=>'图片过大', 'wrongExtension'=>'图片格式错误', 'checkExtensionByMimeType'=>false], // 最大10m
            [['type'], 'in', 'range'=>['oi_front_id', 'oi_back_id', 'oi_customer', 'oi_front_bank', /*'oi_back_bank',*/ 'oi_driving_license_two', 'oi_driving_license_one', 'oi_family_card_two', 'oi_family_card_one', 'oi_video', 'oi_after_contract', 'oi_pick_goods', 'oi_serial_num']],
            [['oid', 'key'], 'required']
        ];
    }

    /**
     * 上传图片
     * @return string
     * @throws yii\web\HttpException
     * @author 涂鸿 <hayto@foxmail.com>
     */
    /*public function upload()
    {
        $key = Yii::$app->getSecurity()->generateRandomString();
        $ret = $this->uploadFile($key, $this->pic->tempName);
        p($ret);
        return $key;
    }*/



    /**
     * 生成token给客户端用
     * @return mixed|string
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function genToken()
    {
        return $this->genTokenBase();
    }

    /**
     * 返回图片原图外链 or null
     * @param $key
     * @return null|string
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function getUrl($key)
    {
        $url = null;
        if(!empty($key)){
            $url = $this->getUrlBase($key);
        }
        return $url;
    }

    /**
     * 删除图片
     * @param $key
     * @return bool
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function delFile($key)
    {
        $url = false;
        if(!empty($key)){
            $url = $this->delFileBase($key);
        }
        return $url;
    }
}