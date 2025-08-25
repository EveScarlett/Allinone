<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Insumos $model */

$this->title = $model->nombre_comercial;
$this->params['breadcrumbs'][] = ['label' => 'Insumos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="insumos-view">

    <div class="container">
        <h1 class="title1"><?= Html::encode($this->title) ?></h1>

        <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'id_empresa',
            'tipo',
            'nombre_comercial',
            'nombre_generico',
            'foto',
            'concentracion',
            'fabricante',
            'formula',
            'condiciones_conservacion',
            'id_presentacion',
            'id_unidad',
            'cantidad',
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