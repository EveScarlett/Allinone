<?php

use app\models\Empresas;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use kartik\select2\Select2;
use yii\grid\GridView;
use app\models\Estudios;
use yii\helpers\ArrayHelper;
use kartik\export\ExportMenu;

/** @var yii\web\View $this */
/** @var app\models\EmpresasSearch $searchModel */
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

$this->title = 'Requisitos Mínimos por Puestos de Trabajo'.$name_empresa;
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
$estudios = ArrayHelper::map(Estudios::find()->orderBy('estudio')->all(), 'id', 'estudio');
?>
<?php
$this->registerCss(".select2-container--bootstrap .select2-selection--multiple .select2-selection__choice {
    color: black;
    background: #636AF25a;
    border: 1px solid #636AF2;
    border-radius: 10px 10px 10px 10px;
    cursor: default;
    float: left;
    margin: 5px 0 0 6px;
    padding: 0 6px;
    font-size:10px;
}

.select2-container--bootstrap .select2-selection{
    background-color:transparent;
}

.select2-container--bootstrap .select2-selection--multiple .select2-selection__choice__remove {
    color: #ffffff;
}

.select2-container--bootstrap .select2-selection {
    border: none;
    border-radius:0px;
    border-bottom: 1px solid #0d6efd;
    font-size:10px;
}
");
?>
<div class="empresas-index">

    <h1 class="title1 text-uppercase"><?= Html::encode($this->title) ?></h1>

    <?php
    $template = '';
    if(Yii::$app->user->can('requerimientos_ver')){
        $template .='{view}';
    }
    if(Yii::$app->user->can('requerimientos_actualizar')){
        $template .='{update}';
    }
    ?>

    <?php 
     $fullExportMenu = '';
      if(Yii::$app->user->can('requerimientos_exportar')){
        $fullExportMenu = ExportMenu::widget([
            'dataProvider' => $dataProvider,
            'columns' =>[
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'attribute' =>'razon',
                    'visible' => $showempresa,
                    'label'=>'Empresa',
                    'value'=>function($model){
                        $ret = '';
                        $ret .= $model->razon;
                        return $ret;
                      },
                ],
                [
                    'attribute' =>'tipo_req',
                    'label' =>'Tipo',
                    'value'=>function($model){
                        $tipos = ['1'=>'Médico','2'=>'Otro'];
                        $ret = '';
                       
                        if($model->requisitos){
                            foreach($model->requisitos as $key=>$estudio){
                                $ret.= $tipos[$estudio->id_tipo];
                                if($key < (count($model->requisitos)-1)){
                                    $ret .= ',';
                                }
                            }
                        }

                        return $ret;
                      },
                ],
                [
                    'attribute' =>'estudio_req',
                    'label' =>'Requisito',
                    'value'=>function($model){
            
                        $ret = '';
                        
                        if($model->requisitos){
                            foreach($model->requisitos as $key=>$estudio){
                                $ret.= $estudio->estudio->estudio;
                                if($key < (count($model->requisitos)-1)){
                                    $ret .= ',';
                                }
                            }
                        }
            
                        return $ret;
                      },
                ],
                [
                    'attribute' =>'periodicidad_req',
                    'label' =>'Periodicidad',
                    'value'=>function($model){
                        $periodicidades = ['0'=>'INDEFINIDO','1'=>'15 DÍAS','2'=>'1 MES','3'=>'2 MESES','4'=>'3 MESES','5'=>'4 MESES','6'=>'5 MESES','7'=>'6 MESES','8'=>'7 MESES','9'=>'8 MESES','10'=>'9 MESES','11'=>'10 MESES','12'=>'11 MESES','13'=>'1 AÑO','14'=>'1 AÑO 3 MESES','15'=>'1 AÑO 6 MESES','16'=>'1 AÑO 9 MESES','17'=>'2 AÑOS'];
                        $ret = '';
    
                        if($model->requisitos){
                            foreach($model->requisitos as $key=>$estudio){
                                $ret.= $periodicidades[$estudio->id_periodicidad];
                                if($key < (count($model->requisitos)-1)){
                                    $ret .= ',';
                                }
                            }
                        }
            
                        return $ret;
                      },
                ],
                [
                    'attribute' =>'status_req',
                    'label' =>'Status',
                    'value'=>function($model){
                        $status = ['1'=>'Activo','0'=>'Baja'];
                        $ret = '';
                       
                        if($model->requisitos){
                            foreach($model->requisitos as $key=>$estudio){
                                $ret.= $status[$estudio->id_status];
                                if($key < (count($model->requisitos)-1)){
                                    $ret .= ',';
                                }
                            }
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
        <div class="col-lg-9 d-grid">

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
                'attribute'=>'logo',
                'format'=>'raw',
                'label'=>'',
                'headerOptions'=>['style'=>'width:5%;','class'=>'font12'],
                'contentOptions'=>['class'=>'font500 y_centercenter' ,'style'=>'vertical-align: middle;'],
                'filterInputOptions' => ['class' => 'icon-find form-control','placeholder'=>'Buscar...'],
                'visible'=>true,
                "value"=>function($model){
                    $ret = ''.$model->logo;
                    
                    if(isset($model->logo) && $model->logo != ""){
                        $ret = '';
                        $filePath =  '/resources/Empresas/'.$model->id.'/'.$model->logo;
                        $foto = Html::img('@web'. $filePath, ['alt'=>' ', 'class' => "iconphoto img-responsive", 'width' => '35px', 'height' =>'auto','style'=>'box-shadow: rgba(50, 50, 93, 0.25) 0px 13px 27px -5px, rgba(0, 0, 0, 0.3) 0px 8px 16px -8px; height:auto; width:35px;']);
                        $ret = $foto;
                    }

                    return $ret;
                }
            ],
            [
                'attribute' =>'razon',
                'visible' => $showempresa,
                'label'=>'Empresa',
                'contentOptions' => ['class' => "y_centercenter color3 font600",'style'=>'width:25%'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'format'=>'raw',
                'value'=>function($model){
                    $ret = '';
                    $ret .= $model->razon;
        
                    return $ret;
                  },
            ],
            [
                'attribute' =>'tipo_req',
                'label' =>'Tipo',
                'contentOptions' => ['class' => "y_centercenter color3 font600",'style'=>'width:15%'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'headerOptions' => ['class' => "y_centercenter",'style'=>'color: #716F81;
                text-decoration:none;
                font-weight: 500;
                font-size: 11px;'],
                'filter'=>Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'tipo_req',
                    'data' => ['1'=>'Médico','2'=>'Otro'],
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
                    $tipos = ['1'=>'Médico','2'=>'Otro'];
                    $ret = '';
                    $ret = '<table class="table table-dark table-hover table-sm text-little" style="height:100%">';
                    $ret .= '<tbody>';
                    $iconheart = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clipboard2-heart" viewBox="0 0 16 16">
                    <path d="M10.058.501a.501.501 0 0 0-.5-.501h-2.98c-.276 0-.5.225-.5.501A.499.499 0 0 1 5.582 1a.497.497 0 0 0-.497.497V2a.5.5 0 0 0 .5.5h4.968a.5.5 0 0 0 .5-.5v-.503A.497.497 0 0 0 10.555 1a.499.499 0 0 1-.497-.499Z"/>
                    <path d="M3.605 2a.5.5 0 0 0-.5.5v12a.5.5 0 0 0 .5.5h9a.5.5 0 0 0 .5-.5v-12a.5.5 0 0 0-.5-.5h-.5a.5.5 0 0 1 0-1h.5a1.5 1.5 0 0 1 1.5 1.5v12a1.5 1.5 0 0 1-1.5 1.5h-9a1.5 1.5 0 0 1-1.5-1.5v-12a1.5 1.5 0 0 1 1.5-1.5h.5a.5.5 0 0 1 0 1h-.5Z"/>
                    <path d="M8.068 6.482c1.656-1.673 5.795 1.254 0 5.018-5.795-3.764-1.656-6.69 0-5.018Z"/>
                    </svg>';
                    $iconsquare = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file" viewBox="0 0 16 16">
                    <path d="M4 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H4zm0 1h8a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1z"/>
                    </svg>';
                    $arr_iconos = ['1'=>$iconheart,'2'=>$iconsquare];
                    $arr_colors = ['1'=>'color10','2'=>'color5'];

                    if($model->requisitos){
                        foreach($model->requisitos as $key=>$estudio){
                            $ret.= '<tr><td class="text-light">'.($key+1).'</td><td class="font500 text-center text-uppercase '.$arr_colors[$estudio->id_tipo].'"> <span class="mx-2">'.$arr_iconos[$estudio->id_tipo].'</span>'.$tipos[$estudio->id_tipo].'</td></tr>';
                        }
                    }

                    $ret .= '</tbody></table>';
        
                    return $ret;
                  },
            ],
            [
                'attribute' =>'estudio_req',
                'label' =>'Requisito',
                'contentOptions' => ['class' => "y_centercenter color3 font600",'style'=>'width:30%'],
                'headerOptions' => ['class' => "y_centercenter",'style'=>'color: #716F81;
                text-decoration:none;
                font-weight: 500;
                font-size: 11px;'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'filter'=>Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'estudio_req',
                    'data' => $estudios,
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
                    $tipos = ['1'=>'Médico','2'=>'Otro'];
                    $ret = '';
                    $ret = '<table class="table table-dark table-hover table-sm text-little" style="height:100%">';
                    $ret .= '<tbody>';

                    if($model->requisitos){
                        foreach($model->requisitos as $key=>$estudio){
                            $ret.= '<tr><td class="font600">'.$estudio->estudio->estudio.'</td></tr>';
                        }
                    }

                    $ret .= '</tbody></table>';
        
                    return $ret;
                  },
            ],
            [
                'attribute' =>'periodicidad_req',
                'label' =>'Periodicidad',
                'contentOptions' => ['class' => "y_centercenter color6 font600",'style'=>'width:10%'],
                'headerOptions' => ['class' => "y_centercenter",'style'=>'color: #716F81;
                text-decoration:none;
                font-weight: 500;
                font-size: 11px;'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'format'=>'raw',
                'filter'=>Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'periodicidad_req',
                    'data' => ['0'=>'INDEFINIDO','1'=>'15 DÍAS','2'=>'1 MES','3'=>'2 MESES','4'=>'3 MESES','5'=>'4 MESES','6'=>'5 MESES','7'=>'6 MESES','8'=>'7 MESES','9'=>'8 MESES','10'=>'9 MESES','11'=>'10 MESES','12'=>'11 MESES','13'=>'1 AÑO','14'=>'1 AÑO 3 MESES','15'=>'1 AÑO 6 MESES','16'=>'1 AÑO 9 MESES','17'=>'2 AÑOS'],
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
                    $periodicidades = ['0'=>'INDEFINIDO','1'=>'15 DÍAS','2'=>'1 MES','3'=>'2 MESES','4'=>'3 MESES','5'=>'4 MESES','6'=>'5 MESES','7'=>'6 MESES','8'=>'7 MESES','9'=>'8 MESES','10'=>'9 MESES','11'=>'10 MESES','12'=>'11 MESES','13'=>'1 AÑO','14'=>'1 AÑO 3 MESES','15'=>'1 AÑO 6 MESES','16'=>'1 AÑO 9 MESES','17'=>'2 AÑOS'];
                    $ret = '';
                    $ret = '<table class="table table-dark table-hover table-sm text-little" style="height:100%">';
                    $ret .= '<tbody>';

                    if($model->requisitos){
                        foreach($model->requisitos as $key=>$estudio){
                            $ret.= '<tr><td class="font500 color6 text-center">'.$periodicidades[$estudio->id_periodicidad].'</td></tr>';
                        }
                    }

                    $ret .= '</tbody></table>';
        
                    return $ret;
                  },
            ],
            [
                'attribute' =>'status_req',
                'label' =>'Status',
                'contentOptions' => ['class' => "y_centercenter color3 font600",'style'=>'width:10%'],
                'headerOptions' => ['class' => "y_centercenter",'style'=>'color: #716F81;
                text-decoration:none;
                font-weight: 500;
                font-size: 11px;'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'filter'=>Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'status_req',
                    'data' => ['1'=>'Activo','0'=>'Baja'],
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
                    $status = ['1'=>'<span class="badge bgcolor3 text-light font11"><span class="color7 mx-2" style=""><i class="bi bi-check"></i></span>Activo</span>','0'=>'<span class="badge bgcolor6 text-light font11"><span class="color11 mx-2" style=""><i class="bi bi-x"></i></span>Baja</span>'];
                    $ret = '';
                    $ret = '<table class="table table-dark table-hover table-sm text-little" style="height:100%">';
                    $ret .= '<tbody>';

                    if($model->requisitos){
                        foreach($model->requisitos as $key=>$estudio){
                            $ret.= '<tr><td class="font500 text-center">'.$status[$estudio->id_status].'</td></tr>';
                        }
                    }

                    $ret .= '</tbody></table>';
        
                    return $ret;
                  },
            ],
            //'pais',
            //'estado',
            //'ciudad',
            //'municipio',
            //'logo',
            //'contacto',
            //'telefono',
            //'correo',
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
                    'view' =>  function($url,$model) {
                        return Html::a('<span class="color2"><i class="bi bi-eye-fill"></i><span>', Url::to(['requerimientoempresa/view','id' => $model->id]), [
                            'title' => Yii::t('app', 'Ver'),
                            'data-toggle'=>"tooltip",
                            'data-placement'=>"top",
                            'class'=>'btn btn-sm text-center shadow-sm'
                        ]);
                    },
                    'update' =>  function($url,$model) {
                        return Html::a('<span class=""><i class="bi bi-pen-fill"></i><span>', Url::to(['requerimientoempresa/update','id' => $model->id]), [
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