<?php

use app\models\Configconsentimientos;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use app\models\Empresas;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;


/** @var yii\web\View $this */
/** @var app\models\ConfigconsentimientosSearch $searchModel */
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

$this->title = Yii::t('app', 'Formato de Consentimientos'.$name_empresa);
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
$tipoconsentimiento = ['1'=>'HISTORIAS CLÍNICAS','2'=>'CONSULTAS CLÍNICAS','3'=>'POES'];
?>
<?php
    $template = '';
    if(Yii::$app->user->can('consentimientos_view')){
        $template .='{view}';
    }
    if(Yii::$app->user->can('consentimientos_actualizar')){
        $template .='{update}';
    }
    ?>
<div class="configconsentimientos-index">

    <h1 class="title1"><?= Html::encode($this->title) ?></h1>

    <div class="row mb-3">
        <div class="col-lg-3 d-grid">
            <?php if(Yii::$app->user->can('consentimientos_crear')):?>
            <?= Html::a('<span class="mx-2 small"><i class="bi bi-plus-lg"></i></span>Nuevo Formato', ['create'], ['class' => 'btn btn-primary btnnew btn-block']) ?>
            <?php endif;?>
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
                    if($model->dempresa){
                        $ret = $model->dempresa->comercial;
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
                'attribute' =>'id_tipoconsentimiento',
                'contentOptions' => ['class' => "y_centercenter color3",'style'=>'width:15%'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'filter'=>Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'id_tipoconsentimiento',
                    'data' =>$tipoconsentimiento,
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
                'value'=>function($model) use ($tipoconsentimiento){
                    $ret = '';
                    if($model->id_tipoconsentimiento){
                        $ret = $tipoconsentimiento[$model->id_tipoconsentimiento];
                    }
                    return $ret;
                  },
            ],
           
            [
                'attribute' =>'texto_consentimiento',
                'contentOptions' => ['class' => "y_centercenter text-justify small",'style'=>'width:60%'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'format'=>'raw',
                'value'=>function($model){
                    $ret = '';
                    if($model->texto_consentimiento){
                        $ret = $model->texto_consentimiento;
                    }
                    return $ret;
                  },
            ],
            
            [
                'attribute' =>'status',
                'label'=>'Condición',
                'format'=>'raw',
                'contentOptions' => ['class' => "y_centercenter",'style'=>''],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'filter'=>Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'status',
                    'data' =>['1'=>'Activo','0'=>'Baja'],
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
                        return Html::a('<span class="color2"><i class="bi bi-eye-fill"></i><span>', Url::to(['configconsentimientos/view','id' => $model->id]), [
                            'title' => Yii::t('app', 'Ver'),
                            'data-toggle'=>"tooltip",
                            'data-placement'=>"top",
                            'class'=>'btn btn-sm text-center shadow-sm'
                        ]);
                    },
                    'update' =>  function($url,$model) {
                        return Html::a('<span class=""><i class="bi bi-pen-fill"></i><span>', Url::to(['configconsentimientos/update','id' => $model->id]), [
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