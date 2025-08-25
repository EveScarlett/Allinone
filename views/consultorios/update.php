<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Consultorios $model */

$this->title = Yii::t('app', 'Actualizar Consultorio: {name}', [
    'name' => $model->id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Consultorios'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->consultorio, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Actualizar');
?>
<div class="consultorios-update">

    <div class="container">
        <h1 class="title1 text-uppercase"><?= Html::encode($this->title) ?></h1>

        <?= $this->render('_form', [
            'model' => $model,
            ]) ?>
    </div>

</div>