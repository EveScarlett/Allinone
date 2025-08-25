<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Vacantes $model */

$this->title = Yii::t('app', 'Nueva Vacante');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Vacantes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vacantes-create">

    <div class="container">
        <h1 class="title1"><?= Html::encode($this->title) ?></h1>

        <?= $this->render('_form', [
            'model' => $model,
            ]) ?>
    </div>

</div>