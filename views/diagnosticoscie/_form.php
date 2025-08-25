<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Diagnosticoscie $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="diagnosticoscie-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row my-3">

        <div class="col-lg-6">
            <?= $form->field($model, 'diagnostico')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-lg-3 offset-lg-1">
            <?= $form->field($model, 'clave')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-lg-2">
            <?= $form->field($model, 'cie_version')->textInput(['type'=>'number','min'=>0,'max'=>20,'maxlength' => true]) ?>
        </div>

    </div>

    <div class="row mt-5">
        <div class="col-lg-4 d-grid">
            <?= Html::submitButton('Guardar <i class="bi bi-hdd"></i>', ['class' => 'btn btn-primary btnnew btn-block']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>