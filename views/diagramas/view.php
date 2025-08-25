<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Empresas $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Empresas'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="empresas-view">

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
            'razon',
            'comercial',
            'abreviacion',
            'rfc',
            'pais',
            'estado',
            'ciudad',
            'municipio',
            'logo',
            'contacto',
            'telefono',
            'correo',
            'horario',
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
            'create_date',
            'create_user',
            'update_date',
            'update_user',
            'delete_date',
            'delete_user',
            'medico_laboral',
            'status',
            'soft_delete',
        ],
    ]) ?>

</div>
