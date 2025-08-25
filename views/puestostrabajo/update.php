<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\PuestosTrabajo $model */

$this->title = 'Actualizar Puesto de Trabajo: ' . $model->nombre;
$this->params['breadcrumbs'][] = ['label' => 'Puestos Trabajos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="puestos-trabajo-update">

    <div class="container">
        <h1 class="title1"><?= Html::encode($this->title) ?></h1>

        <?= $this->render('_form', [
            'model' => $model,
            ]) ?>
    </div>

</div>