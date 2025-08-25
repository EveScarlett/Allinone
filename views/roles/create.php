<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Roles $model */

$this->title = Yii::t('app', 'Nuevo Rol');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Roles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="roles-create">

    <div class="container-fluid">
        <h1 class="title1 text-uppercase"><?= Html::encode($this->title) ?></h1>

        <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

    </div>
</div>