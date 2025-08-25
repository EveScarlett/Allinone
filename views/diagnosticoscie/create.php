<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Diagnosticoscie $model */

$this->title = 'Nuevo Diagnóstico CIE';
$this->params['breadcrumbs'][] = ['label' => 'Diagnosticoscies', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="diagnosticoscie-create">

    <div class="container">
        <h1 class="title1 text-uppercase"><?= Html::encode($this->title) ?></h1>

        <?= $this->render('_form', [
            'model' => $model,
            ]) ?>
    </div>

</div>