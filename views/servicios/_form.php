<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use app\models\TipoServicios;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var app\models\Servicios $model */
/** @var yii\widgets\ActiveForm $form */
$tipos = ArrayHelper::map(TipoServicios::find()->orderBy('nombre')->all(), 'id', 'nombre');
?>

<div class="servicios-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row my-3">
        <div class="col-lg-4">
            <?= $form->field($model, 'id_tiposervicio')->widget(Select2::classname(), [
                    'data' => $tipos,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
        </div>
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
    <div class="row my-3" style="display:none;">
        <div class="col-lg-4">
            <?= $form->field($model, 'orden')->textInput(['type'=>'number']) ?>
        </div>
    </div>



    <div class="row mt-5">
        <div class="col-lg-4 d-grid">
            <?= Html::submitButton('Guardar <i class="bi bi-hdd"></i>', ['class' => 'btn btn-primary btnnew btn-block']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>