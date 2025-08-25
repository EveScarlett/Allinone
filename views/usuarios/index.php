<?php

use app\models\Usuarios;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use kartik\export\ExportMenu;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;


use app\models\Empresas;
use app\models\Roles;
use app\models\Ubicaciones;
use app\models\Paises;
use app\models\Paisempresa;
use app\models\Lineas;


use app\models\NivelOrganizacional1;
use app\models\NivelOrganizacional2;
use app\models\NivelOrganizacional3;
use app\models\NivelOrganizacional4;

use app\models\Areas;
use app\models\Consultorios;
use app\models\ProgramaSalud;
use app\models\Programaempresa;

/** @var yii\web\View $this */
/** @var app\models\UsuariosSearch $searchModel */
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

$this->title = Yii::t('app', 'Usuarios'.$name_empresa);
$this->params['breadcrumbs'][] = $this->title;

$this->registerJs("jQuery('.b-psw').on('mousedown', function() {
    $(this).parent().parent().find('input').attr('type','text');
}).on('mouseup', function() {
    $(this).parent().parent().find('input').attr('type','password');
});");
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

if(Yii::$app->user->identity->role->hidden == 1){
    $roles = ArrayHelper::map(Roles::find()->orderBy('nombre')->all(), 'id', 'nombre');
} else {
    $roles = ArrayHelper::map(Roles::find()->where(['IS', 'hidden', new \yii\db\Expression('NULL')])->orderBy('nombre')->all(), 'id', 'nombre');
}



$limite_usuarios = 5;
$utilizados_usuarios = 0;
if(Yii::$app->user->identity->empresa && Yii::$app->user->identity->empresa->configuracion){
    $limite_usuarios = Yii::$app->user->identity->empresa->configuracion->cantidad_usuarios;
}

if(Yii::$app->user->identity->empresa){
    $empresa = Yii::$app->user->identity->empresa;
    if($empresa->usuariosactivos){
        $utilizados_usuarios = count($empresa->usuariosactivos); 
    }
}

$disponible = false;
if($utilizados_usuarios < $limite_usuarios){
    $disponible = true; 
}

?>



