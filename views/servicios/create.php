<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Servicios $model */

$this->title = Yii::t('app', 'Nuevo Estudio');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Estudios'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="servicios-create">

    <div class="container">
        <h1 class="title1"><?= Html::encode($this->title) ?></h1>

        <?= $this->render('_form', [
            'model' => $model,
            ]) ?>
    </div>

</div>