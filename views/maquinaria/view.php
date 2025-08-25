<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Maquinaria $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Maquinarias'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="maquinaria-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'id_empresa',
            'clave',
            'foto',
            'maquina',
            'fabricante',
            'modelo',
            'marca',
            'combustible',
            'id_ubicacion',
            'id_area',
            'peso',
            'altura',
            'ancho',
            'largo',
            'alto',
            'detalles_tecnicos',
            'funcion',
            'qr',
            'status',
            'create_date',
            'create_user',
            'update_date',
            'update_user',
        ],
    ]) ?>

</div>
