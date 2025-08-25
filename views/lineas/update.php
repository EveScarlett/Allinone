<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Lineas $model */

$this->title = Yii::t('app', 'Actualizar Linea: {name}', [
    'name' => $model->linea,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Lineas'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->linea, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Actualizar');
?>
<div class="lineas-update">

    <div class="container">
        <h1 class="title1 text-uppercase"><?= Html::encode($this->title) ?></h1>

        <?= $this->render('_form', [
            'model' => $model,
            ]) ?>
    </div>

</div>