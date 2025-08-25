<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Cargasmasivas $model */

$this->title = Yii::t('app', 'Nueva Carga Masiva - POE');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Cargas Masivas - POES'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cargasmasivas-create">

    <div class="container">
        <h1 class="title1"><?= Html::encode($this->title) ?></h1>

        <?= $this->render('_formpoe', [
            'model' => $model,
            ]) ?>
    </div>

</div>