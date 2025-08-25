<?php

use app\models\Empresas;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\bootstrap5\Modal;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;


use app\models\NivelOrganizacional1;
use app\models\NivelOrganizacional2;
use app\models\NivelOrganizacional3;
use app\models\NivelOrganizacional4;

use app\models\Lineas;
use app\models\Ubicaciones;


use app\models\Areas;
use app\models\Consultorios;
use app\models\Turnos;
use app\models\Programaempresa;

use app\models\ProgramaSalud;

use app\models\Kpis;


/** @var yii\web\View $this */
/** @var app\models\DiagramasSearch $searchModel */
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

$this->title = Yii::t('app', 'KPIs Empresa'.$name_empresa);
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
$showempresa = true;
$empresas = explode(',', Yii::$app->user->identity->empresas_select);

if(Yii::$app->user->identity->empresa_all != 1){
    if(count($empresas) == 1){
        $showempresa = false;
    }
}

$programas = ArrayHelper::map(ProgramaSalud::find()->orderBy('nombre')->all(), 'id', function($data){
        $ret = 'PS - '.$data['nombre'];
        
        return $ret;
});

$kpis = [
    'A'=>'ACCIDENTES',
    'B'=>'NUEVOS INGRESOS',
    'C'=>'INCAPACIDADES',
    'E'=>'POES'
];

$kpis_mixed = $kpis + $programas;
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
      Modal::begin([
        'id' =>'modal-kpiedit',
        'options' => [
            'id' => 'modal-kpiedit',
            'tabindex' => false
        ],
        'title' => '<h5 class="text-uppercase text-purple">
                        Editar KPI
                    </h5>',
        'size' => 'modal-xl',
        'headerOptions' =>[
            'class' => 'text-light bg-mymodal btnnew'
        ],
        ]);
        echo '<div id="body-kpiedit"></div>';
        Modal::end();
?>

