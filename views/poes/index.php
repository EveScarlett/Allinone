<?php

use app\models\Poes;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use kartik\export\ExportMenu;
use kartik\select2\Select2;
use app\models\Empresas;
use app\models\Servicios;
use app\models\TipoServicios;
use app\models\Trabajadores;
use app\models\Puestostrabajo;
use app\models\Areas;
use app\models\Poeestudio;
use yii\bootstrap5\Modal;

use yii\helpers\ArrayHelper;

use app\models\NivelOrganizacional1;
use app\models\NivelOrganizacional2;
use app\models\NivelOrganizacional3;
use app\models\NivelOrganizacional4;

use app\models\Ubicaciones;
use app\models\Paises;
use app\models\Paisempresa;

/** @var yii\web\View $this */
/** @var app\models\PoesSearch $searchModel */
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
$this->title = Yii::t('app', 'Exámenes Médicos'.$name_empresa);
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
$estudiossearch = ArrayHelper::map(Servicios::find()->where(['IS', 'status_baja', new \yii\db\Expression('NULL')])->orderBy('nombre')->all(), 'id', 'nombre');
//dd($estudiossearch);
?>


<?php
$label_nivel1 = 'Nivel 1';
$label_nivel2 = 'Nivel 2';
$label_nivel3 = 'Nivel 3';
$label_nivel4 = 'Nivel 4';

$show_nivel1 = false;
$show_nivel2 = false;
$show_nivel3 = false;
$show_nivel4 = false;


if(Yii::$app->user->identity->empresa_all != 1) {
    $array_empresas = explode(',', Yii::$app->user->identity->empresas_select);
    if(count($array_empresas) == 1){
        $dataempresa = Empresas::findOne(intval(Yii::$app->user->identity->empresas_select));
        if($dataempresa){
            $label_nivel1 = $dataempresa->label_nivel1;
            $label_nivel2 = $dataempresa->label_nivel2;
            $label_nivel3 = $dataempresa->label_nivel3;
            $label_nivel4 = $dataempresa->label_nivel4;

            if($dataempresa->cantidad_niveles >= 1){
                if($mostrar_nivel1){
                    $show_nivel1 = true;
                }
            }
            if($dataempresa->cantidad_niveles >= 2){
                if($mostrar_nivel2){
                    $show_nivel2 = true;
                }
            }
            if($dataempresa->cantidad_niveles >= 2){
                if($mostrar_nivel3){
                    $show_nivel3 = true;
                }
            }
            if($dataempresa->cantidad_niveles >= 4){
                if($mostrar_nivel4){
                    $show_nivel4 = true;
                }
            }
        }
    }
} else {
    
    $show_nivel1 = true;
    $show_nivel2 = true;
    $show_nivel3 = true;
    $show_nivel4 = true;
}
//dd($label_nivel1,$label_nivel2,$label_nivel3,$label_nivel4,$show_nivel1,$show_nivel2,$show_nivel3,$show_nivel4);
?>

<?php
if(Yii::$app->user->identity->empresa_all != 1) {
    if($array_empresas == null && $array_empresas == '' && $array_empresas == ' '){
        $array_empresas = [];
    }
    $nivel1 = ArrayHelper::map(NivelOrganizacional1::find()->where(['in','id_empresa',$array_empresas])->andWhere(['status'=>1])->orderBy('id_pais')->all(), 'id_pais', function($data){
        $rtlvl1 = '';
        if($data->pais){
            $rtlvl1 = $data->pais->pais;
        }
        return $rtlvl1;
    });
} else {
    $nivel1 = ArrayHelper::map(Paises::find()->orderBy('pais')->all(), 'id', 'pais');
}
?>

