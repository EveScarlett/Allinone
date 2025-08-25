<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Turnos $model */

$this->title = Yii::t('app', 'Actualizar Turno: {name}', [
    'name' => $model->id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Turnos'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->turno, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Actualizar');
?>
<div class="turnos-update">

    <div class="container">
        <h1 class="title1 text-uppercase"><?= Html::encode($this->title) ?></h1>

        <?= $this->render('_form', [
            'model' => $model,
            ]) ?>
    </div>

</div>
