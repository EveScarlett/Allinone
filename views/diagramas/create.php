<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Empresas $model */

$this->title = Yii::t('app', 'Create Empresas');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Empresas'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="empresas-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
