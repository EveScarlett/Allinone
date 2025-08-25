<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Configconsentimientos $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Formato de Consentimientos'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="configconsentimientos-view">

    <div class="container">
        <h1 class="title1"><?= Html::encode($this->title) ?></h1>

        <?php
        echo $model->texto_consentimiento;
    ?>
    </div>
</div>