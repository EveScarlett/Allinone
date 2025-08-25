<?php

use app\models\Trabajadores;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use kartik\export\ExportMenu;
use kartik\field\FieldRange;

use app\models\Empresas;
use app\models\Epps;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\Areas;
use app\models\Puestostrabajo;
use app\models\Riesgos;
use yii\bootstrap5\Modal;

use app\models\Ubicaciones;
use app\models\Paises;
use app\models\Paisempresa;

use app\models\ProgramaSalud;

use app\models\NivelOrganizacional1;
use app\models\NivelOrganizacional2;
use app\models\NivelOrganizacional3;
use app\models\NivelOrganizacional4;

use app\models\Poes;

/** @var yii\web\View $this */
/** @var app\models\TrabajadorespsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Programas de Salud - Trabajadores');
$this->params['breadcrumbs'][] = $this->title;
?>

<?php
$mostrar_nivel1 = true;
$mostrar_nivel2 = true;
$mostrar_nivel3 = true;
$mostrar_nivel4 = true;


if(Yii::$app->user->identity->nivel1_all != 1){
    $array_nivel1 = explode(',', Yii::$app->user->identity->nivel1_select);
    if(count($array_nivel1) == 1){
        $mostrar_nivel1 = false;
    }
}
if(Yii::$app->user->identity->nivel2_all != 1){
    $array_nivel2 = explode(',', Yii::$app->user->identity->nivel2_select);
    if(count($array_nivel2) == 1){
        $mostrar_nivel2 = false;
    }
}
if(Yii::$app->user->identity->nivel3_all != 1){
    $array_nivel3 = explode(',', Yii::$app->user->identity->nivel3_select);
    if(count($array_nivel3) == 1){
        $mostrar_nivel3 = false;
    }
}
if(Yii::$app->user->identity->nivel4_all != 1){
    $array_nivel4 = explode(',', Yii::$app->user->identity->nivel4_select);
    if(count($array_nivel4) == 1){
        $mostrar_nivel4 = false;
    }
}
//dd($mostrar_nivel1,$mostrar_nivel2,$mostrar_nivel3,$mostrar_nivel4);
?>

<?php
$programas = ArrayHelper::map(ProgramaSalud::find()->orderBy('nombre')->all(), 'id', 'nombre');

$showempresa = true;
$empresas = explode(',', Yii::$app->user->identity->empresas_select);

if(Yii::$app->user->identity->empresa_all != 1){
    if(count($empresas) == 1){
        $showempresa = false;
    }
}
?>

<?php

$empresas = explode(',', Yii::$app->user->identity->empresas_select);

$antiguedades =['1'=>'≤ 1 mes','2'=>'≤ 3 meses','3'=>'≤ 6 meses','4'=>'≤ 9 meses','5'=>'< 1 año','6'=>'= 1 año','7'=>'>= 1 año < 2 años','8'=>'>= 2 años < 3 años','9'=>'>= 3 años < 4 años','10'=>'>= 4 años < 5 años','11'=>'>= 5 años < 6 años','12'=>'>= 6 años < 7 años','13'=>'>= 7 años < 8 años','14'=>'>= 8 años < 9 años','15'=>'>= 9 años < 10 años','16'=>'>= 10 años'];
        
if(Yii::$app->user->identity->empresa_all != 1) {
    $empresas = ArrayHelper::map(Empresas::find()->where(['in','id',$empresas])->orderBy('comercial')->all(), 'id', 'comercial');
} else{
    $empresas = ArrayHelper::map(Empresas::find()->orderBy('comercial')->all(), 'id', 'comercial');
}

$ids_empresas = [];

foreach($empresas as $key=>$item){
    if(!in_array($key, $ids_empresas)){
        array_push($ids_empresas, $key);
    }
}

$ubicaciones = ArrayHelper::map(Ubicaciones::find()->where(['in','id_empresa',$ids_empresas])->orderBy('ubicacion')->all(), 'id', 'ubicacion');
$areas = ArrayHelper::map(Areas::find()->where(['in','id_empresa',$ids_empresas])->orderBy('area')->all(), 'id', 'area');
$puestos = ArrayHelper::map(Puestostrabajo::find()->where(['in','id_empresa',$ids_empresas])->orderBy('nombre')->all(), 'id', 'nombre');
$riesgos = ArrayHelper::map(Riesgos::find()->orderBy('riesgo')->all(), 'id', 'riesgo');
$epps = ArrayHelper::map(Epps::find()->orderBy('epp')->all(), 'id', 'epp');

$paisempresa = Paisempresa::find()->where(['in','id_empresa',$ids_empresas])->all();
$id_paises = [];
foreach($paisempresa as $key=>$pais){
    if(!in_array($pais->id_pais, $id_paises)){
        array_push($id_paises, $pais->id_pais);
    }
}
   
$paises = ArrayHelper::map(Paises::find()->where(['in','id',$id_paises])->orderBy('pais')->all(), 'id', 'pais');

/* $limite_trabajadores = 5;
$utilizados_trabajadores = 0;
if(Yii::$app->user->identity->empresa && Yii::$app->user->identity->empresa->configuracion){
    $limite_trabajadores = Yii::$app->user->identity->empresa->configuracion->cantidad_trabajadores;
}

if(Yii::$app->user->identity->empresa){
    $empresa = Yii::$app->user->identity->empresa;
    if($empresa->trabajadoresactivos){
        $utilizados_trabajadores = count($empresa->trabajadoresactivos); 
    }
} */

