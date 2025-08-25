<?php

use app\models\Trabajadores;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use kartik\export\ExportMenu;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\bootstrap5\Modal;

use app\models\Trabajadorestudio;
use app\models\Areas;
use app\models\Puestostrabajo;
use app\models\Riesgos;
use app\models\Empresas;
use app\models\Epps;

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
$this->title = Yii::t('app', 'Vencimiento de Documentos'.$name_empresa);
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

$antiguedades =['1'=>'≤ 1 mes','2'=>'≤ 3 meses','3'=>'≤ 6 meses','4'=>'≤ 9 meses','5'=>'≤ 1 año','6'=>'≤ 1 año 3 meses','7'=>'≤ 1 año 6 meses','8'=>'≤ 1 año 9 meses','9'=>'≤ 2 años','10'=>'≤ 3 años','11'=>'≤ 4 años','12'=>'≤ 5 años','13'=>'≥ 5 años'];
        
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
$puestos = ArrayHelper::map(Puestostrabajo::find()->where(['in','id_empresa',$ids_empresas])->orderBy('nombre')->all(), 'id', 'nombre');
$riesgos = ArrayHelper::map(Riesgos::find()->orderBy('riesgo')->all(), 'id', 'riesgo');
$epps = ArrayHelper::map(Epps::find()->orderBy('epp')->all(), 'id', 'epp');
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
    $template = '';
   
    if(Yii::$app->user->can('trabajadores_expediente')){
        $template .='{doc}';
    }
    
    ?>

