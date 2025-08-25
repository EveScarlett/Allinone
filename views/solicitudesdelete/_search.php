<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\SolicitudesDeleteSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="solicitudes-delete-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'status_solicitud') ?>

    <?= $form->field($model, 'modelo') ?>

    <?= $form->field($model, 'id_modelo') ?>

    <?= $form->field($model, 'user_solicita') ?>

    <?php // echo $form->field($model, 'date_solicita') ?>

    <?php // echo $form->field($model, 'user_aprueba') ?>

    <?php // echo $form->field($model, 'date_aprueba') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
