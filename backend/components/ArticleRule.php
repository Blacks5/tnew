<?php

namespace backend\components;


use Yii;
use yii\rbac\Rule;

/**
 * Class ArticleRule
 * @package backend\components
 * @author 涂鸿 <hayto@foxmail.com>
 */
class ArticleRule extends Rule
{
    public $name = 'article';
    public function execute($user, $item, $params)
    {
        // 这里先设置为false,逻辑上后面再完善
        return false;
    }
}