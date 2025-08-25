<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\HccOhcSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="hcc-ohc-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'id_trabajador') ?>

    <?= $form->field($model, 'id_empresa') ?>

    <?= $form->field($model, 'folio') ?>

    <?= $form->field($model, 'fecha') ?>

    <?php // echo $form->field($model, 'hora') ?>

    <?php // echo $form->field($model, 'examen') ?>

    <?php // echo $form->field($model, 'empresa') ?>

    <?php // echo $form->field($model, 'area') ?>

    <?php // echo $form->field($model, 'puesto') ?>

    <?php // echo $form->field($model, 'nombre') ?>

    <?php // echo $form->field($model, 'apellidos') ?>

    <?php // echo $form->field($model, 'sexo') ?>

    <?php // echo $form->field($model, 'fecha_nacimiento') ?>

    <?php // echo $form->field($model, 'edad') ?>

    <?php // echo $form->field($model, 'estado_civil') ?>

    <?php // echo $form->field($model, 'nivel_lectura') ?>

    <?php // echo $form->field($model, 'nivel_escritura') ?>

    <?php // echo $form->field($model, 'grupo') ?>

    <?php // echo $form->field($model, 'rh') ?>

    <?php // echo $form->field($model, 'diabetess') ?>

    <?php // echo $form->field($model, 'diabetesstxt') ?>

    <?php // echo $form->field($model, 'hipertension') ?>

    <?php // echo $form->field($model, 'hipertensiontxt') ?>

    <?php // echo $form->field($model, 'cancer') ?>

    <?php // echo $form->field($model, 'cancertxt') ?>

    <?php // echo $form->field($model, 'nefropatias') ?>

    <?php // echo $form->field($model, 'nefropatiastxt') ?>

    <?php // echo $form->field($model, 'cardiopatias') ?>

    <?php // echo $form->field($model, 'cardiopatiastxt') ?>

    <?php // echo $form->field($model, 'reuma') ?>

    <?php // echo $form->field($model, 'reumatxt') ?>

    <?php // echo $form->field($model, 'hepa') ?>

    <?php // echo $form->field($model, 'hepatxt') ?>

    <?php // echo $form->field($model, 'tuber') ?>

    <?php // echo $form->field($model, 'tubertxt') ?>

    <?php // echo $form->field($model, 'psi') ?>

    <?php // echo $form->field($model, 'psitxt') ?>

    <?php // echo $form->field($model, 'tabaquismo') ?>

    <?php // echo $form->field($model, 'tabdesde') ?>

    <?php // echo $form->field($model, 'tabfrec') ?>

    <?php // echo $form->field($model, 'tabcantidad') ?>

    <?php // echo $form->field($model, 'alcoholismo') ?>

    <?php // echo $form->field($model, 'alcodesde') ?>

    <?php // echo $form->field($model, 'alcofrec') ?>

    <?php // echo $form->field($model, 'alcocantidad') ?>

    <?php // echo $form->field($model, 'cocina') ?>

    <?php // echo $form->field($model, 'cocinadesde') ?>

    <?php // echo $form->field($model, 'audifonos') ?>

    <?php // echo $form->field($model, 'audiodesde') ?>

    <?php // echo $form->field($model, 'audiocuando') ?>

    <?php // echo $form->field($model, 'droga') ?>

    <?php // echo $form->field($model, 'drogatxt') ?>

    <?php // echo $form->field($model, 'duracion_droga') ?>

    <?php // echo $form->field($model, 'fecha_droga') ?>

    <?php // echo $form->field($model, 'vacunacion_cov') ?>

    <?php // echo $form->field($model, 'nombre_vacunacion') ?>

    <?php // echo $form->field($model, 'dosis_vacunacion') ?>

    <?php // echo $form->field($model, 'fecha_vacunacion') ?>

    <?php // echo $form->field($model, 'mano') ?>

    <?php // echo $form->field($model, 'alergias') ?>

    <?php // echo $form->field($model, 'alergiastxt') ?>

    <?php // echo $form->field($model, 'asma') ?>

    <?php // echo $form->field($model, 'asmatxt') ?>

    <?php // echo $form->field($model, 'asma_anio') ?>

    <?php // echo $form->field($model, 'cardio') ?>

    <?php // echo $form->field($model, 'cardiotxt') ?>

    <?php // echo $form->field($model, 'cirugias') ?>

    <?php // echo $form->field($model, 'cirugiastxt') ?>

    <?php // echo $form->field($model, 'convulsiones') ?>

    <?php // echo $form->field($model, 'convulsionestxt') ?>

    <?php // echo $form->field($model, 'diabetes') ?>

    <?php // echo $form->field($model, 'diabetestxt') ?>

    <?php // echo $form->field($model, 'hiper') ?>

    <?php // echo $form->field($model, 'hipertxt') ?>

    <?php // echo $form->field($model, 'lumbalgias') ?>

    <?php // echo $form->field($model, 'lumbalgiastxt') ?>

    <?php // echo $form->field($model, 'nefro') ?>

    <?php // echo $form->field($model, 'nefrotxt') ?>

    <?php // echo $form->field($model, 'polio') ?>

    <?php // echo $form->field($model, 'politxt') ?>

    <?php // echo $form->field($model, 'poliomelitis_anio') ?>

    <?php // echo $form->field($model, 'saram') ?>

    <?php // echo $form->field($model, 'saram_anio') ?>

    <?php // echo $form->field($model, 'pulmo') ?>

    <?php // echo $form->field($model, 'pulmotxt') ?>

    <?php // echo $form->field($model, 'hematicos') ?>

    <?php // echo $form->field($model, 'hematicostxt') ?>

    <?php // echo $form->field($model, 'trauma') ?>

    <?php // echo $form->field($model, 'traumatxt') ?>

    <?php // echo $form->field($model, 'medicamentos') ?>

    <?php // echo $form->field($model, 'medicamentostxt') ?>

    <?php // echo $form->field($model, 'protesis') ?>

    <?php // echo $form->field($model, 'protesistxt') ?>

    <?php // echo $form->field($model, 'trans') ?>

    <?php // echo $form->field($model, 'transtxt') ?>

    <?php // echo $form->field($model, 'enf_ocular') ?>

    <?php // echo $form->field($model, 'enf_ocular_txt') ?>

    <?php // echo $form->field($model, 'enf_auditiva') ?>

    <?php // echo $form->field($model, 'enf_auditiva_txt') ?>

    <?php // echo $form->field($model, 'fractura') ?>

    <?php // echo $form->field($model, 'fractura_txt') ?>

    <?php // echo $form->field($model, 'amputacion') ?>

    <?php // echo $form->field($model, 'amputacion_txt') ?>

    <?php // echo $form->field($model, 'hernias') ?>

    <?php // echo $form->field($model, 'hernias_txt') ?>

    <?php // echo $form->field($model, 'enfsanguinea') ?>

    <?php // echo $form->field($model, 'enfsanguinea_txt') ?>

    <?php // echo $form->field($model, 'tumorescancer') ?>

    <?php // echo $form->field($model, 'tumorescancer_txt') ?>

    <?php // echo $form->field($model, 'enfpsico') ?>

    <?php // echo $form->field($model, 'enfpsico_txt') ?>

    <?php // echo $form->field($model, 'gestas') ?>

    <?php // echo $form->field($model, 'partos') ?>

    <?php // echo $form->field($model, 'abortos') ?>

    <?php // echo $form->field($model, 'cesareas') ?>

    <?php // echo $form->field($model, 'menarca') ?>

    <?php // echo $form->field($model, 'ivsa') ?>

    <?php // echo $form->field($model, 'fum') ?>

    <?php // echo $form->field($model, 'mpf') ?>

    <?php // echo $form->field($model, 'doc') ?>

    <?php // echo $form->field($model, 'docma') ?>

    <?php // echo $form->field($model, 'peso') ?>

    <?php // echo $form->field($model, 'talla') ?>

    <?php // echo $form->field($model, 'imc') ?>

    <?php // echo $form->field($model, 'categoria_imc') ?>

    <?php // echo $form->field($model, 'fc') ?>

    <?php // echo $form->field($model, 'fr') ?>

    <?php // echo $form->field($model, 'temp') ?>

    <?php // echo $form->field($model, 'ta') ?>

    <?php // echo $form->field($model, 'ta_diastolica') ?>

    <?php // echo $form->field($model, 'caries_rd') ?>

    <?php // echo $form->field($model, 'inspeccion') ?>

    <?php // echo $form->field($model, 'inspeccion_otros') ?>

    <?php // echo $form->field($model, 'txt_inspeccion_otros') ?>

    <?php // echo $form->field($model, 'cabeza') ?>

    <?php // echo $form->field($model, 'cabeza_otros') ?>

    <?php // echo $form->field($model, 'txt_cabeza_otros') ?>

    <?php // echo $form->field($model, 'oidos') ?>

    <?php // echo $form->field($model, 'oidos_otros') ?>

    <?php // echo $form->field($model, 'txt_oidos_otros') ?>

    <?php // echo $form->field($model, 'ojos') ?>

    <?php // echo $form->field($model, 'ojos_otros') ?>

    <?php // echo $form->field($model, 'txt_ojos_otros') ?>

    <?php // echo $form->field($model, 'sLentes') ?>

    <?php // echo $form->field($model, 'sLentesD') ?>

    <?php // echo $form->field($model, 'cLentes') ?>

    <?php // echo $form->field($model, 'cLentesD') ?>

    <?php // echo $form->field($model, 'Rlentes') ?>

    <?php // echo $form->field($model, 'Ulentes') ?>

    <?php // echo $form->field($model, 'boca') ?>

    <?php // echo $form->field($model, 'boca_otros') ?>

    <?php // echo $form->field($model, 'txt_boca_otros') ?>

    <?php // echo $form->field($model, 'cuello') ?>

    <?php // echo $form->field($model, 'cuello_otros') ?>

    <?php // echo $form->field($model, 'txt_cuello_otros') ?>

    <?php // echo $form->field($model, 'torax') ?>

    <?php // echo $form->field($model, 'torax_otros') ?>

    <?php // echo $form->field($model, 'txt_torax_otros') ?>

    <?php // echo $form->field($model, 'abdomen') ?>

    <?php // echo $form->field($model, 'abdomen_otros') ?>

    <?php // echo $form->field($model, 'txt_abdomen_otros') ?>

    <?php // echo $form->field($model, 'superior') ?>

    <?php // echo $form->field($model, 'miembrossup_otros') ?>

    <?php // echo $form->field($model, 'txt_miembrossup_otros') ?>

    <?php // echo $form->field($model, 'inferior') ?>

    <?php // echo $form->field($model, 'miembrosinf_otros') ?>

    <?php // echo $form->field($model, 'txt_miembrosinf_otros') ?>

    <?php // echo $form->field($model, 'columna') ?>

    <?php // echo $form->field($model, 'columna_otros') ?>

    <?php // echo $form->field($model, 'txt_columna_otros') ?>

    <?php // echo $form->field($model, 'txtneurologicos') ?>

    <?php // echo $form->field($model, 'neurologicos_otros') ?>

    <?php // echo $form->field($model, 'txt_neurologicos_otros') ?>

    <?php // echo $form->field($model, 'diagnostico') ?>

    <?php // echo $form->field($model, 'comentarios') ?>

    <?php // echo $form->field($model, 'conclusion') ?>

    <?php // echo $form->field($model, 'medico') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'firma_medicolaboral') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
