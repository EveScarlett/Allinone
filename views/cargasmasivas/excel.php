<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

\yii\web\YiiAsset::register($this);
?>
<div class="cargasmasivas-view">

    <div class="container-fluid text-justify">
        <div class="row mb-3">
            <div class="col-lg-4 d-grid">
                <?php
                $filePath = 'img/ListadoTrabajadoresEjemplo.csv';
                 echo Html::a('Descargar CSV Ejemplo <span class="mx-2"><i class="bi bi-download"></i></span>', $filePath, $options = ['class' => 'btn btn-success excel btn-block','target'=>'_blank']);
                ?>
            </div>
        </div>

        <h1 class="title1 text-uppercase"><?= Html::encode('Convertir Archivo Excel a CSV') ?></h1>
        <div class="row my-3">
            <ul>
                <li>Las columnas del excel deben estar sin formato, colores, y la información solo en un libro</li>
                <li>Teniendo el listado de trabajadores en hoja de Excel dar clic en la sección Archivo</li>
            </ul>
        </div>
        <?= Html::img("./img/carga_5.JPG",['class'=>'img-fluid']) ?>
        <div class="row my-3">
            <ul>
                <li>Dar clic en la seccion <b>GUARDAR COMO</b></li>
                <li>Seleccionar alguna carpeta en la cual guardar el archivo</li>
            </ul>
        </div>
        <?= Html::img("./img/carga_6.JPG",['class'=>'img-fluid']) ?>
        <div class="row my-3">
            <ul>
                <li>En Tipo de Archivo seleccionar la opción <b>CSV UTF-8 (delimitado por comas)(*.csv)</b></li>
            </ul>
        </div>
        <?= Html::img("./img/carga_7.JPG",['class'=>'img-fluid']) ?>
        <div class="row my-3">
            <ul>
                <li>Una vez seleccionado el tipo de archivo .csv guardar y ya está listo</li>
            </ul>
        </div>
        <?= Html::img("./img/carga_8.JPG",['class'=>'img-fluid']) ?>

    </div>



</div>