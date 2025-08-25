<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\TipoServicios $model */

$this->title = Yii::t('app', 'Nueva Categoría de Estudio');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tipo de Estudios'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tipo-servicios-create">

    <div class="container">
        <h1 class="title1"><?= Html::encode($this->title) ?></h1>

        <?= $this->render('_form', [
            'model' => $model,
            ]) ?>
    </div>

</div>