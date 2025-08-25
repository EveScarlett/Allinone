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

use app\models\Empresas;
use app\models\NivelOrganizacional1;
use app\models\NivelOrganizacional2;
use app\models\NivelOrganizacional3;
use app\models\NivelOrganizacional4;

use app\models\Ubicaciones;
use app\models\Paises;
use app\models\Paisempresa;

use app\models\Poes;
use app\models\Poeestudio;
?>

<?php
$array_sexo = ['1' => 'MASCULINO', '2' => 'FEMENINO'];
$array_conclusion = [
    '1'=>'SANO Y APTO PARA EL PUESTO',
    '2'=>'NO APTO',
    '3'=>'REQUIERE MEJORAR SALUD, APTO PARA EL PUESTO',
];
$tipoexamen = ['1'=>'NUEVO INGRESO','2'=>'PRIMERA VEZ','3'=>'PERIODICO','4'=>'PREEXISTENTE','5'=>'SALIDA'];
$estadocivil = ['1'=>'Soltero','2'=>'Casado','3'=>'Viudo','4'=>'Unión Libre'];
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
        <div style="margin-bottom: 5px;">
            <h1 class="title text-center">APTITUD MÉDICA LABORAL</h1>
        </div>

        <div style="margin-bottom: 10px;">
            <?php
            $text_vigencia = '';
            $vigencia = '';
            if($model->datavigencia){
                $text_vigencia = $model->datavigencia->vigencia;

                if($model->fecha_vigencia != null && $model->fecha_vigencia != '' && $model->fecha_vigencia != ' '){
                    $vigencia = 'hasta el ';
                    $vigencia .= date('d/m/Y', strtotime($model->fecha_vigencia));
                }
            }
            //$vigencia = date('Y-m-d', strtotime($model->fecha. ' + 1 years'));
            ?>
            <h1 class="titlev text-center">VIGENCIA <?= $text_vigencia.' '.$vigencia?></h1>
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
                        <td colspan="6" class="t5">
                            <b>Folio:</b>
                            <?=$model->folio?>
                        </td>
                        <td colspan="14" class="t5">
                            <b>Fecha:</b>
                            <?=date('d/m/Y', strtotime($model->fecha));?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="10" class="t5">
                            <b>Empresa:</b>
                            <?=$nombre_empresa;?>
                        </td>
                        <td colspan="10" class="t5">
                            <?php
                            $area = '';
                            if($model->areadata){//aqui estoy
                                $area = $model->areadata->area;
                            }
                            ?>
                            <b>Área:</b>
                            <?=$area?>
                        </td>
                    </tr>



                    <tr>
                        <?php if($show_nivel1 == 'block'):?>
                        <td colspan="10" class="t5" style="display:<?=$show_nivel1?>;">
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
                        <td colspan="10" class="t5" style="display:<?=$show_nivel2?>;">
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
                        <td colspan="10" class="t5" style="display:<?=$show_nivel3?>;">
                            <b><?=$label_nivel3;?>:</b>
                            <?php
                            if($model->nivel3){
                                echo $model->nivel3->nivelorganizacional3;
                            }
                            ?>
                        </td>
                        <?php endif; ?>

                        <?php if($show_nivel4 == 'block'):?>
                        <td colspan="10" class="t5" style="display:<?=$show_nivel4?>;">
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
                        <td style="padding-top:10px;"></td>
                    </tr>
                    <tr>
                        <td colspan="10" class="t5">
                            <?php
                            $puesto = '';
                            if($model->puestodata){//aqui estoy
                                $puesto = $model->puestodata->nombre;
                            }
                            ?>
                            <b>Puesto:</b>
                            <?=$puesto?>
                        </td>
                        <td colspan="10" class="t5">
                            <?php
                            $examen = '';
                            if($model->examen){//aqui estoy
                                $examen = $tipoexamen[$model->examen];
                            }
                            ?>
                            <b>Tipo de Examen:</b>
                            <?=$examen?>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-top:10px;"></td>
                    </tr>

                    <tr>
                        <td colspan="20" class="bgbadges">
                            Ficha de Identificación
                        </td>
                    </tr>
                    <tr>
                        <td colspan="8" class="t5">
                            <b>Nombre:</b>
                            <?=$model->nombre?>
                        </td>
                        <td colspan="8" class="t5">
                            <b>Apellidos:</b>
                            <?=$model->apellidos?>
                        </td>
                        <td colspan="4" class="t5">
                            <b>Sexo:</b>
                            <?=$array_sexo[$model->sexo]?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="6" class="t5">
                            <?php
                            $estado = '';
                            if($model->estado_civil){//aqui estoy
                                $estado = $estadocivil[$model->estado_civil];
                            }
                            ?>
                            <b>Estado Civil:</b>
                            <?=$estado?>
                        </td>
                        <td colspan="6" class="t5">
                            <b>Fecha de nacimiento:</b>
                            <?=$model->fecha_nacimiento?>
                        </td>
                        <td colspan="6" class="t5">
                            <b>Edad:</b>
                            <?=$model->edad?>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding-top:5px;"></td>
                    </tr>
                    <tr>
                        <td colspan="20" class="bgbadges">
                            Conclusión Médica Laboral
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-top:10px;"></td>
                    </tr>
                    <?php if($model->recomendaciones == null || $model->recomendaciones == '' || $model->recomendaciones == ' '):?>
                    <tr>
                        <td colspan="4" class="t5"><b>Comentarios:</b></td>
                        <td colspan="17" class="t5 borderall"><?= $model->comentarios;?></td>
                    </tr>
                    <?php else:?>
                    <tr>
                        <td colspan="4" class="t5"><b>Recomendaciones:</b></td>
                        <td colspan="17" class="t5 borderall"><?= $model->recomendaciones;?></td>
                    </tr>
                    <?php endif;?>
                    
                    <tr>
                        <td style="padding-top:10px;"></td>
                    </tr>
                    <tr>
                        <td colspan="4" class="t5"><b>Conclusión Laboral:</b></td>
                        <td colspan="17" class="t5 borderall">
                            <?php
                            if(isset($model->conclusion_cal) && $model->conclusion_cal != null && $model->conclusion_cal != '' && $model->conclusion_cal != ' '){
                                echo $array_conclusion[$model->conclusion_cal];
                            }
                            ?>
                        </td>
                    </tr>



                    <tr>
                        <td style="padding-top:10px;"></td>
                    </tr>
                    <tr>
                        <td colspan="20" class="bgbadges"> Estudios</td>
                    </tr>
                    <tr>
                        <td style="padding-top:10px;"></td>
                    </tr>
                    <?php
                    $poe = Poes::findOne($model->id_poe);
                    
                    $estudios_poes = null;
                    if($poe){
                        $estudios_poes = Poeestudio::find()->where(['id_poe'=>$poe->id])->andWhere(['not in','id_estudio',[1,21]])->all();
                    }
                    //dd($model,$poe,$estudios_poes);

                    if($model->testudios){
                        $array_conclusion = ['0'=>'PENDIENTE','1'=>'BUENO','2'=>'REGULAR','3'=>'MALO'];

                        echo '<tr>
                        <td colspan="4" class="t5 color3 border-bottom"><b>CATEGORIA</b></td>
                        <td colspan="4" class="t5 color3 border-bottom"><b>ESTUDIO</b></td>
                        <td colspan="4" class="t5 color3 border-bottom"><b>CONCLUSION</b></td>
                        <td colspan="8" class="t5 border-bottom"><b>COMENTARIOS</b></td>
                        </tr>';
                        foreach($model->testudios as $key=>$estudio){
                            $est_categoria = '';
                            $est_estudio = '';
                            $est_conclusion = '';
                            $est_comentarios = $estudio->comentario;

                            if (array_key_exists($estudio->conclusion, $array_conclusion)) {
                                $est_conclusion = $array_conclusion[$estudio->conclusion];
                            }

                            if($estudio->categoria){
                                $est_categoria = $estudio->categoria->nombre;
                            }

                            if($estudio->estudio){
                                $est_estudio = $estudio->estudio->nombre;
                            }

                            echo '<tr>
                                <td colspan="4" class="text-11 border-bottom">'.$est_categoria.'</td>
                                <td colspan="4" class="text-11 border-bottom">'.$est_estudio.'</td>
                                <td colspan="4" class="text-11 border-bottom">'.$est_conclusion.'</td>
                                <td colspan="8" class="text-11 border-bottom">'.$est_comentarios.'</td>
                                </tr>';
                        }
                    } else if($estudios_poes){
                        $array_conclusion = ['100'=>'SIN AVANCE','0'=>'PENDIENTE','1'=>'BIEN','2'=>'REGULAR','3'=>'MAL'];

                        echo '<tr>
                        <td colspan="4" class="t5 color3 border-bottom"><b>CATEGORIA</b></td>
                        <td colspan="4" class="t5 color3 border-bottom"><b>ESTUDIO</b></td>
                        <td colspan="4" class="t5 color3 border-bottom"><b>CONCLUSION</b></td>
                        <td colspan="8" class="t5 border-bottom"><b>COMENTARIOS</b></td>
                        </tr>';
                        foreach($estudios_poes as $key=>$estudio){
                            $est_categoria = '';
                            $est_estudio = '';
                            $est_conclusion = '';
                            $est_comentarios = $estudio->comentario;

                            if (array_key_exists($estudio->condicion, $array_conclusion)) {
                                $est_conclusion = $array_conclusion[$estudio->condicion];
                            }

                            if($estudio->tipo){
                                $est_categoria = $estudio->tipo->nombre;
                            }

                            if($estudio->estudio){
                                $est_estudio = $estudio->estudio->nombre;
                            }

                            echo '<tr>
                                <td colspan="4" class="text-11 border-bottom">'.$est_categoria.'</td>
                                <td colspan="4" class="text-11 border-bottom">'.$est_estudio.'</td>
                                <td colspan="4" class="text-11 border-bottom">'.$est_conclusion.'</td>
                                <td colspan="8" class="text-11 border-bottom">'.$est_comentarios.'</td>
                                </tr>';
                        }
                    } else {
                        echo '<tr><td colspan="20" class="text-11 border-bottom">NINGUNO</td></tr>';
                    }
                    
                    ?>


                    <tr>
                        <td style="padding-top:15px;"></td>
                    </tr>

                    <tr>
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
                                echo '<h4 class="text-center bordertop">'.$model->uMedico->firmaa->abreviado_titulo.' '.$model->uMedico->firmaa->nombre.'</h4>';
                                echo '<h4 class="text-center">'.$model->uMedico->firmaa->universidad.'</h4>';
                                echo '<h4 class="text-center">Ced. Prof. '.$model->uMedico->firmaa->cedula.'   Reg. S.S.A. '.$model->uMedico->firmaa->registro_sanitario.'</h4>';
                                echo '<h4 class="text-center">'.$model->uMedico->firmaa->titulo.'</h4>';
                            }
                            ?>
                        </td>
                        <td colspan="2" class="t5"></td>
                        <td colspan="9" class="t5 text-center">
                            <?php
                            $id_empresas = [];
                            $id_firmas = [];
                            $firmaempresa = Firmaempresa::find()->where(['id_empresa'=>$model->id_empresa])->all();
                             
                            foreach($firmaempresa as $key=>$fe){
                                array_push($id_empresas, $fe->id_empresa);
                                array_push($id_firmas, $fe->id_firma);
                            }
                             
                            $medicolaboral = Firmas::find()->where(['in','id',$id_firmas])->andWhere(['tipo'=>2])->andWhere(['<=','fecha_inicio', $model->fecha])->andWhere(['>=','fecha_fin', $model->fecha])->one();
 
                            //$medicolaboral = Firmas::find()->where(['in','id_empresa',$id_empresas])->andWhere(['tipo'=>2])->andWhere(['<=','fecha_inicio', $model->fecha])->andWhere(['>=','fecha_fin', $model->fecha])->one();
                            //$medicolaboral = Firmas::find()->where(['id_empresa'=>$model->id_empresa])->andWhere(['tipo'=>2])->andWhere(['<=','fecha_inicio', $model->fecha])->andWhere(['>=','fecha_fin', $model->fecha])->one();
                            //dd($model->uCaptura);
                            if($medicolaboral){
                                
                                $ret = '';
                                if($model->firmado == 1 && isset($medicolaboral->firma) && $medicolaboral->firma != ""){
                              
                                    $filePath =  '/resources/firmas/'.$medicolaboral->firma;
                                    $ret = Html::img('@web'. $filePath, ['alt'=>' ', 'class' => "img-responsive", 'width' => '150px', 'height' =>'auto','style'=>'box-shadow: rgba(50, 50, 93, 0.25) 0px 13px 27px -5px, rgba(0, 0, 0, 0.3) 0px 8px 16px -8px; height:150px; width:auto;']);
                                }
                                echo $ret;
                                echo '<h4 class="text-center bordertop">'.$medicolaboral->abreviado_titulo.' '.$medicolaboral->nombre.'</h4>';
                                echo '<h4 class="text-center">'.$medicolaboral->universidad.'</h4>';
                                echo '<h4 class="text-center">Ced. Prof. '.$medicolaboral->cedula.'   Reg. S.S.A. '.$medicolaboral->registro_sanitario.'</h4>';
                                echo '<h4 class="text-center">'.$medicolaboral->titulo.'</h4>';
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="6" class="t5"></td>
                        <td colspan="8" class="t5 text-center">
                            <?php
                            if($model->firma_ruta){
                                //dd($model->firma_ruta);
                                $ret = '';
                                if($model->firmado == 1 && isset($model->firma_ruta) && $model->firma_ruta != ""){
                              
                                    $filePath =  '/resources/Empresas/'.$model->id_empresa.'/HC/'.$model->id.'/'.$model->firma_ruta;
                                    $ret = Html::img('@web'. $filePath, ['alt'=>' ', 'class' => "img-responsive", 'width' => '100px', 'height' =>'auto','style'=>'box-shadow: rgba(50, 50, 93, 0.25) 0px 13px 27px -5px, rgba(0, 0, 0, 0.3) 0px 8px 16px -8px; height:100px; width:auto;']);
                                }
                                echo $ret;
                                echo '<h4 class="text-center bordertop">FIRMA DEL PACIENTE</h4>';
                              
                            }
                            ?>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding-top:20px;"></td>
                    </tr>
                    <tr>
                        <td colspan="20" class="t5 text-center">
                            <?php
                           $base = 'https://nuevoohc.test';
                           $qrCode = (new QrCode( $base.'/index.php?r=hccohc%2Fqrcal&id='.$model->id))
                           ->setSize(250)
                           ->setMargin(5)
                           ->useForegroundColor(0, 0, 0);

                           echo '<img src="' . $qrCode->writeDataUri() . '" class="img-fluid float-end" style="width:140px;">';
                            ?>

                        </td>
                    </tr>


                </tbody>
            </table>

        </div>
    </div>
</body>

</html>