<div class="usuarios-index">

    <h1 class="title1"><?= Html::encode($this->title) ?></h1>

    <?php 
  if(Yii::$app->user->can('usuarios_exportar')){
    $fullExportMenu = ExportMenu::widget([
        'dataProvider' => $dataProvider,
        'columns' =>[
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' =>'name',
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
                'attribute' => 'username',
                'visible'=>false,
            ],
            [
                'attribute' =>'rol',
                'value'=>function($model){
                    $ret = '';
                    if($model->role){
                        $ret = $model->role->nombre;
                    }
                    return $ret;
                  },
            ],
            [
                'attribute' =>'empresa_all',
                'label'=>'Empresas que Administra',
                'value'=>function($model){
                    $ret = '';
                    if($model->empresa_all == 1){
                        $ret = 'TODAS';
                    } else{
                        $array = explode(',', $model->empresas_select);
                        if($array && count($array)>0){
                            foreach($array as $key=>$id_empresa){
                                $empresa = Empresas::find()->where(['id'=>$id_empresa])->one();
                                if($empresa){
                                    $ret .=$empresa->comercial;
                                    if($key < (count($array)-1)){
                                        $ret .= ',';
                                    }
                                }
                            }
                        } else{
                            $ret = 'NINGUNA';
                        }
        
                    }
                    return $ret;
                  },
            ],
            [
                'attribute' =>'permisos_all',
                'label'=>'Todos los permisos',
                'value'=>function($model){
                    $ret = '';
                    if($model->permisos_all == 1){
                        $ret = 'SI';
                    } else{
                        $ret = 'NO';
                    }
                    return $ret;
                  },
            ],
            [
                'attribute' =>'firma',
                'label'=>'Firma',
                'value'=>function($model){
                    $ret = '';
                    if($model->firmaa){
                        $ret = 'SI';
                    } else{
                        $ret = 'NO';
                    }
                    return $ret;
                  },
            ],
            [
                'attribute' => 'last_login', 
                'label' => 'Último Acceso',
                "value"=>function($model){
                    $ret = '';

                    if(isset($model->last_login)){
                        date_default_timezone_set('America/Mazatlan');
                        $time = new \DateTime('now');
                        $today = $time->format('Y-m-d H:i');
                        $fecha = Yii::$app->formatter->format($model->last_login, ['relativeTime', $today ]);
                        $ret = $fecha.'-'.$model->last_login;
                    }
                    return $ret;
                },
            ],
            [
                'attribute' =>'status',
                'value'=>function($model){
                    $ret = '';

                    if($model->status == 2){
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
    <?php
$icon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-buildings-fill" viewBox="0 0 16 16">
<path d="M15 .5a.5.5 0 0 0-.724-.447l-8 4A.5.5 0 0 0 6 4.5v3.14L.342 9.526A.5.5 0 0 0 0 10v5.5a.5.5 0 0 0 .5.5h9a.5.5 0 0 0 .5-.5V14h1v1.5a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5V.5ZM2 11h1v1H2v-1Zm2 0h1v1H4v-1Zm-1 2v1H2v-1h1Zm1 0h1v1H4v-1Zm9-10v1h-1V3h1ZM8 5h1v1H8V5Zm1 2v1H8V7h1ZM8 9h1v1H8V9Zm2 0h1v1h-1V9Zm-1 2v1H8v-1h1Zm1 0h1v1h-1v-1Zm3-2v1h-1V9h1Zm-1 2h1v1h-1v-1Zm-2-4h1v1h-1V7Zm3 0v1h-1V7h1Zm-2-2v1h-1V5h1Zm1 0h1v1h-1V5Z"/>
</svg>';
?>
    <div class="row mb-3">
        <div class="col-lg-3 d-grid">
            <?php if(Yii::$app->user->can('usuarios_crear') && $disponible):?>
            <?= Html::a('<span class="mx-2 small"><i class="bi bi-plus-lg"></i></span>Nuevo Usuario', ['create'], ['class' => 'btn btn-primary btnnew btn-block']) ?>
            <?php endif;?>
        </div>
        <div class="col-lg-6 text-center">
            <label class="shadow p-2 rounded-pill">Usuarios Disponibles <?php echo ($limite_usuarios-$utilizados_usuarios).'/'.$limite_usuarios?></label>
        </div>

        <div class="col-lg-3 text-end">
            <?php
        echo $fullExportMenu;
        ?>
        </div>
    </div>

    <?php
    $template = '';
    if(Yii::$app->user->can('usuarios_ver')){
        $template .='{view}';
    }
    if(Yii::$app->user->can('usuarios_actualizar')){
        $template .='{update}'; 
    }
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
                        $ret = '';
                        $filePath =  '/resources/images/perfil/'.$model->foto;
                        $foto = Html::img('@web'. $filePath, ['alt'=>' ', 'class' => "iconphoto rounded-circle img-responsive", 'width' => '35px', 'height' =>'auto','style'=>'box-shadow: rgba(50, 50, 93, 0.25) 0px 13px 27px -5px, rgba(0, 0, 0, 0.3) 0px 8px 16px -8px; height:auto; width:35px;']);
                        $ret = $foto;
                    }

                    return $ret;
                }
            ],
            [
                'attribute' =>'name',
                'contentOptions' => ['class' => "y_centercenter color3 font600",'style'=>'width:15%'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'value'=>function($model){
                   return $model->name;
                },
            ],
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
                'attribute' => 'username',
                'contentOptions' => ['class' => "y_centercenter color4 font500",'style'=>'width:10%'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'format'=>'raw',
                'value'=>function($model){
                    $ret = '<div class="p-2 bg-light rounded-3">'.$model->username.'</div>';
                    
                    return $ret;
                  },
            ],
            [  
                'attribute' => 'password',
                'contentOptions' => ['class' => "y_centercenter",'style'=>'width:10%'],
                'format' =>'raw',
                "value"=>function($model){
                    //$hash = Yii::$app->getSecurity()->generatePasswordHash($model->password);
                    return '<div class="input-group">
                    <input type="password" disabled class="form-control form-control-sm rounded-sm psw" value="'.$model->password.'">
                    <div class="input-group-append">
                      <button class="btn btn-sm rounded-sm b-psw text-warning" type="button"><i class="fas fa-lock"></i></button>
                    </div>
                  </div>';
                    //return '<input type="password" class="form-control psw" value="'.$model->password.'"><span><i class="fas fa-eye"></i></span>';
                }

            ],
            [
                'attribute' =>'rol',
                'contentOptions' => ['class' => "y_centercenter color6 font600",'style'=>'width:10%'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'filter'=>Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'rol',
                    'data' =>$roles,
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
                    if($model->role){
                        $ret = '<span class="badge bgtransparent1 text-dark font12 m-1">'.$model->role->nombre.'</span>';
                    }
                    return $ret;
                  },
            ],
            [
                'attribute' =>'empresa_all',
                'label'=>'Empresas que Administra',
                'contentOptions' => ['class' => "y_centercenter",'style'=>'width:10%'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'format'=>'raw',
                'value'=>function($model){
                    $ret = '';
                    if($model->empresa_all == 1){
                        $ret = 'TODAS';
                    } else{
                        $array = explode(',', $model->empresas_select);
                        if($array && count($array)>0){
                            foreach($array as $key=>$id_empresa){
                                $empresa = Empresas::find()->where(['id'=>$id_empresa])->one();
                                if($empresa){
                                    $ret .='<span class="badge rounded-pill font10 bgcolor15 text-light">'.$empresa->comercial.'</span>';
                                }
                            }
                        } else{
                            $ret = 'NINGUNA';
                        }
        
                    }
                    return $ret;
                  },
            ],
             [
                    'attribute' =>'nivel1',
                    'format'=>'raw',
                    'contentOptions' => ['class' => " color3 font600",'style'=>'width:12%'],
                    'filterInputOptions' => ['class' => 'icon-find form-control'],
                    'headerOptions' => ['class' => "y_centercenter",'style'=>'color: #716F81;
                    text-decoration:none;
                    font-weight: 500;
                    font-size: 11px;'],
                    'visible'=>$show_nivel1,
                    'label'=> $label_nivel1,
                    'value'=>function($model){
                        $ret = '';

                        if($model->nivel1_all == 1){
                            $ret = 'TODO';
                        } else{
                            $datos = null;
                            $array = explode(',', $model->nivel1_select);
                            if($array && count($array)>0){
                                $datos = Paises::find()->where(['in','id',$array])->all();
                            }

                            if($datos){
                                foreach($datos as $key=>$data){
                                    $ret .= '<span class="badge rounded-pill font10 btnnew2" style="">'.'<i class="bi bi-dash"></i>'.$data->pais.'</span>';
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
                    'headerOptions' => ['class' => "y_centercenter",'style'=>'color: #716F81;
                    text-decoration:none;
                    font-weight: 500;
                    font-size: 11px;'],
                    'visible'=>$show_nivel2,
                    'label'=> $label_nivel2,
                    'value'=>function($model){
                        $ret = ''; 

                        if($model->nivel2_all == 1){
                            $ret = 'TODO';
                        } else{
                            $datos = null;
                            $array = explode(',', $model->nivel2_select);
                            if($array && count($array)>0){
                                $datos = NivelOrganizacional2::find()->where(['in','id',$array])->all();
                            }

                            if($datos){
                                foreach($datos as $key=>$data){
                                    $ret .= '<span class="badge rounded-pill font10 btnnew2" style="">'.'<i class="bi bi-dash"></i>'.$data->nivelorganizacional2.'</span>';
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
                    'headerOptions' => ['class' => "y_centercenter",'style'=>'color: #716F81;
                    text-decoration:none;
                    font-weight: 500;
                    font-size: 11px;'],
                    'visible'=>$show_nivel3,
                    'label'=> $label_nivel3,
                    'value'=>function($model){
                        $ret = '';
                        
                        if($model->nivel3_all == 1){
                            $ret = 'TODO';
                        } else{
                            $datos = null;
                            $array = explode(',', $model->nivel3_select);
                            if($array && count($array)>0){
                                $datos = NivelOrganizacional3::find()->where(['in','id',$array])->all();
                            }

                            if($datos){
                                foreach($datos as $key=>$data){
                                    $ret .= '<span class="badge rounded-pill font10 btnnew2" style="">'.'<i class="bi bi-dash"></i>'.$data->nivelorganizacional3.'</span>';
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
                    'headerOptions' => ['class' => "y_centercenter",'style'=>'color: #716F81;
                    text-decoration:none;
                    font-weight: 500;
                    font-size: 11px;'],
                    'visible'=>$show_nivel4,
                    'label'=> $label_nivel4,
                    'value'=>function($model){
                        $ret = '';
                        
                        if($model->nivel4_all == 1){
                            $ret = 'TODO';
                        } else{
                            $datos = null;
                            $array = explode(',', $model->nivel4_select);
                            if($array && count($array)>0){
                                $datos = NivelOrganizacional4::find()->where(['in','id',$array])->all();
                            }

                            if($datos){
                                foreach($datos as $key=>$data){
                                    $ret .= '<span class="badge rounded-pill font10 btnnew2" style="">'.'<i class="bi bi-dash"></i>'.$data->nivelorganizacional4.'</span>';
                                }
                            }
                        }
                        
                        return $ret;
                    },
            ],
            [
                'attribute' =>'areas',
                'contentOptions' => ['class' => " color6",'style'=>'width:12%'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'headerOptions' => ['class' => "y_centercenter",'style'=>'color: #716F81;
                text-decoration:none;
                font-weight: 500;
                font-size: 11px;'],
                'format'=>'raw',
                'value'=>function($model){
                        $ret = '';
                        
                        if($model->areas_all == 1){
                            $ret = 'TODO';
                        } else{
                            $datos = null;
                            $array = explode(',', $model->areas_select);
                            if($array && count($array)>0){
                                $datos = Areas::find()->where(['in','id',$array])->all();
                            }

                            if($datos){
                                foreach($datos as $key=>$data){
                                    $ret .= '<span class="badge rounded-pill bg-light text-dark font10" style="">'.'<i class="bi bi-dash"></i>'.$data->area.'</span>';
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
                'value'=>function($model){
                        $ret = '';
                        
                        if($model->consultorios_all == 1){
                            $ret = 'TODO';
                        } else{
                            $datos = null;
                            $array = explode(',', $model->consultorios_select);
                            if($array && count($array)>0){
                                $datos = Consultorios::find()->where(['in','id',$array])->all();
                            }

                            if($datos){
                                foreach($datos as $key=>$data){
                                    $ret .= '<span class="badge rounded-pill bg-light text-dark font10" style="">'.'<i class="bi bi-dash"></i>'.$data->consultorio.'</span>';
                                }
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
                'format'=>'raw',
                'value'=>function($model){
                        $ret = '';
                        
                        if($model->programas_all == 1){
                            $ret = 'TODO';
                        } else{
                            $datos = null;
                            $array = explode(',', $model->programas_select);
                            if($array && count($array)>0){
                                $datos = ProgramaSalud::find()->where(['in','id',$array])->all();
                            }

                            if($datos){
                                foreach($datos as $key=>$data){
                                    $ret .= '<span class="badge rounded-pill bg-light text-dark font10" style="">'.'<i class="bi bi-dash"></i>'.$data->nombre.'</span>';
                                }
                            }
                        }
                        
                        return $ret;
                },
            ],
            [
                'attribute' =>'permisos_all',
                'label'=>'Todos los permisos',
                'visible'=>false,
                'contentOptions' => ['class' => "y_centercenter",'style'=>'width:10%'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'format'=>'raw',
                'value'=>function($model){
                    $ret = '';
                    if($model->permisos_all == 1){
                        $ret = 'SI';
                    } else{
                        $ret = 'NO';
                    }
                    return $ret;
                  },
            ],
            [
                'attribute' =>'firma',
                'label'=>'Firma',
                'contentOptions' => ['class' => "y_centercenter"],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'format'=>'raw',
                'value'=>function($model){
                    $ret = '';
                    if($model->firmaa){
                        $ret = '<span class="color7"><i class="bi bi-check-lg"></i></span>';
                    } else{
                        $ret = '<span class="color11"><i class="bi bi-x-lg"></i></span>';
                    }
                    return $ret;
                  },
            ],
            [
                'attribute' => 'last_login', 
                'contentOptions' => [ 'class' => "y_centercenter color6", 'style' => 'width: 10%;' ],
                'headerOptions' => ['style' =>' font-weight: normal;'],
                'label' => 'Último Acceso',
                "value"=>function($model){
                    $ret = '';

                    if(isset($model->last_login)){
                        date_default_timezone_set('America/Monterrey');
                        $time = new \DateTime('now');
                        $today = $time->format('Y-m-d H:i');
                        $fecha = Yii::$app->formatter->format($model->last_login, ['relativeTime', $today ]);
                        $ret = '<label>'.$fecha.'</label><br><span class="small text-compras">'.$model->last_login.'</span>';
                    }
                    return $ret;
                },
                'format' => [
                    'raw'
                ],
                'filter' => \kartik\daterange\DateRangePicker::widget(
                    [
                        'model' => $searchModel, 
                        'attribute' => 'last_login', 
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
                'format'=>'raw',
                'contentOptions' => ['class' => "y_centercenter",'style'=>''],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'filter'=>Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'status',
                    'data' =>['1'=>'Activo','2'=>'Baja'],
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

                    if($model->status == 2){
                        $ret =  '<span class="badge bgcolor6 text-light font11"><span class="color11 mx-2" style=""><i class="bi bi-x"></i></span>Baja</span>';
                    }else if( $model->status == 1){
                        $ret =  '<span class="badge bgcolor3 text-light font11"><span class="color7 mx-2" style=""><i class="bi bi-check"></i></span>Activo</span>';
                    }
                   
                    return $ret;
                },
            ],
            //'firma',
            //'authKey',
            //'accessToken',
            //'id_firma',
            //'id_empresa',
            //'foto',
            //'empresas_todos',
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
                        return Html::a('<span class="color2"><i class="bi bi-eye-fill"></i><span>', Url::to(['usuarios/view','id' => $model->id]), [
                            'title' => Yii::t('app', 'Ver'),
                            'data-toggle'=>"tooltip",
                            'data-placement'=>"top",
                            'class'=>'btn btn-sm text-center shadow-sm'
                        ]);
                    },
                    'update' =>  function($url,$model) {
                        return Html::a('<span class=""><i class="bi bi-pen-fill"></i><span>', Url::to(['usuarios/update','id' => $model->id]), [
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