<?php
/**
 * Created by PhpStorm.
 * Date: 16/8/20
 * Time: 15:55
 * @author 涂鸿 <hayto@foxmail.com>
 */

namespace backend\controllers;

use backend\components\CustomBackendException;
use common\components\Helper;
use common\models\Customer;
use common\models\CustomerSearch;
use common\models\OrderImages;
use common\models\OrdersSearch;
use common\models\UploadFile;
use common\models\User;
use yii;
use backend\core\CoreBackendController;

/**
 * 用户管理
 * Class CustomerController
 * @package app\controllers
 * @author 涂鸿 <hayto@foxmail.com>
 */
class CustomerController extends CoreBackendController
{
    public function actionIndexp()
    {
        echo '客户管理';
    }
    /**
     * 用户列表
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionIndex()
    {
        $this->getView()->title = '客户列表';
        $model = new CustomerSearch();
        $params = Yii::$app->getRequest()->getQueryParams();
        //新增客户筛选条件
        $query = $model->search($params)/*->andWhere(['<','o_created_at',strtotime(Yii::$app->params['customernew_date'])])*/;

        // 如果查看某个销售的客户，就执行
        $user = null;
        if(!empty($params["CustomerSearch"]['u_id'])){
            $user['realname'] = (new yii\db\Query())->select(['realname'])->from(User::tableName())->where(['id'=>$params["CustomerSearch"]['u_id']])->scalar();
            $user['u_id'] = $params["CustomerSearch"]['u_id'];
        }

        $querycount = clone $query;
        $pages = new yii\data\Pagination(['totalCount' => $querycount->count()]);
        $pages->pageSize = Yii::$app->params['page_size'];
        $data = $query->orderBy(['c_created_at' => SORT_DESC])->offset($pages->offset)->limit($pages->limit)->asArray()->all();
        array_walk($data, function(&$v){
            $v['c_status'] = Customer::getAllStatus()[$v['c_status']];
            $v['c_created_at'] = date('Y-m-d H:i:s', $v['c_created_at']);
            $v['c_updated_at'] = date('Y-m-d H:i:s', $v['c_updated_at']);
        });
//        var_dump($data);die;
        $provinces = Helper::getAllProvince();
        return $this->render('index', [
            'sear' => $model->getAttributes(),
            'model' => $data,
            'totalpage' => $pages->pageCount,
            'pages'=>$pages,
            'provinces'=>$provinces,
            'user'=>$user
        ]);
    }

    public function actionView($c_id)
    {
        if($data = Customer::getOneDetail($c_id)){
            $this->getView()->title = $data['c_customer_name'];
//            $data['c_status'] = Customer::getAllStatus()[$data['c_status']];
            return $this->render('view', ['model'=>$data]);
        }
    }

    /**
     * 获取某个客户的所有订单
     * @return string
     * @author too <hayto@foxmail.com>
     */
    public function actionGetAllOrdersByCustomer()
    {
        $this->getView()->title = '所有订单';
        $model = new OrdersSearch();
        $params = Yii::$app->getRequest()->getQueryParams();
        $query = $model->search($params);

        // 如果查看某个客户的所有订单，就执行
        $customer = null;
        if(!empty($params["OrdersSearch"]['customer_id'])){
            $customer['c_customer_name'] = (new yii\db\Query())->select(['c_customer_name'])->from(Customer::tableName())->where(['c_id'=>$params["OrdersSearch"]['customer_id']])->scalar();
            $customer['customer_id'] = $params["OrdersSearch"]['customer_id'];
        }

        $querycount = clone $query;
        $pages = new yii\data\Pagination(['totalCount' => $querycount->count()]);
        $pages->pageSize = Yii::$app->params['page_size'];
        $data = $query->orderBy(['orders.o_created_at' => SORT_DESC])->offset($pages->offset)->limit($pages->limit)->asArray()->all();
        return $this->render('allordersbycustomer', [
            'sear' => $model->getAttributes(),
            'model' => $data,
            'totalpage' => $pages->pageCount,
            'pages' => $pages,
            'customer'=>$customer
        ]);
    }

    /*array(5) {
  ["customer_id"]=>
  string(3) "226"
  ["c_bank"]=>
  string(1) "1"
  ["c_banknum"]=>
  string(1) "2"
  ["oi_front_bank"]=>
  string(42) "BRTs077tHnM0zr5oi-i_kijz9nyELpqn1494485287"
  ["_csrf-backend"]=>
  string(56) "WjdxbW9hZ3gMbThfH1MKDxtaGFgtECMuKHtGGjonLBIoWkE3KjkEMQ=="
}
*/
    /**
     * 修改客户还款银行卡相关信息
     * @return array
     * @author too <hayto@foxmail.com>
     */
    public function actionChangeBankInfo()
    {
        if(Yii::$app->getRequest()->getIsAjax()){
            Yii::$app->getResponse()->format = yii\web\Response::FORMAT_JSON;

            $trans = Yii::$app->getDb()->beginTransaction();
            try{
                $data = Yii::$app->getRequest()->post();
                if(false === !empty($data['o_images_id'])){
                    throw new CustomBackendException('参数异常');
                }
                if(false === !empty($data['customer_id'])){
                    throw new CustomBackendException('参数异常');
                }
                if(false === !empty($data['c_bank'])){
                    throw new CustomBackendException('参数异常');
                }
                if(false === !empty($data['c_banknum'])){
                    throw new CustomBackendException('参数异常');
                }
                if(false === !empty($data['oi_front_bank'])){
                    throw new CustomBackendException('参数异常');
                }

                $sql = "select * from ". Customer::tableName(). " where c_id=:c_id limit 1 for update";
                $Customer = Customer::findBySql($sql, [':c_id'=>$data['customer_id']])->one();
                if(false === !empty($Customer)){
                    throw new CustomBackendException('客户不存在');
                }
                $Customer->c_bank = $data['c_bank'];
                $Customer->c_banknum = $data['c_banknum'];
                if(false === $Customer->update()){
                    throw new CustomBackendException('更新客户失败');
                }
                $sql = "select * from ". OrderImages::tableName(). " where oi_id=:oi_id limit 1 for update";
                $OrderImages = OrderImages::findBySql($sql, [':oi_id'=>$data['o_images_id']])->one();
                if(false === !empty($OrderImages)){
                    throw new CustomBackendException('图片不存在');
                }
                $OrderImages->oi_front_bank = $data['oi_front_bank'];
                if(false === $OrderImages->update()){
                    throw new CustomBackendException('更新银行卡图片失败');
                }
                $trans->commit();
                return ['status'=>1, 'message'=>'更新银行卡信息成功'];
            }catch (CustomBackendException $e){
                $trans->rollBack();
                return ['status'=>0, 'message'=>$e->getMessage()];
            }catch (\Exception $e){
                $trans->rollBack();
                return ['status'=>0, 'message'=>'网络错误'];
            }
        }
    }

    /**
     * 上传银行卡图片
     * @return bool|string
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionUpload()
    {
        $pic = yii\web\UploadedFile::getInstanceByName('file'); // 获取图片
        $key = Yii::$app->getSecurity()->generateRandomString();
        $key .= $_SERVER['REQUEST_TIME'];
        $handle = new UploadFile();
        $ret = $handle->uploadFile($key, $pic);
        $key = false;
        if(end($ret) === null){
            $key = reset($ret)['key'];
        }
        return $key;
    }
}