<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Almacen $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Almacens', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="almacen-view">

    <div class="container">
        <h1 class="title1"><?= Html::encode($this->title) ?></h1>

        <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'id_empresa',
            'id_consultorio',
            'id_insumo',
            'stock',
            'stock_unidad',
            'update_date',
            'update_user',
        ],
    ]) ?>
    </div>
</div>