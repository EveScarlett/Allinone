<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Insumos $model */

if($tipo == 1){
    $this->title = 'Actualizar Medicamento';
} else{
    $this->title = 'Actualizar Equipo de Protección Personal';
}

$modulo = 'de Medicamentos';
if($tipo == 2){
    $modulo = 'de Equipo de Protección';
}

$this->params['breadcrumbs'][] = ['label' => 'Catálogos '.$modulo, 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="insumos-update">

    <div class="container">
        <h1 class="title1 text-uppercase"><?= Html::encode($this->title) ?></h1>

        <?= $this->render('_form', [
            'model' => $model,
            'tipo'=>$tipo
            ]) ?>
    </div>

</div>