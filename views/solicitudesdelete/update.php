<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\SolicitudesDelete $model */

$this->title = Yii::t('app', 'Update Solicitudes Delete: {name}', [
    'name' => $model->id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Solicitudes Deletes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="solicitudes-delete-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
