<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/lab.ico" type="image/x-icon">

</head>

<?php

use yii\helpers\Html;
use app\models\Riesgos;
use app\models\Areas;
use app\models\Puestostrabajo;
use app\models\ProgramaSalud;
use app\models\Movimientos;
use app\models\Almacen;

use app\models\Empresas;
use app\models\NivelOrganizacional1;
use app\models\NivelOrganizacional2;
use app\models\NivelOrganizacional3;
use app\models\NivelOrganizacional4;

use app\models\Ubicaciones;
use app\models\Paises;
use app\models\Paisempresa;

?>
<?php
$tipoexamen = ['3'=>'CLÍNICA','1'=>'ACCIDENTE','7'=>'PROGRAMAS DE SALUD','4'=>'INCAPACIDAD','6'=>'TRABAJOS DE RIESGO','2'=>'ANTIDOPING','5'=>'PREOCUPANTE','8'=>'COVID-19','9'=>'NUTRICIÓN','10'=>'PSICOLÓGICA','11'=>'ALCOHOLEMIA'];
$riesgostipos = [
    '1'=>'Trabajos en caliente',
    '2'=>'Espacios confinados',
    '3'=>'Trabajo en componentes energizados',
    '4'=>'Trabajos en alturas',
    '5'=>'Trabajos en solitario y/o áreas remotas',
    '6'=>'Trabajo en sistemas presurizados',
    '7'=>'Trabajos químicos altamente peligrosos',
    '8'=>'Construcción/excavación/demolición/izaje'
];
$solicitantes = ['1'=>'EMPLEADO','2'=>'CONTRATISTA','3'=>'VISITANTE'];
$visitas = ['1'=>'1A VEZ','2'=>'SUBSECUENTE'];
$resultados = ['1'=>'REGRESA A LABORAR (MISMA ACTIVIDAD)','2'=>'REGRESA A LABORAR (CAMBIO ACTIVIDAD)',
'3'=>'ENVIO IMSS','4'=>'ENVIO A DOMICILIO','5'=>'INCAPACIDAD IMSS','6'=>'EN OBSERVACIÓN'];
$tipopadecimiento = ['1'=>'LABORAL','2'=>'NO LABORAL'];
$tipoincapacidad = ['1'=>'IMSS','2'=>'INTERNA','3'=>'PARTICULAR'];
$ramoincapacidad = ['1'=>'Maternidad','2'=>'Enfermedad General','3'=>'Riesgo del Trabajo'];
$tadiagnosticos = ['1'=>'Normal','2'=>'Hipotensión','3'=>'Hipertensión'];
$oxidiagnosticos = ['1'=>'Normal','2'=>'Hipoxia'];
$frdiagnosticos = ['1'=>'Normal','2'=>'Bradipnea','3'=>'Taquipnea'];
?>


<?php
$label_nivel1 = 'Nivel 1';
$label_nivel2 = 'Nivel 2';
$label_nivel3 = 'Nivel 3';
$label_nivel4 = 'Nivel 4';

$show_nivel1 = false;
$show_nivel2 = false;
$show_nivel3 = false;
$show_nivel4 = false;

$nombre_empresa = '';
if(true) {
    $dataempresa = Empresas::findOne(intval($model->id_empresa));
    if($dataempresa){
        if($model->dempresa->mostrar_nivel_pdf == 1){
            $nombre_empresa = $model->dempresa->comercial;
        }

        $label_nivel1 = $dataempresa->label_nivel1;
        $label_nivel2 = $dataempresa->label_nivel2;
        $label_nivel3 = $dataempresa->label_nivel3;
        $label_nivel4 = $dataempresa->label_nivel4;

        if($dataempresa->cantidad_niveles >= 1){
            $show_nivel1 = true;

            if($model->dempresa->mostrar_nivel_pdf == 2 && $model->nivel1){
                if($model->nivel1->pais){
                    
                    $nombre_empresa = $model->nivel1->pais->pais;
                }
            }
        }
        if($dataempresa->cantidad_niveles >= 2){
            $show_nivel2 = true;

            if($model->dempresa->mostrar_nivel_pdf == 2 && $model->nivel2){
                $nombre_empresa = $model->nivel2->nivelorganizacional2;
            }
        }
        if($dataempresa->cantidad_niveles >= 2){
            $show_nivel3 = true;

            if($model->dempresa->mostrar_nivel_pdf == 2 && $model->nivel3){
                $nombre_empresa = $model->nivel3->nivelorganizacional3;
            }
        }
        if($dataempresa->cantidad_niveles >= 4){
            $show_nivel4 = true;

            if($model->dempresa->mostrar_nivel_pdf == 2 && $model->nivel4){
                $nombre_empresa = $model->nivel4->nivelorganizacional4;
            }
        }
    }
} else {
    
    $show_nivel1 = true;
    $show_nivel2 = true;
    $show_nivel3 = true;
    $show_nivel4 = true;
}
//dd($label_nivel1,$label_nivel2,$label_nivel3,$label_nivel4,$show_nivel1,$show_nivel2,$show_nivel3,$show_nivel4);
?>

