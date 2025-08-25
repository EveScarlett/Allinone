<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Diagnosticoscie $model */

$this->title = 'Actualizar Diagnóstico CIE: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Diagnosticoscies', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="diagnosticoscie-update">

    <div class="container">
        <h1 class="title1 text-uppercase"><?= Html::encode($this->title) ?></h1>

        <?= $this->render('_form', [
            'model' => $model,
            ]) ?>
    </div>

</div>