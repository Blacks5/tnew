<?php
/**
 * Created by PhpStorm.
 * Date: 16/9/11
 * Time: 20:32
 * @author 涂鸿 <hayto@foxmail.com>
 */

namespace app\models;
use yii;
use app\core\base\BaseModel;
class TeamSearch extends BaseModel
{
    public $t_name;
    public function search($param)
    {
        $this->load($param);
        $query = Team::find();
        if(!$this->validate()){
            return [];
        }
        return $query;
    }
}