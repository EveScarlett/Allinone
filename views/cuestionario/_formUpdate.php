<?php

use app\models\Areas;
use app\models\Preguntas;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
// use yii\widgets\ActiveForm;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Cuestionario $model */
/** @var yii\widgets\ActiveForm $form */

$preguntas = Preguntas::find()->where(['status' => 1])->all();
$areas = Areas::find()->where(['status' => 1])->all();

$arr_preguntas = ArrayHelper::map($preguntas, 'id', 'pregunta');
$arr_areas = ArrayHelper::map($areas, 'id', 'nombre');

$arr_medicos = ArrayHelper::map($m_medicos,'id','nombre');

// Verificar si es medico u otro usuario --
// $_usuario = Yii::$app->user->identity->nivel;

// if ($_usuario === 3) {
//     $this->registerJs("
//         let select_medico = document.getElementById('cuestionario-id_medico');
//         select_medico.value = ".Yii::$app->user->identity->id.";
//         select_medico.setAttribute('disabled', true);
//     ");
// }
?>

<div class="cuestionario-form">

    <?php $form = ActiveForm::begin(
        [
            //'id' => 'questionnaire-form',
            'options' => ['class' => 'form-horizontal'],
        ]
    ); ?>
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 mt-3">
                <?= $form->field($model, 'id_medico')->widget(Select2::className(),[
                        'data' => $arr_medicos,
                        'size' => 'sm',
                        'options' => [
                            'class' => "form-select form-select-sm",
                            'prompt' => '-- Seleccione el medico --',
                            //'onchange' => 'loadWorkerData(this)'
                        ]
                ])->label("Medico:"); ?>
            </div>
            <!-- <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 mt-3">
                <?= $form->field($model, 'nombre_medico', ['options' => ['tag' => false]])->textInput(['class' => "form-control form-control-sm", 'placeholder' => 'Nombre y apellidos'])->label("Nombre del medico:") ?>
            </div> -->

            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 mt-3">
                <?= $form->field($model, 'nombre_empresa')->textInput(['class' => "form-control form-control-sm", 'placeholder' => 'Ingrese el nombre de la empresa'])->label("Nombre de la empresa:") ?>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 mt-3">
                <?= $form->field($m_pacientes, 'nombre')->textInput(['class' => "form-control form-control-sm", 'placeholder' => 'Ingrese su nombre(s)', 'disabled' => true])->label("Nombre del empleado:") ?>
            </div>

            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 mt-3">
                <?= $form->field($m_pacientes, 'apellidos')->textInput(['class' => "form-control form-control-sm", 'placeholder' => 'Ingrese sus apellidos', 'disabled' => true])->label("Apellidos del empleado:") ?>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 mt-3">
                <?= $form->field($m_pacientes, 'fecha_nacimiento')->textInput(['type' => 'date', 'class' => "form-control form-control-sm", 'onchange' => 'getAge(this)'])->label('Fecha de nacimiento:') ?>
            </div>

            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 mt-3">
                <?= $form->field($m_pacientes, 'edad')->textInput(['readonly' => true, 'class' => "form-control form-control-sm"])->label("Edad:") ?>
            </div>
            
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 mt-3">
                <?= $form->field($m_pacientes, 'sexo')->dropDownList(
                            [
                                'MASCULINO' => "MASCULINO",
                                'FEMENINO' => "FEMENIDO"
                            ],
                            [
                                'prompt' => '-- Seleccione --',
                                'class' => "form-select form-select-sm"
                            ]
                )->label("Sexo:"); ?>
            </div>
        </div>

        <div class="row mb-3">
            <!-- <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12 mt-3">
                < ?= $form->field($m_pacientes, 'no_empleado')->textInput(['class' => "form-control form-control-sm", 'placeholder' => 'Ingrese el numero del empleado', 'disabled' => true])->label("No. empleado:") ?>
            </div> -->

            <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12 mt-3">
                <?= $form->field($m_pacientes, 'puesto_trabajo')->textInput(['class' => "form-control form-control-sm", 'placeholder' => 'Ingrese el puesto del trabajador'])->label("Puesto:") ?>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12 mt-3">
                <?= $form->field($m_pacientes, 'area')->textInput(['class' => "form-control form-control-sm", 'placeholder' => 'Ingrese el area del trabajador'])->label("Area:") ?>
            </div>
            <!-- <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12 mt-3">
                < ?= $form->field($m_pacientes, 'linea')->textInput(['class' => "form-control form-control-sm", 'placeholder' => 'Ingrese la linea del trabajador'])->label("Linea:") ?>
            </div> -->
        </div>

    <div class="form-group mt-5">
        <?= Html::submitButton('Actualizar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>