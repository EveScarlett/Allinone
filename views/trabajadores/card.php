<?php
use app\models\Areascuestionario;
use app\models\Preguntas;
use app\models\DetalleCuestionario;
use yii\helpers\ArrayHelper;
use dosamigos\chartjs\ChartJs;
use app\models\Trabajadorparametro;
use app\models\Puestoparametro;
use app\models\Parametros;
use app\models\Puestostrabajo;

$preguntas = ArrayHelper::map(Preguntas::find()->where(['in','id_tipo_cuestionario',[4]])->orderBy('pregunta')->all(), 'id', 'pregunta');

$factores = ArrayHelper::map(Areascuestionario::find()->where(['in','id_pregunta',[30,31,32]])->orderBy('id_pregunta')->all(), 'id', function($model) use ($preguntas ){
    $ret = $model['nombre'];
    if($model['id_pregunta'] != '32'){
        $ret .=' - '.$preguntas[$model['id_pregunta']];
    }
    return $ret;
});
?>
<?php
$foto = '';

if(isset($model->foto) && $model->foto != ""){
    $foto = '<div class="circularbig2 y_centercenter" style="background-image: url(\'/web/resources/Empresas/'.$model->id_empresa."/Trabajadores/".$model->id."/Documentos/".$model->foto.'\');  background-position: center; background-size: cover;"></div>';
}
?>


