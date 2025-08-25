<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\HccOhc $model */

$this->title = 'Ver Historia Clínica: '.$model->nombre.' '.$model->apellidos;
$this->params['breadcrumbs'][] = ['label' => 'Historias Clínicas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="hcc-ohc-create">

    <div class="container-fluid">

        <h1 class="title1"><?= Html::encode($this->title) ?></h1>


        <div class="row my-3 border30 bg-customlight border-custom p-3">
            <?php if(Yii::$app->user->can('historias_actualizar') && $model->status != 2):?>
            <div class="col-lg-3 d-grid ">
                <?= Html::a('<span class="mx-2 small"><i class="bi bi-pen-fill"></i></span>Actualizar Historia Clínica', ['update','id'=>$model->id], ['class' => 'btn btn-primary btnnew btn-block']) ?>
            </div>
            <?php endif;?>

            <?php if(true):?>
            <div class="col-lg-2 d-grid ">
                <?php 
            $image = '<span class="color1"><i class="bi bi-file-earmark-text-fill"></i></span> Ver PDF';
            $ret = Html::a($image, Url::to(['hccohc/pdf','id' => $model->id,'firmado'=>1]), $options = ['target'=>'_blank','title' => Yii::t('app', 'Con Firma'),'data-toggle'=>"tooltip",'data-placement'=>"top",'class'=>'btn bginfo btn-block text-light']);
            echo $ret;
            ?>
            </div>
            <?php endif;?>

            <?php if(true):?>
            <div class="col-lg-2 d-grid ">
                <?php 
            $image = '<span class="color11"><i class="bi bi-file-pdf-fill"></i></span> Ver CAL';
            $ret = Html::a($image, Url::to(['hccohc/pdfcal','id' => $model->id,'firmado'=>1]), $options = ['target'=>'_blank','title' => Yii::t('app', 'Con Firma'),'data-toggle'=>"tooltip",'data-placement'=>"top",'class'=>'btn bgnocumple btn-block text-light']);
            echo $ret;
            ?>
            </div>
            <?php endif;?>

            <?php if(Yii::$app->user->can('historias_corregir')):?>
            <div class="col-lg-3 d-grid ">
                <?= Html::a('<span class="mx-2 small"><i class="bi bi-unlock-fill"></i><i class="bi bi-pen-fill"></i></span>Corregir Historia Clínica', ['correction','id'=>$model->id], ['class' => 'btn bgcolor12 btn-block text-light']) ?>
            </div>
            <?php endif;?>
        </div>

        <?= $this->render('viewclean', [
            'model' => $model,
            ]) ?>
    </div>

</div>