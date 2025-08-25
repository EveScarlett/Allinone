<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Cuestionario $model */

$this->title = 'Actualización de datos';
$this->params['breadcrumbs'][] = ['label' => 'Cuestionarios', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Actualizacion';
?>
<div class="cuestionario-update">

    <h3 class="label-secondary">
        <?= Html::encode($this->title) ?>
    </h3>

    <?= $this->render('_formUpdate', [
        'model' => $model,
        'm_pacientes' => $m_pacientes,
        'm_medicos' => $m_medicos
        //'m_detalle' => $m_detalle
    ]) ?>

</div>
