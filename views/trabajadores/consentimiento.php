<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Usuarios $model */
\yii\web\YiiAsset::register($this);
?>
<div class="usuarios-view">

    <?php $form = ActiveForm::begin(['id'=>'formOHC',
'options' => ['enctype' => 'multipart/form-data'],]); ?>

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