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
use Qiniu\Storage\BucketManager;
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
    private $domain = 'obuoq9z60.bkt.clouddn.com/';
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
//        $key = Yii::$app->getSecurity()->generateRandomString();
//        $key = 'too123123123dewrdqdqswde1qdaswde1';
        $policy = [
//            'saveKey'=>$key, // 客户端没有主动指定key时才有用
            'insertOnly'=>1 // 只能新增
        ];
        return $this->handle->uploadToken($this->bucket, null, 10, $policy);
    }


    /**
     * 返回图片外链
     * @param $key
     * @return null|string
     * @author 涂鸿 <hayto@foxmail.com>
     */
    protected function getUrlBase($key)
    {
        return $this->domain. $key;
    }

    /**
     * 删除成功返回true， or false
     * @param $key
     * @return bool
     * @author 涂鸿 <hayto@foxmail.com>
     */
    protected function delFileBase($key)
    {
        $a = new BucketManager($this->handle);
        return ($a->delete($this->bucket, $key) === null) ? true: false;
    }
}