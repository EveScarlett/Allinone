<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\helpers\ArrayHelper;

use app\models\Empresas;
use app\models\Puestostrabajo;
use app\models\Estudios;
use app\models\Servicios;


use app\models\Paisempresa;
use app\models\Paises;
use app\models\Lineas;
use app\models\Ubicaciones;


use app\models\NivelOrganizacional1;
use app\models\NivelOrganizacional2;
use app\models\NivelOrganizacional3;
use app\models\NivelOrganizacional4;
use app\models\Areas;
use app\models\Consultorios;
use app\models\ProgramaSalud;
use app\models\Programaempresa;

/** @var yii\web\View $this */
/** @var app\models\Cargasmasivas $model */
$usuario = Yii::$app->user->identity;

$title = '';
if($model->empresa){
    $title = $model->empresa->comercial;
}
$title .= ' | '.$model->anio;

$this->title = $title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Cargas Masivas'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<?php
$identificadores = [
1=>'ID Trabajador',
2=>'N° Trabajador',
3=>'Nombre Trabajador'
];

$estudios = ArrayHelper::map(Servicios::find()->orderBy('nombre')->all(), 'id', 'nombre');


$icon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-badge-fill" viewBox="0 0 16 16">
<path d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2zm4.5 0a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1h-3zM8 11a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm5 2.755C12.146 12.825 10.623 12 8 12s-4.146.826-5 1.755V14a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1v-.245z"/>
</svg>';

$iconclip = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-workspace" viewBox="0 0 16 16">
<path d="M4 16s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1H4Zm4-5.95a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5Z"/>
<path d="M2 1a2 2 0 0 0-2 2v9.5A1.5 1.5 0 0 0 1.5 14h.653a5.373 5.373 0 0 1 1.066-2H1V3a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v9h-2.219c.554.654.89 1.373 1.066 2h.653a1.5 1.5 0 0 0 1.5-1.5V3a2 2 0 0 0-2-2H2Z"/>
</svg>';

$asterisco = '<span class="px-2 color11 font11"><i class="bi bi-asterisk"></i></span>';
$asteriscomini = '<span class="px-2 color11 font8"><i class="bi bi-asterisk"></i></span>';
?>

<?php
$showempresa = 'block';
$empresas = explode(',', Yii::$app->user->identity->empresas_select);

if(Yii::$app->user->identity->empresa_all != 1){
    if(count($empresas) == 1){
        $showempresa = 'none';
    }
}
?>

<?php
$modelo_ = 'cargasmasivas';
$label_nivel1 = 'Nivel 1';
$label_nivel2 = 'Nivel 2';
$label_nivel3 = 'Nivel 3';
$label_nivel4 = 'Nivel 4';

$show_nivel1 = 'none';
$show_nivel2 = 'none';
$show_nivel3 = 'none';
$show_nivel4 = 'none';


