<?php
/**
 * Created by PhpStorm.
 * Date: 16/8/20
 * Time: 15:49
 * @author 涂鸿 <hayto@foxmail.com>
 */

namespace backend\controllers;

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
    public function actionIndex()
    {
        $this->getView()->title = '商户列表';

        $model = new Stores();
        $query = $model->search(Yii::$app->getRequest()->getQueryParams());
        $querycount = clone $query;
        $pages = new yii\data\Pagination(['totalCount' => $querycount->count()]);
        $pages->pageSize = 2;//Yii::$app->params['page_size'];
        $data = $query->orderBy(['s_created_at' => SORT_DESC])->offset($pages->offset)->limit($pages->limit)->asArray()->all();
        return $this->render('index', [
            'sear' => $model->getAttributes(),
            'model' => $data,
            'totalpage' => $pages->pageCount,
            'pages' => $pages
        ]);
    }

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

    public function actionView($id)
    {
        if ($model = Stores::findOne($id)) {
            $t = new BaseUploadFile();
            $model->s_photo_one = $model->s_photo_one ? $t->handle->getLink($model->s_photo_one) : '';
            $model->s_photo_two = $model->s_photo_two ? $t->handle->getLink($model->s_photo_two) : '';
            $model->s_photo_three = $model->s_photo_three ? $t->handle->getLink($model->s_photo_three) : '';
            $model->s_photo_four = $model->s_photo_four ? $t->handle->getLink($model->s_photo_four) : '';
            $model->s_photo_five = $model->s_photo_five ? $t->handle->getLink($model->s_photo_five) : '';
            $all_sales = Stores::find()->select(['id', 'realname', 'belong_stores_id'])
                ->leftJoin(User::tableName(), 'county=s_county')
                ->where(['s_id' => $id, 'status' => User::STATUS_ACTIVE])->orderBy(['belong_stores_id' => SORT_ASC])
                ->asArray()->all();
            return $this->render('view', ['model' => $model, 's_id' => $id, 'data' => $all_sales]);
        } else {
            return Yii::$app->getResponse()->redirect(['stores/index']);
        }
    }


    public function actionUpdate($id)
    {
        $this->getView()->title = '编辑商户';
        if (!$model = Stores::findOne($id)) {
            return Yii::$app->getResponse()->redirect(['stores/index']);
        }
        $request = Yii::$app->getRequest();
        if ($request->getIsPost()) {
            if ($model->updateStore($request->post(), $id)) {
                return $this->redirect(['view', 'id' => $model->s_id]);
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
        $provinces = GetPcc::getPcc();
        return $this->render('update', [
            'model' => $model,
            'store_status' => $stroe_status,
            'is_private_bank' => $is_private_bank,
            'provinces' => $provinces
        ]);
    }

    public function actionDelete($id)
    {
        if (Stores::findOne($id)->delete()) {
            return $this->redirect(['index']);
        }
    }

    public function actionAllorders($id)
    {
        $this->getView()->title = '商户所有订单';

        $model = new AllOrdersWithStoreSearch();
        $query = $model->search($id, Yii::$app->getRequest()->getQueryParams());


        $querycount = clone $query;
        $pages = new yii\data\Pagination(['totalCount' => $querycount->count()]);
        $pages->pageSize = Yii::$app->params['page_size'];
        $pages->pageSize = 1;
        $data = $query->orderBy(['o_goods_num' => SORT_DESC])->offset($pages->offset)->limit($pages->limit)->asArray()->all();


        return $this->render('allorders', [
            'sear' => $model->getAttributes(),
            'model' => $data,
            'totalpage' => $pages->pageCount,
        ]);
    }

    /**
     * 捆绑销售人员到店铺
     * @param $store_id
     * @return array|bool
     * @throws yii\db\Exception
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionSales()
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

    public function actionUpload()
    {
        $pic = yii\web\UploadedFile::getInstanceByName('file'); // 获取图片
        $key = Yii::$app->getSecurity()->generateRandomString();
        $handle = new UploadFile();
        $handle->handle->uploadFile($pic->tempName, $key);
        return $key;
    }

    /**
     * 绑定销售人员
     * @param $store_id
     * @return string
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionSales_bak($store_id)
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

