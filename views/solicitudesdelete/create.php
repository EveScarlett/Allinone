<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\SolicitudesDelete $model */

$this->title = Yii::t('app', 'Create Solicitudes Delete');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Solicitudes Deletes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="solicitudes-delete-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
