<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\CieconsultaSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="cieconsulta-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'id_empresa') ?>

    <?= $form->field($model, 'id_nivel1') ?>

    <?= $form->field($model, 'id_nivel2') ?>

    <?= $form->field($model, 'id_nivel3') ?>

    <?php // echo $form->field($model, 'id_nivel4') ?>

    <?php // echo $form->field($model, 'id_consulta') ?>

    <?php // echo $form->field($model, 'id_cie') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
