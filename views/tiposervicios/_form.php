<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\color\ColorInput;
use kartik\select2\Select2;

/** @var yii\web\View $this */
/** @var app\models\TipoServicios $model */
/** @var yii\widgets\ActiveForm $form */

?>

<div class="tipo-servicios-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row my-3">
        <div class="col-lg-4">
            <?= $form->field($model, 'nombre')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);']) ?>
        </div>
        <div class="col-lg-2">
            <?= $form->field($model, 'status')->widget(Select2::classname(), [
                    'data' => ['1'=>'Activo','2'=>'Baja'],
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ])->label(); ?>
        </div>
    </div>


    <div class="row mt-5">
        <div class="col-lg-4 d-grid">
            <?= Html::submitButton('Guardar <i class="bi bi-hdd"></i>', ['class' => 'btn btn-primary btnnew btn-block']) ?>
        </div>
    </div>


    <?php ActiveForm::end(); ?>

</div>