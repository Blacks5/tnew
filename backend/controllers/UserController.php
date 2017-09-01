<?php

namespace backend\controllers;

use backend\core\CoreBackendController;
use backend\models\AuthAssignment;
use backend\models\AuthItem;
use backend\models\Menu;
use backend\models\User as Users;
use backend\models\PasswordForm;
use common\components\Helper;
use common\models\Department;
use common\models\Jobs;
use common\models\TooRegion;
use common\models\UploadFile;
use common\models\User;
use yii\data\Pagination;
//use backend\models\User;
use common\models\UserSearch;

use Yii;
use yii\db\Query;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\web\Response;

use backend\components\CustomBackendException;
use common\models\FreezeuserLog;
/**
 * 员工控制器
 * Class UserController
 * @package backend\controllers
 * @author 涂鸿 <hayto@foxmail.com>
 */
class UserController extends CoreBackendController
{
    //public $enableCsrfValidation = false;

    /*public function beforeAction($action)
    {
        $action = Yii::$app->controller->module->requestedRoute;
        if(\Yii::$app->user->can($action)){
            return true;
        }else{
            echo '<div style="margin: 100px auto;text-align: center;background-color: #1ab394; color: #ffffff;width: 500px;height: 50px;line-height: 50px;border-radius: 5px;;"><h4>对不起，您现在还没获此操作的权限</h4></div>';
        }
    }*/

    public function actionIndex()
    {
        echo '员工管理';
    }

    public function actionView($id)
    {
//        return $this->success("添加成功！");
        $model = User::find()->alias('a')
            ->select('a.*, aa.item_name, d.d_name, j_name')
            ->leftJoin(AuthAssignment::tableName() . ' as aa', 'user_id=id')
            ->leftJoin(Jobs::tableName(), 'job_id=j_id')
            ->leftJoin(Department::tableName() . ' as d', 'department_id=d_id')
            ->where(['id' => $id])
            ->asArray()->one();

        $t = new UploadFile();
//        var_dump($model);die;
        $model['id_card_pic_one'] = $model['id_card_pic_one'] ? $t->getUrl($model['id_card_pic_one']) : '';

        $model['province'] = Helper::getAddrName($model['province']);
        $model['city'] = Helper::getAddrName($model['city']);
        $model['county'] = Helper::getAddrName($model['county']);
        return $this->render('view', ['model' => $model]);
    }

    /**
     * 获取子地区
     * @param $p_id
     * @return array
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionGetSubAddr($p_id)
    {
        Yii::$app->getResponse()->format = Response::FORMAT_JSON;
        return Helper::getSubAddr($p_id);
    }

    public function actionGetJobs($d_id)
    {
        Yii::$app->getResponse()->format = Response::FORMAT_JSON;
        return Jobs::getJobs($d_id);
    }

    /**
     * 增加销售时获取上级领导
     * @param $cityName
     * @param $cityId
     * @param $leader
     * @return array
     * @author OneStep
     */
    public function actionGetLeader($cityName,$cityId,$leader)
    {
        Yii::$app->getResponse()->format = Response::FORMAT_JSON;

        $User = new Users();
        $parentLeader = $User->jobToleader($leader)-1;

        return Users::getLeader($cityName,$cityId,$parentLeader);

    }


    public function actionList()
    {
        $query = new UserSearch();
        $model = $query->search(Yii::$app->getRequest()->getQueryParams());
        $clone_model = clone $model;
        $pages = new Pagination(['totalCount' => $clone_model->count(), 'pageSize' => '20']);
        $user = $model->orderBy(['status'=>SORT_DESC,'id' => SORT_DESC])->offset($pages->offset)->limit($pages->limit)->asArray()->all();

        $provinces = Helper::getAllProvince();
//        array_unshift($provinces, '省');
        //员工状态
        $user_status = User::getAllStatus();

        //var_dump($user);die;
        return $this->render('list', [
            'sear' => $query->getAttributes(),
            'user' => $user,
            'pages' => $pages,
            'user_status' => $user_status,
            'provinces'=>$provinces
        ]);
    }
    /**
     * 员工列表
     * @return string
     * @author 涂鸿 <hayto@foxmail.com>
     */
    /*public function actionList()
    {
        $query = new UserSearch();
        $model = $query->search(Yii::$app->getRequest()->getQueryParams());
        $clone_model = clone $model;
        $pages = new Pagination(['totalCount' => $clone_model->count(), 'pageSize' => '20']);
        $user = $model->joinWith('usergroup')->orderBy(['id' => SORT_DESC])->offset($pages->offset)->limit($pages->limit)->all();
        foreach($user as $v){
            $v->department_id = Department::find()->select(['d_name'])->where(['d_id'=>$v->department_id])->scalar();
        }
        return $this->render('list', [
            'sear' => $query->getAttributes(),
            'user' => $user,
            'pages' => $pages
        ]);
    }*/

