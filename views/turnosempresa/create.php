<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Turnos $model */

$this->title = Yii::t('app', 'Nuevo Turno');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Turnos'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="turnos-create">

    <div class="container">
        <h1 class="title1 text-uppercase"><?= Html::encode($this->title) ?></h1>

        <?= $this->render('_form', [
            'model' => $model,
            ]) ?>
    </div>

</div>
