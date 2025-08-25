<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\PuestosTrabajo $model */

$this->title = 'Nuevo Puesto de Trabajo';
$this->params['breadcrumbs'][] = ['label' => 'Puestos Trabajos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="puestos-trabajo-create">

    <div class="container">
        <h1 class="title1"><?= Html::encode($this->title) ?></h1>

        <?= $this->render('_form', [
            'model' => $model,
            ]) ?>
    </div>

</div>