<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Trabajadores $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Trabajadores'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="trabajadores-view">

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
            'tipo_registro',
            'id_cargamasiva',
            'id_empresa',
            'tipo_examen',
            'nombre',
            'apellidos',
            'foto',
            'sexo',
            'estado_civil',
            'fecha_nacimiento',
            'edad',
            'turno',
            'nivel_lectura',
            'nivel_escritura',
            'escolaridad',
            'grupo',
            'rh',
            'num_imss',
            'curp',
            'rfc',
            'correo',
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
            'motivo_baja',
            'antiguedad',
            'antiguedad_dias',
            'antiguedad_meses',
            'antiguedad_anios',
            'ruta',
            'parada',
            'id_puesto',
            'id_area',
            'fecha_iniciop',
            'fecha_finp',
            'teamleader',
            'talla_cabeza',
            'talla_general',
            'talla_superior',
            'talla_inferior',
            'talla_manos',
            'talla_pies',
            'personalidad',
            'dato_extra1',
            'dato_extra2',
            'dato_extra3',
            'dato_extra4',
            'dato_extra5',
            'dato_extra6',
            'dato_extra7',
            'dato_extra8',
            'dato_extra9',
            'dato_extra10',
            'create_date',
            'create_user',
            'update_date',
            'update_user',
            'delete_date',
            'delete_user',
            'status_documentos',
            'status',
            'id_link',
            'soft_delete',
            'origen_extraccion',
            'id_origen',
            'hidden',
        ],
    ]) ?>

</div>
