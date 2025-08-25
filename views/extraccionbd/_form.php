<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\ExtraccionBd $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="extraccion-bd-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'base_datos')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tabla')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'create_date')->textInput() ?>

    <?= $form->field($model, 'create_user')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
