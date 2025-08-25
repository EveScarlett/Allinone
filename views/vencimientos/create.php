<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Trabajadores $model */

$this->title = Yii::t('app', 'Create Trabajadores');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Trabajadores'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="trabajadores-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
