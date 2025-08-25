<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Configuracion $model */

$this->title = Yii::t('app', 'Nueva Configuración');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Configuracions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="configuracion-create">

    <div class="container">
        <h1 class="title1"><?= Html::encode($this->title) ?></h1>

        <?= $this->render('_form', [
            'model' => $model,
            ]) ?>
    </div>

</div>