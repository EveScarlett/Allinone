<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Trabajadores $model */

$this->title = Yii::t('app', 'Asignar Programas de Salud');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Programas de Salud'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="trabajadores-create">

    <div class="container-fluid">
        <h1 class="title1 text-uppercase"><?= Html::encode($this->title) ?></h1>

        <?= $this->render('_form', [
            'model' => $model,
            ]) ?>
    </div>

</div>
