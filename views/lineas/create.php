<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Lineas $model */

$this->title = Yii::t('app', 'Nueva Linea');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Lineas'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lineas-create">

     <div class="container">
        <h1 class="title1 text-uppercase"><?= Html::encode($this->title) ?></h1>

        <?= $this->render('_form', [
            'model' => $model,
            ]) ?>
    </div>

</div>
