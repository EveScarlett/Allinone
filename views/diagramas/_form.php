<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Empresas $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="empresas-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'razon')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'comercial')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'abreviacion')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'rfc')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pais')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'estado')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ciudad')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'municipio')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'logo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'contacto')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'telefono')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'correo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'horario')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'lunes_inicio')->textInput() ?>

    <?= $form->field($model, 'lunes_fin')->textInput() ?>

    <?= $form->field($model, 'martes_inicio')->textInput() ?>

    <?= $form->field($model, 'martes_fin')->textInput() ?>

    <?= $form->field($model, 'miercoles_inicio')->textInput() ?>

    <?= $form->field($model, 'miercoles_fin')->textInput() ?>

    <?= $form->field($model, 'jueves_inicio')->textInput() ?>

    <?= $form->field($model, 'jueves_fin')->textInput() ?>

    <?= $form->field($model, 'viernes_inicio')->textInput() ?>

    <?= $form->field($model, 'viernes_fin')->textInput() ?>

    <?= $form->field($model, 'sabado_inicio')->textInput() ?>

    <?= $form->field($model, 'sabado_fin')->textInput() ?>

    <?= $form->field($model, 'domingo_inicio')->textInput() ?>

    <?= $form->field($model, 'domingo_fin')->textInput() ?>

    <?= $form->field($model, 'create_date')->textInput() ?>

    <?= $form->field($model, 'create_user')->textInput() ?>

    <?= $form->field($model, 'update_date')->textInput() ?>

    <?= $form->field($model, 'update_user')->textInput() ?>

    <?= $form->field($model, 'delete_date')->textInput() ?>

    <?= $form->field($model, 'delete_user')->textInput() ?>

    <?= $form->field($model, 'medico_laboral')->textInput() ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
