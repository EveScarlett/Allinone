<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Consultas $model */

$this->title = Yii::t('app', 'Actualizar Incapacidad');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Incapacidades'), 'url' => ['incapacidades']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="consultas-create">

    <div class="container">
        <h1 class="title1"><?= Html::encode($this->title) ?></h1>

        <?= $this->render('_formincapacidad', [
            'model' => $model,
            ]) ?>
    </div>

</div>