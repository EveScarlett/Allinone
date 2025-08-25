<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var app\models\Ubicaciones $model */
/** @var yii\widgets\ActiveForm $form */
?>
<?php
$icon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-badge-fill" viewBox="0 0 16 16">
<path d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2zm4.5 0a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1h-3zM8 11a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm5 2.755C12.146 12.825 10.623 12 8 12s-4.146.826-5 1.755V14a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1v-.245z"/>
</svg>';

$iconclip = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-workspace" viewBox="0 0 16 16">
<path d="M4 16s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1H4Zm4-5.95a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5Z"/>
<path d="M2 1a2 2 0 0 0-2 2v9.5A1.5 1.5 0 0 0 1.5 14h.653a5.373 5.373 0 0 1 1.066-2H1V3a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v9h-2.219c.554.654.89 1.373 1.066 2h.653a1.5 1.5 0 0 0 1.5-1.5V3a2 2 0 0 0-2-2H2Z"/>
</svg>';

$asterisco = '<span class="px-2 color11 font11"><i class="bi bi-asterisk"></i></span>';

$kpis = ['1'=>'Trabajadores','2'=>'CAL','3'=>'POE','4'=>'Programas de Salud','5'=>'Accidentes','6'=>'Incapacidades','7'=>'Consultas Clínicas','8'=>'Historias Clínicas','9'=>'Cuestionario Nórdico','10'=>'Evaluacion Antropométrica'];
?>

<div class="ubicaciones-form p-4">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row my-2 boxtitle2 p-2 rounded-4">
        <div class="col-lg-12">
            Ingrese los <span class="font500">KPI</span> (Indicador Clave de Desempeño)
            con los que desea medir el <span class="font500">% de Cumplimiento</span> de la Empresa y sus niveles organizacionales.
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <?= $form->field($model, 'id_kpi1')->widget(Select2::classname(), [
                    'data' => $kpis,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => true,
                         'dropdownParent' => '#modal-kpi'
                    ],
                    ])->label(); ?>
        </div>

        <div class="col-lg-12 mt-3">
            <?= $form->field($model, 'id_kpi2')->widget(Select2::classname(), [
                    'data' => $kpis,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => true,
                         'dropdownParent' => '#modal-kpi'
                    ],
                    ])->label(); ?>
        </div>

        <div class="col-lg-12 mt-3">
            <?= $form->field($model, 'id_kpi3')->widget(Select2::classname(), [
                    'data' => $kpis,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => true,
                         'dropdownParent' => '#modal-kpi'
                    ],
                    ])->label(); ?>
        </div>

        <div class="col-lg-12 mt-3">
            <?= $form->field($model, 'id_kpi4')->widget(Select2::classname(), [
                    'data' => $kpis,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => true,
                         'dropdownParent' => '#modal-kpi'
                    ],
                    ])->label(); ?>
        </div>

        <div class="col-lg-12 mt-3">
            <?= $form->field($model, 'id_kpi5')->widget(Select2::classname(), [
                    'data' => $kpis,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => true,
                         'dropdownParent' => '#modal-kpi'
                    ],
                    ])->label(); ?>
        </div>

        <div class="col-lg-12 mt-3">
            <?= $form->field($model, 'id_kpi6')->widget(Select2::classname(), [
                    'data' => $kpis,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => true,
                         'dropdownParent' => '#modal-kpi'
                    ],
                    ])->label(); ?>
        </div>

        <div class="col-lg-12 mt-3">
            <?= $form->field($model, 'id_kpi7')->widget(Select2::classname(), [
                    'data' => $kpis,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => true,
                         'dropdownParent' => '#modal-kpi'
                    ],
                    ])->label(); ?>
        </div>

        <div class="col-lg-12 mt-3">
            <?= $form->field($model, 'id_kpi8')->widget(Select2::classname(), [
                    'data' => $kpis,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => true,
                         'dropdownParent' => '#modal-kpi'
                    ],
                    ])->label(); ?>
        </div>

        <div class="col-lg-12 mt-3">
            <?= $form->field($model, 'id_kpi9')->widget(Select2::classname(), [
                    'data' => $kpis,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => true,
                         'dropdownParent' => '#modal-kpi'
                    ],
                    ])->label(); ?>
        </div>

        <div class="col-lg-12 mt-3">
            <?= $form->field($model, 'id_kpi10')->widget(Select2::classname(), [
                    'data' => $kpis,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => true,
                         'dropdownParent' => '#modal-kpi'
                    ],
                    ])->label(); ?>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-lg-12 d-grid">
            <?= Html::submitButton('Guardar <i class="bi bi-hdd"></i>', ['class' => 'btn btn-primary btnnew btn-block']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>