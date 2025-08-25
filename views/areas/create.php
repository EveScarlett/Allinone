<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Areas $model */

$this->title = Yii::t('app', 'Nueva Área');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Áreas'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="areas-create">

    <div class="container">
        <h1 class="title1 text-uppercase"><?= Html::encode($this->title) ?></h1>

        <?= $this->render('_form', [
            'model' => $model,
            ]) ?>
    </div>

</div>
