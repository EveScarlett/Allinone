<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;
use app\models\Empresas;
use app\models\Trabajadores;
use app\models\Consultorios;
use app\models\Almacen;
use app\models\Programaempresa;
use kartik\file\FileInput;
use app\models\Diagnosticoscie;
use yii\web\JsExpression;
use kartik\time\TimePicker;
use unclead\multipleinput\MultipleInput;
use yii\helpers\Url;


use app\models\Riesgos;
use app\models\Areas;
use app\models\Puestostrabajo;
use app\models\ProgramaSalud;
use app\models\Movimientos;



/** @var yii\web\View $this */
/** @var app\models\Consultas $model */

$this->title = 'Consulta Médica '.$model->nombre.' '.$model->apellidos.' | '.$model->fecha;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Consultas'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
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
    font-size:17px;
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
    font-size:12px;
}
");
?>

<?php
$empresas = explode(',', Yii::$app->user->identity->empresas_select);
        
if(Yii::$app->user->identity->empresa_all != 1) {
    $empresas = ArrayHelper::map(Empresas::find()->where(['in','id',$empresas])->orderBy('comercial')->all(), 'id', 'comercial');
} else{
    $empresas = ArrayHelper::map(Empresas::find()->orderBy('comercial')->all(), 'id', 'comercial');
}

$riesgos = ArrayHelper::map(Riesgos::find()->orderBy('riesgo')->all(), 'id', 'riesgo');
$empresas[0] = 'OTRA';
$areas = ArrayHelper::map(Areas::find()->where(['id_empresa'=>$model->id_empresa])->orderBy('area')->all(), 'id', 'area');
$puestos = ArrayHelper::map(Puestostrabajo::find()->where(['id_empresa'=>$model->id_empresa])->orderBy('nombre')->all(), 'id', 'nombre');
$programas = ArrayHelper::map(Programaempresa::find()->orderBy('id_programa')->where(['id_empresa'=>$model->id_empresa])->all(), 'id_programa', function($model){
    return $model->programasalud['nombre'];
});
if($model->id_empresa != 0){
    $consultorios = ArrayHelper::map(Consultorios::find()->where(['id_empresa'=>$model->id_empresa])->orderBy('consultorio')->all(), 'id', 'consultorio');
} else{
    $id_empresas = [];
    foreach($empresas as $k =>$empresa){
        if (!in_array($k, $id_empresas)) {
            array_push($id_empresas, $k);
        }
    }
    $consultorios = ArrayHelper::map(Consultorios::find()->where(['in','id_empresa',$id_empresas])->orderBy('consultorio')->all(), 'id', 'consultorio');
}
$consultorios = ArrayHelper::map(Consultorios::find()->orderBy('consultorio')->all(), 'id', 'consultorio');
$trabajadores = ArrayHelper::map(Trabajadores::find()->where(['id_empresa'=>$model->id_empresa])->orderBy('nombre')->all(), 'id', function($data){
    return $data['nombre'].' '.$data['apellidos'];
});
$tipoexamen = ['3'=>'CLÍNICA','1'=>'ACCIDENTE','7'=>'PROGRAMAS DE SALUD','4'=>'INCAPACIDAD','6'=>'TRABAJOS DE RIESGO','2'=>'ANTIDOPING','5'=>'PREOCUPANTE','8'=>'COVID-19','9'=>'NUTRICIÓN','10'=>'PSICOLÓGICA','11'=>'ALCOHOLEMIA']; 
$riesgostipos = [
    '1'=>'Trabajos en caliente',
    '2'=>'Espacios confinados',
    '3'=>'Trabajo en componentes energizados',
    '4'=>'Trabajos en alturas',
    '5'=>'Trabajos en solitario y/o áreas remotas',
    '6'=>'Trabajo en sistemas presurizados',
    '7'=>'Trabajos químicos altamente peligrosos',
    '8'=>'Construcción/excavación/demolición/izaje'
];
$hoy = date('Y-m-d');
$medicamentos = ArrayHelper::map(Almacen::find()->where(['id_empresa'=>$model->id_empresa])->andWhere(['id_consultorio'=>$model->id_consultorio])->andWhere(['>','stock',0])->andWhere(['>=','fecha_caducidad',$hoy])->orderBy(['id_insumo'=>SORT_DESC,'fecha_caducidad'=>SORT_ASC])->all(), 'id', function($model){
    $ret = '';
    if($model->insumo){
        $ret =  $model->insumo['nombre_comercial'].' | '.$model->insumo['nombre_generico'];
    }
    $ret .= ' - [Exp. '.$model->fecha_caducidad.']';
    return $ret;
});

$tadiagnosticos = ['1'=>'Normal','2'=>'Hipotensión','3'=>'Hipertensión'];
$oxidiagnosticos = ['1'=>'Normal','2'=>'Hipoxia'];
$frdiagnosticos = ['1'=>'Normal','2'=>'Bradipnea','3'=>'Taquipnea'];

$solicitantes = ['1'=>'EMPLEADO','2'=>'CONTRATISTA','3'=>'VISITANTE'];
$sexos = ['1'=>'Masculino','2'=>'Femenino','3'=>'Otro'];
$visitas = ['1'=>'1A VEZ','2'=>'SUBSECUENTE'];
$incapacidades = ['1'=>'IMSS','2'=>'INTERNA','3'=>'PARTICULAR'];
$ramo_incapacidad = ['1'=>'Maternidad','2'=>'Enfermedad General','3'=>'Riesgo del Trabajo'];
$yes_no = ['1'=>'SI','2'=>'NO'];

$diabetes3 = ['1'=>'Ardor/Disuria','2'=>'Sensación de vaciamiento incompleto/Tenesmo','3'=>'No presenta molestias'];
$hipertension6 = ['1'=>'Frecuencia (incremento)','2'=>'Frecuencia (descenso)', '3'=>'Volumen (disminución del chorro)','4'=>'Orina con aspecto fuera de la normalidad','5'=>'No presenta cambios'];
$lactancia2 = ['1'=>'Parto','2'=>'Cesárea'];
$lactancia6 = ['1'=>'Lactancia materna exclusiva','2'=>'Lactancia materna complementaria','3'=>'Lactancia materna predominante'];
$lactancia7 = ['1'=>'Salida constante de leche','2'=>'Salida de material purulento','3'=>'Fiebre','4'=>'Abultamientos o endurecimientos de mama','5'=>'Fisuras o grietas mamarias','6'=>'Dolor al tacto'];
$hiperdiabe1 = ['1'=>'Si, ambas','2'=>'No, ninguno','3'=>'Solo glucosa en control','4'=>'Solo presión arterial en control'];
$hiperdiabe2 = ['1'=>'Si, ambos','2'=>'No, ninguno','3'=>'Solo buena adherencia al tratamiendo para diabetes mellitus','4'=>'Solo buena adherencia al tratamiento para hipertensión'];
$hiperdiabe3 = ['1'=>'Alteraciones en el aspecto de la orina','2'=>'Alteraciones en el olor de la orina','3'=>'Alteraciones en el color de la orina','4'=>'Alteraciones asociadas como disuria (ardor), tenesmo (sensación de vaciamiento incompleto)','5'=>'Alteraciones en la frecuencia','6'=>'Alteraciones en el horario (nicturia)'];

$status = ['1'=>'Abierta','2'=>'Cerrada'];
$resultados = ['1'=>'REGRESA A LABORAR (MISMA ACTIVIDAD)','2'=>'REGRESA A LABORAR (CAMBIO ACTIVIDAD)',
'3'=>'ENVIO IMSS','4'=>'ENVIO A DOMICILIO','5'=>'INCAPACIDAD IMSS','6'=>'EN OBSERVACIÓN'];
?>

