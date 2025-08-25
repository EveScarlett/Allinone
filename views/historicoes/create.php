<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Historicoes $model */

$this->title = Yii::t('app', 'Create Historicoes');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Historicoes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="historicoes-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
