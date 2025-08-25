<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Usuarios $model */

$this->title = Yii::t('app', 'Nuevo Usuario');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Usuarios'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="usuarios-create">

    <div class="container-fluid">
        <h1 class="title1 text-uppercase"><?= Html::encode($this->title) ?></h1>

        <?= $this->render('_form', [
            'model' => $model,
            ]) ?>
    </div>

</div>