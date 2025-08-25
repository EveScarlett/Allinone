<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Insumostockmin $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Insumostockmins', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="insumostockmin-view">

    <div class="container">
        <h1 class="title1"><?= Html::encode($this->title) ?></h1>

        <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'id_insumo',
            'id_consultorio',
            'stock',
            'stock_unidad',
        ],
    ]) ?>
    </div>
</div>