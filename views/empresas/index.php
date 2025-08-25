<?php

use app\models\Empresas;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use kartik\export\ExportMenu;
use app\models\ProgramaSalud;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

use app\models\Areas;
use app\models\Paisempresa;
use app\models\Paises;

use app\models\NivelOrganizacional1;
use app\models\NivelOrganizacional2;
use app\models\NivelOrganizacional3;
use app\models\NivelOrganizacional4;

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

$this->title = 'Empresas'.$name_empresa;
$this->params['breadcrumbs'][] = $this->title;

$usuario = Yii::$app->user->identity;
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
<?php
$paises = ArrayHelper::map(Paises::find()->orderBy('pais')->all(), 'id', 'pais');
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

<div class="empresas-index">

    <h1 class="title1"><?= Html::encode($this->title) ?></h1>

    <?php 
    $fullExportMenu = '';
      if(Yii::$app->user->can('empresas_exportar')){
        $fullExportMenu = ExportMenu::widget([
            'dataProvider' => $dataProvider,
            'columns' =>[
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'attribute' =>'razon',
                ],
                [
                    'attribute' =>'contacto',
                ],
                [
                    'attribute' =>'abreviacion',
                ],
               /*  [
                    'attribute' =>'rfc',
                ], */
                [
                    'attribute' =>'pais',
                ],
                [
                    'attribute' =>'estado',
                ],
                [
                    'attribute' =>'ciudad',
                ],
                [
                    'attribute' =>'municipio',
                ],
                [
                    'attribute' =>'contacto',
                ],
                [
                    'attribute' =>'telefono',
                ],
                [
                    'attribute' =>'correo',
                ],
                [
                    'attribute' =>'horario',
                ],
                [
                    'attribute' =>'nivel1',
                    'visible'=>$show_nivel1,
                    'label'=> $label_nivel1,
                    'value'=>function($model){
                        $ret = '';

                        if($model->niveles1){
                            foreach($model->niveles1 as $key=>$data){
                                if($data->pais){
                                    $ret .= $data->pais->pais.',';
                                }
                            }
                        }
                        
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'nivel2',
                    'visible'=>$show_nivel2,
                    'label'=> $label_nivel2,
                    'value'=>function($model){
                        $ret = ''; 

                        if($model->niveles2){
                            foreach($model->niveles2 as $key=>$data){
                                $ret .= $data->nivelorganizacional2.',';
                            }
                        }

                        return $ret;
                    },
                ],
                [
                    'attribute' =>'nivel3',
                    'visible'=>$show_nivel3,
                    'label'=> $label_nivel3,
                     'value'=>function($model){
                        $ret = ''; 

                        if($model->niveles3){
                            foreach($model->niveles3 as $key=>$data){
                                $ret .= $data->nivelorganizacional3.',';
                            }
                        }

                        return $ret;
                    },
                ],
                [
                    'attribute' =>'nivel4',
                    'visible'=>$show_nivel4,
                    'label'=> $label_nivel4,
                     'value'=>function($model){
                        $ret = ''; 

                        if($model->niveles4){
                            foreach($model->niveles4 as $key=>$data){
                                $ret .= $data->nivelorganizacional4.',';
                            }
                        }

                        return $ret;
                    },
                ],
                [
                'attribute' =>'paises',
                'value'=>function($model){
                    $ret = '';

                    $total = count($model->paises);
                    $ret .= '<div class="color5 font10">Registros: <span class="color3">'.$total.'</span></div>';
                    if($model->paises){
                        //dd($model->paises);
                        foreach($model->paises as $key=>$data){
                            if($data->pais){
                                $ret .= '<span class="badge rounded-pill text-dark font10" style="background-color:#6DA9E44b4b;border:none;">'.'<i class="bi bi-dash"></i>'.$data->pais->pais.'</span>';
                            }
                        }
                    }
                    return $ret;
                  },
                ],
                [
                    'attribute' =>'lineas',
                    'value'=>function($model){
                        $ret = '';
                        
                        if($model->lineas){
                            foreach($model->lineas as $key=>$data){
                                $ret .= $data->linea;
                                if($key < (count($model->lineas)-1)){
                                    $ret .= ',';
                                }
                            }
                        }
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'ubicaciones',
                    'value'=>function($model){
                        $ret = '';
    
                        if($model->ubicaciones){
                            foreach($model->ubicaciones as $key=>$ubicacion){
                                $ret .= $ubicacion->ubicacion;
                                if($key < (count($model->ubicaciones)-1)){
                                    $ret .= ',';
                                }
                            }
                        }
                        return $ret;
                      },
                ],
                [
                    'attribute' =>'areas',
                   'value'=>function($model){
                    $ret = '';
                    $areas_array = [];

                    if($model->cantidad_niveles != null && $model->cantidad_niveles != '' && $model->cantidad_niveles != ' '){
                        $areas_array = Areas::find()->where(['id_empresa'=>$model->id])->andWhere(['status'=>1])->andWhere(['nivel'=>$model->cantidad_niveles])->orderBy(['id_superior'=>SORT_ASC])->all();
                    }

                    if($areas_array){
                        if($areas_array){
                            foreach($areas_array as $key=>$data){
                                $ret .= $data->area;
                                if($key < (count($areas_array)-1)){
                                    $ret .= ',';
                                }
                            }
                        }
                    }
                    
                    return $ret;
                    },
                ],
                [
                    'attribute' =>'consultorios',
                    'value'=>function($model){
                        $ret = '';
    
                        if($model->consultorios){
                            foreach($model->consultorios as $key=>$consultorio){
                                $ret.= $consultorio->consultorio;
                                if($key < (count($model->consultorios)-1)){
                                    $ret .= ',';
                                }
                            }
                        }
                        return $ret;
                      },
                ],
                [
                    'attribute' =>'programas',
                    'value'=>function($model){
                        $ret = '';
    
                        if($model->programas){
                            foreach($model->programas as $key=>$programa){
                                $ret.= $programa->nombre;
                                if($key < (count($model->programas)-1)){
                                    $ret .= ',';
                                }
                            }
                        }
                        return $ret;
                      },
                ],
                 [
                    'attribute' =>'turnos',
                    'value'=>function($model){
                        $ret = '';
    
                        if($model->turnos){
                            foreach($model->turnos as $key=>$turno){
                                $ret.= $turno->turno;
                                if($key < (count($model->turnos)-1)){
                                    $ret .= ',';
                                }
                            }
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
<?php
$icon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-buildings-fill" viewBox="0 0 16 16">
<path d="M15 .5a.5.5 0 0 0-.724-.447l-8 4A.5.5 0 0 0 6 4.5v3.14L.342 9.526A.5.5 0 0 0 0 10v5.5a.5.5 0 0 0 .5.5h9a.5.5 0 0 0 .5-.5V14h1v1.5a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5V.5ZM2 11h1v1H2v-1Zm2 0h1v1H4v-1Zm-1 2v1H2v-1h1Zm1 0h1v1H4v-1Zm9-10v1h-1V3h1ZM8 5h1v1H8V5Zm1 2v1H8V7h1ZM8 9h1v1H8V9Zm2 0h1v1h-1V9Zm-1 2v1H8v-1h1Zm1 0h1v1h-1v-1Zm3-2v1h-1V9h1Zm-1 2h1v1h-1v-1Zm-2-4h1v1h-1V7Zm3 0v1h-1V7h1Zm-2-2v1h-1V5h1Zm1 0h1v1h-1V5Z"/>
</svg>';
?>
    <div class="row mb-3">
        <div class="col-lg-3 d-grid">
            <?php if(Yii::$app->user->can('empresas_crear')):?>
            <?= Html::a('<span class="mx-2 small"><i class="bi bi-plus-lg"></i></span>Nueva Empresa', ['create'], ['class' => 'btn btn-primary btnnew btn-block']) ?>
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
    if(Yii::$app->user->can('empresas_ver')){
        $template .='{view}';
    }
    if(Yii::$app->user->can('empresas_actualizar')){
        $template .='{update}';
    }
    if(Yii::$app->user->can('empresas_delete')){
        $template .='{delete}';
    }

    $template .='{updateqrs}';
    ?>

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
                'attribute'=>'logo',
                'format'=>'raw',
                'label'=>'',
                'headerOptions'=>['style'=>'width:5%;','class'=>'font12'],
                'contentOptions'=>['class'=>'font500 ' ,'style'=>'vertical-align: middle;'],
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
                'contentOptions' => ['class' => " color3 font600",'style'=>'width:20%'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'format'=>'raw',
                'value'=>function($model){
                    $ret = '';
                    $ret .= $model->razon.'<br><span class="small color6">'.$model->comercial.'</span>';
                    /* $ret .= '<br><div class="border-top small font500 text-dark">RFC '.$model->rfc.'</div>'; */
                    return $ret;
                  },
            ],
            [
                'attribute' =>'cantidad_niveles',
                'contentOptions' => ['class' => "",'style'=>'width:20%'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'format'=>'raw',
                'value'=>function($model){
                    $ret = '';
                    $cantidad = 'No Definido';
                    $contenido = '';

                    if($model->cantidad_niveles != null && $model->cantidad_niveles != '' && $model->cantidad_niveles != ' '){
                        $cantidad = $model->cantidad_niveles;

                        for ($i = 1; $i <= $model->cantidad_niveles; $i++) {
                            $contenido .= '<p class="color11 mt-1">
                                           Nivel '.$i.': <span class="text-dark">'.$model['label_nivel'.$i].'</span>
                                           </p>';
                        }
                    }
                    $ret .= '<div class="color11 font10">
                             Cantidad de Niveles: <span class="font500 text-dark">'.$cantidad.'</span>
                            </div>'.$contenido;
                    $ret .= '';
                    return $ret;
                  },
            ],
            [
                    'attribute' =>'nivel1',
                    'format'=>'raw',
                    'contentOptions' => ['class' => " color3 font600",'style'=>'width:12%'],
                    'filterInputOptions' => ['class' => 'icon-find form-control'],
                    'visible'=>$show_nivel1,
                    'label'=> $label_nivel1,
                    'value'=>function($model) use ($usuario){
                        $ret = '';

                        if($usuario->nivel1_all == 1){

                            $total = count($model->niveles1);
                            $ret .= '<div class="color5 font10">Registros: <span class="color3">'.$total.'</span></div>';
                            if($model->niveles1){
                                foreach($model->niveles1 as $key=>$data){
                                    if($data->pais){
                                        $ret .= '<span class="badge rounded-pill font10 btnnew2" style="" data-bs-toggle="tooltip" title="Actividad: '.$data->actividad.'">'.'<i class="bi bi-dash"></i>'.$data->pais->pais.'</span>';
                                    }
                                }
                            }

                        } else {
                           
                            $array = explode(',', $usuario->nivel1_select);
                            if($array && count($array)>0){

                            } else {
                                $array = [];
                            }
                            
                            $nivel1 = NivelOrganizacional1::find()->where(['id_empresa'=>$model->id])->andWhere(['status'=>1])->andWhere(['in','id_pais',$array])->orderBy('id_pais')->all();

                            $total = count($nivel1);
                            $ret .= '<div class="color5 font10">Registros: <span class="color3">'.$total.'</span></div>';
                            if($nivel1){
                                foreach($nivel1 as $key=>$data){
                                    if($data->pais){
                                        $ret .= '<span class="badge rounded-pill font10 btnnew2" style="" data-bs-toggle="tooltip" title="Actividad: '.$data->actividad.'">'.'<i class="bi bi-dash"></i>'.$data->pais->pais.'</span>';
                                    }
                                }
                            }
                        }
                        
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'nivel2',
                    'format'=>'raw',
                    'contentOptions' => ['class' => " color3 font600",'style'=>'width:12%'],
                    'filterInputOptions' => ['class' => 'icon-find form-control'],
                    'visible'=>$show_nivel2,
                    'label'=> $label_nivel2,
                    'value'=>function($model) use ($usuario){
                        $ret = ''; 

                        if($usuario->nivel2_all == 1){
                            $total = count($model->niveles2);
                            $ret .= '<div class="color5 font10">Registros: <span class="color3">'.$total.'</span></div>';
                            if($model->niveles2){
                                foreach($model->niveles2 as $key=>$data){
                                    $ret .= '<span class="badge rounded-pill font10 bgcolor9" data-bs-toggle="tooltip" title="Actividad: '.$data->actividad.'"><i class="bi bi-dot"></i>'.$data->nivelorganizacional2.'</span>';
                                }
                            }
                        } else {
                            $array = explode(',', $usuario->nivel2_select);
                            if($array && count($array)>0){
                            } else {
                                $array = [];
                            }
                            $nivel2 = NivelOrganizacional2::find()->where(['id_empresa'=>$model->id])->andWhere(['status'=>1])->andWhere(['in','id',$array])->orderBy('nivelorganizacional2')->all();

                            $total = count($nivel2);
                            $ret .= '<div class="color5 font10">Registros: <span class="color3">'.$total.'</span></div>';
                            if($nivel2){
                                foreach($nivel2 as $key=>$data){
                                    $ret .= '<span class="badge rounded-pill font10 bgcolor9" data-bs-toggle="tooltip" title="Actividad: '.$data->actividad.'"><i class="bi bi-dot"></i>'.$data->nivelorganizacional2.'</span>';
                                }
                            }
                        }

                        return $ret;
                    },
                ],
                [
                    'attribute' =>'nivel3',
                    'format'=>'raw',
                    'contentOptions' => ['class' => " color3 font600",'style'=>'width:12%'],
                    'filterInputOptions' => ['class' => 'icon-find form-control'],
                    'visible'=>$show_nivel3,
                    'label'=> $label_nivel3,
                    'value'=>function($model) use ($usuario){
                        $ret = '';
                        
                        if($usuario->nivel3_all == 1){
                            $total = count($model->niveles3);
                            $ret .= '<div class="color5 font10">Registros: <span class="color3">'.$total.'</span></div>';
                            if($model->niveles3){
                                foreach($model->niveles3 as $key=>$data){
                                    $ret .= '<span class="badge rounded-pill font10 bgcolor5" data-bs-toggle="tooltip" title="Actividad: '.$data->actividad.'"><i class="bi bi-dot"></i>'.$data->nivelorganizacional3.'</span>';
                                }
                            }
                        } else {
                            $array = explode(',', $usuario->nivel3_select);
                            if($array && count($array)>0){
                            } else {
                                $array = [];
                            }
                            
                            $nivel3 = NivelOrganizacional3::find()->where(['id_empresa'=>$model->id])->andWhere(['status'=>1])->andWhere(['in','id',$array])->orderBy('nivelorganizacional3')->all();
                            $total = count($nivel3);
                            $ret .= '<div class="color5 font10">Registros: <span class="color3">'.$total.'</span></div>';
                            if($nivel3){
                                foreach($nivel3 as $key=>$data){
                                    $ret .= '<span class="badge rounded-pill font10 bgcolor5" data-bs-toggle="tooltip" title="Actividad: '.$data->actividad.'"><i class="bi bi-dot"></i>'.$data->nivelorganizacional3.'</span>';
                                }
                            }
                        }
                        

                        return $ret;
                    },
                ],
                [
                    'attribute' =>'nivel4',
                    'format'=>'raw',
                    'contentOptions' => ['class' => " color3 font600",'style'=>'width:12%'],
                    'filterInputOptions' => ['class' => 'icon-find form-control'],
                    'visible'=>$show_nivel4,
                    'label'=> $label_nivel4,
                    'value'=>function($model) use ($usuario){
                        $ret = '';

                        if($usuario->nivel4_all == 1){
                            $total = count($model->niveles4);
                            $ret .= '<div class="color5 font10">Registros: <span class="color3">'.$total.'</span></div>';
                            if($model->niveles4){
                                foreach($model->niveles4 as $key=>$data){
                                    $ret .= '<span class="badge rounded-pill font10 bgcolor6 text-light" data-bs-toggle="tooltip" title="Actividad: '.$data->actividad.'"><i class="bi bi-dot"></i>'.$data->nivelorganizacional4.'</span>';
                                }
                            }
                        } else {
                            $array = explode(',', $usuario->nivel4_select);
                            if($array && count($array)>0){

                            } else {
                                $array = [];
                            }
                            
                            $nivel4 = NivelOrganizacional4::find()->where(['id_empresa'=>$model->id])->andWhere(['status'=>1])->andWhere(['in','id',$array])->orderBy('nivelorganizacional4')->all();
                            $total = count($nivel4);
                            $ret .= '<div class="color5 font10">Registros: <span class="color3">'.$total.'</span></div>';
                            if($nivel4){
                                foreach($nivel4 as $key=>$data){
                                    $ret .= '<span class="badge rounded-pill font10 bgcolor6 text-light" data-bs-toggle="tooltip" title="Actividad: '.$data->actividad.'"><i class="bi bi-dot"></i>'.$data->nivelorganizacional4.'</span>';
                                }
                            }
                        }
                        
                        
                        
                        return $ret;
                    },
                ],
            [
                'attribute' =>'paises',
                'visible'=>false,
                'contentOptions' => ['class' => " color3 font600",'style'=>'width:12%'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'filter'=>Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'paises',
                    'data' =>$paises,
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
                'format'=>'raw',
                'value'=>function($model){
                    $ret = '';
                    $iconpais = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-globe-americas" viewBox="0 0 16 16">
                    <path d="M8 0a8 8 0 1 0 0 16A8 8 0 0 0 8 0M2.04 4.326c.325 1.329 2.532 2.54 3.717 3.19.48.263.793.434.743.484q-.121.12-.242.234c-.416.396-.787.749-.758 1.266.035.634.618.824 1.214 1.017.577.188 1.168.38 1.286.983.082.417-.075.988-.22 1.52-.215.782-.406 1.48.22 1.48 1.5-.5 3.798-3.186 4-5 .138-1.243-2-2-3.5-2.5-.478-.16-.755.081-.99.284-.172.15-.322.279-.51.216-.445-.148-2.5-2-1.5-2.5.78-.39.952-.171 1.227.182.078.099.163.208.273.318.609.304.662-.132.723-.633.039-.322.081-.671.277-.867.434-.434 1.265-.791 2.028-1.12.712-.306 1.365-.587 1.579-.88A7 7 0 1 1 2.04 4.327Z"/>
                    </svg>';

                    $total = count($model->paises);
                    $ret .= '<div class="color5 font10">Registros: <span class="color3">'.$total.'</span></div>';
                    if($model->paises){
                        foreach($model->paises as $key=>$data){
                            if($data->pais){
                                $ret .= '<span class="badge rounded-pill font10 btnnew2" style="">'.'<i class="bi bi-dash"></i>'.$data->pais->pais.'</span>';
                            }
                        }
                    }
                    return $ret;
                  },
            ],
            [
                'attribute' =>'lineas',
                'visible'=>false,
                'contentOptions' => ['class' => " color3 font600",'style'=>'width:12%'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'headerOptions' => ['class' => "y_centercenter",'style'=>'color: #716F81;
                text-decoration:none;
                font-weight: 500;
                font-size: 11px;'],
                'format'=>'raw',
                'value'=>function($model){
                    $ret = ''; 

                    $total = count($model->lineas);
                    $ret .= '<div class="color5 font10">Registros: <span class="color3">'.$total.'</span></div>';
                    if($model->lineas){
                        foreach($model->lineas as $key=>$data){
                            $ret .= '<span class="badge rounded-pill font10 bgcolor9"><i class="bi bi-dot"></i>'.$data->linea.'</span>';
                        }
                    }
                    return $ret;
                  },
            ],
            [
                'attribute' =>'ubicaciones',
                'visible'=>false,
                'contentOptions' => ['class' => " color3 font600",'style'=>'width:12%'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'headerOptions' => ['class' => "y_centercenter",'style'=>'color: #716F81;
                text-decoration:none;
                font-weight: 500;
                font-size: 11px;'],
                'format'=>'raw',
                'value'=>function($model){
                    $ret = '';

                    $total = count($model->ubicaciones);
                    $ret .= '<div class="color5 font10">Registros: <span class="color3">'.$total.'</span></div>';
                    if($model->ubicaciones){
                        foreach($model->ubicaciones as $key=>$ubicacion){
                            $ret .= '<span class="badge rounded-pill font10 bg-light text-dark"><i class="bi bi-geo-alt-fill"></i>'.$ubicacion->ubicacion.'</span>';
                        }
                    }
                    return $ret;
                  },
            ],
            [
                'attribute' =>'areas',
                'visible'=>true,
                'contentOptions' => ['class' => " color6",'style'=>'width:20%'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'headerOptions' => ['class' => "y_centercenter",'style'=>'color: #716F81;
                text-decoration:none;
                font-weight: 500;
                font-size: 11px;'],
                'format'=>'raw',
                'value'=>function($model) use ($usuario){
                    $ret = '';
                    $areas_array = [];

                    if($model->cantidad_niveles != null && $model->cantidad_niveles != '' && $model->cantidad_niveles != ' '){
                        if($usuario->areas_all == 1){
                            $areas_array = Areas::find()->where(['id_empresa'=>$model->id])->andWhere(['status'=>1])->andWhere(['nivel'=>$model->cantidad_niveles])->orderBy(['id_superior'=>SORT_ASC])->all();
                        } else{

                            $array = explode(',', $usuario->areas_select);
                            if($array && count($array)>0){
                            } else {
                                $array = [];
                            }
                            $areas_array = Areas::find()->where(['id_empresa'=>$model->id])->andWhere(['nivel'=>1])->andWhere(['in','id',$array])->all();
                        
                        }
                    }

                    if($areas_array){
                        $total = count($areas_array);
                        $ret .= '<div class="color5 font10">Registros: <span class="color3">'.$total.'</span></div>';
                        if($areas_array){
                            $anterior = null;
                            $titulo = null;

                            foreach($areas_array as $key=>$data){
                                if($data->id_superior != $anterior){
                                    $anterior = $data->id_superior;

                                    if($data->nivel == 1){
                                        $titulo = NivelOrganizacional1::findOne($data->id_superior);
                                        if($titulo && $titulo->pais){
                                            $ret .= '<div class="border-top text-dark font600 mt-2 font10">'.$titulo->pais->pais.'</div>';
                                        }
                                    } else if($data->nivel == 2){
                                        $titulo = NivelOrganizacional2::findOne($data->id_superior);
                                        if($titulo){
                                            $ret .= '<div class="border-top text-dark font600 mt-2 font10">'.$titulo->nivelorganizacional2.'</div>';
                                        }
                                    } else if($data->nivel == 3){
                                        $titulo = NivelOrganizacional3::findOne($data->id_superior);
                                        if($titulo){
                                            $ret .= '<div class="border-top text-dark font600 mt-2 font10">'.$titulo->nivelorganizacional3.'</div>';
                                        }
                                    } else if($data->nivel == 4){
                                        $titulo = NivelOrganizacional4::findOne($data->id_superior);
                                        if($titulo){
                                            $ret .= '<div class="border-top text-dark font600 mt-2 font10">'.$titulo->nivelorganizacional4.'</div>';
                                        }
                                    }
                                }
                                $ret .= '<span class="badge rounded-pill bg-light color6 font10">'.$data->area.'</span>';
                            }
                        }
                    }
                    
                    return $ret;
                  },
            ],
            [
                'attribute' =>'consultorios',
                'contentOptions' => ['class' => " color6",'style'=>'width:12%'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'headerOptions' => ['class' => "y_centercenter",'style'=>'color: #716F81;
                text-decoration:none;
                font-weight: 500;
                font-size: 11px;'],
                'format'=>'raw',
                'visible'=>false,
                'value'=>function($model){
                    $ret = '';

                    $total = count($model->consultorios);
                    $ret .= '<div class="color5 font10">Registros: <span class="color3">'.$total.'</span></div>';
                    if($model->consultorios){
                        foreach($model->consultorios as $key=>$consultorio){
                            $ret.= '<span class="badge rounded-pill bg-light text-dark font10">'.$consultorio->consultorio.'</span>';
                        }
                    }
                    return $ret;
                  },
            ],
            [
                'attribute' =>'programas',
                'contentOptions' => ['class' => " color6",'style'=>'width:12%'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'headerOptions' => ['class' => "y_centercenter",'style'=>'color: #716F81;
                text-decoration:none;
                font-weight: 500;
                font-size: 11px;'],
                'filter'=>Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'programas',
                    'data' =>ArrayHelper::map(ProgramaSalud::find()->orderBy('nombre')->all(), 'id', 'nombre'),
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

                    $total = count($model->programas);
                    $ret .= '<div class="color5 font10">Registros: <span class="color3">'.$total.'</span></div>';
                    if($model->programas){
                        foreach($model->programas as $key=>$programa){
                            $ret.= '<span class="badge rounded-pill bg-light text-dark font10">'.$programa->nombre.'</span>';
                        }
                    }
                    return $ret;
                  },
            ],
            [
                'attribute' =>'turnos',
                'contentOptions' => ['class' => " color6",'style'=>'width:12%'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'headerOptions' => ['class' => "y_centercenter",'style'=>'color: #716F81;
                text-decoration:none;
                font-weight: 500;
                font-size: 11px;'],
                'format'=>'raw',
                'visible'=>false,
                'value'=>function($model){
                    $ret = '';

                    $total = count($model->turnos);
                    $ret .= '<div class="color5 font10">Registros: <span class="color3">'.$total.'</span></div>';
                    if($model->turnos){
                        foreach($model->turnos as $key=>$turno){
                            $ret.= '<span class="badge rounded-pill bg-light color6 font10">'.$turno->turno.'</span>';
                        }
                    }
                    return $ret;
                  },
            ],
            [
                'attribute' =>'contacto',
                'contentOptions' => ['class' => "y_centercenter",'style'=>'width:10%'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'format'=>'raw',
                'value'=>function($model){
                    $ret = '<div class="p-2 bg-light rounded-3">';
                    $ret .= '<span class="mx-1 fontsmall2 color3"><i class="bi bi-person-fill"></i></span>'.$model->contacto;
                    $ret .= '<br><span class="mx-1 fontsmall2 color5"><i class="bi bi-telephone-fill"></i></span>'.$model->telefono;
                    $ret .= '<br><span class="mx-1 fontsmall2 color4"><i class="bi bi-envelope-fill"></i></span>'.$model->correo;
                    $ret .= '</div>';
                    return $ret;
                  },
            ],
           /*  [
                'attribute' =>'create_date',
                'contentOptions' => ['class' => "y_centercenter color6",'style'=>''],
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
            ], */
            [
                'attribute' =>'status',
                'contentOptions' => ['class' => "y_centercenter",'style'=>''],
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
                'headerOptions' => ['class' => "y_centercenter",'style'=>'color: #716F81;
                text-decoration:none;
                font-weight: 500;
                font-size: 11px;'],
                'buttons' => [
                    'view' =>  function($url,$model) {
                        return Html::a('<span class="color2"><i class="bi bi-eye-fill"></i><span>', Url::to(['empresas/view','id' => $model->id]), [
                            'title' => Yii::t('app', 'Ver'),
                            'data-toggle'=>"tooltip",
                            'data-placement'=>"top",
                            'class'=>'btn btn-sm text-center shadow-sm'
                        ]);
                    },
                    'update' =>  function($url,$model) {
                        return Html::a('<span class=""><i class="bi bi-pen-fill"></i><span>', Url::to(['empresas/update','id' => $model->id]), [
                            'title' => Yii::t('app', 'Actualizar'),
                            'data-toggle'=>"tooltip",
                            'data-placement'=>"top",
                            'class'=>'btn btn-sm text-center shadow-sm'
                        ]);
                    },
                    'delete' =>  function($url,$model) {
                        return Html::a('<span class="color10"><i class="bi bi-trash"></i><span>', Url::to(['solicitudesdelete/create','id' => $model->id,'modelo'=>'empresas']), [
                            'title' => Yii::t('app', 'Solicitar Borrado'),
                            'data-toggle'=>"tooltip",
                            'data-placement'=>"top",
                            'class'=>'btn btn-sm text-center shadow-sm'
                        ]);
                    },
                    'updateqrs' =>  function($url,$model) {
                        $iconqr = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-qr-code" viewBox="0 0 16 16">
                        <path d="M2 2h2v2H2z"/>
                        <path d="M6 0v6H0V0zM5 1H1v4h4zM4 12H2v2h2z"/>
                        <path d="M6 10v6H0v-6zm-5 1v4h4v-4zm11-9h2v2h-2z"/>
                        <path d="M10 0v6h6V0zm5 1v4h-4V1zM8 1V0h1v2H8v2H7V1zm0 5V4h1v2zM6 8V7h1V6h1v2h1V7h5v1h-4v1H7V8zm0 0v1H2V8H1v1H0V7h3v1zm10 1h-1V7h1zm-1 0h-1v2h2v-1h-1zm-4 0h2v1h-1v1h-1zm2 3v-1h-1v1h-1v1H9v1h3v-2zm0 0h3v1h-2v1h-1zm-4-1v1h1v-2H7v1z"/>
                        <path d="M7 12h1v3h4v1H7zm9 2v2h-3v-1h2v-1z"/>
                        </svg>';

                        if(Yii::$app->user->identity->id == 2){
                            return  Html::a('<span class="color9">'.$iconqr.'<span>', ['registrarqrs', 'id' => $model->id], [
                                'data' => [
                                    'confirm' => Yii::t('app', '¿Crear QR´s de trabajadores faltantes para la empresa '.$model->comercial.'?'),
                                    'method' => 'post',
                                ],
                                'title' => Yii::t('app', 'Crear QR´s'),
                                'data-bs-toggle'=>"tooltip",
                                'data-bs-placement'=>"top",
                                'class'=>'btn btn-sm text-center shadow-sm'
                            ]);
                           
                        } else {
                            return '';
                        }
                       
                    },
                ],
            ],
        ],
    ]); ?>


</div>