<?php

use app\models\Empresas;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use kartik\export\ExportMenu;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

/** @var yii\web\View $this */
/** @var app\models\EmpresasSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Turnos');
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
$horarios = [
    '1'=> '12:00 am',
    '2'=> '1:00 am',
    '3'=> '2:00 am',
    '4'=> '3:00 am',
    '5'=> '4:00 am',
    '6'=> '5:00 am',
    '7'=> '6:00 am',
    '8'=> '7:00 am',
    '9'=> '8:00 am',
    '10'=> '9:00 am',
    '11'=> '10:00 am',
    '12'=> '11:00 am',
    '13'=> '12:00 pm',
    '14'=> '1:00 pm',
    '15'=> '2:00 pm',
    '16'=> '3:00 pm',
    '17'=> '4:00 pm',
    '18'=> '5:00 pm',
    '19'=> '6:00 pm',
    '20'=> '7:00 pm',
    '21'=> '8:00 pm',
    '22'=> '9:00 pm',
    '23'=> '10:00 pm',
    '24'=> '11:00 pm',
];?>
<div class="empresas-index">

    <h1 class="title1"><?= Html::encode($this->title) ?></h1>

    <?php
    $template = '';
    if(Yii::$app->user->can('turnos_ver')){
        $template .='{view}';
    }
    if(Yii::$app->user->can('turnos_actualizar')){
        $template .='{update}';
    }
    ?>

    <?php 
     $fullExportMenu = '';
      if(Yii::$app->user->can('turnos_exportar')){
        $fullExportMenu = ExportMenu::widget([
            'dataProvider' => $dataProvider,
            'columns' =>[
                ['class' => 'yii\grid\SerialColumn'],
               
            ],
            'hiddenColumns'=>[0],
            'clearBuffers' => true,
            'showConfirmAlert' =>false,
            'columnBatchToggleSettings'=>[
                'label'=>'Seleccionar Todo',
                'class' =>'px-2'
            ], 
            'filename'=> $this->title,
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
                'label'=>'Empresa',
                'contentOptions' => ['class' => "y_centercenter color3 font600",'style'=>'width:20%'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'format'=>'raw',
                'visible' => $showempresa,
                'value'=>function($model){
                    $ret = '';
                    $ret .= $model->razon.'<br><span class="small color6">'.$model->comercial.'</span>';
                    return $ret;
                  },
            ],
            [
                'attribute' =>'turnos',
                'contentOptions' => ['class' => "y_centercenter color3 font600",'style'=>'width:75%'],
                'filterInputOptions' => ['class' => 'icon-find form-control'],
                'headerOptions' => ['class' => "y_centercenter",'style'=>'color: #716F81;
                text-decoration:none;
                font-weight: 500;
                font-size: 11px;'],
                'format'=>'raw',
                'value'=>function($model) use ($horarios){
                    $ret = '';

                    $ret = '<table class="table table-hover table-striped table-sm table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center color1" style="font-weight:600; width:15%;">
                                
                            </th>
                            <th class="text-center" style="font-weight:600;">
                                <span class="badge rounded-pill btnnew">Lunes</span>
                            </th>
                            <th class="text-center color1" style="font-weight:600;">
                                <span class="badge rounded-pill btnnew">Martes</span>
                            </th>
                            <th class="text-center color1" style="font-weight:600;">
                                <span class="badge rounded-pill btnnew">Miercoles</span>
                            </th>
                            <th class="text-center color1" style="font-weight:600;">
                                <span class="badge rounded-pill btnnew">Jueves</span>
                            </th>
                            <th class="text-center color1" style="font-weight:600;">
                                <span class="badge rounded-pill btnnew">Viernes</span>
                            </th>
                            <th class="text-center color1" style="font-weight:600;">
                                <span class="badge rounded-pill btnnew">Sabado</span>
                            </th>
                            <th class="text-center color1" style="font-weight:600;">
                                <span class="badge rounded-pill btnnew">Domingo</span>
                            </th>
                            <th class="text-center bg-dark color2" style="font-weight:600;">
                               Enfermeros
                            </th>
                            <th class="text-center bg-dark color2" style="font-weight:600;">
                               Médicos
                            </th>
                            <th class="text-center bg-dark color2" style="font-weight:600;">
                                Personal Extra
                            </th>
                        </tr>
                        </thead>
                        <tbody>';
                   

                    if($model->turnos){
                        foreach($model->turnos as $key=>$turno){
                            $lunes_inicio = '<span class="color11">--</span>';
                            $martes_inicio = '<span class="color11">--</span>';
                            $miercoles_inicio = '<span class="color11">--</span>';
                            $jueves_inicio = '<span class="color11">--</span>';
                            $viernes_inicio = '<span class="color11">--</span>';
                            $sabado_inicio = '<span class="color11">--</span>';
                            $domingo_inicio = '<span class="color11">--</span>';

                            $lunes_fin = '<span class="color11">--</span>';
                            $martes_fin = '<span class="color11">--</span>';
                            $miercoles_fin = '<span class="color11">--</span>';
                            $jueves_fin = '<span class="color11">--</span>';
                            $viernes_fin = '<span class="color11">--</span>';
                            $sabado_fin = '<span class="color11">--</span>';
                            $domingo_fin = '<span class="color11">--</span>';

                            if(isset($turno->lunes_inicio) && $turno->lunes_inicio != '' && $turno->lunes_inicio != null){
                                $lunes_inicio = $horarios[$turno->lunes_inicio]; 
                            }
                            if(isset($turno->martes_inicio) && $turno->martes_inicio != '' && $turno->martes_inicio != null){
                                $martes_inicio = $horarios[$turno->martes_inicio]; 
                            }
                            if(isset($turno->miercoles_inicio) && $turno->miercoles_inicio != '' && $turno->miercoles_inicio != null){
                                $miercoles_inicio = $horarios[$turno->miercoles_inicio]; 
                            }
                            if(isset($turno->jueves_inicio) && $turno->jueves_inicio != '' && $turno->jueves_inicio != null){
                                $jueves_inicio = $horarios[$turno->jueves_inicio]; 
                            }
                            if(isset($turno->viernes_inicio) && $turno->viernes_inicio != '' && $turno->viernes_inicio != null){
                                $viernes_inicio = $horarios[$turno->viernes_inicio]; 
                            }
                            if(isset($turno->sabado_inicio) && $turno->sabado_inicio != '' && $turno->sabado_inicio != null){
                                $sabado_inicio = $horarios[$turno->sabado_inicio]; 
                            }
                            if(isset($turno->domingo_inicio) && $turno->domingo_inicio != '' && $turno->domingo_inicio != null){
                                $domingo_inicio = $horarios[$turno->domingo_inicio]; 
                            }



                            if(isset($turno->lunes_fin) && $turno->lunes_fin != '' && $turno->lunes_fin != null){
                                $lunes_fin = $horarios[$turno->lunes_fin]; 
                            }
                            if(isset($turno->martes_fin) && $turno->martes_fin != '' && $turno->martes_fin != null){
                                $martes_fin = $horarios[$turno->martes_fin]; 
                            }
                            if(isset($turno->miercoles_fin) && $turno->miercoles_fin != '' && $turno->miercoles_fin != null){
                                $miercoles_fin = $horarios[$turno->miercoles_fin]; 
                            }
                            if(isset($turno->jueves_fin) && $turno->jueves_fin != '' && $turno->jueves_fin != null){
                                $jueves_fin = $horarios[$turno->jueves_fin]; 
                            }
                            if(isset($turno->viernes_fin) && $turno->viernes_fin != '' && $turno->viernes_fin != null){
                                $viernes_fin = $horarios[$turno->viernes_fin]; 
                            }
                            if(isset($turno->sabado_fin) && $turno->sabado_fin != '' && $turno->sabado_fin != null){
                                $sabado_fin = $horarios[$turno->sabado_fin]; 
                            }
                            if(isset($turno->domingo_fin) && $turno->domingo_fin != '' && $turno->domingo_fin != null){
                                $domingo_fin = $horarios[$turno->domingo_fin]; 
                            }

                            $ret .= ' <tr>
                            <td colspan="11" class="text-center color3 font500">
                            <div class="row m-0 p-0"><div class="col-lg-4 offset-lg-4 title2 boxtitle p-0 small rounded-3 color3"><label class=""><span class="mx-2"><i class="bi bi-calendar3-week"></i></span>'.$turno->turno.'</label>
                            </td>
                            </tr>';

                            $ret .= ' <tr>
                            <td class="text-center color3 font500">
                                <label class="">H. Inicio <i class="bi bi-alarm-fill"></i></label>
                            </td>
                            <td class="text-center color6 font500">'.$lunes_inicio.'</td>
                            <td class="text-center color6 font500">'.$martes_inicio.'</td>
                            <td class="text-center color6 font500">'.$miercoles_inicio.'</td>
                            <td class="text-center color6 font500">'.$jueves_inicio.'</td>
                            <td class="text-center color6 font500">'.$viernes_inicio.'</td>
                            <td class="text-center color6 font500">'.$sabado_inicio.'</td>
                            <td class="text-center color6 font500">'.$domingo_inicio.'</td>
                            <td rowspan = "2" class="text-center font500 y_centercenter bg-dark text-light">'.$turno->cantidad_enfermeros.'</td>
                            <td rowspan = "2" class="text-center font500 y_centercenter bg-dark text-light">'.$turno->cantidad_medicos.'</td>
                            <td rowspan = "2" class="text-center font500 y_centercenter bg-dark text-light">'.$turno->cantidad_extras.'</td></tr>';

                            $ret .= ' <tr>
                            <td class="text-center color3 font500">
                                <label class="">H. Fin <i class="bi bi-alarm-fill"></i></label>
                            </td>
                            <td class="text-center color6 font500">'.$lunes_fin.'</td>
                            <td class="text-center color6 font500">'.$martes_fin.'</td>
                            <td class="text-center color6 font500">'.$miercoles_fin.'</td>
                            <td class="text-center color6 font500">'.$jueves_fin.'</td>
                            <td class="text-center color6 font500">'.$viernes_fin.'</td>
                            <td class="text-center color6 font500">'.$sabado_fin.'</td>
                            <td class="text-center color6 font500">'.$domingo_fin.'</td></tr>';
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
            //'lunes_inicio',
            //'lunes_fin',
            //'martes_inicio',
            //'martes_fin',
            //'miercoles_inicio',
            //'miercoles_fin',
            //'jueves_inicio',
            //'jueves_fin',
            //'viernes_inicio',
            //'viernes_fin',
            //'sabado_inicio',
            //'sabado_fin',
            //'domingo_inicio',
            //'domingo_fin',
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
                        return Html::a('<span class="color2"><i class="bi bi-eye-fill"></i><span>', Url::to(['turnos/view','id' => $model->id]), [
                            'title' => Yii::t('app', 'Ver'),
                            'data-toggle'=>"tooltip",
                            'data-placement'=>"top",
                            'class'=>'btn btn-sm text-center shadow-sm'
                        ]);
                    },
                    'update' =>  function($url,$model) {
                        return Html::a('<span class=""><i class="bi bi-pen-fill"></i><span>', Url::to(['turnos/update','id' => $model->id]), [
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