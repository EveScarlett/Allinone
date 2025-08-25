<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Trabajadores $model */

$this->title = 'Carga Masiva Trabajadores';
$this->params['breadcrumbs'][] = ['label' => 'Trabajadores', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="trabajadores-create">


    <div class="container">
        <h1 class="title1"><?= Html::encode($this->title) ?></h1>

        <?= $this->render('_formcarga', [
            'model' => $model,
            ]) ?>
    </div>


</div>