<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Ni $model */

$this->title = Yii::t('app', 'Actualizar Requisitos Nuevo Ingreso: {name}', [
    'name' => $model->ni,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Nuevo Ingreso'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->ni, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Actualizar');
?>
<div class="ni-update">

    <div class="container">
        <h1 class="title1"><?= Html::encode($this->title) ?></h1>

        <?= $this->render('_form', [
            'model' => $model,
            ]) ?>
    </div>

</div>
