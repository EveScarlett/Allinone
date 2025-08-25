<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\ExtraccionBd $model */

$this->title = Yii::t('app', 'Create Extraccion Bd');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Extraccion Bds'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="extraccion-bd-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
