<?php

use app\models\Trashhistory;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use kartik\export\ExportMenu;
use app\models\Empresas;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var app\models\TrashhistorySearch $searchModel */
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

$this->title = Yii::t('app', 'Papelera de Reciclaje'.$name_empresa);
$this->params['breadcrumbs'][] = $this->title;
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
?>
<?php
$modelos2 = [
            'AlergiaTrabajador' => 'AlergiaTrabajador',
            'Almacen' => 'Almacen',
            'Areas' => 'Areas',
            'Areascuestionario' => 'Areascuestionario',
            'Bitacora' => 'Bitacora',
            'Cargasmasivas' => 'Cargasmasivas',
            'Configconsentimientos' => 'Configconsentimientos',
            'Configuracion' => 'Configuracion',
            'Consentimientos' => 'Consentimientos',
            'Consultas' => 'Consultas',
            'Consultorios' => 'Consultorios',
            'ContactForm' => 'ContactForm',
            'Cuestionario' => 'Cuestionario',
            'DetalleCuestionario' => 'DetalleCuestionario',
            'Detallehc' => 'Detallehc',
            'Detallemovimiento' => 'Detallemovimiento',
            'Detalleordenpoe' => 'Detalleordenpoe',
            'Diagnosticoscie' => 'Diagnosticoscie',
            'Documentacion' => 'Documentacion',
            'DocumentoTrabajador' => 'DocumentoTrabajador',
            'Empresamails' => 'Empresamails',
            'Empresas' => 'Empresas',
            'Epps' => 'Epps',
            'Estudios' => 'Estudios',
            'ExtraccionBd' => 'ExtraccionBd',
            'Firmaempresa' => 'Firmaempresa',
            'Firmas' => 'Firmas',
            'Hccohc' => 'Historias Clínicas',
            'Hccohcestudio' => 'Hccohcestudio',
            'Historialdocumentos' => 'Historialdocumentos',
            'Historicoes' => 'Historicoes',
            'Insumos' => 'Insumos',
            'Insumostockmin' => 'Insumostockmin',
            'Lineas' => 'Lineas',
            'LoginForm' => 'LoginForm',
            'Lotes' => 'Lotes',
            'Mantenimientoactividad' => 'Mantenimientoactividad',
            'Mantenimientocomponentes' => 'Mantenimientocomponentes',
            'Mantenimientos' => 'Mantenimientos',
            'Maquinaria' => 'Maquinaria',
            'Maquinariesgo' => 'Maquinariesgo',
            'Medidas' => 'Medidas',
            'Movimientos' => 'Movimientos',
            'NivelOrganizacional1' => 'NivelOrganizacional1',
            'NivelOrganizacional2' => 'NivelOrganizacional2',
            'NivelOrganizacional3' => 'NivelOrganizacional3',
            'NivelOrganizacional4' => 'NivelOrganizacional4',
            'Notification' => 'Notification',
            'Ordenespoes' => 'Ordenespoes',
            'Ordenpoetrabajador' => 'Ordenpoetrabajador',
            'Paisempresa' => 'Paisempresa',
            'Paises' => 'Paises',
            'Parametros' => 'Parametros',
            'Poeestudio' => 'Poeestudio',
            'Poes' => 'Poes',
            'Preguntas' => 'Preguntas',
            'Presentaciones' => 'Presentaciones',
            'Programaempresa' => 'Programaempresa',
            'ProgramaSalud' => 'ProgramaSalud',
            'Programasaludempresa' => 'Programasaludempresa',
            'ProgramaTrabajador' => 'ProgramaTrabajador',
            'PuestoEpp' => 'PuestoEpp',
            'PuestoEstudio' => 'PuestoEstudio',
            'Puestoparametro' => 'Puestoparametro',
            'PuestoRiesgo' => 'PuestoRiesgo',
            'Puestostrabajo' => 'Puestostrabajo',
            'Puestotrabajador' => 'Puestotrabajador',
            'Requerimientoempresa' => 'Requerimientoempresa',
            'Riesgos' => 'Riesgos',
            'Roles' => 'Roles',
            'Rolpermiso' => 'Rolpermiso',
            'Secciones' => 'Secciones',
            'Servicios' => 'Servicios',
            'SolicitudesDelete' => 'SolicitudesDelete',
            'TipoCuestionario' => 'TipoCuestionario',
            'TipoServicios' => 'TipoServicios',
            'Trabajadorepp' => 'Trabajadorepp',
            'Trabajadores' => 'Trabajadores',
            'Trabajadorestudio' => 'Trabajadorestudio',
            'Trabajadormaquina' => 'Trabajadormaquina',
            'Trabajadorparametro' => 'Trabajadorparametro',
            'Trashhistory' => 'Trashhistory',
            'Turnopersonal' => 'Turnopersonal',
            'Turnos' => 'Turnos',
            'Ubicaciones' => 'Ubicaciones',
            'Unidades' => 'Unidades',
            'Usuarios' => 'Usuarios',
            'Vacantes' => 'Vacantes',
            'Vacunacion' => 'Vacunacion',
            'Vacantetrabajador' => 'Vacantetrabajador',
            'Vacunacion' => 'Vacunacion',
            'Viasadministracion' => 'Viasadministracion',
            'Vigencias' => 'Vigencias'
          
        ];
