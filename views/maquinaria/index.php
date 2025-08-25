<?php

use app\models\Maquinaria;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use kartik\export\ExportMenu;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\bootstrap5\Modal;



use app\models\Empresas;
use app\models\Areas;
use app\models\Ubicaciones;
use app\models\Riesgos;
use app\models\Mantenimientos;
/** @var yii\web\View $this */
/** @var app\models\MaquinariaSearch $searchModel */
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

$this->title = Yii::t('app', 'Maquinarias'.$name_empresa);
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
$areas = ArrayHelper::map(Areas::find()->where(['in','id_empresa',$ids_empresas])->orderBy('area')->all(), 'id', 'area');
$ubicaciones = ArrayHelper::map(Ubicaciones::find()->where(['in','id_empresa',$ids_empresas])->orderBy('ubicacion')->all(), 'id', 'ubicacion');
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
    $template = '';
    if(true){
        $template .='{view}';
    }
    if(true){
        $template .='{update}';
    }
    ?>

<?php
$empresas = explode(',', Yii::$app->user->identity->empresas_select);
        
if(Yii::$app->user->identity->empresa_all != 1) {
    $empresas = ArrayHelper::map(Empresas::find()->where(['in','id',$empresas])->orderBy('comercial')->all(), 'id', 'comercial');
} else{
    $empresas = ArrayHelper::map(Empresas::find()->orderBy('comercial')->all(), 'id', 'comercial');
}

$ver_qr = false;
if(Yii::$app->user->identity->empresa && Yii::$app->user->identity->empresa->configuracion->verqr_maquina == 1){
    $ver_qr = true;
}

?>


<?php 
      Modal::begin([
        'id' =>'modal-qr',
        'title' => '<h5 class="text-uppercase text-purple">
                        QR Maquinaria
                    </h5>',
        'size' => 'modal-sm',
        'headerOptions' =>[
            'class' => 'text-light bg-mymodal btnnew'
        ],
        ]);
        echo '<div id="body-qr"></div>';
        Modal::end();
?>


