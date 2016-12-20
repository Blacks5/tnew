<?php
/**
 * Created by PhpStorm.
 * Date: 16/9/11
 * Time: 10:39
 * @author 涂鸿 <hayto@foxmail.com>
 */

namespace common\models;
//use crazyfd\qiniu\Qiniu;
use Qiniu\Auth;
use yii;
use api\core\CoreApiModel;

/**
 * 文件上传基类
 * Class BaseUploadFile
 * @package app\helper
 * @author 涂鸿 <hayto@foxmail.com>
 */
class BaseUploadFile extends CoreApiModel
{
    private $ak = 'AqCtkCB9tHRtjb6lc5Fvx6Yqtnqe7dhM4oNwc5_a';
    private $sk = '8B-wvW43Rmdq7yQGAQnbzFd03lJzxYYYbkkglW9k';
    private $domain = 'obuoq9z60.bkt.clouddn.com';
    private $bucket = 'wcb89';

    public $handle=null;
    public function __construct()
    {
        if($this->handle === null){
//            $this->handle = new Qiniu($this->ak, $this->sk, $this->domain, $this->bucket);
            $this->handle = new Auth($this->ak, $this->sk);
        }
    }

    /**
     * 生成token给客户端用
     * @return string
     * @author 涂鸿 <hayto@foxmail.com>
     */
    protected function genTokenBase()
    {
        $key = Yii::$app->getSecurity()->generateRandomString();
        $key = 'too123123123dewrdqdqswde1qdaswde1';
        $policy = [
            'saveKey'=>$key
        ];
//        return $this->handle->uploadToken($this->bucket);
        return $this->handle->uploadToken($this->bucket, $key, 3600);
    }


}