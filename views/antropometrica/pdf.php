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
use app\models\Trabajadores;
use app\models\Areascuestionario;
use app\models\Cuestionario;
use app\models\DetalleCuestionario;
use app\models\Preguntas;
use app\models\Usuarios;

use yii\helpers\ArrayHelper;

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
$cuestionario = $model;


$paciente = Trabajadores::findOne($cuestionario->id_paciente);
$preguntas = Preguntas::find()->where(["id_tipo_cuestionario" => $cuestionario->id_tipo_cuestionario])->andWhere(["!=", "pregunta", "_general"])->andWhere(["status" => 1])->all();

$preguntas_general = Preguntas::find()->where(["id_tipo_cuestionario" => $cuestionario->id_tipo_cuestionario])->andWhere(["pregunta" => "_general"])->andWhere(["status" => 1])->one();
            
$det_cuestionario_general = [];

$det_cuestionario = DetalleCuestionario::find()->where(['id_cuestionario' => $cuestionario->id])->andWhere(['status' => 1]);
if ($preguntas_general) {
    $det_cuestionario = $det_cuestionario->andWhere(['!=', 'id_pregunta', $preguntas_general->id]);
    $det_cuestionario_general = DetalleCuestionario::find()->where(['id_cuestionario' => $cuestionario->id])->andWhere(["id_pregunta" => $preguntas_general->id])->andWhere(['status' => 1])->all();
    $det_cuestionario_general = ArrayHelper::map($det_cuestionario_general, 'id_area', 'respuesta_1', 'id_pregunta');
}
$det_cuestionario = $det_cuestionario->all();

$det_cuestionario_ = ArrayHelper::map($det_cuestionario, 'id_area', 'respuesta_1', 'id_pregunta');         
           

$preguntas = ArrayHelper::map($preguntas, 'id', 'pregunta');


$_areas = Areascuestionario::findAll(['id_tipo_cuestionario' => 4, 'status' => 1]);

$_areas = ArrayHelper::map($_areas, 'id', 'nombre');

$sexo = '';
$fecha_nacimiento = '';
$edad = '';
$puesto = '';
$area = '';
$nombre_paciente = '';
$no_empleado = '';

$peso = "";
$talla = "";
$comentarios = ""; 

$fecha = date('Y-m-d', strtotime($model->fecha_cuestionario));

if($cuestionario->trabajador){
    $nombre_paciente = $cuestionario->trabajador->nombre.' '.$cuestionario->trabajador->apellidos;
    $fecha_nacimiento = $cuestionario->fecha_nacimiento;
    $edad = $cuestionario->edad;

    ($cuestionario->trabajador->sexo == 1) ? $sexo = 'MASCULINO' : $sexo = 'FEMENINO';

    $no_empleado = $cuestionario->trabajador->num_trabajador;

    if($cuestionario->area){
        $area = $cuestionario->area->area;
    }

    if($cuestionario->puesto){
        $puesto = $cuestionario->puesto->nombre;
    }
}

$linea = ""; //$paciente->linea;

$f_cuestionario = $cuestionario->fecha_cuestionario;
$empresa = $cuestionario->nombre_empresa;


if($cuestionario){
    $det_cuestionario = DetalleCuestionario::find()->where(['id_cuestionario' => $cuestionario->id])->andWhere(['status' => 1])->all();
    $tabla = ArrayHelper::map($det_cuestionario, 'id_area', 'respuesta_1', 'id_pregunta'); 
}


$tabla_detalles = $det_cuestionario_general;
//dd($tabla_detalles);

