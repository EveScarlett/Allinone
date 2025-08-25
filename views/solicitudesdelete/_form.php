<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\SolicitudesDelete $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="solicitudes-delete-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id')->textInput() ?>

    <?= $form->field($model, 'status_solicitud')->textInput() ?>

    <?= $form->field($model, 'modelo')->textInput() ?>

    <?= $form->field($model, 'id_modelo')->textInput() ?>

    <?= $form->field($model, 'user_solicita')->textInput() ?>

    <?= $form->field($model, 'date_solicita')->textInput() ?>

    <?= $form->field($model, 'user_aprueba')->textInput() ?>

    <?= $form->field($model, 'date_aprueba')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
