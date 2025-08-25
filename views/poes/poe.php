<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

use app\models\Poes;
use app\models\Poeestudio;
?>
<?php
$diagnosticos = ['100'=>'SIN AVANCE','0'=>'PENDIENTE','1'=>'BIEN','2'=>'REGULAR','3'=>'MAL'];
$evoluciones = ['100'=>'SIN AVANCE','0'=>'PENDIENTE','5'=>'INICIAL','1'=>'IGUAL','2'=>'MEJOR','3'=>'PEOR','4'=>'N/A'];
$colordiag = ['100'=>'bg-light','0'=>'color6','1'=>'color7','2'=>'color12','3'=>'color11'];
$colores = ['100'=>'bg-light','0'=>'bgpendiente','1'=>'bgcumple','2'=>'bgregular','3'=>'bgnocumple'];

 $image = '<span class="" style="font-size:20px"><i class="bi bi-file-pdf-fill"></i></span>';
 $image2 = '<span class="" style="font-size:20px"><i class="bi bi-file-pdf-fill"></i></span>';
?>


<div class="container-fluid p-4">
    <?php
    //dd($model);
    $fondos = ['bgcolor1','bgcolor2','bgcolor3','bgcolor4','bgcolor5'];
    $bordes = ['bordercolor1','bordercolor2','bordercolor3','bordercolor4','bordercolor5'];
    $show = '';
    $numero = rand(0, 4);

    $puestoanterior = '';
    if($poeanterior->puesto){
        $puestoanterior = $poeanterior->puesto->nombre;
    }

    $areaanterior = '';
    if($poeanterior->area){
        $areaanterior = $poeanterior->area->area;
    }

    $n_imss = '';
    if($poeanterior->trabajador){
        $n_imss = $poeanterior->trabajador->num_imss;
    }

    $show = 'show'; 

    $ret = '
    <div class="card '.$bordes[$numero].'">
            <div class="card-header '.$fondos[$numero].' title2 ">
                <a class="btn" data-bs-toggle="collapse" href="#collapse'.$poeanterior->id.'">
                    POE '.$poeanterior->anio.' | '.$poeanterior->nombre.' '.$poeanterior->apellidos.'
                </a>
            </div>
            <div id="collapse'.$poeanterior->id.'" class="collapse '.$show.'" data-bs-parent="#accordion">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-2">
                            <label class="control-label">Año</label>
                            <div class="form-control">
                                '.$poeanterior->anio.'
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <label class="control-label">Puesto de Trabajo</label>
                            <div class="form-control">
                                '.$puestoanterior.'
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <label class="control-label">Área</label>
                            <div class="form-control">
                            &nbsp;'.$areaanterior.'
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <label class="control-label">N° IMSS</label>
                            <div class="form-control">
                                 '.$n_imss.'
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <table class="table table-sm font12">
                            <thead class="table-dark">
                                <tr>
                                <th>#</th><th>Categoria</th><th>Estudio</th><th>Fecha</th><th>Evidencia</th><th>Diagnóstico</th><th>Evolución</th><th>Comentarios</th>
                                </tr>
                            </thead>
                            <tbody>';

                            if($poeanterior->estudios){
                                $poesanteriores = Poeestudio::find()->where(['id_poe'=>$poeanterior->id])->orderBy(['orden'=>SORT_ASC])->all();
                                foreach ($poesanteriores as $key2=>$estudio2){
                                    //dd($estudio2);

                                    $est_categoria = '';
                                    $est_estudio = '';
                                    $est_fecha = '';
                                    $est_evidencia = '';
                                    $est_diagnostico = '';
                                    $est_evolucion = '';
                                    $est_comentario = '';

                                    if($estudio2->tipo){
                                        $est_categoria = $estudio2->tipo->nombre; 
                                    } 
                                    
                                    if($estudio2->estudio){
                                        $est_estudio = $estudio2->estudio->nombre; 
                                    }

                                    $est_fecha = $estudio2->fecha;

                                    if(isset($estudio2->evidencia)){
                                        $filePath = 'resources/Empresas/'.$poeanterior->id_empresa.'/Trabajadores/'.$poeanterior->id_trabajador.'/Poes/'.$estudio2->evidencia;
                                        $pdf = Html::a('<span style="font-size:15px;" class="color3 mx-1">'.$image.'</span>', $filePath, $options = ['target'=>'_blank']);
                                        $est_evidencia .= $pdf;
                                    }
        
                                    if(isset($estudio2->evidencia2)){
                                        $filePath = 'resources/Empresas/'.$poeanterior->id_empresa.'/Trabajadores/'.$poeanterior->id_trabajador.'/Poes/'.$estudio2->evidencia2;
                                        $pdf2 = Html::a('<span style="font-size:15px;" class="color4 mx-1">'.$image2.'</span>', $filePath, $options = ['target'=>'_blank']);
                                        $est_evidencia .= $pdf2;
                                    }
        
                                    if(isset($estudio2->evidencia3)){
                                        $filePath = 'resources/Empresas/'.$poeanterior->id_empresa.'/Trabajadores/'.$poeanterior->id_trabajador.'/Poes/'.$estudio2->evidencia3;
                                        $pdf3 = Html::a('<span style="font-size:15px;" class="color7 mx-1">'.$image2.'</span>', $filePath, $options = ['target'=>'_blank']);
                                        $est_evidencia .= $pdf3;
                                    }

                                    if (array_key_exists($estudio2->condicion, $diagnosticos)) {
                                        $est_diagnostico = $diagnosticos[$estudio2->condicion];
                                        $color = $colordiag[$estudio2->condicion];
                                        $colorbg = $colores[$estudio2->condicion];
                                    }

                                    if (array_key_exists($estudio2->evolucion, $evoluciones)) {
                                        $est_evolucion = $evoluciones[$estudio2->evolucion];
                                    }

                                    $est_comentario = $estudio2->comentario;
                                    $ret .= 
                                    '<tr>
                                    <td class="font-11">'.($key2+1).'</td>
                                    <td class="font-11">'.$est_categoria.'</td>
                                    <td>'.$est_estudio.'</td>
                                    <td>'.$est_fecha.'</td>
                                    <td>'.$est_evidencia.'</td>
                                    <td class="'.$colorbg.'">'.$est_diagnostico.'</td>
                                    <td>'.$est_evolucion.'</td>
                                    <td>'.$est_comentario.'</td>
                                    </tr>';
                                }
                                
                            }
                                
                    $ret .= '</tbody>
                        </table>
                    </div>
                </div>
            </div>
    </div>
    ';

    echo $ret;
    ?>
</div>