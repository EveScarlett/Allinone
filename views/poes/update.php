<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Poes $model */

$this->title = Yii::t('app', 'Actualizar POE: {name}', [
    'name' => $model->nombre.' '.$model->apellidos.' - AÑO '.$model->anio,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Exámenes Médicos'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Actualizar');
?>
<div class="poes-update">

    <div class="container-fluid">
        <h1 class="title1"><?= Html::encode($this->title) ?></h1>

        <?= $this->render('_form', [
            'model' => $model,
            ]) ?>
    </div>

</div>