<?php

use app\models\Cuestionario;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use app\models\Empresas;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use kartik\export\ExportMenu;


//Traer los campos del cuestionario nordico
use app\models\Preguntas;
use app\models\Areascuestionario;
use app\models\DetalleCuestionario;


use app\models\Ubicaciones;
use app\models\Paises;
use app\models\Paisempresa;

use app\models\NivelOrganizacional1;
use app\models\NivelOrganizacional2;
use app\models\NivelOrganizacional3;
use app\models\NivelOrganizacional4;

/** @var yii\web\View $this */
/** @var app\models\CuestionarioSearch $searchModel */
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

if($tipo == 1){
    $this->title = Yii::t('app', 'Cuestionario Nórdico'.$name_empresa);
} else {
    $this->title = Yii::t('app', 'Evaluación Antropométrica'.$name_empresa);
}

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
        
if(Yii::$app->user->identity->empresa_all != 1) {
    $empresas = ArrayHelper::map(Empresas::find()->where(['in','id',$empresas])->orderBy('comercial')->all(), 'id', 'comercial');
} else{
    $empresas = ArrayHelper::map(Empresas::find()->orderBy('comercial')->all(), 'id', 'comercial');
}
$tipos = ['1'=>'CUESTIONARIO NÓRDICO','4'=>'EVALUACIÓN ANTROPOMÉTRICA'];
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
    $template = '';
    if($tipo == 1 && Yii::$app->user->can('nordicos_ver')){
        $template .='{view}';
    } else if ($tipo == 4 && Yii::$app->user->can('antropometricos_ver')){
        $template .='{view}';
    }
?>

