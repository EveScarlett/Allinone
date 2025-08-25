<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Turnos $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Turnos'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="turnos-view">

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
            'turno',
            'lunes_inicio',
            'lunes_fin',
            'martes_inicio',
            'martes_fin',
            'miercoles_inicio',
            'miercoles_fin',
            'jueves_inicio',
            'jueves_fin',
            'viernes_inicio',
            'viernes_fin',
            'sabado_inicio',
            'sabado_fin',
            'domingo_inicio',
            'domingo_fin',
            'requiere_enfermeros',
            'requiere_medicos',
            'requiere_extras',
            'cantidad_enfermeros',
            'cantidad_medicos',
            'cantidad_extras',
            'orden',
            'status',
            'soft_delete',
        ],
    ]) ?>

</div>
