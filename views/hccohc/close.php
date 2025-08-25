<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\HccOhc $model */

$this->title = 'Cerrar Historia Clínica: ' . $model->nombre.' '.$model->apellidos;
$this->params['breadcrumbs'][] = ['label' => 'Historias Clínicas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['close', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Cerrar';
?>
<div class="hcc-ohc-close">

    <div class="container-fluid">
        <h1 class="title1"><?= Html::encode($this->title) ?></h1>

        <?= $this->render('_formclose', [
            'model' => $model,
            ]) ?>
    </div>

</div>