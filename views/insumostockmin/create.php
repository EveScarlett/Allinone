<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Insumostockmin $model */

$this->title = 'Definir Stock Mínimo';
$this->params['breadcrumbs'][] = ['label' => 'Insumostockmins', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="insumostockmin-create">

    <div class="container">
        <h1 class="title1"><?= Html::encode($this->title) ?></h1>

        <?= $this->render('_form', [
        'model' => $model,
        'tipo'=>$tipo
    ]) ?>
    </div>

</div>