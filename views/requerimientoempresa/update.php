<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Empresas $model */

$this->title = 'Actualizar Requisitos Mínimos para la Empresa: ' . $model->comercial;
$this->params['breadcrumbs'][] = ['label' => 'Empresas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="empresas-update">

    <div class="container-fluid">
        <h1 class="title1"><?= Html::encode($this->title) ?></h1>

        <?= $this->render('_form', [
            'model' => $model,
            ]) ?>
    </div>

</div>