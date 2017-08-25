<?php

namespace backend\controllers;

use yii;
use backend\core\CoreBackendController;
use common\models\Product;

class ProductController extends CoreBackendController
{
    public function actionIndexp()
    {
        echo '产品管理';
    }
    public function actionIndex()
    {
        $this->getView()->title = '产品列表';
        $arr = Yii::$app->getRequest()->getQueryParams();
        $model = new Product();
        $query = $model->search(Yii::$app->getRequest()->getQueryParams());
        $querycount = clone $query;
        $pages = new yii\data\Pagination(['totalCount' => $querycount->count()]);
        $pages->pageSize = Yii::$app->params['page_size'];
        $data = $query->orderBy(['p_created_at' => SORT_DESC])->offset($pages->offset)->limit($pages->limit)->asArray()->all();
        $status = 100;
        if(!empty($arr['Product']['p_status'])){
            $status = $arr['Product']['p_status'];
        }
        return $this->render('index', [
            'status' => $status,
            'sear' => $model->getAttributes(),
            'model' => $data,
            'totalpage' => $pages->pageCount,
            'pages' => $pages
        ]);
    }

    /**
     * 筛选产品
     * @return $this|string1
     * @author 皮潇世 <p30436397@163.com>
     */
//    public function actionFilter()
//    {
//        $this->getView()->title = '产品列表';
//
//        $model = new Product();
//        $query = $model->search(Yii::$app->getRequest()->getQueryParams());
//        $querycount = clone $query;
//        $pages = new yii\data\Pagination(['totalCount' => $querycount->count()]);
//        $pages->pageSize = Yii::$app->params['page_size'];
//        $data = $query->orderBy(['p_created_at' => SORT_DESC])->offset($pages->offset)->limit($pages->limit)->asArray()->all();
//
//        return $this->render('index', [
//            'sear' => $model->getAttributes(),
//            'model' => $data,
//            'totalpage' => $pages->pageCount,
//            'pages' => $pages
//        ]);
//    }

    /**
     * 创建产品
     * @return $this|string1
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionCreate()
    {
        $this->getView()->title = '添加产品';

        $request = \Yii::$app->getRequest();
        $model = new Product();
        if ($request->getIsPost()) {
            $data['data'] = $request->post();
            $model->load($data, 'data');

            if ($model->createProduct()) {
                return $this->success('添加成功', yii\helpers\Url::toRoute(['product/view', 'id' => $model->p_id]));
            }
        }

        $all_status = Product::getAllStatus();
        array_pop($all_status);
        $goods_type = Yii::$app->params['goods_type'];
        return $this->render('create', ['model' => $model, 'all_status' => $all_status, 'goods_type' => $goods_type]);
    }

    /**
     * 查看产品
     * @param $id
     * @return string
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionView($id)
    {
        $data = Product::findOne($id);
        $this->getView()->title = $data->p_name;
        return $this->render('view', ['model' => $data]);
    }

    /*
     * 产品不能修改 20160928 涂鸿
     * */
    /*public function actionUpdate($id)
    {
        $model = Product::findOne($id);
        $request = Yii::$app->getRequest();
        if($request->getIsPost()){
            if($model->updateProduct($request->post())){
                return \Yii::$app->getResponse()->redirect(['product/view', 'id'=>$model->p_id]);
            }
        }

        $this->getView()->title='编辑产品';
        $all_status = Product::getAllStatus();
        return $this->render('update', ['model'=>$model, 'all_status'=>$all_status]);
    }*/


    /**
     * 删除产品
     * @param $id
     * @return yii\web\Response
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionDelete($p_id)
    {
        if ($model = Product::findOne($p_id)) {
            $model->p_status = Product::STATUS_DEL;
            $model->save(false);
            return $this->success('删除成功！');
        }
        return $this->error('删除失败！');
    }

    /**
     * 冻结产品
     * @param $p_id 产品id
     * @return yii\web\Response
     * @author 皮潇世 <p30436397@163.com>
     */
    public function actionFreeze($p_id)
    {
        if ($model = Product::findOne($p_id)) {
            $model->p_status = Product::STATUS_STOP;
            $model->save(false);
            return $this->success('冻结成功！');
        }
        return $this->error('冻结失败！');
    }

    /**
     * 解冻产品
     * @param $p_id 产品id
     * @return yii\web\Response
     * @author 皮潇世 <p30436397@163.com>
     */
    public function actionThaw($p_id)
    {
        if ($model = Product::findOne($p_id)) {
            $model->p_status = Product::STATUS_OK;
            $model->save(false);
            return $this->success('解冻成功！');
        }
        return $this->error('解冻失败！');
    }
}
