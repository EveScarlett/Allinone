<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Roles $model */

$this->title = Yii::t('app', 'Actualizar Rol: {name}', [
    'name' => $model->nombre,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Roles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Actualizar');
?>
<div class="roles-update">

    <div class="container-fluid">
        <h1 class="title1 text-uppercase"><?= Html::encode($this->title) ?></h1>

        <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    </div>
</div>