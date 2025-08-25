<?php

use app\models\Mantenimientos;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use kartik\export\ExportMenu;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\bootstrap5\Modal;



use app\models\Empresas;

/** @var yii\web\View $this */
/** @var app\models\MantenimientosSearch $searchModel */
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

$this->title = Yii::t('app', 'Histórico de Mantenimiento'.$name_empresa);
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

$ids_empresas = [];

foreach($empresas as $key=>$item){
    if(!in_array($key, $ids_empresas)){
        array_push($ids_empresas, $key);
    }
}

$tipomantenimiento = ['1' => 'PREVENTIVO', '2' => 'CORRECTIVO', '3'=> 'REVISIÓN', '4'=>'PUESTA EN MARCHA'];
$statusmaquina = ['1' => 'Funcionando Correctamente', '2' => 'Fuera de Servicio', '3'=> 'Pendiente de Repuesto'];
?>

<?php
    $template = '';
    if(Yii::$app->user->can('trabajadores_ver')){
        $template .='{view}';
    }
    if(Yii::$app->user->can('trabajadores_actualizar')){
        $template .='{update}';
    }
    ?>
<?php 
  if(Yii::$app->user->can('usuarios_exportar')){
    $fullExportMenu = ExportMenu::widget([
        'dataProvider' => $dataProvider,
        'columns' =>[
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' =>'clave',
                'contentOptions' => ['class' => "y_centercenter",'style'=>''],
                'value'=>function($model){
                    $ret = '';
                    if($model->clave){
                        $ret .= $model->clave;
                    }
                    return $ret;
                  },
            ],
            [
                'attribute' =>'id_empresa',
                'visible' => $showempresa,
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
                'attribute' =>'tipo_mantenimiento',
                'contentOptions' => ['class' => "y_centercenter",'style'=>''],
                'format'=>'raw',
                'value'=>function($model) use ($tipomantenimiento){
                    $ret = '';
                   
                    $ret .= $tipomantenimiento[$model->tipo_mantenimiento];
                    return $ret;
                  },
            ],
            [
                'attribute' =>'id_maquina',
                'contentOptions' => ['class' => "y_centercenter",'style'=>'width:20%'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'format'=>'raw',
                'value'=>function($model){
                    $ret = '';
                    $ret .= $model->nombre;
                    return $ret;
                  },
            ],
            [
                'attribute' =>'marca',
                'contentOptions' => ['class' => "y_centercenter",'style'=>'width:20%'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'format'=>'raw',
                'value'=>function($model){
                    $ret = '';

                    $ret .= $model->marca;
                    $ret .= $model->modelo;
                    $ret .= $model->numero_serie;
                    
                    return $ret;
                  },
            ],
            [
                'attribute' =>'modelo',
                'contentOptions' => ['class' => "y_centercenter",'style'=>'width:20%'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'format'=>'raw',
                'value'=>function($model){
                    $ret = '';

                    $ret .= $model->modelo;
                   
                    return $ret;
                  },
            ],
            [
                'attribute' =>'numero_serie',
                'contentOptions' => ['class' => "y_centercenter",'style'=>'width:20%'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'format'=>'raw',
                'value'=>function($model){
                    $ret = '';

                    $ret .= $model->numero_serie;
                    
                    return $ret;
                  },
            ],
            [
                'attribute' =>'realiza_mantenimiento',
                'contentOptions' => ['class' => "y_centercenter",'style'=>'width:10%'],
                'format'=>'raw',
                'value'=>function($model){
                    $ret = '';
                    if($model->realiza_mantenimiento){
                        $ret .= $model->realiza_mantenimiento;
                    }
                    return $ret;
                  },
            ],
            [
                'attribute' =>'fecha',
                'contentOptions' => ['class' => "y_centercenter color6",'style'=>'width:10%;'],
            ],
            [
                'attribute' =>'status_maquina',
                'contentOptions' => ['class' => "y_centercenter",'style'=>''],
                'format'=>'raw',
                'value'=>function($model) use ($statusmaquina){
                    $ret = '';
                   
                    $ret .= $statusmaquina[$model->status_maquina];
                    return $ret;
                  },
            ],
            [
                'attribute' =>'proximo_mantenimiento',
                'contentOptions' => ['class' => "y_centercenter color6",'style'=>'width:10%;'],
                'value'=>function($model){
                    $ret = '';
                    if($model->proximo_mantenimiento){
                        $ret = date('Y-m-d', strtotime($model->proximo_mantenimiento));
                    }
                    return $ret;
                },
                        
            ],
            [
                'attribute' =>'descripcion',
                'label'=>'Descripción',
                'value'=>function($model){
                    $ret = '';
                    if($model->descripcion){
                        $ret .= $model->descripcion;
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
$icon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-buildings-fill" viewBox="0 0 16 16">
<path d="M15 .5a.5.5 0 0 0-.724-.447l-8 4A.5.5 0 0 0 6 4.5v3.14L.342 9.526A.5.5 0 0 0 0 10v5.5a.5.5 0 0 0 .5.5h9a.5.5 0 0 0 .5-.5V14h1v1.5a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5V.5ZM2 11h1v1H2v-1Zm2 0h1v1H4v-1Zm-1 2v1H2v-1h1Zm1 0h1v1H4v-1Zm9-10v1h-1V3h1ZM8 5h1v1H8V5Zm1 2v1H8V7h1ZM8 9h1v1H8V9Zm2 0h1v1h-1V9Zm-1 2v1H8v-1h1Zm1 0h1v1h-1v-1Zm3-2v1h-1V9h1Zm-1 2h1v1h-1v-1Zm-2-4h1v1h-1V7Zm3 0v1h-1V7h1Zm-2-2v1h-1V5h1Zm1 0h1v1h-1V5Z"/>
</svg>';
?>
<div class="mantenimientos-index">

    <h1 class="title1"><?= Html::encode($this->title) ?></h1>

    <div class="row mb-3">
        <div class="col-lg-3 d-grid">
            <?php if(Yii::$app->user->can('usuarios_crear')):?>
            <?= Html::a('<span class="mx-2 small"><i class="bi bi-plus-lg"></i></span>Nuevo Mantenimiento', ['create'], ['class' => 'btn btn-primary btnnew btn-block']) ?>
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
        'headerRowOptions' =>['class' => 'text-uppercase font12 color9'],
        'tableOptions' => ['class' => 'table table-hover table-sm small'],
        'rowOptions' => ['class' => 'font-12 text-600 bg-white shadow-sm small'],
        'pager' => [
            'class' => 'yii\bootstrap5\LinkPager'
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' =>'clave',
                'contentOptions' => ['class' => "y_centercenter",'style'=>''],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'format'=>'raw',
                'value'=>function($model){
                    $ret = '';
                    if($model->clave){
                        $ret .= $model->clave;
                    }
                    return $ret;
                  },
            ],
            [
                'attribute' =>'id_empresa',
                'visible' => $showempresa,
                'contentOptions' => ['class' => "color6 font600 y_centercenter",'style'=>'width:15%'],
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
                'attribute' =>'tipo_mantenimiento',
                'contentOptions' => ['class' => "y_centercenter",'style'=>''],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'filter'=>Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'tipo_mantenimiento',
                    'data' =>$tipomantenimiento,
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
                'value'=>function($model) use ($tipomantenimiento){
                    $ret = '';
                   
                    $ret .= '<span class="badge bgtransparent1 text-dark font12 m-1">'.$tipomantenimiento[$model->tipo_mantenimiento].'</span>';
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
                'visible'=>false,
                "value"=>function($model){
                    $ret = '';

                    if($model->maquina){
                        if(isset($model->maquina->foto) && $model->maquina->foto != ""){
                            $extra = '';
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
                'contentOptions' => ['class' => "y_centercenter",'style'=>'width:20%'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'format'=>'raw',
                'value'=>function($model){
                    $ret = '';
                    if($model->maquina){
                        $ret .= $model->maquina->maquina;

                        $ret .= '<br><div class="p-2 bg-light rounded-3 small text-dark font500">'.$model->maquina->clave.'</div>';

                        $ret .= '<div class="font10">Marca: '.$model->marca.'<br>Modelo: '.$model->modelo.'<br>N° Serie: '.$model->numero_serie.'</div>';
                    }
                    return $ret;
                  },
            ],
            [
                'attribute' =>'realiza_mantenimiento',
                'contentOptions' => ['class' => "y_centercenter",'style'=>'width:10%'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'format'=>'raw',
                'value'=>function($model){
                    $ret = '';
                    if($model->realiza_mantenimiento){
                        $ret .= $model->realiza_mantenimiento;
                    }
                    return $ret;
                  },
            ],
            [
                'attribute' =>'fecha',
                'contentOptions' => ['class' => "y_centercenter color6",'style'=>'width:10%;'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
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
                'attribute' =>'status_maquina',
                'contentOptions' => ['class' => "y_centercenter",'style'=>''],
                'contentOptions'=>function ($model) {
                    if($model->status_maquina == 1){
                        return ['class' => "y_centercenter font600",'style'=>'background-color:#16C47F4b;color:#16C47F;'];
                    } else if($model->status_maquina == 2){
                        return ['class' => "y_centercenter font600",'style'=>'background-color:#ED2B2A4b;color:#ED2B2A;'];
                    } else if($model->status_maquina == 3){
                        return ['class' => "y_centercenter font600",'style'=>'background-color:#E7B10A4b;color:#E7B10A;'];
                    }
                },
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'filter'=>Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'status_maquina',
                    'data' =>$statusmaquina,
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
                'value'=>function($model) use ($statusmaquina){
                    $ret = '';
                   
                    $ret .= $statusmaquina[$model->status_maquina];
                    return $ret;
                  },
            ],
            [
                'attribute' =>'proximo_mantenimiento',
                'contentOptions' => ['class' => "y_centercenter color6",'style'=>'width:10%;'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'filter' => \kartik\daterange\DateRangePicker::widget(
                    [
                        'model' => $searchModel, 
                        'attribute' => 'proximo_mantenimiento', 
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
                            if($model->proximo_mantenimiento){
                                $ret = date('Y-m-d', strtotime($model->proximo_mantenimiento));
                            }
                            return $ret;
                        },
            ],
            [
                'attribute' =>'descripcion',
                'label'=>'Descripción',
                'contentOptions' => ['class' => "y_centercenter",'style'=>'width:20%'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'format'=>'raw',
                'value'=>function($model){
                    $ret = '';
                    if($model->descripcion){
                        $ret .= $model->descripcion;
                    }
                    return $ret;
                  },
            ],
            [
                'attribute' =>'pdf',
                'label'=>'Ficha',
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
                    $ret = Html::a($image, Url::to(['mantenimientos/pdf','id' => $model->id,'firmado'=>1]), $options = ['target'=>'_blank','title' => Yii::t('app', 'Con Firma'),'data-toggle'=>"tooltip",'data-placement'=>"top"]);
                    $ret .= Html::a($image2, Url::to(['mantenimientos/pdf','id' => $model->id,'firmado'=>0]), $options = ['target'=>'_blank','title' => Yii::t('app', 'Sin Firma'),'data-toggle'=>"tooltip",'data-placement'=>"top"]);
                    
                    return $ret;
                },
            ],
            //'realiza_mantenimiento',
            //'descripcion:ntext',
            //'status_maquina',
            //'proximo_mantenimiento',
            //'nombre_firma1',
            //'firma1:ntext',
            //'nombre_firma2',
            //'firma2:ntext',
            //'nombre_firma3',
            //'firma3:ntext',
            //'status',
            //'create_date',
            //'create_user',
            //'update_date',
            //'update_user',
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
                        return Html::a('<span class="color2"><i class="bi bi-eye-fill"></i><span>', Url::to(['view','id' => $model->id]), [
                            'title' => Yii::t('app', 'Ver'),
                            'data-toggle'=>"tooltip",
                            'data-placement'=>"top",
                            'class'=>'btn btn-sm text-center shadow-sm'
                        ]);
                    },
                    'update' =>  function($url,$model) {
                        //return($model->conclusion_cal);
                        if($model->status != 2){
                            return Html::a('<span class=""><i class="bi bi-pen-fill"></i><span>', Url::to(['update','id' => $model->id]), [
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