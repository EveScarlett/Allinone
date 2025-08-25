<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Maquinaria $model */

$this->title = Yii::t('app', 'Nueva Maquinaria');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Maquinarias'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="maquinaria-create">

    <div class="container">
        <h1 class="title1"><?= Html::encode($this->title) ?></h1>

        <?= $this->render('_form', [
            'model' => $model,
            ]) ?>
    </div>

</div>