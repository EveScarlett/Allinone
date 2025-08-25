<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Mantenimientos $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Mantenimientos'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="mantenimientos-view">

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
            'clave',
            'id_empresa',
            'tipo_mantenimiento',
            'id_maquina',
            'realiza_mantenimiento',
            'descripcion:ntext',
            'status_maquina',
            'proximo_mantenimiento',
            'nombre_firma1',
            'firma1:ntext',
            'nombre_firma2',
            'firma2:ntext',
            'nombre_firma3',
            'firma3:ntext',
            'status',
            'create_date',
            'create_user',
            'update_date',
            'update_user',
        ],
    ]) ?>

</div>
