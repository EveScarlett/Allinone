<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Movimientos $model */

$this->title = Yii::t('app', 'Actualizar Movimiento');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Movimientos'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Actualizar');
?>
<div class="movimientos-update">

    <div class="container">
        <h1 class="title1 text-uppercase"><?= Html::encode($this->title) ?></h1>

        <?= $this->render('_form', [
            'model' => $model,
            'tipo'=>$tipo
            ]) ?>
    </div>

</div>