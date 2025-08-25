<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Trabajadores $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Trabajadores', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="trabajadores-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'id_empresa',
            'tipo_examen',
            'nombre',
            'apellidos',
            'foto',
            'sexo',
            'estado_civil',
            'fecha_nacimiento',
            'edad',
            'nivel_lectura',
            'nivel_escritura',
            'escolaridad',
            'grupo',
            'rh',
            'num_imss',
            'celular',
            'contacto_emergencia',
            'direccion',
            'colonia',
            'pais',
            'estado',
            'ciudad',
            'municipio',
            'cp',
            'num_trabajador',
            'tipo_contratacion',
            'fecha_contratacion',
            'fecha_baja',
            'antiguedad',
            'ruta',
            'parada',
            'create_date',
            'create_user',
            'update_date',
            'update_user',
            'delete_date',
            'delete_user',
            'status',
        ],
    ]) ?>

</div>
