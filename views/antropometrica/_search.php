<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\AntropometricaSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="cuestionario-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'id_tipo_cuestionario') ?>

    <?= $form->field($model, 'id_paciente') ?>

    <?= $form->field($model, 'id_medico') ?>

    <?= $form->field($model, 'nombre_cuestionario') ?>

    <?php // echo $form->field($model, 'fecha_cuestionario') ?>

    <?php // echo $form->field($model, 'nombre_medico') ?>

    <?php // echo $form->field($model, 'nombre_empresa') ?>

    <?php // echo $form->field($model, 'firma_medico') ?>

    <?php // echo $form->field($model, 'firma_paciente') ?>

    <?php // echo $form->field($model, 'id_bitacora') ?>

    <?php // echo $form->field($model, 'status') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
