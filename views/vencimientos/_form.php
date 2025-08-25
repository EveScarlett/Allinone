<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Trabajadores $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="trabajadores-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'tipo_registro')->textInput() ?>

    <?= $form->field($model, 'id_cargamasiva')->textInput() ?>

    <?= $form->field($model, 'id_empresa')->textInput() ?>

    <?= $form->field($model, 'tipo_examen')->textInput() ?>

    <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'apellidos')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'foto')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sexo')->textInput() ?>

    <?= $form->field($model, 'estado_civil')->textInput() ?>

    <?= $form->field($model, 'fecha_nacimiento')->textInput() ?>

    <?= $form->field($model, 'edad')->textInput() ?>

    <?= $form->field($model, 'turno')->textInput() ?>

    <?= $form->field($model, 'nivel_lectura')->textInput() ?>

    <?= $form->field($model, 'nivel_escritura')->textInput() ?>

    <?= $form->field($model, 'escolaridad')->textInput() ?>

    <?= $form->field($model, 'grupo')->textInput() ?>

    <?= $form->field($model, 'rh')->textInput() ?>

    <?= $form->field($model, 'num_imss')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'curp')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'rfc')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'correo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'celular')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'contacto_emergencia')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'direccion')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'colonia')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pais')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'estado')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ciudad')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'municipio')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cp')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'num_trabajador')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tipo_contratacion')->textInput() ?>

    <?= $form->field($model, 'fecha_contratacion')->textInput() ?>

    <?= $form->field($model, 'fecha_baja')->textInput() ?>

    <?= $form->field($model, 'motivo_baja')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'antiguedad')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'antiguedad_dias')->textInput() ?>

    <?= $form->field($model, 'antiguedad_meses')->textInput() ?>

    <?= $form->field($model, 'antiguedad_anios')->textInput() ?>

    <?= $form->field($model, 'ruta')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'parada')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'id_puesto')->textInput() ?>

    <?= $form->field($model, 'id_area')->textInput() ?>

    <?= $form->field($model, 'fecha_iniciop')->textInput() ?>

    <?= $form->field($model, 'fecha_finp')->textInput() ?>

    <?= $form->field($model, 'teamleader')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'talla_cabeza')->textInput() ?>

    <?= $form->field($model, 'talla_general')->textInput() ?>

    <?= $form->field($model, 'talla_superior')->textInput() ?>

    <?= $form->field($model, 'talla_inferior')->textInput() ?>

    <?= $form->field($model, 'talla_manos')->textInput() ?>

    <?= $form->field($model, 'talla_pies')->textInput() ?>

    <?= $form->field($model, 'personalidad')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'dato_extra1')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'dato_extra2')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'dato_extra3')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'dato_extra4')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'dato_extra5')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'dato_extra6')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'dato_extra7')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'dato_extra8')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'dato_extra9')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'dato_extra10')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'create_date')->textInput() ?>

    <?= $form->field($model, 'create_user')->textInput() ?>

    <?= $form->field($model, 'update_date')->textInput() ?>

    <?= $form->field($model, 'update_user')->textInput() ?>

    <?= $form->field($model, 'delete_date')->textInput() ?>

    <?= $form->field($model, 'delete_user')->textInput() ?>

    <?= $form->field($model, 'status_documentos')->textInput() ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'id_link')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
