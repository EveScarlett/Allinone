<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Trabajadores $model */

$this->title = 'Documentos Trabajador: ' . $model->nombre.' '.$model->apellidos ;
$this->params['breadcrumbs'][] = ['label' => 'Trabajadores', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['folder', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Documentos';
?>
<div class="trabajadores-update">

    <div class="container-fluid">
        <h1 class="title1"><?= Html::encode($this->title) ?></h1>

        <?= $this->render('_formfolder', [
        'model' => $model,]) ?>

    </div>


</div>