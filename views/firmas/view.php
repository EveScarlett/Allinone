<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Firmas $model */

$this->title = $model->nombre;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Firmas'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="firmas-view">

    <div class="container">
        <h1 class="title1"><?= Html::encode($this->title) ?></h1>

        <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'nombre',
            'universidad',
            'cedula',
            'firma',
            'titulo',
            'abreviado_titulo',
            'registro_sanitario',
            'fecha_inicio',
            'fecha_fin',
            'status',
        ],
    ]) ?>
    </div>
</div>