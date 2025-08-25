<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\DiagnosticoscieSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="diagnosticoscie-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'clave') ?>

    <?= $form->field($model, 'clave_epi') ?>

    <?= $form->field($model, 'diagnostico') ?>

    <?= $form->field($model, 'cie_version') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
