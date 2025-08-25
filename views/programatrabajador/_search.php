<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\TrabajadorespsSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="trabajadores-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'tipo_registro') ?>

    <?= $form->field($model, 'id_cargamasiva') ?>

    <?= $form->field($model, 'id_empresa') ?>

    <?= $form->field($model, 'id_nivel1') ?>

    <?php // echo $form->field($model, 'id_nivel2') ?>

    <?php // echo $form->field($model, 'id_nivel3') ?>

    <?php // echo $form->field($model, 'id_nivel4') ?>

    <?php // echo $form->field($model, 'id_pais') ?>

    <?php // echo $form->field($model, 'id_linea') ?>

    <?php // echo $form->field($model, 'id_ubicacion') ?>

    <?php // echo $form->field($model, 'tipo_examen') ?>

    <?php // echo $form->field($model, 'nombre') ?>

    <?php // echo $form->field($model, 'apellidos') ?>

    <?php // echo $form->field($model, 'foto') ?>

    <?php // echo $form->field($model, 'sexo') ?>

    <?php // echo $form->field($model, 'estado_civil') ?>

    <?php // echo $form->field($model, 'fecha_nacimiento') ?>

    <?php // echo $form->field($model, 'edad') ?>

    <?php // echo $form->field($model, 'turno') ?>

    <?php // echo $form->field($model, 'nivel_lectura') ?>

    <?php // echo $form->field($model, 'nivel_escritura') ?>

    <?php // echo $form->field($model, 'escolaridad') ?>

    <?php // echo $form->field($model, 'grupo') ?>

    <?php // echo $form->field($model, 'rh') ?>

    <?php // echo $form->field($model, 'num_imss') ?>

    <?php // echo $form->field($model, 'curp') ?>

    <?php // echo $form->field($model, 'rfc') ?>

    <?php // echo $form->field($model, 'correo') ?>

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

    <?php // echo $form->field($model, 'motivo_baja') ?>

    <?php // echo $form->field($model, 'antiguedad') ?>

    <?php // echo $form->field($model, 'antiguedad_dias') ?>

    <?php // echo $form->field($model, 'antiguedad_meses') ?>

    <?php // echo $form->field($model, 'antiguedad_anios') ?>

    <?php // echo $form->field($model, 'ruta') ?>

    <?php // echo $form->field($model, 'parada') ?>

    <?php // echo $form->field($model, 'id_puesto') ?>

    <?php // echo $form->field($model, 'puesto_contable') ?>

    <?php // echo $form->field($model, 'puesto_sueldo') ?>

    <?php // echo $form->field($model, 'id_area') ?>

    <?php // echo $form->field($model, 'fecha_iniciop') ?>

    <?php // echo $form->field($model, 'fecha_finp') ?>

    <?php // echo $form->field($model, 'teamleader') ?>

    <?php // echo $form->field($model, 'talla_cabeza') ?>

    <?php // echo $form->field($model, 'talla_general') ?>

    <?php // echo $form->field($model, 'talla_superior') ?>

    <?php // echo $form->field($model, 'talla_inferior') ?>

    <?php // echo $form->field($model, 'talla_manos') ?>

    <?php // echo $form->field($model, 'talla_pies') ?>

    <?php // echo $form->field($model, 'personalidad') ?>

    <?php // echo $form->field($model, 'dato_extra1') ?>

    <?php // echo $form->field($model, 'dato_extra2') ?>

    <?php // echo $form->field($model, 'dato_extra3') ?>

    <?php // echo $form->field($model, 'dato_extra4') ?>

    <?php // echo $form->field($model, 'dato_extra5') ?>

    <?php // echo $form->field($model, 'dato_extra6') ?>

    <?php // echo $form->field($model, 'dato_extra7') ?>

    <?php // echo $form->field($model, 'dato_extra8') ?>

    <?php // echo $form->field($model, 'dato_extra9') ?>

    <?php // echo $form->field($model, 'dato_extra10') ?>

    <?php // echo $form->field($model, 'create_date') ?>

    <?php // echo $form->field($model, 'create_user') ?>

    <?php // echo $form->field($model, 'update_date') ?>

    <?php // echo $form->field($model, 'update_user') ?>

    <?php // echo $form->field($model, 'delete_date') ?>

    <?php // echo $form->field($model, 'delete_user') ?>

    <?php // echo $form->field($model, 'status_documentos') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'status_backup') ?>

    <?php // echo $form->field($model, 'id_link') ?>

    <?php // echo $form->field($model, 'soft_delete') ?>

    <?php // echo $form->field($model, 'origen_extraccion') ?>

    <?php // echo $form->field($model, 'id_origen') ?>

    <?php // echo $form->field($model, 'hidden') ?>

    <?php // echo $form->field($model, 'uso_consentimiento') ?>

    <?php // echo $form->field($model, 'retirar_consentimiento') ?>

    <?php // echo $form->field($model, 'acuerdo_confidencialidad') ?>

    <?php // echo $form->field($model, 'tipo_identificacion') ?>

    <?php // echo $form->field($model, 'numero_identificacion') ?>

    <?php // echo $form->field($model, 'ife') ?>

    <?php // echo $form->field($model, 'ife_reverso') ?>

    <?php // echo $form->field($model, 'foto_web') ?>

    <?php // echo $form->field($model, 'base64_foto') ?>

    <?php // echo $form->field($model, 'base64_ine') ?>

    <?php // echo $form->field($model, 'base64_inereverso') ?>

    <?php // echo $form->field($model, 'firma') ?>

    <?php // echo $form->field($model, 'firma_ruta') ?>

    <?php // echo $form->field($model, 'fecha') ?>

    <?php // echo $form->field($model, 'hora') ?>

    <?php // echo $form->field($model, 'qty_trabajadores') ?>

    <?php // echo $form->field($model, 'estudios_pendientes') ?>

    <?php // echo $form->field($model, 'status_baja') ?>

    <?php // echo $form->field($model, 'puesto_cumplimiento') ?>

    <?php // echo $form->field($model, 'riesgo_cumplimiento') ?>

    <?php // echo $form->field($model, 'programasalud_cumplimiento') ?>

    <?php // echo $form->field($model, 'expediente_cumplimiento') ?>

    <?php // echo $form->field($model, 'hc_cumplimiento') ?>

    <?php // echo $form->field($model, 'poe_cumplimiento') ?>

    <?php // echo $form->field($model, 'cuestionario_cumplimiento') ?>

    <?php // echo $form->field($model, 'antropometrica_cumplimiento') ?>

    <?php // echo $form->field($model, 'programassalud_cumplimiento') ?>

    <?php // echo $form->field($model, 'porcentaje_cumplimiento') ?>

    <?php // echo $form->field($model, 'status_cumplimiento') ?>

    <?php // echo $form->field($model, 'ps_status') ?>

    <?php // echo $form->field($model, 'ps_activos') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
