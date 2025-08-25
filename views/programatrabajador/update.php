<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Trabajadores $model */

$this->title = Yii::t('app', 'Actualizar Programas de Salud {name}', [
    'name' => '',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Programas de Salud'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->nombre.' '.$model->apellidos, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Actualizar');
?>
<div class="trabajadores-update">

    <div class="container-fluid">
        <h1 class="title1 text-uppercase"><?= Html::encode($this->title) ?></h1>

        <?= $this->render('_form', [
            'model' => $model,
            ]) ?>
    </div>

</div>
