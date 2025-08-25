<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\EmpresasSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="empresas-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'razon') ?>

    <?= $form->field($model, 'comercial') ?>

    <?= $form->field($model, 'abreviacion') ?>

    <?= $form->field($model, 'rfc') ?>

    <?php // echo $form->field($model, 'pais') ?>

    <?php // echo $form->field($model, 'estado') ?>

    <?php // echo $form->field($model, 'ciudad') ?>

    <?php // echo $form->field($model, 'municipio') ?>

    <?php // echo $form->field($model, 'logo') ?>

    <?php // echo $form->field($model, 'contacto') ?>

    <?php // echo $form->field($model, 'telefono') ?>

    <?php // echo $form->field($model, 'correo') ?>

    <?php // echo $form->field($model, 'lunes_inicio') ?>

    <?php // echo $form->field($model, 'lunes_fin') ?>

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

    <?php // echo $form->field($model, 'create_date') ?>

    <?php // echo $form->field($model, 'create_user') ?>

    <?php // echo $form->field($model, 'update_date') ?>

    <?php // echo $form->field($model, 'update_user') ?>

    <?php // echo $form->field($model, 'delete_date') ?>

    <?php // echo $form->field($model, 'delete_user') ?>

    <?php // echo $form->field($model, 'status') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
