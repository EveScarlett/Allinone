<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\AlmacenSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="almacen-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'id_empresa') ?>

    <?= $form->field($model, 'id_consultorio') ?>

    <?= $form->field($model, 'id_insumo') ?>

    <?= $form->field($model, 'stock') ?>

    <?php // echo $form->field($model, 'stock_unidad') ?>

    <?php // echo $form->field($model, 'update_date') ?>

    <?php // echo $form->field($model, 'update_user') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
