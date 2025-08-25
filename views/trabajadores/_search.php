<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\TrabajadoresSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="trabajadores-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'id_empresa') ?>

    <?= $form->field($model, 'tipo_examen') ?>

    <?= $form->field($model, 'nombre') ?>

    <?= $form->field($model, 'apellidos') ?>

    <?php // echo $form->field($model, 'foto') ?>

    <?php // echo $form->field($model, 'sexo') ?>

    <?php // echo $form->field($model, 'estado_civil') ?>

    <?php // echo $form->field($model, 'fecha_nacimiento') ?>

    <?php // echo $form->field($model, 'edad') ?>

    <?php // echo $form->field($model, 'nivel_lectura') ?>

    <?php // echo $form->field($model, 'nivel_escritura') ?>

    <?php // echo $form->field($model, 'escolaridad') ?>

    <?php // echo $form->field($model, 'grupo') ?>

    <?php // echo $form->field($model, 'rh') ?>

    <?php // echo $form->field($model, 'num_imss') ?>

    <?php // echo $form->field($model, 'celular') ?>

    <?php // echo $form->field($model, 'contacto_emergencia') ?>

    <?php // echo $form->field($model, 'direccion') ?>

    <?php // echo $form->field($model, 'colonia') ?>

    <?php // echo $form->field($model, 'pais') ?>

    <?php // echo $form->field($model, 'estado') ?>

    <?php // echo $form->field($model, 'ciudad') ?>

    <?php // echo $form->field($model, 'municipio') ?>

    <?php // echo $form->field($model, 'cp') ?>

    <?php // echo $form->field($model, 'num_trabajador') ?>

    <?php // echo $form->field($model, 'tipo_contratacion') ?>

    <?php // echo $form->field($model, 'fecha_contratacion') ?>

    <?php // echo $form->field($model, 'fecha_baja') ?>

    <?php // echo $form->field($model, 'antiguedad') ?>

    <?php // echo $form->field($model, 'ruta') ?>

    <?php // echo $form->field($model, 'parada') ?>

    <?php // echo $form->field($model, 'create_date') ?>

    <?php // echo $form->field($model, 'create_user') ?>

    <?php // echo $form->field($model, 'update_date') ?>

    <?php // echo $form->field($model, 'update_user') ?>

    <?php // echo $form->field($model, 'delete_date') ?>

    <?php // echo $form->field($model, 'delete_user') ?>

    <?php // echo $form->field($model, 'status') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
