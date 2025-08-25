<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Poes $model */

$this->title = Yii::t('app', 'Exportar POE');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Poes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="poes-create">

    <div class="container-fluid">
        <h1 class="title1"><?= Html::encode($this->title) ?></h1>

        <?= $this->render('_formexportar', [
            'model' => $model,
            ]) ?>
    </div>

</div>