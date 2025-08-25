<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Mantenimientos $model */

$this->title = Yii::t('app', 'Nuevo Mantenimiento');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Histórico de Mantenimiento'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mantenimientos-create">

    <div class="container-fluid">
        <h1 class="title1"><?= Html::encode($this->title) ?></h1>

        <?= $this->render('_form', [
            'model' => $model,
            ]) ?>
    </div>

</div>