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
use app\models\Estudios;

?>
<?php
$tipoidentificacion = ['1'=>'INE','2'=>'PASAPORTE','3'=>'LICENCIA DE CONDUCIR','4'=>'GAFETE','5'=>'OTRO'];
?>

<body>
    <div class="container px-5">
        <div style="margin-bottom: 15px;">
            <h1 class="title text-center">ENTREGA DE RESULTADOS</h1>
            <h4 class=" text-center"><?php echo $model->nombre.' '.$model->apellidos;?></h4>
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
                        <td colspan="4" class="t5">
                            <b>Fecha:</b>
                            <?=$model->fecha_entrega?>
                        </td>
                        <td colspan="4" class="t5">
                            <b>Hora:</b>
                            <?=$model->hora_entrega?>
                        </td>
                        <td colspan="12" class="t5">
                        </td>
                    </tr>

                    <tr>
                        <td style="padding-top:15px;"></td>
                    </tr>

                    <tr>
                        <td colspan="20" class="t5">
                            POR MEDIO DE LA PRESENTE SIENDO EL DÍA <span
                                class="color3 font600"><?=$model->fecha_entrega?></span> A LAS <span
                                class="color3 font600"><?=$model->hora_entrega?></span> , QUIEN SUSCRIBE C. <span
                                class="color3 font600"><?php echo $model->nombre.' '.$model->apellidos;?></span>
                            , CONFIRMO QUE ME HAN SIDO ENTREGADOS EN FORMATO IMPRESO O DIGITAL LOS ANÁLISIS CLÍNICOS Y/Ó
                            ESTUDIOS
                            MOSTRADOS A CONTINUACIÓN:

                        </td>
                    </tr>

                    <tr>
                        <td style="padding-top:20px;"></td>
                    </tr>
                    
                    <tr>
                        <td colspan="20" class="bgbadges">
                            Estudios
                        </td>
                    </tr>
                    <?php
                    foreach($model->estudios as $key=>$estudio){
                        echo '<tr>
                            <td colspan="20" class="t5 color3 border-bottom">'.$estudio->estudio->nombre.'</td>
                            </tr>';
                    }
                    ?>



                    <tr>
                        <td style="padding-top:15px;"></td>
                    </tr>

                    <tr>
                        <td colspan="10" class="t5 text-center">
                        <td colspan="10" class="t5 text-center">
                            <?php
                            //dd($model->uCaptura);
                            if(true){
                                
                                $ret = '';
                                if($model->firmado == 1){
                                    /* $firma_trabajador =  '/resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id_trabajador.'/Poes/'.$model->firma_ruta;; */
                                    
                                    /* dd($firma_trabajador);
                                    $ret = Html::img('@web'. $firma_trabajador, ['alt'=>' ', 'class' => "img-responsive", 'width' => '150px', 'height' =>'auto','style'=>'box-shadow: rgba(50, 50, 93, 0.25) 0px 13px 27px -5px, rgba(0, 0, 0, 0.3) 0px 8px 16px -8px; height:150px; width:auto;']); */
                                    
                                }
                                echo $ret;
                                echo '<img src="resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id_trabajador.'/Poes/'.$model->firma_ruta.'" style="width:300px;">';
                                echo '<h4 class="text-center bordertop">Firma del trabajador</h4>';
                                
                            }
                            ?>
                        </td>


                        </td>
                    </tr>

                </tbody>
            </table>

        </div>
    </div>
</body>

</html>