<?php 
  if(Yii::$app->user->can('usuarios_exportar')){
    $fullExportMenu = ExportMenu::widget([
        'dataProvider' => $dataProvider,
        'columns' =>[
            ['class' => 'yii\grid\SerialColumn'],
           
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
<div class="maquinaria-index">

    <h1 class="title1"><?= Html::encode($this->title) ?></h1>

    <div class="row mb-3">
        <div class="col-lg-3 d-grid">
            <?php if(Yii::$app->user->can('usuarios_crear')):?>
            <?= Html::a('<span class="mx-2 small"><i class="bi bi-plus-lg"></i></span>Nueva Maquinaria', ['create'], ['class' => 'btn btn-primary btnnew btn-block']) ?>
            <?php endif;?>
        </div>

        <div class="col-lg-9 text-end">
            <?php
        echo $fullExportMenu;
        ?>
        </div>
    </div>

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
                        $extra = '';
                        /* $filePath = $extra.'/resources/Maquinarias/'.$model->id.'/'.$model->foto; */
                        $filePath = $extra.'/web/resources/Maquinarias/'.$model->id.'/'.$model->foto;

                        $imagen = 'background: url(..'.$filePath.');background-size:cover;';
                        $ret .= "<div class='icon-cube p-2 mt-2' style='".$imagen."'></div>";
                    } else{
                        $imagen = '';
                        $ret .= "<div class='icon-cube p-2 mt-2'>".$imagen."</div>";
                    }

                    return $ret;
                }
            ],
            [
                'attribute' =>'maquina',
                'contentOptions' => ['class' => "color3 font600",'style'=>'width:15%'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'format'=>'raw',
                'value'=>function($model){
                    $ret = '';
                    $ret .= $model->maquina;

                    $ret .= '<br><div class="p-2 bg-light rounded-3 small text-dark font500">'.$model->clave.'</div>';
                    return $ret;
                  },
            ],
            [
                'attribute' =>'status_operacion',
                'label'=>'Status Operación',
                'contentOptions' => ['class' => "y_centercenter",'style'=>''],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'filter'=>Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'status_operacion',
                    'data' =>['1'=>'EN OPERACIÓN','2'=>'STOP'],
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

                    if( $model->status_operacion == 1){
                        $ret =  '<div class="row badge bgnocumple m-1 font14 text-light text-uppercase">EN OPERACIÓN</div>';
                        $fecha_inicio = date('Y-m-d', strtotime($model->fecha_inicio));
                        $hora_inicio = date('H:i A', strtotime($model->fecha_inicio));
                        $ret .= '<br><div class="p-2 bg-light rounded-3 small text-dark font500">'.$fecha_inicio.'<br>'.$hora_inicio.'</div>';
                    } else if( $model->status_operacion == 2){
                        $ret =  '<div class="row badge bgcumple m-1 font14 text-light text-uppercase">STOP</div>';
                    }
                   
                    return $ret;
                  },
            ],
            [
                'attribute' =>'riesgos',
                'contentOptions' => ['class' => "color6 font600",'style'=>'width:12%'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'headerOptions' => ['class' => "y_centercenter",'style'=>'color: #716F81;
                text-decoration:none;
                font-weight: 500;
                font-size: 11px;'],
                'format'=>'raw',
                'value'=>function($model){
                    $ret = '';
                    if($model->riesgos){
                        foreach($model->riesgos as $key=>$riesgo){
                            $ret.= '<span class="badge bgtransparent1 text-dark font12 m-1">'.$riesgo->riesgo.'</span>';
                        }
                    }
                    return $ret;
                  },
            ],
            [
                'attribute' =>'id_empresa',
                'visible' => $showempresa,
                'contentOptions' => ['class' => "color6 font600",'style'=>'width:15%'],
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
                'attribute' =>'fabricante',
                'contentOptions' => ['class' => "y_centercenter",'style'=>''],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'value'=>function($model){
                    $ret = '';

                    if(isset($model->fabricante) && $model->fabricante != ''){
                        $ret = $model->fabricante;
                    }
                    return $ret;
                },
            ],
            [
                'attribute' =>'modelo',
                'contentOptions' => ['class' => "y_centercenter",'style'=>''],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'value'=>function($model){
                    $ret = '';

                    if(isset($model->modelo) && $model->modelo != ''){
                        $ret = $model->modelo;
                    }
                    return $ret;
                },
            ],
            [
                'attribute' =>'marca',
                'contentOptions' => ['class' => "y_centercenter",'style'=>''],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'value'=>function($model){
                    $ret = '';

                    if(isset($model->marca) && $model->marca != ''){
                        $ret = $model->marca;
                    }
                    return $ret;
                },
            ],

            
            [
                'attribute' =>'id_area',
                'label'=>'Área',
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
                'attribute' =>'id_ubicacion',
                'label'=>'Ubicación',
                'contentOptions' => ['class' => "y_centercenter color3",'style'=>''],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'filter'=>Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'id_ubicacion',
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
                    if(isset($model->ubicacion)){
                        $ret = $model->ubicacion->ubicacion;
                    }
                    return $ret;
                 },
            ],
            [
                'attribute' =>'qr',
                'label'=>'QR',
                'visible'=>$ver_qr,
                'contentOptions' => ['class' => "y_centercenter font500 font-11",'style'=>'width:10%'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'format'=>'raw',
                'value'=>function($model){
                    $ret = '';

                    if($model->qr){
                       
                        $iconqr = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-qr-code" viewBox="0 0 16 16">
                        <path d="M2 2h2v2H2z"/>
                        <path d="M6 0v6H0V0zM5 1H1v4h4zM4 12H2v2h2z"/>
                        <path d="M6 10v6H0v-6zm-5 1v4h4v-4zm11-9h2v2h-2z"/>
                        <path d="M10 0v6h6V0zm5 1v4h-4V1zM8 1V0h1v2H8v2H7V1zm0 5V4h1v2zM6 8V7h1V6h1v2h1V7h5v1h-4v1H7V8zm0 0v1H2V8H1v1H0V7h3v1zm10 1h-1V7h1zm-1 0h-1v2h2v-1h-1zm-4 0h2v1h-1v1h-1zm2 3v-1h-1v1h-1v1H9v1h3v-2zm0 0h3v1h-2v1h-1zm-4-1v1h1v-2H7v1z"/>
                        <path d="M7 12h1v3h4v1H7zm9 2v2h-3v-1h2v-1z"/>
                        </svg>';

                        $filePath =  '/web'.'/qrsmaquinas/'.$model->id.'/'.$model->id.'qr.png';
                        /* $filePath =  '/qrsmaquinas/'.$model->id.'/'.$model->id.'qr.png'; */

                        $imagen = 'background: url(..'.$filePath.');background-size:cover;';
                        $retimagen = "<label class='mini-cube p-2 puntero' style='".$imagen."'></label>";

                        $botonlink = '<button type="button" class="btn btn-sm text-center shadow-sm font10" onclick="verQrmaquina('.$model->id.')">'.$retimagen.'</button>';
                        $ret .= '<div class="text-center">'.$botonlink.'</div>';

                    } else{
                        $imagen = '';
                        $ret = "<label class='big-cube p-2 puntero'>".$imagen."</label>";
                    }

                    return $ret;
                },
            ],
            [
                'attribute' =>'fecha_mantenimiento',
                'contentOptions' => ['class' => "y_centercenter color6",'style'=>'width:10%;'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'filter' => \kartik\daterange\DateRangePicker::widget(
                    [
                        'model' => $searchModel, 
                        'attribute' => 'fecha_mantenimiento', 
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

                        if($model->fecha_mantenimiento != null && $model->fecha_mantenimiento != '' && $model->fecha_mantenimiento != ' '){
                            $ret = date('Y-m-d', strtotime($model->fecha_mantenimiento));
                        }
                        
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
                    if($model->proximo_mantenimiento != null && $model->proximo_mantenimiento != '' && $model->proximo_mantenimiento != ' '){
                        $ret = date('Y-m-d', strtotime($model->proximo_mantenimiento));
                    }
                    return $ret;
                },
            ],
            [
                'attribute' =>'status',
                'contentOptions' => ['class' => "",'style'=>''],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'filter'=>Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'status',
                    'data' =>['1'=>'Activo','0'=>'Baja'],
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

                    if($model->status == 0){
                        $ret =  '<span class="badge bgcolor6 text-light font11"><span class="color11 mx-2" style=""><i class="bi bi-x"></i></span>Baja</span>';
                    }else if( $model->status == 1){
                        $ret =  '<span class="badge bgcolor3 text-light font11"><span class="color7 mx-2" style=""><i class="bi bi-check"></i></span>Activo</span>';
                    }
                   
                    return $ret;
                },
            ],
            //'combustible',
            //'id_ubicacion',
            //'id_area',
            //'peso',
            //'altura',
            //'ancho',
            //'largo',
            //'alto',
            //'detalles_tecnicos',
            //'funcion',
            //'qr',
            //'status',
            //'create_date',
            //'create_user',
            //'update_date',
            //'update_user',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}{update}',
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