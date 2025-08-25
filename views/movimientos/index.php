<?php

use app\models\Movimientos;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use kartik\export\ExportMenu;
use app\models\Empresas;
use app\models\Presentaciones;
use app\models\Unidades;
use app\models\Viasadministracion;
use app\models\Consultorios;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

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

/** @var yii\web\View $this */
/** @var app\models\MovimientosSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
$titulo = '';
if($tipo == 1){
    $this->title = Yii::t('app', 'Bitácora de Movimientos - Medicamentos'.$name_empresa);
    $titulo = 'Medicamentos';
} else{
    $this->title = Yii::t('app', 'Bitácora de Movimientos - EPP'.$name_empresa);
    $titulo = 'Equipo de Protección Personal';
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
$presentaciones = ArrayHelper::map(Presentaciones::find()->orderBy('presentacion')->all(), 'id', 'presentacion');
$unidades = ArrayHelper::map(Unidades::find()->orderBy('unidad')->all(), 'id', 'unidad');
$vias = ArrayHelper::map(Viasadministracion::find()->orderBy('via_administracion')->all(), 'id', 'via_administracion');
$tipos = ['1'=>'Ingreso','2'=>'Traspaso','3'=>'Ajustes','4'=>'Inventario Inicial','6'=>'Consulta','5'=>'Traspaso','7'=>'Caducidad','8'=>'Devolucion','9'=>'Entrega EPP'];
$consultorios = ArrayHelper::map(Consultorios::find()->orderBy('consultorio')->all(), 'id', 'consultorio');
$consultorios[0]='Almacén';
?>
<div class="movimientos-index">
    <h1 class="title1">
        <?= Html::encode($this->title) ?></h1>

    <?php 
    $fullExportMenu = '';
if(($tipo == 1 && Yii::$app->user->can('medicamentosbitacora_exportar')) || ($tipo == 2 && Yii::$app->user->can('eppsbitacora_exportar'))){
$fullExportMenu = ExportMenu::widget([
    'dataProvider' => $dataProvider,
    'columns' =>[
        ['class' => 'yii\grid\SerialColumn'], 
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
            'attribute' =>'id_consultorio',
            'value'=>function($model){
                $ret = '';
               
                if($model->consultorio1){
                    $ret = $model->consultorio1->consultorio;
                }

                return $ret;
              },
        ],
        [
            'attribute' =>'folio',
            'visible'=>false,
            'value'=>function($model){
                $ret = '';
                if(isset($model->folio)){
                    $ret = $model->folio;
                }
                return $ret;
             },
        ],
        [
            'attribute' =>'e_s',
            'label'=>'Entrada Salida',
            'value'=>function($model) use ($tipos){
                $ret = '';
                
                if($model->e_s == 1){
                    $ret = 'Entrada';
                } else if($model->e_s == 2){
                    $ret = 'Salida';
                }
                return $ret;
             },
        ],
        [
            'attribute' =>'tipo',
            'label'=>'Tipo Movimiento',
            'value'=>function($model) use ($tipos){
                $ret = '';
                
                if(isset($model->tipo)){
                    $ret = $tipos[$model->tipo];
                }
                return $ret;
             },
        ],
        [
            'attribute' =>'create_date',
        ],
        [
            'attribute' =>'medicamentos',
            'label'=>$titulo,
            'value'=>function($model) use ($tipos){
                $ret = '';
                
                if($model->medicamentos){
                    foreach($model->medicamentos as $key=>$medicamento){
                        $ret.= $medicamento->insumo->nombre_comercial.' | '.$medicamento->cantidad_unidad.' unidades';
                        if($key < (count($model->medicamentos)-1)){
                            $ret .= ',';
                        }
                    }
                }

                return $ret;
             },
        ],
        [
            'attribute' =>'observaciones',
            'value'=>function($model){
                $ret = '';

                $ret .= $model->observaciones;
                
                if($model->consulta && $model->consulta->trabajador){
                    $ret = 'Trabajador: '.$model->consulta->trabajador->nombre.' '.$model->consulta->trabajador->apellidos;
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
        <div class="col-lg-3 d-grid" style="display:none;">
            <?php if(($tipo == 1 && Yii::$app->user->can('medicamentosbitacora_crear')) || ($tipo == 2 && Yii::$app->user->can('eppsbitacora_crear'))):?>
            <?= Html::a('<span class="mx-2 small"><i class="bi bi-plus-lg"></i></span>Nuevo Movimiento', ['create','tipo'=>$tipo], ['class' => 'btn btn-primary btnnew btn-block']) ?>
            <?php endif;?>
        </div>

        <div class="col-lg-9 text-end">
            <?php
            echo $fullExportMenu;
            ?>
        </div>
    </div>

    <?php
      $template = '';
      if(($tipo == 1 && Yii::$app->user->can('medicamentosbitacora_ver')) || ($tipo == 2 && Yii::$app->user->can('eppsbitacora_ver'))){
          $template .='{view}';
      }
      if(($tipo == 1 && Yii::$app->user->can('medicamentosbitacora_actualizar')) || ($tipo == 2 && Yii::$app->user->can('eppsbitacora_actualizar'))){
          $template .='{update}';
      }
    ?>


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
                'attribute' =>'id_empresa',
                'visible' => $showempresa,
                'contentOptions' => ['class' => " color6 font600",'style'=>'width:15%'],
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
                'attribute' =>'id_pais',
                'contentOptions' => ['class' => "",'style'=>'width:10%'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'format'=>'raw',
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
                'value'=>function($model){
                    $ret = '';
                    if($model->ubicacion){
                        $ret .='<span class="badge rounded-pill font10 bg-light text-dark">'.$model->ubicacion->ubicacion.'</span>';
                    }
                    return $ret;
                  },
            ],
            [
                'attribute' =>'id_consultorio',
                'contentOptions' => ['class' => " color3 font600",'style'=>'width:15%'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'filter'=>Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'id_consultorio',
                    'data' =>$consultorios,
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
                   
                    if($model->consultorio1){
                        $ret = $model->consultorio1->consultorio;
                    }

                    return $ret;
                  },
            ],
            [
                'attribute' =>'folio',
                'visible'=>false,
                'contentOptions' => ['class' => " color3",'style'=>''],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'value'=>function($model){
                    $ret = '';
                    if(isset($model->folio)){
                        $ret = $model->folio;
                    }
                    return $ret;
                 },
            ],
            [
                'attribute' =>'e_s',
                'label'=>'Entrada Salida',
                'contentOptions' => ['class' => " font600 text-uppercase",'style'=>''],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'filter'=>Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'e_s',
                    'data' =>['1'=>'Entrada','2'=>'Salida'],
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
                'value'=>function($model) use ($tipos){
                    $ret = '';
                    
                    if($model->e_s == 1){
                        $ret = 'Entrada';
                    } else if($model->e_s == 2){
                        $ret = 'Salida';
                    }
                    return $ret;
                 },
            ],
            [
                'attribute' =>'tipo',
                'label'=>'Tipo Movimiento',
                'contentOptions' => ['class' => "",'style'=>''],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'filter'=>Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'tipo',
                    'data' =>$tipos,
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
                'value'=>function($model) use ($tipos){
                    $ret = '';
                    
                    if(isset($model->tipo)){
                        $ret = '<span class="badge bgtransparent1 text-dark font12 m-1">'.$tipos[$model->tipo].'</span>';
                    }
                    return $ret;
                 },
            ],
            [
                'attribute' =>'pdf',
                'label'=>'',
                'contentOptions' => ['class' => " text-center"],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'format'=>'raw',
                'visible' =>true,
                'value'=>function($model){
                    $ret = '';
                   
                    $image = '<span class="color1" style="font-size:25px"><i class="bi bi-file-earmark-text-fill"></i></span>';
                    $image2 = '<span class="color2" style="font-size:25px"><i class="bi bi-file-earmark-text-fill"></i></span>';
                    
                    if($model->id_consultahc){
                        $ret = Html::a($image, Url::to(['consultas/pdf','id' => $model->id_consultahc,'firmado'=>1]), $options = ['target'=>'_blank','title' => Yii::t('app', 'Con Firma'),'data-toggle'=>"tooltip",'data-placement'=>"top"]);
                    $ret .= Html::a($image2, Url::to(['consultas/pdf','id' => $model->id_consultahc,'firmado'=>0]), $options = ['target'=>'_blank','title' => Yii::t('app', 'Sin Firma'),'data-toggle'=>"tooltip",'data-placement'=>"top"]);

                    }
            
                    return $ret;
                },
            ],
            [
                'attribute' =>'create_date',
                'contentOptions' => ['class' => " color6",'style'=>'width:10%;'],
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
                'attribute' =>'medicamentos',
                'label'=>$titulo,
                'contentOptions' => ['class' => "y_centercenter font500",'style'=>'width:40%'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
               'headerOptions' => ['class' => "y_centercenter",'style'=>'color: #716F81;
                text-decoration:none;
                font-weight: 500;
                font-size: 11px;'],
                'format'=>'raw',
                'value'=>function($model) use ($tipos){
                    $ret = '';
                    
                    if($model->medicamentos){
                        foreach($model->medicamentos as $key=>$medicamento){
                            $foto = '';

                            $carpeta = 'Medicamentos';
                            if($medicamento->insumo->tipo == 2){
                                $carpeta = 'EPP';
                            }
                            if(isset($medicamento->insumo->foto) && $medicamento->insumo->foto != ""){
                                $filePath =  '/resources/Empresas/'.$medicamento->insumo->id_empresa.'/'.$carpeta.'/'.$medicamento->insumo->id.'/'.$medicamento->insumo->foto;
                                $foto = Html::img('@web'. $filePath, ['alt'=>' ', 'class' => "iconphoto img-responsive", 'width' => '35px', 'height' =>'auto','style'=>'box-shadow: rgba(50, 50, 93, 0.25) 0px 13px 27px -5px, rgba(0, 0, 0, 0.3) 0px 8px 16px -8px; height:auto; width:35px;']);
                                
                            }
                            $ret.= '<span class="mx-1 color3"><i class="bi bi-dash-lg"></i></span><span class="mx-2">'. $foto.'</span>'.$medicamento->insumo->nombre_comercial.'<span class="font600 color3"> | '.$medicamento->cantidad_unidad.' unidades</span><br>';
                        }
                    }

                    return $ret;
                 },
            ],
            [
                'attribute' =>'observaciones',
                'contentOptions' => ['class' => "",'style'=>'width:10%'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'format'=>'raw',
                'value'=>function($model){
                    $ret = '';

                    $ret .= '<div class="p-2 bg-light rounded-3 small text-dark font500">'.$model->observaciones.'</div>';
                    
                    if($model->consulta && $model->consulta->trabajador){
                        $ret = '<div class="p-2 bg-light rounded-3 small text-dark font500">Trabajador: '.$model->consulta->trabajador->nombre.' '.$model->consulta->trabajador->apellidos.'</div>';
                    }

                    if($model->trabajador){
                        $ret = '<div class="p-2 bg-light rounded-3 small color3 font500">Trabajador: '.$model->trabajador->nombre.' '.$model->trabajador->apellidos.'</div><div class="border-top">'.$model->motivoentrega.'</div>';
                    }
                    return $ret;
                  },
            ],
            //'id_consultorio2',
            //'create_date',
            //'create_user',
            //'update_date',
            //'update_user',
            //'delete_date',
            //'delete_user',
            //'observaciones:ntext',
            //'movimiento_relacionados',
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
                        return Html::a('<span class="color2"><i class="bi bi-eye-fill"></i><span>', Url::to(['movimientos/view','id' => $model->id]), [
                            'title' => Yii::t('app', 'Ver'),
                            'data-toggle'=>"tooltip",
                            'data-placement'=>"top",
                            'class'=>'btn btn-sm text-center shadow-sm'
                        ]);
                    },
                    'update' =>  function($url,$model) {
                        if($model->tipo != 6 && $model->tipo != 1 && $model->e_s != 1){
                            return Html::a('<span class=""><i class="bi bi-pen-fill"></i><span>', Url::to(['movimientos/update','id' => $model->id]), [
                                'title' => Yii::t('app', 'Actualizar'),
                                'data-toggle'=>"tooltip",
                                'data-placement'=>"top",
                                'class'=>'btn btn-sm text-center shadow-sm'
                            ]);
                        }
                        
                    },
                ],
            ],
        ],
    ]); ?>


</div>