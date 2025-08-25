<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Areas $model */

$this->title = Yii::t('app', 'Actualizar Área: {name}', [
    'name' => $model->area,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Áreas'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->area, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Actualizar');
?>
<div class="areas-update">

    <div class="container">
        <h1 class="title1 text-uppercase"><?= Html::encode($this->title) ?></h1>

        <?= $this->render('_form', [
            'model' => $model,
            ]) ?>
    </div>

</div>
