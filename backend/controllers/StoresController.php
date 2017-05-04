<?php
/**
 * Created by PhpStorm.
 * Date: 16/8/20
 * Time: 15:49
 * @author 涂鸿 <hayto@foxmail.com>
 */

namespace backend\controllers;

use backend\components\CustomBackendException;
use common\components\Helper;
use common\models\UploadFile;
use common\models\AllOrdersWithStoreSearch;
use common\models\Orders;
use common\models\Stores;
use common\models\StoresSaleman;
use common\models\User;
use yii;
use backend\core\CoreBackendController;

/**
 * 商户管理
 * Class StoresController
 * @package app\controllers
 * @author 涂鸿 <hayto@foxmail.com>
 */
class StoresController extends CoreBackendController
{

    public function actionIndexp()
    {
        echo '商户管理';
    }
    /**
     * 商户列表
     * @return string
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionIndex()
    {
        $this->getView()->title = '商户列表';

        $model = new Stores();
        $query = $model->search(Yii::$app->getRequest()->getQueryParams());
        $querycount = clone $query;
        $pages = new yii\data\Pagination(['totalCount' => $querycount->count()]);
        $pages->pageSize = Yii::$app->params['page_size'];
        $data = $query->orderBy(['s_created_at' => SORT_DESC])->offset($pages->offset)->limit($pages->limit)->asArray()->all();
        $provinces = Helper::getAllProvince();
        return $this->render('index', [
            'sear' => $model->getAttributes(),
            'model' => $data,
            'totalpage' => $pages->pageCount,
            'pages' => $pages,
            'provinces'=>$provinces
        ]);
    }

    /**
     * 添加商户
     * @return mixed|string
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionCreate()
    {
        $this->getView()->title = '添加商户';
        $model = new Stores();
        $request = Yii::$app->getRequest();
        if ($request->getIsPost()) {
            if ($model->createStores($request->post())) {
                return $this->success('添加成功', yii\helpers\Url::toRoute(['stores/index']));
            }

        }
        $model->s_status = Stores::STATUS_ACTIVE;
        // 商户状态
        $stroe_status = Stores::getAllStatus();
        // 是否对私账户
        $is_private_bank = [
            Stores::BANK_PRIVATE => '是',
            Stores::BANK_PRIVATE_NOT => '否'
        ];
        // 所有省
        $provinces = Helper::getAllProvince();
        return $this->render('create', [
            'model' => $model,
            'store_status' => $stroe_status,
            'is_private_bank' => $is_private_bank,
            'provinces' => $provinces
        ]);
    }

    /**
     * 详情
     * @param $id
     * @return $this|string
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionView($id)
    {
        if ($model = Stores::findOne($id)) {
            $t = new UploadFile();
            $model->s_photo_one = $model->s_photo_one ? $t->getUrl($model->s_photo_one) : '';
            $model->s_photo_two = $model->s_photo_two ? $t->getUrl($model->s_photo_two) : '';
            $model->s_photo_three = $model->s_photo_three ? $t->getUrl($model->s_photo_three) : '';
            $model->s_photo_four = $model->s_photo_four ? $t->getUrl($model->s_photo_four) : '';
            $model->s_photo_five = $model->s_photo_five ? $t->getUrl($model->s_photo_five) : '';
            $model->s_photo_six = $model->s_photo_six ? $t->getUrl($model->s_photo_six) : '';
            $model->s_photo_seven = $model->s_photo_seven ? $t->getUrl($model->s_photo_seven) : '';
            $model->s_photo_eight = $model->s_photo_eight ? $t->getUrl($model->s_photo_eight) : '';
            $all_sales = User::find()->select(['realname'])
                ->where(['belong_stores_id' => $id, 'county' => $model->s_county, 'status' => User::STATUS_ACTIVE])
                ->indexBy('id')->asArray()->column();
            $model->s_province = Helper::getAddrName($model->s_province);
            $model->s_city = Helper::getAddrName($model->s_city);
            $model->s_county = Helper::getAddrName($model->s_county);
            return $this->render('view', ['model' => $model, 's_id' => $id, 'all_sales' => $all_sales]);
        } else {
            return Yii::$app->getResponse()->redirect(['stores/index']);
        }
    }


    /**
     * 编辑
     * @param $id
     * @return $this|string|yii\web\Response
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionUpdate($id)
    {
        $this->getView()->title = '编辑商户';
        if (!$model = Stores::findOne($id)) {
            return Yii::$app->getResponse()->redirect(['stores/index']);
        }
        $request = Yii::$app->getRequest();
        if ($request->getIsPost()) {
            if ($model->updateStore($request->post(), $id)) {
                return $this->success('编辑成功', yii\helpers\Url::toRoute(['stores/view', 'id'=>$model->s_id]));
            }else{
                return $this->error('编辑失败');
            }
        }
        // 商户状态
        $stroe_status = Stores::getAllStatus();
        // 是否对私账户
        $is_private_bank = [
            Stores::BANK_PRIVATE => '是',
            Stores::BANK_PRIVATE_NOT => '否'
        ];
        // 所有省
        $all_province = Helper::getAllProvince();

        $all_citys = Helper::getSubAddr($model->s_province);
        $all_countys = Helper::getSubAddr($model->s_city);
        return $this->render('update', [
            'model' => $model,
            'store_status' => $stroe_status,
            'is_private_bank' => $is_private_bank,
            'all_provinces' => $all_province,
            'all_citys'=>$all_citys,
            'all_countys'=>$all_countys
        ]);
    }

    public function actionDelete($id)
    {
        $model = Stores::findOne($id);
        $model->s_status = Stores::STATUS_DELETE;
        if($model->save()){
            return $this->success('删除成功', yii\helpers\Url::toRoute(['stores/index']));
        }

        return $this->error('删除失败');
    }

    public function actionAllorders($id)
    {
        $this->getView()->title = '商户所有订单';

        $model = new AllOrdersWithStoreSearch();
        $query = $model->search($id, Yii::$app->getRequest()->getQueryParams());


        $querycount = clone $query;
        $pages = new yii\data\Pagination(['totalCount' => $querycount->count()]);
        $pages->pageSize = Yii::$app->params['page_size'];
        $pages->pageSize = 6;
        $data = $query->orderBy(['o_goods_num' => SORT_DESC])->offset($pages->offset)->limit($pages->limit)->asArray()->all();
        return $this->render('allorders', [
            'sear' => $model->getAttributes(),
            'model' => $data,
            'totalpage' => $pages->pageCount,
            'pages'=>$pages
        ]);
    }

    /**
     * 店铺销售人员列表
     * @param $tu_tid
     * @return string
     * @author lzz <leewangyi@126.com>
     */

