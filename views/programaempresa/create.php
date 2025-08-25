<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Programaempresa $model */

$this->title = Yii::t('app', 'Nuevo Programa de Salud');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Programas de Salud'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="programaempresa-create">

    <div class="container">
        <h1 class="title1 text-uppercase"><?= Html::encode($this->title) ?></h1>

        <?= $this->render('_form', [
            'model' => $model,
            ]) ?>
    </div>

</div>
