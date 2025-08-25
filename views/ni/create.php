<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Ni $model */

$this->title = Yii::t('app', 'Crear Requisitos Nuevo Ingreso');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Nuevo Ingreso'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ni-create">

    <div class="container">
        <h1 class="title1"><?= Html::encode($this->title) ?></h1>

        <?= $this->render('_form', [
            'model' => $model,
            ]) ?>
    </div>

</div>
