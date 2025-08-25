<?php

use app\models\Consultas;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use kartik\export\ExportMenu;
use app\models\Empresas;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

use app\models\Ubicaciones;
use app\models\Paises;
use app\models\Paisempresa;

use app\models\NivelOrganizacional1;
use app\models\NivelOrganizacional2;
use app\models\NivelOrganizacional3;
use app\models\NivelOrganizacional4;

/** @var yii\web\View $this */
/** @var app\models\ConsultasSearch $searchModel */
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
$this->title = Yii::t('app', 'Consultas Médicas'.$name_empresa);
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
$tipoexamen = ['3'=>'CLÍNICA','1'=>'ACCIDENTE','7'=>'PROGRAMAS DE SALUD','4'=>'INCAPACIDAD','6'=>'TRABAJOS DE RIESGO','2'=>'ANTIDOPING','5'=>'PREOCUPANTE','8'=>'COVID-19','9'=>'NUTRICIÓN','10'=>'PSICOLÓGICA','11'=>'ALCOHOLEMIA'];
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
<div class="consultas-index">

    <h1 class="title1"><?= Html::encode($this->title) ?></h1>

    <?php 
       $fullExportMenu = '';
      if(Yii::$app->user->can('consultas_exportar')){
        $fullExportMenu = ExportMenu::widget([
            'batchSize' => 20,
            'dataProvider' => $dataProvider,
            'columns' =>[
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'attribute' =>'id_trabajador',
                    'value'=>function($model){
                        $ret = '';
                        if($model->solicitante == 2 || $model->solicitante == 3){
                            $ret = $model->apellidos.' '.$model->nombre;
                        } else {
                            if($model->trabajador){
                                $ret = $model->trabajador->apellidos.' '.$model->trabajador->nombre;
                            }
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
                    'attribute' =>'condicion',
                    'value'=>function($model){
                        $ret = '';
                        if($model->trabajador){
                            if($model->trabajador->status == 5){
                                $ret =  'Baja';
                            }else if( $model->trabajador->status == 1){
                                $ret =  'Activo';
                            }
                            else if( $model->trabajador->status == 1){
                                $ret =  'NI';
                            }
                        } else {
                            if($model->solicitante == 2){
                                $ret =  'Contratista';
                            } else if($model->solicitante == 3){
                                $ret =  'Visitante';
                            }
                        }
                        return $ret;
                      },
                   
                ],
                [
                    'attribute' =>'id_empresa',
                    'visible' => $showempresa,
                    'value'=>function($model){
                        $ret = '';
                        if($model->solicitante == 2 || $model->solicitante == 3){
                            $ret = $model->empresa;
                        } else{
                            if($model->dempresa){
                                $ret = $model->dempresa->comercial;
                            }
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
                    'attribute' =>'id_consultorio',
                    'value'=>function($model){
                        $ret = '';
                        if($model->consultorio){
                            $ret = $model->consultorio->consultorio;
                        }
                        return $ret;
                      },
                ],
                [
                    'attribute' =>'tipo',
                    'value'=>function($model){
                        $ret = '';
                        $tipoexamen = ['3'=>'CLÍNICA','1'=>'ACCIDENTE','7'=>'PROGRAMAS DE SALUD','4'=>'INCAPACIDAD','6'=>'TRABAJOS DE RIESGO','2'=>'ANTIDOPING','5'=>'PREOCUPANTE','8'=>'COVID-19','9'=>'NUTRICIÓN','10'=>'PSICOLÓGICA','11'=>'ALCOHOLEMIA'];
                        $ret .= '<span class="badge bgtransparent1 text-dark font12 m-1">'.$tipoexamen[$model->tipo].'</span>';
                        return $ret;
                      },
                ],
                [
                    'attribute' =>'folio',
                ],
                [
                    'attribute' =>'fecha',
                ],
                [
                    'attribute' =>'hora_inicio',
                ],
                [
                    'attribute' =>'hora_fin',
                ],
                [
                    'attribute' =>'visita',
                    'value'=>function($model){
                        $ret = '';
                        $visita = ['1'=>'1A VEZ','2'=>'SUBSECUENTE'];
                        $ret .= $visita[$model->visita];
                        return $ret;
                      },
                ],
                [
                    'attribute' =>'solicitante',
                    'value'=>function($model){
                        $ret = '';
                        $solicitante = ['1'=>'EMPLEADO','2'=>'CONTRATISTA','3'=>'VISITANTE'];
                        $ret .= $solicitante[$model->solicitante];
                        return $ret;
                      },
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
                    'attribute' =>'edad',
                ],
                [
                    'attribute' =>'num_imss',
                ],
                [
                    'attribute' =>'area',
                    'value'=>function($model){
                        $ret = '';
                        if(isset($model->areadata)){
                            $ret = $model->areadata->area;
                        }
                        return $ret;
                     },
                ],
                [
                    'attribute' =>'puesto',
                    'value'=>function($model){
                        $ret = '';
                        if(isset($model->puestodata)){
                            $ret = $model->puestodata->nombre;
                        }
                        return $ret;
                     },
                ],
                [
                    'attribute' =>'fc',
                    'label'=>'Signos-FC'
                ],
                [
                    'attribute' =>'fr',
                    'label'=>'Signos-FR'
                ],
                [
                    'attribute' =>'fr_diagnostico',
                    'label'=>'Signos-FR Diagnóstico',
                    'value'=>function($model){
                        $ret = '';
                        $frdiagnosticos = ['1'=>'Normal','2'=>'Bradipnea','3'=>'Taquipnea'];
                        if(isset($model->fr_diagnostico) && $model->fr_diagnostico != ''){
                            $ret .= $frdiagnosticos[$model->fr_diagnostico];
                        }
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'temp',
                    'label'=>'Signos-TEMPERATURA'
                ],
                [
                    'attribute' =>'ta',
                    'label'=>'Signos-TA'
                ],
               
                [
                    'attribute' =>'ta_diastolica',
                    'label'=>'Signos-TA Diastólica',
                ],
                [
                    'attribute' =>'tasis_diagnostico',
                    'label'=>'Signos-TA Sistólica Diagnóstico',
                    'value'=>function($model){
                        $ret = '';
                        $tadiagnosticos = ['1'=>'Normal','2'=>'Hipotensión','3'=>'Hipertensión'];
                        if(isset($model->tasis_diagnostico) && $model->tasis_diagnostico != ''){
                            $ret .= $tadiagnosticos[$model->tasis_diagnostico];
                        }
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'tadis_diagnostico',
                    'label'=>'Signos-TA Diastólica Diagnóstico',
                    'value'=>function($model){
                        $ret = '';
                        $tadiagnosticos = ['1'=>'Normal','2'=>'Hipotensión','3'=>'Hipertensión'];
                        if(isset($model->tadis_diagnostico) && $model->tadis_diagnostico != ''){
                            $ret .= $tadiagnosticos[$model->tadis_diagnostico];
                        }
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'pulso',
                    'label'=>'Signos-Pulso'
                ],
                [
                    'attribute' =>'oxigeno',
                    'label'=>'Signos-Oxígeno'
                ],
                [
                    'attribute' =>'oximetria_diagnostico',
                    'label'=>'Signos-Oximetría Diagnóstico',
                    'value'=>function($model){
                        $ret = '';
                        $oxidiagnosticos = ['1'=>'Normal','2'=>'Hipoxia'];
                        if(isset($model->oximetria_diagnostico) && $model->oximetria_diagnostico != ''){
                            $ret .= $oxidiagnosticos[$model->oximetria_diagnostico];
                        }
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'peso',
                    'label'=>'Signos-Peso'
                ],
                [
                    'attribute' =>'talla',
                    'label'=>'Signos-Talla'
                ],
                [
                    'attribute' =>'imc',
                    'label'=>'Signos-IMC'
                ],
                [
                    'attribute' =>'categoria_imc',
                    'label'=>'Signos-Categoría IMC'
                ],
                [
                    'attribute' =>'sintomatologia',
                ],
                [
                    'attribute' =>'alergias',
                ],
                [
                    'attribute' =>'embarazo',
                ],
                [
                    'attribute' =>'diagnostico',
                ],
                [
                    'attribute' =>'estudios',
                ],
                [
                    'attribute' =>'manejo',
                ],
                [
                    'attribute' =>'seguimiento',
                ],
                [
                    'attribute' =>'programa_salud',
                    'contentOptions' => ['class' => "y_centercenter",'style'=>''],
                    'filterInputOptions' => ['class' => 'icon-find2 form-control'],
                    'format'=>'raw',
                    'visible'=>false,
                    'value'=>function($model){
                        $ret = '';
                       
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
                    'attribute' =>'resultado',
                    'label'=>'Resultado',
                    'value'=>function($model){
                        $ret = '';
                        $resultados = ['1'=>'REGRESA A LABORAR (MISMA ACTIVIDAD)','2'=>'REGRESA A LABORAR (CAMBIO ACTIVIDAD)',
                        '3'=>'ENVIO IMSS','4'=>'ENVIO A DOMICILIO','5'=>'INCAPACIDAD IMSS','6'=>'EN OBSERVACIÓN'];
                        if(isset($model->resultado) && $model->resultado != ''){
                            $ret .= $resultados[$model->resultado];
                        }
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'status',
                    'value'=>function($model){
                        $ret = '';
    
                        if($model->status == 1){
                            $ret =  'Abierta';
                        }else if( $model->status == 2){
                            $ret =  'Cerrada';
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
    }else{
        $fullExportMenu = '';
    }
    ?>

    <div class="row mb-3">
        <div class="col-lg-3 d-grid">
            <?php if(Yii::$app->user->can('consultas_crear')):?>
            <?= Html::a('<span class="mx-2 small"><i class="bi bi-plus-lg"></i></span>Nueva Consulta Médica', ['create'], ['class' => 'btn btn-primary btnnew btn-block']) ?>
            <?php endif;?>
        </div>
        <div class="col-lg-3 text-end color3">
            <?=date('Y-m-d H:i')?>
        </div>
        <div class="col-lg-2 text-center">
            <?php
            if(Yii::$app->user->identity->hidden_actions == 1){
                echo Html::a('<span class=""><i class="bi bi-arrow-clockwise"></i><span>Refresh Nombre Empresas y Médicos', Url::to(['consultas/refreshnames']), [
                    'title' => Yii::t('app', 'Refresh Nombre Empresas y Médicos'),
                    'data-toggle'=>"tooltip",
                    'data-placement'=>"top",
                    'class'=>'btn btn-primary btnnew5 btn-block'
                ]);
            }
            ?>
        </div>
        <div class="col-lg-3 text-end">
            <?php
            echo $fullExportMenu;
            ?>
        </div>
    </div>

    <?php
    $template = '';
    if(Yii::$app->user->can('consultas_ver')){
        $template .='{view}';
    }
    if(Yii::$app->user->can('consultas_actualizar')){
        $template .='{update}';
    }
    if(Yii::$app->user->can('historias_delete') && Yii::$app->user->identity->activo_eliminar == 1){
        $template .='{delete}';
    }
    ?>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'headerRowOptions' =>['class' => 'text-label shadow-sm text-uppercase control-label border-0 small'],
        'tableOptions' => ['class' => 'table table-hover table-sm small','style'=>''],
        'rowOptions' => ['class' => 'font-12 text-600 bg-white shadow-sm small'],
        'pager' => [
            'class' => 'yii\bootstrap5\LinkPager'
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' =>'id_empresa',
                'visible' => $showempresa,
                'contentOptions' => ['class' => "y_centercenter color6 font600",'style'=>'width:15%'],
                'filterInputOptions' => ['class' => 'icon-find2 form-control'],
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

                    if($model->solicitante == 2 || $model->solicitante == 3){
                        $ret = $model->empresa;
                    } else{
                        if($model->dempresa){
                            $ret = $model->dempresa->comercial;
                        }
                    }
                    
                    return $ret;
                  },
            ],
            [
                    'attribute' =>'id_nivel1',
                    'format'=>'raw',
                    'contentOptions' => ['class' => " color3 font600",'style'=>'width:12%'],
                    'filterInputOptions' => ['class' => 'icon-find2 form-control'],
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
                    'filterInputOptions' => ['class' => 'icon-find2 form-control'],
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
                    'filterInputOptions' => ['class' => 'icon-find2 form-control'],
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
                    'filterInputOptions' => ['class' => 'icon-find2 form-control'],
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
                'filterInputOptions' => ['class' => 'icon-find2 form-control'],
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
                'filterInputOptions' => ['class' => 'icon-find2 form-control'],
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
                'filterInputOptions' => ['class' => 'icon-find2 form-control'],
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
                'filterInputOptions' => ['class' => 'icon-find2 form-control'],
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
                'attribute' =>'id_trabajador',
                'contentOptions' => ['class' => "y_centercenter color3 font600",'style'=>'width:20%'],
                'filterInputOptions' => ['class' => 'icon-find2 form-control'],
                'format'=>'raw',
                'value'=>function($model){
                    $ret = '';
                    if($model->solicitante == 2 || $model->solicitante == 3){
                        $ret = $model->apellidos.' '.$model->nombre;
                    } else {
                        if($model->trabajador){
                            $ret = $model->trabajador->apellidos.' '.$model->trabajador->nombre;
                        }
                    }
                    
                    return $ret;
                  },
            ],
            [
                'attribute' =>'condicion',
                'format'=>'raw',
                'contentOptions' => ['class' => "y_centercenter",'style'=>''],
                'filterInputOptions' => ['class' => 'icon-find2 form-control'],
                'headerOptions' => ['class' => "y_centercenter",'style'=>'color: #716F81;
                text-decoration:none;
                font-weight: 500;
                font-size: 11px;'],
                'filter'=>Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'condicion',
                    'data' =>['1'=>'Activo','3'=>'NI','5'=>'Baja'],
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
                    if($model->trabajador){
                        if($model->trabajador->status == 5){
                            $ret =  '<span class="badge bgcolor6 text-light font11"><span class="color11 mx-2" style=""><i class="bi bi-x"></i></span>Baja</span>';
                        }else if( $model->trabajador->status == 1){
                            $ret =  '<span class="badge bgcolor3 text-light font11"><span class="color7 mx-2" style=""><i class="bi bi-check"></i></span>Activo</span>';
                        } else if( $model->trabajador->status == 3){
                            $ret =  '<span class="badge bgcolor12 text-light font11"><span class="color7 mx-2" style=""><i class="bi bi-check"></i></span>NI</span>';
                        }
                    } else {
                        if($model->solicitante == 2){
                            $ret =  '<span class="badge bgcolor3 text-light font11"><span class="color15 mx-2" style=""><i class="bi bi-wrench"></i></span>Contratista</span>';
                        } else if($model->solicitante == 3){
                            $ret =  '<span class="badge bgcolor3 text-light font11"><span class="color14 mx-2" style=""><i class="bi bi-person-raised-hand"></i></span>Visitante</span>';
                        }
                    }
                    return $ret;
                  },
               
            ],
            [
                'attribute' =>'fecha',
                'contentOptions' => ['class' => "y_centercenter color6",'style'=>'width:10%;'],
                'filterInputOptions' => ['class' => 'icon-find2 form-control'],
                'filter' => \kartik\daterange\DateRangePicker::widget(
                    [
                        'model' => $searchModel, 
                        'attribute' => 'fecha', 
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
                'attribute' =>'create_user',
                'contentOptions' => ['class' => "y_centercenter",'style'=>''],
                'filterInputOptions' => ['class' => 'icon-find2 form-control'],
                'format'=>'raw',
                'value'=>function($model){
                    $ret = '';
                    
                    if($model->uCaptura){
                        $ret =  $model->uCaptura->name;
                    }

                    return $ret;
                  },
            ],
            [
                'attribute' =>'tipo',
                'contentOptions' => ['class' => "y_centercenter",'style'=>''],
                'filterInputOptions' => ['class' => 'icon-find2 form-control'],
                'filter'=>Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'tipo',
                    'data' =>$tipoexamen,
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
                    $tipoexamen = ['3'=>'CLÍNICA','1'=>'ACCIDENTE','7'=>'PROGRAMAS DE SALUD','4'=>'INCAPACIDAD','6'=>'TRABAJOS DE RIESGO','2'=>'ANTIDOPING','5'=>'PREOCUPANTE','8'=>'COVID-19','9'=>'NUTRICIÓN','10'=>'PSICOLÓGICA','11'=>'ALCOHOLEMIA'];
                    $ret .= '<span class="badge bgtransparent1 text-dark font12 m-1">'.$tipoexamen[$model->tipo].'</span>';
                    return $ret;
                  },
            ],
            [
                'attribute' =>'visita',
                'contentOptions' => ['class' => "y_centercenter color3",'style'=>''],
                'filterInputOptions' => ['class' => 'icon-find2 form-control'],
                'filter'=>Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'visita',
                    'data' =>['1'=>'1A VEZ','2'=>'SUBSECUENTE'],
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
                    $visita = ['1'=>'1A VEZ','2'=>'SUBSECUENTE'];
                    $ret .= $visita[$model->visita];
                    return $ret;
                  },
            ],
            [
                'attribute' =>'solicitante',
                'contentOptions' => ['class' => "y_centercenter",'style'=>''],
                'filterInputOptions' => ['class' => 'icon-find2 form-control'],
                'filter'=>Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'solicitante',
                    'data' =>['1'=>'EMPLEADO','2'=>'CONTRATISTA','3'=>'VISITANTE'],
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
                    $solicitante = ['1'=>'EMPLEADO','2'=>'CONTRATISTA','3'=>'VISITANTE'];
                    $ret .= $solicitante[$model->solicitante];
                    return $ret;
                  },
            ],
            [
                'attribute' =>'programa_salud',
                'contentOptions' => ['class' => "y_centercenter",'style'=>''],
                'filterInputOptions' => ['class' => 'icon-find2 form-control'],
                'format'=>'raw',
                'visible'=>false,
                'value'=>function($model){
                    $ret = '';
                   
                    return $ret;
                  },
            ],
            
            [
                'attribute' =>'status',
                'contentOptions' => ['class' => "y_centercenter",'style'=>''],
                'filterInputOptions' => ['class' => 'icon-find2 form-control'],
                'filter'=>Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'status',
                    'data' =>['1'=>'Abierta','2'=>'Cerrada'],
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

                    if($model->status == 1){
                        $ret =  '<div class="badge rounded-pill bg-light text-dark font10 y_centercenter font14"><span class="color5 font14" style="font-size:25px"><i class="bi bi-folder2-open"></i></span> Abierta</div>';
                    }else if( $model->status == 2){
                        $ret =  '<div class="badge rounded-pill bg-light text-dark font10 y_centercenter font14"><span class="color10 font14" style="font-size:25px"><i class="bi bi-folder"></i></span> Cerrada</div>';
                    }
                    return $ret;
                },
            ],
            [
                'attribute' =>'consentimiento',
                'label'=>'Consentimiento',
                'contentOptions' => ['class' => "y_centercenter text-center"],
                'filterInputOptions' => ['class' => 'icon-find2 form-control'],
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
                'label'=>'Consulta',
                'contentOptions' => ['class' => "y_centercenter text-center"],
                'filterInputOptions' => ['class' => 'icon-find2 form-control'],
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
                    $ret = Html::a($image, Url::to(['consultas/pdf','id' => $model->id,'firmado'=>1]), $options = ['target'=>'_blank','title' => Yii::t('app', 'Con Firma'),'data-toggle'=>"tooltip",'data-placement'=>"top"]);
                    $ret .= Html::a($image2, Url::to(['consultas/pdf','id' => $model->id,'firmado'=>0]), $options = ['target'=>'_blank','title' => Yii::t('app', 'Sin Firma'),'data-toggle'=>"tooltip",'data-placement'=>"top"]);
                    
                    if(isset($model->evidencia) && $model->evidencia!= null && $model->evidencia != '' && $model->evidencia != ' '){
                        $imageevidencia = '<span class="color15" style="font-size:25px"><i class="bi bi-folder-fill"></i></span>';
                        $filePath =  '/resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id_trabajador.'/Consultas/'.$model->evidencia;
                        $ret .= Html::a($imageevidencia, '@web'.$filePath, $options = ['target'=>'_blank','title' => Yii::t('app', 'Evidencia'),'data-bs-toggle'=>"tooltip",'data-bs-placement'=>"top"]);
                        
                    }
                    return $ret;
                },
            ],
            //'fecha',
            //'visita',
            //'solicitante',
            //'hora_inicio',
            //'hora_fin',
            //'sexo',
            //'num_imss',
            //'area',
            //'puesto',
            //'evidencia',
            //'fc',
            //'fr',
            //'temp',
            //'ta',
            //'ta_diastolica',
            //'pulso',
            //'oxigeno',
            //'peso',
            //'talla',
            //'imc',
            //'categoria_imc',
            //'sintomatologia',
            //'aparatos',
            //'alergias',
            //'embarazo',
            //'diagnosticocie',
            //'diagnostico',
            //'estudios',
            //'manejo',
            //'seguimiento',
            //'resultado',
            //'tipo_padecimiento',
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
                        return Html::a('<span class="color2"><i class="bi bi-eye-fill"></i><span>', Url::to(['consultas/view','id' => $model->id]), [
                            'title' => Yii::t('app', 'Ver'),
                            'data-toggle'=>"tooltip",
                            'data-placement'=>"top",
                            'class'=>'btn btn-sm text-center shadow-sm'
                        ]);
                    },
                    'update' =>  function($url,$model) {
                        
                        if($model->status != 2){
                            return Html::a('<span class=""><i class="bi bi-pen-fill"></i><span>', Url::to(['consultas/update','id' => $model->id]), [
                                'title' => Yii::t('app', 'Actualizar'),
                                'data-toggle'=>"tooltip",
                                'data-placement'=>"top",
                                'class'=>'btn btn-sm text-center shadow-sm'
                            ]);
                        } else{
                            return '';
                        }
                       
                    },
                    'delete' =>  function($url,$model) {
                        $iconcancel = '<i class="bi bi-trash"></i>';
                        return  Html::a('<span class="color11">'.$iconcancel.'<span>', ['delete', 'id' => $model->id], [
                            'data' => [
                                'confirm' => Yii::t('app', '¿Seguro que desea eliminar Historia Clínica de '.$model->nombre.' '.$model->apellidos.'?'),
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
        ],
    ]); ?>


</div>