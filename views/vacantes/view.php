<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use dosamigos\chartjs\ChartJs;
use app\models\Areascuestionario;
use app\models\Preguntas;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var app\models\Vacantes $model */

$this->title = $model->titulo;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Vacantes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$remoto = ['1' => 'SI', '2' => 'NO', '3' => 'PARCIAL'];
$status = ['0'=>'Baja','1'=>'Activo','2'=>'Pospuesto'];

$preguntas = ArrayHelper::map(Preguntas::find()->where(['in','id_tipo_cuestionario',[4]])->orderBy('pregunta')->all(), 'id', 'pregunta');

$factores = ArrayHelper::map(Areascuestionario::find()->where(['in','id_pregunta',[30,31,32]])->orderBy('id_pregunta')->all(), 'id', function($model) use ($preguntas ){
    $ret = $model['nombre'];
    if($model['id_pregunta'] != '32'){
        $ret .=' - '.$preguntas[$model['id_pregunta']];
    }
    return $ret;
});
?>
<div class="vacantes-view">

    <div class="container-fluid">
        <h1 class="title1"><?= Html::encode($this->title) ?></h1>

        <div class="row">
            <div class="col-lg-6">
                <div class="row">
                    <div class="col-lg-6">
                        <label for="" class="control-label"><?=$model->getAttributeLabel('id_empresa');?></label>
                        <div class="form-control"><?php echo $model->empresa->comercial?></div>
                    </div>
                    <div class="col-lg-6">
                        <label for="" class="control-label"><?=$model->getAttributeLabel('id_puesto');?></label>
                        <div class="form-control badge bgtransparent1 text-dark font12 font600 p-3">
                            <?php echo $model->puesto->nombre?></div>
                    </div>
                </div>
                <div class="row my-3">
                    <div class="col-lg-12 mt-3">
                        <label for="" class="control-label"><?=$model->getAttributeLabel('titulo');?></label>
                        <div class="form-control"><?php echo $model->titulo?></div>
                    </div>
                    <div class="col-lg-12 mt-3">
                        <label for="" class="control-label"><?=$model->getAttributeLabel('descripcion');?></label>
                        <div class="form-control"><?php echo $model->descripcion?></div>
                    </div>
                </div>
                <div class="row my-3">
                    <div class="col-lg-6 mt-3">
                        <label for="" class="control-label"><?=$model->getAttributeLabel('ubicacion');?></label>
                        <div class="form-control"><?php echo $model->ubicacion?></div>
                    </div>
                    <div class="col-lg-6 mt-3">
                        <label for="" class="control-label"><?=$model->getAttributeLabel('pais');?></label>
                        <div class="form-control"><?php echo $model->pais?></div>
                    </div>
                </div>
                <div class="row my-3">
                    <div class="col-lg-6 mt-3">
                        <label for="" class="control-label"><?=$model->getAttributeLabel('salario');?></label>
                        <div class="form-control"><?php echo $model->salario?></div>
                    </div>
                </div>
                <div class="row my-3">
                    <div class="col-lg-6 mt-3">
                        <label for="" class="control-label"><?=$model->getAttributeLabel('fecha_limite');?></label>
                        <div class="form-control"><?php echo $model->fecha_limite?></div>
                    </div>
                    <div class="col-lg-4 mt-3">
                        <label for="" class="control-label"><?=$model->getAttributeLabel('status');?></label>
                        <div class="form-control"><?php echo $status[$model->status]?></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12  mt-3">
                        <label for="" class="control-label"><?=$model->getAttributeLabel('cantidad_vacantes');?></label>
                        <div class="form-control"><?php echo $model->cantidad_vacantes?></div>
                    </div>
                </div>


            </div>

            <div class="col-lg-6 p-3 card bg-light shadow rounded-4">
                <div class="m-3">
                    <h1 class="title1 text-center"><?= Html::encode('Avatar del Puesto') ?></h1>
                    <div class="p-1 border-bottom border-top mt-5">
                        <h6 class="small text-center font500">Perfil Psicológico <span><img
                                    src="resources/images/psicologico.png" class="px-2" height="50px"
                                    width="auto" /></span>
                        </h6>
                    </div>
                    <div class="card-body bg-soft">
                        <?php
                        $labels = [];
                        $datas = [];
                        $datasmax = [];
                        if($model->puesto && $model->puesto->parametros){
                            foreach($model->puesto->parametros as $key=>$parametro){
                                array_push($labels, $parametro->parametro->nombre);
                                array_push($datas, $parametro->valor);
                                array_push($datasmax, 10);
                            }
                        }
                        
                        ?>
                        <?= ChartJs::widget([
                                    'type' => 'radar',  
                                    'options' => [
                                       
                                    ],
                                    'data' => [
                                        'labels' => $labels, // Your labels
                                        'datasets' => [
                                            [
                                                'label'=> 'Valor Máximo',
                                                'data'=> $datasmax,
                                                'fill'=> true,
                                                'backgroundColor'=> 'rgba(65, 73, 217, 0.2)',
                                                'borderColor'=> 'rgb(65, 73, 217)',
                                                'pointBackgroundColor'=> 'rgb(65, 73, 217)',
                                                'pointBorderColor'=> '#fff',
                                                'pointHoverBackgroundColor'=> '#fff',
                                                'pointHoverBorderColor'=> 'rgb(65, 73, 217)'
                                            ],
                                            [
                                                'label'=> 'Valor Parámetro',
                                                'data'=> $datas,
                                                'fill'=> true,
                                                'backgroundColor'=> 'rgba(255, 99, 132, 0.2)',
                                                'borderColor'=> 'rgb(255, 99, 132)',
                                                'pointBackgroundColor'=> 'rgb(255, 99, 132)',
                                                'pointBorderColor'=> '#fff',
                                                'pointHoverBackgroundColor'=> '#fff',
                                                'pointHoverBorderColor'=> 'rgb(255, 99, 132)'
                                            ],
                                        ]
                                    ],
                                    ]);?>
                    </div>

                    <div class="p-1 border-bottom border-top mt-5">
                        <h6 class="small text-center font500">Medidas Antropométricas <span><img
                                    src="resources/images/medida.png" class="px-2" height="50px" width="auto" /></span>
                        </h6>
                    </div>
                    <div class="row mt-2">
                        <?php
                    if(isset($model->puesto->medida1) && $model->puesto->medida1 != null && $model->puesto->medida1 != ''){
                    $lbl_medida1 = $factores[$model->puesto->medida1];
                    $start1 = $model->puesto->rango1desde;
                    $end1 = $model->puesto->rango1hasta;

                    echo $lbl_medida1;
                    echo '<span class="badge bg-disabled text-dark font11">'.$start1. ' cm - '. $end1.' cm RANGO IDEAL</span>';
                   
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
                    echo '<span class="badge bg-disabled text-dark font11">'.$start2. ' cm - '. $end2.' cm RANGO IDEAL</span>';
                   
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
                    echo '<span class="badge bg-disabled text-dark font11">'.$start3. ' cm - '. $end3.' cm RANGO IDEAL</span>';
                   
                    }
                    ?>
                    </div>
                    <div class="p-1 border-bottom border-top mt-5">
                        <h6 class="small text-center font500">Carga Máxima <span><img src="resources/images/carga.png"
                                    class="px-2" height="50px" width="auto" /></span>
                        </h6>
                    </div>
                    <?php
                        $bloque1 = 'bgnocumple';
                        $bloque2 = 'bgnocumple';
                        $bloque3 = 'bgnocumple';
                        $bloque4 = 'bgnocumple';
                        $bloque5 = 'bgnocumple';
                        $bloque6 = 'bgnocumple';
                        $bloque7 = 'bgnocumple';
                        
                        if($model->puesto->cargamaxima == 5){
                            $bloque5 = 'bgcumple';
                        } else if($model->puesto->cargamaxima == 4){
                            $bloque4 = 'bgcumple';
                            $bloque5 = 'bgcumple';
                            $bloque7 = 'bgcumple';
                        } else if($model->puesto->cargamaxima == 3){
                            $bloque4 = 'bgcumple';
                            $bloque5 = 'bgcumple';
                            $bloque6 = 'bgcumple';
                            $bloque7 = 'bgcumple';
                        } else if($model->puesto->cargamaxima == 2){
                            $bloque3 = 'bgcumple';
                            $bloque4 = 'bgcumple';
                            $bloque5 = 'bgcumple';
                            $bloque6 = 'bgcumple';
                            $bloque7 = 'bgcumple';
                        } else if($model->puesto->cargamaxima == 1){
                            $bloque1 = 'bgcumple';
                            $bloque2 = 'bgcumple';
                            $bloque3 = 'bgcumple';
                            $bloque4 = 'bgcumple';
                            $bloque5 = 'bgcumple';
                            $bloque6 = 'bgcumple';
                            $bloque7 = 'bgcumple';
                        } else if (!isset($model->puesto->cargamaxima) || $model->puesto->cargamaxima == null || $model->puesto->cargamaxima == ''){
                            $bloque1 = '';
                            $bloque2 = '';
                            $bloque3 = '';
                            $bloque4 = '';
                            $bloque5 = '';
                            $bloque6 = '';
                            $bloque7 = '';
                        }

                        $carga = '';

                        if($model->puesto){
                            $carga = $model->puesto->carga;  
                        }
        
                    ?>
                    <div class="row">
                        <div class="col-lg-12">
                            <label for="" class="control-label"><?='Carga Máxima';?></label>
                            <div class="form-control">
                                <?php echo $carga?> kg</div>
                        </div>

                        <div class="col-lg-12">
                            <table class="table table-hover table-bordered table-sm font11 text-little"
                                style="height:100%">
                                <thead class="font500">
                                    <tr>
                                        <td class="text-center" width="40%">Género</td>
                                        <td class="text-center" width="30%">Edad</td>
                                        <td class="text-center" width="30%">Carga Máxima</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-center text-uppercase y_centercenter <?php echo $bloque1;?>"
                                            id='g1'>Femenino</td>
                                        <td class="text-center text-uppercase y_centercenter <?php echo $bloque1;?>"
                                            id='e1'>Menores de 18</td>
                                        <td class="text-center text-uppercase y_centercenter <?php echo $bloque1;?>"
                                            id='c1'>7 kg</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center text-uppercase y_centercenter <?php echo $bloque2;?>"
                                            id='g2'>Masculino</td>
                                        <td class="text-center text-uppercase y_centercenter <?php echo $bloque2;?>"
                                            id='e2'>Menores de 18</td>
                                        <td class="text-center text-uppercase y_centercenter <?php echo $bloque2;?>"
                                            id='c2'>7 kg</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center text-uppercase y_centercenter <?php echo $bloque3;?>"
                                            id='g3'>Femenino</td>
                                        <td class="text-center text-uppercase y_centercenter <?php echo $bloque3;?>"
                                            id='e3'>Embarazadas</td>
                                        <td class="text-center text-uppercase y_centercenter <?php echo $bloque3;?>"
                                            id='c3'>10 kg</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center text-uppercase y_centercenter <?php echo $bloque4;?>"
                                            id='g4'>Femenino</td>
                                        <td class="text-center text-uppercase y_centercenter <?php echo $bloque4;?>"
                                            id='e4'>Entre 18 y 45</td>
                                        <td class="text-center text-uppercase y_centercenter <?php echo $bloque4;?>"
                                            id='c4'>20</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center text-uppercase y_centercenter <?php echo $bloque5;?>"
                                            id='g5'>Masculino</td>
                                        <td class="text-center text-uppercase y_centercenter <?php echo $bloque5;?>"
                                            id='e5'>Entre 18 y 45</td>
                                        <td class="text-center text-uppercase y_centercenter <?php echo $bloque5;?>"
                                            id='c5'>25</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center text-uppercase y_centercenter <?php echo $bloque6;?>"
                                            id='g6'>Femenino</td>
                                        <td class="text-center text-uppercase y_centercenter <?php echo $bloque6;?>"
                                            id='e6'>Mayores de 45*</td>
                                        <td class="text-center text-uppercase y_centercenter <?php echo $bloque6;?>"
                                            id='c6'>15</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center text-uppercase y_centercenter <?php echo $bloque7;?>"
                                            id='g7'>Masculino</td>
                                        <td class="text-center text-uppercase y_centercenter <?php echo $bloque7;?>"
                                            id='e7'>Mayores de 45*</td>
                                        <td class="text-center text-uppercase y_centercenter <?php echo $bloque7;?>"
                                            id='c7'>20</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>


    </div>

</div>