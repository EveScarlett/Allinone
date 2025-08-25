<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Poes $model */

$this->title = $model->nombre.' '.$model->apellidos.'|'.$model->anio;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Exámenes Médicos'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="poes-view">

    <div class="container">
        <h1 class="title1"><?= Html::encode($this->title) ?></h1>

        <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'id_empresa',
            'id_trabajador',
            'nombre',
            'apellidos',
            'sexo',
            'fecha_nacimiento',
            'anio',
            'num_imss',
            'id_puesto',
            'id_ubicacion',
            'observaciones:ntext',
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
</div>