if($model->id_empresa != null && $model->id_empresa != '' && $model->id_empresa != ' '){
    $empresa = Empresas::findOne($model->id_empresa);

    if($empresa){
        $label_nivel1 = $empresa->label_nivel1;
        $label_nivel2 = $empresa->label_nivel2;
        $label_nivel3 = $empresa->label_nivel3;
        $label_nivel4 = $empresa->label_nivel4;

        if($empresa->cantidad_niveles >= 1){
            $show_nivel1 = 'block';
        }
        if($empresa->cantidad_niveles >= 2){
            $show_nivel2 = 'block';
        }
        if($empresa->cantidad_niveles >= 3){
            $show_nivel3 = 'block';
        }
        if($empresa->cantidad_niveles >= 4){
            $show_nivel4 = 'block';
        }
    }
}
?>
<div class="cargasmasivas-view">

    <div class="container">
        <h1 class="title1"><?= Html::encode($this->title) ?></h1>

        <div class="row mt-2">
            <div class="col-lg-8 mt-3" style="display:<?php echo $showempresa?>;">
                <label class="control-label"><?= $model->getAttributeLabel('id_empresa');?></label>
                <div class="form-control bg-disabled ">
                    <?php
                    $data = '';

                    if($model->empresa){
                        $data = $model->empresa->comercial;
                    }

                    echo $data.'&nbsp;';?>
                </div>
            </div>
            <div class="col-lg-2 mt-3" style="display:<?php echo $showempresa?>;">
            </div>
            <div class="col-lg-2 mt-3">
                <label class="control-label"><?= $model->getAttributeLabel('anio');?></label>
                <div class="form-control bg-disabled ">
                    <?php
                    echo $model->anio.'&nbsp;';
                    ?>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-lg-3 mt-3" id="show_nivel1" style="display:<?=$show_nivel1?>;">
                <label class="control-label"><?= '<span id="label_nivel1">'.$label_nivel1.'</span>';?></label>
                <div class="form-control bg-disabled ">
                    <?php
                    $data = '';

                    if($model->nivel1 && $model->nivel1->pais){
                        $data = $model->nivel1->pais->pais;
                    }

                    echo $data.'&nbsp;';?>
                </div>
            </div>
            <div class="col-lg-3 mt-3" id="show_nivel2" style="display:<?=$show_nivel2?>;">
                <label class="control-label"><?= '<span id="label_nivel2">'.$label_nivel2.'</span>';?></label>
                <div class="form-control bg-disabled ">
                    <?php
                    $data = '';

                    if($model->nivel2){
                        $data = $model->nivel2->nivelorganizacional2;
                    }

                    echo $data.'&nbsp;';?>
                </div>
            </div>
            <div class="col-lg-3 mt-3" id="show_nivel3" style="display:<?=$show_nivel3?>;">
                <label class="control-label"><?= '<span id="label_nivel3">'.$label_nivel3.'</span>';?></label>
                <div class="form-control bg-disabled ">
                    <?php
                    $data = '';

                    if($model->nivel3){
                        $data = $model->nivel3->nivelorganizacional3;
                    }

                    echo $data.'&nbsp;';?>
                </div>
            </div>
            <div class="col-lg-3 mt-3" id="show_nivel4" style="display:<?=$show_nivel4?>;">
                <label class="control-label"><?= '<span id="label_nivel4">'.$label_nivel4.'</span>';?></label>
                <div class="form-control bg-disabled ">
                    <?php
                    $data = '';

                    if($model->nivel4){
                        $data = $model->nivel4->nivelorganizacional4;
                    }

                    echo $data.'&nbsp;';?>
                </div>
            </div>
        </div>

        <div class="row mt-2 px-3" style="display:none">
            <div class="col-lg-6 my-2 bgnocumple p-2 rounded-4">
                Suba el listado de trabajadores en formato .CSV
            </div>
        </div>
        <div class="row mt-3">

            <div class="col-lg-4">
                <label class="control-label"><?= 'Archivo POE .CSV';?></label>
                <?php
                    $data = '';
                    if($model->archivo){
                        $filePath = 'cargas/'.$model->archivo;
                        $data =  Html::a('Descargar .CSV <span class="mx-2"><i class="bi bi-download"></i></span>', $filePath, $options = ['class' => 'btn btn-sm btn-success excel btn-block','target'=>'_blank']);
                    }
                    echo $data;
                ?>
            </div>
            <div class="col-lg-8">
                <div class="row my-3" style="display:none;">
                    <div class="col-lg-4 d-grid">
                        <?= Html::button('Ver Tutorial <span class="mx-2"><i class="bi bi-cast"></i></span>', ['class' => 'btn btn-danger youtube btn-block', 'name' => 'ver_tutorial',
                            'value' =>Url::to(['cargasmasivas/tutorial'])]) ?>
                    </div>
                    <div class="col-lg-4 d-grid">
                        <?= Html::button('Ejemplo CSV y tutorial <span class="mx-2"><i class="bi bi-cast"></i></span>', ['class' => 'btn btn-success excel btn-block', 'name' => 'ver_excel',
                            'value' =>Url::to(['cargasmasivas/excel'])]) ?>
                    </div>
                </div>
                <ul>
                    <li class="color11">El listado de poes debe estar en formato .CSV</li>
                    <li class="color11">La primera columna del archivo .csv debe ser un dato que identifique al
                        trabajador al cual se le realizará el POE, siendo una de las opciones: <b>ID TRABAJADOR, NUMERO
                            TRABAJADOR, NOMBRE TRABAJADOR</b></li>
                    <li class="">Acorde a esta primera columna, en el formulario deberá seleccionarse cual es el dato
                        con el cual se buscará al trabajador(<b>ID TRABAJADOR, NUMERO TRABAJADOR, NOMBRE
                            TRABAJADOR</b>), en la seccion <b>IDENTIFICADOR TRABAJADOR</b></li>
                    <li class="">Las siguientes columnas corresponden a los estudios que se realizarán en los poes, por
                        lo tanto seberán seleccionarse en la sección de estudios los que correspondan a cada columna.
                    </li>
                    <li class="color11">Las siguientes columnas del archivo .CSV deberán llenarse con SI o NO,
                        dependiendo de si el trabajador se realizará dicho estudio (correspondiente a la columna) o no.
                    </li>
                </ul>

                <div class="container mt-3">
                    <table class="table table-hover table-bordered">
                        <tbody>
                            <tr>
                                <th class="font500">Trabajador <?php echo $asteriscomini?></th>
                                <th class="font500">Estudio 1</th>
                                <th class="font500">Estudio 2</th>
                                <th class="font500">Estudio 3</th>
                                <th class="font500">Estudio ...</th>
                            </tr>
                            <tr>
                                <td>1</td>
                                <td>SI</td>
                                <td>NO</td>
                                <td>SI</td>
                                <td>SI</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

        <div class="row my-4">
            <div class="col-lg-6 mt-2">
                <label class="control-label"><?= $model->getAttributeLabel('id_trabajador');?></label>
                <div class="form-control bg-disabled ">
                    <?php
                    $data = '';

                    if(array_key_exists($model->id_trabajador, $identificadores)){
                        $data = $identificadores[$model->id_trabajador];
                    }
                    echo $data.'&nbsp;';
                    ?>
                </div>
            </div>
        </div>
        <?php
        $index = 1;
        ?>
        <div class="row">
            <div class="col-lg-3 mt-2">
                <label class="control-label"><?= 'Estudio '.$index;?></label>
                <div class="form-control bg-disabled ">
                    <?php
                    $data = '';
                    $key = $model->extra1;

                    if(array_key_exists($key, $estudios)){
                        $data = $estudios[$key];
                    }
                    echo $data.'&nbsp;';
                    ?>
                </div>
            </div>
            <?php $index ++;?>
            <div class="col-lg-3 mt-2">
                <label class="control-label"><?= 'Estudio '.$index;?></label>
                <div class="form-control bg-disabled ">
                    <?php
                    $data = '';
                    $key = $model->extra2;

                    if(array_key_exists($key, $estudios)){
                        $data = $estudios[$key];
                    }
                    echo $data.'&nbsp;';
                    ?>
                </div>
            </div>
            <?php $index ++;?>
            <div class="col-lg-3 mt-2">
                <label class="control-label"><?= 'Estudio '.$index;?></label>
                <div class="form-control bg-disabled ">
                    <?php
                    $data = '';
                    $key = $model->extra3;

                    if(array_key_exists($key, $estudios)){
                        $data = $estudios[$key];
                    }
                    echo $data.'&nbsp;';
                    ?>
                </div>
            </div>
            <?php $index ++;?>
            <div class="col-lg-3 mt-2">
                <label class="control-label"><?= 'Estudio '.$index;?></label>
                <div class="form-control bg-disabled ">
                    <?php
                    $data = '';
                    $key = $model->extra4;

                    if(array_key_exists($key, $estudios)){
                        $data = $estudios[$key];
                    }
                    echo $data.'&nbsp;';
                    ?>
                </div>
            </div>
            <?php $index ++;?>
            <div class="col-lg-3 mt-2">
                <label class="control-label"><?= 'Estudio '.$index;?></label>
                <div class="form-control bg-disabled ">
                    <?php
                    $data = '';
                    $key = $model->extra5;

                    if(array_key_exists($key, $estudios)){
                        $data = $estudios[$key];
                    }
                    echo $data.'&nbsp;';
                    ?>
                </div>
            </div>
            <?php $index ++;?>
            <div class="col-lg-3 mt-2">
                <label class="control-label"><?= 'Estudio '.$index;?></label>
                <div class="form-control bg-disabled ">
                    <?php
                    $data = '';
                    $key = $model->extra6;

                    if(array_key_exists($key, $estudios)){
                        $data = $estudios[$key];
                    }
                    echo $data.'&nbsp;';
                    ?>
                </div>
            </div>
            <?php $index ++;?>
            <div class="col-lg-3 mt-2">
                <label class="control-label"><?= 'Estudio '.$index;?></label>
                <div class="form-control bg-disabled ">
                    <?php
                    $data = '';
                    $key = $model->extra7;

                    if(array_key_exists($key, $estudios)){
                        $data = $estudios[$key];
                    }
                    echo $data.'&nbsp;';
                    ?>
                </div>
            </div>
            <?php $index ++;?>
            <div class="col-lg-3 mt-2">
                <label class="control-label"><?= 'Estudio '.$index;?></label>
                <div class="form-control bg-disabled ">
                    <?php
                    $data = '';
                    $key = $model->extra8;

                    if(array_key_exists($key, $estudios)){
                        $data = $estudios[$key];
                    }
                    echo $data.'&nbsp;';
                    ?>
                </div>
            </div>
            <?php $index ++;?>
            <div class="col-lg-3 mt-2">
                <label class="control-label"><?= 'Estudio '.$index;?></label>
                <div class="form-control bg-disabled ">
                    <?php
                    $data = '';
                    $key = $model->extra9;

                    if(array_key_exists($key, $estudios)){
                        $data = $estudios[$key];
                    }
                    echo $data.'&nbsp;';
                    ?>
                </div>
            </div>
            <?php $index ++;?>
            <div class="col-lg-3 mt-2">
                <label class="control-label"><?= 'Estudio '.$index;?></label>
                <div class="form-control bg-disabled ">
                    <?php
                    $data = '';
                    $key = $model->extra10;

                    if(array_key_exists($key, $estudios)){
                        $data = $estudios[$key];
                    }
                    echo $data.'&nbsp;';
                    ?>
                </div>
            </div>
            <?php $index ++;?>
            <div class="col-lg-3 mt-2">
                <label class="control-label"><?= 'Estudio '.$index;?></label>
                <div class="form-control bg-disabled ">
                    <?php
                    $data = '';
                    $key = $model->extra11;

                    if(array_key_exists($key, $estudios)){
                        $data = $estudios[$key];
                    }
                    echo $data.'&nbsp;';
                    ?>
                </div>
            </div>
            <?php $index ++;?>
            <div class="col-lg-3 mt-2">
                <label class="control-label"><?= 'Estudio '.$index;?></label>
                <div class="form-control bg-disabled ">
                    <?php
                    $data = '';
                    $key = $model->extra12;

                    if(array_key_exists($key, $estudios)){
                        $data = $estudios[$key];
                    }
                    echo $data.'&nbsp;';
                    ?>
                </div>
            </div>
            <?php $index ++;?>
            <div class="col-lg-3 mt-2">
                <label class="control-label"><?= 'Estudio '.$index;?></label>
                <div class="form-control bg-disabled ">
                    <?php
                    $data = '';
                    $key = $model->extra13;

                    if(array_key_exists($key, $estudios)){
                        $data = $estudios[$key];
                    }
                    echo $data.'&nbsp;';
                    ?>
                </div>
            </div>
            <?php $index ++;?>
            <div class="col-lg-3 mt-2">
                <label class="control-label"><?= 'Estudio '.$index;?></label>
                <div class="form-control bg-disabled ">
                    <?php
                    $data = '';
                    $key = $model->extra14;

                    if(array_key_exists($key, $estudios)){
                        $data = $estudios[$key];
                    }
                    echo $data.'&nbsp;';
                    ?>
                </div>
            </div>
            <?php $index ++;?>
            <div class="col-lg-3 mt-2">
                <label class="control-label"><?= 'Estudio '.$index;?></label>
                <div class="form-control bg-disabled ">
                    <?php
                    $data = '';
                    $key = $model->extra15;

                    if(array_key_exists($key, $estudios)){
                        $data = $estudios[$key];
                    }
                    echo $data.'&nbsp;';
                    ?>
                </div>
            </div>
            <?php $index ++;?>
            <div class="col-lg-3 mt-2">
                <label class="control-label"><?= 'Estudio '.$index;?></label>
                <div class="form-control bg-disabled ">
                    <?php
                    $data = '';
                    $key = $model->extra16;

                    if(array_key_exists($key, $estudios)){
                        $data = $estudios[$key];
                    }
                    echo $data.'&nbsp;';
                    ?>
                </div>
            </div>
            <?php $index ++;?>
            <div class="col-lg-3 mt-2">
                <label class="control-label"><?= 'Estudio '.$index;?></label>
                <div class="form-control bg-disabled ">
                    <?php
                    $data = '';
                    $key = $model->extra17;

                    if(array_key_exists($key, $estudios)){
                        $data = $estudios[$key];
                    }
                    echo $data.'&nbsp;';
                    ?>
                </div>
            </div>
            <?php $index ++;?>
            <div class="col-lg-3 mt-2">
                <label class="control-label"><?= 'Estudio '.$index;?></label>
                <div class="form-control bg-disabled ">
                    <?php
                    $data = '';
                    $key = $model->extra18;

                    if(array_key_exists($key, $estudios)){
                        $data = $estudios[$key];
                    }
                    echo $data.'&nbsp;';
                    ?>
                </div>
            </div>
            <?php $index ++;?>
            <div class="col-lg-3 mt-2">
                <label class="control-label"><?= 'Estudio '.$index;?></label>
                <div class="form-control bg-disabled ">
                    <?php
                    $data = '';
                    $key = $model->extra19;

                    if(array_key_exists($key, $estudios)){
                        $data = $estudios[$key];
                    }
                    echo $data.'&nbsp;';
                    ?>
                </div>
            </div>
            <?php $index ++;?>
            <div class="col-lg-3 mt-2">
                <label class="control-label"><?= 'Estudio '.$index;?></label>
                <div class="form-control bg-disabled ">
                    <?php
                    $data = '';
                    $key = $model->extra20;

                    if(array_key_exists($key, $estudios)){
                        $data = $estudios[$key];
                    }
                    echo $data.'&nbsp;';
                    ?>
                </div>
            </div>
            <?php $index ++;?>
            <div class="col-lg-3 mt-2">
                <label class="control-label"><?= 'Estudio '.$index;?></label>
                <div class="form-control bg-disabled ">
                    <?php
                    $data = '';
                    $key = $model->extra21;

                    if(array_key_exists($key, $estudios)){
                        $data = $estudios[$key];
                    }
                    echo $data.'&nbsp;';
                    ?>
                </div>
            </div>
            <?php $index ++;?>
            <div class="col-lg-3 mt-2">
                <label class="control-label"><?= 'Estudio '.$index;?></label>
                <div class="form-control bg-disabled ">
                    <?php
                    $data = '';
                    $key = $model->extra22;

                    if(array_key_exists($key, $estudios)){
                        $data = $estudios[$key];
                    }
                    echo $data.'&nbsp;';
                    ?>
                </div>
            </div>
            <?php $index ++;?>
            <div class="col-lg-3 mt-2">
                <label class="control-label"><?= 'Estudio '.$index;?></label>
                <div class="form-control bg-disabled ">
                    <?php
                    $data = '';
                    $key = $model->extra23;

                    if(array_key_exists($key, $estudios)){
                        $data = $estudios[$key];
                    }
                    echo $data.'&nbsp;';
                    ?>
                </div>
            </div>
            <?php $index ++;?>
            <div class="col-lg-3 mt-2">
                <label class="control-label"><?= 'Estudio '.$index;?></label>
                <div class="form-control bg-disabled ">
                    <?php
                    $data = '';
                    $key = $model->extra24;

                    if(array_key_exists($key, $estudios)){
                        $data = $estudios[$key];
                    }
                    echo $data.'&nbsp;';
                    ?>
                </div>
            </div>
            <?php $index ++;?>
            <div class="col-lg-3 mt-2">
                <label class="control-label"><?= 'Estudio '.$index;?></label>
                <div class="form-control bg-disabled ">
                    <?php
                    $data = '';
                    $key = $model->extra25;

                    if(array_key_exists($key, $estudios)){
                        $data = $estudios[$key];
                    }
                    echo $data.'&nbsp;';
                    ?>
                </div>
            </div>
        </div>

    </div>
</div>