    /**
     * 新增员工
     * @return string|\yii\web\Response
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionCreate()
    {
        Yii::$app->getView()->title = '新增员工';

        $model = new User();
        $Users = new Users();
//        $model1 = new AuthItem();

        $auth = Yii::$app->getAuthManager();
        $item = $auth->getRoles(); // 获取所有角色
        $item_one = array_keys($item);
        $item_one = array_combine($item_one, $item_one);
        $request = Yii::$app->getRequest();
        if ($request->getIsPost()) {
            $post = $request->post();
            $post['User']['level'] = $Users->jobToleader($post['User']['job_id']);

            try{
                if ($model->createUser($post)) {
                    // 分配部门角色
                    /*$role = $auth->createRole($post['AuthItem']['name']);
                    $auth->assign($role, $model->id);*/
                    return $this->success('添加成功！', Url::toRoute(['user/list']));
//                return $this->redirect(['list']);
                }
            }catch (CustomBackendException $e){
                return $this->error($e->getMessage());
            }

        }
//        var_dump($model->attributes);die;
        $all_province = Helper::getAllProvince();
        $all_departments = Department::getAllDepartments();

        //员工状态
        $user_status = User::getAllStatus();
        return $this->render('create', [
            'model' => $model,
//            'model1' => $model1,
            'item' => $item_one,
            'user_status' => $user_status,
            'all_province' => $all_province,
            'all_departments' => $all_departments
        ]);
    }

    /**
     * 更新员工
     * @return string|\yii\web\Response
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionUpdate()
    {
//        $item_name = Yii::$app->request->get('item_name');
        $id = Yii::$app->request->get('id');
        $model = User::find()->joinWith('usergroup')->where(['id' => $id])->one();
        $auth = Yii::$app->authManager;
        $item = $auth->getRoles();
        $itemArr = array();
        foreach ($item as $v) {
            $itemArr[] .= $v->name;
        }
        foreach ($itemArr as $key => $value) {
            $item_one[$value] = $value;
        }
//        $model1 = $this->findModel($id);
        $model->scenario = 'update';
        if ($model->load(Yii::$app->request->post())) {
//            $post = Yii::$app->request->post();
            //更新密码
            /*if(!empty($post['User']['auth_key_new'])){
                $model1->setPassword($post['User']['auth_key_new']);
                $model1->generateAuthKey();
            }else{
                $model1->auth_key = $post['User']['auth_key'];
            }*/
            if ($model->validate()) {
                $Users = new Users();
                $model['level'] = $Users->jobToleader($model['job_id']);
                //var_dump($model['leader']);die;
                $model->save();
                //分配角色
                /*$role = $auth->createRole($post['AuthAssignment']['item_name']);                //创建角色对象
                $user_id = $id;                                             //获取用户id，此处假设用户id=1
                $auth->revokeAll($user_id);
                $auth->assign($role, $user_id); */                          //添加对应关系
                return $this->success('修改成功');
            }else{
                $msg = $model->getFirstErrors();
                $msg = "<br>". implode(';', $msg);
                return $this->error('修改失败'. $msg);
            }
        }
        //员工状态
        $user_status = User::getAllStatus();
        $all_province = Helper::getAllProvince();
        $all_departments = Department::getAllDepartments();
        $all_leader = [6=>'不需要上级'];

        if($model->department_id==26){
            if ($model->leader!=6){
                $leader = User::find()->select(['realname'])->indexBy('id')->where(['level'=>$model->level-1]);
                if($model->level<4){
                    $leader->andWhere(['county'=>$model->county]);
                }elseif($model->level==4){
                    $leader->andWhere(['city'=>$model->city]);
                }elseif ($model->level==3){
                    $leader->andWhere(['province'=>$model->province]);
                }
                $all_leader = $leader->column();
            }
        }


        //var_dump($leader->column());die;
        //$all_leader = User::find()->select(['id','realname'])->where(['leader'=>$leader->leader])->andWhere(['level'=>$model]);

        //var_dump($all_leader);die;

        $all_citys = Helper::getSubAddr($model->province);

        $all_countys = Helper::getSubAddr($model->city);
        $all_jobs = Jobs::find()->select('j_name')->indexBy('j_id')->where(['j_department_id' => $model->department_id])->column();
        return $this->render('update', [
            'model' => $model,
            'item' => $item_one,
            'leader'=>$all_leader,
            'user_status' => $user_status,
            'all_province' => $all_province,
            'all_citys' => $all_citys,
            'all_countys' => $all_countys,
            'all_departments' => $all_departments,
            'all_jobs' => $all_jobs
        ]);
    }

    /**
     * 删除员工
     * @param $id
     * @return \yii\web\Response
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->status = User::STATUS_DELETE;
        $model->save(false);
        return $this->success('删除成功！', Url::toRoute(['user/list']));
    }

    /**
     * 修改员工密码
     * @param $id
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionModPwd($id)
    {
        $request = Yii::$app->getRequest();
        $model = $this->findModel($id);
        if ($request->getIsPost()) {
            if ($model->modpwd($request->post(), $id)) {
                return $this->success('重置成功', Url::toRoute(['user/list']));
            }
        }

        return $this->render('modpwd', ['model' => $model]);
    }

    /**
     * 修改自己的密码
     * @return string|Response
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionModSelfPwd()
    {
        $request = Yii::$app->getRequest();
        $id = Yii::$app->getUser()->getIdentity()->getId();
        $model = $this->findModel($id);
        if ($request->getIsPost()) {
            if ($model->modselfpwd($request->post(), $id)) {
                return $this->success('修改成功', Url::toRoute(['user/list']));
            }
        }

        return $this->render('modselfpwd', ['model' => $model]);
    }



    /**
     * @param $id
     * @return array
     * @author lilaotou <liwansen@foxmail.com>
     * 激活用户
     */
    public function actionActivateuser()
    {
        $request = Yii::$app->getRequest();
        if ($request->getIsAjax()) {
            try {
                Yii::$app->getResponse()->format = yii\web\Response::FORMAT_JSON;

                $id = $request->post('id');
                $model = User::find()->where(['id' => $id])->one();
                if (!$model) {
                    throw new CustomBackendException('信息不存在！', 4);
                }else{
                    if($model['status'] == 0){
                        throw new CustomBackendException('此员工已被删除无法激活！', 4);
                    }
                }

                //判断用户冻结次数,最多冻结3次
                $freezenum = (new Query())->from(FreezeuserLog::tableName())
                    ->where(['user_id'=>$id])
                    ->count();

                if($freezenum == 3){
                    throw new CustomBackendException('此员工已达冻结最大次数,无法激活', 5);
                }else{
                    $model->status = User::STATUS_ACTIVE;
                    $model->updated_at = $_SERVER['REQUEST_TIME'];
                    if (!$model->save(false)) {
                        throw new CustomBackendException('操作失败', 5);
                    }
                    return ['status' => 1, 'message' => '激活成功!'];
                }
            } catch (CustomBackendException $e) {
                return ['status' => $e->getCode(), 'message' => $e->getMessage()];
            } catch (yii\base\Exception $e) {
                return ['status' => 2, 'message' => '系统错误'];
            }
        }
    }

    /**
     * @param $id
     * @return array
     * @author lilaotou <liwansen@foxmail.com>
     * 冻结用户
     */
    public function actionBlockeduser()
    {
        $request = Yii::$app->getRequest();
        if ($request->getIsAjax()) {
            try {
                Yii::$app->getResponse()->format = yii\web\Response::FORMAT_JSON;

                $id = $request->post('id');
                $freeze_remark = trim($request->post('remark'));
                if(!$freeze_remark){
                    throw new CustomBackendException('请填写冻结原因！', 4);
                }
                $model = User::find()->where(['id' => $id])->one();
                if (!$model) {
                    throw new CustomBackendException('信息不存在！', 4);
                }else{

                    $userinfo = Yii::$app->getUser()->getIdentity();
                    //只有激活的用户可以冻结
                    if($model['status'] == 10){
                        $model->status = User::STATUS_STOP;
                        $model->updated_at = $_SERVER['REQUEST_TIME'];
                        if (!$model->save(false)) {
                            throw new CustomBackendException('操作失败', 5);
                        }else{
                            //新增操作记录
                            $wait_inster_data = [
                                'user_id'=>$id,
                                'operator_id'=>$userinfo->id,
                                'freeze_remark'=>$freeze_remark,
                                'created_at'=>$_SERVER['REQUEST_TIME']
                            ];
                            \Yii::$app->getDb()->createCommand()->insert(FreezeuserLog::tableName(), $wait_inster_data)->execute();
                        }
                        return ['status' => 1, 'message' => '冻结成功!'];
                    }else{
                        throw new CustomBackendException('此员工已离职(或已冻结)！', 4);
                    }
                }
            } catch (CustomBackendException $e) {
                return ['status' => $e->getCode(), 'message' => $e->getMessage()];
            } catch (yii\base\Exception $e) {
                return ['status' => 2, 'message' => '系统错误'];
            }
        }
    }

    /**
     * @param $id
     * @return array
     * @author lilaotou <liwansen@foxmail.com>
     * 关闭用户(离职)
     */
    public function actionLeaveuser()
    {
        $request = Yii::$app->getRequest();
        if ($request->getIsAjax()) {
            try {
                Yii::$app->getResponse()->format = yii\web\Response::FORMAT_JSON;

                $id = $request->post('id');
                $model = User::find()->where(['id' => $id])->one();
                if (!$model) {
                    throw new CustomBackendException('信息不存在！', 4);
                }else{
                    $model->status = User::STATUS_LEAVE;
                    $model->updated_at = $_SERVER['REQUEST_TIME'];
                    if (!$model->save(false)) {
                        throw new CustomBackendException('操作失败', 5);
                    }
                    return ['status' => 1, 'message' => '操作成功!'];
                }
            } catch (CustomBackendException $e) {
                return ['status' => $e->getCode(), 'message' => $e->getMessage()];
            } catch (yii\base\Exception $e) {
                return ['status' => 2, 'message' => '系统错误'];
            }
        }
    }

    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
