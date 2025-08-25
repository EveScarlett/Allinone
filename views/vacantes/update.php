<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Vacantes $model */

$this->title = Yii::t('app', 'Actualizar Vacante: {name}', [
    'name' => $model->titulo,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Vacantes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Actualizar');
?>
<div class="vacantes-update">

    <div class="container">
        <h1 class="title1"><?= Html::encode($this->title) ?></h1>

        <?= $this->render('_form', [
            'model' => $model,
            ]) ?>
    </div>

</div>