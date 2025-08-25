<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Consultas $model */

$this->title = Yii::t('app', 'Nueva Consulta Médica');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Consultas Médicas'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="consultas-create">

    <div class="container">
        <h1 class="title1"><?= Html::encode($this->title) ?></h1>

        <?= $this->render('_form', [
            'model' => $model,
            ]) ?>
    </div>

</div>