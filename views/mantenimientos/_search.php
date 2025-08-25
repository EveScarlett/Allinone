<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\MantenimientosSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="mantenimientos-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'clave') ?>

    <?= $form->field($model, 'id_empresa') ?>

    <?= $form->field($model, 'tipo_mantenimiento') ?>

    <?= $form->field($model, 'id_maquina') ?>

    <?php // echo $form->field($model, 'realiza_mantenimiento') ?>

    <?php // echo $form->field($model, 'descripcion') ?>

    <?php // echo $form->field($model, 'status_maquina') ?>

    <?php // echo $form->field($model, 'proximo_mantenimiento') ?>

    <?php // echo $form->field($model, 'nombre_firma1') ?>

    <?php // echo $form->field($model, 'firma1') ?>

    <?php // echo $form->field($model, 'nombre_firma2') ?>

    <?php // echo $form->field($model, 'firma2') ?>

    <?php // echo $form->field($model, 'nombre_firma3') ?>

    <?php // echo $form->field($model, 'firma3') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'create_date') ?>

    <?php // echo $form->field($model, 'create_user') ?>

    <?php // echo $form->field($model, 'update_date') ?>

    <?php // echo $form->field($model, 'update_user') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