//dd($tabla);
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
        if($model->empresa->mostrar_nivel_pdf == 1){
            $nombre_empresa = $model->empresa->comercial;
        }

        $label_nivel1 = $dataempresa->label_nivel1;
        $label_nivel2 = $dataempresa->label_nivel2;
        $label_nivel3 = $dataempresa->label_nivel3;
        $label_nivel4 = $dataempresa->label_nivel4;

        if($dataempresa->cantidad_niveles >= 1){
            $show_nivel1 = true;

            if($model->empresa->mostrar_nivel_pdf == 2 && $model->nivel1){
                if($model->nivel1->pais){
                    
                    $nombre_empresa = $model->nivel1->pais->pais;
                }
            }
        }
        if($dataempresa->cantidad_niveles >= 2){
            $show_nivel2 = true;

            if($model->empresa->mostrar_nivel_pdf == 2 && $model->nivel2){
                $nombre_empresa = $model->nivel2->nivelorganizacional2;
            }
        }
        if($dataempresa->cantidad_niveles >= 2){
            $show_nivel3 = true;

            if($model->empresa->mostrar_nivel_pdf == 2 && $model->nivel3){
                $nombre_empresa = $model->nivel3->nivelorganizacional3;
            }
        }
        if($dataempresa->cantidad_niveles >= 4){
            $show_nivel4 = true;

            if($model->empresa->mostrar_nivel_pdf == 2 && $model->nivel4){
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
            <h1 class="title text-center">EVALUACIÓN ANTROPOMÉTRICA</h1>
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
                        <td colspan="3" class="t5">
                            <b>Fecha:</b>
                        </td>
                        <td colspan="17" class="t5">
                            <?=$fecha?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" class="t5">
                            <b>Empresa:</b>
                        </td>
                        <td colspan="17" class="t5">
                            <?=$nombre_empresa?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" class="t5">
                            <b>N° Empleado:</b>
                        </td>
                        <td colspan="17" class="t5">
                            <?=$no_empleado?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" class="t5">
                            <b>Nombre:</b>
                        </td>
                        <td colspan="17" class="t5">
                            <?=$nombre_paciente?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" class="t5">
                            <b>Sexo:</b>
                        </td>
                        <td colspan="17" class="t5">
                            <?=$sexo?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" class="t5">
                            <b>F. Nacimiento:</b>
                        </td>
                        <td colspan="17" class="t5">
                            <?=$fecha_nacimiento?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" class="t5">
                            <b>Edad:</b>
                        </td>
                        <td colspan="17" class="t5">
                            <?=$edad?>
                        </td>
                    </tr>

                    <tr>
                        <?php if($show_nivel1 == 'block'):?>
                        <td colspan="3" class="t5" style="display:<?=$show_nivel1?>;">
                            <b><?=$label_nivel1;?>:</b>
                        </td>
                        <td colspan="17" class="t5" style="display:<?=$show_nivel1?>;">
                            <?php
                            if($model->nivel1){
                                if($model->nivel1->pais){
                                    echo $model->nivel1->pais->pais;
                                }
                            }
                            ?>
                        </td>
                        <?php endif; ?>
                    </tr>

                    <tr>
                        <?php if($show_nivel2 == 'block'):?>
                        <td colspan="3" class="t5" style="display:<?=$show_nivel2?>;">
                            <b><?=$label_nivel2;?>:</b>
                        </td>
                        <td colspan="17" class="t5" style="display:<?=$show_nivel2?>;">
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
                        <td colspan="3" class="t5" style="display:<?=$show_nivel2?>;">
                            <b><?=$label_nivel3;?>:</b>
                        </td>
                        <td colspan="17" class="t5" style="display:<?=$show_nivel3?>;">
                            <?php
                            if($model->nivel3){
                                echo $model->nivel3->nivelorganizacional3;
                            }
                            ?>
                        </td>
                        <?php endif; ?>
                    </tr>

                    <tr>
                        <?php if($show_nivel4 == 'block'):?>
                        <td colspan="3" class="t5" style="display:<?=$show_nivel4?>;">
                            <b><?=$label_nivel4;?>:</b>
                        </td>
                        <td colspan="17" class="t5" style="display:<?=$show_nivel4?>;">
                            <?php
                            if($model->nivel4){
                                echo $model->nivel4->nivelorganizacional4;
                            }
                            ?>
                        </td>
                        <?php endif; ?>
                    </tr>

                    
                    <tr>
                        <td colspan="3" class="t5">
                            <b>Puesto:</b>
                        </td>
                        <td colspan="17" class="t5">
                            <?=$puesto?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" class="t5">
                            <b>Area:</b>
                        </td>
                        <td colspan="17" class="t5">
                            <?=$area?>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding-top:15px;"></td>
                    </tr>

                    <?php
                    foreach ($tabla_detalles as $key => $pr) {
                    
                        foreach ($pr as $key2 => $row) {

                            echo '<tr>';
                            echo ' <td colspan="10" class="borderallgray text-12 bgbadgesgray">
                            '.$_areas[$key2].'
                            </td>';
                            echo ' <td colspan="10" class="borderallgray text-12">
                            '.$row.'
                            </td>';
                            echo '</tr>';
                        }

                        echo '<tr><td style="padding-top:10px;"></td></tr>';

                    }

                    //dd($tabla);
                    //dd($preguntas);
                    $index = 1;
                    foreach ($tabla as $key => $pr) {

                        if($key != 32){
                            echo '<tr><td colspan="20" class="bgbadges text-left">'.$preguntas[$key].'</td></tr>';

                            foreach ($pr as $key2 => $row) {
    
                                echo '<tr>';
                                echo ' <td colspan="10" class="borderallgray text-12 bgbadgesgray">
                                '.$_areas[$key2].' - '.$preguntas[$key].'
                                </td>';
                                echo ' <td colspan="10" class="borderallgray text-12">
                                '.$row.' cm
                                </td>';
                                echo '</tr>';

                                $index++;
                            }
    
                            echo '<tr><td style="padding-top:10px;"></td></tr>';
                        }
                        
                    }
                    ?>

                    <tr>
                        <td style="padding-top:5px;"></td>
                    </tr>

                    <tr>
                    <td colspan="9" class="t5 text-center">
                        </td>
                        <td colspan="2" class="t5"></td>
                        <td colspan="9" class="t5 text-center">
                            <?php
                            //dd($model->uCaptura);
                            if($model->uMedico && $model->uMedico->firmaa){
                                $ret = '';
                                if($model->firmado == 1 && isset($model->uMedico->firmaa->firma) && $model->uMedico->firmaa->firma != ""){
                              
                                    $filePath =  '/resources/firmas/'.$model->uMedico->firmaa->firma;
                                    $ret = Html::img('@web'. $filePath, ['alt'=>' ', 'class' => "img-responsive", 'width' => '150px', 'height' =>'auto','style'=>'box-shadow: rgba(50, 50, 93, 0.25) 0px 13px 27px -5px, rgba(0, 0, 0, 0.3) 0px 8px 16px -8px; height:150px; width:auto;']);
                                }
                                echo $ret;
                                echo '<h4 class="text-center bordertop">Firma del medico</h4>';
                                echo '<h4 class="text-center">'.$model->uMedico->firmaa->abreviado_titulo.' '.$model->uMedico->firmaa->nombre.'</h4>';
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