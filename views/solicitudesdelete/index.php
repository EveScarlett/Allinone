<?php

use app\models\SolicitudesDelete;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\SolicitudesDeleteSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Solicitudes de Eliminación');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="solicitudes-delete-index">

    <h1 class="title1"><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'headerRowOptions' =>['class' => 'text-label shadow-sm text-uppercase control-label border-0 small'],
        'tableOptions' => ['class' => 'table table-hover table-sm small'],
        'rowOptions' => ['class' => 'font-12 text-600 bg-white shadow-sm small'],
        'pager' => [
            'class' => 'yii\bootstrap5\LinkPager'
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'modelo',
            'id_modelo',
            'user_solicita',
            'date_solicita',
            'user_aprueba',
            'date_aprueba',
            'status_solicitud',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, SolicitudesDelete $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>