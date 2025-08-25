<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Cieconsulta $model */

$this->title = Yii::t('app', 'Create Cieconsulta');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Cieconsultas'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cieconsulta-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
