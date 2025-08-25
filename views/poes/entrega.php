<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Poes $model */

$this->title = Yii::t('app', 'Entrega de Resultados: {name}', [
    'name' => $model->nombre.' '.$model->apellidos.' - AÑO '.$model->anio,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Poes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Entrega de Resultados');
?>
<div class="poes-entrega">

    <div class="container-fluid">
        <h1 class="title1 text-center"><?= Html::encode($this->title) ?></h1>

        <?= $this->render('_formentrega', [
            'model' => $model,
            ]) ?>
    </div>

</div>