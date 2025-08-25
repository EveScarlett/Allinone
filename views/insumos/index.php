<?php

use app\models\Insumos;
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

/** @var yii\web\View $this */
/** @var app\models\InsumosSearch $searchModel */
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

$ver_medicamento = true;
$ver_epp = false;
if($tipo == 1){
    $this->title = 'Catálogo de Medicamentos'.$name_empresa;
} else{
    $ver_medicamento = false;
    $ver_epp = true;
    $this->title = 'Catálogo de EPP'.$name_empresa;
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
$colores = [1=>'Negro',2=>'Azul',3=>'Marrón',4=>'Gris',5=>'Verde',6=>'Naranja',7=>'Rosa',8=>'Púrpura',9=>'Rojo',10=>'Blanco',11=>'Amarillo'];
$coloresrgb = [1=>'#000000',2=>'#4CB9E7',3=>'#6B240C',4=>'#A9A9A9',5=>'#3CCF4E',6=>'#FF5B22',7=>'#FF6AC2',8=>'#B931FC',9=>'#C70039',10=>'#F9F5F6',11=>'#FFE382'];
/* $tallas = [1=>'Unitalla',2=>'0',3=>'1',4=>'2',5=>'3',6=>'4',7=>'5',8=>'6',9=>'7',10=>'8',11=>'9',12=>'10',13=>'11',14=>'12',15=>'13',16=>'14',17=>'15',18=>'16',19=>'17'
    ,20=>'18',21=>'19',22=>'20',23=>'21',24=>'22',25=>'23',26=>'24',27=>'25',28=>'26',29=>'27',30=>'28',31=>'29',32=>'30',33=>'31',34=>'32',35=>'33',36=>'34',37=>'35',38=>'36',39=>'37',40=>'38',41=>'39',42=>'40',43=>'41',44=>'42',45=>'43',46=>'44',47=>'45',48=>'46'
    ,49=>'47',50=>'48',51=>'49',52=>'50',53=>'51',54=>'52',55=>'53',56=>'54',57=>'55',58=>'56',59=>'57',60=>'58',61=>'59',62=>'60']; */
    $sexo = [1=>'Masculino',2=>'Femenino',3=>'Unisex'];

    $medidas = [
        '1'=>'MEX 20 | US 0 | EUR 30 | INTER XXS',
        '2'=>'MEX 22 | US 2 | EUR 30 | INTER XXS',
        '3'=>'MEX 26 | US 6 | EUR 32 | INTER S',
        '4'=>'MEX 28 | US 8 | EUR 34 | INTER M',
        '5'=>'MEX 30 | US 10 | EUR 34 | INTER M',
        '6'=>'MEX 32 | US 12 | EUR 36 | INTER L',
        '7'=>'MEX 36 | US 14 | EUR 36 | INTER L',
        '8'=>'MEX 38 | US 16 | EUR 38 | INTER XL',
        '9'=>'MEX 40 | US 18 | EUR 38 | INTER XL',
        '10'=>'MEX 42 | US 18 | EUR 40 | INTER XXL',
        '11'=>'MEX 44 | US 20 | EUR 40 | INTER XXL',
    ];
    $medidascabezamano = ['100'=>'XXS','101'=>'S','102'=>'M','103'=>'L','104'=>'XL','105'=>'XXL'];
    $medidascalzado = ['200'=>'2','201'=>'2.5','202'=>'3','203'=>'3.5','204'=>'4','205'=>'4.5','206'=>'5','207'=>'5.5','208'=>'6','209'=>'6.5','210'=>'7','211'=>'7.5','212'=>'8','213'=>'8.5','214'=>'9','215'=>'9.5','216'=>'10','217'=>'10.5','218'=>'11','219'=>'11.5','220'=>'12','221'=>'12.5','222'=>'13'];
    $tallas = $medidas+$medidascabezamano+$medidascalzado;
    //dd($tallas);
?>
<div class="insumos-index">

    <h1 class="title1"><?= Html::encode($this->title) ?></h1>

    <?php 
    $fullExportMenu = '';
    if(($tipo == 1 && Yii::$app->user->can('medicamentos_exportar')) || ($tipo == 2 && Yii::$app->user->can('epp_exportar'))){
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
                'attribute' =>'nombre_comercial',
                'value'=>function($model){
                    $ret = '';
                    if($model->nombre_comercial){
                        $ret = $model->nombre_comercial;

                    }
                    $ret .= $model->nombre_generico;
                    return $ret;
                  },
            ],
            [
                'attribute' =>'stock_minimo',
                'value'=>function($model){
                    $ret = '--';
                    if($model->stock_minimo != null && $model->stock_minimo != '' && $model->stock_minimo != ' '){
                        $ret .= 'TODOS LOS CONSULTORIOS: '.$model->stock_minimo;
                    }

                    if($model->stockconsultorios){
                        foreach($model->stockconsultorios as $key=>$insumo){
                            if($insumo->consultorio){
                                $ret .= $insumo->consultorio->consultorio.': '.$insumo->stock_unidad;
                            }
                            if($key < (count($model->stockconsultorios)-1)){
                                $ret .= ',';
                            }
                        }
                    }

                    return $ret;
                  },
            ],
            [
                'attribute' =>'id_presentacion',
                'value'=>function($model){
                    $ret = '';

                    if($model->presentacion){
                        $ret =  $model->presentacion->presentacion;
                    }
                   
                    return $ret;
                },
            ],
            [
                'attribute' =>'id_unidad',
                'visible' => $ver_medicamento,
                'value'=>function($model){
                    $ret = '';

                    if($model->unidad){
                        $ret =  $model->unidad->unidad;
                    }
                   
                    return $ret;
                },
            ],
            [
                'attribute' =>'fabricante',
                'visible' => $ver_epp,
                'value'=>function($model){
                    $ret = '';

                    if($model->fabricante){
                        $ret =  $model->fabricante;
                    }
                   
                    return $ret;
                },
            ],
            [
                'attribute' =>'talla',
                'visible' => $ver_epp,
                'value'=>function($model) use ($tallas){
                    $ret = '';

                    if($model->talla){
                        $ret =  $tallas[$model->talla];
                    }
                   
                    return $ret;
                },
            ],
            [
                'attribute' =>'sexo',
                'visible' => $ver_epp,
                'value'=>function($model) use ($sexo){
                    $ret = '';

                    if($model->sexo){
                        $ret =  $sexo[$model->sexo];
                    }
                   
                    return $ret;
                },
            ],
            [
                'attribute' =>'color',
                'visible' => $ver_epp,
                'value'=>function($model) use ($colores,$coloresrgb){
                    $ret = '';

                    if($model->color){
                        $ret =  $colores[$model->color];
                    }
                   
                    return $ret;
                },
            ],
            [
                'attribute' =>'unidades_individuales',
                'visible' => $ver_medicamento,
                'value'=>function($model){
                    $ret = '--';
                    if($model->unidades_individuales){
                        $ret = $model->unidades_individuales .' unidades';
                    }
                    return $ret;
                  },
            ],
            [
                'attribute' =>'via_administracion',
                'visible' => $ver_medicamento,
                'value'=>function($model){
                    $ret = '';

                    if($model->viasadministracion){
                        $ret =  $model->viasadministracion->via_administracion;
                    }
                   
                    return $ret;
                },
            ],
            [
                'attribute' =>'formula',
                'visible' => $ver_medicamento,
                'value'=>function($model){
                    $ret = '--';
                    if($model->formula){
                        $ret = $model->formula;
                    }
                    return $ret;
                  },
            ],
            [
                'attribute' =>'advertencias',
                'visible' => $ver_medicamento,
                'value'=>function($model){
                    $ret = '--';
                    if($model->advertencias){
                        $ret = $model->advertencias;
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
            <?php if($tipo == 1):?>
            <?php if(Yii::$app->user->can('medicamentos_crear')):?>
            <?= Html::a('<span class="mx-2 small"><i class="bi bi-plus-lg"></i></span>Nuevo Medicamento', ['create','tipo'=>$tipo], ['class' => 'btn btn-primary btnnew btn-block']) ?>
            <?php endif;?>
            <?php else:?>
            <?php if(Yii::$app->user->can('epp_crear')):?>
            <?= Html::a('<span class="mx-2 small"><i class="bi bi-plus-lg"></i></span>Nuevo EPP', ['create','tipo'=>$tipo], ['class' => 'btn btn-primary btnnew btn-block']) ?>
            <?php endif;?>
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
    if(($tipo == 1 && Yii::$app->user->can('medicamentos_ver')) || ($tipo == 2 && Yii::$app->user->can('epp_ver'))){
        $template .='{view}';
    }
    if(($tipo == 1 && Yii::$app->user->can('medicamentos_actualizar')) || ($tipo == 2 && Yii::$app->user->can('epp_actualizar'))){
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
                'attribute'=>'logo',
                'format'=>'raw',
                'label'=>'',
                'headerOptions'=>['style'=>'width:5%;','class'=>'font12'],
                'contentOptions'=>['class'=>'font500 y_centercenter borderblue' ,'style'=>'vertical-align: middle;'],
                'filterInputOptions' => ['class' => 'icon-find form-control','placeholder'=>'Buscar...'],
                'visible'=>true,
                "value"=>function($model){
                    $ret = ''.$model->foto;
                    $carpeta = 'Medicamentos';
                    if($model->tipo == 2){
                        $carpeta = 'EPP';
                    }
                    
                    if(isset($model->foto) && $model->foto != ""){
                        $ret = '';
                        $filePath =  '/resources/Empresas/'.$model->id_empresa.'/'.$carpeta.'/'.$model->id.'/'.$model->foto;
                        $foto = Html::img('@web'. $filePath, ['alt'=>' ', 'class' => "iconphoto img-responsive", 'width' => '35px', 'height' =>'auto','style'=>'box-shadow: rgba(50, 50, 93, 0.25) 0px 13px 27px -5px, rgba(0, 0, 0, 0.3) 0px 8px 16px -8px; height:auto; width:35px;']);
                        $ret = $foto;
                    }

                    return $ret;
                }
            ],
            [
                'attribute' =>'nombre_comercial',
                'contentOptions' => ['class' => "y_centercenter color3 font600",'style'=>'width:20%'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'format'=>'raw',
                'value'=>function($model){
                    $ret = '';
                    if($model->nombre_comercial){
                        $ret = $model->nombre_comercial;

                    }
                    $ret .= '<br><div class="p-2 bg-light rounded-3 small text-dark font500">'.$model->nombre_generico.'</div>';
                    return $ret;
                  },
            ],
            [
                'attribute' =>'stock_minimo',
                'contentOptions' => ['class' => "y_centercenter",'style'=>'width:25%'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'format'=>'raw',
                'value'=>function($model){
                    $ret = '--';
                    if($model->stock_minimo != null && $model->stock_minimo != '' && $model->stock_minimo != ' '){
                        $ret = '<div class="font600 border-bottom">TODOS LOS CONSULTORIOS: '.$model->stock_minimo.'</div>';
                    }

                    if($model->stockconsultorios){
                        foreach($model->stockconsultorios as $key=>$insumo){
                            if($insumo->consultorio){
                                $ret .= '<br><span class="small">'.$insumo->consultorio->consultorio.': '.$insumo->stock_unidad.'</span>';
                            }
                        }
                    }

                    return $ret;
                  },
            ],
            /* [
                'attribute' =>'nombre_generico',
                'contentOptions' => ['class' => "y_centercenter",'style'=>'width:15%'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'format'=>'raw',
                'value'=>function($model){
                    $ret = '';
                    if($model->nombre_generico){
                        $ret = $model->nombre_generico;
                    }
                    return $ret;
                  },
            ], */
           
            [
                'attribute' =>'id_presentacion',
                'format'=>'raw',
                'contentOptions' => ['class' => "y_centercenter borderblue text-center",'style'=>''],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'filter'=>Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'id_presentacion',
                    'data' =>$presentaciones,
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

                    if($model->presentacion){
                        $ret =  '<span class="badge bgtransparent1 text-dark font12 m-1">'.$model->presentacion->presentacion.'</span>';
                    }
                   
                    return $ret;
                },
            ],
            [
                'attribute' =>'id_unidad',
                'format'=>'raw',
                'visible' => $ver_medicamento,
                'contentOptions' => ['class' => "y_centercenter color3 text-center",'style'=>''],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'filter'=>Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'id_unidad',
                    'data' =>$presentaciones,
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

                    if($model->unidad){
                        $ret =  $model->unidad->unidad;
                    }
                   
                    return $ret;
                },
            ],
            [
                'attribute' =>'fabricante',
                'format'=>'raw',
                'visible' => $ver_epp,
                'contentOptions' => ['class' => "y_centercenter text-center",'style'=>''],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                
                'value'=>function($model){
                    $ret = '';

                    if($model->fabricante){
                        $ret =  $model->fabricante;
                    }
                   
                    return $ret;
                },
            ],
            [
                'attribute' =>'talla',
                'format'=>'raw',
                'visible' => $ver_epp,
                'contentOptions' => ['class' => "y_centercenter text-center",'style'=>''],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'filter'=>Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'talla',
                    'data' =>$tallas,
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'hideSearch' => false,
                    'options' => [
                        'placeholder' => 'Buscar...',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]),
                'value'=>function($model) use ($tallas){
                    $ret = '';

                    if($model->talla){
                        $ret =  $tallas[$model->talla];
                    }
                   
                    return $ret;
                },
            ],
            [
                'attribute' =>'sexo',
                'format'=>'raw',
                'visible' => $ver_epp,
                'contentOptions' => ['class' => "y_centercenter color3 text-center",'style'=>''],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'filter'=>Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'sexo',
                    'data' =>$sexo,
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'hideSearch' => false,
                    'options' => [
                        'placeholder' => 'Buscar...',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]),
                'value'=>function($model) use ($sexo){
                    $ret = '';

                    if($model->sexo){
                        $ret =  $sexo[$model->sexo];
                    }
                   
                    return $ret;
                },
            ],
            [
                'attribute' =>'color',
                'format'=>'raw',
                'visible' => $ver_epp,
                'contentOptions' => ['class' => "y_centercenter text-center",'style'=>''],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'filter'=>Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'color',
                    'data' =>$colores,
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'hideSearch' => false,
                    'options' => [
                        'placeholder' => 'Buscar...',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]),
                'value'=>function($model) use ($colores,$coloresrgb){
                    $ret = '';

                    if($model->color){
                        $ret =  '<span class="mx-2" style="color:'.$coloresrgb[$model->color].';"><i class="bi bi-circle-fill"></i></span>'.$colores[$model->color];
                    }
                   
                    return $ret;
                },
            ],
            [
                'attribute' =>'unidades_individuales',
                'visible' => $ver_medicamento,
                'contentOptions' => ['class' => "y_centercenter text-center font600",'style'=>'width:10%'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'format'=>'raw',
                'value'=>function($model){
                    $ret = '--';
                    if($model->unidades_individuales){
                        $ret = $model->unidades_individuales .' unidades';
                    }
                    return $ret;
                  },
            ],
            [
                'attribute' =>'via_administracion',
                'format'=>'raw',
                'visible' => $ver_medicamento,
                'contentOptions' => ['class' => "y_centercenter text-center",'style'=>''],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'filter'=>Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'via_administracion',
                    'data' =>$vias,
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

                    if($model->viasadministracion){
                        $ret =  $model->viasadministracion->via_administracion;
                    }
                   
                    return $ret;
                },
            ],
            [
                'attribute' =>'formula',
                'visible' => $ver_medicamento,
                'contentOptions' => ['class' => "y_centercenter color6",'style'=>'width:12%'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'format'=>'raw',
                'value'=>function($model){
                    $ret = '--';
                    if($model->formula){
                        $ret = '<div class="p-2 bg-light rounded-3 small font500">'.$model->formula.'</div>';
                    }
                    return $ret;
                  },
            ],
            [
                'attribute' =>'advertencias',
                'visible' => $ver_medicamento,
                'contentOptions' => ['class' => "y_centercenter color11",'style'=>'width:12%'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'format'=>'raw',
                'visible'=>false,
                'value'=>function($model){
                    $ret = '--';
                    if($model->advertencias){
                        $ret = '<div class="p-2 bg-light rounded-3 small font500">'.$model->advertencias.'</div>';
                    }
                    return $ret;
                  },
            ],
            //'foto',
            //'concentracion',
            //'fabricante',
            //'formula',
            //'condiciones_conservacion',
            //'id_presentacion',
            //'id_unidad',
            //'cantidad',
            //'create_date',
            //'create_user',
            //'update_date',
            //'update_user',
            //'delete_date',
            //'delete_user',
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
                    'view' =>  function($url,$model) use ($tipo) {
                        return Html::a('<span class="color2"><i class="bi bi-eye-fill"></i><span>', Url::to(['insumos/view','id' => $model->id,'tipo'=>$tipo]), [
                            'title' => Yii::t('app', 'Ver'),
                            'data-toggle'=>"tooltip",
                            'data-placement'=>"top",
                            'class'=>'btn btn-sm text-center shadow-sm'
                        ]);
                    },
                    'update' =>  function($url,$model) use ($tipo) {
                        return Html::a('<span class=""><i class="bi bi-pen-fill"></i><span>', Url::to(['insumos/update','id' => $model->id,'tipo'=>$tipo]), [
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