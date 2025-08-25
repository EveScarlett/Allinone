<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\TipoServicios $model */

$this->title = Yii::t('app', 'Ordenar Categorias de Estudios', [
    
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Categorias de Estudio'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Ordenar Categoria');
?>
<div class="tipo-servicios-update">

    <div class="container">
        <h1 class="title1"><?= Html::encode($this->title)?></h1>

        <?= $this->render('_formsortcat', [
           
            ]) ?>
    </div>

</div>