$ver_qr = false;
if(Yii::$app->user->identity->empresa && Yii::$app->user->identity->empresa->configuracion->verqr_trabajador == 1){
    $ver_qr = true;
}

$disponible = false;
if(true){
    $disponible = true; 
}

$icon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-arms-up" viewBox="0 0 16 16">
    <path d="M8 3a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3"/>
    <path d="m5.93 6.704-.846 8.451a.768.768 0 0 0 1.523.203l.81-4.865a.59.59 0 0 1 1.165 0l.81 4.865a.768.768 0 0 0 1.523-.203l-.845-8.451A1.5 1.5 0 0 1 10.5 5.5L13 2.284a.796.796 0 0 0-1.239-.998L9.634 3.84a.7.7 0 0 1-.33.235c-.23.074-.665.176-1.304.176-.64 0-1.074-.102-1.305-.176a.7.7 0 0 1-.329-.235L4.239 1.286a.796.796 0 0 0-1.24.998l2.5 3.216c.317.316.475.758.43 1.204Z"/>
    </svg>';
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


if(Yii::$app->user->identity->empresa_all != 1) {
    $array_empresas = explode(',', Yii::$app->user->identity->empresas_select);
    if(count($array_empresas) == 1){
        $dataempresa = Empresas::findOne(intval(Yii::$app->user->identity->empresas_select));
        if($dataempresa){
            $label_nivel1 = $dataempresa->label_nivel1;
            $label_nivel2 = $dataempresa->label_nivel2;
            $label_nivel3 = $dataempresa->label_nivel3;
            $label_nivel4 = $dataempresa->label_nivel4;

            if($dataempresa->cantidad_niveles >= 1){
                if($mostrar_nivel1){
                    $show_nivel1 = true;
                }
            }
            if($dataempresa->cantidad_niveles >= 2){
                if($mostrar_nivel2){
                    $show_nivel2 = true;
                }
            }
            if($dataempresa->cantidad_niveles >= 2){
                if($mostrar_nivel3){
                    $show_nivel3 = true;
                }
            }
            if($dataempresa->cantidad_niveles >= 4){
                if($mostrar_nivel4){
                    $show_nivel4 = true;
                }
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


<?php
if(Yii::$app->user->identity->empresa_all != 1) {
    if($array_empresas == null && $array_empresas == '' && $array_empresas == ' '){
        $array_empresas = [];
    }
    $nivel1 = ArrayHelper::map(NivelOrganizacional1::find()->where(['in','id_empresa',$array_empresas])->andWhere(['status'=>1])->orderBy('id_pais')->all(), 'id_pais', function($data){
        $rtlvl1 = '';
        if($data->pais){
            $rtlvl1 = $data->pais->pais;
        }
        return $rtlvl1;
    });
} else {
    $nivel1 = ArrayHelper::map(Paises::find()->orderBy('pais')->all(), 'id', 'pais');
}
?>

<?php
$cantidad_trabajadores =  '--';
$cantidad_hc =  0;
$cantidad_poe =  0;
$cantidad_cuestionario =  0;
$cantidad_antropometrica =  0;
$porcentaje_cumplimiento =  0;


$iniciar_calculo = false;
if(isset($searchModel->id_empresa) && $searchModel->id_empresa != null && $searchModel->id_empresa != ''){
    $iniciar_calculo = true;
}


if($iniciar_calculo){
    //dd('entra a calcular');
    $querytotales = Trabajadores::find();

    $querypoe = Trabajadores::find();
    $querycuestionario = Trabajadores::find();
    $queryantropometrica = Trabajadores::find();

$querytotales->andWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')]);

$querypoe->andWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')]);
$querycuestionario->andWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')]);
$queryantropometrica->andWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')]);


if (Yii::$app->user->identity->empresa_all != 1) {
    $querytotales->andWhere(['in','id_empresa',$empresas]);

    $querypoe->andWhere(['in','id_empresa',$empresas]);
    $querycuestionario->andWhere(['in','id_empresa',$empresas]);
    $queryantropometrica->andWhere(['in','id_empresa',$empresas]);
}

if(isset($searchModel->id_empresa) && $searchModel->id_empresa != null && $searchModel->id_empresa != ''){
    $querytotales->andWhere(['id_empresa' => $searchModel->id_empresa]);

    $querypoe->andWhere(['id_empresa' => $searchModel->id_empresa]);
    $querycuestionario->andWhere(['id_empresa' => $searchModel->id_empresa]);
    $queryantropometrica->andWhere(['id_empresa' => $searchModel->id_empresa]);
} else {
    $querytotales->andWhere(['id_empresa' => Yii::$app->user->identity->id_empresa]); 

    $querypoe->andWhere(['id_empresa' => Yii::$app->user->identity->id_empresa]);
    $querycuestionario->andWhere(['id_empresa' => Yii::$app->user->identity->id_empresa]);
    $queryantropometrica->andWhere(['id_empresa' => Yii::$app->user->identity->id_empresa]);
}

$total_trabajadores = $querytotales->all();

$cantidad_trabajadores = count($total_trabajadores);

$porcentaje_aceptable = 0;

$queryhc = $querytotales;
$queryhc->andWhere(['>','hc_cumplimiento',$porcentaje_aceptable]);
$total_hc = $queryhc->all();
$cantidad_hc = count($total_hc);


//$querypoe = $querytotales;
$querypoe->andWhere(['>','poe_cumplimiento',$porcentaje_aceptable]);
$total_poe = $querypoe->all();
$cantidad_poe = count($total_poe);


//$querycuestionario = $querytotales;
$querycuestionario->andWhere(['>','cuestionario_cumplimiento',$porcentaje_aceptable]);
$total_cuestionario = $querycuestionario->all();
$cantidad_cuestionario = count($total_cuestionario);


//$queryantropometrica = $querytotales;
$queryantropometrica->andWhere(['>','antropometrica_cumplimiento',$porcentaje_aceptable]);
$total_antropometrica = $queryantropometrica->all();
$cantidad_antropometrica = count($total_antropometrica);


$sumatoria_cumplimiento = 0;
$porcentaje_cumplimiento = 0;
foreach($total_trabajadores as $k=>$t){
    $sumatoria_cumplimiento += $t->porcentaje_cumplimiento;
}

if($cantidad_trabajadores > 0){
    $porcentaje_cumplimiento = $sumatoria_cumplimiento/$cantidad_trabajadores;
}

$porcentaje_cumplimiento = number_format($porcentaje_cumplimiento, 2, '.', ',');
}

if(Yii::$app->user->identity->hidden_actions == 1){
    //dd($querycuestionario,$total_cuestionario);
}
?>

<div class="trabajadores-index">

    <h1 class="title1 text-uppercase"><?= Html::encode($this->title) ?></h1>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'headerRowOptions' =>['class' => 'text-label shadow-sm text-uppercase control-label border-0 small'],
        'tableOptions' => ['class' => 'table table-hover table-sm small'],
        'rowOptions' => ['class' => 'font-12 text-600 bg-white shadow-sm small'],
        'pager' => [
            'class' => 'yii\bootstrap5\LinkPager'
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

             [
                'attribute' =>'nombre',
                'contentOptions' => ['class' => "y_centercenter color3 font600",'style'=>'width:15%'],
                'filterInputOptions' => ['class' => 'icon-find2 form-control'],
                'format'=>'raw',
                'value'=>function($model){
                    $ret = '';

                    $ret = $model->apellidos.' '.$model->nombre;
                    
                    return $ret;
                  },
            ],
            [
                'attribute' =>'id_empresa',
                'visible' => $showempresa,
                'contentOptions' => ['class' => "y_centercenter color6 font600",'style'=>'width:10%'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'filter'=>Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'id_empresa',
                    'data' =>$empresas,
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'hideSearch' => false,
                    'options' => [
                        'placeholder' => 'Buscar...',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]),
                'format'=>'raw',
                'value'=>function($model){
                    $ret = '';
                    if($model->empresa){
                        $ret = $model->empresa->comercial;
                    }
                    return $ret;
                  },
            ],
             [
                    'attribute' =>'id_nivel1',
                    'format'=>'raw',
                    'contentOptions' => ['class' => " color3 font600 y_centercenter",'style'=>'width:10%'],
                    'filterInputOptions' => ['class' => 'icon-find form-control'],
                    'filter'=>Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'id_nivel1',
                    'data' =>$nivel1,
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'hideSearch' => false,
                    'options' => [
                        'placeholder' => 'Buscar...',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                    ]),
                    'headerOptions' => ['class' => "y_centercenter",'style'=>'color: #716F81;
                    text-decoration:none;
                    font-weight: 500;
                    font-size: 11px;'],
                    'visible'=>$show_nivel1,
                    'label'=> $label_nivel1,
                    'value'=>function($model){
                        $ret = '';

                        if($model->nivel1){
                            $ret .= '<span class="badge rounded-pill font10 btnnew2" style="">'.'<i class="bi bi-dash"></i>'.$model->nivel1->pais->pais.'</span>';
                        }
                        
                        return $ret;
                    },
            ],
            [
                    'attribute' =>'id_nivel2',
                    'format'=>'raw',
                    'contentOptions' => ['class' => " color3 font600 y_centercenter",'style'=>'width:10%'],
                    'filterInputOptions' => ['class' => 'icon-find form-control'],
                    'headerOptions' => ['class' => "y_centercenter",'style'=>'color: #716F81;
                    text-decoration:none;
                    font-weight: 500;
                    font-size: 11px;'],
                    'visible'=>$show_nivel2,
                    'label'=> $label_nivel2,
                    'value'=>function($model){
                        $ret = ''; 

                        if($model->nivel2){
                            $ret .= '<span class="badge rounded-pill font10 btnnew2" style="">'.'<i class="bi bi-dash"></i>'.$model->nivel2->nivelorganizacional2.'</span>';
                        }

                        return $ret;
                    },
            ],
            [
                    'attribute' =>'id_nivel3',
                    'format'=>'raw',
                    'contentOptions' => ['class' => " color3 font600 y_centercenter",'style'=>'width:10%'],
                    'filterInputOptions' => ['class' => 'icon-find form-control'],
                    'headerOptions' => ['class' => "y_centercenter",'style'=>'color: #716F81;
                    text-decoration:none;
                    font-weight: 500;
                    font-size: 11px;'],
                    'visible'=>$show_nivel3,
                    'label'=> $label_nivel3,
                    'value'=>function($model){
                        $ret = '';
                        
                        if($model->nivel3){
                            $ret .= '<span class="badge rounded-pill font10 btnnew2" style="">'.'<i class="bi bi-dash"></i>'.$model->nivel3->nivelorganizacional3.'</span>';
                        }

                        return $ret;
                    },
            ],
            [
                    'attribute' =>'id_nivel4',
                    'format'=>'raw',
                    'contentOptions' => ['class' => " color3 font600 y_centercenter",'style'=>'width:10%'],
                    'filterInputOptions' => ['class' => 'icon-find form-control'],
                    'headerOptions' => ['class' => "y_centercenter",'style'=>'color: #716F81;
                    text-decoration:none;
                    font-weight: 500;
                    font-size: 11px;'],
                    'visible'=>$show_nivel4,
                    'label'=> $label_nivel4,
                    'value'=>function($model){
                        $ret = '';
                        
                        if($model->nivel4){
                            $ret .= '<span class="badge rounded-pill font10 btnnew2" style="">'.'<i class="bi bi-dash"></i>'.$model->nivel4->nivelorganizacional4.'</span>';
                        }
                        
                        return $ret;
                    },
            ],
            [
                'attribute' =>'ps_status',
                'label'=>'Programas de Salud',
                'contentOptions' => ['class' => "y_centercenter",'style'=>''],
                'filterInputOptions' => ['class' => 'icon-find2 form-control'],
                'filter'=>Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'ps_status',
                    'data' =>['1'=>'Con Programa','2'=>'Sin Programa'],
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'hideSearch' => true,
                    'options' => [
                        'placeholder' => 'Buscar...',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]),
                'format'=>'raw',
                'value'=>function($model){
                    $ret = '';

                    if($model->ps_status == 1){
                        $ret =  '<div class="badge rounded-pill bg-light text-dark font10 y_centercenter font14"><span class="font10 color7"><i class="bi bi-circle-fill"></i></span> Con Programa</div>';
                    }else if( $model->ps_status == 2){
                        $ret =  '<div class="badge rounded-pill bg-light text-dark font10 y_centercenter font14"><span class="font10 color11" style="font-size:25px"><i class="bi bi-circle-fill"></i></span> Sin Programa</div>';
                    }
                    return $ret;
                },
            ],
            [
                'attribute' =>'ps_activos',
                'label'=>'PS Activos',
                'contentOptions' => ['class' => "y_centercenter",'style'=>''],
                'filterInputOptions' => ['class' => 'icon-find2 form-control'],
                'filter'=>Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'ps_activos',
                    'data' =>['1'=>'SI','2'=>'NO'],
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'hideSearch' => true,
                    'options' => [
                        'placeholder' => 'Buscar...',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]),
                'format'=>'raw',
                'value'=>function($model){
                    $ret = '';

                    if($model->ps_activos == 1){
                        $ret =  '<span class="badge bgcolor3 text-light font11"><span class="color7 mx-2" style=""><i class="bi bi-check"></i></span>SI</span>';
                    }else if( $model->ps_activos == 2){
                        $ret =  '<span class="badge bordercolor6 text-light font11"><span class="color11 mx-2" style=""><i class="bi bi-x"></i></span>NO</span>';
                    }
                    return $ret;
                },
            ],
            [
                    'attribute' =>'programas_salud',
                    'label'=>'Programas de Salud',
                    'format'=>'raw',
                    'contentOptions' => ['class' => " color3 font600 y_centercenter",'style'=>'width:40%'],
                    'filterInputOptions' => ['class' => 'icon-find form-control'],
                    'headerOptions' => ['class' => "y_centercenter",'style'=>'color: #716F81;
                    text-decoration:none;
                    font-weight: 500;
                    font-size: 11px;'],
                    'filter'=>Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'programas_salud',
                    'data' =>$programas,
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'hideSearch' => true,
                    'options' => [
                        'placeholder' => 'Buscar...',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]),
                    'value'=>function($model){
                    $ret = '';
                   
                    $colores = ['1'=>'bgcumple','2'=>'bgnocumple'];
                    $programasall = $model->programasall;

                    if($programasall){
                        $ret = '<table class="table table-bordered table-hover table-sm text-little font11" style="height:100%">';
                        $ret .= '<thead class="table-dark"><tr class="text-center">
                        <th width="5%">#</th>
                        <th width="40%">Programa</th>
                        
                        <th width="15%">F. Inicio</th>
                        <th width="15%">F. Fin</th>
                        <th width="15%">Conclusión</th>
                        <th width="10%">Status</th>
                        </tr></thead><tbody>';

                        foreach($programasall as $key=>$programa){
                            $rprograma = '';
                            $conclusion = '';
                            $fechainicio = $programa->fecha_inicio;
                            $fechafin = $programa->fecha_fin;
                            $status = '';

                            $color = '';
                            $colorconclusion = '';

                            $conclusiones = [1=>'Estable',2=>'En Mejoría',3=>'En Deterioro',4=>'Crítico',5=>'Concluido'];
                            $colores = [1=>'bgcolor7',2=>'bgcolor20',3=>'bgcolor21',4=>'bgcolor6',5=>'bgcolor4'];

                            if($programa->programa){
                                $rprograma = $programa->programa->nombre;
                            }

                            if($programa->status == 1){
                                $status = 'ACTIVO';

                                $color = 'color7 font500';
                            } else if($programa->status == 2){
                                $status = 'BAJA';

                                $color = 'color11 font500';
                            }

                            if($programa->conclusion != null && $programa->conclusion != '' && $programa->conclusion != ' '){
                                $conclusion = $conclusiones[$programa->conclusion];
                                $colorconclusion = $colores[$programa->conclusion];
                            }
                            
                            $ret .= '<tr>
                            <td width="5%" class="text-center ">'.($key+1).'</td>
                            <td width="40%" class="text-center font500">'.$rprograma.'</td>
                            
                            <td width="15%" class="text-center ">'.$fechainicio.'</td>
                            <td width="15%" class="text-center ">'.$fechafin.'</td>
                            <td width="15%" class="text-center '.$colorconclusion.'">'.$conclusion.'</td>
                            <td width="10%" class="text-center '.$color.'">'.$status.'</td>
                            </tr>';

                        }

                        $ret .= '</tbody></table>';

                    } else{
                        $ret = '<span class="color6 font11">SIN PROGRAMAS DE SALUD</span>';
                    }
                    
                    
                    return $ret.'</div>';
                },
            ],
           
             [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}',
                'header'=>"Accion",
                'headerOptions' => ['class' => "text-center", 'style'=>'vertical-align: top;'],
                'headerOptions' => ['class' => "y_centercenter",'style'=>'color: #716F81;
                text-decoration:none;
                font-weight: 500;
                font-size: 11px;'],
                'buttons' => [
                    'update' =>  function($url,$model) {
                        return Html::a('<span class=""><i class="bi bi-pen-fill"></i><span>', Url::to(['update','id' => $model->id]), [
                            'title' => Yii::t('app', 'Actualizar'),
                            'data-toggle'=>"tooltip",
                            'data-placement'=>"top",
                            'class'=>'btn btn-sm text-center shadow-sm'
                        ]);
                    },
                    
                ],
            ],
        ],
    ]); ?>


</div>
