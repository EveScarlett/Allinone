<?php

use app\models\Configuracion;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

use kartik\export\ExportMenu;
use app\models\Empresas;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var app\models\ConfiguracionSearch $searchModel */
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

$this->title = Yii::t('app', 'Configuración'.$name_empresa);
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
<div class="configuracion-index">

    <h1 class="title1"><?= Html::encode($this->title) ?><span class="mx-2"><i class="bi bi-gear-fill"></i></span></h1>

    <?php 
      if(Yii::$app->user->can('configuracion_exportar')){
        $fullExportMenu = ExportMenu::widget([
            'dataProvider' => $dataProvider,
            'columns' =>[
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'attribute' =>'id_empresa',
                    'visible' => $showempresa,
                    'value'=>function($model){
                        $ret = '';
                        if($model->dempresa){
                            $ret = $model->dempresa->comercial;
                        }
                        return $ret;
                      },
                ],
                [
                    'attribute' =>'cantidad_usuarios',
                ],
                [
                    'attribute' =>'cantidad_administradores',
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
        </div>

        <div class="col-lg-9 text-end">
            <?php
            echo $fullExportMenu;
            ?>
        </div>
    </div>

    <?php
    $template = '';
    if(Yii::$app->user->can('configuracion_ver')){
        $template .='{view}';
    }
    if(Yii::$app->user->can('configuracion_actualizar')){
        $template .='{update}';
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
                'attribute' =>'id_empresa',
                'visible' => $showempresa,
                'contentOptions' => ['class' => "y_centercenter font600",'style'=>'width:25%'],
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
                'attribute' =>'cantidad_usuarios',
                'contentOptions' => ['class' => "y_centercenter color3",'style'=>'width:15%;'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'format'=>'raw',
                'value'=>function($model){
                    $ret = '';
                    $cantidad = '<i class="bi bi-exclamation-triangle"></i>';
                    if(isset($model->cantidad_usuarios)){
                        $cantidad = $model->cantidad_usuarios;
                    }
                    $ret = '<div class="card text-light bgcolor12 p-3">
                        <h6 class="title1 text-uppercase text-center">'.$cantidad.'</h6>
                        <label class="font10  text-center">Usuarios</label></div>';

                    return $ret;
                 },
            ],
            [
                'attribute' =>'cantidad_administradores',
                'contentOptions' => ['class' => "y_centercenter color3",'style'=>'width:15%;'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'format'=>'raw',
                'value'=>function($model){
                    $ret = '';
                    $cantidad = '<i class="bi bi-exclamation-triangle"></i>';
                    if(isset($model->cantidad_administradores)){
                        $cantidad = $model->cantidad_administradores;
                    }
                    $ret = '<div class="card text-light bgcolor8 p-3">
                        <h6 class="title1 text-uppercase text-center">'.$cantidad.'</h6>
                        <label class="font10  text-center">Administradores</label></div>';

                    return $ret;
                 },
            ],
            [
                'attribute' =>'cantidad_medicos',
                'contentOptions' => ['class' => "y_centercenter color3",'style'=>'width:15%;'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'format'=>'raw',
                'value'=>function($model){
                    $ret = '';
                    $cantidad = '<i class="bi bi-exclamation-triangle"></i>';
                    if(isset($model->cantidad_medicos)){
                        $cantidad = $model->cantidad_medicos;
                    }
                    $ret = '<div class="card text-light bgcolor9 p-3">
                        <h6 class="title1 text-uppercase text-center">'.$cantidad.'</h6>
                        <label class="font10  text-center">Médicos</label></div>';

                    return $ret;
                 },
            ],
            [
                'attribute' =>'cantidad_medicoslaborales',
                'contentOptions' => ['class' => "y_centercenter color3",'style'=>'width:15%;'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'format'=>'raw',
                'value'=>function($model){
                    $ret = '';
                    $cantidad = '<i class="bi bi-exclamation-triangle"></i>';
                    if(isset($model->cantidad_medicoslaborales)){
                        $cantidad = $model->cantidad_medicoslaborales;
                    }
                    $ret = '<div class="card text-light bgnocumple p-3">
                        <h6 class="title1 text-uppercase text-center">'.$cantidad.'</h6>
                        <label class="font10  text-center">Médicos Laborales</label></div>';

                    return $ret;
                 },
            ],
            [
                'attribute' =>'verseccion_maquina',
                'label'=>'Maquinaria',
                'contentOptions' => ['class' => "y_centercenter text-center"],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'format'=>'raw',
                'value'=>function($model){
                    $ret = '';
                    if($model->verseccion_maquina == 1){
                        $ret = '<span class="color7"><i class="bi bi-check-lg"></i></span>';
                    } else{
                        $ret = '<span class="color11"><i class="bi bi-x-lg"></i></span>';
                    }
                    return $ret;
                  },
            ],
            [
                'attribute' =>'verqr_trabajador',
                'label'=>'QR Trabajador',
                'contentOptions' => ['class' => "y_centercenter text-center"],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'format'=>'raw',
                'value'=>function($model){
                    $ret = '';
                    if($model->verqr_trabajador == 1){
                        $ret = '<span class="color7"><i class="bi bi-check-lg"></i></span>';
                    } else{
                        $ret = '<span class="color11"><i class="bi bi-x-lg"></i></span>';
                    }
                    return $ret;
                  },
            ],
            [
                'attribute' =>'verqr_maquina',
                'label'=>'QR Maquinaria',
                'contentOptions' => ['class' => "y_centercenter text-center"],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'format'=>'raw',
                'value'=>function($model){
                    $ret = '';
                    if($model->verqr_maquina == 1){
                        $ret = '<span class="color7"><i class="bi bi-check-lg"></i></span>';
                    } else{
                        $ret = '<span class="color11"><i class="bi bi-x-lg"></i></span>';
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
                        return Html::a('<span class="color2"><i class="bi bi-eye-fill"></i><span>', Url::to(['configuracion/view','id' => $model->id]), [
                            'title' => Yii::t('app', 'Ver'),
                            'data-toggle'=>"tooltip",
                            'data-placement'=>"top",
                            'class'=>'btn btn-sm text-center shadow-sm'
                        ]);
                    },
                    'update' =>  function($url,$model) {
                        return Html::a('<span class=""><i class="bi bi-pen-fill"></i><span>', Url::to(['configuracion/update','id' => $model->id]), [
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