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
?>
<?php
$tipoexamen = ['3'=>'CLÍNICA','1'=>'ACCIDENTE','7'=>'PROGRAMAS DE SALUD','4'=>'INCAPACIDAD','6'=>'TRABAJOS DE RIESGO','2'=>'ANTIDOPING','5'=>'PREOCUPANTE','8'=>'COVID-19','9'=>'NUTRICIÓN','10'=>'PSICOLÓGICA'];
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
            <h1 class="title text-center">REGISTRO DE INCAPACIDAD</h1>
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
                            <?=$model->trabajador->nombre?>
                        </td>
                        <td colspan="12" class="t5">
                            <b>Apellidos:</b>
                            <?=$model->trabajador->apellidos?>
                        </td>
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


                    <?php if($model->tipo == 4):?>
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
                        </td>
                        <td colspan="15" class="borderall">
                            <?=date('d-m-Y', strtotime($model->incapacidad_fechainicio)); ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="5" class="t5">
                            <b>Días:</b>
                        </td>
                        <td colspan="15" class="borderall">
                            <?=$model->incapacidad_dias; ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="5" class="t5">
                            <b>Fecha Fin:</b>
                        </td>
                        <td colspan="15" class="borderall">
                            <b><?=date('d-m-Y', strtotime($model->incapacidad_fechafin)); ?></b>
                        </td>
                    </tr>
                
                    <?php endif;?>


                    <tr>
                        <td style="padding-top:20px;"></td>
                    </tr>

                    <tr>
                        <td colspan="5" class="t5">
                            <b>Diagnóstico:</b>
                        </td>
                        <td colspan="15" class="borderall">
                            <?=$model->diagnostico ?>
                        </td>
                    </tr>


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
                                echo '<h4 class="text-center border-top">Firma del trabajador</h4>';
                              
                            }
                            ?>
                        </td>
                    </tr>



                    <tr>
                        <td style="padding-top:20px;"></td>
                    </tr>
                    <tr>
                        <td colspan="3" class="">
                            Hora Registro
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