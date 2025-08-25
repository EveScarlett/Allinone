<?php

use app\models\Almacen;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use kartik\export\ExportMenu;
use app\models\Empresas;
use app\models\Presentaciones;
use app\models\Unidades;
use app\models\Viasadministracion;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\Consultorios;
use app\models\Insumostockmin;

/** @var yii\web\View $this */
/** @var app\models\AlmacenSearch $searchModel */
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
    $this->title = Yii::t('app', 'Stock Actual - Medicamentos'.$name_empresa);
} else{
    $this->title = Yii::t('app', 'Stock Actual - EPP'.$name_empresa);
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
$consultorios = ArrayHelper::map(Consultorios::find()->orderBy('consultorio')->all(), 'id', 'consultorio');
$consultorios[0]='Almacén';
?>
<div class="almacen-index">

    <h1 class="title1"><?= Html::encode($this->title) ?></h1>

    <?php 
if(($tipo == 1 && Yii::$app->user->can('medicamentosstock_exportar')) || ($tipo == 2 && Yii::$app->user->can('eppsstock_exportar'))){
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
               
                if($model->consultorio){
                    $ret = $model->consultorio->consultorio;
                }

                return $ret;
              },
        ],
        [
            'attribute' =>'id_insumo',
            'value'=>function($model){
                $ret = '';
                if($model->insumo->nombre_comercial){
                    $ret = $model->insumo->nombre_comercial;
                }
                $ret .= '-'.$model->insumo->nombre_generico;
                return $ret;
              },
        ],
        [
            'attribute' =>'fecha_caducidad',
        ],
        [
            'attribute' =>'stock_minimo',
            'label'=>'Stock Mínimo',
            'value'=>function($model) use ($tipo){
                $ret = '--';
                if($tipo==1){
                    if($model->insumo && $model->insumo->stock_minimo){
                        $ret = $model->insumo->stock_minimo;
                    }
                } else{
                    $stockmin = Insumostockmin::find()->where(['id_insumo'=>$model->id_insumo])->andWhere(['id_consultorio'=>$model->id_consultorio])->one();
                    if($stockmin){
                        $ret = $stockmin->stock;
                    }
                }
                
                return $ret;
              },
        ],
        [
            'attribute' =>'stock',
            'label'=>'Stock Actual',
            'value'=>function($model){
                $ret = '0';
                if($model->stock){
                    $ret = $model->stock;
                }
                return $ret;
              },
        ],
        [
            'attribute' =>'stock_unidad',
            'value'=>function($model){
                $ret = '0';
                if($model->stock_unidad){
                    $ret = $model->stock_unidad.'  unidades';
                }
                return $ret;
              },
        ],
        [
            'attribute' =>'update_date',
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
            <?php if(($tipo == 1 && Yii::$app->user->can('medicamentosbitacora_crear')) || ($tipo == 2 && Yii::$app->user->can('eppsbitacora_crear'))):?>
            <?= Html::a('<span class="mx-2 small"><i class="bi bi-plus-lg"></i></span>Nuevo Movimiento', ['movimientos/create','tipo'=>$tipo], ['class' => 'btn btn-primary btnnew btn-block']) ?>
            <?php endif;?>
        </div>

        <div class="col-lg-9 text-end">
            <?php
            echo $fullExportMenu;
            ?>
        </div>
    </div>
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
                'contentOptions' => ['class' => "y_centercenter color6 font600",'style'=>'width:15%'],
                'contentOptions' => function($model) {
                    $hoy = date('Y-m-d');
                       
                    if($hoy > $model->fecha_caducidad){
                        return ['class' => "y_centercenter color6 font600",'style'=>'width:15%; background-color:#FF597B1a;'];
                    } else{
                        return ['class' => "y_centercenter color6 font600",'style'=>'width:15%'];
                    }
                   
                },
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
                'contentOptions' => ['class' => "y_centercenter color3 font600",'style'=>'width:15%'],
                'contentOptions' => function($model) {
                    $hoy = date('Y-m-d');
                       
                    if($hoy > $model->fecha_caducidad){
                        return ['class' => "y_centercenter color3 font600",'style'=>'width:15%; background-color:#FF597B1a;'];
                    } else{
                        return ['class' => "y_centercenter color3 font600",'style'=>'width:15%'];
                    }
                   
                },
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
                   
                    if($model->consultorio){
                        $ret = $model->consultorio->consultorio;
                    }

                    return $ret;
                  },
            ],
            [
                'attribute'=>'logo',
                'format'=>'raw',
                'label'=>'',
                'headerOptions'=>['style'=>'width:5%;','class'=>'font12'],
                'contentOptions'=>['class'=>'font500 y_centercenter borderblue' ,'style'=>'vertical-align: middle;'],
                'contentOptions' => function($model) {
                    $hoy = date('Y-m-d');
                       
                    if($hoy > $model->fecha_caducidad){
                        return ['class' => "font500 y_centercenter borderblue",'style'=>'vertical-align: middle; background-color:#FF597B1a;'];
                    } else{
                        return ['class' => "font500 y_centercenter borderblue",'style'=>'vertical-align: middle;'];
                    }
                   
                },
                'filterInputOptions' => ['class' => 'icon-find form-control','placeholder'=>'Buscar...'],
                'visible'=>true,
                "value"=>function($model){
                    $ret = '';
                    $carpeta = 'Medicamentos';
                    if($model->insumo->tipo == 2){
                        $carpeta = 'EPP';
                    }
                    
                    if($model->insumo && isset($model->insumo->foto) && $model->insumo->foto != ""){
                        $ret = '';
                        $filePath =  '/resources/Empresas/'.$model->id_empresa.'/'.$carpeta.'/'.$model->insumo->id.'/'.$model->insumo->foto;
                        $foto = Html::img('@web'. $filePath, ['alt'=>' ', 'class' => "iconphoto img-responsive", 'width' => '35px', 'height' =>'auto','style'=>'box-shadow: rgba(50, 50, 93, 0.25) 0px 13px 27px -5px, rgba(0, 0, 0, 0.3) 0px 8px 16px -8px; height:auto; width:35px;']);
                        $ret = $foto;
                    }

                    return $ret;
                }
            ],
            [
                'attribute' =>'id_insumo',
                'contentOptions' => ['class' => "y_centercenter color3 font600",'style'=>'width:35%'],
                'contentOptions' => function($model) {
                    $hoy = date('Y-m-d');
                       
                    if($hoy > $model->fecha_caducidad){
                        return ['class' => "y_centercenter color3 font600",'style'=>'width:40%; background-color:#FF597B1a;'];
                    } else{
                        return ['class' => "y_centercenter color3 font600",'style'=>'width:40%'];
                    }
                   
                },
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'format'=>'raw',
                'value'=>function($model){
                    $ret = '';
                    if($model->insumo->nombre_comercial){
                        $ret = $model->insumo->nombre_comercial;

                    }
                    $ret .= '<br><div class="p-2 bg-light rounded-3 small text-dark font500">'.$model->insumo->nombre_generico.'</div>';
                    return $ret;
                  },
            ],
            [
                'attribute' =>'fecha_caducidad',
                'contentOptions' => ['class' => "y_centercenter font600 text-center",'style'=>'width:10%;'],
                'contentOptions' => function($model) {
                    $hoy = date('Y-m-d');
                       
                    if($hoy > $model->fecha_caducidad){
                        return ['class' => "y_centercenter font600 text-center",'style'=>'width:10%; background-color:#FF597B1a;'];
                    } else{
                        return ['class' => "y_centercenter font600 text-center",'style'=>'width:10%'];
                    }
                   
                },
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'format'=>'raw',
                'filter' => \kartik\daterange\DateRangePicker::widget(
                    [
                        'model' => $searchModel, 
                        'attribute' => 'fecha_caducidad', 
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
                    if($model->fecha_caducidad){
                        $hoy = date('Y-m-d');
                       
                        if($hoy > $model->fecha_caducidad){
                            $ret  = '<span class="badge bgcolor6 text-light font11">'.$model->fecha_caducidad.'</span>';
                            $ret .= '<br><span class="color11">VENCIDO</span>';
                        } else{
                            $ret = '<span class="badge bgcolor3 text-light font11">'.$model->fecha_caducidad.'</span>';
                        }
                        
                    }
                    return $ret;
                },
            ],
            [
                'attribute' =>'stock_minimo',
                'label'=>'Stock Mínimo',
                'contentOptions' => ['class' => "y_centercenter text-center color6",'style'=>'width:10%'],
                'headerOptions' => ['class' => "y_centercenter",'style'=>'color: #716F81;
                text-decoration:none;
                font-weight: 500;
                font-size: 11px;'],
                'contentOptions' => function($model) {
                    $hoy = date('Y-m-d');
                       
                    if($hoy > $model->fecha_caducidad){
                        return ['class' => "y_centercenter color6 text-center",'style'=>'width:10%; background-color:#FF597B1a;'];
                    } else{
                        return ['class' => "y_centercenter color6 text-center",'style'=>'width:10%'];
                    }
                   
                },
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'format'=>'raw',
                'value'=>function($model) use ($tipo){
                    $ret = '--';
                    if($tipo==1){
                        if($model->insumo && $model->insumo->stock_minimo){
                            $ret = $model->insumo->stock_minimo;
                        }
                    } else{
                        $stockmin = Insumostockmin::find()->where(['id_insumo'=>$model->id_insumo])->andWhere(['id_consultorio'=>$model->id_consultorio])->one();
                        if($stockmin){
                            $ret = $stockmin->stock;
                        }
                    }
                    
                    return $ret;
                  },
            ],
            [
                'attribute' =>'stock',
                'label'=>'Stock Actual',
                'visible'=>false,
                'contentOptions' => ['class' => "y_centercenter text-center font600",'style'=>'width:15%'],
                'contentOptions' => function($model) {
                    $hoy = date('Y-m-d');
                       
                    if($hoy > $model->fecha_caducidad){
                        return ['class' => "y_centercenter text-center font600",'style'=>'width:15%; background-color:#FF597B1a;'];
                    } else{
                        return ['class' => "y_centercenter text-center font600",'style'=>'width:15%'];
                    }
                   
                },
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'format'=>'raw',
                'value'=>function($model){
                    $ret = '0';
                    if($model->stock){
                        $ret = $model->stock;
                    }
                    return $ret;
                  },
            ],
            [
                'attribute' =>'stock_unidad',
                'contentOptions' => ['class' => "y_centercenter text-center font600 color3",'style'=>'width:15%'],
                'contentOptions' => function($model) {
                    $hoy = date('Y-m-d');
                       
                    if($hoy > $model->fecha_caducidad){
                        return ['class' => "y_centercenter text-center font600 color3",'style'=>'width:15%; background-color:#FF597B1a;'];
                    } else{
                        return ['class' => "y_centercenter text-center font600 color3",'style'=>'width:15%'];
                    }
                   
                },
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'format'=>'raw',
                'value'=>function($model){
                    $ret = '0';
                    if($model->stock_unidad){
                        $ret = $model->stock_unidad.'  unidades';
                    }
                    return $ret;
                  },
            ],
            [
                'attribute' =>'update_date',
                'visible'=>false,
                'contentOptions' => ['class' => "y_centercenter color6",'style'=>'width:10%;'],
                'contentOptions' => function($model) {
                    $hoy = date('Y-m-d');
                       
                    if($hoy > $model->fecha_caducidad){
                        return ['class' => "y_centercenter color6",'style'=>'width:10%; background-color:#FF597B1a;'];
                    } else{
                        return ['class' => "y_centercenter color6",'style'=>'width:10%'];
                    }
                   
                },
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'filter' => \kartik\daterange\DateRangePicker::widget(
                    [
                        'model' => $searchModel, 
                        'attribute' => 'update_date', 
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
            //'stock_unidad',
            //'update_date',
            //'update_user',
           
        ],
    ]); ?>


</div>