<div class="cuestionario-index">

    <h1 class="title1"><?= Html::encode($this->title) ?></h1>


    <?php
    $columnas = [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'attribute' =>'id_paciente',
            'label'=>'Nombre empleado',
            'value'=>function($model){
                $ret = '';
                if($model->trabajadorsmo){
                    $ret = $model->trabajadorsmo->apellidos.' '.$model->trabajadorsmo->nombre;
                }
                return $ret;
              },
        ],
        [
            'attribute' =>'num_trabajador',
            'value'=>function($model){
                $ret = '';
                if($model->num_trabajador){
                    $ret = $model->num_trabajador;
                }
                return $ret;
              },
        ],
        [
            'attribute' =>'fecha_nacimiento',
            'label'=>'Fecha de nacimiento',
            'value'=>function($model){
                $ret = '';
                if($model->trabajadorsmo){
                    if(isset($model->trabajadorsmo->fecha_nacimiento) && $model->trabajadorsmo->fecha_nacimiento != ''){
                        $ret = $model->trabajadorsmo->fecha_nacimiento;
                    }
                }
                
                return $ret;
            },
        ],
        [
            'attribute' =>'edad',
            'value'=>function($model){
                $ret = '';
                if($model->trabajadorsmo){
                    if(isset($model->trabajadorsmo->edad) && $model->trabajadorsmo->edad != ''){
                        $ret = $model->trabajadorsmo->edad;
                    }
                }
                
                return $ret;
            },
        ],
        [
            'attribute' =>'sexo',
            'label'=>'Sexo',
            'value'=>function($model){
               $ret = '';
    
               if($model->trabajadorsmo){
                   if($model->trabajadorsmo->sexo == 1){
                       $ret = '<span class="mx-1 color1"><i class="bi bi-gender-male"></i></span>M';
                    } else if($model->trabajadorsmo->sexo == 2){
                        $ret = '<span class="mx-1 color8"><i class="bi bi-gender-female"></i></span>F';
                    } else if($model->trabajadorsmo->sexo == 3){
                        $ret = '<span class="mx-1 color4"><i class="bi bi-gender-ambiguous"></i></span>Otro';
                    }
               }
               
               return $ret;
             },
        ],
        [
            'attribute' =>'id_area',
            'label'=>'Area',
            'value'=>function($model){
                $ret = '';
                if($model->trabajadorsmo && $model->trabajadorsmo->area){
                    $ret =$model->trabajadorsmo->area->area;
                }
                return $ret;
              },
        ],
        [
            'attribute' =>'id_puesto',
            'label'=>'Puesto',
            'value'=>function($model){
                $ret = '';
                if($model->trabajadorsmo && $model->trabajadorsmo->puesto){
                    $ret =$model->trabajadorsmo->puesto->nombre;
                }
                return $ret;
              },
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
            'attribute' =>'id_tipo_cuestionario',
            'label'=>'Tipo Cuestionario',
            'value'=>function($model) use ($tipos){
                $ret = '';
                if(isset($model->id_tipo_cuestionario)){
                    $ret = $tipos[$model->id_tipo_cuestionario];
                }
                return $ret;
             },
        ],
        [
            'attribute' =>'fecha_cuestionario',
            'label'=>'F. Evaluación',
        ],
        [
            'attribute' =>'id_medico',
            'label'=>'Medico',
            'value'=>function($model) use ($tipos){
                $ret = '';
                if($model->medico){
                    $ret = $model->medico->name;
                }
                return $ret;
             }
    ]] ;


    $columnasantropo = [
            [
            'attribute' =>'atributo1',
            'label'=>'Peso (kg)',
            'value'=>function($model){
                $ret = '';
                if(isset($model->ageneral1)){
                    $ret = $model->ageneral1->respuesta_1;
                }
                return $ret;
             }
            ], [
                'attribute' =>'atributo2',
                'label'=>'Altura (cm)',
                'value'=>function($model){
                    $ret = '';
                    if(isset($model->ageneral2)){
                        $ret = $model->ageneral2->respuesta_1;
                    }
                    return $ret;
                 }
            ],
            [
                'attribute' =>'atributo3',
                'label'=>'imc',
                'value'=>function($model){
                    $ret = '';
                    if(isset($model->ageneral3)){
                        $ret = $model->ageneral3->respuesta_1;
                    }
                    return $ret;
                 }
            ],
            [
                'attribute' =>'atributo4',
                'label'=>'Comentarios',
                'value'=>function($model){
                    $ret = '';
                    if(isset($model->ageneral4)){
                        $ret = $model->ageneral4->respuesta_1;
                    }
                    return $ret;
                 }
            ],
            [
                'attribute' =>'atributo5',
                'label'=>'Altura ojos - Postura parado',
                'value'=>function($model){
                    $ret = '';
                    if(isset($model->pp1)){
                        $ret = $model->pp1->respuesta_1;
                    }
                    return $ret;
                 }
            ],
            [
                'attribute' =>'atributo6',
                'label'=>'Altura hombro - Postura parado',
                'value'=>function($model){
                    $ret = '';
                    if(isset($model->pp2)){
                        $ret = $model->pp2->respuesta_1;
                    }
                    return $ret;
                 }
            ],
            [
                'attribute' =>'atributo7',
                'label'=>'Altura codo flexionado - Postura parado',
                'value'=>function($model){
                    $ret = '';
                    if(isset($model->pp3)){
                        $ret = $model->pp3->respuesta_1;
                    }
                    return $ret;
                 }
            ],
            [
                'attribute' =>'atributo8',
                'label'=>'Altura nudillos (Prensión) - Postura parado',
                'value'=>function($model){
                    $ret = '';
                    if(isset($model->pp4)){
                        $ret = $model->pp4->respuesta_1;
                    }
                    return $ret;
                 }
            ],
            [
                'attribute' =>'atributo9',
                'label'=>'Altura troncánter - Postura parado',
                'value'=>function($model){
                    $ret = '';
                    if(isset($model->pp5)){
                        $ret = $model->pp5->respuesta_1;
                    }
                    return $ret;
                 }
            ],
            [
                'attribute' =>'atributo10',
                'label'=>'Altura rodilla - Postura parado',
                'value'=>function($model){
                    $ret = '';
                    if(isset($model->pp6)){
                        $ret = $model->pp6->respuesta_1;
                    }
                    return $ret;
                 }
            ],
            [
                'attribute' =>'atributo11',
                'label'=>'Alcance lateral - Postura parado',
                'value'=>function($model){
                    $ret = '';
                    if(isset($model->pp7)){
                        $ret = $model->pp7->respuesta_1;
                    }
                    return $ret;
                 }
            ],
            [
                'attribute' =>'atributo12',
                'label'=>'Alcance máximo vertical - Postura parado',
                'value'=>function($model){
                    $ret = '';
                    if(isset($model->pp8)){
                        $ret = $model->pp8->respuesta_1;
                    }
                    return $ret;
                 }
            ],
            [
                'attribute' =>'atributo13',
                'label'=>'Alcance máximo horizontal - Postura parado',
                'value'=>function($model){
                    $ret = '';
                    if(isset($model->pp9)){
                        $ret = $model->pp9->respuesta_1;
                    }
                    return $ret;
                 }
            ],
            [
                'attribute' =>'atributo14',
                'label'=>'Anchura bideltoidea - Postura parado',
                'value'=>function($model){
                    $ret = '';
                    if(isset($model->pp10)){
                        $ret = $model->pp10->respuesta_1;
                    }
                    return $ret;
                 }
            ],
            [
                'attribute' =>'atributo15',
                'label'=>'Altura - Postura sentado',
                'value'=>function($model){
                    $ret = '';
                    if(isset($model->ps1)){
                        $ret = $model->ps1->respuesta_1;
                    }
                    return $ret;
                 }
            ],
            [
                'attribute' =>'atributo16',
                'label'=>'Altura ojos - Postura sentado',
                'value'=>function($model){
                    $ret = '';
                    if(isset($model->ps2)){
                        $ret = $model->ps2->respuesta_1;
                    }
                    return $ret;
                 }
            ],
            [
                'attribute' =>'atributo17',
                'label'=>'Altura del codo sentado - Postura sentado',
                'value'=>function($model){
                    $ret = '';
                    if(isset($model->ps3)){
                        $ret = $model->ps3->respuesta_1;
                    }
                    return $ret;
                 }
            ],
            [
                'attribute' =>'atributo18',
                'label'=>'Altura cresta iliaca sentado - Postura sentado',
                'value'=>function($model){
                    $ret = '';
                    if(isset($model->ps5)){
                        $ret = $model->ps5->respuesta_1;
                    }
                    return $ret;
                 }
            ],
            [
                'attribute' =>'atributo19',
                'label'=>'Altura pierna sentado - Postura sentado',
                'value'=>function($model){
                    $ret = '';
                    if(isset($model->ps6)){
                        $ret = $model->ps6->respuesta_1;
                    }
                    return $ret;
                 }
            ],
            [
                'attribute' =>'atributo20',
                'label'=>'Anchura codo-codo - Postura sentado',
                'value'=>function($model){
                    $ret = '';
                    if(isset($model->ps7)){
                        $ret = $model->ps7->respuesta_1;
                    }
                    return $ret;
                 }
            ],
            [
                'attribute' =>'atributo21',
                'label'=>'Longitud nalga rodilla sentado - Postura sentado',
                'value'=>function($model){
                    $ret = '';
                    if(isset($model->ps8)){
                        $ret = $model->ps8->respuesta_1;
                    }
                    return $ret;
                 }
            ]
    ];

    //dd($columnasantropo);


    $columnasnordico = [];

    $preguntas = Preguntas::find()->where(['id_tipo_cuestionario'=>1])->all();
    $areas = Areascuestionario::find()->where(['id_tipo_cuestionario'=>1])->all();
    //dd($preguntas);
    //dd($areas);

    foreach($preguntas as $key=>$pregunta){
        if($pregunta->id != 3 && $pregunta->id != 4 && $pregunta->id != 8 && $pregunta->id != 9){
            foreach($areas as $keyy=>$area){
                if($area->id != 12){
                    $columna = [
                        'attribute' =>'atributo'.$key.'_'.$keyy,
                        'label'=>$pregunta->pregunta.' '.$area->nombre,
                        'value'=>function($model) use ($pregunta,$area){
                            $ret = '--';
                            $det = DetalleCuestionario::find()->where(['id_cuestionario'=>$model->id])->andWhere(['id_tipo_cuestionario'=>1])->andWhere(['id_pregunta'=>$pregunta->id])->andWhere(['id_area'=>$area->id])->one();
                            if(isset( $det)){
                                $ret =  $det->respuesta_1;
                            }
                            return $ret;
                         }
                        ];
                    array_push($columnasnordico, $columna);
                }
            }
        }
    }


    if($tipo == 4){
        //dd('hola soy el 4');
        $columnas = array_merge($columnas, $columnasantropo);
    } else {
        $columnas = array_merge($columnas, $columnasnordico);
    }

        //dd($columnas);
    ?>

    <?php
    $fullExportMenu = '';
    if(($tipo == 1 && Yii::$app->user->can('nordicos_exportar')) || $tipo == 4 && Yii::$app->user->can('antropometricos_exportar')){
        $fullExportMenu = ExportMenu::widget([
            'dataProvider' => $dataProvider,
            'columns' => $columnas,
            'hiddenColumns'=>[0],
            'clearBuffers' => true,
            'showConfirmAlert' =>false,
            'columnBatchToggleSettings'=>[
                'label'=>'Seleccionar Todo',
                'class' =>'px-2'
            ], 
            'filename'=> $this->title.'_reportdate_'.date('Y-m-d'),
            'columnSelectorOptions'=>[
                'label' => 'Columnas <img src="resources/images/excel.png" class="px-2" height="20px" width="auto"/>',
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
    } else{
        $fullExportMenu = '';
    }
    ?>

    <div class="row mb-3">
        <div class="col-lg-2">
            <?php 
            $base = 'https://www.cuestionarios.medicalfil.com';
            $link = '';
            $linkpdf = '';
    
            if($tipo == 1){
                $link = $base.'/index.php?r=cuestionario%2Fcreate&sw=2';
                $linkpdf = $base.'/index.php?r=cuestionario%2Fpdf&id_cuestionario=';
            } else {
                $link = $base.'/index.php?r=antropometrica%2Fcreate&sw=2';
                $linkpdf = $base.'/index.php?r=antropometrica%2Fpdf&id_cuestionario=';
            }

            //dd($tipo);
            ?>
            <?php if($tipo == 1):?>
            <?php if(Yii::$app->user->can('nordicos_crear')):?>
            <?= Html::a('<span class="mx-2 small y_centercenter"><i class="bi bi-plus-lg"></i></span>Nuevo(a) '.$this->title, ['create'], ['class' => 'btn btn-primary btnnew y_centercenter']) ?>
            <?php endif;?>
            <?php else:?>
            <?php if(Yii::$app->user->can('antropometricos_crear')):?>
            <?= Html::a('<span class="mx-2 small y_centercenter"><i class="bi bi-plus-lg"></i></span>Nuevo(a) '.$this->title, ['antropometrica/create'], ['class' => 'btn btn-primary btnnew y_centercenter']) ?>
            <?php endif;?>
            <?php endif;?>
        </div>
        <div class="col-lg-2 text-center">
            <?php
            if(Yii::$app->user->identity->hidden_actions == 1){
                echo Html::a('<span class=""><i class="bi bi-arrow-clockwise"></i><span>Refresh Nombre Empresas y Médicos', Url::to(['cuestionario/refreshnames']), [
                    'title' => Yii::t('app', 'Refresh Nombre Empresas y Médicos'),
                    'data-toggle'=>"tooltip",
                    'data-placement'=>"top",
                    'class'=>'btn btn-primary btnnew5 btn-block'
                ]);
            }
            ?>
        </div>
        <div class="col-lg-7">

            <?php if($tipo == 4){
                echo $this->render('_search', ['model' => $searchModel,'tipo'=>$tipo]);
            }?>

            <div class="col-lg-2 text-end">
                <?php
            echo $fullExportMenu;
            ?>
            </div>
        </div>


    </div>

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
                'attribute' =>'id_empresa',
                'visible' => $showempresa,
                'contentOptions' => ['class' => "y_centercenter color6 font600",'style'=>'width:20%'],
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
                'attribute' =>'id_paciente',
                'label'=>'Trabajador',
                'contentOptions' => ['class' => "y_centercenter color3 font600",'style'=>'width:25%'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'format'=>'raw',
                'value'=>function($model){
                    $ret = '';
                    if($model->trabajadorsmo){
                        $ret = $model->trabajadorsmo->apellidos.' '.$model->trabajadorsmo->nombre;
                    }
                    return $ret;
                  },
            ],
              [
                    'attribute' =>'id_nivel1',
                    'format'=>'raw',
                    'contentOptions' => ['class' => " color3 font600",'style'=>'width:12%'],
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
                    'contentOptions' => ['class' => " color3 font600",'style'=>'width:12%'],
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
                    'contentOptions' => ['class' => " color3 font600",'style'=>'width:12%'],
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
                    'contentOptions' => ['class' => " color3 font600",'style'=>'width:12%'],
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
                'attribute' =>'id_pais',
                'contentOptions' => ['class' => "",'style'=>'width:10%'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'format'=>'raw',
                'visible'=>false,
                'value'=>function($model){
                    $ret = '';
                    if($model->pais){
                        $ret .='<span class="badge rounded-pill font10 btnnew2 ">'.$model->pais->pais.'</span>';;
                    }
                    return $ret;
                  },
            ],
            [
                'attribute' =>'id_linea',
                'contentOptions' => ['class' => "",'style'=>'width:10%'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'format'=>'raw',
                'visible'=>false,
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
                    if($model->ubicacion){
                        $ret .='<span class="badge rounded-pill font10 bg-light text-dark">'.$model->ubicacion->ubicacion.'</span>';
                    }
                    return $ret;
                  },
            ],
            [
                'attribute' =>'num_trabajador',
                'contentOptions' => ['class' => "y_centercenter",'style'=>''],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'format'=>'raw',
                'value'=>function($model){
                    $ret = '';
                    if($model->num_trabajador){
                        $ret = $model->num_trabajador;
                    }
                    return $ret;
                  },
            ],
            [
                'attribute' =>'edad',
                'contentOptions' => ['class' => "y_centercenter",'style'=>''],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'value'=>function($model){
                    $ret = '';
                    if($model->trabajadorsmo){
                        if(isset($model->trabajadorsmo->edad) && $model->trabajadorsmo->edad != ''){
                            $ret = $model->trabajadorsmo->edad;
                        }
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

                   if($model->trabajadorsmo){
                       if($model->trabajadorsmo->sexo == 1){
                           $ret = '<span class="mx-1 color1"><i class="bi bi-gender-male"></i></span>M';
                        } else if($model->trabajadorsmo->sexo == 2){
                            $ret = '<span class="mx-1 color8"><i class="bi bi-gender-female"></i></span>F';
                        } else if($model->trabajadorsmo->sexo == 3){
                            $ret = '<span class="mx-1 color4"><i class="bi bi-gender-ambiguous"></i></span>Otro';
                        }
                   }
                   
                   return $ret;
                 },
            ],
            [
                'attribute' =>'id_tipo_cuestionario',
                'label'=>'Tipo Cuestionario',
                'contentOptions' => ['class' => "y_centercenter color3",'style'=>''],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'filter'=>Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'id_tipo_cuestionario',
                    'data' =>$tipos,
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'hideSearch' => true,
                    'options' => [
                        'placeholder' => 'Buscar...',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]),
                'value'=>function($model) use ($tipos){
                    $ret = '';
                    if(isset($model->id_tipo_cuestionario)){
                        $ret = $tipos[$model->id_tipo_cuestionario];
                    }
                    return $ret;
                 },
            ],
            [
                'attribute' =>'fecha_cuestionario',
                'contentOptions' => ['class' => "y_centercenter color6",'style'=>'width:10%;'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'filter' => \kartik\daterange\DateRangePicker::widget(
                    [
                        'model' => $searchModel, 
                        'attribute' => 'fecha_cuestionario', 
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
                'attribute' =>'consentimiento',
                'label'=>'Consentimiento',
                'contentOptions' => ['class' => "y_centercenter text-center"],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'headerOptions' => ['class' => "y_centercenter",'style'=>'color: #716F81;
                text-decoration:none;
                font-weight: 500;
                font-size: 11px;'],
                'format'=>'raw',
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
                'visible' =>true,
                'value'=>function($model){
                    $ret = '';
                   
                    if($model->uso_consentimiento != null && $model->uso_consentimiento != '' && $model->uso_consentimiento != ' ' && $model->retirar_consentimiento != null && $model->retirar_consentimiento != '' && $model->retirar_consentimiento != ' ' && $model->acuerdo_confidencialidad != null && $model->acuerdo_confidencialidad != '' && $model->acuerdo_confidencialidad != ' '){
                        $ret .= '<div class="border-bottom color3 font11">CON CONSENTIMIENTO</div>';
                        $image = '<span class="color1" style="font-size:25px"><i class="bi bi-file-earmark-text-fill"></i></span>';
                        $ret .= Html::a($image, Url::to(['consentimientopdf','id' => $model->id,'firmado'=>1]), $options = ['target'=>'_blank','title' => Yii::t('app', 'Con Firma'),'data-toggle'=>"tooltip",'data-placement'=>"top"]);
                    } else {
                        $ret .= '<div class="border-bottom color11 font11">SIN CONSENTIMIENTO</div>';
                    }
                    
                    return $ret;
                },
            ],
            [
                'attribute' =>'pdf',
                'label'=>'Documento',
                'contentOptions' => ['class' => "y_centercenter text-center"],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'headerOptions' => ['class' => "y_centercenter",'style'=>'color: #716F81;
                text-decoration:none;
                font-weight: 500;
                font-size: 11px;'],
                'format'=>'raw',
                'visible' =>true,
                'value'=>function($model){
                    $ret = '';
                   
                    $image = '<span class="color1" style="font-size:25px"><i class="bi bi-file-earmark-text-fill"></i></span>';
                    $image2 = '<span class="color2" style="font-size:25px"><i class="bi bi-file-earmark-text-fill"></i></span>';
                    
                    if($model->id_tipo_cuestionario == 1){
                        $ret = Html::a($image, Url::to(['cuestionario/pdf','id' => $model->id,'firmado'=>1]), $options = ['target'=>'_blank','title' => Yii::t('app', 'Con Firma'),'data-toggle'=>"tooltip",'data-placement'=>"top"]);
                        $ret .= Html::a($image2, Url::to(['cuestionario/pdf','id' => $model->id,'firmado'=>0]), $options = ['target'=>'_blank','title' => Yii::t('app', 'Sin Firma'),'data-toggle'=>"tooltip",'data-placement'=>"top"]);
                    } else{
                        $ret = Html::a($image, Url::to(['antropometrica/pdf','id' => $model->id,'firmado'=>1]), $options = ['target'=>'_blank','title' => Yii::t('app', 'Con Firma'),'data-toggle'=>"tooltip",'data-placement'=>"top"]);
                        $ret .= Html::a($image2, Url::to(['antropometrica/pdf','id' => $model->id,'firmado'=>0]), $options = ['target'=>'_blank','title' => Yii::t('app', 'Sin Firma'),'data-toggle'=>"tooltip",'data-placement'=>"top"]);
                    }
                    
                    return $ret;
                },
            ],
            //'id_paciente',
            //'id_medico',
            //'nombre_empresa',
            //'fecha_cuestionario',
            //'firma_paciente:ntext',
            //'es_local',
            //'status',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => $template,
                'header'=>"Accion",
                'headerOptions' => ['class' => "text-center", 'style'=>'vertical-align: top;'],
                'headerOptions' => ['class' => "y_centercenter",'style'=>'color: #716F81;
                text-decoration:none;
                font-weight: 500;
                font-size: 11px;'],
                'buttons' => [
                    'view' =>  function($url,$model) {
                        return Html::a('<span class="color2"><i class="bi bi-eye-fill"></i><span>', Url::to(['cuestionario/view','id' => $model->id]), [
                            'title' => Yii::t('app', 'Ver'),
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