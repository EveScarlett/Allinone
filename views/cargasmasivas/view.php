<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

use app\models\Empresas;
use app\models\Puestostrabajo;
use app\models\Estudios;

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
$title .= ' | '.$model->create_date;

$this->title = $title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Cargas Masivas'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
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
<?php
$columnas = [
36=>$label_nivel1,
37=>$label_nivel2,
38=>$label_nivel3,
39=>$label_nivel4,
1=>'N° Trabajador',
2=>'N° IMSS',
3=>'Célular',
4=>'Contacto Emergencia',
5=>'Dirección',
6=>'Colonia',
7=>'Ciudad',
8=>'Municipio',
9=>'Estado',
10=>'--',
11=>'CP.',
12=>'Fecha Contratacion',
13=>'Estado Civil',
14=>'Nivel Lectura',
15=>'Nivel Escritura',
16=>'Escolaridad',
17=>'Ruta',
18=>'Parada',
19=>'Área',
20=>'Puesto de Trabajo',

40=>'Puesto de Trabajo Contable',
41=>'Sueldo Puesto',

21=>'Teamleader',
22=>'Fecha Inicio',
23=>'Curp',
24=>'Rfc',
25=>'Correo Electrónico',
/* 26=>'Ubicación',
27=>'País', */
28=>'Extra 3',
29=>'Extra 4',
30=>'Extra 5',
31=>'Extra 6',
32=>'Extra 7',
33=>'Extra 8',
34=>'Extra 9',
35=>'Extra 10',

];


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
<div class="cargasmasivas-view">

    <div class="container">
        <h1 class="title1"><?= Html::encode($this->title) ?></h1>

        <div class="row mt-2">
            <div class="col-lg-6 mt-3" style="display:<?php echo $showempresa?>;">
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
                <label class="control-label"><?= 'Archivo Listado de Trabajadores .CSV';?></label>
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
                <div class="row my-3">
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
                    <li class="color11">El listado de trabajadores debe estar en formato .CSV</li>
                    <li class="color11">Hay 4 columnas de datos OBLIGATORIAS.</li>
                    <li class="color11">Las columnas obligatorias son <b>NOMBRE, APELLIDOS, SEXO, FECHA DE
                            NACIMIENTO</b>, y
                        deben acomodarse en ese orden.</li>
                    <li class="color11">Los datos deben estar en <b>MAYÚSCULAS</b>.</li>
                    <li class="color11">Las fechas deben estar en formato año-mes-día <b>yyyy-mm-dd</b> (ejemplo
                        1996-05-21).</li>
                    <li class="color11">No colocar símbolos o carácteres especiales. (Ejemplo: µ º ³ ç ζ ).</li>
                    <li class="">Hay hasta 22 columnas de información extra.</li>
                    <li>Las
                        columnas extra no son obligatorias, y debe indicarse el atributo del trabajador que corresponda
                        (direccion, numero de trabajador, etc.) segun el archivo .CSV que esté subiendo.</li>
                </ul>

                <div class="container mt-3">
                    <table class="table table-hover table-bordered">
                        <tbody>
                            <tr>
                                <th class="font500">Nombre <?php echo $asteriscomini?></th>
                                <th class="font500">Apellidos <?php echo $asteriscomini?></th>
                                <th class="font500">Sexo <?php echo $asteriscomini?></th>
                                <th class="font500">Fecha de Nacimiento <?php echo $asteriscomini?></th>
                                <th class="font500">Columna Extra 1</th>
                                <th class="font500">Columna Extra 2</th>
                                <th class="font500">Columna Extra ...</th>
                            </tr>
                            <tr>
                                <td>John</td>
                                <td>Doe</td>
                                <td>MASCULINO</td>
                                <td>1992-05-20</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>


        <div class="row">
            <div class="col-lg-2 mt-2">
                <label class="control-label"><?= $model->getAttributeLabel('extra1');?></label>
                <div class="form-control bg-disabled ">
                    <?php
                    $data = '';
                    $key = $model->extra1;

                    if(array_key_exists($key, $columnas)){
                        $data = $columnas[$key];
                    }
                    echo $data.'&nbsp;';
                    ?>
                </div>
            </div>
            <div class="col-lg-2 mt-2">
                <label class="control-label"><?= $model->getAttributeLabel('extra2');?></label>
                <div class="form-control bg-disabled ">
                    <?php
                    $data = '';
                    $key = $model->extra2;

                    if(array_key_exists($key, $columnas)){
                        $data = $columnas[$key];
                    }
                    echo $data.'&nbsp;';
                    ?>
                </div>
            </div>
            <div class="col-lg-2 mt-2">
                <label class="control-label"><?= $model->getAttributeLabel('extra3');?></label>
                <div class="form-control bg-disabled ">
                    <?php
                    $data = '';
                    $key = $model->extra3;

                    if(array_key_exists($key, $columnas)){
                        $data = $columnas[$key];
                    }
                    echo $data.'&nbsp;';
                    ?>
                </div>
            </div>
            <div class="col-lg-2 mt-2">
                <label class="control-label"><?= $model->getAttributeLabel('extra4');?></label>
                <div class="form-control bg-disabled ">
                    <?php
                    $data = '';
                    $key = $model->extra4;

                    if(array_key_exists($key, $columnas)){
                        $data = $columnas[$key];
                    }
                    echo $data.'&nbsp;';
                    ?>
                </div>
            </div>
            <div class="col-lg-2 mt-2">
                <label class="control-label"><?= $model->getAttributeLabel('extra5');?></label>
                <div class="form-control bg-disabled ">
                    <?php
                    $data = '';
                    $key = $model->extra5;

                    if(array_key_exists($key, $columnas)){
                        $data = $columnas[$key];
                    }
                    echo $data.'&nbsp;';
                    ?>
                </div>
            </div>
            <div class="col-lg-2 mt-2">
                <label class="control-label"><?= $model->getAttributeLabel('extra6');?></label>
                <div class="form-control bg-disabled ">
                    <?php
                    $data = '';
                    $key = $model->extra6;

                    if(array_key_exists($key, $columnas)){
                        $data = $columnas[$key];
                    }
                    echo $data.'&nbsp;';
                    ?>
                </div>
            </div>
            <div class="col-lg-2 mt-2">
                <label class="control-label"><?= $model->getAttributeLabel('extra7');?></label>
                <div class="form-control bg-disabled ">
                    <?php
                    $data = '';
                    $key = $model->extra7;

                    if(array_key_exists($key, $columnas)){
                        $data = $columnas[$key];
                    }
                    echo $data.'&nbsp;';
                    ?>
                </div>
            </div>
            <div class="col-lg-2 mt-2">
               <label class="control-label"><?= $model->getAttributeLabel('extra8');?></label>
                <div class="form-control bg-disabled ">
                    <?php
                    $data = '';
                    $key = $model->extra8;

                    if(array_key_exists($key, $columnas)){
                        $data = $columnas[$key];
                    }
                    echo $data.'&nbsp;';
                    ?>
                </div>
            </div>
            <div class="col-lg-2 mt-2">
                <label class="control-label"><?= $model->getAttributeLabel('extra9');?></label>
                <div class="form-control bg-disabled ">
                    <?php
                    $data = '';
                    $key = $model->extra9;

                    if(array_key_exists($key, $columnas)){
                        $data = $columnas[$key];
                    }
                    echo $data.'&nbsp;';
                    ?>
                </div>
            </div>
            <div class="col-lg-2 mt-2">
                <label class="control-label"><?= $model->getAttributeLabel('extra10');?></label>
                <div class="form-control bg-disabled ">
                    <?php
                    $data = '';
                    $key = $model->extra10;

                    if(array_key_exists($key, $columnas)){
                        $data = $columnas[$key];
                    }
                    echo $data.'&nbsp;';
                    ?>
                </div>
            </div>
            <div class="col-lg-2 mt-2">
                <label class="control-label"><?= $model->getAttributeLabel('extra11');?></label>
                <div class="form-control bg-disabled ">
                    <?php
                    $data = '';
                    $key = $model->extra11;

                    if(array_key_exists($key, $columnas)){
                        $data = $columnas[$key];
                    }
                    echo $data.'&nbsp;';
                    ?>
                </div>
            </div>
            <div class="col-lg-2 mt-2">
                <label class="control-label"><?= $model->getAttributeLabel('extra12');?></label>
                <div class="form-control bg-disabled ">
                    <?php
                    $data = '';
                    $key = $model->extra12;

                    if(array_key_exists($key, $columnas)){
                        $data = $columnas[$key];
                    }
                    echo $data.'&nbsp;';
                    ?>
                </div>
            </div>
            <div class="col-lg-2 mt-2">
                <label class="control-label"><?= $model->getAttributeLabel('extra13');?></label>
                <div class="form-control bg-disabled ">
                    <?php
                    $data = '';
                    $key = $model->extra13;

                    if(array_key_exists($key, $columnas)){
                        $data = $columnas[$key];
                    }
                    echo $data.'&nbsp;';
                    ?>
                </div>
            </div>
            <div class="col-lg-2 mt-2">
                <label class="control-label"><?= $model->getAttributeLabel('extra14');?></label>
                <div class="form-control bg-disabled ">
                    <?php
                    $data = '';
                    $key = $model->extra14;

                    if(array_key_exists($key, $columnas)){
                        $data = $columnas[$key];
                    }
                    echo $data.'&nbsp;';
                    ?>
                </div>
            </div>
            <div class="col-lg-2 mt-2">
                <label class="control-label"><?= $model->getAttributeLabel('extra15');?></label>
                <div class="form-control bg-disabled ">
                    <?php
                    $data = '';
                    $key = $model->extra15;

                    if(array_key_exists($key, $columnas)){
                        $data = $columnas[$key];
                    }
                    echo $data.'&nbsp;';
                    ?>
                </div>
            </div>
            <div class="col-lg-2 mt-2">
                <label class="control-label"><?= $model->getAttributeLabel('extra16');?></label>
                <div class="form-control bg-disabled ">
                    <?php
                    $data = '';
                    $key = $model->extra16;

                    if(array_key_exists($key, $columnas)){
                        $data = $columnas[$key];
                    }
                    echo $data.'&nbsp;';
                    ?>
                </div>
            </div>
            <div class="col-lg-2 mt-2">
                <label class="control-label"><?= $model->getAttributeLabel('extra17');?></label>
                <div class="form-control bg-disabled ">
                    <?php
                    $data = '';
                    $key = $model->extra17;

                    if(array_key_exists($key, $columnas)){
                        $data = $columnas[$key];
                    }
                    echo $data.'&nbsp;';
                    ?>
                </div>
            </div>
            <div class="col-lg-2 mt-2">
                <label class="control-label"><?= $model->getAttributeLabel('extra18');?></label>
                <div class="form-control bg-disabled ">
                    <?php
                    $data = '';
                    $key = $model->extra18;

                    if(array_key_exists($key, $columnas)){
                        $data = $columnas[$key];
                    }
                    echo $data.'&nbsp;';
                    ?>
                </div>
            </div>
            <div class="col-lg-2 mt-2">
                <label class="control-label"><?= $model->getAttributeLabel('extra19');?></label>
                <div class="form-control bg-disabled ">
                    <?php
                    $data = '';
                    $key = $model->extra19;

                    if(array_key_exists($key, $columnas)){
                        $data = $columnas[$key];
                    }
                    echo $data.'&nbsp;';
                    ?>
                </div>
            </div>
            <div class="col-lg-2 mt-2">
                <label class="control-label"><?= $model->getAttributeLabel('extra20');?></label>
                <div class="form-control bg-disabled ">
                    <?php
                    $data = '';
                    $key = $model->extra20;

                    if(array_key_exists($key, $columnas)){
                        $data = $columnas[$key];
                    }
                    echo $data.'&nbsp;';
                    ?>
                </div>
            </div>
            <div class="col-lg-2 mt-2">
                <label class="control-label"><?= $model->getAttributeLabel('extra21');?></label>
                <div class="form-control bg-disabled ">
                    <?php
                    $data = '';
                    $key = $model->extra21;

                    if(array_key_exists($key, $columnas)){
                        $data = $columnas[$key];
                    }
                    echo $data.'&nbsp;';
                    ?>
                </div>
            </div>
            <div class="col-lg-2 mt-2">
               <label class="control-label"><?= $model->getAttributeLabel('extra22');?></label>
                <div class="form-control bg-disabled ">
                    <?php
                    $data = '';
                    $key = $model->extra22;

                    if(array_key_exists($key, $columnas)){
                        $data = $columnas[$key];
                    }
                    echo $data.'&nbsp;';
                    ?>
                </div>
            </div>
            <div class="col-lg-2 mt-2">
                <label class="control-label"><?= $model->getAttributeLabel('extra23');?></label>
                <div class="form-control bg-disabled ">
                    <?php
                    $data = '';
                    $key = $model->extra23;

                    if(array_key_exists($key, $columnas)){
                        $data = $columnas[$key];
                    }
                    echo $data.'&nbsp;';
                    ?>
                </div>
            </div>
            <div class="col-lg-2 mt-2">
                <label class="control-label"><?= $model->getAttributeLabel('extra24');?></label>
                <div class="form-control bg-disabled ">
                    <?php
                    $data = '';
                    $key = $model->extra24;

                    if(array_key_exists($key, $columnas)){
                        $data = $columnas[$key];
                    }
                    echo $data.'&nbsp;';
                    ?>
                </div>
            </div>
            <div class="col-lg-2 mt-2">
                <label class="control-label"><?= $model->getAttributeLabel('extra25');?></label>
                <div class="form-control bg-disabled ">
                    <?php
                    $data = '';
                    $key = $model->extra25;

                    if(array_key_exists($key, $columnas)){
                        $data = $columnas[$key];
                    }
                    echo $data.'&nbsp;';
                    ?>
                </div>
            </div>
        </div>


    </div>
</div>