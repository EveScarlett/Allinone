<?php

use app\models\ExtraccionBd;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\ExtraccionBd2Search $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Importar Registros Bases de Datos');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="extraccion-bd-index">

<h1 class="title1"><?= Html::encode($this->title) ?></h1>

    <div class="row mb-3">
        <div class="col-lg-2">
            <?= Html::a(Yii::t('app', 'Importar Trabajadores'), ['trabajadores'], ['class' => 'btn btn-primary btnnew btn-block']) ?>
        </div>
        <div class="col-lg-2">
            <?= Html::a(Yii::t('app', 'Importar POES'), ['poes'], ['class' => 'btn btn-primary btnnew btn-block']) ?>
        </div>
        <div class="col-lg-2">
            <?= Html::a(Yii::t('app', 'Importar Historias Clínicas'), ['historias'], ['class' => 'btn btn-primary btnnew btn-block']) ?>
        </div>
        <div class="col-lg-2">
            <?= Html::a(Yii::t('app', 'Importar Cuestionarios'), ['cuestionarios'], ['class' => 'btn btn-primary btnnew btn-block']) ?>
        </div>
        <div class="col-lg-2">
            <?= Html::a(Yii::t('app', 'Importar Evolucion POE'), ['evolucionpoes'], ['class' => 'btn btn-primary btnnew btn-block']) ?>
        </div>
        <div class="col-lg-2">
            <?= Html::a(Yii::t('app', 'Importar Documentos POE'), ['poesdocumentos'], ['class' => 'btn btn-primary btnnew btn-block']) ?>
        </div>
        <div class="col-lg-2">
            <?= Html::a(Yii::t('app', 'Importar Documentos HC-CAL'), ['historiasdocumentos'], ['class' => 'btn btn-primary btnnew btn-block']) ?>
        </div>
    </div>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

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
            'base_datos',
            'tabla',
            'create_date',
            'create_user',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, ExtraccionBd $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>