<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Historicoes $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="historicoes-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_model')->textInput() ?>

    <?= $form->field($model, 'id_empresa')->textInput() ?>

    <?= $form->field($model, 'id_trabajador')->textInput() ?>

    <?= $form->field($model, 'id_maquina')->textInput() ?>

    <?= $form->field($model, 'status_trabajo')->textInput() ?>

    <?= $form->field($model, 'fecha_inicio')->textInput() ?>

    <?= $form->field($model, 'fecha_fin')->textInput() ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'create_date')->textInput() ?>

    <?= $form->field($model, 'create_user')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
