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
use Qiniu\Storage\UploadManager;
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
    // 我的
//    private $ak = 'AqCtkCB9tHRtjb6lc5Fvx6Yqtnqe7dhM4oNwc5_a';
//    private $sk = '8B-wvW43Rmdq7yQGAQnbzFd03lJzxYYYbkkglW9k';
//    private $domain = 'http://obuoq9z60.bkt.clouddn.com/';
//    private $bucket = 'wcb89';



    // 天牛的
    private $bucket = 'tianniu-backend-and-androidapp';
    private $ak = 'xgjgQp0pBOODXs8Bweh-c018n40OeC8c06vYoU_Y';
    private $sk = 'jzHiGI3gI83ryCVxaAL2iNrY5qHrmDRPT9MoZY7V';
    private $domain = 'http://omjv2xrxm.bkt.clouddn.com/';

    public $handle=null;
    public function __construct()
    {
        if($this->handle === null){
//            $this->handle = new Qiniu($this->ak, $this->sk, $this->domain, $this->bucket);
            $this->handle = new Auth($this->ak, $this->sk);
        }
    }

    /**
     * 生成token给app用
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
        return $this->handle->uploadToken($this->bucket, null, 3600, $policy);
    }


    /**
     * 返回图片外链
     * @param $key
     * @return null|string
     * @author 涂鸿 <hayto@foxmail.com>
     */
    protected function getUrlBase($key)
    {
        // 1,原始url加工两个参数e和token
        // @todo 图片瘦身用法，要收费
//        $url = $this->handle->privateDownloadUrl($this->domain. $key. "?imageslim"); //
        $url = $this->handle->privateDownloadUrl($this->domain. $key);
        return $url;
        // 2，计算token
//        return $this->domain. $key;
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

    /**
     * web端用
     * 上传图片到七牛云
     * @param $file
     * @return array
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function uploadFile($key, $file)
    {
        $uper = new UploadManager();
        $token = $this->genTokenBase();
        return $uper->putFile($token, $key, $file->tempName);
    }
}