<?php 
      Modal::begin([
        'id' =>'modal-card',
        'title' => '<h5 class="text-uppercase text-purple">
                        
                    </h5>',
        'size' => 'modal-xl',
        'headerOptions' =>[
            'class' => 'text-light bg-mymodal btnnew'
        ],
        ]);
        echo '<div id="body-card"></div>';
        Modal::end();
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
<div class="poes-index">

    <h1 class="title1"><?= Html::encode($this->title) ?></h1>

    <?php
    /* $categorias_ordenado = ArrayHelper::map(TipoServicios::find()->orderBy(['orden'=>SORT_ASC])->all(), 'id', 'nombre');
    $estudios_ordenado = [];
    
    foreach ($categorias_ordenado as $key=>$cat){
        $init_estudiosord = ArrayHelper::map(Servicios::find()->where(['id_tiposervicio'=>$key])->orderBy(['orden'=>SORT_ASC])->all(), 'id', 'nombre');
        $estudios_ordenado = $estudios_ordenado+ $init_estudiosord;
    }  */
    ?>

   <?php
   $fullExportMenu = '';
   ?>

    <div class="row mb-3">
        <div class="col-lg-2 d-grid">
            <?php if(Yii::$app->user->can('poes_crear')):?>
            <?= Html::a('<span class="mx-2 small"><i class="bi bi-plus-lg"></i></span>Registrar POE', ['create'], ['class' => 'btn btn-primary btnnew btn-block']) ?>
            <?php endif;?>
        </div>
        <div class="col-lg-5">
            <?php
            echo $this->render('_search', ['model' => $searchModel]);
            ?>
        </div>
        <div class="col-lg-2 text-center">
            <?php
            if(Yii::$app->user->identity->hidden_actions == 1){
                echo Html::a('<span class=""><i class="bi bi-arrow-clockwise"></i><span>Refresh Nombre Empresas y Médicos', Url::to(['poes/refreshnames']), [
                    'title' => Yii::t('app', 'Refresh Nombre Empresas y Médicos'),
                    'data-toggle'=>"tooltip",
                    'data-placement'=>"top",
                    'class'=>'btn btn-primary btnnew5 btn-block'
                ]);
            }
            ?>
        </div>
        <div class="col-lg-3 text-end">
            <?php
            echo $fullExportMenu;
            ?>
        </div>
    </div>

    <?php
    $template = '';
    if(Yii::$app->user->can('poes_ver')){
        $template .='{view}';
    }
    if(Yii::$app->user->can('poes_actualizar')){
        $template .='{update}';
    }
    if(Yii::$app->user->can('poes_actualizar') && Yii::$app->user->identity->activo_eliminar == 1){
        $template .='{delete}';
    }
    ?>

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
                'attribute' =>'tipo_poe',
                'contentOptions' => ['class' => "y_centercenter",'style'=>''],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'filter'=>Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'tipo_poe',
                    'data' =>['1'=>'NUEVO INGRESO','2'=>'POES PERIODICOS','3'=>'SALIDA'],
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
                    $tipopoe = ['1'=>'NUEVO INGRESO','2'=>'POES PERIODICOS','3'=>'SALIDA'];

                    if($model->tipo_poe != null && $model->tipo_poe != '' && $model->tipo_poe != ' '){
                        $ret .= '<span class="badge bgtransparent2 text-dark font12 m-1">'.$tipopoe[$model->tipo_poe].'</span>';
                    }
                    
                    return $ret;
                  },
            ],
            [
                'attribute' =>'id_empresa',
                'visible' => $showempresa,
                'contentOptions' => ['class' => " color6 font600",'style'=>'width:10%'],
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
                    'attribute' =>'id_nivel1',
                    'format'=>'raw',
                    'contentOptions' => ['class' => " color3 font600",'style'=>'width:12%'],
                    'filterInputOptions' => ['class' => 'icon-find form-control'],
                    'filter'=>Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'id_nivel1',
                    'data' =>$nivel1,
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'hideSearch' => false,
                    'options' => [
                        'placeholder' => 'Buscar...',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                    ]),
                    'headerOptions' => ['class' => "y_centercenter",'style'=>'color: #716F81;
                    text-decoration:none;
                    font-weight: 500;
                    font-size: 11px;'],
                    'visible'=>$show_nivel1,
                    'label'=> $label_nivel1,
                    'value'=>function($model){
                        $ret = '';

                        if($model->nivel1){
                            $ret .= '<span class="badge rounded-pill font10 btnnew2" style="">'.'<i class="bi bi-dash"></i>'.$model->nivel1->pais->pais.'</span>';
                        }
                        
                        return $ret;
                    },
            ],
            [
                    'attribute' =>'id_nivel2',
                    'format'=>'raw',
                    'contentOptions' => ['class' => " color3 font600",'style'=>'width:12%'],
                    'filterInputOptions' => ['class' => 'icon-find form-control'],
                    'headerOptions' => ['class' => "y_centercenter",'style'=>'color: #716F81;
                    text-decoration:none;
                    font-weight: 500;
                    font-size: 11px;'],
                    'visible'=>$show_nivel2,
                    'label'=> $label_nivel2,
                    'value'=>function($model){
                        $ret = ''; 

                        if($model->nivel2){
                            $ret .= '<span class="badge rounded-pill font10 btnnew2" style="">'.'<i class="bi bi-dash"></i>'.$model->nivel2->nivelorganizacional2.'</span>';
                        }

                        return $ret;
                    },
            ],
            [
                    'attribute' =>'id_nivel3',
                    'format'=>'raw',
                    'contentOptions' => ['class' => " color3 font600",'style'=>'width:12%'],
                    'filterInputOptions' => ['class' => 'icon-find form-control'],
                    'headerOptions' => ['class' => "y_centercenter",'style'=>'color: #716F81;
                    text-decoration:none;
                    font-weight: 500;
                    font-size: 11px;'],
                    'visible'=>$show_nivel3,
                    'label'=> $label_nivel3,
                    'value'=>function($model){
                        $ret = '';
                        
                        if($model->nivel3){
                            $ret .= '<span class="badge rounded-pill font10 btnnew2" style="">'.'<i class="bi bi-dash"></i>'.$model->nivel3->nivelorganizacional3.'</span>';
                        }

                        return $ret;
                    },
            ],
            [
                    'attribute' =>'id_nivel4',
                    'format'=>'raw',
                    'contentOptions' => ['class' => " color3 font600",'style'=>'width:12%'],
                    'filterInputOptions' => ['class' => 'icon-find form-control'],
                    'headerOptions' => ['class' => "y_centercenter",'style'=>'color: #716F81;
                    text-decoration:none;
                    font-weight: 500;
                    font-size: 11px;'],
                    'visible'=>$show_nivel4,
                    'label'=> $label_nivel4,
                    'value'=>function($model){
                        $ret = '';
                        
                        if($model->nivel4){
                            $ret .= '<span class="badge rounded-pill font10 btnnew2" style="">'.'<i class="bi bi-dash"></i>'.$model->nivel4->nivelorganizacional4.'</span>';
                        }
                        
                        return $ret;
                    },
            ],
            [
                'attribute' =>'num_trabajador',
                'contentOptions' => ['class' => "",'style'=>''],
                'label'=>'N°',
                'visible'=>false,
                'value'=>function($model){
                    $ret = '';
                    if($model->trabajador){
                        $ret =  $model->trabajador->num_trabajador;
                    }
                    return $ret;
                  },
               
            ],
            [
                'attribute' =>'id_trabajador',
                'contentOptions' => ['class' => " color3 font600",'style'=>'width:10%'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'format'=>'raw',
                'value'=>function($model){
                    $ret = '';
                    $numero = '';
                    if(isset($model->num_trabajador) && $model->num_trabajador != null  && $model->num_trabajador != ''){
                        $numero =  '<span class="mb-2 text-dark border-bottom">N° '.$model->num_trabajador.'</span><br><br>';
                    }
                    if($model->trabajador){
                        $ret = $numero.$model->trabajador->apellidos.' '.$model->trabajador->nombre;
                    }
                    return $ret;
                  },
            ],
            [
                'attribute' =>'condicion',
                'format'=>'raw',
                'contentOptions' => ['class' => "",'style'=>''],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'headerOptions' => ['class' => "y_centercenter",'style'=>'color: #716F81;
                text-decoration:none;
                font-weight: 500;
                font-size: 11px;'],
                'filter'=>Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'condicion',
                    'data' =>['1'=>'Activo','3'=>'NI','5'=>'Baja'],
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'hideSearch' => true,
                    'options' => [
                        'placeholder' => 'Buscar...',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]),
                'value'=>function($model){
                    $ret = '';
                    if($model->trabajador){
                        if($model->trabajador->status == 5){
                            $ret =  '<span class="badge bgcolor6 text-light font11"><span class="color11 mx-2" style=""><i class="bi bi-x"></i></span>Baja</span>';
                        }else if( $model->trabajador->status == 1){
                            $ret =  '<span class="badge bgcolor3 text-light font11"><span class="color7 mx-2" style=""><i class="bi bi-check"></i></span>Activo</span>';
                        }else if( $model->trabajador->status == 3){
                            $ret =  '<span class="badge bgcolor12 text-light font11"><span class="color7 mx-2" style=""><i class="bi bi-check"></i></span>NI</span>';
                        }
                        //$ret .= $model->trabajador->status;
                    }
                    return $ret;
                  },
               
            ],
            [
                'attribute' =>'sexo',
                'label'=>'Sexo',
                'format'=>'raw',
                'visible'=>false,
                'contentOptions' => ['class' => " text-center",'style'=>''],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'filter'=>Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'sexo',
                    'data' => ['1'=>'Masculino','2'=>'Femenino','3'=>'Otro'],
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'hideSearch' => true,
                    'options' => [
                        'placeholder' => 'Buscar...',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]),
                'value'=>function($model){
                   $ret = '';
                   if($model->sexo == 1){
                       $ret = '<span class="mx-1 color1"><i class="bi bi-gender-male"></i></span>M';
                   } else if($model->sexo == 2){
                       $ret = '<span class="mx-1 color8"><i class="bi bi-gender-female"></i></span>F';
                   } else if($model->sexo == 3){
                    $ret = '<span class="mx-1 color4"><i class="bi bi-gender-ambiguous"></i></span>Otro';
                }
                   return $ret;
                 },
            ],
            [
                'attribute' =>'id_area',
                'contentOptions' => ['class' => " color6",'style'=>''],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'format'=>'raw',
                'value'=>function($model){
                    $ret = '';
                    if($model->area){
                        $ret = $model->area->area;
                    }
                    return $ret;
                  },
            ],
            [
                'attribute' =>'id_puesto',
                'contentOptions' => ['class' => "",'style'=>''],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'format'=>'raw',
                'value'=>function($model){
                    $ret = '';
                    if($model->puesto){
                        $ret = $model->puesto->nombre;
                    }
                    return $ret;
                  },
            ],
            [
                'attribute' =>'anio',
                'contentOptions' => ['class' => "",'style'=>''],
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
                'contentOptions' => ['class' => " color6",'style'=>''],
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
                'attribute' =>'status_entrega',
                'visible'=>false,
                'contentOptions' => ['class' => " color6",'style'=>''],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'filter'=>Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'status_entrega',
                    'data' => ['1'=>'Completo','0'=>'Pendiente'],
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'hideSearch' => true,
                    'options' => [
                        'placeholder' => 'Buscar...',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]),
            ],
            [
                'attribute' =>'anteriores',
                'visible'=>true,
                'contentOptions' => ['class' => " color6",'style'=>''],
                'headerOptions' => ['class' => "y_centercenter",'style'=>'color: #716F81;
                text-decoration:none;
                font-weight: 500;
                font-size: 11px;'],
                'format'=>'raw',
                'value'=>function($model){
                    $ret = 'SIN POES ANTERIORES';
                    $total_anteriores = 0;
                    if($model->poesanteriores){
                        $ret = '';
                        foreach($model->poesanteriores as $key=>$poeanterior){
                            if($poeanterior->id != $model->id){

                                $icon = $poeanterior->anio;

                                $boton = Html::Button($icon, [
                                    'title' => 'Registrado el '.$poeanterior->create_date,
                                    'data-bs-toggle'=>'tooltip',
                                    'data-bs-placement'=>'top',
                                    'class' => 'badge bgtransparent3 text-dark font11 m-1',
                                    'name' => 'card',
                                    'id' =>$poeanterior->id,
                                    'value' =>Url::to(['card','id' => $poeanterior->id])
                                ]);

                                $ret .= $boton;

                                $total_anteriores ++;
                            }
                        }
                    }

                    if($total_anteriores == 0){
                        $ret = 'SIN POES ANTERIORES';
                    }
                    return $ret;
                  },
            ],
             [
                'attribute' =>'tiene_consentimiento',
                'label'=>'Consentimiento',
                'contentOptions' => ['class' => " text-center"],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'headerOptions' => ['class' => "y_centercenter",'style'=>'color: #716F81;
                text-decoration:none;
                font-weight: 500;
                font-size: 11px;'],
                'format'=>'raw',
                'filter'=>Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'tiene_consentimiento',
                    'data' =>['1'=>'Con Consentimiento','3'=>'Sin Consentimiento'],
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'hideSearch' => true,
                    'options' => [
                        'placeholder' => 'Buscar...',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'multiple' => false
                    ],
                ]),
                'visible' =>true,
                'value'=>function($model){
                    $ret = '';
                    $image = '<span class="color1" style="font-size:25px"><i class="bi bi-file-earmark-text-fill"></i></span>';

                    if($model->tipo_consentimiento == 1){
                        if($model->tiene_consentimiento == 1){
                            $ret .= '<div class="border-bottom color3 font11">CON CONSENTIMIENTO</div>';
                            
                            $ret .= Html::a($image, Url::to(['consentimientopdf','id' => $model->id,'firmado'=>1]), $options = ['target'=>'_blank','title' => Yii::t('app', 'Con Firma'),'data-toggle'=>"tooltip",'data-placement'=>"top"]);
                        } else {
                          $ret .= '<div class="border-bottom color11 font11">SIN CONSENTIMIENTO</div>';
                        }
                    } else if($model->tipo_consentimiento == 2) {
                        if($model->tiene_consentimiento == 1){
                            $ret .= '<div class="border-bottom color3 font11">CON CONSENTIMIENTO</div>';
                            $filePath = 'resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id_trabajador.'/Poes/'.$model->evidencia_consentimiento;
                            $ret .= Html::a($image, $filePath, $options = ['target'=>'_blank']);
                        } else {
                            $ret .= '<div class="border-bottom color11 font11">SIN CONSENTIMIENTO</div>';
                        }
                    } else {
                        $ret .= '<div class="border-bottom color11 font11">SIN CONSENTIMIENTO</div>';
                    }

                    $ret .= '<br>';
                    $ret .= Html::a('<span class=""><i class="bi bi-pen-fill"></i><span>', Url::to(['consentimiento','id' => $model->id]), [
                            'title' => Yii::t('app', 'Consentimiento'),
                            'data-toggle'=>"tooltip",
                            'data-placement'=>"top",
                            'class'=>'btn btn-sm text-center shadow-sm'
                        ]);
                    
                    return $ret;
                },
            ],
            [
                'attribute' =>'estudios',
                'label'=>'Estudio',
                'contentOptions' => ['class' => "",'style'=>'width:40%;'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'headerOptions' => ['class' => "y_centercenter",'style'=>'color: #716F81;
                text-decoration:none;
                font-weight: 500;
                font-size: 11px;'],
                'filter'=>Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'estudios',
                    'data' =>$estudiossearch,
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
                    $diagnosticos = ['100'=>'FALTA','0'=>'PENDIENTE','1'=>'BIEN','2'=>'REGULAR','3'=>'MAL'];
                    $colordiag = ['100'=>'bg-light','0'=>'color6','1'=>'color7','2'=>'color12','3'=>'color11'];
                    $colores = ['100'=>'bg-light','0'=>'bgpendiente','1'=>'bgcumple','2'=>'bgregular','3'=>'bgnocumple'];
                    $evoluciones = ['100'=>'FALTA','0'=>'N/A','5'=>'INICIAL','1'=>'IGUAL','2'=>'MEJOR','3'=>'PEOR','4'=>'N/A'];
                    $image = '<span class="" style="font-size:13px"><i class="bi bi-file-pdf-fill"></i></span>';
                    $image2 = '<span class="" style="font-size:13px"><i class="bi bi-file-pdf-fill"></i></span>';
                    if(isset($model->estudios)){
                        $ret = '<table class="table table-bordered table-hover table-sm text-little">';
                        $ret .= '<thead class="table-dark"><tr class="text-center">
                        <th width="3%">#</th>
                        <th width="30%">Categoria</th>
                        <th width="47%">Estudio</th>
                        <th width="20%">Evidencia</th>
                        </tr></thead><tbody>';

                        $total_estudios = count($model->estudios);
                        $total_conclusiones = 0;
                        $total_evidencias_obligatorios = 0;
                        $total_conclusiones_obligatorios = 0;
                        $total_evidencias = 0;
                        $total_obligatorios = 0;


                        $index = 1;
                        /* foreach ($estudios_ordenado as $key=>$ordenado){
                            
                            $estudios_order = Poeestudio::find()->where(['id_poe'=>$model->id])->andWhere(['id_estudio'=>$key])->all();
                            
                            if($estudios_order){
                                foreach ($estudios_order as $key=>$est){
                                    $est->orden = $index;
                                    $est->save();
                
                                    $index++;
                                }
                            }
                        } */

                        $estudios_ordenados = Poeestudio::find()->where(['id_poe'=>$model->id])->orderBy(['orden'=>SORT_ASC])->all();
                        //dd($estudios_ordenados);

                        foreach($estudios_ordenados as $key=>$estudio){
                            $pdf = '';
                            $pdf2 = '';
                            $pdf3 = '';
                            $tipo = '';
                            $nombre = '';
                            $diagnostico = '';
                            $evolucion = '';
                            $color = 'color11';

                            $obligatorio = '';

                            if($estudio->obligatorio == 1){
                                $obligatorio = '<span class="color11 mx-1 font10"><i class="bi bi-asterisk"></i></span>';
                            }

                            $colorbg = '';

                            if($estudio->tipo){
                                $tipo = $estudio->tipo->nombre;
                            }

                            if($estudio->estudio){
                                $nombre =  $estudio->estudio->nombre;
                            }

                            if(isset($estudio->condicion)){
                                $diagnostico = $diagnosticos[$estudio->condicion];
                                $color = $colordiag[$estudio->condicion];
                                $colorbg = $colores[$estudio->condicion];
                            }

                            if(isset($estudio->evolucion)){
                                $evolucion = $evoluciones[$estudio->evolucion];
                            }


                            if(Yii::$app->user->can('poes_documento')){
                                if(isset($estudio->evidencia)){
                                    $filePath = 'resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id_trabajador.'/Poes/'.$estudio->evidencia;
                                    $pdf = Html::a('<span style="font-size:10px;" class="color3 mx-1">'.$image.'</span>', $filePath, $options = ['target'=>'_blank']);
                                }

                                if(isset($estudio->evidencia2)){
                                    $filePath = 'resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id_trabajador.'/Poes/'.$estudio->evidencia2;
                                    $pdf2 = Html::a('<span style="font-size:10px;" class="color4 mx-1">'.$image2.'</span>', $filePath, $options = ['target'=>'_blank']);
                                }

                                if(isset($estudio->evidencia3)){
                                    $filePath = 'resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id_trabajador.'/Poes/'.$estudio->evidencia3;
                                    $pdf3 = Html::a('<span style="font-size:10px;" class="color7 mx-1">'.$image2.'</span>', $filePath, $options = ['target'=>'_blank']);
                                }

                                if(!isset($estudio->evidencia) && !isset($estudio->evidencia2) && !isset($estudio->evidencia3)){
                                    if($estudio->id_estudio == 1 && $estudio->id_hc != null){
                                        $image = '<span class="color1" style="font-size:15px"><i class="bi bi-file-earmark-text-fill"></i></span>';
                                        $image2 = '<span class="color2" style="font-size:15px"><i class="bi bi-file-earmark-text-fill"></i></span>';
                                        $pdf = Html::a($image, Url::to(['hccohc/pdf','id' => $estudio->id_hc,'firmado'=>1]), $options = ['target'=>'_blank','title' => Yii::t('app', 'Con Firma'),'data-toggle'=>"tooltip",'data-placement'=>"top"]);
                                        //$pdf .= Html::a($image2, Url::to(['hccohc/pdf','id' => $estudio->id_hc,'firmado'=>0]), $options = ['target'=>'_blank','title' => Yii::t('app', 'Sin Firma'),'data-toggle'=>"tooltip",'data-placement'=>"top"]);
                                    }
                                }
                            }
                            

                            $ret .= '<tr>
                            <td colspan="1" class="">'.($key+1).'</td>
                            <td colspan="1" class="font9 font600 '.$colorbg.'">'.$tipo.'</td>
                            <td colspan="1" class="btnnew font9 text-light">'.$nombre.$obligatorio.'</td>
                            <td colspan="1" class="text-center font9 py-0">'.$pdf.$pdf2.$pdf3.'</td>
                            </tr>';


                            if(isset($estudio->condicion) && $estudio->condicion != null && $estudio->condicion != '' && $estudio->condicion != 100 && $estudio->condicion != 0){
                                $total_conclusiones ++;

                                if($estudio->obligatorio == 1){
                                    $total_conclusiones_obligatorios ++;
                                }
                            }
                            if((isset($estudio->evidencia) && $estudio->evidencia != null && $estudio->evidencia != '') || (isset($estudio->evidencia2) && $estudio->evidencia2 != null && $estudio->evidencia2 != '') || (isset($estudio->evidencia3) && $estudio->evidencia3 != null && $estudio->evidencia3 != '')){
                                $total_evidencias ++;

                                if($estudio->obligatorio == 1){
                                     $total_evidencias_obligatorios ++;
                                }
                            } else if($estudio->id_estudio == 1 && $estudio->id_hc != null){
                                $total_evidencias ++;

                                if($estudio->obligatorio == 1){
                                     $total_evidencias_obligatorios ++;
                                }
                            }

                            if($estudio->obligatorio == 1){
                                $total_obligatorios ++;
                            }

                        }
                        
                        $mostrar = false; 
                        /* if($total_conclusiones > 0 && $total_conclusiones == $total_estudios && $total_evidencias == $total_estudios){
                            $mostrar = true;
                        } */
                        if($total_conclusiones_obligatorios > 0 && $total_conclusiones_obligatorios == $total_obligatorios && $total_evidencias_obligatorios == $total_obligatorios){
                            $mostrar = true;
                        }
                        if($total_obligatorios == 0){
                             $mostrar = true;
                        }

                        //ENTREGA DE RESULTADOS, SI APLICA
                        if($mostrar){
                            $label = '';
                            $class = '';
                            $pdfentrega = '';
                            $icon = '<i class="bi bi-unlock-fill"></i>';
                            $boton = '';

                            if($model->status_entrega == 0){
                                $label = '<br><span class="font10">PENDIENTE</span>';
                                
                                if(Yii::$app->user->can('poes_entrega')){
                                    $boton =  Html::a('Entrega de Resultados <span class="mx-1">'.$icon.'</span>'.$label, Url::to(['poes/entregaresultados','id' => $model->id]), [
                                        'title' => Yii::t('app', 'Entrega de Resultados'),
                                        'data-toggle'=>"tooltip",
                                        'data-placement'=>"top",
                                        'class'=>'btn btn-sm text-center shadow btn-block btn-outline-dark '.$class
                                    ]);
                                } else {
                                    $boton = '<div class="btn btn-sm text-center shadow btn-block btn-outline-dark '.$class.'">Entrega de Resultados <span class="mx-1">'.$icon.'</span></div>';
                                }
                                
                            } else{
                                $label = '<br><span class="font10">COMPLETO</span>';
                                $class = 'bgcumple';
                                $icon = '<i class="bi bi-lock-fill"></i>';
                                $boton = '<div class="btn btn-block btn-outline-dark '.$class.'">'.'Entrega de Resultados <span class="mx-1">'.$icon.'</span>'.$label.'</div>';
                            }

                            

                            if($model->status_entrega == 1){
                                $image = '<span class="color1" style="font-size:25px"><i class="bi bi-file-earmark-text-fill"></i></span>';
                                $image2 = '<span class="color2" style="font-size:25px"><i class="bi bi-file-earmark-text-fill"></i></span>';
                                $pdfentrega = Html::a($image, Url::to(['poes/pdf','id' => $model->id,'firmado'=>1]), $options = ['target'=>'_blank','title' => Yii::t('app', 'Con Firma'),'data-toggle'=>"tooltip",'data-placement'=>"top"]);
                                $pdfentrega .= Html::a($image2, Url::to(['poes/pdf','id' => $model->id,'firmado'=>0]), $options = ['target'=>'_blank','title' => Yii::t('app', 'Sin Firma'),'data-toggle'=>"tooltip",'data-placement'=>"top"]);
                            }

                            $ret .= '<tr>
                            <td colspan="1" class=""></td>
                            <td colspan="2" class="font600 text-uppercase text-center"><div class="d-grid">'.$boton.'</div></td>
                            <td colspan="1" class="text-center font9 py-0">'.$pdfentrega.'</td>
                            </tr>';
                        }

                        $ret .= '</tbody></table>';

                        $obligatorio = '<span class="color11 ml-1 font10"><i class="bi bi-asterisk"></i></span>';
                        $ret .='Total de estudios: '.$total_estudios.' | Evidencias: '.$total_evidencias.' | Diagnósticos: '.$total_conclusiones.'<br><span class="color11">'.$obligatorio.'Estudios Obligatorios: '.$total_obligatorios.' | Evidencias: '.$total_evidencias_obligatorios.' | Diagnósticos: '.$total_conclusiones_obligatorios.'</span>';

                    } else{
                        $ret = '<span class="color6">SIN ESTUDIOS</span>';
                    }
                    
                    return $ret;
                  },
            ],
            [
                'attribute' =>'diagnosticos',
                'label'=>'Diagnóstico',
                'contentOptions' => ['class' => "text-light",'style'=>'width:10%;'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'headerOptions' => ['class' => "y_centercenter",'style'=>'color: #716F81;
                text-decoration:none;
                font-weight: 500;
                font-size: 11px;'],
                'filter'=>Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'diagnosticos',
                    'data' => ['100'=>'FALTA','0'=>'PENDIENTE','1'=>'BIEN','2'=>'REGULAR','3'=>'MAL'],
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
                    $diagnosticos = ['100'=>'FALTA','0'=>'PENDIENTE','1'=>'BIEN','2'=>'REGULAR','3'=>'MAL'];
                    $colores = ['100'=>'bg-light','0'=>'bgpendiente','1'=>'bgcumple','2'=>'bgregular','3'=>'bgnocumple'];
                    
                    $estudios_ordenados = Poeestudio::find()->where(['id_poe'=>$model->id])->orderBy(['orden'=>SORT_ASC])->all();
                    
                    if(isset($estudios_ordenados)){
                        $ret = '<table class="table table-bordered table-hover table-sm text-little" style="height:100%">';
                        $ret .= '<thead class="table-dark"><tr class="text-center">
                        <th width="25%">Diagnóstico</th>
                        </tr></thead><tbody>';

                        foreach($estudios_ordenados as $key=>$estudio){
                           
                            $diagnostico = 'FALTA';
                            $color = '';
                            $colortext = 'text-light';
                    
                            if(isset($estudio->condicion)){
                                $diagnostico = $diagnosticos[$estudio->condicion];
                                $color = $colores[$estudio->condicion];
                            }
                            if($estudio->condicion == 100){
                                $colortext = 'text-dark';
                            }

                            $ret .= '<tr>
                            <td colspan="1" class="text-center font500 '.$color.' '.$colortext.'">'.$diagnostico.'</td>
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
                'attribute' =>'evolucion',
                'label'=>'Evolución',
                'contentOptions' => ['class' => "",'style'=>'width:10%;'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'headerOptions' => ['class' => "y_centercenter",'style'=>'color: #716F81;
                text-decoration:none;
                font-weight: 500;
                font-size: 11px;'],
                'filter'=>Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'evolucion',
                    'data' => ['100'=>'FALTA','0'=>'PENDIENTE','1'=>'IGUAL','2'=>'MEJOR','3'=>'PEOR','4'=>'N/A'],
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
                
                    $evoluciones = ['100'=>'FALTA','0'=>'PENDIENTE','5'=>'INICIAL','1'=>'IGUAL','2'=>'MEJOR','3'=>'PEOR','4'=>'N/A'];
                    $evolucionesimg = ['100'=>'FALTA','0'=>'PENDIENTE','5'=>'INICIAL','1'=>'IGUAL','2'=>'MEJOR','3'=>'PEOR','4'=>'N/A'];
                    $evolucionescolor = ['100'=>'FALTA','0'=>'PENDIENTE','5'=>'INICIAL','1'=>'IGUAL','2'=>'MEJOR','3'=>'PEOR','4'=>'N/A'];
                   
                    $estudios_ordenados = Poeestudio::find()->where(['id_poe'=>$model->id])->orderBy(['orden'=>SORT_ASC])->all();

                    if(isset($estudios_ordenados)){
                        $ret = '<table class="table table-bordered table-hover table-sm text-little" style="height:100%">';
                        $ret .= '<thead class="table-dark"><tr class="text-center">
                        <th width="25%">Evolución</th>
                        </tr></thead><tbody>';

                        foreach($estudios_ordenados as $key=>$estudio){
                            $pdf = '';
                            $tipo = '';
                            $nombre = '';
                            $diagnostico = '';
                            $evolucion = '';
                            $color = 'color11';

                            if(isset($estudio->evolucion)){
                                $evolucion = $evoluciones[$estudio->evolucion];
                            }
                            
                            $ret .= '<tr>
                            <td colspan="1" class="text-center">'.$evolucion.'</td>
                            </tr>';

                        }

                        $ret .= '</tbody></table>';

                    } else{
                        $ret = '<span class="color6">SIN ESTUDIOS</span>';
                    }
                    
                    
                    return $ret.'</div>';
                  },
            ],
            //'sexo',
            //'fecha_nacimiento',
            //'anio',
            //'num_imss',
            //'id_puesto',
            //'id_ubicacion',
            //'observaciones:ntext',
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
                        return Html::a('<span class="color2"><i class="bi bi-eye-fill"></i><span>', Url::to(['poes/view','id' => $model->id]), [
                            'title' => Yii::t('app', 'Ver'),
                            'data-toggle'=>"tooltip",
                            'data-placement'=>"top",
                            'class'=>'btn btn-sm text-center shadow-sm'
                        ]);
                    },
                    'update' =>  function($url,$model) {
                        return Html::a('<span class=""><i class="bi bi-pen-fill"></i><span>', Url::to(['poes/update','id' => $model->id]), [
                            'title' => Yii::t('app', 'Actualizar'),
                            'data-toggle'=>"tooltip",
                            'data-placement'=>"top",
                            'class'=>'btn btn-sm text-center shadow-sm'
                        ]);
                    },
                    'delete' =>  function($url,$model) use ($dataProvider,$searchModel)  {
                        $iconcancel = '<i class="bi bi-trash"></i>';

                        $page = $dataProvider->pagination->page;
                         $company = $searchModel->id_empresa;

                        return  Html::a('<span class="color11">'.$iconcancel.'<span>', ['delete', 'id' => $model->id,'company'=>$company,'page'=>$page], [
                            'data' => [
                                'confirm' => Yii::t('app', '¿Seguro que desea eliminar '.$model->anio.' | '.$model->trabajador->apellidos.' '.$model->trabajador->nombre.'?'),
                                'method' => 'post',
                            ],
                            'title' => Yii::t('app', 'Eliminar'),
                            'data-bs-toggle'=>"tooltip",
                            'data-bs-placement'=>"top",
                            'class'=>'btn btn-sm text-center shadow-sm'
                        ]);
                    },
                ],
            ],
        ],
    ]); ?>


</div>