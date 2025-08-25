<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Consultorios $model */

$this->title = Yii::t('app', 'Nuevo Consultorio');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Consultorios'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="consultorios-create">

    <div class="container">
        <h1 class="title1 text-uppercase"><?= Html::encode($this->title) ?></h1>

        <?= $this->render('_form', [
            'model' => $model,
            ]) ?>
    </div>

</div>
