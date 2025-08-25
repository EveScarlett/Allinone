<?php

use app\models\Cieconsulta;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\CieconsultaSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Cieconsultas');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cieconsulta-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Cieconsulta'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'id_empresa',
            'id_nivel1',
            'id_nivel2',
            'id_nivel3',
            //'id_nivel4',
            //'id_consulta',
            //'id_cie',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Cieconsulta $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