    public function actionSalemanindex()
    {
        $this->getView()->title = '店铺销售人员列表';
        $model = new StoresSaleman();
        //p(Yii::$app->getRequest()->getQueryParams());
        $query = $model->search(Yii::$app->getRequest()->get());
        $query_count = clone $query;
        $pages = new yii\data\Pagination(['totalCount' => $query_count->count()]);
        $pages->pageSize = Yii::$app->params['page_size'];
        $data = $query->orderBy(['ss_store_id' => SORT_DESC])->offset($pages->offset)->limit($pages->limit)->asArray()->all();
        //p($model->getAttributes());
        return $this->render('salemanindex', [
            'model' => $data,
            'sear' => $model->getAttributes(),
            'totalpage' => $pages,
        ]);

    }
    /**
     * 添加销售人员
     * @author lzz <leewangyi@126.com>
     */
    public function actionAddsale($ss_store_id)
    {
        try {
            $this->getView()->title = '添加销售';
            $model = new StoresSaleman();

            $request = Yii::$app->getRequest();
            if ($request->getIsPost()) {
                if (!Stores::findOne($ss_store_id)) {
                    throw new CustomBackendException('店铺不存在');
                }

                if (StoresSaleman::findOne(['ss_saleman_id' => $request->post('ss_saleman_id')])) {
                    throw new CustomBackendException('该销售已添加, 无法重复添加');
                }
                $model->ss_store_id = $ss_store_id;
                $model->ss_saleman_id = $request->post('ss_saleman_id');
                $model->save();
                return $this->success('操作成功', yii\helpers\Url::toRoute(['stores/salemanindex', 'ss_store_id' => $ss_store_id]));
            }
            return $this->render('addsale', [
                'model' => $model
            ]);
        } catch (CustomBackendException $e) {
            return $this->error($e->getMessage(), yii\helpers\Url::toRoute(['stores/index']));
        } catch (yii\base\Exception $e) {
            return $this->error('系统错误', yii\helpers\Url::toRoute(['stores/index']));
        }
    }


