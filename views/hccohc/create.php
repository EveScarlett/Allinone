<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\HccOhc $model */

$this->title = 'HC Subsecuente';
$this->params['breadcrumbs'][] = ['label' => 'Historias Clínicas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hcc-ohc-create">

    <div class="container-fluid">
        <h1 class="title1"><?= Html::encode($this->title) ?></h1>

        <?= $this->render('_form', [
            'model' => $model,
            'msj'=>$msj,
            'hc_anterior'=>$hc_anterior
            ]) ?>
    </div>

</div>