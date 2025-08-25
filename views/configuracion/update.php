<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Configuracion $model */

$this->title = Yii::t('app', 'Actualizar Configuración: {name}', [
    'name' => $model->dempresa->comercial,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Configuración'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Actualizar');
?>
<div class="configuracion-update">

    <div class="container">
        <h1 class="title1"><?= Html::encode($this->title) ?></h1>

        <?= $this->render('_form', [
            'model' => $model,
            ]) ?>
    </div>

</div>