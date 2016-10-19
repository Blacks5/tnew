<?php
/**
 * Created by PhpStorm.
 * Date: 16/9/11
 * Time: 10:39
 * @author 涂鸿 <hayto@foxmail.com>
 */

namespace common\helper;
use crazyfd\qiniu\Qiniu;
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
            $this->handle = new Qiniu($this->ak, $this->sk, $this->domain, $this->bucket);
        }
    }
}