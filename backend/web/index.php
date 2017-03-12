<?php
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require(__DIR__ . '/../../vendor/autoload.php');
require(__DIR__ . '/../../vendor/yiisoft/yii2/Yii.php');
require(__DIR__ . '/../../common/config/bootstrap.php');
require(__DIR__ . '/../config/bootstrap.php');

$config = yii\helpers\ArrayHelper::merge(
    require(__DIR__ . '/../../common/config/main.php'),
    require(__DIR__ . '/../../common/config/main-local.php'),
    require(__DIR__ . '/../config/main.php'),
    require(__DIR__ . '/../config/main-local.php')
);
function p()
{
    echo "<pre>";
    foreach (func_get_args() as $v){
        var_dump($v);
        echo "<hr>";
    }
    return;
}
$application = new yii\web\Application($config);
$application->run();


/**
 * 结合前端的状态码  layer的 icon:n
 * 0 黄色感叹号
 * 1 绿色勾勾
 * 2 红色叉叉
 * 3 黄色问号
 * 4 灰色锁
 * 5 红色哭脸
 * 6 绿色微笑脸
 */