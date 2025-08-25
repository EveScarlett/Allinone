<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\InsumosSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="insumos-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'id_empresa') ?>

    <?= $form->field($model, 'tipo') ?>

    <?= $form->field($model, 'nombre_comercial') ?>

    <?= $form->field($model, 'nombre_generico') ?>

    <?php // echo $form->field($model, 'foto') ?>

    <?php // echo $form->field($model, 'concentracion') ?>

    <?php // echo $form->field($model, 'fabricante') ?>

    <?php // echo $form->field($model, 'formula') ?>

    <?php // echo $form->field($model, 'condiciones_conservacion') ?>

    <?php // echo $form->field($model, 'id_presentacion') ?>

    <?php // echo $form->field($model, 'id_unidad') ?>

    <?php // echo $form->field($model, 'cantidad') ?>

    <?php // echo $form->field($model, 'create_date') ?>

    <?php // echo $form->field($model, 'create_user') ?>

    <?php // echo $form->field($model, 'update_date') ?>

    <?php // echo $form->field($model, 'update_user') ?>

    <?php // echo $form->field($model, 'delete_date') ?>

    <?php // echo $form->field($model, 'delete_user') ?>

    <?php // echo $form->field($model, 'status') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
