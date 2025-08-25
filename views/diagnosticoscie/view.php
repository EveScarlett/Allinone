<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Diagnosticoscie $model */

$this->title = $model->diagnostico;
$this->params['breadcrumbs'][] = ['label' => 'Diagnosticoscies', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="diagnosticoscie-view">

    <div class="container">
        <h1 class="title1"><?= Html::encode($this->title) ?></h1>

        <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'clave',
            'clave_epi',
            'diagnostico',
            'cie_version',
        ],
    ]) ?>
    </div>
</div>