<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Ordenespoes $model */

$this->title = Yii::t('app', 'Nueva Orden de Trabajo - POE´s');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Ordenes de Trabajo - POE'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ordenespoes-create">

    <div class="container">
        <h1 class="title1"><?= Html::encode($this->title) ?></h1>

        <?= $this->render('_form', [
            'model' => $model,
            ]) ?>
    </div>

</div>