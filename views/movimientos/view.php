<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Movimientos $model */

$this->title = $model->folio;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Movimientos'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="movimientos-view">
    <div class="container">
        <h1 class="title1"><?= Html::encode($this->title) ?></h1>

        <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'id_empresa',
            'folio',
            'tipo',
            'id_consultorio',
            'id_consultorio2',
            'create_date',
            'create_user',
            'update_date',
            'update_user',
            'delete_date',
            'delete_user',
            'observaciones:ntext',
            'movimiento_relacionados',
            'status',
        ],
    ]) ?>
    </div>
</div>