<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "menu".
 *
 * @property integer $id
 * @property string $name
 * @property integer $parent
 * @property string $route
 * @property integer $order
 * @property string $data
 */
class Menu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['parent', 'sort'], 'integer'],
            [['data'], 'string'],
            [['status'], 'string'],
            [['name'], 'string', 'max' => 128],
            [['route'], 'string', 'max' => 256],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '名称',
            'parent' => '父级',
            'route' => '路由',
            'sort' => '排序',
            'data' => '描述',
        ];
    }

    //获取顶级菜单列表
    public function  getAllMenu(){
        $menu = Yii::$app->db->createCommand("SELECT * FROM `menu` WHERE parent='0' ")->queryAll();
        return $menu;
    }
    //获取所有菜单列表
    public function  getMenuList(){
        $menu = Yii::$app->db->createCommand("SELECT * FROM `menu` ORDER BY sort ASC ")->queryAll();
        $menu = self::list_to_tree($menu,'id','parent');
        return $menu;
    }
    //获取左侧菜单列表
    public function  getLeftMenuList(){
        $uid = Yii::$app->user->identity->getId();

        // 特殊用户直接读出所有菜单
        if (in_array($uid, Yii::$app->params['SuperDiao'])) {
            $sql = "SELECT * FROM `menu` ORDER BY `order` ASC";
            $menu = Yii::$app->db->createCommand($sql)->queryAll();
            $menu = self::list_to_tree2($menu, 'id', 'parent');
            return $menu;
        }



        $auth = Yii::$app->getAuthManager();
        $Roles = $auth->getRolesByUser($uid);
        $Permission = [];
        foreach($Roles as $vo){
            $Permission += $auth->getPermissionsByRole($vo->name);
        }

        // 增加获取直接赋予的权限，之前直接赋予的权限读不到，因为都是从角色里读取的，没有赋予角色就什么都读不到
        // 2017-01-07 涂鸿修改
        $p1 = $auth->getPermissionsByUser($uid);
        $Permission = array_merge($Permission, $p1);
        // 没有任何权限就返回空数组
        $menu = [];
        if($Permission) {
            $RolesList = '';
            foreach ($Permission as $vo) {
                $RolesList .= "'" . $vo->name . "',";
            }
            $RolesList = substr($RolesList, 0, -1);
            $sql = "SELECT * FROM `menu` WHERE route IN ($RolesList)  ORDER BY `order` ASC";

            $menu = Yii::$app->db->createCommand($sql)->queryAll();
            $menu = self::list_to_tree2($menu, 'id', 'parent');
        }
        return $menu;
    }

    //通过id找到router
    public function getRouteById($id){
        $router = Yii::$app->db->createCommand("SELECT * FROM `menu` WHERE id='$id'")->queryOne();
        return $router['route'];
    }

    /**
     * 生成tree
     * @param $list
     * @param string $pk
     * @param string $pid
     * @param string $child
     * @param int $root
     * @return array
     * @author 涂鸿 <hayto@foxmail.com>
     */
    function list_to_tree($list, $pk='id', $pid = 'pid', $child = '_child', $root = 0) {
        // 创建Tree
        $tree = array();
        if(is_array($list)) {
            // 创建基于主键的数组引用
            $refer = array();
            foreach ($list as $key => $data) {
                $refer[$data[$pk]] =& $list[$key];
            }
            foreach ($list as $key => $data) {
                // 判断是否存在parent
                $parentId =  $data[$pid];
                if ($root == $parentId) {
                    $tree[] =& $list[$key];
                }else{
                    if (isset($refer[$parentId])) {
                        $parent =& $refer[$parentId];
                        $list[$key]['name'] ='&nbsp;&nbsp;&nbsp;&nbsp;|--'.$list[$key]['name'];
                        $parent[$child][] =& $list[$key];
                    }
                }
            }
        }
        return $tree;
    }

    /**
     * 生成tree
     * @param $list
     * @param string $pk
     * @param string $pid
     * @param string $child
     * @param int $root
     * @return array
     * @author 涂鸿 <hayto@foxmail.com>
     */
    function list_to_tree2($list, $pk='id', $pid = 'pid', $child = '_child', $root = 0) {
        // 创建Tree
        $tree = array();
        if(is_array($list)) {
            // 创建基于主键的数组引用
            $refer = array();
            foreach ($list as $key => $data) {
                $refer[$data[$pk]] =& $list[$key];
            }
            foreach ($list as $key => $data) {
                // 判断是否存在parent
                $parentId =  $data[$pid];
                if ($root == $parentId) {
                    $tree[] =& $list[$key];
                }else{
                    if (isset($refer[$parentId])) {
                        $parent =& $refer[$parentId];
                        $list[$key]['name'] =$list[$key]['name'];
                        $parent[$child][] =& $list[$key];
                    }
                }
            }
        }
        return $tree;
    }


}
