<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\TipoServicios $model */

$this->title = Yii::t('app', 'Ordenar Listado de Estudios: {name}', [
    'name' => '',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Categorias de Estudio'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Ordenar');
?>
<div class="tipo-servicios-update">

    <div class="container">
        <h1 class="title1"><?= Html::encode($this->title).' <span style="border-bottom: 1px solid '.$model->logo.';">'.$model->nombre.'</span>' ?></h1>

        <?= $this->render('_formsort', [
            'model' => $model,
            ]) ?>
    </div>

</div>