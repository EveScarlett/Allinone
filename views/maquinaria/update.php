<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Maquinaria $model */

$this->title = Yii::t('app', 'Actualizar Maquinaria: {name}', [
    'name' => $model->maquina,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Maquinarias'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Actualizar');
?>
<div class="maquinaria-update">

    <div class="container">
        <h1 class="title1"><?= Html::encode($this->title) ?></h1>

        <?= $this->render('_form', [
            'model' => $model,
            ]) ?>
    </div>

</div>