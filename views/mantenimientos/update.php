<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Mantenimientos $model */

$this->title = Yii::t('app', 'Actualizar Mantenimiento: {name}', [
    'name' => $model->id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Histórico de Mantenimiento'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Actualizar');
?>
<div class="mantenimientos-update">

    <div class="container-fluid">
        <h1 class="title1"><?= Html::encode($this->title) ?></h1>

        <?= $this->render('_form', [
            'model' => $model,
            ]) ?>
    </div>

</div>