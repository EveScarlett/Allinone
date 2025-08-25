<?php

use app\models\PuestosTrabajo;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use kartik\export\ExportMenu;
use kartik\select2\Select2;
use app\models\Riesgos;
use app\models\Empresas;
use app\models\Epps;
use app\models\Areascuestionario;
use app\models\Preguntas;
use yii\helpers\ArrayHelper;
use app\models\Estudios;


use app\models\NivelOrganizacional1;
use app\models\NivelOrganizacional2;
use app\models\NivelOrganizacional3;
use app\models\NivelOrganizacional4;

use app\models\Ubicaciones;
use app\models\Paises;
use app\models\Paisempresa;

/** @var yii\web\View $this */
/** @var app\models\PuestosTrabajoSearch $searchModel */
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
$this->title = 'Puestos de Trabajo'.$name_empresa;
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

$riesgos = ArrayHelper::map(Riesgos::find()->orderBy('riesgo')->all(), 'id', 'riesgo');
$epps = ArrayHelper::map(Epps::find()->orderBy('epp')->all(), 'id', 'epp');
$estudios = ArrayHelper::map(Estudios::find()->orderBy('estudio')->all(), 'id', 'estudio');

$preguntas = ArrayHelper::map(Preguntas::find()->where(['in','id_tipo_cuestionario',[4]])->orderBy('pregunta')->all(), 'id', 'pregunta');

