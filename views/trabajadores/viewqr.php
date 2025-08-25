<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Trabajadores $model */
?>
<div class="trabajadores-view">
    <div class="container my-3">
        <h1 class="title1">
            <?php echo $model->nombre.' '.$model->apellidos?>
        </h1>

        <div class="row mt-3">

            <?php
             $filePath =  '/web'.'/qrs/'.$model->id.'/'.$model->id.'qr.png';
           /*  $filePath =  '/qrs/'.$model->id.'/'.$model->id.'qr.png'; */

            $imagen = '<img class="img-fluid" src="'.$filePath.'" alt="QR del Trabajador">';
            echo $imagen;
            ?>
            
        </div>

    </div>

</div>