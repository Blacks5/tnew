<?php

namespace backend\components;


use common\models\User;
use Yii;
use yii\rbac\Rule;

/**
 * Class ArticleRule
 * @package backend\components
 * @author 涂鸿 <hayto@foxmail.com>
 */
class UserRule extends Rule
{
    public $name = 'user';
    public function execute($user, $item, $params)
    {
        // 这里先设置为false,逻辑上后面再完善
        // 只有总经办角色和员工本人，可以修改自己的信息
        /*/mnt/wcb_latest/backend/web/index.php:19:int 11 $user 操作者的用户id

/mnt/wcb_latest/backend/web/index.php:19: item
object(yii\rbac\Permission)[105]
  public 'type' => string '2' (length=1)
  public 'name' => string '编辑员工' (length=12)
  public 'description' => null
  public 'ruleName' => string '编辑员工' (length=12)
  public 'data' => null
  public 'createdAt' => string '1477832995' (length=10)
  public 'updatedAt' => string '1478357974' (length=10)

/mnt/wcb_latest/backend/web/index.php:19:
array (size=1)
  'id' => string '12' (length=2) // 参数 User::find()->where(['id'=>$user, ''])->exists()
*/
//        p($user, $params);



        // wlb 能修改所有人的
        // 本人能修改自己的
        // 王妃能修改除了wlb和cuichaowang之外的所有的

        // 王妃（人事）
        if($user == 56){
            if(in_array($params['id'], [40, 11])){ // 40王翠超 11王翠波
                return false;
            }
        }
        if(($user == 11) || ($user == $params['id'])){
            return true;
        }
        return false;
    }
}