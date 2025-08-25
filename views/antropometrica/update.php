<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Cuestionario $model */

$this->title = 'Actualizar Cuestionario: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Cuestionarios', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="cuestionario-update">

    <div class="container">
        <h1 class="title1"><?= Html::encode($this->title) ?></h1>

        <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    </div>

</div>