    /**
     * 删除销售成员
     * @param $id
     * @return mixed
     * @author lzz <leewangyi@126.com>
     */
    public function actionDeletesale($ss_id, $ss_store_id)
    {
        try {
            $this->getView()->title = '删除销售';
            if (!StoresSaleman::findOne(['ss_id' => $ss_id])) {
                throw new CustomBackendException('信息不存在');
            }
            StoresSaleman::deleteAll(['ss_id' => $ss_id]);
            return $this->success('操作成功', yii\helpers\Url::toRoute(['stores/salemanindex', 'ss_store_id' => $ss_store_id]));

        } catch (CustomBackendException $e) {
            return $this->error($e->getMessage(), yii\helpers\Url::toRoute(['stores/index']));
        } catch (yii\base\Exception $e) {
            return $this->error('系统错误', yii\helpers\Url::toRoute(['stores/index']));
        }
    }
    /**
     * 上传商户图片
     * @return bool|string
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionUpload()
    {
        $pic = yii\web\UploadedFile::getInstanceByName('file'); // 获取图片
        $key = Yii::$app->getSecurity()->generateRandomString();
//        var_dump($key,$pic);die;
        $key .= $_SERVER['REQUEST_TIME'];
        $handle = new UploadFile();
        $ret = $handle->uploadFile($key, $pic);
        $key = false;
        if(end($ret) === null){
            $key = reset($ret)['key'];
        }
        return $key;
    }

    /**
     * 捆绑销售人员到店铺
     * @param $store_id
     * @return array|bool
     * @throws yii\db\Exception
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionSales_bak()
    {
        $request = Yii::$app->getRequest();
        Yii::$app->getResponse()->format = 'json';
        if (!$request->getIsAjax()) {
            return false;
        }
        // post添加或取消
        if ($request->getIsPost()) {
            $sales_id = $request->post('sales_id');
            $store_id = $request->get('store_id');
            $model = User::findOne($sales_id);
            if (is_object($model)) {
                if ($model->belong_stores_id != 0) {
                    $model->belong_stores_id = 0;
                } else {
                    $model->belong_stores_id = $store_id;
                }
                $model->save(false);
                return ['status' => 1, 'message' => '编辑成功'];
            } else {
                return ['status' => 0, 'message' => '添加失败'];
            }
        }
    }
    /**
     * 绑定销售人员
     * @param $store_id
     * @return string
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionSales($store_id)
    {
        $request = Yii::$app->getRequest();
        $this->getView()->title = '分配销售人员';
        if ($request->getIsGet()) {
            $s_name = Stores::find()->select(['s_name'])->where(['s_id' => $store_id])->scalar();
            $all_sales = Stores::find()->select(['id', 'realname', 's_name'])
                ->leftJoin(User::tableName(), 'county=s_county')
                ->where(['s_id' => $store_id, 'status' => User::STATUS_ACTIVE, 'belong_stores_id' => 0])
                ->asArray()->all();

            // 已经捆绑过的
            $already = User::find()->select(['id', 'realname'])->where(['belong_stores_id' => $store_id])->asArray()->all();
            return $this->render('sales', ['model' => $all_sales, 's_id' => $store_id, 'already' => $already, 's_name' => $s_name]);
        }

        if ($request->getIsPost()) {
            if (Stores::find()->where(['s_id' => $store_id])->exists()) {
                // 要取消的
                $cancel_id_arr = $request->post('cancel_id');
                $cancel_id_str = implode(',', $cancel_id_arr);


                // 要添加的
                $id_arr = $request->post('id');
                $id_str = implode(',', $id_arr);
                $sql = 'select id from user where id in (' . $id_str . ') for update';
                $total = User::findBySql($sql)->all();
                if (count($total) != count($id_arr)) {
                    Yii::$app->getSession()->setFlash('msg', '绑定失败');
                    return $this->refresh();
                }
                User::updateAll(['belong_stores_id' => $store_id], ['id' => $id_arr]);
                Yii::$app->getSession()->setFlash('msg', '绑定成功');
                return $this->redirect(['view', 'id' => $store_id]);
            }
        }
    }

}

