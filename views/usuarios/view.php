<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Usuarios $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Usuarios'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="usuarios-view">

    <div class="container">
        <h1 class="title1"><?= Html::encode($this->title) ?></h1>

        <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'username',
            'password',
            'rol',
            'firma',
            'authKey',
            'accessToken',
            'id_firma',
            'id_empresa',
            'foto',
            'empresas_todos',
            'status',
        ],
    ]) ?>
    </div>
</div>