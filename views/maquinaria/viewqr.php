<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Trabajadores $model */
?>
<div class="trabajadores-view">
    <div class="container my-3">
        <h1 class="title1">
            <?php echo $model->maquina?>
        </h1>
        <div class="p-2 bg-light rounded-3 small text-dark font500"><?php echo $model->clave;?></div>

        <div class="row mt-3">

            <?php
             $filePath =  '/web'.'/qrsmaquinas/'.$model->id.'/'.$model->id.'qr.png';
            /* $filePath =  '/qrsmaquinas/'.$model->id.'/'.$model->id.'qr.png'; */

            $imagen = '<img class="img-fluid" src="'.$filePath.'" alt="QR de Maquina">';
            echo $imagen;
            ?>
            
        </div>

    </div>

</div>