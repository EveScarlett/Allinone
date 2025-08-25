<?php

use app\models\Historicoes;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use kartik\export\ExportMenu;
use app\models\Empresas;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;


/** @var yii\web\View $this */
/** @var app\models\HistoricoesSearch $searchModel */
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

$this->title = Yii::t('app', 'Histórico Operación Maquinaria'.$name_empresa);
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
  if(Yii::$app->user->can('usuarios_exportar')){
    $fullExportMenu = ExportMenu::widget([
        'dataProvider' => $dataProvider,
        'columns' =>[
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' =>'id_empresa',
                'visible' => $showempresa,
                'contentOptions' => ['class' => "y_centercenter color6 font600",'style'=>'width:15%'],
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
                'attribute' =>'id_trabajador',
                'contentOptions' => ['class' => "y_centercenter color3 font600",'style'=>'width:20%'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'format'=>'raw',
                'value'=>function($model){
                    $ret = '';
                    if($model->trabajador){
                        $ret = $model->trabajador->nombre.' '.$model->trabajador->apellidos;
                    }
                    return $ret;
                  },
            ],
            [
                'attribute' =>'id_maquina',
                'contentOptions' => ['class' => "y_centercenter font600",'style'=>'width:20%'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'format'=>'raw',
                'value'=>function($model){
                    $ret = '';
                    if($model->maquina){
                        $ret .= $model->maquina->maquina;

                        $ret .= ''.$model->maquina->clave.'';
                    }
                    return $ret;
                  },
            ],
            [
                'attribute' =>'fecha_inicio',
                'contentOptions' => ['class' => "y_centercenter ",'style'=>'width:10%;'],
                'format'=>'raw',
                'value'=>function($model){
                    $ret = '';
                    if($model->fecha_inicio){
                        $ret = date('Y-m-d', strtotime($model->fecha_inicio));
                    }
                    return $ret;
                },
            ],
            [
                'attribute' =>'hora_inicio',
                'contentOptions' => ['class' => "y_centercenter color6",'style'=>'width:10%;'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'headerOptions' => ['class' => "y_centercenter",'style'=>'color: #716F81;
                text-decoration:none;
                font-weight: 500;
                font-size: 11px;'],
                'format'=>'raw',
                'value'=>function($model){
                    $ret = '';
                    if($model->fecha_inicio){
                        $ret = date('H:i A', strtotime($model->fecha_inicio));
                    }
                    return $ret;
                },
            ],
            [
                'attribute' =>'fecha_fin',
                'contentOptions' => ['class' => "y_centercenter ",'style'=>'width:10%;'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'format'=>'raw',
                'value'=>function($model){
                    $ret = '';
                    if($model->fecha_fin){
                        $ret = date('Y-m-d', strtotime($model->fecha_fin));
                    }
                    return $ret;
                },
            ],
            [
                'attribute' =>'hora_fin',
                'contentOptions' => ['class' => "y_centercenter color6",'style'=>'width:10%;'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'headerOptions' => ['class' => "y_centercenter",'style'=>'color: #716F81;
                text-decoration:none;
                font-weight: 500;
                font-size: 11px;'],
                'format'=>'raw',
                'value'=>function($model){
                    $ret = '';
                    if($model->fecha_fin){
                        $ret = date('H:i A', strtotime($model->fecha_fin));
                    }
                    return $ret;
                },
            ],
            [
                'attribute' =>'status_trabajo',
                'label'=>'Status Operación',
                'contentOptions' => ['class' => "y_centercenter",'style'=>''],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'format'=>'raw',
                'value'=>function($model){
                    $ret = '';

                    if( $model->status_trabajo == 1){
                        $ret =  'EN PROCESO';
                    } else if( $model->status_trabajo == 3){
                        $ret =  'FINALIZADA';
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

<?php
$empresas = explode(',', Yii::$app->user->identity->empresas_select);
        
if(Yii::$app->user->identity->empresa_all != 1) {
    $empresas = ArrayHelper::map(Empresas::find()->where(['in','id',$empresas])->orderBy('comercial')->all(), 'id', 'comercial');
} else{
    $empresas = ArrayHelper::map(Empresas::find()->orderBy('comercial')->all(), 'id', 'comercial');
}
?>
<div class="historicoes-index">

    <h1 class="title1"><?= Html::encode($this->title) ?></h1>

    <div class="row mb-3">
        <div class="col-lg-3 d-grid">
            
        </div>
        <div class="col-lg-6 text-center">
           
        </div>
        <div class="col-lg-3 text-end">
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
                'attribute'=>'foto_qr',
                'format'=>'raw',
                'label'=>'',
                'headerOptions'=>['style'=>'width:5%;','class'=>'font12'],
                'contentOptions'=>['class'=>'font500 y_centercenter' ,'style'=>'vertical-align: middle;'],
                'filterInputOptions' => ['class' => 'icon-find form-control','placeholder'=>'Buscar...'],
                'visible'=>true,
                "value"=>function($model){
                    $ret = '';

                    if($model->trabajador){
                        
                        if(isset($model->trabajador->foto) && $model->trabajador->foto != ""){
                            $extra = '/web';
                            $filePath = $extra.'/resources/Empresas/'.$model->trabajador->id_empresa.'/Trabajadores/'.$model->trabajador->id.'/Documentos/'.$model->trabajador->foto;
                            /* $filePath = $extra.'/web/resources/Empresas/'.$model->trabajador->id_empresa.'/Trabajadores/'.$model->trabajador->id.'/Documentos/'.$model->trabajador->foto; */
    
                            $imagen = 'background: url(..'.$filePath.');background-size:cover;';
                            $ret .= "<div class='icon-cube p-2 mt-2' style='".$imagen."'></div>";
                        } else{
                            $imagen = '';
                            $ret .= "<div class='icon-cube p-2 mt-2'>".$imagen."</div>";
                        }
                    }

                    return $ret;
                }
            ],
            [
                'attribute' =>'id_trabajador',
                'contentOptions' => ['class' => "y_centercenter color3 font600",'style'=>'width:20%'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'format'=>'raw',
                'value'=>function($model){
                    $ret = '';
                    if($model->trabajador){
                        $ret = $model->trabajador->nombre.' '.$model->trabajador->apellidos;
                    }
                    return $ret;
                  },
            ],
            [
                'attribute'=>'foto_maquina',
                'format'=>'raw',
                'label'=>'',
                'headerOptions'=>['style'=>'width:5%;','class'=>'font12'],
                'contentOptions'=>['class'=>'font500 y_centercenter' ,'style'=>'vertical-align: middle;'],
                'filterInputOptions' => ['class' => 'icon-find form-control','placeholder'=>'Buscar...'],
                'visible'=>true,
                "value"=>function($model){
                    $ret = '';

                    if($model->maquina){
                        if(isset($model->maquina->foto) && $model->maquina->foto != ""){
                            $extra = '/web';
                            $filePath = $extra.'/resources/Maquinarias/'.$model->maquina->id.'/'.$model->maquina->foto;
                            /* $filePath = $extra.'/web/resources/Maquinarias/'.$model->maquina->id.'/'.$model->maquina->foto; */
    
                            $imagen = 'background: url(..'.$filePath.');background-size:cover;';
                            $ret .= "<div class='icon-cube p-2 mt-2' style='".$imagen."'></div>";
                        } else{
                            $imagen = '';
                            $ret .= "<div class='icon-cube p-2 mt-2'>".$imagen."</div>";
                        }
                    }

                    return $ret;
                }
            ],
            [
                'attribute' =>'id_maquina',
                'contentOptions' => ['class' => "y_centercenter font600",'style'=>'width:20%'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'format'=>'raw',
                'value'=>function($model){
                    $ret = '';
                    if($model->maquina){
                        $ret .= $model->maquina->maquina;

                        $ret .= '<br><div class="p-2 bg-light rounded-3 small text-dark font500">'.$model->maquina->clave.'</div>';
                    }
                    return $ret;
                  },
            ],
            [
                'attribute' =>'fecha_inicio',
                'contentOptions' => ['class' => "y_centercenter ",'style'=>'width:10%;'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'filter' => \kartik\daterange\DateRangePicker::widget(
                    [
                        'model' => $searchModel, 
                        'attribute' => 'fecha_inicio', 
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
                'format'=>'raw',
                'value'=>function($model){
                    $ret = '';
                    if($model->fecha_inicio){
                        $ret = date('Y-m-d', strtotime($model->fecha_inicio));
                    }
                    return $ret;
                },
            ],
            [
                'attribute' =>'hora_inicio',
                'contentOptions' => ['class' => "y_centercenter color6",'style'=>'width:10%;'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'headerOptions' => ['class' => "y_centercenter",'style'=>'color: #716F81;
                text-decoration:none;
                font-weight: 500;
                font-size: 11px;'],
                'format'=>'raw',
                'value'=>function($model){
                    $ret = '';
                    if($model->fecha_inicio){
                        $ret = date('H:i A', strtotime($model->fecha_inicio));
                    }
                    return $ret;
                },
            ],
            [
                'attribute' =>'fecha_fin',
                'contentOptions' => ['class' => "y_centercenter ",'style'=>'width:10%;'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'filter' => \kartik\daterange\DateRangePicker::widget(
                    [
                        'model' => $searchModel, 
                        'attribute' => 'fecha_fin', 
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
                'format'=>'raw',
                'value'=>function($model){
                    $ret = '';
                    if($model->fecha_fin){
                        $ret = date('Y-m-d', strtotime($model->fecha_fin));
                    }
                    return $ret;
                },
            ],
            [
                'attribute' =>'hora_fin',
                'contentOptions' => ['class' => "y_centercenter color6",'style'=>'width:10%;'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'headerOptions' => ['class' => "y_centercenter",'style'=>'color: #716F81;
                text-decoration:none;
                font-weight: 500;
                font-size: 11px;'],
                'format'=>'raw',
                'value'=>function($model){
                    $ret = '';
                    if($model->fecha_fin){
                        $ret = date('H:i A', strtotime($model->fecha_fin));
                    }
                    return $ret;
                },
            ],
            [
                'attribute' =>'status_trabajo',
                'label'=>'Status Operación',
                'contentOptions' => ['class' => "y_centercenter",'style'=>''],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'format'=>'raw',
                'value'=>function($model){
                    $ret = '';

                    if( $model->status_trabajo == 1){
                        $ret =  '<span class="badge bgcumple m-1 font14 text-light text-uppercase">EN PROCESO</span>';
                    } else if( $model->status_trabajo == 3){
                        $ret =  '<span class="badge bgnocumple m-1 font14 text-light text-uppercase">FINALIZADA</span>';
                    }
                   
                    return $ret;
                  },
            ],
            //'status_trabajo',
            //'fecha_inicio',
            //'fecha_fin',
            //'status',
            //'create_date',
            //'create_user',
           
        ],
    ]); ?>


</div>