<div class="container-fluid cardcustom p-4">
    <div class="">
        <div class="circularbig"><?php echo $foto;?></div>
    </div>
    <div class="row mt-5">
        <div class="col-lg-8 offset-lg-4">
            <h1 class="title1">
                <?php echo $model->nombre.' '.$model->apellidos?>
            </h1>
            <h6 class="color6">
                <?php if(isset($model->puesto)){
                      echo '<i class="bi bi-briefcase-fill"></i> '.$model->puesto->nombre;
                }?>
            </h6>
        </div>
        <div class="col-lg-8 offset-lg-4">
            <?php
            if(isset($model->sexo) && $model->sexo != null && $model->sexo != ''){
                $sexos = ['1'=>'Hombre','2'=>'Mujer','3'=>'Otro'];
                echo '<span class="badge bgcolor3 text-light rounded-pill font500 m-1">'.$sexos[$model->sexo].'</span>';
            }
            if(isset($model->edad) && $model->edad != null && $model->edad != ''){
                echo '<span class="badge bgcolor3 text-light rounded-pill font500 m-1">'.$model->edad.' Años</span>';
            }
            if(isset($model->celular) && $model->celular != null && $model->celular != ''){
                echo '<span class="badge bgcolor3 text-light rounded-pill font500 m-1"><i class="bi bi-telephone mx-2"></i>'.$model->celular.'</span>';
            }
            if(isset($model->correo) && $model->correo != null && $model->correo != ''){
                echo '<span class="badge bgcolor3 text-light rounded-pill font500 m-1"><i class="bi bi-envelope mx-2"></i>'.$model->correo.'</span>';
            }
            if(isset($model->ruta) && $model->ruta != null && $model->ruta != ''){
                echo '<span class="badge bgcolor3 text-light rounded-pill font500 m-1"><i class="bi bi-geo-alt-fill mx-2"></i> Ruta '.$model->ruta.'</span>';
            }
            if(isset($model->turno) && $model->turnoactual ){
                echo '<span class="badge bgcolor3 text-light rounded-pill font500 m-1"><i class="bi bi-calendar3"></i> Turno '.$model->turnoactual->turno.'</span>';
            }
            ?>

        </div>
    </div>
    <div class="row mt-4 bg-soft">
        <div class="col-lg-4  offset-lg-2 text-center">
            <?php
            $retcarga = '';
            $retcargatrabajador = '';
            $cumple = 0;
            if (isset($model->puesto)){
                //echo '<span class="badge bgtransparent1 text-dark font12 m-1">'.$model->puesto->carga.' kg</span>';
                $retcarga = $model->puesto->carga.' kg';

                if($model->puesto->carga){
                    if($model->sexo && $model->edad){
                        
                        if($model->puesto->carga <= 7){//Carga de 7
                            $cumple = 1;
                        } else if($model->puesto->carga > 7 && $model->puesto->carga <= 10){//Carga de 10
                            if($model->sexo == 1){//HOMBRE
                                if($model->edad > 18 && $model->edad <= 45){//Hombre 18-45
                                    $cumple = 1;
                                } else if($model->edad > 45){//Hombre > 45
                                    $cumple = 1;
                                } else {
                                    $cumple = 0;
                                }
                            } else if($model->sexo == 2){//MUJER
                                if($model->edad > 18 && $model->edad <= 45){//Mujer 18-45
                                    $cumple = 1;
                                } else if($model->edad > 45){//Mujer > 45
                                    $cumple = 1;
                                } else {
                                    $cumple = 0;
                                }
                            }
                        } else if($model->puesto->carga > 10 && $model->puesto->carga <= 15){//Carga de 15
                            if($model->sexo == 1){//HOMBRE
                                if($model->edad > 18 && $model->edad <= 45){//Hombre 18-45
                                    $cumple = 1;
                                } else if($model->edad > 45){//Hombre > 45
                                    $cumple = 1;
                                } else {
                                    $cumple = 0;
                                }
                            } else if($model->sexo == 2){//MUJER
                                if($model->edad > 18 && $model->edad <= 45){//Mujer 18-45
                                    $cumple = 1;
                                } else if($model->edad > 45){//Mujer > 45
                                    $cumple = 2;
                                } else {
                                    $cumple = 0;
                                }
                            }
                        } else if($model->puesto->carga > 16 && $model->puesto->carga <= 20){//Carga de 20
                            if($model->sexo == 1){//HOMBRE
                                if($model->edad > 18 && $model->edad <= 45){//Hombre 18-45
                                    $cumple = 1;
                                } else if($model->edad > 45){//Hombre > 45
                                    $cumple = 2;
                                } else {
                                    $cumple = 0;
                                }
                            } else if($model->sexo == 2){//MUJER
                                if($model->edad > 18 && $model->edad <= 45){//Mujer 18-45
                                    $cumple = 1;
                                } else {
                                    $cumple = 0;
                                }
                            }
                        } else if($model->puesto->carga > 21 && $model->puesto->carga <= 25){//Carga de 25
                            if($model->sexo == 1){//HOMBRE
                                if($model->edad > 18 && $model->edad <= 45){//Hombre 18-45
                                    $cumple = 1;
                                } else {
                                    $cumple = 0;
                                }
                            } else if($model->sexo == 2){//MUJER
                                $cumple = 0;
                            }
                        }

                        if($cumple == 1){
                            $retcargatrabajador = '<label class="badge bgtransparent1 text-dark font12 m-1">'.$model->puesto->carga.' kg <span class="color7"> CUMPLE <i class="bi bi-check"></i></span></label>';
                        } else if($cumple == 2){
                            $retcargatrabajador = '<label class="badge bgtransparent1 text-dark font12 m-1">'.$model->puesto->carga.' kg <span class="color12"> CUMPLE, REQUIERE PERMISO MÉDICO<i class="bi bi-exclamation"></i></span></label>';
                        } else {
                            $retcargatrabajador = '<label class="badge bgtransparent1 text-dark font12 m-1">'.$model->puesto->carga.' kg <span class="color11"> NO CUMPLE <i class="bi bi-x"></i></span></label>';
                        }
                    }
                }
            }
            ?>
            <h6 class="small font500">Carga Máxima Ideal por Puesto: <?=$retcarga;?></h6>
            <?=
            $retcargatrabajador;
            ?>
        </div>
        <div class="col-lg-6">
            <h6 class="small font500">Medidas Antropométricas</h6>
            <div class="row">
                <?php
                $cuestionario = $model->antropometrica;
                $medida1 = 'SIN INFORMACIÓN';
                $medida2 = 'SIN INFORMACIÓN';
                $medida3 = 'SIN INFORMACIÓN';

                $lbl_medida1 = '';
                $lbl_medida2 = '';
                $lbl_medida3 = '';

                $start1 = 0;
                $start2 = 0;
                $start3 = 0;

                $end1 = 0;
                $end2 = 0;
                $end3 = 0;

                if($cuestionario){
                    /* $preguntas = Preguntas::find()->where(["id_tipo_cuestionario" => $cuestionario->id_tipo_cuestionario])->andWhere(["!=", "pregunta", "_general"])->andWhere(["status" => 1])->all();
                    $preguntas_general = Preguntas::find()->where(["id_tipo_cuestionario" => $cuestionario->id_tipo_cuestionario])->andWhere(["pregunta" => "_general"])->andWhere(["status" => 1])->one();
 
                    $det_cuestionario = DetalleCuestionario::find()->where(['id_cuestionario' => $cuestionario->id])->andWhere(['status' => 1]);
                    if ($preguntas_general) {
                        $det_cuestionario = $det_cuestionario->andWhere(['!=', 'id_pregunta', $preguntas_general->id]);
                        $det_cuestionario_general = DetalleCuestionario::find()->where(['id_cuestionario' => $cuestionario->id])->andWhere(["id_pregunta" => $preguntas_general->id])->andWhere(['status' => 1])->all();
                        $det_cuestionario_general = ArrayHelper::map($det_cuestionario_general, 'id_area', 'respuesta_1', 'id_pregunta');
                    }
                    $det_cuestionario = $det_cuestionario->all();
                    
                    $det_cuestionario_ = ArrayHelper::map($det_cuestionario, 'id_area', 'respuesta_1', 'id_pregunta');   */
                }

                if($cuestionario && $model->puesto && $model->puesto->medida1){
                    $c_medida1 = DetalleCuestionario::find()->where(['id_cuestionario'=>$cuestionario->id])->andWhere(['id_area'=>$model->puesto->medida1])->one();
                    if($c_medida1){
                        $medida1 = $c_medida1->respuesta_1;
                    }
                }

                if($cuestionario && $model->puesto && $model->puesto->medida2){
                    $c_medida2 = DetalleCuestionario::find()->where(['id_cuestionario'=>$cuestionario->id])->andWhere(['id_area'=>$model->puesto->medida2])->one();
                    if($c_medida2){
                        $medida2 = $c_medida2->respuesta_1;
                    }
                }

                if($cuestionario && $model->puesto && $model->puesto->medida3){
                    $c_medida3 = DetalleCuestionario::find()->where(['id_cuestionario'=>$cuestionario->id])->andWhere(['id_area'=>$model->puesto->medida3])->one();
                    if($c_medida3){
                        $medida3 = $c_medida3->respuesta_1;
                    }
                }
                
                ?>
                <?php
                if(isset($model->puesto->medida1) && $model->puesto->medida1 != null && $model->puesto->medida1 != ''){
                    $lbl_medida1 = $factores[$model->puesto->medida1];
                    $start1 = $model->puesto->rango1desde;
                    $end1 = $model->puesto->rango1hasta;

                    echo $lbl_medida1;
                    echo '<span class="badge bg-disabled text-dark font11">'.$medida1.' cm</span>';
                    echo '<br><div class="color9 font10 text-center">'.$start1. ' cm - '.$end1.' cm RANGO IDEAL</div>';
                }
                ?>
            </div>
            <div class="row mt-2">
                <?php
                if(isset($model->puesto->medida2) && $model->puesto->medida2 != null && $model->puesto->medida2 != ''){
                    $lbl_medida2 = $factores[$model->puesto->medida2];
                    $start2 = $model->puesto->rango2desde;
                    $end2 = $model->puesto->rango2hasta;

                    echo $lbl_medida2;
                    echo '<span class="badge bg-disabled text-dark font11">'.$medida2.' cm</span>';
                    echo '<br><div class="color9 font10 text-center">'.$start2. ' cm - '. $end2.' cm RANGO IDEAL</div>';
                }
                ?>
            </div>
            <div class="row mt-2">
                <?php
                if(isset($model->puesto->medida3) && $model->puesto->medida3 != null && $model->puesto->medida3 != ''){
                    $lbl_medida3 = $factores[$model->puesto->medida3];
                    $start3 = $model->puesto->rango3desde;
                    $end3 = $model->puesto->rango3hasta;
                    echo $lbl_medida3;
                    echo '<span class="badge bg-disabled text-dark font11">'.$medida3.' cm</span>';
                    echo '<br><div class="color9 font10 text-center">'.$start3. ' cm - '.$end3.' cm RANGO IDEAL</div>';
                }
                ?>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="card bg-soft" style="border-radius:0px;">
            <div class="p-1">
                <h6 class="small text-center font500">Comparativa del Avatar</h6>
            </div>
            <div class="card-body bg-soft">
                <?= ChartJs::widget([
                                    'type' => 'radar',  
                                    'options' => [
                                       
                                    ],
                                    'data' => [
                                        'labels' => [$lbl_medida1,$lbl_medida2,$lbl_medida3], // Your labels
                                        'datasets' => [
                                            [
                                                'label'=> 'Valor Máximo',
                                                'data'=> [$end1, $end2, $end3],
                                                'fill'=> true,
                                                'backgroundColor'=> 'rgba(255, 99, 132, 0.2)',
                                                'borderColor'=> 'rgb(255, 99, 132)',
                                                'pointBackgroundColor'=> 'rgb(255, 99, 132)',
                                                'pointBorderColor'=> '#fff',
                                                'pointHoverBackgroundColor'=> '#fff',
                                                'pointHoverBorderColor'=> 'rgb(255, 99, 132)'
                                            ],
                                            [
                                                'label'=> 'Medidas Antropométricas de Trabajador',
                                                'data'=> [$medida1, $medida2, $medida3],
                                                'fill'=> true,
                                                'backgroundColor'=> 'rgba(65, 73, 217, 0.2)',
                                                'borderColor'=> 'rgb(65, 73, 217)',
                                                'pointBackgroundColor'=> 'rgb(65, 73, 217)',
                                                'pointBorderColor'=> '#fff',
                                                'pointHoverBackgroundColor'=> '#fff',
                                                'pointHoverBorderColor'=> 'rgb(65, 73, 217)'
                                            ],
                                            [
                                                'label'=> 'Valor Mínimo',
                                                'data'=> [$start1, $start2, $start3],
                                                'fill'=> true,
                                                'backgroundColor'=> 'rgba(255, 204, 0, 0.2)',
                                                'borderColor'=> 'rgb(255, 204, 0)',
                                                'pointBackgroundColor'=> 'rgb(255, 204, 0)',
                                                'pointBorderColor'=> '#fff',
                                                'pointHoverBackgroundColor'=> '#fff',
                                                'pointHoverBorderColor'=> 'rgb(255, 204, 0)'
                                            ]
                                        ]
                                    ],
                                    ]);?>
            </div>
        </div>
    </div>

    <div class="row" style="display:none;">
        <div class="card bg-soft" style="border-radius:0px;">
            <div class="p-1">
                <h6 class="small text-center font500">Perfil Psicológico <span><img
                            src="resources/images/psicologico.png" class="px-2" height="50px" width="auto" /></span>
                </h6>
            </div>
            <div class="col-lg-12 text-center">
                <label class="badge bgcolor9 text-light rounded-pill font500 m-1">Personalidad:
                    <?php echo '<span class="font500">'.$model->personalidad.'</span>';?></label>
            </div>
            <div class="col-lg-12">
                <div class="card-body bg-soft">
                    <div class="card-body bg-soft">
                        <div class="row">
                            <div class="col-lg-9">
                                <?php
                        $labels = [];
                        $datas = [];
                        $datasmax = [];
                        $id_params = [];

                        $todos_parametros = [];
                        $param_puesto = [];
                        $hay_puesto = false;
                        $sumatoria = 0;

                        if($model->puesto){
                            $hay_puesto = true;
                            if($model->puesto->parametros){
                                foreach($model->puesto->parametros as $key=>$parametro){
                                    if (!in_array($parametro->parametro->id, $todos_parametros)) {
                                        array_push($todos_parametros, $parametro->parametro->id);
                                        array_push($id_params, $parametro->parametro->id);
                                    }
                                    
                                    /* if (!in_array($parametro->parametro->id, $param_puesto)) {
                                        array_push($param_puesto, $parametro->parametro->id);
                                    } */
                                
                                    $sumatoria += $parametro->valor;
                                }
                            }
                        }
                        
                        foreach($model->parametros as $key=>$parametro){
                            if (!in_array($parametro->parametro->id, $todos_parametros)) {
                                array_push($todos_parametros, $parametro->parametro->id);
                                array_push($id_params, $parametro->parametro->id);
                            }
                        }

                        

                        foreach($todos_parametros as $key=>$param){
                            $parametrooriginal = Parametros::findOne($param);
                            $parametrovalor = 0;

                            if($parametrooriginal){
                                //Guardar el nombre del parametro
                                array_push($labels, $parametrooriginal->nombre);

                                if(!$hay_puesto){//Si no hay puesto ponemos el parametro en 0, porque no hay que evaluar
                                    array_push($datasmax, 0);
                                } else{//Si si hay puesto vemos si tiene el parametro
                                    $parametro = Puestoparametro::find()->where(['id_puesto'=>$model->puesto->id])->andWhere(['id_parametro'=>$param])->one();
    
                                    $param_puesto[$parametro->id_parametro] = 0;
                                    $valor = 0;
                                    if(!$parametro){//Si no hay parametro, mandamos 0
                                        array_push($datasmax, 0);
                                    } else{//Si si hay parametro, le agregamos el valor correspondiente
                                        array_push($datasmax, $parametro->valor);
                                        $valor = $parametro->valor;
                                    }

                                    $porcentaje_avatar = ($valor*100)/$sumatoria;
                                    $porcentaje_avatar = round($porcentaje_avatar, 2);
                                    $param_puesto[$parametro->id_parametro] =$porcentaje_avatar;
                                }
    
                                $parametrotrabajador = Trabajadorparametro::find()->where(['id_trabajador'=>$model->id])->andWhere(['id_parametro'=>$param])->one();
                                if(!$parametrotrabajador){
                                    array_push($datas, 0);
                                } else{
                                    array_push($datas, $parametrotrabajador->valor);
                                }

                            }
                        
                        }

                        //dd($id_params);
                       
                        ?>
                                <?= ChartJs::widget([
                                    'type' => 'radar',  
                                    'options' => [
                                       
                                    ],
                                    'data' => [
                                        'labels' => $labels, // Your labels
                                        'datasets' => [
                                            [
                                                'label'=> 'Valor Ideal - Avatar',
                                                'data'=> $datasmax,
                                                'fill'=> true,
                                                'backgroundColor'=> 'rgba(39, 225, 193, 0.2)',
                                                'borderColor'=> 'rgb(39, 225, 193)',
                                                'pointBackgroundColor'=> 'rgb(39, 225, 193)',
                                                'pointBorderColor'=> '#fff',
                                                'pointHoverBackgroundColor'=> '#fff',
                                                'pointHoverBorderColor'=> 'rgb(39, 225, 193)'
                                            ],
                                            [
                                                'label'=> 'Valor Parámetro P.S. Trabajador',
                                                'data'=> $datas,
                                                'fill'=> true,
                                                'backgroundColor'=> 'rgba(65, 73, 217, 0.2)',
                                                'borderColor'=> 'rgb(65, 73, 217)',
                                                'pointBackgroundColor'=> 'rgb(65, 73, 217)',
                                                'pointBorderColor'=> '#fff',
                                                'pointHoverBackgroundColor'=> '#fff',
                                                'pointHoverBorderColor'=> 'rgb(65, 73, 217)'
                                            ],
                                        ]
                                    ],
                                    ]);?>
                            </div>
                            <div class="col-lg-3 bg-light">
                                <?php
                                //$labels = [];
                                //$datas = [];
                                //$datasmax = [];

                                //$todos_parametros = [];
                                //$hay_puesto = false;

                                $tabla = '<table class="table table-hover rounded-5"><tbody>';
                                $totalparametros = count($datasmax);
                                $suma = 0;
                                $concordancia = 0;

                                /* $parametrovalor = $parametro->valor;
                                $porcentaje_parametro = ($parametrovalor*100)/$sumatoria;
                                $porcentaje_parametro = round($porcentaje_parametro, 2); */
                                //dd($datasmax);
                                foreach($labels as $key=>$label){
                                    $id_parametro = $id_params[$key];
                                    $avatar = $datasmax[$key];
                                    $trabajador = $datas[$key];
                                    $signo = '';


                                    if($avatar < 1){
                                        $porcentaje = '--'; 
                                        $suma += 0;
                                    } else{
                                        $porcentajeavatar = 0;
                                        if($param_puesto[$id_parametro]){
                                            $porcentajeavatar = $param_puesto[$id_parametro];
                                        }

                                        
                                        if($trabajador > $avatar){
                                            $trabajador = $avatar;
                                            $signo = '>';

                                            $percenfinal = $porcentajeavatar;
                                        } else{

                                            $percenfinal = ($trabajador*$porcentajeavatar)/$avatar;
                                            $percenfinal = round($percenfinal, 2);
                                        }
                                        $porcentaje = ($trabajador *100)/$avatar;
                                        $porcentaje = number_format($porcentaje, 1, '.', ',');
                

                                        //dd('Valor trabajador: '.$trabajador.'| Valor Avatar: '.$avatar.'| Porcentaje Avatar: '.$porcentajeavatar);
                                        $suma += $percenfinal;
                                    }

                                    //$porcentaje =  $avatar.'|'.$trabajador;
                                    $tabla .= '<tr class="font10"><td>'.$label.'</td><td class="font500 font12">'.$signo.$porcentaje.'%</td></tr>';
                                }
                                $tabla .= '</tbody></table>';

                                $concordancia = $suma;

                                //dd($concordancia);
                                
                                $showporcentaje = '<div class="card text-light bgcolor9 p-3">
                                <h6 class="title1 text-uppercase text-center">'.$concordancia.'%</h6>
                                <label class="font10  text-center">Coincidente con el perfil</label>
                                </div>';

                                echo $showporcentaje.$tabla;
                                echo '<span class="font10 color6">NOTA: --% Indica que no es un parámetro requerido por el avatar, por lo tanto no se toma en cuenta en el calculo.</span>';

                                
                                ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">

            </div>

        </div>
    </div>

    <div class="row">
        <div class="card bg-soft" style="border-radius:0px;">
            <div class="p-1">
                <h6 class="small text-center font500">Vacantes Sugeridas<span><img src="resources/images/vacante.png"
                            class="px-2" height="50px" width="auto" /></span>
                </h6>
            </div>
            <?php
            $tabla = '<table class="table table-hover rounded-5"><tbody>';
            $tabla .= '<thead><tr class="font12 font550"><td width="40%" class="font550">Puesto de trabajo</td><td width="20%" class="">% Medidas Antropométricas</td><td width="20%" class="">% Perfil Psicológico</td><td width="20%" class="">% Afinidad con Puesto</td></tr></thead>';
            
            
            foreach($puestosordenados as $key=>$puestoporcentaje){
                $puesto = Puestostrabajo::findOne($key);
                $medida = $medidas_porcentaje[$key];
                $psicologico = $psicologico_porcentaje[$key];
                $puestoporcentaje = '<div class="card text-light bgcolor5 p-1 text-center"><h6 class="mb-0 pb-0 font600">'.$puestoporcentaje.'%</h6></div>';
                //dd($medida);
                if($puesto){
                    $tabla .= '<tr class=""><td class="color6">'.$puesto->nombre.'</td><td class="font500 font11">'.$medida.'%</td><td class="font11">'.$psicologico.'%</td><td class="font500 font12">'.$puestoporcentaje.'</td></tr>';
                }  
            }

            $tabla .= '</tbody></table>';
            echo $tabla;
            ?>
        </div>
    </div>
</div>