<?php
    $iconclip = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clipboard2-fill" viewBox="0 0 16 16">
    <path d="M9.5 0a.5.5 0 0 1 .5.5.5.5 0 0 0 .5.5.5.5 0 0 1 .5.5V2a.5.5 0 0 1-.5.5h-5A.5.5 0 0 1 5 2v-.5a.5.5 0 0 1 .5-.5.5.5 0 0 0 .5-.5.5.5 0 0 1 .5-.5h3Z"/>
    <path d="M3.5 1h.585A1.498 1.498 0 0 0 4 1.5V2a1.5 1.5 0 0 0 1.5 1.5h5A1.5 1.5 0 0 0 12 2v-.5c0-.175-.03-.344-.085-.5h.585A1.5 1.5 0 0 1 14 2.5v12a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 14.5v-12A1.5 1.5 0 0 1 3.5 1Z"/>
    </svg>';

    $iconcura = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bandaid" viewBox="0 0 16 16">
    <path d="M14.121 1.879a3 3 0 0 0-4.242 0L8.733 3.026l4.261 4.26 1.127-1.165a3 3 0 0 0 0-4.242ZM12.293 8 8.027 3.734 3.738 8.031 8 12.293 12.293 8Zm-5.006 4.994L3.03 8.737 1.879 9.88a3 3 0 0 0 4.241 4.24l.006-.006 1.16-1.121ZM2.679 7.676l6.492-6.504a4 4 0 0 1 5.66 5.653l-1.477 1.529-5.006 5.006-1.523 1.472a4 4 0 0 1-5.653-5.66l.001-.002 1.505-1.492.001-.002Z"/>
    <path d="M5.56 7.646a.5.5 0 1 1-.706.708.5.5 0 0 1 .707-.708Zm1.415-1.414a.5.5 0 1 1-.707.707.5.5 0 0 1 .707-.707ZM8.39 4.818a.5.5 0 1 1-.708.707.5.5 0 0 1 .707-.707Zm0 5.657a.5.5 0 1 1-.708.707.5.5 0 0 1 .707-.707ZM9.803 9.06a.5.5 0 1 1-.707.708.5.5 0 0 1 .707-.707Zm1.414-1.414a.5.5 0 1 1-.706.708.5.5 0 0 1 .707-.708ZM6.975 9.06a.5.5 0 1 1-.707.708.5.5 0 0 1 .707-.707ZM8.39 7.646a.5.5 0 1 1-.708.708.5.5 0 0 1 .707-.708Zm1.413-1.414a.5.5 0 1 1-.707.707.5.5 0 0 1 .707-.707Z"/>
  </svg>';

  $iconhospital = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-hospital" viewBox="0 0 16 16">
  <path d="M8.5 5.034v1.1l.953-.55.5.867L9 7l.953.55-.5.866-.953-.55v1.1h-1v-1.1l-.953.55-.5-.866L7 7l-.953-.55.5-.866.953.55v-1.1h1ZM13.25 9a.25.25 0 0 0-.25.25v.5c0 .138.112.25.25.25h.5a.25.25 0 0 0 .25-.25v-.5a.25.25 0 0 0-.25-.25h-.5ZM13 11.25a.25.25 0 0 1 .25-.25h.5a.25.25 0 0 1 .25.25v.5a.25.25 0 0 1-.25.25h-.5a.25.25 0 0 1-.25-.25v-.5Zm.25 1.75a.25.25 0 0 0-.25.25v.5c0 .138.112.25.25.25h.5a.25.25 0 0 0 .25-.25v-.5a.25.25 0 0 0-.25-.25h-.5Zm-11-4a.25.25 0 0 0-.25.25v.5c0 .138.112.25.25.25h.5A.25.25 0 0 0 3 9.75v-.5A.25.25 0 0 0 2.75 9h-.5Zm0 2a.25.25 0 0 0-.25.25v.5c0 .138.112.25.25.25h.5a.25.25 0 0 0 .25-.25v-.5a.25.25 0 0 0-.25-.25h-.5ZM2 13.25a.25.25 0 0 1 .25-.25h.5a.25.25 0 0 1 .25.25v.5a.25.25 0 0 1-.25.25h-.5a.25.25 0 0 1-.25-.25v-.5Z"/>
  <path d="M5 1a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v1a1 1 0 0 1 1 1v4h3a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1V8a1 1 0 0 1 1-1h3V3a1 1 0 0 1 1-1V1Zm2 14h2v-3H7v3Zm3 0h1V3H5v12h1v-3a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3Zm0-14H6v1h4V1Zm2 7v7h3V8h-3Zm-8 7V8H1v7h3Z"/>
