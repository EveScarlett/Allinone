<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Firmas $model */

$this->title = Yii::t('app', 'Nueva Firma Medico Laboral');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Firmas Medico Laboral'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="firmas-create">

    <div class="container">
        <h1 class="title1"><?= Html::encode($this->title) ?></h1>

        <?= $this->render('_form', [
            'model' => $model,
            ]) ?>
    </div>


</div>