<body>
    <div class="container px-5">
        <div style="margin-bottom: 15px;">
            <h1 class="title text-center">CONSULTA MÉDICA</h1>
        </div>
        <div>

            <table style="line-height: 1.5; font-size:11px;width:100%;border-collapse: collapse;">
                <tbody>
                    <tr>
                        <!-- Linea para generar 10 espacios iguales -->
                        <td style="width:5%"></td>
                        <td style="width:5%"></td>
                        <td style="width:5%"></td>
                        <td style="width:5%"></td>
                        <td style="width:5%"></td>
                        <td style="width:5%"></td>
                        <td style="width:5%"></td>
                        <td style="width:5%"></td>
                        <td style="width:5%"></td>
                        <td style="width:5%"></td>
                        <td style="width:5%"></td>
                        <td style="width:5%"></td>
                        <td style="width:5%"></td>
                        <td style="width:5%"></td>
                        <td style="width:5%"></td>
                        <td style="width:5%"></td>
                        <td style="width:5%"></td>
                        <td style="width:5%"></td>
                        <td style="width:5%"></td>
                        <td style="width:5%"></td>
                    </tr>

                    <tr>
                        <td colspan="8" class="t5">
                            <b>Folio:</b>
                            <?=$model->folio?>
                        </td>
                        <td colspan="6" class="t5">
                            <b>Fecha:</b>
                            <?=$model->fecha?>
                        </td>
                        <td colspan="6" class="t5">
                            <b>N° IMSS:</b>
                            <?=$model->num_imss?>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="8" class="t5">
                            <b>Tipo de Atención:</b>
                            <?=$tipoexamen[$model->tipo]?>
                        </td>
                        <td colspan="6" class="t5">
                            <b>Solicitante:</b>
                            <?=$solicitantes[$model->solicitante]?>
                        </td>
                        <td colspan="6" class="t5">
                            <b></b>
                            <?=''?>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="8" class="t5">
                            <b>Nombre:</b>
                            <?php
                            $nombreret = ''; 
                            
                            if($model->solicitante == 2 || $model->solicitante == 3){
                                $nombreret = $model->nombre;
                            } else{
                                if($model->trabajador){
                                    $nombreret = $model->trabajador->nombre;
                                } else{
                                    $nombreret = $model->nombre;
                                }
                                
                            }
                            echo $nombreret;
                            ?>
                        </td>
                        <td colspan="12" class="t5">
                            <b>Apellidos:</b>
                            <?php
                            $apellidoret = ''; 
                            
                            if($model->solicitante == 2 || $model->solicitante == 3){
                                $apellidoret = $model->apellidos;
                            } else{
                                if($model->trabajador){
                                    $apellidoret = $model->trabajador->apellidos;
                                } else{
                                    $apellidoret = $model->apellidos;
                                }
                               
                            }
                            echo $apellidoret;
                            ?>
                        </td>
                    </tr>


                    <tr>
                        <?php if($show_nivel1 == 'block'):?>
                        <td colspan="8" class="t5" style="display:<?=$show_nivel1?>;">
                            <b><?=$label_nivel1;?>:</b>
                            <?php
                            if($model->nivel1){
                                if($model->nivel1->pais){
                                    echo $model->nivel1->pais->pais;
                                }
                            }
                            ?>
                        </td>
                        <?php endif; ?>

                        <?php if($show_nivel2 == 'block'):?>
                        <td colspan="12" class="t5" style="display:<?=$show_nivel2?>;">
                            <b><?=$label_nivel2;?>:</b>
                            <?php
                            if($model->nivel2){
                                echo $model->nivel2->nivelorganizacional2;
                            }
                            ?>
                        </td>
                        <?php endif; ?>
                    </tr>
                    <tr>
                        <?php if($show_nivel3 == 'block'):?>
                        <td colspan="8" class="t5" style="display:<?=$show_nivel3?>;">
                            <b><?=$label_nivel3;?>:</b>
                            <?php
                            if($model->nivel3){
                                echo $model->nivel3->nivelorganizacional3;
                            }
                            ?>
                        </td>
                        <?php endif; ?>

                        <?php if($show_nivel4 == 'block'):?>
                        <td colspan="12" class="t5" style="display:<?=$show_nivel4?>;">
                            <b><?=$label_nivel4;?>:</b>
                            <?php
                            if($model->nivel4){
                                echo $model->nivel4->nivelorganizacional4;
                            }
                            ?>
                        </td>
                        <?php endif; ?>
                    </tr>


                    <tr>
                        <td colspan="8" class="t5">
                            <b>Puesto:</b>
                            <?php
                            if($model->puestodata){
                                echo $model->puestodata->nombre;
                            }
                            ?>
                        </td>
                        <td colspan="6" class="t5">
                            <b>Area:</b>
                            <?php
                            if($model->areadata){
                                echo $model->areadata->area;
                            }
                            ?>
                        </td>
                        <td colspan="6" class="t5">
                            <b>Empresa:</b>
                            <?php
                            if($model->solicitante == 2 || $model->solicitante == 3){
                                echo $model->empresa;
                            } else{
                                echo $nombre_empresa;
                            }
                            
                            ?>
                        </td>
                    </tr>


                    <tr>
                        <td colspan="20" class="t5">
                            <b>Consultorio:</b>
                            <?php
                            if($model->consultorio){
                                echo $model->consultorio->consultorio;
                            }
                            ?>
                        </td>
                    </tr>


                    <tr>
                        <td style="padding-top:20px;"></td>
                    </tr>
                    <tr>
                        <td colspan="20" class="bgbadges">
                            Resultado
                        </td>
                    </tr>
                    <tr>
                        <td colspan="20" class="borderall text-center title">
                            <?=$resultados[$model->resultado];?>
                        </td>
                    </tr>


                    <tr>
                        <td style="padding-top:20px;"></td>
                    </tr>
                    <tr>
                        <td colspan="20" class="bgbadges">
                            Signos Vitales
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" class="t5">
                            <b>Peso:</b>
                            <?=$model->peso.' kg'?>
                        </td>
                        <td colspan="3" class="t5">
                            <b>Talla:</b>
                            <?=$model->talla." m"?>
                        </td>
                        <td colspan="2" class="t5">
                            <b>F.C.:</b>
                            <?=$model->fc?>
                        </td>
                        <td colspan="2" class="t5">
                            <b>F.R.:</b>
                            <?=$model->fr?>
                        </td>
                        <td colspan="4" class="t5">
                            <b>F.R. Diag.:</b>
                            <?php
                            if(isset($model->fr_diagnostico) && $model->fr_diagnostico != '' && $model->fr_diagnostico != null){
                                echo $frdiagnosticos[$model->fr_diagnostico];
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4" class="t5">
                            <b>Temperatura:</b>
                            <?=$model->temp." ºC"?>
                        </td>
                        <td colspan="3" class="t5">
                            <b>Oximetría:</b>
                            <?=$model->oxigeno?>
                        </td>
                        <td colspan="4" class="t5">
                            <b>Diag.:</b>
                            <?php
                            if(isset($model->oximetria_diagnostico) && $model->oximetria_diagnostico != '' && $model->oximetria_diagnostico != null){
                                echo $oxidiagnosticos[$model->oximetria_diagnostico];
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" class="t5">
                            <b>T/A: Sistólica:</b>
                            <?=$model->ta?>
                        </td>
                        <td colspan="3" class="t5">
                            <b>Diastólica:</b>
                            <?=$model->ta_diastolica?>
                        </td>
                        <td colspan="5" class="t5">
                            <b>Sistólica Diag.:</b>
                            <?php
                            if(isset($model->tasis_diagnostico) && $model->tasis_diagnostico != '' && $model->tasis_diagnostico != null){
                                echo $tadiagnosticos[$model->tasis_diagnostico];
                            }
                            ?>
                        </td>
                        <td colspan="5" class="t5">
                            <b>Diastólica Diag.:</b>
                            <?php
                            if(isset($model->tadis_diagnostico) && $model->tadis_diagnostico != '' && $model->tadis_diagnostico != null){
                                echo $tadiagnosticos[$model->tadis_diagnostico];
                            }
                            ?>
                        </td>
                        <td colspan="5" class="t5">
                            <b>TA Diag.:</b>
                            <?php
                            if(isset($model->ta_diagnostico) && $model->ta_diagnostico != '' && $model->ta_diagnostico != null){
                                echo $tadiagnosticos[$model->ta_diagnostico];
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" class="t5">
                            <b>IMC:</b>
                            <?=$model->imc?>
                        </td>
                        <td colspan="9" class="t5">
                            <b>E.Nutri:</b>
                            <?=$model->categoria_imc?>
                        </td>
                        <td colspan="3" class="t5">
                            <b>Pulso:</b>
                            <?=$model->pulso?>
                        </td>

                    </tr>

                    <?php
                    $array = [];
                    if($model->id_programa){
                        $array = explode(',', $model->id_programa);
                    } 
                    
                    $arraysino = ['1'=>'SI','2'=>'NO'];
                    $arraydiabetes = ['1'=>'Ardor/Disuria','2'=>'Sensación de vaciamiento incompleto/Tenesmo','3'=>'No presenta molestias'];
                    $arrayhipertension = ['1'=>'Frecuencia (incremento)','2'=>'Frecuencia (descenso)', '3'=>'Volumen (disminución del chorro)','4'=>'Orina con aspecto fuera de la normalidad','5'=>'No presenta cambios'];
                    $arraylactancia2 =  ['1'=>'Parto','2'=>'Cesárea'];
                    $arraylactancia6 =  ['1'=>'Lactancia materna exclusiva','2'=>'Lactancia materna complementaria','3'=>'Lactancia materna predominante'];
                    $arraylactancia7 =  ['1'=>'Salida constante de leche','2'=>'Salida de material purulento','3'=>'Fiebre','4'=>'Abultamientos o endurecimientos de mama','5'=>'Fisuras o grietas mamarias','6'=>'Dolor al tacto'];
                    $arrayhiperdia1 =  ['1'=>'Si, ambas','2'=>'No, ninguno','3'=>'Solo glucosa en control','4'=>'Solo presión arterial en control'];
                    $arrayhiperdia2 =  ['1'=>'Si, ambos','2'=>'No, ninguno','3'=>'Solo buena adherencia al tratamiendo para diabetes mellitus','4'=>'Solo buena adherencia al tratamiento para hipertensión'];
                    $arrayhiperdia3 =  ['1'=>'Alteraciones en el aspecto de la orina','2'=>'Alteraciones en el olor de la orina','3'=>'Alteraciones en el color de la orina','4'=>'Alteraciones asociadas como disuria (ardor), tenesmo (sensación de vaciamiento incompleto)','5'=>'Alteraciones en la frecuencia','6'=>'Alteraciones en el horario (nicturia)'];

                    $showdiabetes = 0;
                    $showhipertension = 0;
                    $showmaternidad = 0;
                    $showlactancia = 0;
                    $showhiperdiabetes = 0;

                    $contador = 1;

                    if (in_array('1', $array)) {
                        $showdiabetes = 1;
                    } 
                    if (in_array('2', $array)) {
                        $showhipertension = 1;
                    } 
                    if (in_array('3', $array)) {
                        $showmaternidad = 1;
                    }
                    if (in_array('4', $array)) {
                        $showlactancia = 1;
                    }
                    if (in_array('5', $array)) {
                       $showhiperdiabetes = 1;
                    }

                    $array_diabetes6 = '';
                    $array_hipertension6 = '';
                    $array_lactancia7 = '';
                    $array_hiperdiabe3 = '';

                    $array = explode(',', $model->ps_diabetes3);
                    if(isset($model->ps_diabetes3) && $model->ps_diabetes3 != null && $model->ps_diabetes3 != ''){
                        foreach($array as $key=>$elemento){
                            $array_diabetes6 .= $arraydiabetes[$elemento];  
                            if($key < (count($array)-1)){
                                $array_diabetes6 .= ', ';
                            }
                        }
                    }

                    $array = explode(',', $model->ps_hipertension6);
                    if(isset($model->ps_hipertension6) && $model->ps_hipertension6 != null && $model->ps_hipertension6 != ''){
                        foreach($array as $key=>$elemento){
                            $array_hipertension6 .= $arrayhipertension[$elemento];  
                            if($key < (count($array)-1)){
                                $array_hipertension6 .= ', ';
                            }
                        }
                    }

                    $array = explode(',', $model->ps_lactancia7);
                    if(isset($model->ps_lactancia7) && $model->ps_lactancia7 != null && $model->ps_lactancia7 != ''){
                        foreach($array as $key=>$elemento){
                            $array_lactancia7 .= $arraylactancia7[$elemento];  
                            if($key < (count($array)-1)){
                                $array_lactancia7 .= ', ';
                            }
                        }
                    }

                    $array = explode(',', $model->ps_hiperdiabe3);
                    if(isset($model->ps_hiperdiabe3) && $model->ps_hiperdiabe3 != null && $model->ps_hiperdiabe3 != ''){
                        foreach($array as $key=>$elemento){
                            $array_hiperdiabe3 .= $arrayhiperdia3[$elemento];  
                            if($key < (count($array)-1)){
                                $array_hiperdiabe3 .= ', ';
                            }
                        }
                    }
    
                    ?>

                    <!--  PROGRAMAS DE SALUD -->
                    <?php if($model->tipo == 7):?>
                    <?php if($showdiabetes == 1):?>
                    <tr>
                        <td style="padding-top:20px;" class="t3" colspan="20">DIABETES</td>
                    </tr>
                    <tr>
                        <td colspan="20" class="t5">
                            <?php echo $contador.'.-'.$model->getAttributeLabel('ps_diabetes1').': ';?>
                            <b><?php echo $arraysino[$model->ps_diabetes1];?></b>
                        </td>
                    <tr>
                        <?php $contador = $contador+1;?>
                    <tr>
                        <td colspan="20" class="t5">
                            <?php echo $contador.'.-'.$model->getAttributeLabel('ps_diabetes2').': ';?>
                            <b><?php echo $arraysino[$model->ps_diabetes2];?></b>
                        </td>
                    <tr>
                        <?php $contador = $contador+1;?>
                    <tr>
                        <td colspan="20" class="t5">
                            <?php echo $contador.'.-'.$model->getAttributeLabel('ps_diabetes3').': ';?>
                            <b><?php echo $array_diabetes6;?></b>
                        </td>
                    <tr>
                        <?php $contador = $contador+1;?>
                    <tr>
                        <td colspan="20" class="t5">
                            <?php echo $contador.'.-'.$model->getAttributeLabel('ps_diabetes4').': ';?>
                            <b><?php echo $arraysino[$model->ps_diabetes4];?></b>
                        </td>
                    <tr>
                        <?php $contador = $contador+1;?>
                    <tr>
                        <td colspan="20" class="t5">
                            <?php echo $contador.'.-'.$model->getAttributeLabel('ps_diabetes5').': ';?>
                            <b><?php echo $arraysino[$model->ps_diabetes5];?></b>
                        </td>
                    <tr>
                        <?php $contador = $contador+1;?>
                    <tr>
                        <td colspan="20" class="t5">
                            <?php echo $contador.'.-'.$model->getAttributeLabel('ps_diabetes6').': ';?></b>
                            <b><?php echo $arraysino[$model->ps_diabetes6];?>
                        </td>
                    <tr>
                        <?php $contador = $contador+1;?>
                    <tr>
                        <td colspan="20" class="t5">
                            <?php echo $contador.'.-'.$model->getAttributeLabel('ps_diabetes7').': ';?>
                            <b><?php echo $arraysino[$model->ps_diabetes7];?></b>
                        </td>
                    <tr>
                        <?php $contador = $contador+1;?>
                    <tr>
                        <td colspan="20" class="t5">
                            <?php echo $contador.'.-'.$model->getAttributeLabel('ps_diabetes8').': ';?>
                            <b><?php echo $arraysino[$model->ps_diabetes8];?></b>
                        </td>
                    <tr>
                        <?php $contador = $contador+1;?>
                    <tr>
                        <td colspan="20" class="t5">
                            <?php echo $contador.'.-'.$model->getAttributeLabel('ps_diabetes9').': ';?>
                            <b><?php echo $arraysino[$model->ps_diabetes9];?></b>
                        </td>
                    <tr>
                        <?php endif;?>
                        <?php if($showhipertension == 1):?>
                    <tr>
                        <td style="padding-top:20px;" class="t3" colspan="20">HIPERTENSION</td>
                    </tr>
                    <?php $contador = 1;?>
                    <tr>
                        <td colspan="20" class="t5">
                            <?php echo $contador.'.-'.$model->getAttributeLabel('ps_hipertension1').': ';?>
                            <b><?php echo $arraysino[$model->ps_hipertension1];?></b>
                        </td>
                    <tr>
                        <?php $contador = $contador+1;?>
                    <tr>
                        <td colspan="20" class="t5">
                            <?php echo $contador.'.-'.$model->getAttributeLabel('ps_hipertension2').': ';?>
                            <b><?php echo $arraysino[$model->ps_hipertension2];?></b>
                        </td>
                    <tr>
                        <?php $contador = $contador+1;?>
                    <tr>
                        <td colspan="20" class="t5">
                            <?php echo $contador.'.-'.$model->getAttributeLabel('ps_hipertension3').': ';?>
                            <b><?php echo $arraysino[$model->ps_hipertension3];?></b>
                        </td>
                    <tr>
                        <?php $contador = $contador+1;?>
                    <tr>
                        <td colspan="20" class="t5">
                            <?php echo $contador.'.-'.$model->getAttributeLabel('ps_hipertension4').': ';?>
                            <b><?php echo $arraysino[$model->ps_hipertension4];?></b>
                        </td>
                    <tr>
                        <?php $contador = $contador+1;?>
                    <tr>
                        <td colspan="20" class="t5">
                            <?php echo $contador.'.-'.$model->getAttributeLabel('ps_hipertension5').': ';?>
                            <b><?php echo $arraysino[$model->ps_hipertension2];?></b>
                        </td>
                    <tr>
                        <?php $contador = $contador+1;?>
                    <tr>
                        <td colspan="20" class="t5">
                            <?php echo $contador.'.-'.$model->getAttributeLabel('ps_hipertension6').': ';?>
                            <b><?php echo $array_hipertension6;?></b>
                        </td>
                    <tr>
                        <?php $contador = $contador+1;?>
                    <tr>
                        <td colspan="20" class="t5">
                            <?php echo $contador.'.-'.$model->getAttributeLabel('ps_hipertension7').': ';?>
                            <b><?php echo $arraysino[$model->ps_hipertension7];?></b>
                        </td>
                    <tr>
                        <?php $contador = $contador+1;?>
                    <tr>
                        <td colspan="20" class="t5">
                            <?php echo $contador.'.-'.$model->getAttributeLabel('ps_hipertension8').': ';?>
                            <b><?php echo $arraysino[$model->ps_hipertension8];?></b>
                        </td>
                    <tr>
                        <?php $contador = $contador+1;?>
                    <tr>
                        <td colspan="20" class="t5">
                            <?php echo $contador.'.-'.$model->getAttributeLabel('ps_hipertension9').': ';?>
                            <b><?php echo $arraysino[$model->ps_hipertension9];?></b>
                        </td>
                    <tr>
                        <?php $contador = $contador+1;?>
                    <tr>
                        <td colspan="20" class="t5">
                            <?php echo $contador.'.-'.$model->getAttributeLabel('ps_hipertension10').': ';?>
                            <b><?php echo $arraysino[$model->ps_hipertension10];?></b>
                        </td>
                    <tr>
                        <?php endif;?>
                        <?php if($showmaternidad == 1):?>
                    <tr>
                        <td style="padding-top:20px;" class="t3" colspan="20">MATERNIDAD</td>
                    </tr>
                    <?php $contador = 1;?>
                    <tr>
                        <td colspan="20" class="t5">
                            <?php echo $contador.'.-'.$model->getAttributeLabel('ps_maternidad1').': ';?>
                            <b><?php echo $arraysino[$model->ps_maternidad1];?></b>
                        </td>
                    <tr>
                        <?php $contador = $contador+1;?>
                    <tr>
                        <td colspan="20" class="t5">
                            <?php echo $contador.'.-'.$model->getAttributeLabel('ps_maternidad2').': ';?>
                            <b><?php echo $arraysino[$model->ps_maternidad2];?></b>
                        </td>
                    <tr>
                        <?php $contador = $contador+1;?>
                    <tr>
                        <td colspan="20" class="t5">
                            <?php echo $contador.'.-'.$model->getAttributeLabel('ps_maternidad3').': ';?>
                            <b><?php echo $arraysino[$model->ps_maternidad3];?></b>
                        </td>
                    <tr>
                        <?php $contador = $contador+1;?>
                    <tr>
                        <td colspan="20" class="t5">
                            <?php echo $contador.'.-'.$model->getAttributeLabel('ps_maternidad4').': ';?>
                            <b><?php echo $arraysino[$model->ps_maternidad4];?></b>
                        </td>
                    <tr>
                        <?php $contador = $contador+1;?>
                    <tr>
                        <td colspan="20" class="t5">
                            <?php echo $contador.'.-'.$model->getAttributeLabel('ps_maternidad5').': ';?>
                            <b><?php echo $arraysino[$model->ps_maternidad5];?></b>
                        </td>
                    <tr>
                        <?php $contador = $contador+1;?>
                    <tr>
                        <td colspan="20" class="t5">
                            <?php echo $contador.'.-'.$model->getAttributeLabel('ps_maternidad6').': ';?>
                            <b><?php echo $arraysino[$model->ps_maternidad6];?></b>
                        </td>
                    <tr>
                        <?php $contador = $contador+1;?>
                    <tr>
                        <td colspan="20" class="t5">
                            <?php echo $contador.'.-'.$model->getAttributeLabel('ps_maternidad7').': ';?>
                            <b><?php echo $arraysino[$model->ps_maternidad7];?></b>
                        </td>
                    <tr>
                        <?php $contador = $contador+1;?>
                    <tr>
                        <td colspan="20" class="t5">
                            <?php echo $contador.'.-'.$model->getAttributeLabel('ps_maternidad8').': ';?>
                            <b><?php echo $arraysino[$model->ps_maternidad8];?></b>
                        </td>
                    <tr>
                        <?php $contador = $contador+1;?>
                    <tr>
                        <td colspan="20" class="t5">
                            <?php echo $contador.'.-'.$model->getAttributeLabel('ps_maternidad9').': ';?>
                            <b><?php echo $arraysino[$model->ps_maternidad9];?></b>
                        </td>
                    <tr>
                        <?php $contador = $contador+1;?>
                    <tr>
                        <td colspan="20" class="t5">
                            <?php echo $contador.'.-'.$model->getAttributeLabel('ps_maternidad10').': ';?>
                            <b><?php echo $arraysino[$model->ps_maternidad10];?></b>
                        </td>
                    <tr>
                        <?php $contador = $contador+1;?>
                    <tr>
                        <td colspan="20" class="t5">
                            <?php echo $contador.'.-'.$model->getAttributeLabel('ps_maternidad11').': ';?>
                            <b><?php echo $arraysino[$model->ps_maternidad11];?></b>
                        </td>
                    <tr>
                        <?php endif;?>
                        <?php if($showlactancia == 1):?>
                    <tr>
                        <td style="padding-top:20px;" class="t3" colspan="20">LACTANCIA</td>
                    </tr>
                    <?php $contador = 1;?>
                    <tr>
                        <td colspan="20" class="t5">
                            <?php echo $contador.'.-'.$model->getAttributeLabel('ps_lactancia1').': ';?>
                            <b><?php echo $model->ps_lactancia1;?></b>
                        </td>
                    <tr>
                        <?php $contador = $contador+1;?>
                    <tr>
                        <td colspan="20" class="t5">
                            <?php echo $contador.'.-'.$model->getAttributeLabel('ps_lactancia2').': ';?>
                            <b><?php echo $arraylactancia2[$model->ps_lactancia2];?></b>
                        </td>
                    <tr>
                        <?php $contador = $contador+1;?>
                    <tr>
                        <td colspan="20" class="t5">
                            <?php echo $contador.'.-'.$model->getAttributeLabel('ps_lactancia3').': ';?>
                            <b><?php echo $model->ps_lactancia3;?></b>
                        </td>
                    <tr>
                        <?php $contador = $contador+1;?>
                    <tr>
                        <td colspan="20" class="t5">
                            <?php echo $contador.'.-'.$model->getAttributeLabel('ps_lactancia4').': ';?>
                            <b><?php echo $model->ps_lactancia4;?></b>
                        </td>
                    <tr>
                        <?php $contador = $contador+1;?>
                    <tr>
                        <td colspan="20" class="t5">
                            <?php echo $contador.'.-'.$model->getAttributeLabel('ps_lactancia5').': ';?>
                            <b><?php echo $arraysino[$model->ps_lactancia5];?></b>
                        </td>
                    <tr>
                        <?php $contador = $contador+1;?>
                    <tr>
                        <td colspan="20" class="t5">
                            <?php echo $contador.'.-'.$model->getAttributeLabel('ps_lactancia6').': ';?>
                            <b><?php echo $arraylactancia6[$model->ps_lactancia6];?></b>
                        </td>
                    <tr>
                        <?php $contador = $contador+1;?>
                    <tr>
                        <td colspan="20" class="t5">
                            <?php echo $contador.'.-'.$model->getAttributeLabel('ps_lactancia7').': ';?>
                            <b><?php echo $array_lactancia7;?></b>
                        </td>
                    <tr>
                        <?php endif;?>
                        <?php if($showhiperdiabetes == 1):?>
                    <tr>
                        <td style="padding-top:20px;" class="t3" colspan="20">HIPERTENSION Y DIABETES</td>
                    </tr>
                    <?php $contador = 1;?>
                    <tr>
                        <td colspan="20" class="t5">
                            <?php echo $contador.'.-'.$model->getAttributeLabel('ps_hiperdiabe1').': ';?>
                            <b><?php echo $arrayhiperdia1[$model->ps_hiperdiabe1];?></b>
                        </td>
                    <tr>
                        <?php $contador = $contador+1;?>
                    <tr>
                        <td colspan="20" class="t5">
                            <?php echo $contador.'.-'.$model->getAttributeLabel('ps_hiperdiabe2').': ';?>
                            <b><?php echo $arrayhiperdia2[$model->ps_hiperdiabe2];?></b>
                        </td>
                    <tr>
                        <?php $contador = $contador+1;?>
                    <tr>
                        <td colspan="20" class="t5">
                            <?php echo $contador.'.-'.$model->getAttributeLabel('ps_hiperdiabe3').': ';?>
                            <b><?php echo $array_hiperdiabe3;?></b>
                        </td>
                    <tr>
                        <?php $contador = $contador+1;?>
                    <tr>
                        <td colspan="20" class="t5">
                            <?php echo $contador.'.-'.$model->getAttributeLabel('ps_hiperdiabe4').': ';?>
                            <b><?php echo $arraysino[$model->ps_hiperdiabe4];?></b>
                        </td>
                    <tr>
                        <?php $contador = $contador+1;?>
                    <tr>
                        <td colspan="20" class="t5">
                            <?php echo $contador.'.-'.$model->getAttributeLabel('ps_hiperdiabe5').': ';?>
                            <b><?php echo $arraysino[$model->ps_hiperdiabe5];?></b>
                        </td>
                    <tr>
                        <?php $contador = $contador+1;?>
                    <tr>
                        <td colspan="20" class="t5">
                            <?php echo $contador.'.-'.$model->getAttributeLabel('ps_hiperdiabe6').': ';?>
                            <b><?php echo $arraysino[$model->ps_hiperdiabe6];?></b>
                        </td>
                    <tr>
                        <?php $contador = $contador+1;?>
                    <tr>
                        <td colspan="20" class="t5">
                            <?php echo $contador.'.-'.$model->getAttributeLabel('ps_hiperdiabe7').': ';?>
                            <b><?php echo $arraysino[$model->ps_hiperdiabe7];?></b>
                        </td>
                    <tr>
                        <?php $contador = $contador+1;?>
                    <tr>
                        <td colspan="20" class="t5">
                            <?php echo $contador.'.-'.$model->getAttributeLabel('ps_hiperdiabe8').': ';?>
                            <b><?php echo $arraysino[$model->ps_hiperdiabe8];?></b>
                        </td>
                    <tr>
                        <?php $contador = $contador+1;?>
                    <tr>
                        <td colspan="20" class="t5">
                            <?php echo $contador.'.-'.$model->getAttributeLabel('ps_hiperdiabe9').': ';?>
                            <b><?php echo $arraysino[$model->ps_hiperdiabe9];?></b>
                        </td>
                    <tr>
                        <?php endif;?>

                        <?php endif;?>


                        <?php if($model->tipo == 1):?>
                    <tr>
                        <td style="padding-top:20px;"></td>
                    </tr>
                    <tr>
                        <td colspan="5" class="t5">
                            <b>Hora de Accidente:</b>
                        </td>
                        <td colspan="3" class="bgcolor5 color11 text-center">
                            <?=date('H:i', strtotime($model->accidente_hora)); ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="5" class="t5">
                            <b>Hora de Reporte:</b>
                        </td>
                        <td colspan="3" class="bgcolor5 color11 text-center">
                            <?=date('H:i', strtotime($model->accidente_horareporte)); ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-top:10px;"></td>
                    </tr>
                    <tr>
                        <td colspan="3" class="t5">
                            <b>Zona:</b>
                        </td>
                        <td colspan="17" class="borderall">
                            <?=$model->accidente_zona ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" class="t5">
                            <b>Causa:</b>
                        </td>
                        <td colspan="17" class="borderall">
                            <?=$model->accidente_causa ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" class="t5">
                            <b>Descripción:</b>
                        </td>
                        <td colspan="17" class="borderall">
                            <?=$model->accidente_descripcion ?>
                        </td>
                    </tr>
                    <?php elseif($model->tipo == 4):?>
                    <tr>
                        <td style="padding-top:20px;"></td>
                    </tr>
                    <tr>
                        <td colspan="5" class="t5">
                            <b>Folio Incapacidad:</b>
                        </td>
                        <td colspan="15" class="borderall">
                            <?=$model->incapacidad_folio ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="5" class="t5">
                            <b>Tipo:</b>
                        </td>
                        <td colspan="15" class="borderall">
                            <?=$tipoincapacidad[$model->incapacidad_tipo]; ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="5" class="t5">
                            <b>Ramo de Seguro:</b>
                        </td>
                        <td colspan="15" class="borderall">
                            <?=$ramoincapacidad[$model->incapacidad_ramo]; ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-top:10px;"></td>
                    </tr>
                    <tr>
                        <td colspan="5" class="t5">
                            <b>Fecha Inicio:</b>
                            <?=date('d-m-Y', strtotime($model->incapacidad_fechainicio)); ?>
                        </td>

                        <td colspan="3" class="t5">
                            <b>Días:</b>
                            <?=$model->incapacidad_dias; ?>
                        </td>

                        <td colspan="2" class="t5">
                            <b>Fecha Fin:</b>
                        </td>
                        <td colspan="3" class="bgcolor5 color11 text-center">
                            <b><?=date('d-m-Y', strtotime($model->incapacidad_fechafin)); ?></b>
                        </td>

                    </tr>
                    <?php endif;?>


                    <tr>
                        <td style="padding-top:20px;"></td>
                    </tr>
                    <!-- <tr>
                        <td colspan="3" class="t5">
                            <b>Alergias:</b>
                        </td>
                        <td colspan="17" class="borderall">
                            <?=$model->alergias ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" class="t5">
                            <b>Embarazo:</b>
                        </td>
                        <td colspan="17" class="borderall">
                            <?=$model->embarazo ?>
                        </td>
                    </tr> -->
                    <tr>
                        <td colspan="3" class="t5">
                            <b>Sintomatología:</b>
                        </td>
                        <td colspan="17" class="borderall">
                            <?=$model->sintomatologia ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" class="t5">
                            <b>Diagnóstico:</b>
                        </td>
                        <td colspan="17" class="borderall">
                            <?=$model->diagnostico ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" class="t5">
                            <b>Manejo:</b>
                        </td>
                        <td colspan="17" class="borderall">
                            <?=$model->manejo ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" class="t5">
                            <b>Seguimiento:</b>
                        </td>
                        <td colspan="17" class="borderall">
                            <?=$model->seguimiento ?>
                        </td>
                    </tr>


                    <tr>
                        <td style="padding-top:20px;"></td>
                    </tr>
                    <tr>
                        <td colspan="20" class="bgbadges">
                            Entrega de medicamentos
                        </td>
                    </tr>
                    <tr>
                        <td colspan="9" class="t5"><b>Medicamento(Nombre Comercial)</b></td>
                        <td colspan="6" class="t5"><b>Nombre Genérico</b></td>
                        <td colspan="5" class="t5">Cantidad Unidad</td>
                    </tr>
                    <?php
                    $movimiento = Movimientos::find()->where(['id_consultahc'=>$model->id])->one();
                    if($movimiento){
                        foreach($movimiento->medicamentos as $key=>$medicamento){
                            $almacen = Almacen::find()->where(['id_insumo'=>$medicamento->id_insumo])->andWhere(['id_consultorio'=>$model->id_consultorio])->andWhere(['fecha_caducidad'=>$medicamento->fecha_caducidad])->one();
                            if($almacen){
                                echo '<tr>
                                <td colspan="9" class="t5 color3">'.$almacen->insumo->nombre_comercial.'</td>
                                <td colspan="6" class="t5">'.$almacen->insumo->nombre_generico.'</td>
                                <td colspan="5" class="t5 t4"><b>'.$medicamento->cantidad_unidad.'</b></td>
                                </tr>';
                            }
                        }

                    }
                     
                    ?>



                    <tr>
                        <td style="padding-top:15px;"></td>
                    </tr>

                    <tr>
                        <td colspan="9" class="t5 text-center">
                            <?php
                            //dd($model->uCaptura);
                            if($model->uCaptura && $model->uCaptura->firmaa){
                                
                                $ret = '';
                                if($model->firmado == 1 && isset($model->uCaptura->firmaa->firma) && $model->uCaptura->firmaa->firma != ""){
                              
                                    $filePath =  '/resources/firmas/'.$model->uCaptura->firmaa->firma;
                                    $ret = Html::img('@web'. $filePath, ['alt'=>' ', 'class' => "img-responsive", 'width' => '150px', 'height' =>'auto','style'=>'box-shadow: rgba(50, 50, 93, 0.25) 0px 13px 27px -5px, rgba(0, 0, 0, 0.3) 0px 8px 16px -8px; height:150px; width:auto;']);
                                }
                                echo $ret;
                                echo '<h4 class="text-center">'.$model->uCaptura->firmaa->abreviado_titulo.' '.$model->uCaptura->firmaa->nombre.'</h4>';
                                echo '<h4 class="text-center">'.$model->uCaptura->firmaa->universidad.'</h4>';
                                echo '<h4 class="text-center">Ced. Prof. '.$model->uCaptura->firmaa->cedula.'   Reg. S.S.A. '.$model->uCaptura->firmaa->registro_sanitario.'</h4>';
                                echo '<h4 class="text-center">'.$model->uCaptura->firmaa->titulo.'</h4>';
                            }
                            ?>
                        </td>
                        <td colspan="2" class="t5"></td>
                        <td colspan="9" class="t5 text-center">
                        <?php
                            if($model->firma_ruta){
                                $ret = '';
                                if($model->firmado == 1 && isset($model->firma_ruta) && $model->firma_ruta != ""){
                              
                                    $filePath =  'resources/Empresas/'.$model->id_empresa.'/Consultas/'.$model->id.'/'.$model->firma_ruta;
                                    $ret = Html::img($filePath, ['alt'=>' ', 'class' => "img-responsive", 'width' => '100px', 'height' =>'auto','style'=>'box-shadow: rgba(50, 50, 93, 0.25) 0px 13px 27px -5px, rgba(0, 0, 0, 0.3) 0px 8px 16px -8px; height:100px; width:auto;']);
                                }
                                echo $ret;
                                echo '<h4 class="text-center border-top">Firma del paciente</h4>';
                              
                            }
                            ?>
                        </td>
                    </tr>



                    <tr>
                        <td style="padding-top:20px;"></td>
                    </tr>
                    <tr>
                        <td colspan="3" class="">
                            Hora Consulta
                        </td>
                        <td colspan="2" class="color4 text-center">
                            Inicio: <?php echo date('H:i', strtotime($model->hora_inicio));?>
                        </td>
                        <td colspan="2" class="color4 text-center">
                            Fin: <?php echo date('H:i', strtotime($model->hora_fin));?>
                        </td>
                    </tr>



                </tbody>
            </table>

        </div>
    </div>
</body>

</html>