<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Movimientos $model */
if($tipo == 1){
    $this->title = 'Nuevo Movimiento - Medicamentos';
} else{
    $ver_medicamento = false;
    $ver_epp = true;
    $this->title = 'Nuevo Movimiento - Equipo de Protección Personal';
}

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Movimientos'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="movimientos-create">

    <div class="container">
        <h1 class="title1 text-uppercase"><?= Html::encode($this->title) ?></h1>

        <?= $this->render('_form', [
            'model' => $model,
            'tipo'=>$tipo
            ]) ?>
    </div>

</div>