$factores = ArrayHelper::map(Areascuestionario::find()->where(['in','id_pregunta',[30,31,32]])->orderBy('id_pregunta')->all(), 'id', function($model) use ($preguntas ){
    $ret = $model['nombre'];
    if($model['id_pregunta'] != '32'){
        $ret .=' - '.$preguntas[$model['id_pregunta']];
    }
    return $ret;
});
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
<div class="puestos-trabajo-index">

    <h1 class="title1"><?= Html::encode($this->title) ?></h1>

    <?php 
    $fullExportMenu = '';
      if(Yii::$app->user->can('puestos_exportar')){
        $fullExportMenu = ExportMenu::widget([
            'dataProvider' => $dataProvider,
            'columns' =>[
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'attribute' =>'nombre',
                ],
                [
                    'attribute' =>'descripcion',
                ],
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
                    'attribute' =>'riesgos',
                    'value'=>function($model){
                        $ret = '';
                        if($model->riesgos){
                            foreach($model->riesgos as $key=>$riesgo){
                                $ret.= $riesgo->riesgo;
                                if($key < (count($model->riesgos)-1)){
                                    $ret .= ',';
                                }
                            }
                        }
                        return $ret;
                      },
                ],
                [
                    'attribute' =>'epps',
                    'label'=>'EPP',
                    'value'=>function($model){
                        $ret = '';
                        if($model->epps){
                            foreach($model->epps as $key=>$epp){
                                $ret.= $epp->epp;
                                if($key < (count($model->epps)-1)){
                                    $ret .= ',';
                                }
                            }
                        }
                        return $ret;
                      },
                ],
                [
                    'attribute' =>'estudios',
                    'label'=>'Requisitos del Puesto',
                    'value'=>function($model){
                        $ret = '';
                       
                        if($model->empresa->requisitosactivos){
                            foreach($model->empresa->requisitosactivos as $key=>$estudio){
                                $ret.= $estudio->estudio->estudio;
                                if($key < (count($model->empresa->requisitosactivos)-1)){
                                    $ret .= ',';
                                }
                            }
                        }
    
                        if($model->pestudiosactivos){
                            $ret .= ',';
                            foreach($model->pestudiosactivos as $key=>$estudio){
                                $ret.= $estudio->estudio->estudio;
                                if($key < (count($model->pestudiosactivos)-1)){
                                    $ret .= ',';
                                }
                            }
                        }
    
                        return $ret;
                      },
                ],
                [
                    'attribute' =>'create_date',
                    'label'=>'Fecha Alta',
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
                [
                    'attribute' =>'status',
                    'value'=>function($model){
                        $ret = '';
    
                        if($model->status == 0){
                            $ret =  'Baja';
                        }else if( $model->status == 1){
                            $ret =  'Activo';
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
            <?php if(Yii::$app->user->can('puestos_crear')):?>
            <?= Html::a('<span class="mx-2 small"><i class="bi bi-plus-lg"></i></span>Nuevo Puesto de Trabajo', ['create'], ['class' => 'btn btn-primary btnnew btn-block']) ?>
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
    if(Yii::$app->user->can('puestos_ver')){
        $template .='{view}';
    }
    if(Yii::$app->user->can('puestos_actualizar')){
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
                'attribute' =>'nombre',
                'contentOptions' => ['class' => "color3 font600",'style'=>'width:15%'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'format'=>'raw',
                'value'=>function($model){
                    $ret = '';
                    $ret .= $model->nombre;

                    $ret .= '<br><div class="p-2 bg-light rounded-3 small text-dark font500">'.$model->descripcion.'</div>';
                    return $ret;
                  },
            ],
           /*  'descripcion', */
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
                'visible'=>false,
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
                'visible'=>false,
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
                'visible'=>false,
                'value'=>function($model){
                    $ret = '';
                    if($model->ubicacion){
                        $ret .='<span class="badge rounded-pill font10 bg-light text-dark">'.$model->ubicacion->ubicacion.'</span>';
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
                'attribute' =>'riesgos',
                'contentOptions' => ['class' => "color6 font600",'style'=>'width:12%'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'headerOptions' => ['class' => "y_centercenter",'style'=>'color: #716F81;
                text-decoration:none;
                font-weight: 500;
                font-size: 11px;'],
                'filter'=>Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'riesgos',
                    'data' =>$riesgos,
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
                    if($model->riesgos){
                        foreach($model->riesgos as $key=>$riesgo){
                            $ret.= '<span class="badge bgtransparent1 text-dark font12 m-1">'.$riesgo->riesgo.'</span>';
                        }
                    }
                    return $ret;
                  },
            ],
            [
                'attribute' =>'epps',
                'label'=>'EPP',
                'contentOptions' => ['class' => "color6 font600",'style'=>'width:12%'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'headerOptions' => ['class' => "y_centercenter",'style'=>'color: #716F81;
                text-decoration:none;
                font-weight: 500;
                font-size: 11px;'],
                'filter'=>Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'epps',
                    'data' =>$epps,
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
                    if($model->epps){
                        foreach($model->epps as $key=>$epp){
                            $ret.= '<span class="badge rounded-pill bg-light text-dark font10">'.$epp->epp.'</span>';
                        }
                    }
                    return $ret;
                  },
            ],
            [
                'attribute'=>'avatar',
                'format'=>'raw',
                'label'=>'Avatar',
                'headerOptions'=>['style'=>'width:18%;','class'=>'font12'],
                'contentOptions'=>['class'=>'font500'],
                'filterInputOptions' => ['class' => 'icon-find form-control','placeholder'=>'Buscar...'],
                'visible'=>true,
                "value"=>function($model) use ($factores){
                    $ret = '';

                    $iconnumber1 = '<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="currentColor" class="bi bi-1-circle-fill" viewBox="0 0 16 16">
                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M9.283 4.002H7.971L6.072 5.385v1.271l1.834-1.318h.065V12h1.312z"/>
                    </svg>';

                    $iconnumber2 = '<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="currentColor" class="bi bi-2-circle-fill" viewBox="0 0 16 16">
                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M6.646 6.24c0-.691.493-1.306 1.336-1.306.756 0 1.313.492 1.313 1.236 0 .697-.469 1.23-.902 1.705l-2.971 3.293V12h5.344v-1.107H7.268v-.077l1.974-2.22.096-.107c.688-.763 1.287-1.428 1.287-2.43 0-1.266-1.031-2.215-2.613-2.215-1.758 0-2.637 1.19-2.637 2.402v.065h1.271v-.07Z"/>
                    </svg>';

                    $iconnumber3 = '<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="currentColor" class="bi bi-3-circle-fill" viewBox="0 0 16 16">
                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-8.082.414c.92 0 1.535.54 1.541 1.318.012.791-.615 1.36-1.588 1.354-.861-.006-1.482-.469-1.54-1.066H5.104c.047 1.177 1.05 2.144 2.754 2.144 1.653 0 2.954-.937 2.93-2.396-.023-1.278-1.031-1.846-1.734-1.916v-.07c.597-.1 1.505-.739 1.482-1.876-.03-1.177-1.043-2.074-2.637-2.062-1.675.006-2.59.984-2.625 2.12h1.248c.036-.556.557-1.054 1.348-1.054.785 0 1.348.486 1.348 1.195.006.715-.563 1.237-1.342 1.237h-.838v1.072h.879Z"/>
                    </svg>';

                    if(isset($model->medida1) || isset($model->medida2) || isset($model->medida3) || isset($model->sexo)){
                        $filePath =  '/resources/images/avatar.png';
                        $foto = Html::img('@web'. $filePath, ['alt'=>' ', 'class' => "img-responsive text-center", 'width' => '80px', 'height' =>'auto','style'=>'']);
                        //$ret = $foto;

                        if(isset($model->carga) && $model->carga != null && $model->carga != ''){
                            
                            $ret .= '<table class="table table-hover table-bordered table-sm font11 text-little" style="height:100%">
                            <thead class="font500">
                                <tr>
                                    <td class="text-center" width="100%">Carga de Peso</td>
                                </tr>
                            </thead>
                            <tbody><tr>
                            <td class="text-center text-uppercase y_centercenter font500 bgcolor8 text-light">'.$model->carga.' kilos</td>
                            </tr></tbody></table>';

                        }

                        $bloque1 = 'bgnocumple';
                        $bloque2 = 'bgnocumple';
                        $bloque3 = 'bgnocumple';
                        $bloque4 = 'bgnocumple';
                        $bloque5 = 'bgnocumple';
                        $bloque6 = 'bgnocumple';
                        $bloque7 = 'bgnocumple';
                        
                        if($model->cargamaxima == 5){
                            $bloque5 = 'bgcumple';
                        } else if($model->cargamaxima == 4){
                            $bloque4 = 'bgcumple';
                            $bloque5 = 'bgcumple';
                            $bloque7 = 'bgcumple';
                        } else if($model->cargamaxima == 3){
                            $bloque4 = 'bgcumple';
                            $bloque5 = 'bgcumple';
                            $bloque6 = 'bgcumple';
                            $bloque7 = 'bgcumple';
                        } else if($model->cargamaxima == 2){
                            $bloque3 = 'bgcumple';
                            $bloque4 = 'bgcumple';
                            $bloque5 = 'bgcumple';
                            $bloque6 = 'bgcumple';
                            $bloque7 = 'bgcumple';
                        } else if($model->cargamaxima == 1){
                            $bloque1 = 'bgcumple';
                            $bloque2 = 'bgcumple';
                            $bloque3 = 'bgcumple';
                            $bloque4 = 'bgcumple';
                            $bloque5 = 'bgcumple';
                            $bloque6 = 'bgcumple';
                            $bloque7 = 'bgcumple';
                        } else {
                            /* $bloque1 = '';
                            $bloque2 = '';
                            $bloque3 = '';
                            $bloque4 = '';
                            $bloque5 = '';
                            $bloque6 = '';
                            $bloque7 = ''; */
                        }
                        $ret .= '<table class="table table-hover table-bordered table-sm font11 text-little" style="height:100%">
                        <thead class="font500">
                            <tr>
                                <td class="text-center" width="40%">Género</td>
                                <td class="text-center" width="60%">Edad</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center text-uppercase y_centercenter '.$bloque1.'" id ="g1">Femenino</td>
                                <td class="text-center text-uppercase y_centercenter '.$bloque1.'" id ="e1">Menores de 18</td>
                            </tr>
                            <tr>
                                <td class="text-center text-uppercase y_centercenter '.$bloque2.'" id ="g2">Masculino</td>
                                <td class="text-center text-uppercase y_centercenter '.$bloque2.'" id ="e1">Menores de 18</td>
                            </tr>
                            <tr>
                                <td class="text-center text-uppercase y_centercenter '.$bloque3.'" id ="g3">Femenino</td>
                                <td class="text-center text-uppercase y_centercenter '.$bloque3.'" id ="e2">Embarazadas</td>
                            </tr>
                            <tr>
                                <td class="text-center text-uppercase y_centercenter '.$bloque4.'" id ="g4">Femenino</td>
                                <td class="text-center text-uppercase y_centercenter '.$bloque4.'" id ="e3">Entre 18 y 45</td>
                            </tr>
                            <tr>
                                <td class="text-center text-uppercase y_centercenter '.$bloque5.'" id ="g5">Masculino</td>
                                <td class="text-center text-uppercase y_centercenter '.$bloque5.'" id ="e4">Entre 18 y 45</td>
                            </tr>
                            <tr>
                                <td class="text-center text-uppercase y_centercenter '.$bloque6.'" id ="g6">Femenino</td>
                                <td class="text-center text-uppercase y_centercenter '.$bloque6.'" id ="e5">Mayores de 45*</td>
                            </tr>
                            <tr>
                                <td class="text-center text-uppercase y_centercenter '.$bloque7.'" id ="g6">Masculino</td>
                                <td class="text-center text-uppercase y_centercenter '.$bloque7.'" id ="e5">Mayores de 45*</td>
                            </tr>
                            </tbody>
                            </table>';
    
    
                        $ret .= '<table class="table table-hover table-bordered table-sm font11 text-little" style="height:100%">
                        <thead class="font500">
                            <tr>
                                <td class="text-center" width="80%">Medida</td>
                                <td class="text-center" width="20%">Rango</td>
                            </tr>
                        </thead>
                        <tbody>';
                        $medida = '';
                        if(isset($model->medida1) && $model->medida1 != null && $model->medida1 != ''){
                            $medida = $factores[$model->medida1];
                            $ret.= '<tr><td><span class="color3 mx-2">'.$iconnumber1.'</span>'.$medida.'</td>';
                            $ret.= '<td>'.$model->rango1desde.' - '.$model->rango1hasta.'</td></tr>';
                        }
    
                        $medida = '';
                        if(isset($model->medida2) && $model->medida2 != null && $model->medida2 != ''){
                            $medida = $factores[$model->medida2];
                            $ret.= '<tr><td><span class="color4 mx-2">'.$iconnumber2.'</span>'.$medida.'</td>';
                            $ret.= '<td>'.$model->rango2desde.' - '.$model->rango2hasta.'</td></tr>';
                        }
    
                        $medida = '';
                        if(isset($model->medida3) && $model->medida3 != null && $model->medida3 != ''){
                            $medida = $factores[$model->medida3];
                            $ret.= '<tr><td><span class="color14 mx-2">'.$iconnumber3.'</span>'.$medida.'</td>';
                            $ret.= '<td>'.$model->rango3desde.' - '.$model->rango3hasta.'</td></tr>';
                        }

                        $ret.=' </tbody>
                    </table>';
                    } else{
                        $ret .= 'NO DEFINIDO';
                    }
                    
                    return $ret;
                }
            ],
            [
                'attribute' =>'estudios',
                'label'=>'Requisitos del Puesto',
                'contentOptions' => ['class' => "",'style'=>'width:30%'],
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
                    $periodicidad = ['0'=>'INDEFINIDO','1'=>'15 DÍAS','2'=>'1 MES','3'=>'2 MESES','4'=>'3 MESES','5'=>'4 MESES','6'=>'5 MESES','7'=>'6 MESES','8'=>'7 MESES','9'=>'8 MESES','10'=>'9 MESES','11'=>'10 MESES','12'=>'11 MESES','13'=>'1 AÑO','14'=>'1 AÑO 3 MESES','15'=>'1 AÑO 6 MESES','16'=>'1 AÑO 9 MESES','17'=>'2 AÑOS'];
                    if($model->pestudios){
                        foreach($model->pestudios as $key=>$estudio){
                            //$ret.= '<li class="color3">'.$estudio->estudio->estudio.': <span class="small color6">'.$periodicidad[$estudio->periodicidad].'</span></li>';
                        }
                    }
                    
                    $tipos = ['1'=>'Médico','2'=>'Otro'];
                    $ret = '';
                    $ret = '<table class="table table-dark table-hover table-sm text-little mb-0" style="height:100%">';
                    $ret .= '<thead class="table-dark font600"><tr><td class="text-center">#</td><td class="text-center">Tipo</td><td class="text-center">Estudio</td><td class="text-center">Periodicidad</td></tr></thead><tbody>';
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
                    $periodicidades = ['0'=>'INDEFINIDO','1'=>'15 DÍAS','2'=>'1 MES','3'=>'2 MESES','4'=>'3 MESES','5'=>'4 MESES','6'=>'5 MESES','7'=>'6 MESES','8'=>'7 MESES','9'=>'8 MESES','10'=>'9 MESES','11'=>'10 MESES','12'=>'11 MESES','13'=>'1 AÑO','14'=>'1 AÑO 3 MESES','15'=>'1 AÑO 6 MESES','16'=>'1 AÑO 9 MESES','17'=>'2 AÑOS'];

                    $ret .= '<tr><td class="font600 text-center text-uppercase boxtitle color3" colspan="4">Requisitos Mínimos</td></tr>';
                    

                    $index = 1;
                    if($model->empresa->requisitosactivos){
                        foreach($model->empresa->requisitosactivos as $key=>$estudio){
                            $ret.= '<tr><td class="text-light ">'.($index).'</td><td class="font500 text-center text-uppercase '.$arr_colors[$estudio->id_tipo].'">'.$tipos[$estudio->id_tipo].'</td><td class="font600">'.$estudio->estudio->estudio.'</td><td class="font500 color6 text-center">'.$periodicidades[$estudio->id_periodicidad].'</td></tr>';
                            $index++;
                        }
                    }

                    $ret .= '</tbody></table>';

                    $ret .= '<table class="table table-hover table-sm text-little table-primary" style="height:100%">';
                    $ret .= '<tbody>';

                    if($model->pestudiosactivos){
                        $ret .= '<tr><td class="font600 text-center text-uppercase color3" colspan="4">Requisitos Específicos del puesto</td></tr>';
                        foreach($model->pestudiosactivos as $key=>$estudio){
                            $ret.= '<tr><td class="text-dark">'.($index).'</td><td class="font500 text-center text-uppercase '.$arr_colors[$estudio->id_tipo].'"> '.$tipos[$estudio->id_tipo].'</td><td class="font600">'.$estudio->estudio->estudio.'</td><td class="font500 color6 text-center">'.$periodicidades[$estudio->periodicidad].'</td></tr>';
                            $index++;
                        }
                    }

                    $ret .= '</tbody></table>';



                    $ret .= '<table class="table table-hover table-sm text-little" style="height:100%">';
                    $ret .= '<tbody>';

                    if($model->requisitosniactivos){
                        $ret .= '<tr><td class="font600 text-center text-uppercase border border-secondary color11" colspan="3">Requisitos Nuevo Ingreso</td></tr>';
                        foreach($model->requisitosniactivos as $key=>$estudio){
                            $nombreestudio = '';
                            $periodicidad = '';

                            if($estudio->tipo_doc_examen == 1){
                                if($estudio->estudio){
                                    $nombreestudio = $estudio->estudio->estudio;
                                }
                            } else if($estudio->tipo_doc_examen == 2){
                                if($estudio->examenmedico){
                                    $nombreestudio = $estudio->examenmedico->nombre;
                                }
                            }

                            if($estudio->periodicidad != null && $estudio->periodicidad != '' && $estudio->periodicidad != ' '){
                                $periodicidad = $periodicidades[$estudio->periodicidad];
                            }
                            $ret.= '<tr><td class="text-muted">'.($key+1).'</td><td class="text-secondary">'.$nombreestudio.'</td><td class="text-secondary text-center">'.$periodicidad.'</td></tr>';
                            $index++;
                        }
                    }

                    $ret .= '</tbody></table>';

                    return $ret;
                  },
            ],
           
            [
                'attribute' =>'create_date',
                'label'=>'Fecha Alta',
                'visible'=>false,
                'contentOptions' => ['class' => "color6",'style'=>'width:10%;'],
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
                        return Html::a('<span class="color2"><i class="bi bi-eye-fill"></i><span>', Url::to(['puestostrabajo/view','id' => $model->id]), [
                            'title' => Yii::t('app', 'Ver'),
                            'data-toggle'=>"tooltip",
                            'data-placement'=>"top",
                            'class'=>'btn btn-sm text-center shadow-sm'
                        ]);
                    },
                    'update' =>  function($url,$model) {
                        return Html::a('<span class=""><i class="bi bi-pen-fill"></i><span>', Url::to(['puestostrabajo/update','id' => $model->id]), [
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