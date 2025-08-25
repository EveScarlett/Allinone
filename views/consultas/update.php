<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Consultas $model */

$this->title = Yii::t('app', 'Actualizar Consulta Médica: {name}', [
    'name' => $model->folio,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Consultas Médicas'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Actualizar');
?>
<div class="consultas-update">

    <div class="container">
        <h1 class="title1"><?= Html::encode($this->title) ?></h1>

        <?= $this->render('_form', [
            'model' => $model,
            ]) ?>
    </div>

</div>