<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Trashhistory $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="trashhistory-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'model')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'id_model')->textInput() ?>

    <?= $form->field($model, 'deleted_date')->textInput() ?>

    <?= $form->field($model, 'deleted_user')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