<div class="empresas-index">

    <h1 class="title1"><?= Html::encode($this->title) ?></h1>

    <div class="table-responsive">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'headerRowOptions' =>['class' => 'text-uppercase font12 color9'],
        'tableOptions' => ['class' => 'table table-hover table-sm small','style'=>'width:2500px'],
        'rowOptions' => ['class' => 'font-12 text-600 bg-white shadow-sm small'],
        'pager' => [
            'class' => 'yii\bootstrap5\LinkPager'
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
           
            [
                'attribute' =>'id',
                'label'=>'Diagrama',
                'format'=>'raw',
                'contentOptions' => ['class' => "y_centercenter",'style'=>'width:100%'],
                'filter'=>Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'id',
                    'data' =>$empresas,
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'hideSearch' => false,
                    'options' => [
                        'placeholder' => 'Diagrama Empresa...',
                    ],
                    'pluginOptions' => [
                        'allowClear' => false,
                    ],
                ]),
                'value'=>function($model) use ($usuario,$kpis_mixed){
                    $nodo_0 = '';
                    $ret = '';
                    $retpais = '';
                    $retniveles3 = '';
                    $retniveles4 = '';
                    $total_paises = 1;
                    $width_lienzo = 2500;
                    $linea_vertical_0 = '';
                    $linea_horizontal_nivel1 = '';
                    $linea_horizontal_nivel2 = '';
                    $linea_horizontal_nivel3 = '';
                    $linea_horizontal_nivel4 = '';

                    $kpis = ['1'=>'Trabajadores','2'=>'CAL','3'=>'POE','4'=>'Programas de Salud','5'=>'Accidentes','6'=>'Incapacidades','7'=>'Consultas Clínicas','8'=>'Historias Clínicas','9'=>'Cuestionario Nórdico','10'=>'Evaluacion Antropométrica'];

                    $usuario_niveles1 = null;

                    $ancho_empresa = 250;

                    $y_inicial = 20;
                    $x_inicial = ($width_lienzo - $ancho_empresa)/2;

                    $ret = '<div class="contenedor">';

                    $porcentaje_cumplimineto = '<span class="rounded-3 p-2 border14 color16 mx-2 font14 font600">'.number_format($model->kpi_cumplimiento, 2, '.', ',').' %</span>';
                    $nodo_0 .= '<span class="nodo width'.$ancho_empresa.' bgcolor15 text-light text-center title1" style="top: '.$y_inicial.'px;left: '.$x_inicial.'px;"><div class="background_color2 rounded-3 p-1">'.$model->comercial.$porcentaje_cumplimineto.'</div></span>';
                    $ret .= $nodo_0;

        
                    $kpi = '<div class="bgcolor14 text-dark font9 shadow p-3 rounded-3" style="z-index: 1;position: absolute;top:0px;left:20px;"><h1 class="font600 text-center">KPIs</h1></div>';
                    $ret .= $kpi;


                    $altura_inicial = 75;
                    $linea_vertical_0 .= '<span style="border-left: 1px solid #483AA0; width:2px; height:'.$altura_inicial.'px; z-index: 1;position: absolute;top: '.$y_inicial.'px;left: '.($width_lienzo/2).'px;"></span>';
                    $ret .= $linea_vertical_0;

                    $label_nivel1 = '';
                    $label_nivel2 = '';
                    $label_nivel3 = '';
                    $label_nivel4 = '';


                    if($model->cantidad_niveles > 0){ //SE AGREGO 1 NIVEL ORGANIZACIONAL EN LA CONFIG DE LA EMPRESA
                        if($model->cantidad_niveles > 0){
                            $label_nivel1 = $model->label_nivel1;
                        }
                        if($model->cantidad_niveles > 1){
                            $label_nivel2 = $model->label_nivel2;
                        }
                        if($model->cantidad_niveles > 2){
                            $label_nivel3 = $model->label_nivel3;
                        }
                        if($model->cantidad_niveles > 3){
                            $label_nivel4 = $model->label_nivel4;
                        }

                        $botonplus = '';
                        
                        $etiqueta_nivel1 = '<span class="badge rounded-pill bg-light text-dark font9" style="z-index: 1;position: absolute;top: 65px;left: '.(($width_lienzo/2)+5).'px;">'.$label_nivel1.' '.$botonplus.'</span>';
                        $ret .= $etiqueta_nivel1;

                        if($usuario_niveles1){

                        } else {
                            $linea_horizontal_nivel1 = '<span style="z-index: 2;position: absolute;top: 100px;left: 525px;"></span>';
                            $ret .= $linea_horizontal_nivel1;
                        }
                    }
                    

                    
                    if($usuario->nivel1_all == 1){
                        $usuario_niveles1 = $model->niveles1;
                    } else {
                        $array_usuario_niveles1 = explode(',', $usuario->nivel1_select);
                        if($array_usuario_niveles1 && count($array_usuario_niveles1)>0){

                        } else {
                            $array_usuario_niveles1 = [];
                        }
                        
                        $usuario_niveles1 = NivelOrganizacional1::find()->where(['id_empresa'=>$model->id])->andWhere(['status'=>1])->andWhere(['in','id_pais',$array_usuario_niveles1])->orderBy('id_pais')->all();
                    }
                    

                    if($usuario_niveles1){
                       
                        $width_pais = $width_lienzo;
                        $mitad_pais = 0;
                        $total_paises = count($usuario_niveles1);
                        $tamanio_p = 200;
                        $width_pais = $width_lienzo/$total_paises;
                        $mitad_pais = $width_pais/2;
                        $x_pais = $mitad_pais - ($tamanio_p/2);
                        $posicion_x_pais = 0;

                        if(count($usuario_niveles1) == 1){
                            $ret .= '<span style="border-left: 1px solid #483AA0; width:2px; height:50px; z-index: 1;position: absolute;top: 75px;left: '.$mitad_pais.'px;"></span>';
                        } else {
                            $ancho = (count($usuario_niveles1)-1)*$width_pais;
                            $ret .= '<span style="border-bottom: 1px solid #483AA0; width:'.$ancho.'px; height:1px; z-index: 1;position: absolute;top: 95px;left: '.$mitad_pais.'px;"></span>';
                        }
                        

                        foreach($usuario_niveles1 as $keyp => $pais){
                            $retlineas = '';

                            $linealateral_contenido = '';
                            $lineavertical_contenidolinea = '';
                            $lineavertical_contenidoubicacion = '';
                            $lineavertical_contenidoarea = '';
                            $lineavertical_contenidoconsultorio = '';
                            $lineavertical_contenidoprograma = '';

                            $linea_vertical_nivel1 = '';

                            if($keyp > 0){
                                $posicion_x_pais = $posicion_x_pais + $width_pais;
                            }

                            
                            

                            if($pais->pais){
                                if($keyp > 0){
                                    $x_pais += $width_pais;
                                }

                                $porcentaje_cumplimineto = '<span class="rounded-1 p-1 bgcolor14 text-dark mx-2 font10 font600">'.number_format($pais->kpi_cumplimiento, 2, '.', ',').' %</span>';
                                $retpais .= '<span style="border-left: 1px solid #483AA0; width:2px; height:50px; z-index: 1;position: absolute;top: 95px;left: '.($x_pais+($tamanio_p/2)).'px;"></span>';
                                
                                if(Yii::$app->user->can('diagrama_actualizar')){
                                    $label_lvl1 = Html::submitButton($pais->pais->pais.$porcentaje_cumplimineto, ['class' => 'btn p-0 m-0 font11 text-light p-1', 'onclick'=>'editaKpinivel1('.$model->id.',"'.$label_nivel1.'","'.Url::to(['empresas/editkpinivel1','id_empresa'=>$model->id,'id_nivel1'=>$pais->id]).'")']);
                                } else {
                                    $label_lvl1 = '<div class="btn p-0 m-0 font11 text-light p-1">'.$pais->pais->pais.$porcentaje_cumplimineto.'</div>';
                                }
                                
                                $retpais .= '<span class="nodo width'.$tamanio_p.' btnnew2 text-light text-center p-0 m-0" style="top: 120px;left: '.$x_pais.'px;"><div class="background_color2 rounded-3 p-0 m-0">'.$label_lvl1.'</div></span>';


                                if($model->cantidad_niveles > 1){ //SE AGREGARON 2 NIVELES ORGANIZACIONALES EN LA CONFIG DE LA EMPRESA

                                    $lineas = null;
                                    if($usuario->nivel2_all == 1){
                                        $lineas = NivelOrganizacional2::find()->where(['id_empresa'=>$model->id])->andWhere(['id_nivelorganizacional1'=>$pais->id])->andWhere(['status'=>1])->all();
                                    } else {
                                        $array_usuario_niveles2 = explode(',', $usuario->nivel2_select);
                                        if($array_usuario_niveles2 && count($array_usuario_niveles2)>0){

                                        } else {
                                            $array_usuario_niveles2 = [];
                                        }
                                        $lineas = NivelOrganizacional2::find()->where(['id_empresa'=>$model->id])->andWhere(['id_nivelorganizacional1'=>$pais->id])->andWhere(['status'=>1])->andWhere(['in','id',$array_usuario_niveles2])->all();
                                    }
                                    
                                    $botonplusnivel2 = '';
                                    
                                    $etiqueta_nivel2 = '<span class="badge rounded-pill bg-light text-dark font9" style="z-index: 1;position: absolute;top: 165px;left: '.($x_pais+($tamanio_p/2)).'px;">'.$label_nivel2.' '.$botonplusnivel2.'</span>';
                                    $ret .= $etiqueta_nivel2;


                                    if(!$lineas){
                                        $linea_vertical_nivel1 = '<span style="border-left: 1px solid #483AA0; width:2px; height:120px; z-index: 1;position: absolute;top: 95px;left: '.($x_pais+($tamanio_p/2)).'px;"></span>';
                                        $ret .= $linea_vertical_nivel1;
                                    }
                                    if($lineas){
                                       
                                    
                                        $total_lineas = count($lineas);
                                        $tamanio_l = 100;
                                        if(count($lineas) == 1){
                                            $tamanio_l = 250;
                                        }
                                        $width_linea = $width_pais/$total_lineas;
                                        $mitad_linea = $width_linea/2;
                                        $x_linea = ($mitad_linea - ($tamanio_l/2)) + $posicion_x_pais;


                                        $retlineas .= '<span style="border-left: 1px solid #483AA0; width:2px; height:100px; z-index: 1;position: absolute;top: 95px;left: '.($x_pais+($tamanio_p/2)).'px;"></span>';
                                    
                                        $ancholinea = (count($lineas)-1)*$width_linea;
                                        $retlineas .= '<span style="border-bottom: 1px solid #483AA0; width:'.$ancholinea.'px; height:1px; z-index: 1;position: absolute;top: 195px;left: '.($x_linea+($tamanio_l/2)).'px;"></span>';

                                        if(count($lineas) == 1){
                                            $retlineas .= '<span style="border-left: 1px solid #483AA0; width:2px; height:50px; z-index: 1;position: absolute;top: 195px;left: '.($x_pais+($tamanio_p/2)).'px;"></span>';
                                        }

                                        //dd('$width_pais: '.$width_pais.' | $total_lineas: '.$total_lineas.' | $mitad_linea: '.$mitad_linea.' | $x_linea: '.$x_linea);
                        
                                        foreach($lineas as $keyl =>$linea){

                                            $retubicaciones = '';
                                            $retareas = '';
                                            $retconsultorios = '';
                                            $retkpis = '';
                                            $retturnos = '';
                                            $retprogramasalud = '';

                                            $tamanio_contenido = $width_linea - 10;

                                            if($keyl > 0){
                                                $x_linea += $width_linea;
                                            }


                                            $posicionx_linea = ($x_pais+($tamanio_p/2));
                                            $posicionx_linea = $x_linea + ($tamanio_l/2);
                                            if(count($lineas) > 1) {
                                                $retlineas .= '<span style="border-left: 1px solid #483AA0; width:2px; height:50px; z-index: 1;position: absolute;top: 195px;left: '.$posicionx_linea.'px;" id="nivel2_'.$linea->id.'"></span>';
                                            }

                                            $porcentaje_cumplimineto = '<br><span class="rounded-1 p-1 bgcolor14 text-dark mx-2 font10 font600">'.number_format($linea->kpi_cumplimiento, 2, '.', ',').' %</span>';
                                            if(Yii::$app->user->can('diagrama_actualizar')){
                                                $label_lvl2 = Html::submitButton($linea->nivelorganizacional2.$porcentaje_cumplimineto, ['class' => 'btn p-0 m-0 font11 text-light p-1', 'onclick'=>'editaKpinivel2('.$model->id.','.$linea->id.',"'.$label_nivel2.'","'.Url::to(['empresas/editkpinivel2','id_empresa'=>$model->id,'id_nivel2'=>$linea->id]).'")']);
                                            } else {
                                                $label_lvl2 = '<div class="btn p-0 m-0 font11 text-light p-1">'.$linea->nivelorganizacional2.$porcentaje_cumplimineto.'</div>';
                                            }
                                            
                                            $retlineas .= '<span class="nodo width'.$tamanio_l.' bgcolor9 text-light text-center p-0 m-0" style="top: 230px;left: '.$x_linea.'px;"><div class="background_color2 rounded-3  p-0 m-0">'.$label_lvl2.'</div></span>';


                                            if($model->cantidad_niveles > 2){//SE AGREGARON 3 NIVELES ORGANIZACIONALES EN LA CONFIG DE LA EMPRESA
                                                
                                                $niveles3 = null;
                                                if($usuario->nivel3_all == 1){
                                                    $niveles3 = NivelOrganizacional3::find()->where(['id_empresa'=>$model->id])->andWhere(['id_nivelorganizacional1'=>$pais->id_pais])->andWhere(['id_nivelorganizacional2'=>$linea->id])->andWhere(['status'=>1])->all();
                                                } else {
                                                    $array_usuario_niveles3 = explode(',', $usuario->nivel3_select);
                                                    if($array_usuario_niveles3 && count($array_usuario_niveles3)>0){

                                                    } else {
                                                        $array_usuario_niveles3 = [];
                                                    }
                                                    $niveles3 = NivelOrganizacional3::find()->where(['id_empresa'=>$model->id])->andWhere(['id_nivelorganizacional1'=>$pais->id_pais])->andWhere(['id_nivelorganizacional2'=>$linea->id])->andWhere(['status'=>1])->andWhere(['in','id',$array_usuario_niveles3])->all();
                                                }

                                
                                                $botonplusnivel3 = '';
                                                
                                                $etiqueta_nivel3 = '<span class="badge rounded-pill bg-light text-dark font9" style="z-index: 1;position: absolute;top: 270px;left:  '.($posicionx_linea+5).'px;">'.$label_nivel3.' '.$botonplusnivel3.'</span>';
                                                $ret .= $etiqueta_nivel3;


                                                if(!$niveles3){
                                                    $linea_vertical_nivel3 = '<span style="border-left: 1px solid #483AA0; width:2px; height:50px; z-index: 1;position: absolute;top: 250px;left: '.($x_linea+($tamanio_l/2)).'px;"></span>';
                                                    $ret .= $linea_vertical_nivel3;
                                                }

                                                if($niveles3){
                                                    $total_nivel3 = count($niveles3);
                                                    $tamanio_n3 = 100;
                                                    if(count($niveles3) == 1){
                                                        $tamanio_n3 = 100;
                                                    }
                                                    $width_n3 = $width_linea/$total_nivel3;
                                                    $mitad_n3 = $width_n3/2;

                                                    //$x_linea = ($mitad_linea - ($tamanio_l/2)) + $posicion_x_pais;
                                                    //$x_n3 = ($mitad_n3 - ($tamanio_n3/2)) + (($posicionx_linea-$mitad_n3));
                                                    //$x_n3 = ($mitad_n3 - ($tamanio_n3/2)) + (($posicionx_linea-($width_linea)+$width_n3));

                                                    if(count($niveles3) == 1){
                                                        $x_n3 = ($mitad_n3 - ($tamanio_n3/2)) + (($posicionx_linea-($width_linea-$mitad_n3)));
                                                    } else {
                                                        $x_n3 = ($mitad_n3 - ($tamanio_n3/2)) + (($posicionx_linea-($width_linea)+$width_n3));
                                                    }
                                                    

                                                    
                                                    $anchon3 = (count($niveles3)-1)*$width_n3;
                                                    $retniveles3 .= '<span style="border-bottom: 1px solid #483AA0; width:'.$anchon3.'px; height:1px; z-index: 1;position: absolute;top: 300px;left: '.($x_n3+($tamanio_n3/2)).'px;"></span>';
                                                    $retniveles3 .= '<span style="border-left: 1px solid #483AA0; width:2px; height:50px; z-index: 1;position: absolute;top: 250px;left: '.($posicionx_linea).'px;"></span>';

                                                    if(count($niveles3) == 1){
                                                        $retniveles3 .= '<span style="border-left: 1px solid #483AA0; width:2px; height:50px; z-index: 1;position: absolute;top: 300px;left: '.($posicionx_linea).'px;"></span>';
                                                    }

                                                    $ret .= $retniveles3;

                                                   
                                                    foreach($niveles3 as $keyn3 =>$nivel3){

                                                        if($keyn3 > 0){
                                                            $x_n3 += $width_n3 ;
                                                        }

                                                        $posicionx_n3 = $x_n3 + ($tamanio_n3/2);
                                                        if(count($niveles3) > 1) {
                                                            $ret .= '<span style="border-left: 1px solid #483AA0; width:2px; height:50px; z-index: 1;position: absolute;top: 300px;left: '.$posicionx_n3.'px;" id="nivel3_linea'.$nivel3->id.'"></span>';
                                                        }

                                                        $porcentaje_cumplimineto = '<br><span class="rounded-1 p-1 bgcolor14 text-dark mx-2 font10 font600">'.number_format($nivel3->kpi_cumplimiento, 2, '.', ',').' %</span>';
                                                        if(Yii::$app->user->can('diagrama_actualizar')){
                                                            $label_lvl3 = Html::submitButton($nivel3->nivelorganizacional3.$porcentaje_cumplimineto, ['class' => 'btn p-0 m-0 font11 text-dark p-1', 'onclick'=>'editaKpinivel3('.$model->id.','.$nivel3->id.',"'.$label_nivel3.'","'.Url::to(['empresas/editkpinivel3','id_empresa'=>$model->id,'id_nivel3'=>$nivel3->id]).'")']);
                                                        } else {
                                                            $label_lvl3 = '<div class="btn p-0 m-0 font11 text-dark p-1">'.$nivel3->nivelorganizacional3.$porcentaje_cumplimineto.'</div>';
                                                        }
                                                        
                                                        $ret .= '<span class="nodo nodo3 width'.$tamanio_n3.' bgcolor5 text-dark text-center font10 p-0 m-0" style="top: 350px;left: '.($x_n3).'px;"><div class="background_color2 rounded-3  p-0 m-0">'.$label_lvl3.'</div></span>';
                                                        
                                                        
                                                        if($model->cantidad_niveles > 3){

                                                            $niveles4 = null;
                                                            if($usuario->nivel4_all == 1){
                                                                $niveles4 = NivelOrganizacional4::find()->where(['id_empresa'=>$model->id])->andWhere(['id_nivelorganizacional1'=>$pais->id_pais])->andWhere(['id_nivelorganizacional2'=>$linea->id])->andWhere(['id_nivelorganizacional3'=>$nivel3->id])->andWhere(['status'=>1])->all();
                                                            } else {
                                                                $array_usuario_niveles4 = explode(',', $usuario->nivel4_select);
                                                                if($array_usuario_niveles4 && count($array_usuario_niveles4)>0){

                                                                } else {
                                                                    $array_usuario_niveles4 = [];
                                                                }

                                                                $niveles4 = NivelOrganizacional4::find()->where(['id_empresa'=>$model->id])->andWhere(['id_nivelorganizacional1'=>$pais->id_pais])->andWhere(['id_nivelorganizacional2'=>$linea->id])->andWhere(['id_nivelorganizacional3'=>$nivel3->id])->andWhere(['status'=>1])->andWhere(['in','id',$array_usuario_niveles4])->all();
                                                            }
                                                            

                                                            $botonplusnivel4 = '';
                                                           
                                                            $etiqueta_nivel4 = '<span class="badge rounded-pill bg-light text-dark font9" style="z-index: 1;position: absolute;top: 390px;left:  '.($posicionx_n3+5).'px;">'.$label_nivel4.' '.$botonplusnivel4.'</span>';
                                                            $ret .= $etiqueta_nivel4;


                                                            
                                                            if(!$niveles4){
                                                                $linea_vertical_nivel4 = '<span style="border-left: 1px solid #483AA0; width:2px; height:50px; z-index: 1;position: absolute;top: 370px;left: '.($posicionx_n3).'px;"></span>';
                                                                $ret .= $linea_vertical_nivel4;
                                                            }


                                                            if($niveles4){
                                                                $total_nivel4 = count($niveles4);
                                                                $tamanio_n4 = 100;
                                                                if(count($niveles4) == 1){
                                                                    $tamanio_n4 = 100;
                                                                }
                                                                $width_n4 = $width_n3/$total_nivel4;
                                                                $mitad_n4 = $width_n4/2;

                                                                //$x_n3 = ($mitad_n3 - ($tamanio_n3/2)) + $posicion_x_pais;
                                                                $x_n4 =  ($x_n3);

                                                    
                                                                $anchon4 = (count($niveles4)-1)*$width_n4;
                                                                $retniveles4 .= '<span style="border-bottom: 1px solid #483AA0; width:'.$anchon4.'px; height:1px; z-index: 1;position: absolute;top: 420px;left: '.($x_n4+($tamanio_n4/2)).'px;"></span>';
                                                                $retniveles4 .= '<span style="border-left: 1px solid #483AA0; width:2px; height:50px; z-index: 1;position: absolute;top: 370px;left: '.($posicionx_n3).'px;"></span>';

                                                                if(count($niveles4) == 1){
                                                                    $retniveles4 .= '<span style="border-left: 1px solid #483AA0; width:2px; height:50px; z-index: 1;position: absolute;top: 420px;left: '.($posicionx_n3).'px;"></span>';
                                                                }

                                                                $ret .= $retniveles4;

                                                                
                                                                foreach($niveles4 as $keyn4 =>$nivel4){

                                                                    if($keyn4 > 0){
                                                                        $x_n4 += $width_n4;
                                                                    }

                                                                    $posicionx_n4 = $x_n4 + ($tamanio_n4/2);
                                                                    if(count($niveles4) > 1) {
                                                                        $ret .= '<span style="border-left: 1px solid #483AA0; width:2px; height:50px; z-index: 1;position: absolute;top: 420px;left: '.$posicionx_n4.'px;" id="nivel3_'.$nivel4->id.'"></span>';
                                                                    }


                                                                    $porcentaje_cumplimineto = '<br><span class="rounded-1 p-1 bgcolor14 text-dark mx-2 font10 font600">'.number_format($nivel4->kpi_cumplimiento, 2, '.', ',').' %</span>';
                                                                    if(Yii::$app->user->can('diagrama_actualizar')){
                                                                        $label_lvl4 = Html::submitButton($nivel4->nivelorganizacional4.$porcentaje_cumplimineto, ['class' => 'btn p-0 m-0 font11 text-light p-1', 'onclick'=>'editaKpinivel4('.$model->id.','.$nivel4->id.',"'.$label_nivel4.'","'.Url::to(['empresas/editkpinivel4','id_empresa'=>$model->id,'id_nivel4'=>$nivel4->id]).'")']);
                                                                    } else {
                                                                        $label_lvl4 = '<div class="btn p-0 m-0 font11 text-light p-1">'.$nivel4->nivelorganizacional4.$porcentaje_cumplimineto.'</div>';
                                                                    }
                                                                    
                                                                    $ret .= '<span class="nodo nodo4_'.$posicion_x_pais.' width'.$tamanio_n4.' bgcolor6 text-light text-center font10 p-0 m-0" style="top: 470px;left: '.$x_n4.'px;"><div class="background_color2 rounded-3  p-0 m-0">'.$label_lvl4.'</div></span>';


                                                                }
                                                            }
                                                        }

                                                    }

                                                    $retpais .= $retlineas;
                                                }
                                            }

                                        }

                                        $retpais .= $retlineas;

                                    }
                                }
                            

                            }
                        }
                    }

                    $ret .= $retpais;
                    $ret .= '</div>';
                   
                    return $ret;
                },
            ],
        ],
    ]); ?>
</div>

</div>