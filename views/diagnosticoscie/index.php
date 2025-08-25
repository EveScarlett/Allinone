<?php

use app\models\Diagnosticoscie;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use kartik\export\ExportMenu;

/** @var yii\web\View $this */
/** @var app\models\DiagnosticoscieSearch $searchModel */
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

$this->title = 'Diagnósticos CIE'.$name_empresa;
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

<div class="diagnosticoscie-index">

    <h1 class="title1"><?= Html::encode($this->title) ?></h1>

    <?php 
    $fullExportMenu = '';
      if(Yii::$app->user->can('diagnosticoscie_exportar')){
        $fullExportMenu = ExportMenu::widget([
            'dataProvider' => $dataProvider,
            'columns' =>[
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'attribute' =>'clave',
                    'value'=>function($model){
                        $ret = '';
                        if($model->clave){
                            $ret = $model->clave;
                        }
                        return $ret;
                      },
                ],
                [
                    'attribute' =>'diagnostico',
                    'value'=>function($model){
                        $ret = '';
                        $ret .= $model->diagnostico;
                        return $ret;
                      },
                ],
                'cie_version',
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
            <?php if(Yii::$app->user->can('diagnosticoscie_crear')):?>
            <?= Html::a('<span class="mx-2 small"><i class="bi bi-plus-lg"></i></span>Nuevo diagnóstico CIE', ['create'], ['class' => 'btn btn-primary btnnew btn-block']) ?>
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
    if(Yii::$app->user->can('diagnosticoscie_ver')){
        $template .='{view}';
    }
    if(Yii::$app->user->can('diagnosticoscie_actualizar')){
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
                'attribute' =>'clave',
                'contentOptions' => ['class' => "y_centercenter color6 font600",'style'=>'width:15%'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'format'=>'raw',
                'value'=>function($model){
                    $ret = '';
                    if($model->clave){
                        $ret = $model->clave;
                    }
                    return $ret;
                  },
            ],
            [
                'attribute' =>'diagnostico',
                'contentOptions' => ['class' => "y_centercenter color3 font600",'style'=>'width:20%'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'format'=>'raw',
                'value'=>function($model){
                    $ret = '';
                    $ret .= $model->diagnostico;
                    return $ret;
                  },
            ],
            'cie_version',
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
                        return Html::a('<span class="color2"><i class="bi bi-eye-fill"></i><span>', Url::to(['diagnosticoscie/view','id' => $model->id]), [
                            'title' => Yii::t('app', 'Ver'),
                            'data-toggle'=>"tooltip",
                            'data-placement'=>"top",
                            'class'=>'btn btn-sm text-center shadow-sm'
                        ]);
                    },
                    'update' =>  function($url,$model) {
                        return Html::a('<span class=""><i class="bi bi-pen-fill"></i><span>', Url::to(['diagnosticoscie/update','id' => $model->id]), [
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