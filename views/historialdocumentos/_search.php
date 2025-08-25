<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\HistorialdocumentosSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="historialdocumentos-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'id_empresa') ?>

    <?= $form->field($model, 'id_trabajador') ?>

    <?= $form->field($model, 'id_tipo') ?>

    <?= $form->field($model, 'id_estudio') ?>

    <?php // echo $form->field($model, 'id_periodicidad') ?>

    <?php // echo $form->field($model, 'fecha_documento') ?>

    <?php // echo $form->field($model, 'fecha_vencimiento') ?>

    <?php // echo $form->field($model, 'evidencia') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
