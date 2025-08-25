<?php

use app\models\Ordenespoes;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use kartik\export\ExportMenu;
use app\models\Empresas;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\Servicios;

/** @var yii\web\View $this */
/** @var app\models\OrdenespoesSearch $searchModel */
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

$this->title = Yii::t('app', 'Ordenes de Trabajo - POE'.$name_empresa);
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
$estudios = ArrayHelper::map(Servicios::find()->orderBy('nombre')->all(), 'id', 'nombre');
?>
<?php
    $template = '';
    if(Yii::$app->user->can('ordenpoe_ver')){
        $template .='{view}';
    }
    if(1==2){
        $template .='{update}';
    }
    ?>
<div class="ordenespoes-index">

    <h1 class="title1"><?= Html::encode($this->title) ?></h1>

    <?php 
      if(Yii::$app->user->can('ordenpoe_exportar')){
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
                    'attribute' =>'anio',
                ],
                [
                    'attribute' =>'estudios',
                    'label'=>'Paquete de Estudios',
                    'value'=>function($model){
                        $ret = '';
                       
                        if(isset($model->estudios)){
    
                            foreach($model->estudios as $key=>$estudio){
    
                                if($estudio->estudio){
                                    $ret .=  $estudio->estudio->nombre;
                                }
                                if($key < (count($model->estudios)-1)){
                                    $ret .= ',';
                                }
    
                            }
    
                        } else{
                            $ret = 'SIN ESTUDIOS';
                        }
                        
                        return $ret;
                      },
                ],
                [
                    'attribute' =>'trabajadores',
                    'label'=>'Trabajadores',
                    'value'=>function($model){
                        $ret = '';
                        if(isset($model->trabajadores)){
                            $ret = count($model->trabajadores).' trabajadores';
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
            <?php if(Yii::$app->user->can('ordenpoe_crear')):?>
            <?= Html::a('<span class="mx-2 small"><i class="bi bi-plus-lg"></i></span>Nueva Orden POE', ['create'], ['class' => 'btn btn-primary btnnew btn-block']) ?>
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
                'attribute' =>'anio',
                'contentOptions' => ['class' => "y_centercenter",'style'=>''],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'format'=>'raw',
                'value'=>function($model){
                    $ret = '';
                
                    $ret .= '<span class="badge bgtransparent1 text-dark font12 m-1">'.$model->anio.'</span>';
                    return $ret;
                  },
            ],
            [
                'attribute' =>'create_date',
                'contentOptions' => ['class' => "y_centercenter color6",'style'=>'width:10%;'],
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
                'attribute' =>'create_user',
                'contentOptions' => ['class' => "y_centercenter",'style'=>'width:15%'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'format'=>'raw',
                'value'=>function($model){
                    $ret = '';
                    if($model->uCaptura){
                        $ret = $model->uCaptura->name;
                    }
                    return $ret;
                  },
            ],
            [
                'attribute' =>'estudios',
                'label'=>'Paquete de Estudios',
                'contentOptions' => ['class' => "y_centercenter",'style'=>'width:40%;'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'headerOptions' => ['class' => "y_centercenter",'style'=>'color: #716F81;
                text-decoration:none;
                font-weight: 500;
                font-size: 11px;'],
                'filter'=>Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'estudios',
                    'data' =>$estudios,
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
                   
                    if(isset($model->estudios)){
                        $ret = '<table class="table table-bordered table-hover table-sm text-little">';
                        $ret .= '<thead class="table-dark"><tr class="text-center">
                        <th width="30%">Categoria</th>
                        <th width="40%">Estudio</th>
                        </tr></thead><tbody>';

                        foreach($model->estudios as $key=>$estudio){
                            $pdf = '';
                            $pdf2 = '';
                            $tipo = '';
                            $nombre = '';
                            $diagnostico = '';
                            $evolucion = '';
                            $color = '#FFFFFF';

                            if($estudio->categoria){
                                $tipo = $estudio->categoria->nombre;
                                $color =$estudio->categoria->logo;
                            }

                            if($estudio->estudio){
                                $nombre =  $estudio->estudio->nombre;
                            }
                            

                            $ret .= '<tr>
                            <td colspan="1" class="" style="background-color:'.$color.'8e;">'.$tipo.'</td>
                            <td colspan="1" class="">'.$nombre.'</td>
                            </tr>';

                        }

                        $ret .= '</tbody></table>';

                    } else{
                        $ret = '<span class="color6">SIN ESTUDIOS</span>';
                    }
                    
                    return $ret;
                  },
            ],
            [
                'attribute' =>'trabajadores',
                'label'=>'Trabajadores',
                'contentOptions' => ['class' => "y_centercenter color3 font600",'style'=>''],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'headerOptions' => ['class' => "y_centercenter",'style'=>'color: #716F81;
                text-decoration:none;
                font-weight: 500;
                font-size: 11px;'],
                'value'=>function($model){
                    $ret = '';
                    if(isset($model->trabajadores)){
                        $ret = count($model->trabajadores).' trabajadores';
                    }
                    return $ret;
                 },
            ],
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
                        return Html::a('<span class="color2"><i class="bi bi-eye-fill"></i><span>', Url::to(['ordenespoes/view','id' => $model->id]), [
                            'title' => Yii::t('app', 'Ver'),
                            'data-toggle'=>"tooltip",
                            'data-placement'=>"top",
                            'class'=>'btn btn-sm text-center shadow-sm'
                        ]);
                    },
                    'update' =>  function($url,$model) {
                        return Html::a('<span class=""><i class="bi bi-pen-fill"></i><span>', Url::to(['ordenespoes/update','id' => $model->id]), [
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