?>
<div class="trashhistory-index">

    <h1 class="title1"><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-lg-2 text-center">
            <?php
            if(Yii::$app->user->identity->hidden_actions == 1){
                echo Html::a('<span class=""><i class="bi bi-arrow-clockwise"></i><span>Refresh Contenido', Url::to(['trashhistory/refresh']), [
                    'title' => Yii::t('app', 'Refresh Contenido'),
                    'data-toggle'=>"tooltip",
                    'data-placement'=>"top",
                    'class'=>'btn btnnew4 btn-block text-light'
                ]);
            }
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
                'attribute' =>'model',
                'contentOptions' => ['class' => "y_centercenter color3 font600",'style'=>'width:15%'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'filter'=>Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'model',
                    'data' =>$modelos2,
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
                'value'=>function($model) use ($modelos2){
                    $ret = '';

                    if($model->model){
                        $ret = $modelos2[$model->model];
                    }
                    
                    return $ret;
                  },
            ],
            /* [
                'attribute' =>'id_model',
                'contentOptions' => ['class' => "y_centercenter",'style'=>'width:20%'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'format'=>'raw',
                'value'=>function($model){
                    $ret = '';
                    $atributo = 'data'.$model->model;

                    if($model->model != null && $model->model != '' && !$model->model != ' '){
                        switch ($model->model) {
                            case 'Trabajadores':
                                $temporal = $model[$atributo];

                                if($temporal){
                                    $ret = $temporal->nombre_trabajador;
                                }

                                break;
                            
                            case 'Servicios':
                                $temporal = $model[$atributo];

                                if($temporal){
                                    $ret = $temporal->nombre;
                                }

                                break;
                            
                            default:
                                $ret = '';
                                break;
                        }
                    }
                    
                    return $ret;
                  },
            ], */
            [
                'attribute' =>'contenido',
                'contentOptions' => ['class' => "y_centercenter",'style'=>'width:20%'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'format'=>'raw',
            ],
            [
                'attribute' =>'deleted_date',
                'format'=>'raw',
                'contentOptions' => ['class' => "y_centercenter text-center color6",'style'=>'width:15%;'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'filter' => \kartik\daterange\DateRangePicker::widget(
                    [
                        'model' => $searchModel, 
                        'attribute' => 'deleted_date', 
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
                    date_default_timezone_set('America/Costa_Rica');
                    if($model->deleted_date){
                        $ret = date('Y-m-d', strtotime($model->deleted_date));

                        $ret .= '<br><label class="color3 font9">'.date('H:i A', strtotime($model->deleted_date)).'</label>';
                    }
                    return $ret;
                },
            ],
            [
                'attribute' =>'tiempo_transcurrido',
                'format'=>'raw',
                'contentOptions' => ['class' => "y_centercenter text-center color6",'style'=>'width:15%;'],
                'headerOptions' => ['class' => "y_centercenter",'style'=>'color: #716F81;
                text-decoration:none;
                font-weight: 500;
                font-size: 11px;'],
                'value'=>function($model){
                    $ret = '';
                    date_default_timezone_set('America/Costa_Rica');
                    if($model->deleted_date){
                        $time = new \DateTime('now');
                        $today = $time->format('Y-m-d H:i');
                        $fecha = Yii::$app->formatter->format($model->deleted_date, ['relativeTime', $today ]);
                        $ret .= '<span class="color11"><i class="bi bi-trash"></i></span> '.'<span class="small text-compras">'.$fecha.'</span>';
                    }
                    return $ret;
                },
            ],
            [
                'attribute' =>'deleted_user',
                'contentOptions' => ['class' => "y_centercenter",'style'=>'width:20%'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'format'=>'raw',
                'value'=>function($model){
                    $ret = '';
                    $class_color = 'color11';
                    $button = '<span class="badge color11 text-dark font11">Autorizado a eliminar</span>';
                    $icon = '<i class="bi bi-x-lg"></i>';

                    if($model->elimina){
                        if($model->elimina->activo_eliminar == 2){
                            $class_color = 'color11';
                            $button = '<span class="badge color11 text-light font11">No Autorizado a eliminar</span>';
                        } 

                        $ret = '<span class="'.$class_color.' mx-2">'.$model->elimina->name.'</span> - '.$button;
                    }
                    
                    return $ret;
                  },
            ],
            [
                'attribute' =>'restaurar',
                'contentOptions' => ['class' => "y_centercenter text-center",'style'=>'width:8%'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                 'headerOptions' => ['class' => "y_centercenter",'style'=>'color: #716F81;
                text-decoration:none;
                font-weight: 500;
                font-size: 11px;'],
                'format'=>'raw',
                'value'=>function($model){
                    $ret = '';
                    
                    $iconreactivate = '<i class="bi bi-shift"></i>';
                    $ret = Html::a('<span class="color7">'.$iconreactivate.'<span>', ['restore', 'id' => $model->id], [
                            'data' => [
                                'confirm' => Yii::t('app', 'Restaurar '.$model->contenido.'?'),
                                'method' => 'post',
                            ],
                            'title' => Yii::t('app', 'Restaurar'),
                            'data-bs-toggle'=>"tooltip",
                            'data-bs-placement'=>"top",
                            'class'=>'btn btn-sm text-center shadow-sm'
                        ]);
                    
                    return $ret;
                  },
            ],
            [
                'attribute' =>'restored_date',
                'format'=>'raw',
                'contentOptions' => ['class' => "y_centercenter text-center color6",'style'=>'width:15%;'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'filter' => \kartik\daterange\DateRangePicker::widget(
                    [
                        'model' => $searchModel, 
                        'attribute' => 'restored_date', 
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
                    date_default_timezone_set('America/Costa_Rica');
                    if($model->restored_date){
                        $ret = date('Y-m-d', strtotime($model->restored_date));

                        $ret .= '<br><label class="color3 font9">'.date('H:i A', strtotime($model->restored_date)).'</label>';
                    }
                    return $ret;
                },
            ],
             [
                'attribute' =>'restored_user',
                'contentOptions' => ['class' => "y_centercenter",'style'=>'width:10%'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'format'=>'raw',
                'value'=>function($model){
                    $ret = '';
                    $class_color = 'colorexcel';
                    $button = '<span class="badge colorexcel text-dark font11">Autorizado a eliminar</span>';
                    $icon = '<i class="bi bi-x-lg"></i>';

                    if($model->restaura){
                        $ret = '<span class="'.$class_color.' mx-2">'.$model->restaura->name.'</span>';
                    }
                    
                    return $ret;
                  },
            ],
            [
                'attribute' =>'action',
                'contentOptions' => ['class' => "y_centercenter",'style'=>'width:5%'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                 'filterInputOptions' => ['class' => 'icon-find form-control'],
                 'headerOptions' => ['class' => "y_centercenter",'style'=>'color: #716F81;
                text-decoration:none;
                font-weight: 500;
                font-size: 11px;'],
                'format'=>'raw',
                'value'=>function($model){
                    $ret = '';
                    $class_color = 'color7';
                    $button = '';
                    $icon = '<i class="bi bi-x-lg"></i>';

                    if($model->elimina){
                        if($model->elimina->activo_eliminar == 2){
                            $class_color = 'color11';

                            $icon = '<i class="bi bi-check2"></i>';
                            $button =  Html::a($icon, ['up', 'id' => $model->elimina->id], [
                            'data' => [
                                'confirm' => Yii::t('app', '¿Desea Activar la papelera de reciclaje (acción de eliminar) para el usuario '.$model->elimina->name.'?'),
                                'method' => 'post',
                            ],
                            'title' => Yii::t('app', 'Activar papelera para usuario: '.$model->elimina->name),
                            'data-bs-toggle'=>"tooltip",
                            'data-bs-placement'=>"top",
                            'class'=>'btn btn-sm text-center shadow-sm bordercolor7'
                            ]);
                        } else {
                            $icon = '<i class="bi bi-x"></i>';
                            $button =  Html::a($icon, ['down', 'id' => $model->elimina->id], [
                            'data' => [
                                'confirm' => Yii::t('app', '¿Desea Desactivar la papelera de reciclaje (acción de eliminar) para el usuario '.$model->elimina->name.'?'),
                                'method' => 'post',
                            ],
                            'title' => Yii::t('app', 'Desactivar papelera para usuario: '.$model->elimina->name),
                            'data-bs-toggle'=>"tooltip",
                            'data-bs-placement'=>"top",
                            'class'=>'btn btn-sm text-center shadow-sm bgcolor6 text-light'
                            ]);
                        }

                        $ret = $button;
                    }
                    
                    return $ret;
                  },
            ],
           
        ],
    ]); ?>


</div>
