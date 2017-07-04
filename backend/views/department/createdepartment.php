<?php
$this->params['breadcrumbs'][] = ['label' => '所有部门', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
use yii\helpers\Html;
?>

<div class="wrapper wrapper-content">
    <div class="user-create">
        <div class="ibox-content">
            <h1><?= Html::encode($this->title) ?></h1>

            <hr/>
            <?= $this->render('_form', [
                'model' => $model
            ]) ?>
        </div>
    </div>
</div>