</svg>';
?>
<div class="consultas-view container">
    <h1 class="title1"><?= Html::encode($this->title) ?></h1>
    <div class="row my-3">
        <div class="col-lg-5">
            <label class="control-label"><?= $model->getAttributeLabel('solicitante');?></label>
            <div class="form-control bg-disabled"><?= $solicitantes[$model->solicitante].'&nbsp;';?></div>
        </div>
        <?php 
            $show = 'none';
            if($model->solicitante == '2' || $model->solicitante == '3'){
                $show = 'block';
            }
        ?>
        <div class="col-lg-3 apartado_nombre" style="display:<?php echo $show;?>;">
            <label class="control-label"><?= $model->getAttributeLabel('nombre');?></label>
            <div class="form-control bg-disabled "><?= $model->nombre.'&nbsp;';?></div>
        </div>
        <div class="col-lg-3 apartado_nombre" style="display:<?php echo $show;?>;">
            <label class="control-label"><?= $model->getAttributeLabel('apellidos');?></label>
            <div class="form-control bg-disabled "><?= $model->apellidos.'&nbsp;';?></div>
        </div>
    </div>

    <div class="row my-3 mt-5">
        <div class="col-lg-5">
            <label class="control-label"><?= $model->getAttributeLabel('id_empresa');?></label>
            <div class="form-control bg-disabled "><?= $empresas[$model->id_empresa].'&nbsp;';?></div>
        </div>
        <?php 
            $show = 'none';
            if($model->id_empresa == '0'){
                $show = 'block';
            }
        ?>
        <div class="col-lg-4" id="empresa" style="display:<?php echo $show;?>;">
            <label class="control-label"><?= $model->getAttributeLabel('empresa');?></label>
            <div class="form-control bg-disabled "><?= $model->empresa.'&nbsp;';?></div>
        </div>
        <div class="col-lg-3">
            <label class="control-label"><?= $model->getAttributeLabel('id_consultorio');?></label>
            <div class="form-control bg-disabled "><?= $consultorios[$model->id_consultorio].'&nbsp;';?></div>
        </div>
        <div class="col-lg-3">
            <label class="control-label"><?= $model->getAttributeLabel('fecha');?></label>
            <div class="form-control bg-disabled "><?= $model->fecha.'&nbsp;';?></div>
        </div>
        <div class="col-lg-1">
            <label class="control-label"><?= $model->getAttributeLabel('hora_inicio');?></label>
            <div class="form-control bg-disabled "><?= $model->hora_inicio.'&nbsp;';?></div>
        </div>
        <div class="col-lg-4" style="display:none;">
            <label class="control-label"><?= $model->getAttributeLabel('envia_empresa');?></label>
            <div class="form-control bg-disabled "><?= $model->envia_empresa.'&nbsp;';?></div>
        </div>
        <div class="col-lg-4" style="display:none;">
            <label class="control-label"><?= $model->getAttributeLabel('envia_consultorio');?></label>
            <div class="form-control bg-disabled "><?= $model->envia_consultorio.'&nbsp;';?></div>
        </div>
        <div class="col-lg-4" style="display:none;">
            <label class="control-label"><?= $model->getAttributeLabel('id_hccohc');?></label>
            <div class="form-control bg-disabled "><?= $model->id_hccohc.'&nbsp;';?></div>
        </div>
        <div class="col-lg-4" style="display:none;">
            <label class="control-label"><?= $model->getAttributeLabel('envia_form');?></label>
            <div class="form-control bg-disabled "><?= $model->envia_form.'&nbsp;';?></div>
        </div>
    </div>

    <?php 
        $show = 'none';
        if($model->solicitante == '1'){
            $show = 'block';
        }
    ?>
    <div class="row my-3">
        <div class="col-lg-5 datos_trabajador" style="display:<?php echo $show;?>;">
            <label class="control-label"><?= $model->getAttributeLabel('id_trabajador');?></label>
            <div class="form-control bg-disabled ">
                <?php
                $trab = '';
                if(isset($model->id_trabajador) && $model->id_trabajador != null && $model->id_trabajador != '' && $model->id_trabajador != ' '){
                    $trab =$trabajadores[$model->id_trabajador];
                }
                echo $trab.'&nbsp;';
                ?>
            </div>
        </div>
        <div class="col-lg-3" style="display:none;">
            <label class="control-label"><?= $model->getAttributeLabel('folio');?></label>
            <div class="form-control bg-disabled "><?= $model->folio.'&nbsp;';?></div>
        </div>
        <div class="col-lg-3 border30 boxtitle p-2 text-center datos_trabajador" style="display:<?php echo $show;?>;">
            <label class="title2 color3">
                Historia Clínica
            </label>
            <div class="text-center" id="historia_clinica"></div>
        </div>
        <div class="col-lg-3 offset-lg-1 border30 boxtitle p-2 text-center datos_trabajador"
            style="display:<?php echo $show;?>;">
            <label class="title2 color3">
                Programas de Salud
            </label>
            <div class="text-center" id="programas_salud"></div>
        </div>

    </div>

    <?php
    $deshabilitados = false;
    if($model->solicitante == 1){
        $deshabilitados = true;
    }
    ?>

    <div class="row mb-3">
        <div class="col-lg-2">
            <label class="control-label"><?= $model->getAttributeLabel('sexo');?></label>
            <div class="form-control bg-disabled ">
                <?php 
                if(isset($model->sexo) && $model->sexo != null && $model->sexo != ''){
                    echo $sexos[$model->sexo];
                }
                ?>
            </div>
        </div>
        <div class="col-lg-1">
            <label class="control-label"><?= $model->getAttributeLabel('edad');?></label>
            <div class="form-control bg-disabled "><?= $model->edad.'&nbsp;';?></div>
        </div>
        <div class="col-lg-2 datos_trabajador" style="display:<?php echo $show;?>;">
            <label class="control-label"><?= $model->getAttributeLabel('num_imss');?></label>
            <div class="form-control bg-disabled "><?= $model->num_imss.'&nbsp;';?></div>
        </div>
        <div class="col-lg-2 datos_trabajador" style="display:<?php echo $show;?>;">
            <label class="control-label"><?= $model->getAttributeLabel('num_trabajador');?></label>
            <div class="form-control bg-disabled "><?= $model->num_trabajador.'&nbsp;';?></div>
        </div>
        <div class="col-lg-3 datos_trabajador" style="display:<?php echo $show;?>;">
            <label class="control-label"><?= $model->getAttributeLabel('area');?></label>
            <div class="form-control bg-disabled ">
                <?php 
                if(isset($model->area) && $model->area != null && $model->area != ''){
                    echo $areas[$model->area].'&nbsp;';
                }
                ?>
            </div>
        </div>
        <div class="col-lg-4 datos_trabajador" style="display:<?php echo $show;?>;">
            <label class="control-label"><?= $model->getAttributeLabel('puesto');?></label>
            <div class="form-control bg-disabled ">
                <?php 
                if(isset($model->puesto) && $model->puesto != null && $model->puesto != ''){
                    echo $puestos[$model->puesto].'&nbsp;';
                }
                ?>
            </div>
        </div>
    </div>
    <?php 
    $show = 'none';
    if($model->tipo == '1'){
        $show = 'block';
    }
   ?>

    <div class="container-fluid my-3 border30 bg-light p-4 datos_trabajador" style="display:<?php echo $show;?>;">
        <div class="row m-0 p-0">
            <div class="col-lg-6">
                <div class="row p-0 m-0">
                    <div class="col-lg-6 title2 boxtitle2 p-1 rounded-3 color10 my-3">
                        <label class="">
                            <span class="mx-2"><i class="bi bi-exclamation-circle"></i></span>
                            Alergias
                        </label>
                    </div>
                    <div class="col-lg-12" id='alergias'>
                        <?php
                        if($model->trabajador && $model->trabajador->alergias){
                            foreach($model->trabajador->alergias as $key=>$alerg){
                                echo $alerg->alergia.', ';
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="row p-0 m-0">
                    <div class="col-lg-6 title2 boxtitle2 p-1 rounded-3 color10 my-3">
                        <label class="">
                            <span class="mx-2"><i class="bi bi-slash-circle"></i></span>
                            Riesgos de Trabajo
                        </label>
                    </div>
                    <div class="col-lg-12" id='riesgos'>
                        <?php
                        $riesgos = '';
                        if($model->trabajador && $model->trabajador->puestoactivo){
                            $puesto = $model->trabajador->puestoactivo;
                            $alergias = $model->trabajador->alergias;
                            if($puesto){
                                if($puesto->puesto){
                                    try {
                                        $riesgos .= $puesto->puesto->riesgos.', ';
                                    } catch (\Throwable $th) {
                                        //throw $th;
                                    }
                                    
                                }   
                            }
                        }
                        echo $riesgos.'&nbsp;';
                        ?>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="row my-3">
        <div class="col-lg-5">
            <?php
            $tipoexamen = ['3'=>'CLÍNICA','1'=>'ACCIDENTE','7'=>'PROGRAMAS DE SALUD','4'=>'INCAPACIDAD','6'=>'TRABAJOS DE RIESGO','2'=>'ANTIDOPING','5'=>'PREOCUPANTE','8'=>'COVID-19','9'=>'NUTRICIÓN','10'=>'PSICOLÓGICA','11'=>'ALCOHOLEMIA'];
            if($model->solicitante == 2 ||$model->solicitante == 3){
                $tipoexamen = ['3'=>'CLÍNICA','1'=>'ACCIDENTE','6'=>'TRABAJOS DE RIESGO','2'=>'ANTIDOPING','5'=>'PREOCUPANTE','8'=>'COVID-19'];
            }
            ?>
            <label class="control-label"><?= $model->getAttributeLabel('tipo');?></label>
            <div class="form-control bg-disabled "><?= $tipoexamen[$model->tipo].'&nbsp;';?></div>
        </div>
        <div class="col-lg-2">
            <label class="control-label"><?= $model->getAttributeLabel('visita');?></label>
            <div class="form-control bg-disabled "><?= $visitas[$model->visita].'&nbsp;';?></div>
        </div>
        <?php 
        $show = 'none';
        if($model->tipo == '7'){
            $show = 'block';
        }
        ?>
        <div class="col-lg-5 bloque_programas" style="display:<?php echo $show;?>;">
            <label class="control-label"><?= $model->getAttributeLabel('id_programa');?></label>
            <div class="form-control bg-disabled ">
                <?php 
                if(isset($model->id_programa) && $model->id_programa != null && $model->id_programa != ''){
                    echo $programas[$model->id_programa].'&nbsp;';
                }
                ?>
            </div>
        </div>
    </div>


    <div class="container-fluid my-3 border30 bg-light p-4" id="bloque_accidente" style="display:<?php echo $show;?>;">
        <div class="row m-0 p-0">
            <div class="col-lg-4 title2 boxtitle p-1 rounded-3 color3 my-3">
                <label class="">
                    <span class="mx-2"><?php echo  $iconcura;?></span>
                    Información del Accidente
                </label>
            </div>
        </div>
        <div class="row my-3">
            <div class="col-lg-2">
                <label class="control-label"><?= $model->getAttributeLabel('accidente_hora');?></label>
                <div class="form-control bg-disabled "><?= $model->accidente_hora.'&nbsp;';?></div>
            </div>
            <div class="col-lg-2">
                <label class="control-label"><?= $model->getAttributeLabel('accidente_horareporte');?></label>
                <div class="form-control bg-disabled "><?= $model->accidente_horareporte.'&nbsp;';?></div>
            </div>
            <div class="col-lg-8">
                <label class="control-label"><?= $model->getAttributeLabel('accidente_zona');?></label>
                <div class="form-control bg-disabled "><?= $model->accidente_zona.'&nbsp;';?></div>
            </div>
            <div class="col-lg-6 my-3">
                <label class="control-label"><?= $model->getAttributeLabel('accidente_causa');?></label>
                <div class="form-control bg-disabled "><?= $model->accidente_causa.'&nbsp;';?></div>
            </div>
            <div class="col-lg-6 my-3">
                <label class="control-label"><?= $model->getAttributeLabel('accidente_descripcion');?></label>
                <div class="form-control bg-disabled "><?= $model->accidente_descripcion;?></div>
            </div>
        </div>
    </div>

    <?php 
    $show = 'none';
    if($model->tipo == '4'){
        $show = 'block';
    }
   ?>
    <div class="container-fluid my-3 border30 bg-light p-4" id="bloque_incapacidad"
        style="display:<?php echo $show;?>;">
        <div class="row m-0 p-0">
            <div class="col-lg-4 title2 boxtitle p-1 rounded-3 color3 my-3">
                <label class="">
                    <span class="mx-2"><?php echo  $iconhospital;?></span>
                    Datos de la Incapacidad
                </label>
            </div>
        </div>
        <div class="row my-3">
            <div class="col-lg-3">
                <label class="control-label"><?= $model->getAttributeLabel('incapacidad_folio');?></label>
                <div class="form-control bg-disabled "><?= $model->incapacidad_folio.'&nbsp;';?></div>
            </div>
            <div class="col-lg-4">
                <label class="control-label"><?= $model->getAttributeLabel('incapacidad_tipo');?></label>
                <div class="form-control bg-disabled ">
                    <?php 
                    if(isset($model->incapacidad_tipo) && $model->incapacidad_tipo != null && $model->incapacidad_tipo != ''){
                        echo $incapacidades[$model->incapacidad_tipo].'&nbsp;';
                    }
                    ?>
                </div>
            </div>
            <div class="col-lg-4">
                <label class="control-label"><?= $model->getAttributeLabel('incapacidad_ramo');?></label>
                <div class="form-control bg-disabled ">
                    <?php 
                    if(isset($model->incapacidad_ramo) && $model->incapacidad_ramo != null && $model->incapacidad_ramo != ''){
                        echo $ramo_incapacidad[$model->incapacidad_ramo].'&nbsp;';
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="row my-3">
            <div class="col-lg-3">
                <label class="control-label"><?= $model->getAttributeLabel('incapacidad_fechainicio');?></label>
                <div class="form-control bg-disabled "><?= $model->incapacidad_fechainicio.'&nbsp;';?></div>
            </div>
            <div class="col-lg-2">
                <label class="control-label"><?= $model->getAttributeLabel('incapacidad_dias');?></label>
                <div class="form-control bg-disabled "><?= $model->incapacidad_dias.'&nbsp;';?></div>
            </div>
            <div class="col-lg-3">
                <label class="control-label"><?= $model->getAttributeLabel('incapacidad_fechafin');?></label>
                <div class="form-control bg-disabled "><?= $model->incapacidad_fechafin.'&nbsp;';?></div>
            </div>
        </div>
    </div>


    <?php 
    $show = 'none';
    if($model->tipo == '6'){
        $show = 'block';
    }
   ?>
    <div class="container-fluid my-3 border30 bg-light p-4" id="bloque_riesgo" style="display:<?php echo $show;?>;">
        <div class="row m-0 p-0">
            <div class="col-lg-4 title2 boxtitle p-1 rounded-3 color3">
                <label class="">
                    <span class="mx-2"><i class="bi bi-exclamation-triangle"></i></span>
                    Trabajo de Riesgo
                </label>
            </div>
        </div>
        <div class="row my-3">
            <div class="col-lg-4">
                <label class="control-label"><?= $model->getAttributeLabel('triesgo_tipo');?></label>
                <div class="form-control bg-disabled ">
                    <?php 
                    if(isset($model->triesgo_tipo) && $model->triesgo_tipo != null && $model->triesgo_tipo != ''){
                        echo $riesgostipos[$model->triesgo_tipo].'&nbsp;';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid my-3 border30 bg-light p-4">
        <div class="row m-0 p-0">
            <div class="col-lg-4 title2 boxtitle p-1 rounded-3 color3 my-3">
                <label class="">
                    <span class="mx-2"><?php echo  $iconclip;?></span>
                    Exploración Física
                </label>
            </div>
        </div>
        <div class="row my-3">
            <div class="col-lg-2">
                <label class="control-label"><?= $model->getAttributeLabel('peso');?></label>
                <div class="form-control bg-disabled "><?= $model->peso.'&nbsp;';?></div>
            </div>
            <div class="col-lg-2">
                <label class="control-label"><?= $model->getAttributeLabel('talla');?></label>
                <div class="form-control bg-disabled "><?= $model->talla.'&nbsp;';?></div>
            </div>
            <div class="col-lg-2">
                <label class="control-label"><?= $model->getAttributeLabel('imc');?></label>
                <div class="form-control bg-disabled "><?= $model->imc.'&nbsp;';?></div>
            </div>
            <div class="col-lg-6">
                <label class="control-label"><?= $model->getAttributeLabel('categoria_imc');?></label>
                <div class="form-control bg-disabled "><?= $model->categoria_imc.'&nbsp;';?></div>
            </div>
        </div>
        <div class="row my-3">
            <div class="col-lg-2">
                <label class="control-label"><?= $model->getAttributeLabel('fc');?></label>
                <div class="form-control bg-disabled "><?= $model->fc.'&nbsp;';?></div>
            </div>
            <div class="col-lg-2">
                <label class="control-label"><?= $model->getAttributeLabel('fr');?></label>
                <div class="form-control bg-disabled "><?= $model->fr.'&nbsp;';?></div>
            </div>
            <div class="col-lg-2">
                <label class="control-label"><?= $model->getAttributeLabel('fr_diagnostico');?></label>
                <div class="form-control bg-disabled "><?= $model->fr_diagnostico.'&nbsp;';?></div>
            </div>
            <div class="col-lg-2">
                <label class="control-label"><?= $model->getAttributeLabel('temp');?></label>
                <div class="form-control bg-disabled "><?= $model->temp.'&nbsp;';?></div>
            </div>
            <div class="col-lg-2">
                <label class="control-label"><?= $model->getAttributeLabel('oxigeno');?></label>
                <div class="form-control bg-disabled "><?= $model->oxigeno.'&nbsp;';?></div>
            </div>
            <div class="col-lg-2">
                <label class="control-label"><?= $model->getAttributeLabel('oxigeoximetria_diagnosticono');?></label>
                <div class="form-control bg-disabled ">
                    <?php 
                    if(isset($model->oximetria_diagnostico) && $model->oximetria_diagnostico != null && $model->oximetria_diagnostico != ''){
                        echo $oxidiagnosticos[$model->oximetria_diagnostico].'&nbsp;';
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="row my-3">
            <div class="col-lg-2">
                <label class="control-label"><?= $model->getAttributeLabel('ta');?></label>
                <div class="form-control bg-disabled "><?= $model->ta.'&nbsp;';?></div>
            </div>
            <div class="col-lg-2">
                <label class="control-label"><?= $model->getAttributeLabel('ta_diastolica');?></label>
                <div class="form-control bg-disabled "><?= $model->ta_diastolica.'&nbsp;';?></div>
            </div>
            <div class="col-lg-2">
                <label class="control-label"><?= $model->getAttributeLabel('tasis_diagnostico');?></label>
                <div class="form-control bg-disabled ">
                    <?php 
                    if(isset($model->tasis_diagnostico) && $model->tasis_diagnostico != null && $model->tasis_diagnostico != '')
                    {
                        echo $tadiagnosticos[$model->tasis_diagnostico].'&nbsp;';
                    }
                    ?>
                </div>
            </div>
            <div class="col-lg-2">
                <label class="control-label"><?= $model->getAttributeLabel('tadis_diagnostico');?></label>
                <div class="form-control bg-disabled ">
                    <?php 
                    if(isset($model->tadis_diagnostico) && $model->tadis_diagnostico != null && $model->tadis_diagnostico != '')
                    {
                        echo $tadiagnosticos[$model->tadis_diagnostico].'&nbsp;';
                    }
                    ?></div>
            </div>
            <div class="col-lg-2">
                <label class="control-label"><?= $model->getAttributeLabel('ta_diagnostico');?></label>
                <div class="form-control bg-disabled ">
                    <?php 
                    if(isset($model->ta_diagnostico) && $model->ta_diagnostico != null && $model->ta_diagnostico != '')
                    {
                        echo $tadiagnosticos[$model->ta_diagnostico].'&nbsp;';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>


    <?php
    $iconheart = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-heart-pulse-fill" viewBox="0 0 16 16">
    <path d="M1.475 9C2.702 10.84 4.779 12.871 8 15c3.221-2.129 5.298-4.16 6.525-6H12a.5.5 0 0 1-.464-.314l-1.457-3.642-1.598 5.593a.5.5 0 0 1-.945.049L5.889 6.568l-1.473 2.21A.5.5 0 0 1 4 9H1.475Z"/>
    <path d="M.88 8C-2.427 1.68 4.41-2 7.823 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C11.59-2 18.426 1.68 15.12 8h-2.783l-1.874-4.686a.5.5 0 0 0-.945.049L7.921 8.956 6.464 5.314a.5.5 0 0 0-.88-.091L3.732 8H.88Z"/>
    </svg>';
    ?>
    <?php 
    $show = 'none';
    if($model->tipo == '7'){
        $show = 'block';
    }
   ?>
    <div class="container-fluid my-3 border30 bg-light p-4 bloque_programas" style="display:<?php echo $show;?>;">
        <?php
        $array = [];
        if($model->id_programa){
            $temp = $model->id_programa;

            $array = explode(",", $temp);
        }
       
        ?>
        <?php
        $showdiabetes = 'none';
        if (in_array('1', $array)) {
           $showdiabetes = 'block';
        }
        ?>
        <div id="diabetes" style="display:<?php echo $showdiabetes;?>;">
            <div class="row m-0 p-0">
                <div class="col-lg-4 title2 boxtitle p-1 rounded-3 color3 my-3">
                    <label class="">
                        <span class="mx-2"><?php echo  $iconheart;?></span>
                        Diabetes
                    </label>
                </div>
            </div>
            <?php $contador = 1;?>
            <div class="row py-1">
                <div class="col-lg-7">
                    <label class="control-label"><?=$contador.'.- '.$model->getAttributeLabel('ps_diabetes1');?></label>
                </div>
                <div class="col-lg-2">
                    <div class="form-control bg-disabled ">
                        <?php 
                        if(isset($model->ps_diabetes1) && $model->ps_diabetes1 != null && $model->ps_diabetes1 != ''){
                            echo $yes_no[$model->ps_diabetes1].'&nbsp;';
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php $contador++;?>
            <div class="row py-1">
                <div class="col-lg-7">
                    <label class="control-label"><?=$contador.'.- '.$model->getAttributeLabel('ps_diabetes2');?></label>
                </div>
                <div class="col-lg-2">
                    <div class="form-control bg-disabled ">
                        <?php 
                        if(isset($model->ps_diabetes2) && $model->ps_diabetes2 != null && $model->ps_diabetes2 != ''){
                            echo $yes_no[$model->ps_diabetes2].'&nbsp;';
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php $contador++;?>
            <div class="row py-1">
                <div class="col-lg-7">
                    <label class="control-label"><?=$contador.'.- '.$model->getAttributeLabel('ps_diabetes3');?></label>
                </div>
                <div class="col-lg-5">
                    <div class="form-control bg-disabled ">
                        <?php 
                        if(isset($model->ps_diabetes3) && $model->ps_diabetes3 != null && $model->ps_diabetes3 != ''){
                            echo $diabetes3[$model->ps_diabetes3].'&nbsp;';
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php $contador++;?>
            <div class="row py-1">
                <div class="col-lg-7">
                    <label class="control-label"><?=$contador.'.- '.$model->getAttributeLabel('ps_diabetes4');?></label>
                </div>
                <div class="col-lg-2">
                    <div class="form-control bg-disabled ">
                        <?php 
                        if(isset($model->ps_diabetes4) && $model->ps_diabetes4 != null && $model->ps_diabetes4 != ''){
                            echo $yes_no[$model->ps_diabetes4].'&nbsp;';
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php $contador++;?>
            <div class="row py-1">
                <div class="col-lg-7">
                    <label class="control-label"><?=$contador.'.- '.$model->getAttributeLabel('ps_diabetes5');?></label>
                </div>
                <div class="col-lg-2">
                    <div class="form-control bg-disabled ">
                        <?php 
                        if(isset($model->ps_diabetes5) && $model->ps_diabetes5 != null && $model->ps_diabetes5 != ''){
                            echo $yes_no[$model->ps_diabetes5].'&nbsp;';
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php $contador++;?>
            <div class="row py-1">
                <div class="col-lg-7">
                    <label class="control-label"><?=$contador.'.- '.$model->getAttributeLabel('ps_diabetes6');?></label>
                </div>
                <div class="col-lg-2">
                    <div class="form-control bg-disabled ">
                        <?php 
                        if(isset($model->ps_diabetes6) && $model->ps_diabetes6 != null && $model->ps_diabetes6 != ''){
                            echo $yes_no[$model->ps_diabetes6].'&nbsp;';
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php $contador++;?>
            <div class="row py-1">
                <div class="col-lg-7">
                    <label class="control-label"><?=$contador.'.- '.$model->getAttributeLabel('ps_diabetes7');?></label>
                </div>
                <div class="col-lg-2">
                    <div class="form-control bg-disabled ">
                        <?php 
                        if(isset($model->ps_diabetes7) && $model->ps_diabetes7 != null && $model->ps_diabetes7 != ''){
                            echo $yes_no[$model->ps_diabetes7].'&nbsp;';
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php $contador++;?>
            <div class="row py-1">
                <div class="col-lg-7">
                    <label class="control-label"><?=$contador.'.- '.$model->getAttributeLabel('ps_diabetes8');?></label>
                </div>
                <div class="col-lg-2">
                    <div class="form-control bg-disabled ">
                        <?php 
                        if(isset($model->ps_diabetes8) && $model->ps_diabetes8 != null && $model->ps_diabetes8 != ''){
                            echo $yes_no[$model->ps_diabetes8].'&nbsp;';
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php $contador++;?>
            <div class="row py-1">
                <div class="col-lg-7">
                    <label class="control-label"><?=$contador.'.- '.$model->getAttributeLabel('ps_diabetes9');?></label>
                </div>
                <div class="col-lg-2">
                    <div class="form-control bg-disabled ">
                        <?php 
                        if(isset($model->ps_diabetes9) && $model->ps_diabetes9 != null && $model->ps_diabetes9 != ''){
                            echo $yes_no[$model->ps_diabetes9].'&nbsp;';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>


        <?php
    $showhipertension = 'none';
    if (in_array('2', $array)) {
       $showhipertension = 'block';
    }
    ?>
        <div id="hipertension" style="display:<?php echo $showhipertension;?>;">
            <div class="row m-0 p-0">
                <div class="col-lg-4 title2 boxtitle p-1 rounded-3 color3 my-3">
                    <label class="">
                        <span class="mx-2"><?php echo  $iconheart;?></span>
                        Hipertensión
                    </label>
                </div>
            </div>
            <?php $contador = 1;?>
            <div class="row py-1">
                <div class="col-lg-7">
                    <label
                        class="control-label"><?=$contador.'.- '.$model->getAttributeLabel('ps_hipertension1');?></label>
                </div>
                <div class="col-lg-2">
                    <div class="form-control bg-disabled ">
                        <?php 
                        if(isset($model->ps_hipertension1) && $model->ps_hipertension1 != null && $model->ps_hipertension1 != ''){
                            echo $yes_no[$model->ps_hipertension1].'&nbsp;';
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php $contador++;?>
            <div class="row py-1">
                <div class="col-lg-7">
                    <label
                        class="control-label"><?=$contador.'.- '.$model->getAttributeLabel('ps_hipertension2');?></label>
                </div>
                <div class="col-lg-2">
                    <div class="form-control bg-disabled ">
                        <?php 
                        if(isset($model->ps_hipertension2) && $model->ps_hipertension2 != null && $model->ps_hipertension2 != ''){
                            echo $yes_no[$model->ps_hipertension2].'&nbsp;';
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php $contador++;?>
            <div class="row py-1">
                <div class="col-lg-7">
                    <label
                        class="control-label"><?=$contador.'.- '.$model->getAttributeLabel('ps_hipertension3');?></label>
                </div>
                <div class="col-lg-2">
                    <div class="form-control bg-disabled ">
                        <?php 
                        if(isset($model->ps_hipertension3) && $model->ps_hipertension3 != null && $model->ps_hipertension3 != ''){
                            echo $yes_no[$model->ps_hipertension3].'&nbsp;';
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php $contador++;?>
            <div class="row py-1">
                <div class="col-lg-7">
                    <label
                        class="control-label"><?=$contador.'.- '.$model->getAttributeLabel('ps_hipertension4');?></label>
                </div>
                <div class="col-lg-2">
                    <div class="form-control bg-disabled ">
                        <?php 
                        if(isset($model->ps_hipertension4) && $model->ps_hipertension4 != null && $model->ps_hipertension4 != ''){
                            echo $yes_no[$model->ps_hipertension4].'&nbsp;';
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php $contador++;?>
            <div class="row py-1">
                <div class="col-lg-7">
                    <label
                        class="control-label"><?=$contador.'.- '.$model->getAttributeLabel('ps_hipertension5');?></label>
                </div>
                <div class="col-lg-2">
                    <div class="form-control bg-disabled ">
                        <?php 
                        if(isset($model->ps_hipertension5) && $model->ps_hipertension5 != null && $model->ps_hipertension5 != ''){
                            echo $yes_no[$model->ps_hipertension5].'&nbsp;';
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php $contador++;?>
            <div class="row py-1">
                <div class="col-lg-7">
                    <label
                        class="control-label"><?=$contador.'.- '.$model->getAttributeLabel('ps_hipertension6');?></label>
                </div>
                <div class="col-lg-5">
                    <div class="form-control bg-disabled ">
                        <?php 
                        if(isset($model->ps_hipertension6) && $model->ps_hipertension6 != null && $model->ps_hipertension6 != ''){
                            echo $hipertension6[$model->ps_hipertension6].'&nbsp;';
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php $contador++;?>
            <div class="row py-1">
                <div class="col-lg-7">
                    <label
                        class="control-label"><?=$contador.'.- '.$model->getAttributeLabel('ps_hipertension7');?></label>
                </div>
                <div class="col-lg-2">
                    <div class="form-control bg-disabled ">
                        <?php 
                        if(isset($model->ps_hipertension7) && $model->ps_hipertension7 != null && $model->ps_hipertension7 != ''){
                            echo $yes_no[$model->ps_hipertension7].'&nbsp;';
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php $contador++;?>
            <div class="row py-1">
                <div class="col-lg-7">
                    <label
                        class="control-label"><?=$contador.'.- '.$model->getAttributeLabel('ps_hipertension8');?></label>
                </div>
                <div class="col-lg-2">
                    <div class="form-control bg-disabled ">
                        <?php 
                        if(isset($model->ps_hipertension8) && $model->ps_hipertension8 != null && $model->ps_hipertension8 != ''){
                            echo $yes_no[$model->ps_hipertension8].'&nbsp;';
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php $contador++;?>
            <div class="row py-1">
                <div class="col-lg-7">
                    <label
                        class="control-label"><?=$contador.'.- '.$model->getAttributeLabel('ps_hipertension9');?></label>
                </div>
                <div class="col-lg-2">
                    <div class="form-control bg-disabled ">
                        <?php 
                        if(isset($model->ps_hipertension9) && $model->ps_hipertension9 != null && $model->ps_hipertension9 != ''){
                            echo $yes_no[$model->ps_hipertension9].'&nbsp;';
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php $contador++;?>
            <div class="row py-1">
                <div class="col-lg-7">
                    <label
                        class="control-label"><?=$contador.'.- '.$model->getAttributeLabel('ps_hipertension10');?></label>
                </div>
                <div class="col-lg-2">
                    <div class="form-control bg-disabled ">
                        <?php 
                        if(isset($model->ps_hipertension10) && $model->ps_hipertension10 != null && $model->ps_hipertension10 != ''){
                            echo $yes_no[$model->ps_hipertension10].'&nbsp;';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>


        <?php
    $showmaternidad = 'none';
    if (in_array('3', $array)) {
       $showmaternidad = 'block';
    }
    ?>
        <div id="maternidad" style="display:<?php echo $showmaternidad;?>;">
            <div class="row m-0 p-0">
                <div class="col-lg-4 title2 boxtitle p-1 rounded-3 color3 my-3">
                    <label class="">
                        <span class="mx-2"><?php echo  $iconheart;?></span>
                        Maternidad
                    </label>
                </div>
            </div>
            <?php $contador = 1;?>
            <div class="row py-1">
                <div class="col-lg-7">
                    <label
                        class="control-label"><?=$contador.'.- '.$model->getAttributeLabel('ps_maternidad1');?></label>
                </div>
                <div class="col-lg-2">
                    <div class="form-control bg-disabled ">
                        <?php 
                        if(isset($model->ps_maternidad1) && $model->ps_maternidad1 != null && $model->ps_maternidad1 != ''){
                            echo $yes_no[$model->ps_maternidad1].'&nbsp;';
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php $contador++;?>
            <div class="row py-1">
                <div class="col-lg-7">
                    <label
                        class="control-label"><?=$contador.'.- '.$model->getAttributeLabel('ps_maternidad2');?></label>
                </div>
                <div class="col-lg-2">
                    <div class="form-control bg-disabled ">
                        <?php 
                        if(isset($model->ps_maternidad2) && $model->ps_maternidad2 != null && $model->ps_maternidad2 != ''){
                            echo $yes_no[$model->ps_maternidad2].'&nbsp;';
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php $contador++;?>
            <div class="row py-1">
                <div class="col-lg-7">
                    <label
                        class="control-label"><?=$contador.'.- '.$model->getAttributeLabel('ps_maternidad3');?></label>
                </div>
                <div class="col-lg-2">
                    <div class="form-control bg-disabled ">
                        <?php 
                        if(isset($model->ps_maternidad3) && $model->ps_maternidad3 != null && $model->ps_maternidad3 != ''){
                            echo $yes_no[$model->ps_maternidad3].'&nbsp;';
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php $contador++;?>
            <div class="row py-1">
                <div class="col-lg-7">
                    <label
                        class="control-label"><?=$contador.'.- '.$model->getAttributeLabel('ps_maternidad4');?></label>
                </div>
                <div class="col-lg-2">
                    <div class="form-control bg-disabled ">
                        <?php 
                        if(isset($model->ps_maternidad4) && $model->ps_maternidad4 != null && $model->ps_maternidad4 != ''){
                            echo $yes_no[$model->ps_maternidad4].'&nbsp;';
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php $contador++;?>
            <div class="row py-1">
                <div class="col-lg-7">
                    <label
                        class="control-label"><?=$contador.'.- '.$model->getAttributeLabel('ps_maternidad5');?></label>
                </div>
                <div class="col-lg-2">
                    <div class="form-control bg-disabled ">
                        <?php 
                        if(isset($model->ps_maternidad5) && $model->ps_maternidad5 != null && $model->ps_maternidad5 != ''){
                            echo $yes_no[$model->ps_maternidad5].'&nbsp;';
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php $contador++;?>
            <div class="row py-1">
                <div class="col-lg-7">
                    <label
                        class="control-label"><?=$contador.'.- '.$model->getAttributeLabel('ps_maternidad6');?></label>
                </div>
                <div class="col-lg-2">
                    <div class="form-control bg-disabled ">
                        <?php 
                        if(isset($model->ps_maternidad6) && $model->ps_maternidad6 != null && $model->ps_maternidad6 != ''){
                            echo $yes_no[$model->ps_maternidad6].'&nbsp;';
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php $contador++;?>
            <div class="row py-1">
                <div class="col-lg-7">
                    <label
                        class="control-label"><?=$contador.'.- '.$model->getAttributeLabel('ps_maternidad7');?></label>
                </div>
                <div class="col-lg-2">
                    <div class="form-control bg-disabled ">
                        <?php 
                        if(isset($model->ps_maternidad7) && $model->ps_maternidad7 != null && $model->ps_maternidad7 != ''){
                            echo $yes_no[$model->ps_maternidad7].'&nbsp;';
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php $contador++;?>
            <div class="row py-1">
                <div class="col-lg-7">
                    <label
                        class="control-label"><?=$contador.'.- '.$model->getAttributeLabel('ps_maternidad8');?></label>
                </div>
                <div class="col-lg-2">
                    <div class="form-control bg-disabled ">
                        <?php 
                        if(isset($model->ps_maternidad8) && $model->ps_maternidad8 != null && $model->ps_maternidad8 != ''){
                            echo $yes_no[$model->ps_maternidad8].'&nbsp;';
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php $contador++;?>
            <div class="row py-1">
                <div class="col-lg-7">
                    <label
                        class="control-label"><?=$contador.'.- '.$model->getAttributeLabel('ps_maternidad9');?></label>
                </div>
                <div class="col-lg-2">
                    <div class="form-control bg-disabled ">
                        <?php 
                        if(isset($model->ps_maternidad9) && $model->ps_maternidad9 != null && $model->ps_maternidad9 != ''){
                            echo $yes_no[$model->ps_maternidad9].'&nbsp;';
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php $contador++;?>
            <div class="row py-1">
                <div class="col-lg-7">
                    <label
                        class="control-label"><?=$contador.'.- '.$model->getAttributeLabel('ps_maternidad10');?></label>
                </div>
                <div class="col-lg-2">
                    <div class="form-control bg-disabled ">
                        <?php 
                        if(isset($model->ps_maternidad10) && $model->ps_maternidad10 != null && $model->ps_maternidad10 != ''){
                            echo $yes_no[$model->ps_maternidad10].'&nbsp;';
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php $contador++;?>
            <div class="row py-1">
                <div class="col-lg-7">
                    <label
                        class="control-label"><?=$contador.'.- '.$model->getAttributeLabel('ps_maternidad11');?></label>
                </div>
                <div class="col-lg-2">
                    <div class="form-control bg-disabled ">
                        <?php 
                        if(isset($model->ps_maternidad11) && $model->ps_maternidad11 != null && $model->ps_maternidad11 != ''){
                            echo $yes_no[$model->ps_maternidad11].'&nbsp;';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>


        <?php
    $showlactancia = 'none';
    if (in_array('4', $array)) {
       $showlactancia = 'block';
    }
    ?>
        <div id="lactancia" style="display:<?php echo $showlactancia;?>;">
            <div class="row m-0 p-0">
                <div class="col-lg-4 title2 boxtitle p-1 rounded-3 color3 my-3">
                    <label class="">
                        <span class="mx-2"><?php echo  $iconheart;?></span>
                        Lactancia
                    </label>
                </div>
            </div>
            <?php $contador = 1;?>
            <div class="row py-1">
                <div class="col-lg-7">
                    <label
                        class="control-label"><?=$contador.'.- '.$model->getAttributeLabel('ps_lactancia1');?></label>
                </div>
                <div class="col-lg-3">
                    <div class="form-control bg-disabled ">
                        <?php 
                        if(isset($model->ps_lactancia1) && $model->ps_lactancia1 != null && $model->ps_lactancia1 != ''){
                            echo $yes_no[$model->ps_lactancia1].'&nbsp;';
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php $contador++;?>
            <div class="row py-1">
                <div class="col-lg-7">
                    <label
                        class="control-label"><?=$contador.'.- '.$model->getAttributeLabel('ps_lactancia2');?></label>
                </div>
                <div class="col-lg-2">
                    <div class="form-control bg-disabled ">
                        <?php 
                        if(isset($model->ps_lactancia2) && $model->ps_lactancia2 != null && $model->ps_lactancia2 != ''){
                            echo $lactancia2[$model->ps_lactancia2].'&nbsp;';
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php $contador++;?>
            <div class="row py-1">
                <div class="col-lg-7">
                    <label
                        class="control-label"><?=$contador.'.- '.$model->getAttributeLabel('ps_lactancia3');?></label>
                </div>
                <div class="col-lg-5">
                    <div class="form-control bg-disabled "><?= $model->ps_lactancia3.'&nbsp;';?></div>
                </div>
            </div>
            <?php $contador++;?>
            <div class="row py-1">
                <div class="col-lg-7">
                    <label
                        class="control-label"><?=$contador.'.- '.$model->getAttributeLabel('ps_lactancia4');?></label>
                </div>
                <div class="col-lg-5">
                    <div class="form-control bg-disabled "><?= $model->ps_lactancia4.'&nbsp;';?></div>
                </div>
            </div>
            <?php $contador++;?>
            <div class="row py-1">
                <div class="col-lg-7">
                    <label
                        class="control-label"><?=$contador.'.- '.$model->getAttributeLabel('ps_lactancia5');?></label>
                </div>
                <div class="col-lg-2">
                    <div class="form-control bg-disabled ">
                        <?php 
                        if(isset($model->ps_lactancia5) && $model->ps_lactancia5 != null && $model->ps_lactancia5 != ''){
                            echo $yes_no[$model->ps_lactancia5].'&nbsp;';
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php $contador++;?>
            <div class="row py-1">
                <div class="col-lg-7">
                    <label
                        class="control-label"><?=$contador.'.- '.$model->getAttributeLabel('ps_lactancia6');?></label>
                </div>
                <div class="col-lg-5">
                    <div class="form-control bg-disabled ">
                        <?php 
                        if(isset($model->ps_lactancia6) && $model->ps_lactancia6 != null && $model->ps_lactancia6 != ''){
                            echo $lactancia6[$model->ps_lactancia6].'&nbsp;';
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php $contador++;?>
            <div class="row py-1">
                <div class="col-lg-7">
                    <label
                        class="control-label"><?=$contador.'.- '.$model->getAttributeLabel('ps_lactancia7');?></label>
                </div>
                <div class="col-lg-5">
                    <div class="form-control bg-disabled ">
                        <?php 
                        if(isset($model->ps_lactancia7) && $model->ps_lactancia7 != null && $model->ps_lactancia7 != ''){
                            echo $lactancia7[$model->ps_lactancia7].'&nbsp;';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>


        <?php
        $showhiperdiabetes = 'none';
        if (in_array('5', $array)) {
            $showhiperdiabetes = 'block';
        }
        ?>
        <div id="hiperdiabetes" style="display:<?php echo $showhiperdiabetes;?>;">
            <div class="row m-0 p-0">
                <div class="col-lg-4 title2 boxtitle p-1 rounded-3 color3 my-3">
                    <label class="">
                        <span class="mx-2"><?php echo  $iconheart;?></span>
                        Hispertensión y Diabetes
                    </label>
                </div>
            </div>
            <?php $contador = 1;?>
            <div class="row py-1">
                <div class="col-lg-7">
                    <label
                        class="control-label"><?=$contador.'.- '.$model->getAttributeLabel('ps_hiperdiabe1');?></label>
                </div>
                <div class="col-lg-5">
                    <div class="form-control bg-disabled ">
                        <?php 
                        if(isset($model->ps_hiperdiabe1) && $model->ps_hiperdiabe1 != null && $model->ps_hiperdiabe1 != ''){
                            echo $hiperdiabe1[$model->ps_hiperdiabe1].'&nbsp;';
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php $contador++;?>
            <div class="row py-1">
                <div class="col-lg-7">
                    <label
                        class="control-label"><?=$contador.'.- '.$model->getAttributeLabel('ps_hiperdiabe2');?></label>
                </div>
                <div class="col-lg-5">
                    <div class="form-control bg-disabled ">
                        <?php 
                        if(isset($model->ps_hiperdiabe2) && $model->ps_hiperdiabe2 != null && $model->ps_hiperdiabe2 != ''){
                            echo $hiperdiabe2[$model->ps_hiperdiabe2].'&nbsp;';
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php $contador++;?>
            <div class="row py-1">
                <div class="col-lg-7">
                    <label
                        class="control-label"><?=$contador.'.- '.$model->getAttributeLabel('ps_hiperdiabe3');?></label>
                </div>
                <div class="col-lg-5">
                    <div class="form-control bg-disabled ">
                        <?php 
                        if(isset($model->ps_hiperdiabe3) && $model->ps_hiperdiabe3 != null && $model->ps_hiperdiabe3 != ''){
                            echo $hiperdiabe3[$model->ps_hiperdiabe3].'&nbsp;';
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php $contador++;?>
            <div class="row py-1">
                <div class="col-lg-7">
                    <label
                        class="control-label"><?=$contador.'.- '.$model->getAttributeLabel('ps_hiperdiabe4');?></label>
                </div>
                <div class="col-lg-2">
                    <div class="form-control bg-disabled ">
                        <?php 
                        if(isset($model->ps_hiperdiabe4) && $model->ps_hiperdiabe4 != null && $model->ps_hiperdiabe4 != ''){
                            echo $yes_no[$model->ps_hiperdiabe4];
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php $contador++;?>
            <div class="row py-1">
                <div class="col-lg-7">
                    <label
                        class="control-label"><?=$contador.'.- '.$model->getAttributeLabel('ps_hiperdiabe5');?></label>
                </div>
                <div class="col-lg-2">
                    <div class="form-control bg-disabled ">
                        <?php 
                        if(isset($model->ps_hiperdiabe5) && $model->ps_hiperdiabe5 != null && $model->ps_hiperdiabe5 != ''){
                            echo $yes_no[$model->ps_hiperdiabe5].'&nbsp;';
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php $contador++;?>
            <div class="row py-1">
                <div class="col-lg-7">
                    <label
                        class="control-label"><?=$contador.'.- '.$model->getAttributeLabel('ps_hiperdiabe6');?></label>
                </div>
                <div class="col-lg-2">
                    <div class="form-control bg-disabled ">
                        <?php 
                        if(isset($model->ps_hiperdiabe6) && $model->ps_hiperdiabe6 != null && $model->ps_hiperdiabe6 != ''){
                            echo $yes_no[$model->ps_hiperdiabe6].'&nbsp;';
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php $contador++;?>
            <div class="row py-1">
                <div class="col-lg-7">
                    <label
                        class="control-label"><?=$contador.'.- '.$model->getAttributeLabel('ps_hiperdiabe7');?></label>
                </div>
                <div class="col-lg-2">
                    <div class="form-control bg-disabled ">
                        <?php 
                        if(isset($model->ps_hiperdiabe7) && $model->ps_hiperdiabe7 != null && $model->ps_hiperdiabe7 != ''){
                            echo $yes_no[$model->ps_hiperdiabe7].'&nbsp;';
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php $contador++;?>
            <div class="row py-1">
                <div class="col-lg-7">
                    <label
                        class="control-label"><?=$contador.'.- '.$model->getAttributeLabel('ps_hiperdiabe8');?></label>
                </div>
                <div class="col-lg-2">
                    <div class="form-control bg-disabled ">
                        <?php 
                        if(isset($model->ps_hiperdiabe8) && $model->ps_hiperdiabe8 != null && $model->ps_hiperdiabe8 != ''){
                            echo $yes_no[$model->ps_hiperdiabe8].'&nbsp;';
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php $contador++;?>
            <div class="row py-1">
                <div class="col-lg-7">
                    <label
                        class="control-label"><?=$contador.'.- '.$model->getAttributeLabel('ps_hiperdiabe9');?></label>
                </div>
                <div class="col-lg-2">
                    <div class="form-control bg-disabled ">
                        <?php 
                        if(isset($model->ps_hiperdiabe9) && $model->ps_hiperdiabe9 != null && $model->ps_hiperdiabe9 != ''){
                            echo $yes_no[$model->ps_hiperdiabe9].'&nbsp;';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row my-3 mt-5">
        <div class="col-lg-12">
            <label class="control-label"><?= $model->getAttributeLabel('sintomatologia');?></label>
            <div class="form-control bg-disabled "><?=$model->sintomatologia.'&nbsp;';?></div>
        </div>
        <?php 
        $show = 'none';
        if($model->solicitante == '2' || $model->solicitante == '3'){
            $show = 'block';
        }
        ?>
        <div class="col-lg-12 my-3 apartado_nombre" style="display:<?php echo $show;?>;">
            <label class="control-label"><?= $model->getAttributeLabel('alergias');?></label>
            <div class="form-control bg-disabled "><?=$model->alergias.'&nbsp;';?></div>
        </div>
        <div class="col-lg-12 my-3">
            <label class="control-label"><?= $model->getAttributeLabel('diagnostico');?></label>
            <div class="form-control bg-disabled "><?=$model->diagnostico.'&nbsp;';?></div>
        </div>
        <div class="row my-3">
            <div class="col-lg-12 my-3">
                <label class="control-label"><?= $model->getAttributeLabel('diagnosticocie');?></label>
                <div class="form-control bg-disabled ">
                    <?php
                    $label_diagnosticos = '';
                    if($model->diagnosticocie){
                        $array = explode(',', $model->diagnosticocie);
                        foreach($array as $key=>$dig){
                            $diag = Diagnosticoscie::find()->where(['id'=>$dig])->one();
                            if($diag){
                                $label_diagnosticos .= $diag->diagnostico.', ';
                            }
                        }
                    } 
                    echo $label_diagnosticos.'&nbsp;';
                ?>
                </div>
            </div>
        </div>
        <div class="col-lg-12 my-3">
            <label class="control-label"><?= $model->getAttributeLabel('estudios');?></label>
            <div class="form-control bg-disabled "><?=$model->estudios.'&nbsp;';?></div>
        </div>
        <div class="col-lg-12 my-3">
            <label class="control-label"><?= $model->getAttributeLabel('manejo');?></label>
            <div class="form-control bg-disabled "><?=$model->manejo.'&nbsp;';?></div>
        </div>
        <div class="col-lg-12 my-3">
            <label class="control-label"><?= $model->getAttributeLabel('seguimiento');?></label>
            <div class="form-control bg-disabled "><?=$model->seguimiento.'&nbsp;';?></div>
        </div>
    </div>

    <div class="row my-3 mt-5">
        <div class="col-lg-6">
            <label class="control-label"><?= $model->getAttributeLabel('file_evidencia');?></label>
            <div class="form-control bg-disabled ">
                <?php
            if($model->file_evidencia){
                $evidencia_file = 'resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id_trabajador.'/Consultas/'.$model->file_evidencia;
                $evidencia = Html::a('<span style="font-size:30px;" class="color7"><i class="bi bi-file-pdf-fill"></i></span>', $evidencia_file, $options = ['target'=>'_blank']);
                echo $evidencia.'&nbsp;';
            }
            ?>
            </div>
        </div>
        <div class="col-lg-6">
            <?php
            if(isset($model->evidencia) && $model->evidencia!= null && $model->evidencia != '' && $model->evidencia != ' '){
                $imageevidencia = '<span class="color15" style="font-size:100px"><i class="bi bi-folder-fill"></i></span><span class="badge bgtransparent2 color11 font12 m-1">Evidencia</span>';
                $filePath =  '/resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id_trabajador.'/Consultas/'.$model->evidencia;
                $ret = Html::a($imageevidencia, '@web'.$filePath, $options = ['target'=>'_blank','title' => Yii::t('app', 'Evidencia'),'data-bs-toggle'=>"tooltip",'data-bs-placement'=>"top"]);
                echo $ret;
            } else {
                echo '&nbsp;';
            }
            ?>
        </div>
    </div>


    <div class="container-fluid my-3 border30 bg-customlight border-custom p-2">
        <div class="row m-0 p-0">
            <div class="col-lg-4 title2 boxtitle p-1 rounded-3 color3 my-3">
                <label class="">
                    <span class="mx-2"><?php echo  $iconclip;?></span>
                    Medicamentos
                </label>
            </div>
        </div>
        <div class="row">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th class="control-label font500" width="5%">#</th>
                        <th class="control-label font500" width="40%">Medicamento(Nombre Comercial)</th>
                        <th class="control-label font500" width="40%">Nombre Genérico</th>
                        <th class="control-label font500" width="15%">Cantidad Unidad</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $movimiento = Movimientos::find()->where(['id_consultahc'=>$model->id])->one();
                    if($movimiento){
                        foreach($movimiento->medicamentos as $key=>$medicamento){
                            $almacen = Almacen::find()->where(['id_insumo'=>$medicamento->id_insumo])->andWhere(['id_consultorio'=>$model->id_consultorio])->andWhere(['fecha_caducidad'=>$medicamento->fecha_caducidad])->one();
                            if($almacen){
                                echo '<tr><td class="t5 color3">'.($key+1).'</td>
                                <td class="t5 color3">'.$almacen->insumo->nombre_comercial.'</td>
                                <td class="t5">'.$almacen->insumo->nombre_generico.'</td>
                                <td class="t5 t4">'.$medicamento->cantidad_unidad.'</td>
                                </tr>';
                            }
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>


    <div class="row my-3 mt-5">
        <div class="col-lg-4">
            <label class="control-label"><?= $model->getAttributeLabel('resultado');?></label>
            <div class="form-control bg-disabled ">
                <?php 
                    if(isset($model->resultado) && $model->resultado != null && $model->resultado != ''){
                        echo $resultados[$model->resultado].'&nbsp;';
                    }
                ?>
            </div>
        </div>
        <div class="col-lg-4">
            <?php
            $tipospadecimientos = ['1'=>'LABORAL','2'=>'NO LABORAL'];
            if($model->solicitante == 2 ||$model->solicitante == 3){
                $tipospadecimientos = ['3'=>'GENERAL'];
            }
            ?>
            <label class="control-label"><?= $model->getAttributeLabel('tipo_padecimiento');?></label>
            <div class="form-control bg-disabled ">
                <?php 
                    if(isset($model->tipo_padecimiento) && $model->tipo_padecimiento != null && $model->tipo_padecimiento != ''){
                        echo $tipospadecimientos[$model->tipo_padecimiento].'&nbsp;';
                    }
                ?>
            </div>
        </div>
        <div class="col-lg-4">
            <?php
            $tipososha = ['1'=>'ANOTHER RECORDABLE CASES','2'=>'NO  WORK RELEATED','3'=>'NO APLICA'];
            ?>
            <label class="control-label"><?= $model->getAttributeLabel('calificacion_osha');?></label>
            <div class="form-control bg-disabled ">
                <?php 
                    if(isset($model->calificacion_osha) && $model->calificacion_osha != null && $model->calificacion_osha != ''){
                        echo $tipososha[$model->calificacion_osha].'&nbsp;';
                    }
                ?>
            </div>
        </div>
    </div>

    <?php
    $url = Url::to(['firma']);
    ?>
    <div class="row my-3">
        <div class="col-lg-12 text-center">
            <?php if(isset($model->firma_ruta) && $model->firma_ruta != null && $model->firma_ruta != ''):?>
            <img src="<?php  echo '/web/resources/Empresas/'.$model->id_empresa.'/Consultas/'.$model->id.'/'.$model->firma_ruta;?>"
                class="img-fluid img-responsive" width="500px" height="auto" />
            <?php endif;?>
        </div>
    </div>


    <div class="row my-4">
        <div class="col-lg-4">
            <label class="control-label"><?= $model->getAttributeLabel('status');?></label>
            <div class="form-control bg-disabled ">
                <?php 
                    if(isset($model->status) && $model->status != null && $model->status != ''){
                        echo $status[$model->status].'&nbsp;';
                    }
                ?>
            </div>
        </div>
    </div>

</div>