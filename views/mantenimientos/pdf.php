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
use Da\QrCode\QrCode;
use app\models\DetalleConsentimiento;
use app\models\Consentimientos;
use app\models\Firmas;
use app\models\Firmaempresa;
?>

<?php
$tipomantenimiento = ['1' => 'PREVENTIVO', '2' => 'CORRECTIVO', '3'=> 'REVISIÓN', '4'=>'PUESTA EN MARCHA'];
$statusmaquina = ['1' => 'Funcionando Correctamente', '2' => 'Fuera de Servicio', '3'=> 'Pendiente de Repuesto'];
$statusatividad = ['100'=>'N/A: No Aplica','1'=>'BE: Buen Estado','2'=>'ME: Mal Estado','3'=>'F: Falta'];
?>

<body>
    <div class="container px-5">
        <div style="margin-bottom: 5px;">
            <h1 class="title text-center">FORMATO DE MANTENIMIENTO PREVENTIVO DE MAQUINARIA</h1>
        </div>
        <div style="margin-bottom: 5px;">
            <h5>
               
            </h5>
        </div>
        <div>

            <table style="line-height: 1.5; font-size:12px;width:100%;border-collapse: collapse;">
                <tbody>
                    <tr>
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
                        <td colspan="12" class="t5">
                            <b>Tipo Mantenimiento:</b>
                            <?php
                            if (array_key_exists($model->tipo_mantenimiento, $tipomantenimiento)) {
                                echo $tipomantenimiento[$model->tipo_mantenimiento];
                            }
                            ?>
                        </td>
                        <td colspan="4" class="t5">
                            <b>Folio:</b>
                            <?=$model->clave?>
                        </td>
                        <td colspan="4" class="t5">
                            <b>Fecha:</b>
                            <?=$model->fecha?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="20" class="t5">
                            <b>Maquina:</b>
                            <?=$model->nombre?>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-top:10px;"></td>
                    </tr>
                    <tr>
                        <td colspan="6" class="t5">
                            <b>Marca:</b>
                            <?=$model->marca?>
                        </td>
                        <td colspan="6" class="t5">
                            <b>Modelo:</b>
                            <?=$model->modelo?>
                        </td>
                        <td colspan="6" class="t5">
                            <b>Num Serie:</b>
                            <?=$model->numero_serie?>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-top:10px;"></td>
                    </tr>
                    <tr>
                        <td colspan="20" class="t5">
                            <b>Realiza Mantenimiento:</b>
                            <?=$model->realiza_mantenimiento?>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-top:10px;"></td>
                    </tr>

                    <tr>
                        <td colspan="20" class="bgbadges">
                            Revisiones Efectuadas
                        </td>
                    </tr>
                    <tr>
                        <td colspan="14" class="t5"><b>Actividad</b></td>
                        <td colspan="6" class="t5"><b>Status</b></td>
                    </tr>
                    <?php
                    foreach($model->actividades as $key=>$actividad){
                        $status = '';
                        if (array_key_exists($actividad->status, $statusatividad)) {
                            $status = $statusatividad[$actividad->status];
                        }
                        echo '<tr>
                            <td colspan="14" class="t5 color3 border-bottom">'.$actividad->actividad.'</td>
                            <td colspan="6" class="t5 border-bottom">'.$status.'</td>
                            </tr>';
                    }
                    ?>
                    <tr>
                        <td style="padding-top:20px;"></td>
                    </tr>
                    <tr>
                        <td colspan="20" class="bgbadges">
                            Equipo/Herramientas/Accesorios
                        </td>
                    </tr>
                    <tr>
                        <td colspan="5" class="t5"><b>Elemento</b></td>
                        <td colspan="4" class="t5"><b>N° Serie</b></td>
                        <td colspan="7" class="t5"><b>Observaciones</b></td>
                        <td colspan="4" class="t5"><b>Status</b></td>
                    </tr>
                    <?php
                    foreach($model->componentes as $key=>$componente){
                        $status = '';
                        if (array_key_exists($componente->status, $statusatividad)) {
                            $status = $statusatividad[$componente->status];
                        }
                        echo '<tr>
                            <td colspan="5" class="t5 color3 border-bottom">'.$componente->componente.'</td>
                            <td colspan="4" class="t5 color3 border-bottom">'.$componente->numero_serie.'</td>
                            <td colspan="7" class="t5 color3 border-bottom">'.$componente->descripcion.'</td>
                            <td colspan="4" class="t5 border-bottom">'.$status.'</td>
                            </tr>';
                    }
                    ?>

                    <tr>
                        <td style="padding-top:20px;"></td>
                    </tr>
                    <tr>
                        <td colspan="4" class="t5"><b>Descripción del mantenimiento realizado:</b></td>
                        <td colspan="17" class="t5 borderall">
                            <?php
                            echo $model->descripcion;
                            ?>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding-top:15px;"></td>
                    </tr>
                    <tr>
                        <td colspan="6" class="t5"></td>
                        <td colspan="8" class="t5 text-center">
                            <?php
                            if($model->ruta_firma1){
                                //dd($model->ruta_firma1);
                                
                                $ret = '';
                                if($model->firmado == 1 && isset($model->ruta_firma1) && $model->ruta_firma1 != ""){
                              
                                    $filePath =  '/resources/Empresas/'.$model->id_empresa.'/Maquinarias/'.$model->id_maquina.'/'.$model->ruta_firma1;
                                    $ret = Html::img('@web'. $filePath, ['alt'=>' ', 'class' => "img-responsive", 'width' => '100px', 'height' =>'auto','style'=>'box-shadow: rgba(50, 50, 93, 0.25) 0px 13px 27px -5px, rgba(0, 0, 0, 0.3) 0px 8px 16px -8px; height:100px; width:auto;']);
                                }
                                echo $ret;
                                echo '<h4 class="text-center bordertop">Firmado</h4>';
                              
                            }
                            ?>
                        </td>
                    </tr>

                </tbody>
            </table>

        </div>
    </div>
</body>

</html>