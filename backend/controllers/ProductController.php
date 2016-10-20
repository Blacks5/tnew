<?php

namespace backend\controllers;
use yii;
use backend\core\CoreBackendController;
use common\models\Product;

class ProductController extends CoreBackendController
{
    public function actionIndex()
    {
        $this->getView()->title = '产品列表';

        $model = new Product();
        $query = $model->search(Yii::$app->getRequest()->getQueryParams());
        $querycount = clone $query;
        $pages = new yii\data\Pagination(['totalCount'=>$querycount->count()]);
        $pages->pageSize=20;
        $data = $query->orderBy(['p_created_at'=>SORT_DESC])->offset($pages->offset)->limit($pages->limit)->asArray()->all();

        return $this->render('index', [
            'sear'=>$model->getAttributes(),
            'model'=>$data,
            'totalpage'=> $pages->pageCount,
            'pages'=>$pages
        ]);
    }

    /**
     * 创建产品
     * @return $this|string1
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionCreate()
    {
//        $this->getView()->title='添加产品';

        /*$request = \Yii::$app->getRequest();
        $model = new Product();
        if($request->getIsPost()){
            $model->load($request->post());
            if($model->validate()){
                if($model->createProduct()){
                    return \Yii::$app->getResponse()->redirect(['product/view', 'id'=>$model->p_id]);
                }
            }
        }

        $all_status = Product::getAllStatus();
        array_pop($all_status);*/

        return $this->render('create'/*, ['model'=>$model, 'all_status'=>$all_status]*/);
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
        return $this->render('view', ['model'=>$data]);
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
    public function actionDelete($id)
    {
        if($model = Product::findOne($id)){
            $model->p_status = Product::STATUS_DEL;
            $model->save(false);
            return $this->redirect(['index']);
        }
    }
}
