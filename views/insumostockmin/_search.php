<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\InsumostockminSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="insumostockmin-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'id_insumo') ?>

    <?= $form->field($model, 'id_consultorio') ?>

    <?= $form->field($model, 'stock') ?>

    <?= $form->field($model, 'stock_unidad') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
