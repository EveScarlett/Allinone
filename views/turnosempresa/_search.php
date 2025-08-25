<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\TurnosempresaSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="turnos-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'id_empresa') ?>

    <?= $form->field($model, 'turno') ?>

    <?= $form->field($model, 'lunes_inicio') ?>

    <?= $form->field($model, 'lunes_fin') ?>

    <?php // echo $form->field($model, 'martes_inicio') ?>

    <?php // echo $form->field($model, 'martes_fin') ?>

    <?php // echo $form->field($model, 'miercoles_inicio') ?>

    <?php // echo $form->field($model, 'miercoles_fin') ?>

    <?php // echo $form->field($model, 'jueves_inicio') ?>

    <?php // echo $form->field($model, 'jueves_fin') ?>

    <?php // echo $form->field($model, 'viernes_inicio') ?>

    <?php // echo $form->field($model, 'viernes_fin') ?>

    <?php // echo $form->field($model, 'sabado_inicio') ?>

    <?php // echo $form->field($model, 'sabado_fin') ?>

    <?php // echo $form->field($model, 'domingo_inicio') ?>

    <?php // echo $form->field($model, 'domingo_fin') ?>

    <?php // echo $form->field($model, 'requiere_enfermeros') ?>

    <?php // echo $form->field($model, 'requiere_medicos') ?>

    <?php // echo $form->field($model, 'requiere_extras') ?>

    <?php // echo $form->field($model, 'cantidad_enfermeros') ?>

    <?php // echo $form->field($model, 'cantidad_medicos') ?>

    <?php // echo $form->field($model, 'cantidad_extras') ?>

    <?php // echo $form->field($model, 'orden') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'soft_delete') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
