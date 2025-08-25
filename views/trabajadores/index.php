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

use app\models\NivelOrganizacional1;
use app\models\NivelOrganizacional2;
use app\models\NivelOrganizacional3;
use app\models\NivelOrganizacional4;

use app\models\Poes;

/** @var yii\web\View $this */
/** @var app\models\TrabajadoresSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$name_empresa = '';
if(Yii::$app->user->identity->empresa){
    $name_empresa = ' | '.Yii::$app->user->identity->empresa->comercial;

    try {
        $empresa_usuario = Yii::$app->user->identity->empresa;

        if($empresa_usuario){
            if($empresa_usuario->cantidad_niveles >= 1){
                if(Yii::$app->user->identity->nivel1_all != 1) {
                    $array_niveles_1 = explode(',', Yii::$app->user->identity->nivel1_select);
                    if(count($array_niveles_1) == 1){
                        $pais_n1= Paises::findOne($array_niveles_1[0]);
                        if($pais_n1){
                            $name_empresa .= ' / '.$pais_n1->pais;
                        }
                    }
                }
            }
            if($empresa_usuario->cantidad_niveles >= 2){
                $niveles_2 = explode(',', Yii::$app->user->identity->nivel2_select);
                if(Yii::$app->user->identity->nivel2_all != 1 && (count($niveles_2) == 1)) {
                    $nivel_n2 = NivelOrganizacional2::findOne($niveles_2[0]);
                    if($nivel_n2){
                        $name_empresa .= ' / '.$nivel_n2->nivelorganizacional2;
                    }
                }
            }
            if($empresa_usuario->cantidad_niveles >= 3){
                $niveles_3 = explode(',', Yii::$app->user->identity->nivel3_select);
                if(Yii::$app->user->identity->nivel3_all != 1 && (count($niveles_3) == 1)) {
                    $nivel_n3 = NivelOrganizacional3::findOne($niveles_3[0]);
                    if($nivel_n3){
                        $name_empresa .= ' / '.$nivel_n3->nivelorganizacional3;
                    }
                }
            }
            if($empresa_usuario->cantidad_niveles >= 4){
                $niveles_4 = explode(',', Yii::$app->user->identity->nivel4_select);
                if(Yii::$app->user->identity->nivel4_all != 1 && (count($niveles_4) == 1)) {
                    $nivel_n4 = NivelOrganizacional4::findOne($niveles_4[0]);
                    if($nivel_n4){
                        $name_empresa .= ' / '.$nivel_n4->nivelorganizacional4;
                    }
                }
            }
        }
    } catch (\Throwable $th) {
        //throw $th;
    }
    
}

$this->title = 'Listado Trabajadores'.$name_empresa;
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


<?php 
      Modal::begin([
        'id' =>'modal-card',
        'title' => '<h5 class="text-uppercase text-purple">
                        
                    </h5>',
        'size' => 'modal-lg',
        'headerOptions' =>[
            'class' => 'text-light bg-mymodal btnnew'
        ],
        ]);
        echo '<div id="body-card"></div>';
        Modal::end();
?>

<?php 
      Modal::begin([
        'id' =>'modal-qr',
        'title' => '<h5 class="text-uppercase text-purple">
                        QR Trabajador
                    </h5>',
        'size' => 'modal-sm',
        'headerOptions' =>[
            'class' => 'text-light bg-mymodal btnnew'
        ],
        ]);
        echo '<div id="body-qr"></div>';
        Modal::end();
?>

<div class="trabajadores-index">

    <h1 class="title1 text-uppercase"><?= Html::encode($this->title) ?></h1>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?php 
      if(Yii::$app->user->can('trabajadores_exportar')){
        $fullExportMenu = ExportMenu::widget([
            'dataProvider' => $dataProvider,
            'columns' =>[
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'attribute' =>'id',
                    'label'=>'ID'
                ],
                [
                    'attribute' =>'id_empresa',
                    'visible' => $showempresa,
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
                    'visible'=>$show_nivel1,
                    'label'=> $label_nivel1,
                    'value'=>function($model){
                        $ret = '';

                        if($model->nivel1){
                            $ret .= $model->nivel1->pais->pais;
                        }
                        
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'id_nivel2',
                    'format'=>'raw',
                    'visible'=>$show_nivel2,
                    'label'=> $label_nivel2,
                    'value'=>function($model){
                        $ret = ''; 

                        if($model->nivel2){
                            $ret .= $model->nivel2->nivelorganizacional2;
                        }

                        return $ret;
                    },
                ],
                [
                    'attribute' =>'id_nivel3',
                    'format'=>'raw',
                    'visible'=>$show_nivel3,
                    'label'=> $label_nivel3,
                    'value'=>function($model){
                        $ret = '';
                        
                        if($model->nivel3){
                            $ret .= $model->nivel3->nivelorganizacional3;
                        }

                        return $ret;
                    },
                ],
                [
                    'attribute' =>'id_nivel4',
                    'format'=>'raw',
                    'visible'=>$show_nivel4,
                    'label'=> $label_nivel4,
                    'value'=>function($model){
                        $ret = '';
                        
                        if($model->nivel4){
                            $ret .= $model->nivel4->nivelorganizacional4;
                        }
                        
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'nombre',
                    'value'=>function($model){
                       return $model->nombre.' '.$model->apellidos;
                    },
                ],
                [
                    'attribute' =>'num_trabajador',
                ],
                [
                    'attribute' =>'fecha_nacimiento',
                ],
                [
                    'attribute' =>'edad',
                ],
                [
                    'attribute' =>'sexo',
                    'value'=>function($model){
                       $ret = '';
                       if($model->sexo == 1){
                           $ret = 'Masculino';
                       } else if($model->sexo == 2){
                           $ret = 'Femenino';
                       } else if($model->sexo == 3){
                        $ret = 'Otro';
                    }
                       return $ret;
                     },
                ],
                [
                    'attribute' =>'id_area',
                    'visible'=>false,
                    'value'=>function($model){
                        $ret = '';
                        if(isset($model->area)){
                            $ret = $model->area->area;
                        }
                        return $ret;
                     },
                ],
                [
                    'attribute' =>'puesto_contable',
                    'contentOptions' => ['class' => "y_centercenter",'style'=>''],
                    'filterInputOptions' => ['class' => 'icon-find form-control'],
                    'format'=>'raw',
                ],
                [
                    'attribute' =>'puesto_sueldo',
                    'format'=>'raw',
                    'value'=>function($model){
                        $ret = '';
    
                        if($model->puesto_sueldo){
                            $ret = '$'.number_format($model->puesto_sueldo, 3, '.', ',');
                        }
    
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'id_puesto',
                    'value'=>function($model){
                        $ret = '';
                        if(isset($model->puesto)){
                            $ret = $model->puesto->nombre;
                        }
                        return $ret;
                     },
                ],
                [
                    'attribute' =>'riesgos',
                    'value'=>function($model){
                        $ret = '';
    
                        if(isset($model->puesto)){
                            if($model->puesto->riesgos){
                                foreach($model->puesto->riesgos as $key=>$riesgo){
                                    $ret.= $riesgo->riesgo;
                                    if($key < (count($model->puesto->riesgos)-1)){
                                        $ret .= ',';
                                    }
                                }
                            }
                        }
                        
                        return $ret;
                      },
                ],
                
                [
                    'attribute' =>'tipo_contratacion',
                    'value'=>function($model){
                        $ret = '';
                        if($model->tipo_contratacion == 1){
                            $ret = '1 MES';
                        } else if($model->tipo_contratacion == 2){
                            $ret = '2 MESES';
                        } else if($model->tipo_contratacion == 3){
                            $ret = '3 MESES';
                        } else if($model->tipo_contratacion == 4){
                            $ret = '4 MESES';
                        } else if($model->tipo_contratacion == 5){
                            $ret = '5 MESES';
                        } else if($model->tipo_contratacion == 6){
                            $ret = '6 MESES';
                        } else if($model->tipo_contratacion == 7){
                            $ret = 'INDEFINIDO';
                        }
                       return $ret;
                     },
                ],
                [
                    'attribute' =>'fecha_contratacion',
                ],
                [
                    'attribute' =>'fecha_baja',
                ],
                [
                    'attribute' =>'antiguedad',
                ],
                [
                    'attribute' =>'estado_civil',
                    'value'=>function($model){
                        $ret = '';
                        if($model->estado_civil == 1){
                            $ret = 'SOLTERO';
                        } else if($model->estado_civil == 2){
                           $ret = 'CASADO';
                        } else if($model->estado_civil == 3){
                            $ret = 'VIUDO';
                        } else if($model->estado_civil == 4){
                            $ret = 'UNIÓN LIBRE';
                        }
                       return $ret;
                     },
                ],
                [
                    'attribute' =>'nivel_lectura',
                    'value'=>function($model){
                       $ret = '';
                       if($model->nivel_lectura == 1){
                           $ret = 'BUENO';
                       } else if($model->nivel_lectura == 2){
                           $ret = 'REGULAR';
                       } else if($model->nivel_lectura == 3){
                        $ret = 'MALO';
                    }
                       return $ret;
                     },
                ],
                [
                    'attribute' =>'nivel_escritura',
                    'value'=>function($model){
                       $ret = '';
                       if($model->nivel_escritura == 1){
                           $ret = 'BUENO';
                       } else if($model->nivel_escritura == 2){
                           $ret = 'REGULAR';
                       } else if($model->nivel_escritura == 3){
                        $ret = 'MALO';
                    }
                       return $ret;
                     },
                ],
                [
                    'attribute' =>'escolaridad',
                    'value'=>function($model){
                        $ret = '';
                        if($model->escolaridad == 1){
                            $ret = 'PRIMARIA';
                        } else if($model->escolaridad == 2){
                            $ret = 'SECUNDARIA';
                        } else if($model->escolaridad == 3){
                            $ret = 'PREPARATORIA';
                        } else if($model->escolaridad == 4){
                            $ret = 'CARRERA TÉCNICA';
                        } else if($model->escolaridad == 5){
                            $ret = 'LICENCIATURA';
                        } else if($model->escolaridad == 6){
                            $ret = 'MAESTRIA';
                        } else if($model->escolaridad == 7){
                            $ret = 'DOCTORADO';
                        }
                       return $ret;
                     },
                ],
                [
                    'attribute' =>'num_imss',
                ],
                [
                    'attribute' =>'curp',
                ],
                [
                    'attribute' =>'rfc',
                ],
                [
                    'attribute' =>'celular',
                ],
                [
                    'attribute' =>'correo',
                ],
                [
                    'attribute' =>'contacto_emergencia',
                ],
                [
                    'attribute' =>'direccion',
                ],
                [
                    'attribute' =>'colonia',
                ],
                [
                    'attribute' =>'pais',
                ],
                [
                    'attribute' =>'estado',
                ],
                [
                    'attribute' =>'ciudad',
                ],
                [
                    'attribute' =>'municipio',
                ],
                [
                    'attribute' =>'cp',
                ],
                [
                    'attribute' =>'ruta',
                ],
                [
                    'attribute' =>'parada',
                ],
                [
                    'attribute' =>'teamleader',
                ],
                [
                    'attribute' =>'cal',
                    'value'=>function($model){
                        $ret = 'NO';
                        if($model->historiaclinica && $model->historiaclinica->status == 2){
                            $ret = 'SI';
                        }
                        return $ret;
                     },
                ],
                [
                    'attribute' =>'prog_salud',
                    'label'=>'Prog. Salud',
                    'value'=>function($model){
                        $ret = '';
                        if($model->programas){
                            foreach($model->programas as $key=>$program){
                                if($program->programa){
                                    $ret .= $program->programa->nombre;
                                    if($key < (count($model->programas)-1)){
                                        $ret .= ',';
                                    }
                                }
                               
                            }
                        }
                        return $ret;
                     },
                ],
                
                [
                    'attribute' =>'tipo_registro',
                    'label'=>'Registro',
                    'value'=>function($model){
                        $ret = '';
                        if($model->tipo_registro != 2){
                            $ret .= 'MANUAL';
                        } else if($model->tipo_registro == 2){
                            $ret .= 'CARGA MASIVA';
                        }
                        return $ret;
                     },
                ],
                [
                    'attribute' =>'dato_extra1',
                    'value'=>function($model){
                        $ret = '';
                        if(isset($model->dato_extra1)){
                            $ret = $model->dato_extra1;
                        }
                        return $ret;
                     },
                ],
                [
                    'attribute' =>'dato_extra2',
                    'value'=>function($model){
                        $ret = '';
                        if(isset($model->dato_extra2)){
                            $ret = $model->dato_extra2;
                        }
                        return $ret;
                     },
                ],
                [
                    'attribute' =>'dato_extra3',
                    'value'=>function($model){
                        $ret = '';
                        if(isset($model->dato_extra3)){
                            $ret = $model->dato_extra3;
                        }
                        return $ret;
                     },
                ],
                [
                    'attribute' =>'dato_extra4',
                    'value'=>function($model){
                        $ret = '';
                        if(isset($model->dato_extra4)){
                            $ret = $model->dato_extra4;
                        }
                        return $ret;
                     },
                ],
                [
                    'attribute' =>'dato_extra5',
                    'value'=>function($model){
                        $ret = '';
                        if(isset($model->dato_extra5)){
                            $ret = $model->dato_extra5;
                        }
                        return $ret;
                     },
                ],
                [
                    'attribute' =>'dato_extra6',
                    'value'=>function($model){
                        $ret = '';
                        if(isset($model->dato_extra6)){
                            $ret = $model->dato_extra6;
                        }
                        return $ret;
                     },
                ],
                [
                    'attribute' =>'dato_extra7',
                    'value'=>function($model){
                        $ret = '';
                        if(isset($model->dato_extra7)){
                            $ret = $model->dato_extra7;
                        }
                        return $ret;
                     },
                ],
                [
                    'attribute' =>'dato_extra8',
                    'value'=>function($model){
                        $ret = '';
                        if(isset($model->dato_extra8)){
                            $ret = $model->dato_extra8;
                        }
                        return $ret;
                     },
                ],
                [
                    'attribute' =>'dato_extra9',
                    'value'=>function($model){
                        $ret = '';
                        if(isset($model->dato_extra9)){
                            $ret = $model->dato_extra9;
                        }
                        return $ret;
                     },
                ],
                [
                    'attribute' =>'dato_extra10',
                    'value'=>function($model){
                        $ret = '';
                        if(isset($model->dato_extra10)){
                            $ret = $model->dato_extra10;
                        }
                        return $ret;
                     },
                ],
                [
                    'attribute' =>'status_documentos',
                    'label'=>'Status Docs.',
                    'value'=>function($model){
                        $ret = '';
    
                        if($model->status_documentos == 0){
                            $ret =  'PENDIENTE';
                        }else if( $model->status_documentos == 1){
                            $ret =  'CUMPLE';
                        } else if( $model->status_documentos == 2){
                            $ret =  'NO CUMPLE';
                        }
                       
                        return $ret;
                      },
                ],
                [
                'attribute' =>'estudios_pendientes',
                'label'=>'Estudios Pendientes',
                'contentOptions' => ['class' => "y_centercenter",'style'=>''],
                'format'=>'raw',
                'value'=>function($model){
                    $ret = '';

                    if($model->estudios_pendientes == 1){
                        $ret =  'COMPLETO';
                    } if($model->estudios_pendientes == 2){
                        $ret =  'FALTA ENTREGA DE RESULTADOS';
                    } else if ($model->estudios_pendientes == 3) {
                        $ret =  'FALTA REALIZAR';
                    }
                   
                    return $ret;
                  },
                ],
                [
                    'attribute' =>'status',
                    'value'=>function($model){
                        $ret = '';
    
                        if($model->status == 5){
                            $ret =  'Baja';
                        }else if( $model->status == 1){
                            $ret =  'Activo';
                        } else if( $model->status == 3){
                            $ret =  'NI';
                        }
                       
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'create_date',
                ],
                [
                    'attribute' =>'create_user',
                    'value'=>function($model){
                        $ret = '';
    
                        if($model->uCaptura){
                            $ret =  $model->uCaptura->name;
                        }
                       
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'update_date',
                ],
                [
                    'attribute' =>'update_user',
                    'value'=>function($model){
                        $ret = '';
    
                        if($model->uActualiza){
                            $ret =  $model->uActualiza->name;
                        }
                       
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'delete_date',
                ],
                [
                    'attribute' =>'delete_user',
                    'value'=>function($model){
                        $ret = '';
    
                        if($model->uElimina){
                            $ret =  $model->uElimina->name;
                        }
                       
                        return $ret;
                    },
                ],
               
            ],
            'hiddenColumns'=>[0],
            'clearBuffers' => true,
            'showConfirmAlert' =>false,
            'columnBatchToggleSettings'=>[
                'label'=>'Seleccionar Todo',
                'class' =>'px-2'
            ], 
            'filename'=> $this->title.'_reportdate_'.date('Y-m-d'),
            'columnSelectorOptions'=>[
                'label' => 'Columnas',
                'class' => 'btn btn-success text-light colorexcel'
            ],
              'dropdownOptions' => [
              'label' => 'Exportar a',
              'class' => 'btn btn-success text-light colorexcel'
             ],
            'exportConfig' => [
                ExportMenu::FORMAT_CSV => false,
                ExportMenu::FORMAT_TEXT => false,
                ExportMenu::FORMAT_HTML => false,
                //ExportMenu::FORMAT_EXCEL_X => false,
                ExportMenu::FORMAT_EXCEL => false,
                ExportMenu::FORMAT_PDF => false,
            ]   
        ]);
    }else{
        $fullExportMenu = '';
    }
?>

    <div class="row mb-3">
        <div class="col-lg-2 d-grid">
            <?php if(Yii::$app->user->can('trabajadores_crear') && $disponible):?>
            <?= Html::a('<span class="mx-2 small"><i class="bi bi-plus-lg"></i></span>Nuevo Trabajador', ['create'], ['class' => 'btn btn-primary btnnew btn-block']) ?>
            <?php endif;?>
        </div>
        <div class="col-lg-1 text-center px-0">
            <?php
            if(Yii::$app->user->identity->hidden_actions == 1){
                echo Html::a('<span class=""><i class="bi bi-arrow-clockwise"></i><span> Trabajadores', Url::to(['trabajadores/refresh']), [
                    'title' => Yii::t('app', 'Refresh Trabajadores'),
                    'data-toggle'=>"tooltip",
                    'data-placement'=>"top",
                    'class'=>'btn btn-primary btnnew3 btn-block'
                ]);
            }
            ?>
        </div>
        <div class="col-lg-1 text-center px-0">
            <?php
            if(Yii::$app->user->identity->hidden_actions == 1){
                echo Html::a('<span class=""><i class="bi bi-arrow-clockwise"></i><span> Estudios Médicos', Url::to(['trabajadores/refreshpoes']), [
                    'title' => Yii::t('app', 'Refresh Estudios Médicos'),
                    'data-toggle'=>"tooltip",
                    'data-placement'=>"top",
                    'class'=>'btn btn-primary btnnew5 btn-block'
                ]);
            }
            ?>
        </div>
        <div class="col-lg-1 text-center px-0">
            <?php
            if(Yii::$app->user->identity->hidden_actions == 1){
                echo Html::a('<span class=""><i class="bi bi-arrow-clockwise"></i><span> Trabajadores->HC', Url::to(['trabajadores/refreshtrabhc']), [
                    'title' => Yii::t('app', 'Refresh Trabajadores->HC'),
                    'data-toggle'=>"tooltip",
                    'data-placement'=>"top",
                    'class'=>'btn bgcolor1 btnnew5 btn-block text-light'
                ]);
            }
            ?>
        </div>
        <div class="col-lg-1 text-center px-0">
            <?php
            if(Yii::$app->user->identity->hidden_actions == 1){
                echo Html::a('<span class=""><i class="bi bi-arrow-clockwise"></i><span> Trabajadores->Cumplimientos', Url::to(['trabajadores/refreshcumplimientos']), [
                    'title' => Yii::t('app', 'Refresh Trabajadores->Cumplimientos'),
                    'data-toggle'=>"tooltip",
                    'data-placement'=>"top",
                    'class'=>'btn btnnew4 btn-block text-light'
                ]);
            }
            ?>
        </div>
        <div class="col-lg-1 text-center px-0">
            <?php
            if(Yii::$app->user->identity->hidden_actions == 1){
                echo Html::a('<span class=""><i class="bi bi-arrow-clockwise"></i><span> Programas de Salud', Url::to(['trabajadores/refreshprogramas']), [
                    'title' => Yii::t('app', 'Refresh Programas de Salud'),
                    'data-toggle'=>"tooltip",
                    'data-placement'=>"top",
                    'class'=>'btn btnnew6 btn-block '
                ]);
            }
            ?>
        </div>
        <div class="col-lg-2 text-end">
            <?php
            echo $fullExportMenu;
            ?>
        </div>
    </div>


    <?php if($iniciar_calculo):?>
    <div class="row mb-3">
        <div class="col-lg-2">
            <div class="card text-dark btnnew2 border-0 p-3">
                <h6 class="title1 text-uppercase text-center text-strong">
                    <?php echo $cantidad_trabajadores;?></h6>
                <label class="font12  text-center">Qty Trabajadores</label>
            </div>
        </div>
        <div class="col-lg-2">
            <div class="card text-dark bggloss-color2 border-0 p-3">
                <h6 class="title1 text-uppercase text-center text-strong">
                    <?php echo $cantidad_hc;?></h6>
                <label class="font10  text-center">Qty trabajadores con Historia Clínica</label>
            </div>
        </div>
        <div class="col-lg-2">
            <div class="card text-dark bggloss-color5 border-0 p-3">
                <h6 class="title1 text-uppercase text-center text-strong">
                    <?php echo $cantidad_poe;?></h6>
                <label class="font10  text-center">Qty trabajadores con Exámenes Médicos</label>
            </div>
        </div>
        <div class="col-lg-2">
            <div class="card text-dark bggloss-color3 border-0 p-3">
                <h6 class="title1 text-uppercase text-center text-strong">
                    <?php echo $cantidad_cuestionario;?></h6>
                <label class="font10  text-center">Qty trabajadores con Cuestionario Nórdico</label>
            </div>
        </div>
        <div class="col-lg-2">
            <div class="card text-dark bggloss-color4 border-0 p-3">
                <h6 class="title1 text-uppercase text-center text-strong">
                    <?php echo $cantidad_antropometrica;?></h6>
                <label class="font10  text-center">Qty trabajadores con Medidas Antropométricas</label>
            </div>
        </div>
        <div class="col-lg-2">
            <div class="card text-dark bgcolor21 border-0 p-3">
                <h6 class="title1 text-uppercase text-center text-strong">
                    <?php echo $porcentaje_cumplimiento.'%';?></h6>
                <label class="font12  text-center">Cumplimiento Trabajadores</label>
            </div>
        </div>
    </div>
    <?php endif;?>

    <?php
    $template = '';
    /* if(Yii::$app->user->can('trabajadores_ver')){
        $template .='{view}';
    } */
    if(Yii::$app->user->can('trabajadores_expediente')){
        $template .='{doc}';
    }
    if(Yii::$app->user->can('trabajadores_actualizar')){
        $template .='{update}';
    }
    if(Yii::$app->user->can('trabajadores_delete') ){
        //&& Yii::$app->user->identity->activo_eliminar == 1
        $template .='{delete}';
    }
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'headerRowOptions' =>['class' => 'text-label shadow-sm text-uppercase control-label border-0 small text-center','style'=>''],
        'tableOptions' => ['class' => 'table table-hover table-sm small','style'=>'width:2500px !important'],
        'rowOptions' => ['class' => 'font-12 text-600 bg-white shadow-sm small'],
        'pager' => [
            'class' => 'yii\bootstrap5\LinkPager'
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute'=>'foto',
                'format'=>'raw',
                'label'=>'',
                'headerOptions'=>['style'=>'width:5%;','class'=>'font12'],
                'contentOptions'=>['class'=>'font500 y_centercenter' ,'style'=>'vertical-align: middle;'],
                'filterInputOptions' => ['class' => 'icon-find form-control','placeholder'=>'Buscar...'],
                'visible'=>true,
                "value"=>function($model){
                    $ret = '';
                    
                    if(isset($model->foto) && $model->foto != ""){
                        $filePath =  '/resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id.'/Documentos/'.$model->foto;
                        //$foto = '<div class="circular2 y_centercenter" style="background-image: url(\'/resources/images/av2.jpg\');  background-position: center; background-size: cover;"></div>';
                        $foto = '<div class="circular2 y_centercenter" style="background-image: url(\'/web/resources/Empresas/'.$model->id_empresa."/Trabajadores/".$model->id."/Documentos/".$model->foto.'\');  background-position: center; background-size: cover;"></div>';
                        //$ret = '<div class="circular text-center y_centercenter">'.$foto.'</div>';

                        $boton = Html::Button($foto, [
                            'title' => Yii::t('app', 'Ficha Trabajador: '.$model->apellidos.' '.$model->nombre),
                            'data-toggle'=>'tooltip',
                            'data-placement'=>'right',
                            'class' => 'p-0 m-0 border-0',
                            'style'=>'font-size:25px;',
                            'name' => 'card',
                            'id' =>$model->id,
                            'value' =>Url::to(['card','id' => $model->id])
                        ]);

                        $ret = '<div class="circular text-center y_centercenter">'.$boton.'</div>';

                    } else{

                        $boton = Html::Button('<span class="circular2"></span>', [
                            'title' => Yii::t('app', 'Ficha Trabajador: '.$model->apellidos.' '.$model->nombre),
                            'data-toggle'=>'tooltip',
                            'data-placement'=>'right',
                            'class' => 'p-0 m-0 border-0',
                            'style'=>'font-size:25px;',
                            'name' => 'card',
                            'id' =>$model->id,
                            'value' =>Url::to(['card','id' => $model->id])
                        ]);

                        $ret = '<div class="circular text-center y_centercenter">'.$boton.'</div>';
                    }

                    return $ret;
                }
            ],
            [
                'attribute' =>'num_trabajador',
                'contentOptions' => ['class' => "y_centercenter",'style'=>''],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'value'=>function($model){
                   return $model->num_trabajador;
                },
            ],
            [
                'attribute' =>'nombre',
                'label'=>'Nombre Trabajador',
                'contentOptions' => ['class' => "y_centercenter text-center color3 font600",'style'=>''],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'headerOptions' => ['style'=>''],
                'format'=>'raw',
                'value'=>function($model){
                    $ret = '';

                    $ret = Html::a($model->apellidos.' '.$model->nombre.'<span class="mx-2"><i class="bi bi-archive-fill"></i></span>', ['diagramas/historicodocs', 'src' => $model->id], [
                            'title' => Yii::t('app', 'Ver Histórico'),
                            'data-bs-toggle'=>"tooltip",
                            'data-bs-placement'=>"top",
                            'class'=>'btn btn-sm shadow-sm color3 font12 font600',
                            'target'=>'_blank'
                    ]);
                    

                    if(Yii::$app->user->identity->hidden_actions == 1){
                        $iconmagic = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-magic" viewBox="0 0 16 16">
                                 <path d="M9.5 2.672a.5.5 0 1 0 1 0V.843a.5.5 0 0 0-1 0zm4.5.035A.5.5 0 0 0 13.293 2L12 3.293a.5.5 0 1 0 .707.707zM7.293 4A.5.5 0 1 0 8 3.293L6.707 2A.5.5 0 0 0 6 2.707zm-.621 2.5a.5.5 0 1 0 0-1H4.843a.5.5 0 1 0 0 1zm8.485 0a.5.5 0 1 0 0-1h-1.829a.5.5 0 0 0 0 1zM13.293 10A.5.5 0 1 0 14 9.293L12.707 8a.5.5 0 1 0-.707.707zM9.5 11.157a.5.5 0 0 0 1 0V9.328a.5.5 0 0 0-1 0zm1.854-5.097a.5.5 0 0 0 0-.706l-.708-.708a.5.5 0 0 0-.707 0L8.646 5.94a.5.5 0 0 0 0 .707l.708.708a.5.5 0 0 0 .707 0l1.293-1.293Zm-3 3a.5.5 0 0 0 0-.706l-.708-.708a.5.5 0 0 0-.707 0L.646 13.94a.5.5 0 0 0 0 .707l.708.708a.5.5 0 0 0 .707 0z"/>
                                  </svg>';
                        $ret .= '<br>'.Html::a('<span class="color16">'.$iconmagic.'<span>', ['getnivelestrabajador', 'id' => $model->id], [
                            'data' => [
                                'confirm' => Yii::t('app', '¿Seguro que desea <b>COMPLETAR NIVELES FALTANTES</b> en Consultorio para el trabajador: '.$model->apellidos.' '.$model->nombre.'?'),
                                'method' => 'post',
                            ],
                            'title' => Yii::t('app', 'Fix'),
                            'data-bs-toggle'=>"tooltip",
                            'data-bs-placement'=>"top",
                            'class'=>'btn btn-sm text-center shadow-sm'
                        ]);
                    }
                    return $ret;
                },
            ],
            [
                'attribute' =>'porcentaje_cumplimiento',
                'label'=>'Cumplimiento Trabajador',
                'contentOptions' => ['class' => "y_centercenter text-center font500",'style'=>''],
                'filter'=>Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'porcentaje_cumplimiento',
                    'data' =>[0=>'0%',25=>'25%',50=>'50%',75=>'75%',100=>'100%'],
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
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'value'=>function($model){
                    $ret =  $model->porcentaje_cumplimiento.'%<br>';

                    $color_status = '';
                    $atributo = $model->puesto_cumplimiento;
                    if($atributo < 1){
                        $color_status = 'bg-light';
                    } else if($atributo > 0 && $atributo <= 25){
                        $color_status = 'bg-danger';
                    } else if($atributo > 25 && $atributo <= 50){
                        $color_status = 'bg-warning';
                    } else if($atributo > 50 && $atributo <= 75){
                        $color_status = 'bg-primary';
                    } else if($atributo > 75){
                        $color_status = 'bg-success';
                    }
                    $ret.= '<label class="font10">Puesto de Trabajo - '.$model->puesto_cumplimiento.'%</label>';
                    if($atributo>0){
                    $ret.= '<div class="progress mb-1">
                                <div class="progress-bar '.$color_status.'" style="width:'.$model->puesto_cumplimiento.'%"></div>
                            </div> ';
                    }

                    $color_status = '';
                    $atributo = $model->riesgo_cumplimiento;
                    if($atributo < 1){
                        $color_status = 'bg-light';
                    } else if($atributo > 0 && $atributo <= 25){
                        $color_status = 'bg-danger';
                    } else if($atributo > 25 && $atributo <= 50){
                        $color_status = 'bg-warning';
                    } else if($atributo > 50 && $atributo <= 75){
                        $color_status = 'bg-primary';
                    } else if($atributo > 75){
                        $color_status = 'bg-success';
                    }
                    $ret.= '<label class="font10">Riesgos - '.$model->riesgo_cumplimiento.'%</label>';
                    if($atributo>0){
                    $ret.= '<div class="progress">
                                <div class="progress-bar '.$color_status.'" style="width:'.$model->riesgo_cumplimiento.'%"></div>
                            </div> ';
                    }

                    $color_status = '';
                    $atributo = $model->riesgohistorico_cumplimiento;
                    if($atributo < 1){
                        $color_status = 'bg-light';
                    } else if($atributo > 0 && $atributo <= 25){
                        $color_status = 'bg-danger';
                    } else if($atributo > 25 && $atributo <= 50){
                        $color_status = 'bg-warning';
                    } else if($atributo > 50 && $atributo <= 75){
                        $color_status = 'bg-primary';
                    } else if($atributo > 75){
                        $color_status = 'bg-success';
                    }
                    $ret.= '<label class="font10">Riesgos Histórico - '.$model->riesgohistorico_cumplimiento.'%</label>';
                    if($atributo>0){
                    $ret.= '<div class="progress">
                                <div class="progress-bar '.$color_status.'" style="width:'.$model->riesgohistorico_cumplimiento.'%"></div>
                            </div> ';
                    }

                    $color_status = '';
                    $atributo = $model->programasalud_cumplimiento;
                    if($atributo < 1){
                        $color_status = 'bg-light';
                    } else if($atributo > 0 && $atributo <= 25){
                        $color_status = 'bg-danger';
                    } else if($atributo > 25 && $atributo <= 50){
                        $color_status = 'bg-warning';
                    } else if($atributo > 50 && $atributo <= 75){
                        $color_status = 'bg-primary';
                    } else if($atributo > 75){
                        $color_status = 'bg-success';
                    }
                    $ret .= '<label class="font10">Programas de Salud - '.$model->programasalud_cumplimiento.'%</label>';
                    if($atributo>0){
                    $ret.= '<div class="progress">
                                <div class="progress-bar '.$color_status.'" style="width:'.$model->programasalud_cumplimiento.'%"></div>
                            </div> ';
                    }

                    $color_status = '';
                    $atributo = $model->expediente_cumplimiento;
                    if($atributo < 1){
                        $color_status = 'bg-light';
                    } else if($atributo > 0 && $atributo <= 25){
                        $color_status = 'bg-danger';
                    } else if($atributo > 25 && $atributo <= 50){
                        $color_status = 'bg-warning';
                    } else if($atributo > 50 && $atributo <= 75){
                        $color_status = 'bg-primary';
                    } else if($atributo > 75){
                        $color_status = 'bg-success';
                    }
                    $ret.= '<label class="font10">Expediente - '.$model->expediente_cumplimiento.'%</label>';
                    if($atributo>0){
                    $ret.= '<div class="progress">
                                <div class="progress-bar '.$color_status.'" style="width:'.$model->expediente_cumplimiento.'%"></div>
                            </div> ';
                    }

                    $color_status = '';
                    $atributo = $model->hc_cumplimiento;
                    if($atributo < 1){
                        $color_status = 'bg-light';
                    } else if($atributo > 0 && $atributo <= 25){
                        $color_status = 'bg-danger';
                    } else if($atributo > 25 && $atributo <= 50){
                        $color_status = 'bg-warning';
                    } else if($atributo > 50 && $atributo <= 75){
                        $color_status = 'bg-primary';
                    } else if($atributo > 75){
                        $color_status = 'bg-success';
                    }
                    $ret.= '<label class="font10">Historia Clínica - '.$model->hc_cumplimiento.'%</label>';
                    if($atributo>0){
                    $ret.= '<div class="progress">
                                <div class="progress-bar '.$color_status.'" style="width:'.$model->hc_cumplimiento.'%"></div>
                            </div> ';
                    }      

                    $color_status = '';
                    $atributo = $model->poe_cumplimiento;
                    if($atributo < 1){
                        $color_status = 'bg-light';
                    } else if($atributo > 0 && $atributo <= 25){
                        $color_status = 'bg-danger';
                    } else if($atributo > 25 && $atributo <= 50){
                        $color_status = 'bg-warning';
                    } else if($atributo > 50 && $atributo <= 75){
                        $color_status = 'bg-primary';
                    } else if($atributo > 75){
                        $color_status = 'bg-success';
                    }
                    $ret.= '<label class="font10">Exámenes Médicos - '.$model->poe_cumplimiento.'%</label>';
                    if($atributo>0){
                    $ret.= '<div class="progress">
                                <div class="progress-bar '.$color_status.'" style="width:'.$model->poe_cumplimiento.'%"></div>
                            </div> ';
                    }

                    $color_status = '';
                    $atributo = $model->cuestionario_cumplimiento;
                    if($atributo < 1){
                        $color_status = 'bg-light';
                    } else if($atributo > 0 && $atributo <= 25){
                        $color_status = 'bg-danger';
                    } else if($atributo > 25 && $atributo <= 50){
                        $color_status = 'bg-warning';
                    } else if($atributo > 50 && $atributo <= 75){
                        $color_status = 'bg-primary';
                    } else if($atributo > 75){
                        $color_status = 'bg-success';
                    }
                    $ret.= '<label class="font10">Cuestionario Nórdico - '.$model->cuestionario_cumplimiento.'%</label>';
                    if($atributo>0){
                    $ret.= '<div class="progress">
                                <div class="progress-bar '.$color_status.'" style="width:'.$model->cuestionario_cumplimiento.'%"></div>
                            </div> ';
                    }

                    $color_status = '';
                    $atributo = $model->antropometrica_cumplimiento;
                    if($atributo < 1){
                        $color_status = 'bg-light';
                    } else if($atributo > 0 && $atributo <= 25){
                        $color_status = 'bg-danger';
                    } else if($atributo > 25 && $atributo <= 50){
                        $color_status = 'bg-warning';
                    } else if($atributo > 50 && $atributo <= 75){
                        $color_status = 'bg-primary';
                    } else if($atributo > 75){
                        $color_status = 'bg-success';
                    }
                    $ret.= '<label class="font10">Medidas Antropométricas - '.$model->antropometrica_cumplimiento.'%</label>';
                    if($atributo>0){
                    $ret.= '<div class="progress">
                                <div class="progress-bar '.$color_status.'" style="width:'.$model->antropometrica_cumplimiento.'%"></div>
                            </div> ';
                    }
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
                    'contentOptions' => ['class' => " color3 font600 y_centercenter",'style'=>'width:12%'],
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
                    'contentOptions' => ['class' => " color3 font600 y_centercenter",'style'=>'width:12%'],
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
                    'contentOptions' => ['class' => " color3 font600 y_centercenter",'style'=>'width:12%'],
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
                    'contentOptions' => ['class' => " color3 font600 y_centercenter",'style'=>'width:12%'],
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
                'attribute' =>'create_date',
                'label'=>'F. Registro',
                'visible'=>false,
                'contentOptions' => ['class' => "y_centercenter",'style'=>''],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'filter' => \kartik\daterange\DateRangePicker::widget(
                    [
                        'model' => $searchModel, 
                        'attribute' => 'create_date', 
                        'convertFormat' => true,
                        'presetDropdown' => true, 
                        'hideInput' => true, 
                        'pluginOptions' => [
                            'timePicker' => true,
                            'timePickerIncrement' => 1,
                            'timePicker24Hour' => true,
                            'locale' => [
                                'format' => ('Y-m-d'),
                            ],
                        ]
                    ])
            ],
            [
                'attribute' =>'status',
                'label'=>'Condición',
                'format'=>'raw',
                'contentOptions' => ['class' => "y_centercenter",'style'=>''],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'filter'=>Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'status',
                    'data' =>['1'=>'Activo','3'=>'NI','5'=>'Baja'],
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'hideSearch' => false,
                    
                    'options' => [
                        'placeholder' => 'Buscar...',
                        'allowClear' => true,
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]),
                'value'=>function($model){
                    $ret = '';

                    if($model->status == 5){
                        $ret =  '<span class="badge bgcolor6 text-light font11"><span class="color11 mx-2" style=""><i class="bi bi-x"></i></span>Baja</span>';
                    }else if( $model->status == 1){
                        $ret =  '<span class="badge bgcolor3 text-light font11"><span class="color7 mx-2" style=""><i class="bi bi-check"></i></span>Activo</span>';
                    } else if( $model->status == 3){
                        $ret =  '<span class="badge bgcolor12 text-light font11"><span class="color7 mx-2" style=""><i class="bi bi-check"></i></span>NI</span>';
                    }
                   
                    return $ret;
                },
            ],
            [
                'attribute' =>'fecha_nacimiento',
                'visible'=>false,
                'label'=>'F. Nacimiento',
                'contentOptions' => ['class' => "y_centercenter",'style'=>''],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'filter' => \kartik\daterange\DateRangePicker::widget(
                    [
                        'model' => $searchModel, 
                        'attribute' => 'fecha_nacimiento', 
                        'convertFormat' => true,
                        'presetDropdown' => true, 
                        'hideInput' => true, 
                        'pluginOptions' => [
                            'timePicker' => true,
                            'timePickerIncrement' => 1,
                            'timePicker24Hour' => true,
                            'locale' => [
                                'format' => ('Y-m-d'),
                            ],
                        ]
                        ]),
            ],
            [
                'attribute' =>'edad',
                'contentOptions' => ['class' => "y_centercenter",'style'=>''],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'value'=>function($model){
                    $ret = '';

                    if(isset($model->edad) && $model->edad != ''){
                        $ret = $model->edad;
                    }
                    return $ret;
                },
            ],
            [
                'attribute' =>'sexo',
                'label'=>'Sexo',
                'format'=>'raw',
                'contentOptions' => ['class' => "y_centercenter text-center",'style'=>''],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'filter'=>Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'sexo',
                    'data' => ['1'=>'M','2'=>'F','3'=>'O'],
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'hideSearch' => false,
                    'options' => [
                        'placeholder' => 'Buscar...',
                        'allowClear' => true,
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]),
                'value'=>function($model){
                   $ret = '';
                   if($model->sexo == 1){
                       $ret = '<span class="mx-1 color1"><i class="bi bi-gender-male"></i></span>M';
                   } else if($model->sexo == 2){
                       $ret = '<span class="mx-1 color8"><i class="bi bi-gender-female"></i></span>F';
                   } else if($model->sexo == 3){
                    $ret = '<span class="mx-1 color4"><i class="bi bi-gender-ambiguous"></i></span>Otro';
                }
                   return $ret;
                 },
            ],
            [
                'attribute' =>'fecha_contratacion',
                'label'=>'F. Contrato',
                'format'=>'raw',
                'contentOptions' => ['class' => "y_centercenter color6",'style'=>''],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'filter' => \kartik\daterange\DateRangePicker::widget(
                    [
                        'model' => $searchModel, 
                        'attribute' => 'fecha_contratacion', 
                        'convertFormat' => true,
                        'presetDropdown' => true, 
                        'hideInput' => true, 
                        'pluginOptions' => [
                            'timePicker' => true,
                            'timePickerIncrement' => 1,
                            'timePicker24Hour' => true,
                            'locale' => [
                                'format' => ('Y-m-d'),
                            ],
                        ]
                ]),
                'value'=>function($model){
                    $ret = '';
                    if(isset($model->fecha_contratacion) && $model->fecha_contratacion != ''){
                        $ret = $model->fecha_contratacion;
                    }

                    if(isset($model->fecha_baja) && $model->fecha_baja != ''){
                        $ret .= '<br><span class="small color11 border-top">'.$model->fecha_baja.'</span>';
                    }
                    return $ret;
                },
            ],
            [
                'attribute' =>'fecha_baja',
                'label'=>'F. Venc. Contrato',
                'visible'=>false,
                'contentOptions' => ['class' => "y_centercenter color6",'style'=>''],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'filter' => \kartik\daterange\DateRangePicker::widget(
                    [
                        'model' => $searchModel, 
                        'attribute' => 'fecha_baja', 
                        'convertFormat' => true,
                        'presetDropdown' => true, 
                        'hideInput' => true, 
                        'pluginOptions' => [
                            'timePicker' => true,
                            'timePickerIncrement' => 1,
                            'timePicker24Hour' => true,
                            'locale' => [
                                'format' => ('Y-m-d'),
                            ],
                        ]
                        ]),
                'value'=>function($model){
                    $ret = '';
                    if(isset($model->fecha_baja) && $model->fecha_baja != ''){
                        $ret = $model->fecha_baja;
                    }
                    return $ret;
                  },
            ],
            /* [
                'attribute' =>'antiguedad',
                'label'=>'Antigüedad',
                'contentOptions' => ['class' => "y_centercenter borderblue text-center",'style'=>''],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'value'=>function($model){
                    return $model->antiguedad;
                 },
                 'filter'=>Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'antiguedad',
                    'data' =>$antiguedades,
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'hideSearch' => false,
                    'options' => [
                        'placeholder' => 'Buscar...',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]),
            ], */
            [
                'attribute' =>'antiguedad',
                'label'=>'Antigüedad',
                'contentOptions' => ['class' => "y_centercenter borderblue text-center",'style'=>''],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'value'=>function($model){
                    return $model->antiguedad;
                 },
                 'filter'=>Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'antiguedad',
                    'data' =>$antiguedades,
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'hideSearch' => false,
                    'options' => [
                        'placeholder' => 'Buscar...',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]),
            ],
           /*  [
                'attribute' =>'dato_extra1',
                'contentOptions' => ['class' => "y_centercenter ",'style'=>''],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'filter'=>Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'dato_extra1',
                    'data' =>$ubicaciones,
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'hideSearch' => false,
                    'options' => [
                        'placeholder' => 'Buscar...',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]),
                'value'=>function($model){
                    $ret = '';
                    if($model->ubicacion){
                        $ret = $model->ubicacion->ubicacion;
                    }
                    return $ret;
                 },
            ],
             [
                'attribute' =>'dato_extra2',
                'contentOptions' => ['class' => "y_centercenter ",'style'=>''],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'filter'=>Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'dato_extra2',
                    'data' =>$paises,
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'hideSearch' => false,
                    'options' => [
                        'placeholder' => 'Buscar...',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]),
                'value'=>function($model){
                    $ret = '';
                    if($model->datapais){
                        $ret = $model->datapais->pais;
                    }
                    return $ret;
                 },
            ], */
           
            [
                'attribute' =>'id_pais',
                'contentOptions' => ['class' => "",'style'=>'width:10%'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'format'=>'raw',
                'visible'=>false,
                'value'=>function($model){
                    $ret = '';
                    if($model->infopais){
                        $ret .='<span class="badge rounded-pill font10 btnnew2 ">'.$model->infopais->pais.'</span>';;
                    }
                    return $ret;
                  },
            ],
            [
                'attribute' =>'id_linea',
                'contentOptions' => ['class' => "",'style'=>'width:10%'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'visible'=>false,
                'format'=>'raw',
                'value'=>function($model){
                    $ret = '';
                    if($model->linea){
                        $ret .='<span class="badge rounded-pill font10 bgcolor9 ">'.$model->linea->linea.'</span>';
                    }
                    return $ret;
                  },
            ],
            [
                'attribute' =>'id_ubicacion',
                'contentOptions' => ['class' => "",'style'=>'width:10%'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'format'=>'raw',
                'visible'=>false,
                'value'=>function($model){
                    $ret = '';
                    if($model->dataubicacion){
                        $ret .='<span class="badge rounded-pill font10 bg-light text-dark">'.$model->dataubicacion->ubicacion.'</span>';
                    }
                    return $ret;
                  },
            ],
            [
                'attribute' =>'id_area',
                'label'=>'Área',
                'visible'=>false,
                'contentOptions' => ['class' => "y_centercenter color3",'style'=>''],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'filter'=>Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'id_area',
                    'data' =>$areas,
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'hideSearch' => false,
                    'options' => [
                        'placeholder' => 'Buscar...',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]),
                'value'=>function($model){
                    $ret = '';
                    if(isset($model->area)){
                        $ret = $model->area->area;
                    }
                    return $ret;
                 },
            ],
            [
                'attribute' =>'puesto_contable',
                'contentOptions' => ['class' => "y_centercenter",'style'=>''],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'format'=>'raw',
                'value'=>function($model){
                    $ret = '';
                    if(isset($model->puesto_contable)){
                        $ret = '<span class="badge bgtransparent2 text-dark font12 m-1">'.$model->puesto_contable.'</span>';
                    }
                    return $ret;
                 },
            ],
            [
                'attribute' =>'id_puesto',
                'contentOptions' => ['class' => "y_centercenter",'style'=>''],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'filter'=>Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'id_puesto',
                    'data' =>$puestos,
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
                    if(isset($model->puesto)){
                        $ret = '<span class="badge bgtransparent1 text-dark font12 m-1">'.$model->puesto->nombre.'</span>';
                    }
                    return $ret;
                 },
            ],
            
            [
                'attribute' =>'riesgos',
                'contentOptions' => ['class' => "y_centercenter color6 font600",'style'=>'width:12%'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'headerOptions' => ['class' => "y_centercenter",'style'=>'color: #716F81;
                text-decoration:none;
                font-weight: 500;
                font-size: 11px;'],
                'filter'=>Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'riesgos',
                    'data' =>$riesgos,
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'hideSearch' => true,
                    'options' => [
                        'placeholder' => 'Buscar...',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'multiple' => true
                    ],
                ]),
                'format'=>'raw',
                'value'=>function($model){
                    $ret = '';

                    if(isset($model->puesto)){
                        if($model->puesto->riesgos){
                            foreach($model->puesto->riesgos as $key=>$riesgo){
                                $ret.= '<span class="badge rounded-pill bgcolor8 text-light font10">'.$riesgo->riesgo.'</span>';
                            }
                        }
                    }
                    
                    return $ret;
                  },
            ],
            
            [
                'attribute' =>'status_documentos',
                'label'=>'Expediente',
                'contentOptions' => ['class' => "y_centercenter",'style'=>''],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'filter'=>Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'status_documentos',
                    'data' =>['1'=>'CUMPLE','2'=>'NO CUMPLE','0'=>'PENDIENTE'],
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'hideSearch' => false,
                    
                    'options' => [
                        'placeholder' => 'Buscar...',
                        'allowClear' => true,
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]),
                'format'=>'raw',
                'value'=>function($model){
                    $ret = '';

                    if($model->status_documentos == 0){
                        $ret =  '<span class="badge bgpendiente m-1 font14 text-light text-uppercase">PENDIENTE</span>';
                    }else if( $model->status_documentos == 1){
                        $ret =  '<span class="badge bgcumple m-1 font14 text-light text-uppercase">CUMPLE</span>';
                    } else if( $model->status_documentos == 2){
                        $ret =  '<span class="badge bgnocumple m-1 font14 text-light text-uppercase">NO CUMPLE</span>';
                    }
                   
                    return $ret;
                  },
            ],
            [
                'attribute' =>'estudios_pendientes',
                'label'=>'Estudios Médicos',
                'contentOptions' => ['class' => "y_centercenter",'style'=>''],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'headerOptions' => ['class' => "y_centercenter",'style'=>'color: #716F81;
                text-decoration:none;
                font-weight: 500;
                font-size: 11px;'],
                'filter'=>Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'estudios_pendientes',
                    'data' =>['2'=>'COMPLETO','1'=>'FALTA ENTREGA DE RESULTADOS','3'=>'FALTA REALIZAR'],
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'hideSearch' => false,
                    
                    'options' => [
                        'placeholder' => 'Buscar...',
                        'allowClear' => true,
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]),
                'format'=>'raw',
                'value'=>function($model){
                    $ret = '';

                    if($model->estudios_pendientes == 1){
                        $ret = Html::a('<span class="font10 color16 mx-2"><i class="bi bi-circle-fill"></i></span>FALTA ENTREGA DE RESULTADOS', Url::to(['poes/index','id_trabajador' => $model->id]), [
                                            'title' => Yii::t('app', 'Ver Exámenes Médicos'),
                                            'data-toggle'=>"tooltip",
                                            'data-placement'=>"top",
                                            'class'=>'btn btn-sm text-center shadow-sm font11',
                                            'target'=>'_blank'
                                ]);
                    } if($model->estudios_pendientes == 2){
                        $ret = Html::a('<span class="font10 color7 mx-2"><i class="bi bi-circle-fill"></i></span>COMPLETO', Url::to(['poes/index','id_trabajador' => $model->id]), [
                                            'title' => Yii::t('app', 'Ver Exámenes Médicos'),
                                            'data-toggle'=>"tooltip",
                                            'data-placement'=>"top",
                                            'class'=>'btn btn-sm text-center shadow-sm font11',
                                            'target'=>'_blank'
                                ]);
                    } else if ($model->estudios_pendientes == 3) {
                        $ret = Html::a('<span class="font10 color11 mx-2"><i class="bi bi-circle-fill"></i></span>FALTA REALIZAR', Url::to(['poes/index','id_trabajador' => $model->id]), [
                                            'title' => Yii::t('app', 'Ver Exámenes Médicos'),
                                            'data-toggle'=>"tooltip",
                                            'data-placement'=>"top",
                                            'class'=>'btn btn-sm text-center shadow-sm font11',
                                            'target'=>'_blank'
                                ]);
                    }
                   
                    return $ret;
                  },
            ],
            [
                'attribute' =>'id_link',
                'label'=>'QR',
                'visible'=>$ver_qr,
                'contentOptions' => ['class' => "y_centercenter font500 font-11",'style'=>'width:10%'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'headerOptions' => ['class' => "y_centercenter text-center",'style'=>'color: #716F81;
                text-decoration:none;
                font-weight: 500;
                font-size: 11px;'],
                'format'=>'raw',
                'value'=>function($model){
                    $ret = '';

                    if($model->id_link){
                       
                        $iconqr = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-qr-code" viewBox="0 0 16 16">
                        <path d="M2 2h2v2H2z"/>
                        <path d="M6 0v6H0V0zM5 1H1v4h4zM4 12H2v2h2z"/>
                        <path d="M6 10v6H0v-6zm-5 1v4h4v-4zm11-9h2v2h-2z"/>
                        <path d="M10 0v6h6V0zm5 1v4h-4V1zM8 1V0h1v2H8v2H7V1zm0 5V4h1v2zM6 8V7h1V6h1v2h1V7h5v1h-4v1H7V8zm0 0v1H2V8H1v1H0V7h3v1zm10 1h-1V7h1zm-1 0h-1v2h2v-1h-1zm-4 0h2v1h-1v1h-1zm2 3v-1h-1v1h-1v1H9v1h3v-2zm0 0h3v1h-2v1h-1zm-4-1v1h1v-2H7v1z"/>
                        <path d="M7 12h1v3h4v1H7zm9 2v2h-3v-1h2v-1z"/>
                        </svg>';

                         $filePath =  '/web'.'/qrs/'.$model->id.'/'.$model->id.'qr.png';
                        /* $filePath =  '/qrs/'.$model->id.'/'.$model->id.'qr.png'; */

                        $imagen = 'background: url(..'.$filePath.');background-size:cover;';
                        $retimagen = "<label class='mini-cube p-2 puntero' style='".$imagen."'></label>";

                        $botonlink = '<button type="button" class="btn btn-sm text-center shadow-sm font10" onclick="verQr('.$model->id.')">'.$retimagen .'</button>';
                        $ret .= '<div class="text-center">'.$botonlink.'</div>';

                        //$ret = $model->id_link;
                    } else{
                        $imagen = '';
                        $ret = "<label class='big-cube p-2 puntero'>".$imagen."</label>";
                    }

                    return $ret;
                },
            ],
            [
                'attribute' =>'cal',
                'label'=>'CAL',
                'contentOptions' => ['class' => "y_centercenter",'style'=>''],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'headerOptions' => ['class' => "y_centercenter",'style'=>'color: #716F81;
                text-decoration:none;
                font-weight: 500;
                font-size: 11px;'],
                'format'=>'raw',
                'value'=>function($model){
                    $ret = '';
                    if($model->historiaclinica && $model->historiaclinica->status == 2){
                        $image = '<span class="color10" style="font-size:25px"><i class="bi bi-file-pdf-fill"></i></span>';
                        $ret = Html::a($image, Url::to(['hccohc/pdfcal','id' => $model->historiaclinica->id,'firmado'=>1]), $options = ['target'=>'_blank']);
                    }
                    return $ret;
                 },
            ],
            [
                'attribute' =>'prog_salud',
                'label'=>'Prog. Salud',
                'format'=>'raw',
                'contentOptions' => ['class' => "y_centercenter",'style'=>''],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'headerOptions' => ['class' => "y_centercenter",'style'=>'color: #716F81;
                text-decoration:none;
                font-weight: 500;
                font-size: 11px;'],
                'value'=>function($model){
                    $ret = '';
                    if($model->programas){
                        foreach($model->programas as $key=>$program){
                            if($program->programa){
                                $ret .= '<span class="badge rounded-pill bg-light color6 font10">'.$program->programa->nombre.'</span>';
                            } 
                        }
                    }
                    return $ret;
                 },
            ],
            
            
            [
                'attribute' =>'tipo_registro',
                'label'=>'Registro',
                'format'=>'raw',
                'contentOptions' => ['class' => "y_centercenter text-center",'style'=>''],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'filter'=>Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'tipo_registro',
                    'data' =>['1'=>'INDIVIDUAL','2'=>'CARGA MASIVA'],
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'hideSearch' => false,
                    
                    'options' => [
                        'placeholder' => 'Buscar...',
                        'allowClear' => true,
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]),
                'value'=>function($model){
                    $ret = '';
                    if($model->tipo_registro != 2){
                        $ret .= '<span class="color3 font600">INDIVIDUAL</span>';

                        if($model->uCaptura){
                            $ret .= '<br><span class="border-bottom font10">'.$model->uCaptura->name.'</span>';
                        }
                    } else if($model->tipo_registro == 2){
                        $ret .= '<span class="color14 font600">CARGA MASIVA</span>';
                    }
                    return $ret;
                 },
            ],
            [
                'attribute' =>'create_date',
                'label'=>'Fecha Registro',
                'contentOptions' => ['class' => "y_centercenter color6",'style'=>''],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'filter' => \kartik\daterange\DateRangePicker::widget(
                    [
                        'model' => $searchModel, 
                        'attribute' => 'create_date', 
                        'convertFormat' => true,
                        'presetDropdown' => true, 
                        'hideInput' => true, 
                        'pluginOptions' => [
                            'timePicker' => true,
                            'timePickerIncrement' => 1,
                            'timePicker24Hour' => true,
                            'locale' => [
                                'format' => ('Y-m-d'),
                            ],
                        ]
                        ]),
                'value'=>function($model){
                    $ret = '';
                    if(isset($model->create_date) && $model->create_date != ''){
                        $ret = $model->create_date;
                    }
                    return $ret;
                  },
            ],
            //'foto',
            //'sexo',
            //'estado_civil',
            //'fecha_nacimiento',
            //'edad',
            //'nivel_lectura',
            //'nivel_escritura',
            //'escolaridad',
            //'grupo',
            //'rh',
            //'num_imss',
            //'celular',
            //'contacto_emergencia',
            //'direccion',
            //'colonia',
            //'pais',
            //'estado',
            //'ciudad',
            //'municipio',
            //'cp',
            //'num_trabajador',
            //'tipo_contratacion',
            //'fecha_contratacion',
            //'fecha_baja',
            //'antiguedad',
            //'ruta',
            //'parada',
            //'create_date',
            //'create_user',
            //'update_date',
            //'update_user',
            //'delete_date',
            //'delete_user',
            //'status',
            [
                'attribute' =>'consentimiento',
                'label'=>'Consentimiento',
                'contentOptions' => ['class' => "y_centercenter text-center"],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'headerOptions' => ['class' => "y_centercenter",'style'=>'color: #716F81;
                text-decoration:none;
                font-weight: 500;
                font-size: 11px;'],
                'format'=>'raw',
                'visible'=>false,
                'filter'=>Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'consentimiento',
                    'data' =>['1'=>'Con Consentimiento','2'=>'Sin Consentimiento'],
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'hideSearch' => true,
                    'options' => [
                        'placeholder' => 'Buscar...',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'multiple' => false
                    ],
                ]),
                'value'=>function($model){
                    $ret = '';
                   
                    if($model->uso_consentimiento != null && $model->uso_consentimiento != '' && $model->uso_consentimiento != ' ' && $model->retirar_consentimiento != null && $model->retirar_consentimiento != '' && $model->retirar_consentimiento != ' ' && $model->acuerdo_confidencialidad != null && $model->acuerdo_confidencialidad != '' && $model->acuerdo_confidencialidad != ' '){
                        $ret .= '<div class="border-bottom color3 font11">CON CONSENTIMIENTO</div>';
                        $image = '<span class="color1" style="font-size:25px"><i class="bi bi-file-earmark-text-fill"></i></span>';
                        $ret .= Html::a($image, Url::to(['trabajadores/consentimiento','id' => $model->id,'firmado'=>1]), $options = ['target'=>'_blank','title' => Yii::t('app', 'Con Firma'),'data-toggle'=>"tooltip",'data-placement'=>"top"]);
                    } else {
                        $ret .= '<div class="border-bottom color11 font11">SIN CONSENTIMIENTO</div>';
                    }
                    
                    return $ret;
                },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => $template.'{medidas}',
                'header'=>"Accion",
                'headerOptions' => ['class' => "text-center", 'style'=>'vertical-align: top;'],
                'headerOptions' => ['class' => "y_centercenter",'style'=>'color: #716F81;
                text-decoration:none;
                font-weight: 500;
                font-size: 11px;'],
                'buttons' => [
                    'doc' =>  function($url,$model) {
                        return Html::a('<span class=""><i class="bi bi-paperclip"></i><span>', Url::to(['trabajadores/folder','id' => $model->id]), [
                            'title' => Yii::t('app', 'Documentos'),
                            'data-toggle'=>"tooltip",
                            'data-placement'=>"top",
                            'class'=>'btn btn-sm text-center shadow-sm'
                        ]);
                    },
                    'update' =>  function($url,$model) {
                        return Html::a('<span class=""><i class="bi bi-pen-fill"></i><span>', Url::to(['trabajadores/update','id' => $model->id]), [
                            'title' => Yii::t('app', 'Actualizar'),
                            'data-toggle'=>"tooltip",
                            'data-placement'=>"top",
                            'class'=>'btn btn-sm text-center shadow-sm'
                        ]);
                    },
                    'medidas' =>  function($url,$model) use ($icon) {
                        return '';
                    },
                    'delete' =>  function($url,$model) use ($dataProvider,$searchModel) {
                        $iconcancel = '<i class="bi bi-trash"></i>';

                        $page = $dataProvider->pagination->page;
                        $company = $searchModel->id_empresa;

                        return  Html::a('<span class="color11">'.$iconcancel.'<span>', ['delete', 'id' => $model->id,'company'=>$company,'page'=>$page], [
                            'data' => [
                                'confirm' => Yii::t('app', '¿Seguro que desea eliminar Trabajador de '.$model->apellidos.' '.$model->nombre.'?'),
                                'method' => 'post',
                            ],
                            'title' => Yii::t('app', 'Eliminar'),
                            'data-bs-toggle'=>"tooltip",
                            'data-bs-placement'=>"top",
                            'class'=>'btn btn-sm text-center shadow-sm'
                        ]);
                    },
                ],
            ],
            [
                'attribute' =>'epps',
                'label'=>'EPP',
                'contentOptions' => ['class' => "y_centercenter color6 font600",'style'=>'width:12%'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'headerOptions' => ['class' => "y_centercenter",'style'=>'color: #716F81;
                text-decoration:none;
                font-weight: 500;
                font-size: 11px;'],
                'filter'=>Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'epps',
                    'data' =>$epps,
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'hideSearch' => true,
                    'options' => [
                        'placeholder' => 'Buscar...',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'multiple' => true
                    ],
                ]),
                'format'=>'raw',
                'value'=>function($model){
                    $ret = '';

                    if(isset($model->puesto)){
                        if($model->puesto->epps){
                            foreach($model->puesto->epps as $key=>$epp){
                                $ret.= '<span class="badge rounded-pill bgcolor14 text-dark font10">'.$epp->epp.'</span>';
                            }

                            if(Yii::$app->user->can('trabajadores_actualizar')){
                                $ret.= Html::a('<span class=""><i class="bi bi-pen-fill"></i><span>', Url::to(['trabajadores/epp','id' => $model->id]), [
                                    'title' => Yii::t('app', 'Equipo de Protección'),
                                    'data-toggle'=>"tooltip",
                                    'data-placement'=>"top",
                                    'class'=>'btn btn-sm text-center shadow-sm'
                                ]);
                            }
                            
                        }
                    }


                    
                    return $ret;
                  },
            ],
        ],
    ]); ?>


</div>