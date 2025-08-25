<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Insumos $model */

if($tipo == 1){
    $this->title = 'Nuevo Medicamento';
} else{
    $this->title = 'Nuevo Equipo de Protección Personal';
}
$this->params['breadcrumbs'][] = ['label' => 'Insumos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="insumos-create">

    <div class="container">
        <h1 class="title1 text-uppercase"><?= Html::encode($this->title) ?></h1>

        <?= $this->render('_form', [
            'model' => $model,
            'tipo'=>$tipo
            ]) ?>
    </div>

</div>