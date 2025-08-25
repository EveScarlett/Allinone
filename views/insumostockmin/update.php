<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Insumostockmin $model */

$this->title = 'Actualizar Stock Mínimo: ' . $model->insumo->nombre_comercial;
$this->params['breadcrumbs'][] = ['label' => 'Stock Mínimo', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="insumostockmin-update">

    <div class="container">
        <h1 class="title1"><?= Html::encode($this->title) ?></h1>
        <?= $this->render('_form', [
        'model' => $model,
        'tipo'=>$tipo
    ]) ?>

    </div>
</div>