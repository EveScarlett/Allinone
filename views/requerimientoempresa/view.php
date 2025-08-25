<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Empresas $model */

$this->title = $model->comercial;
$this->params['breadcrumbs'][] = ['label' => 'Empresas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="empresas-view">

    <div class="container">
        <h1 class="title1"><?= Html::encode($this->title) ?></h1>

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