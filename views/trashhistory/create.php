<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Trashhistory $model */

$this->title = Yii::t('app', 'Create Trashhistory');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Trashhistories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="trashhistory-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
