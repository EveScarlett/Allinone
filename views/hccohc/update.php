<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\HccOhc $model */

$this->title = 'Actualizar Historia Clínica: ' . $model->nombre.' '.$model->apellidos;
$this->params['breadcrumbs'][] = ['label' => 'Historias Clínicas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="hcc-ohc-update">

    <div class="container-fluid">
        <h1 class="title1"><?= Html::encode($this->title) ?></h1>

        <?= $this->render('_form', [
            'model' => $model,
            'msj'=>$msj,
            'hc_anterior'=>$hc_anterior
            ]) ?>
    </div>

</div>