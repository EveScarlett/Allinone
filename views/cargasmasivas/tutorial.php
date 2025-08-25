<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

\yii\web\YiiAsset::register($this);
?>
<div class="cargasmasivas-view">

    <div class="container-fluid text-justify">
        
        <div class="row mb-3">
            <ul>
                <li>Seleccione la empresa a la cual pertenecen los trabajadores</li>
                <li>Suba el listado de trabajadores en formato .CSV (ver ejemplo de archivo a subir y tutorial de conversion en el botón correspondiente)</li>
            </ul>
        </div>
        <?= Html::img("./img/carga_1.JPG",['class'=>'img-fluid']) ?>
        <div class="row my-3">
            <ul>
                <li>Las columnas del archivo .CSV a cargar deben estar acomodadas de la siguiente forma: NOMBRE, APELLIDOS, SEXO, FECHA DE NACIMIENTO, siendo estas 4 columnas los datos mínimos requeridos para dar de alta un trabajador, (la edad se calcula en base a la fecha de nacimiento brindada)</li>
                    <li>Recordar que las fechas deben estar en formato año-mes-día, como se muestra en la imagen</li>

            </ul>
        </div>
        <?= Html::img("./img/carga_2.JPG",['class'=>'img-fluid']) ?>
       

        <div class="row my-3 mt-5">
            <ul>
                <li>En caso de tener en nuestro archivo .CSV columnas con información extra, como numero de trabajador, puesto de trabajo, ciudad, dirección, etc. (ver formulario de alta de Trabajadores) debemos indicar cual es el dato que corresponde a cada columna extra que contenga nuestro archivo</li>
                <li>Es decir, si nuestra 5° columna (recordemos que las 4 primeras se llenan con los datos obligatorios) corresponde al número de trabajador, se debe seleccionar en columna extra 1 "N° Trabajador", si la 6° columna contiene la ciudad de los trabajadores, la columna extra 2 se debe seleccionar la opción ciudad, y así sucesivamente, dependiendo de los datos extra que tengamos en nuestro .CSV</li>
                <li>Si el archivo .CSV tiene columnas extras, pero no se indicaron en la sección de columnas extras del formulario, no se tomarán en cuenta al momento de realizar la carga de trabajadores, es decir serán ignoradas y solo se generarán los trabajadores con los 4 primeros datos</li>
                <li>La carga masiva depende enteramente del archivo .CSV que se esté subiendo, así que, si las columnas están acomodadas en otro orden, o hay columnas vacías antes del nombre, etc. esto afectará y no se realizará de forma correcta.</li>
            </ul>
        </div>

        <?= Html::img("./img/carga_3.JPG",['class'=>'img-fluid']) ?>
       

        <div class="row my-3 mt-5">
            <ul>
                <li>La carga masiva depende enteramente del archivo .CSV que se esté subiendo, así que si las columnas están acomodadas en otro orden, o hay columnas vacías antes del nombre, etc. esto afectará la carga y no se realizará de forma correcta.</li>
            </ul>
        </div>

        <div class="row my-3 mt-5">
            <ul>
                <li>Existen ciertos datos que deberán escribirse tal cual aparecen en el sistema SMO, ya que de lo contrario no se encontrara coincidencia y no se registrará como dato del trabajador, es decir respetar la mayúscula o minúscula con la que esté escrito, los espacios, los acentos, etc.</li>
                <li>Los datos de este tipo son: <b>SEXO, ESTADO CIVIL, NIVEL LECTURA, NIVEL ESCRITURA, ESCOLARIDAD, TIPO CONTRATACIÓN, ÁREA, PUESTO</b></li>
                <li>Verificar como aparecen estos datos en el formulario de registro del trabajador, y escribirlos tal cual en el archivo .CSV, es decir si en lugar de MASCULINO, la columna se llena con M, masc, MASCU, M A S C U L I N O etc, no se encontrará coincidencia y se tomará como campo vacío</li>
            </ul>
        </div>

        <div class="row my-3">
            <ul>
                <li>Una vez adjuntado el archivo .CSV y seleccionado la empresa dar clic en el botón de guardar para que se realice el procesamiento de la carga masiva</li>
            </ul>
        </div>

    </div>



</div>