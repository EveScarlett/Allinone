<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\ConsultasSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="consultas-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'id_trabajador') ?>

    <?= $form->field($model, 'id_empresa') ?>

    <?= $form->field($model, 'id_consultorio') ?>

    <?= $form->field($model, 'tipo') ?>

    <?php // echo $form->field($model, 'folio') ?>

    <?php // echo $form->field($model, 'fecha') ?>

    <?php // echo $form->field($model, 'visita') ?>

    <?php // echo $form->field($model, 'solicitante') ?>

    <?php // echo $form->field($model, 'hora_inicio') ?>

    <?php // echo $form->field($model, 'hora_fin') ?>

    <?php // echo $form->field($model, 'sexo') ?>

    <?php // echo $form->field($model, 'num_imss') ?>

    <?php // echo $form->field($model, 'area') ?>

    <?php // echo $form->field($model, 'puesto') ?>

    <?php // echo $form->field($model, 'evidencia') ?>

    <?php // echo $form->field($model, 'fc') ?>

    <?php // echo $form->field($model, 'fr') ?>

    <?php // echo $form->field($model, 'temp') ?>

    <?php // echo $form->field($model, 'ta') ?>

    <?php // echo $form->field($model, 'ta_diastolica') ?>

    <?php // echo $form->field($model, 'pulso') ?>

    <?php // echo $form->field($model, 'oxigeno') ?>

    <?php // echo $form->field($model, 'peso') ?>

    <?php // echo $form->field($model, 'talla') ?>

    <?php // echo $form->field($model, 'imc') ?>

    <?php // echo $form->field($model, 'categoria_imc') ?>

    <?php // echo $form->field($model, 'sintomatologia') ?>

    <?php // echo $form->field($model, 'aparatos') ?>

    <?php // echo $form->field($model, 'alergias') ?>

    <?php // echo $form->field($model, 'embarazo') ?>

    <?php // echo $form->field($model, 'diagnosticocie') ?>

    <?php // echo $form->field($model, 'diagnostico') ?>

    <?php // echo $form->field($model, 'estudios') ?>

    <?php // echo $form->field($model, 'manejo') ?>

    <?php // echo $form->field($model, 'seguimiento') ?>

    <?php // echo $form->field($model, 'resultado') ?>

    <?php // echo $form->field($model, 'tipo_padecimiento') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