<?php 
  if(Yii::$app->user->can('usuarios_exportar')){
    $fullExportMenu = ExportMenu::widget([
        'dataProvider' => $dataProvider,
        'columns' =>[
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' =>'num_trabajador',
                'label'=>'N°',
                'contentOptions' => ['class' => "y_centercenter",'style'=>''],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'value'=>function($model){
                   return $model->num_trabajador;
                },
            ],
            [
                'attribute' =>'nombre',
                'label'=>'Nombre Trabajador',
                'contentOptions' => ['class' => "y_centercenter color3 font600",'style'=>''],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'value'=>function($model){
                   return $model->nombre.' '.$model->apellidos;
                },
            ],
            [
                'attribute' =>'status',
                'label'=>'Condición',
                'format'=>'raw',
                'contentOptions' => ['class' => "y_centercenter",'style'=>''],
                'value'=>function($model){
                    $ret = '';

                    if($model->status == 2){
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
                'attribute' =>'id_empresa',
                'visible' => $showempresa,
                'contentOptions' => ['class' => "y_centercenter color6 font600",'style'=>'width:10%'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
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
                'attribute' =>'id_puesto',
                'label'=>'Puesto',
                'contentOptions' => ['class' => "y_centercenter",'style'=>''],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'format'=>'raw',
                'value'=>function($model){
                    $ret = '';
                    if(isset($model->puesto)){
                        $ret = $model->puesto->nombre;
                    }
                    return $ret;
                 },
            ],
            [
                'attribute' =>'vencimientos',
                'label'=>'DOCUMENTOS - VENCIMIENTOS',
                'contentOptions' => ['class' => "",'style'=>'width:40%;'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'format'=>'raw',
                'value'=>function($model) use ($searchModel){
                    $ret = '';
                   
                    if($model->testudios){
                        
                        $ret = '';

                        $total_estudios = count($model->testudios);

                        $index = 1;

                        $estudios = [];
                        if($searchModel->vencimientos != null && $searchModel->vencimientos != ''){
                            $fechas = explode(' - ', $searchModel->vencimientos);
                            $fechas[0] = $fechas[0];
                            $fechas[1] = $fechas[1];
                            $estudios = Trabajadorestudio::find()->where(['id_trabajador'=>$model->id])->andWhere(['between', 'fecha_vencimiento', $fechas[0], $fechas[1]])->all();
                        } else {
                            $estudios = $model->testudios;
                        }
                        foreach($estudios as $key=>$estudio){
                            $nombreestudio = '';
                            $fecha_vencimiento = '';
                            if($estudio->estudio){
                                $nombreestudio = $estudio->estudio->estudio;
                            }

                            if($estudio->id_periodicidad == 0){
                                $fecha_vencimiento = 'INDEFINIDO';
                            } else {
                                $fecha_vencimiento = $estudio->fecha_vencimiento;
                            }

                            $ret .= $nombreestudio.' ('.$fecha_vencimiento.'),';
                        
                        }

                    } else{
                        $ret = 'SIN DOCUMENTOS';
                    }
                    
                    return $ret;
                  },
            ],
            [
                'attribute' =>'status_documentos',
                'label'=>'Status Docs.',
                'contentOptions' => ['class' => "y_centercenter",'style'=>''],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'format'=>'raw',
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
<div class="trabajadores-index">

    <h1 class="title1 text-uppercase"><?= Html::encode($this->title) ?></h1>

    <div class="row mb-3">
        <div class="col-lg-8">
            <?php
            $iconcalcular = '<i class="bi bi-calculator"></i>';
            $calcular = Html::a('<span class="color1">'.$iconcalcular.'<span>Calcular Vencidos', ['getexpired'], [
                'data' => [
                    'confirm' => Yii::t('app', '¿Seguro que desea Calcular los vencimientos?'),
                    'method' => 'post',
                ],
                'title' => Yii::t('app', 'Calcular vencimientos'),
                'data-bs-toggle'=>"tooltip",
                'data-bs-placement'=>"top",
                'class'=>'btn btn-sm text-center shadow-sm'
            ]);
            echo $calcular;
            ?>
        </div>
        <div class="col-lg-4 text-end">
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
                'attribute' =>'num_trabajador',
                'label'=>'N°',
                'contentOptions' => ['class' => "y_centercenter",'style'=>''],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'value'=>function($model){
                   return $model->num_trabajador;
                },
            ],
            [
                'attribute'=>'foto_qr',
                'format'=>'raw',
                'label'=>'',
                'headerOptions'=>['style'=>'width:5%;','class'=>'font12'],
                'contentOptions'=>['class'=>'font500 y_centercenter' ,'style'=>'vertical-align: middle;'],
                'filterInputOptions' => ['class' => 'icon-find form-control','placeholder'=>'Buscar...'],
                'visible'=>false,
                "value"=>function($model){
                    $ret = '';

                    if(isset($model->foto) && $model->foto != ""){
                        $extra = '/web';
                        $filePath = $extra.'/resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id.'/Documentos/'.$model->foto;
                        /* $filePath = $extra.'/web/resources/Empresas/'.$model->trabajador->id_empresa.'/Trabajadores/'.$model->trabajador->id.'/Documentos/'.$model->trabajador->foto; */

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
                'attribute' =>'nombre',
                'label'=>'Nombre Trabajador',
                'contentOptions' => ['class' => "y_centercenter color3 font600",'style'=>''],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'value'=>function($model){
                   return $model->nombre.' '.$model->apellidos;
                },
            ],
            [
                'attribute' =>'status',
                'label'=>'Condición',
                'format'=>'raw',
                'contentOptions' => ['class' => "y_centercenter",'style'=>''],
                'headerOptions' => ['class' => "y_centercenter",'style'=>'color: #716F81;
                text-decoration:none;
                font-weight: 500;
                font-size: 11px;'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                 'filter'=>Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'status',
                    'data' =>['1'=>'Activo','2'=>'Baja','3'=>'NI'],
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

                    if($model->status == 2){
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
                'attribute' =>'id_puesto',
                'label'=>'Puesto',
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
                'attribute' =>'vencimientos',
                'label'=>'DOCUMENTOS - VENCIMIENTOS',
                'contentOptions' => ['class' => "",'style'=>'width:40%;'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'headerOptions' => ['class' => "y_centercenter",'style'=>'color: #716F81;
                text-decoration:none;
                font-weight: 500;
                font-size: 11px;'],
                'filter' => \kartik\daterange\DateRangePicker::widget(
                    [
                        'model' => $searchModel, 
                        'attribute' => 'vencimientos', 
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
                        ], 
                        'options'=>[
                            'placeholder'=>'Filtro FECHA DE VENCIMIENTO',
                        ]
                ]),
                'format'=>'raw',
                'value'=>function($model) use ($searchModel){
                    $ret = '';
                    $periodicidades = ['0'=>'INDEFINIDO','1'=>'15 DÍAS','2'=>'1 MES','3'=>'2 MESES','4'=>'3 MESES','5'=>'4 MESES','6'=>'5 MESES','7'=>'6 MESES','8'=>'7 MESES','9'=>'8 MESES','10'=>'9 MESES','11'=>'10 MESES','12'=>'11 MESES','13'=>'1 AÑO','14'=>'1 AÑO 3 MESES','15'=>'1 AÑO 6 MESES','16'=>'1 AÑO 9 MESES','17'=>'2 AÑOS'];
                   
                    if($model->testudios){
                        
                        $ret = '<table class="table table-bordered table-hover table-sm text-little">';
                        $ret .= '<thead class="table-dark"><tr class="text-center">
                        <th width="3%">#</th>
                        <th width="10%">Tipo</th>
                        <th width="30%">Estudio</th>
                        <th width="12%">Periodicidad</th>
                        <th width="15%">Fecha Documento</th>
                        <th width="15%">Fecha Vencimiento</th>
                        <th width="10%">Doc</th>
                        <th width="10%">Status</th>
                        </tr></thead><tbody>';

                        $total_estudios = count($model->testudios);

                        $index = 1;

                        $estudios = [];
                        if($searchModel->vencimientos != null && $searchModel->vencimientos != ''){
                            $fechas = explode(' - ', $searchModel->vencimientos);
                            if($fechas[0] == $fechas[1]){
                                $fechas[0] = '1970-01-01';
                            }
                            $fechas[0] = $fechas[0];
                            $fechas[1] = $fechas[1];
                            $estudios = Trabajadorestudio::find()->where(['id_trabajador'=>$model->id])->andWhere(['between', 'fecha_vencimiento', $fechas[0], $fechas[1]])->all();
                        } else {
                            $estudios = $model->testudios;
                        }
                        foreach($estudios as $key=>$estudio){
                            $tipo = '';
                            $nombreestudio = '';
                            $periodicidad = '';
                            $fecha_documento = '';
                            $fecha_vencimiento = '';
                            $doc = '';
                            $status = '';
                            $color = 'color11';

                            $colorbg = '';

                            if($estudio->estudio){
                                $nombreestudio = $estudio->estudio->estudio;
                            }

                            if($estudio->id_tipo == 1){
                                $tipo = 'Médico';
                            } else {
                                $tipo = 'Otro';
                            }

                            $fecha_documento = $estudio->fecha_documento;

                            if (array_key_exists($estudio->id_periodicidad, $periodicidades)) {
                                $periodicidad = $periodicidades[$estudio->id_periodicidad];
                            }

                            if($estudio->id_periodicidad == 0){
                                $fecha_vencimiento = 'INDEFINIDO';
                            } else {
                                $fecha_vencimiento = $estudio->fecha_vencimiento;

                                if($searchModel->vencimientos != null && $searchModel->vencimientos != ''){
                                    $fechas = explode(' - ', $searchModel->vencimientos);
                                    if($fechas[0] == $fechas[1]){
                                        $fechas[0] = '1970-01-01';
                                    }
                                    $fechas[0] = $fechas[0];
                                    $fechas[1] = $fechas[1];
                                    //dd($fecha_vencimiento);

                                    if($fecha_vencimiento >= $fechas[0] && $fecha_vencimiento <= $fechas[1]){
                                        $colorbg = 'boxtitle2';
                                       
                                    }
                                }
                            }

                            if($estudio->evidencia != '' && $estudio->evidencia != null){
                                $filePath = 'resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id.'/Documentos/'.$estudio->evidencia;
                                $image = '<span class="" style="font-size:30px"><i class="bi bi-file-earmark-pdf"></i></span>';
                                $doc = Html::a('<span style="font-size:30px;" class="color1">'.$image.'</span>', $filePath, $options = ['target'=>'_blank']);
                            }

                            if($estudio->status == 1){
                                $status = '<span class="badge bgcumple m-1 font14 text-light text-uppercase">Vigente</span>';
                            } else if($estudio->status == 2){
                                $status = '<span class="badge bgnocumple m-1 font14 text-light text-uppercase">Vencido</span>';
                            } else if($estudio->status == 0){
                                $status = '<span class="badge bgpendiente m-1 font14 text-light text-uppercase">Indefinido</span>';
                            }
                            

                            $ret .= '<tr>
                            <td colspan="1" class="font11 '.$colorbg.'" width="3%">'.($key+1).'</td>
                            <td colspan="1" class="font9 '.$colorbg.' font600" width="10%">'.$tipo.'</td>
                            <td colspan="1" class="btnnew text-light font9 text-sark" width="30%">'.$nombreestudio.'</td>
                            <td colspan="1" class="font11 '.$colorbg.'" width="12%">'.$periodicidad.'</td>
                            <td colspan="1" class="font11 '.$colorbg.'" width="15%">'.$fecha_documento.'</td>
                            <td colspan="1" class="font11 '.$colorbg.'" width="15%">'.$fecha_vencimiento.'</td>
                            <td colspan="1" class="text-center '.$colorbg.' font11 py-0" width="10%">'.$doc.'</td>
                            <td colspan="1" class="font11 '.$colorbg.'" width="10%">'.$status.'</td>
                            </tr>';


                        }

                        $ret .= '</tbody></table>';

                    } else{
                        $ret = '<span class="color6">SIN DOCUMENTOS</span>';
                    }
                    
                    return $ret;
                  },
            ],
            [
                'attribute' =>'status_documentos',
                'label'=>'Status Docs.',
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
            //'nombre',
            //'apellidos',
            //'foto',
            //'sexo',
            //'estado_civil',
            //'fecha_nacimiento',
            //'edad',
            //'turno',
            //'nivel_lectura',
            //'nivel_escritura',
            //'escolaridad',
            //'grupo',
            //'rh',
            //'num_imss',
            //'curp',
            //'rfc',
            //'correo',
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
            //'motivo_baja',
            //'antiguedad',
            //'antiguedad_dias',
            //'antiguedad_meses',
            //'antiguedad_anios',
            //'ruta',
            //'parada',
            //'id_puesto',
            //'id_area',
            //'fecha_iniciop',
            //'fecha_finp',
            //'teamleader',
            //'talla_cabeza',
            //'talla_general',
            //'talla_superior',
            //'talla_inferior',
            //'talla_manos',
            //'talla_pies',
            //'personalidad',
            //'dato_extra1',
            //'dato_extra2',
            //'dato_extra3',
            //'dato_extra4',
            //'dato_extra5',
            //'dato_extra6',
            //'dato_extra7',
            //'dato_extra8',
            //'dato_extra9',
            //'dato_extra10',
            //'create_date',
            //'create_user',
            //'update_date',
            //'update_user',
            //'delete_date',
            //'delete_user',
            //'status_documentos',
            //'status',
            //'id_link',
            //'soft_delete',
            //'origen_extraccion',
            //'id_origen',
            //'hidden',
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
                    'doc' =>  function($url,$model) {
                        return Html::a('<span class=""><i class="bi bi-paperclip"></i><span>', Url::to(['trabajadores/folder','id' => $model->id]), [
                            'title' => Yii::t('app', 'Documentos'),
                            'data-toggle'=>"tooltip",
                            'data-placement'=>"top",
                            'class'=>'btn btn-sm text-center shadow-sm color3'
                        ]);
                    },
                   
                ],
            ],
           
        ],
    ]); ?>


</div>