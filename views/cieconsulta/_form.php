<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Cieconsulta $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="cieconsulta-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id')->textInput() ?>

    <?= $form->field($model, 'id_empresa')->textInput() ?>

    <?= $form->field($model, 'id_nivel1')->textInput() ?>

    <?= $form->field($model, 'id_nivel2')->textInput() ?>

    <?= $form->field($model, 'id_nivel3')->textInput() ?>

    <?= $form->field($model, 'id_nivel4')->textInput() ?>

    <?= $form->field($model, 'id_consulta')->textInput() ?>

    <?= $form->field($model, 'id_cie')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
