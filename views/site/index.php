<?php
use dosamigos\chartjs\ChartJs;
use app\models\Empresas;
use app\models\Areas;
use app\models\Puestostrabajo;
use app\models\Riesgos;
use app\models\Trabajadores;
use app\models\Cuestionario;
use app\models\Consultas;
use app\models\Hccohc;
use app\models\Poes;
use app\models\Poeestudio;
use app\models\Servicios;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
/** @var yii\web\View $this */

$this->title = 'SMO Tools';
$this->registerCss("
body {
   background-color:#F3F8FF;
}
");
?>

<?php
$logo = '';
$name_user = '';
if(!Yii::$app->user->isGuest){
    $name_user = Yii::$app->user->identity->name;
    if(Yii::$app->user->identity->empresa && isset(Yii::$app->user->identity->empresa->logo) && Yii::$app->user->identity->empresa->logo != ""){
        $filePath =  '/resources/Empresas/'.Yii::$app->user->identity->empresa->id.'/'.Yii::$app->user->identity->empresa->logo;
        $foto = Html::img('@web'. $filePath, ['alt'=>' ', 'class' => "iconphoto img-responsive", 'height' => '50px', 'width' =>'auto','style'=>'']);
        $logo = $foto;
    }
}

$icon_person = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-arms-up" viewBox="0 0 16 16">
<path d="M8 3a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3"/>
<path d="m5.93 6.704-.846 8.451a.768.768 0 0 0 1.523.203l.81-4.865a.59.59 0 0 1 1.165 0l.81 4.865a.768.768 0 0 0 1.523-.203l-.845-8.451A1.5 1.5 0 0 1 10.5 5.5L13 2.284a.796.796 0 0 0-1.239-.998L9.634 3.84a.7.7 0 0 1-.33.235c-.23.074-.665.176-1.304.176-.64 0-1.074-.102-1.305-.176a.7.7 0 0 1-.329-.235L4.239 1.286a.796.796 0 0 0-1.24.998l2.5 3.216c.317.316.475.758.43 1.204Z"/>
</svg>';

$icon_firma = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-feather" viewBox="0 0 16 16">
<path d="M15.807.531c-.174-.177-.41-.289-.64-.363a3.8 3.8 0 0 0-.833-.15c-.62-.049-1.394 0-2.252.175C10.365.545 8.264 1.415 6.315 3.1S3.147 6.824 2.557 8.523c-.294.847-.44 1.634-.429 2.268.005.316.05.62.154.88q.025.061.056.122A68 68 0 0 0 .08 15.198a.53.53 0 0 0 .157.72.504.504 0 0 0 .705-.16 68 68 0 0 1 2.158-3.26c.285.141.616.195.958.182.513-.02 1.098-.188 1.723-.49 1.25-.605 2.744-1.787 4.303-3.642l1.518-1.55a.53.53 0 0 0 0-.739l-.729-.744 1.311.209a.5.5 0 0 0 .443-.15l.663-.684c.663-.68 1.292-1.325 1.763-1.892.314-.378.585-.752.754-1.107.163-.345.278-.773.112-1.188a.5.5 0 0 0-.112-.172M3.733 11.62C5.385 9.374 7.24 7.215 9.309 5.394l1.21 1.234-1.171 1.196-.027.03c-1.5 1.789-2.891 2.867-3.977 3.393-.544.263-.99.378-1.324.39a1.3 1.3 0 0 1-.287-.018Zm6.769-7.22c1.31-1.028 2.7-1.914 4.172-2.6a7 7 0 0 1-.4.523c-.442.533-1.028 1.134-1.681 1.804l-.51.524zm3.346-3.357C9.594 3.147 6.045 6.8 3.149 10.678c.007-.464.121-1.086.37-1.806.533-1.535 1.65-3.415 3.455-4.976 1.807-1.561 3.746-2.36 5.31-2.68a8 8 0 0 1 1.564-.173"/>
</svg>';



/* $colores = ['#864AF9','#FF6868','#37B5B6','#4942E4','#96EFFF','#9400FF','#37B5B6','#FF9800','#F29727','#F24C3D','#05BFDB','#6DA9E4','#A084E8','#B9EDDD','#D61355','#00D7FF','#876445','#65B741','#9ADE7B','#1F8A70','#FDE767','#F4F27E','#E9B824','#6D2932','#7E6363','#B99470','#FE7A36','#EA906C','#FF6C22','#E25E3E','#EF4040','#BE3144','#FF6666','#FF9BD2','#F875AA','#FF52A2','#6C22A6','#DC84F3','#BEADFA','#3652AD','#596FB7','#A0BFE0','#3D3B40','#0F0F0F','#B4B4B3','#9BA4B5','#332C39'];

$empresas = explode(',', Yii::$app->user->identity->empresas_select);

$antiguedades =['1'=>'1 mes','2'=>'3 meses','3'=>'6 meses','4'=>'9 meses','5'=>'1 año','6'=>'1 año 3 meses','7'=>'1 año 6 meses','8'=>'1 año 9 meses','9'=>'2 años','10'=>'3 años','11'=>'4 años','12'=>'5 años','13'=>'+ 5 años'];
        
if(Yii::$app->user->identity->empresa_all != 1) {
    $empresas = ArrayHelper::map(Empresas::find()->where(['in','id',$empresas])->orderBy('comercial')->all(), 'id', 'comercial');
} else{
    $empresas = ArrayHelper::map(Empresas::find()->orderBy('comercial')->all(), 'id', 'comercial');
}

$ids_empresas = [];

foreach($empresas as $key=>$item){
    if(!in_array($key, $ids_empresas)){
        array_push($ids_empresas, $key);
    }
}


$areas = ArrayHelper::map(Areas::find()->where(['in','id_empresa',$ids_empresas])->orderBy('area')->all(), 'id', 'area');
$puestos = ArrayHelper::map(Puestostrabajo::find()->where(['in','id_empresa',$ids_empresas])->orderBy('nombre')->all(), 'id', 'nombre');
$riesgos = ArrayHelper::map(Riesgos::find()->orderBy('riesgo')->all(), 'id', 'riesgo');
$poes = Poes::find()->where(['in','id_empresa',$ids_empresas])->all();

$trabajadores = Trabajadores::find()->where(['in','id_empresa',$ids_empresas])->all();
$trabajadoresactivos = Trabajadores::find()->where(['in','id_empresa',$ids_empresas])->andWhere(['status'=>1])->all();


$ids_trabajadores = [];
$hombres_activos = [];
$mujeres_activos = [];
$otros_activos = [];

foreach($trabajadoresactivos as $key=>$trabajador){
    if($trabajador->sexo == 1){
        if(!in_array($trabajador->id, $hombres_activos)){
            array_push($hombres_activos, $trabajador->id);
        }
    } else if($trabajador->sexo == 2){
        if(!in_array($trabajador->id, $mujeres_activos)){
            array_push($mujeres_activos, $trabajador->id);
        }
    } else if($trabajador->sexo == 3){
        if(!in_array($trabajador->id, $otros_activos)){
            array_push($otros_activos, $trabajador->id);
        }
    }
    if(!in_array($trabajador->id, $ids_trabajadores)){
        array_push($ids_trabajadores, $trabajador->id);
    }
}


$tipoexamen = ['ACCIDENTE','ANTIDOPING','CLÍNICA','INCAPACIDAD','PREOCUPANTE','TRABAJOS DE RIESGO','PROGRAMAS DE SALUD','COVID-19','NUTRICIÓN','PSICOLÓGICA','ALCOHOLEMIA']; 
$tipoexamentotal = [0,0,0,0,0,0,0,0,0,0,0];
$tipoexamencolor = ['#FFFFFF','#FFFFFF','#FFFFFF','#FFFFFF','#FFFFFF','#FFFFFF','#FFFFFF','#FFFFFF','#FFFFFF','#FFFFFF','#FFFFFF'];
$total_consultas = count(Consultas::find()->where(['in','id_empresa',$ids_empresas])->all());
$total_poesorig = count(Poes::find()->where(['in','id_empresa',$ids_empresas])->all());
$consultas = Consultas::find()->where(['in','id_trabajador',$ids_trabajadores])->all();

foreach($consultas as $key=>$consulta){
    if(isset($consulta->tipo) && $consulta->tipo != null && $consulta->tipo != ''){
        $tipoexamentotal[($consulta->tipo)-1] = $tipoexamentotal[($consulta->tipo)-1]+1;
    }
    $tipoexamencolor[($consulta->tipo)-1] = $colores[rand(1,(count($colores)-1))];
}

$statushc = ['Pendiente','Abierta','Cerrada','Cancelada'];
$statushctotal = [0,0,0,0];
$statushccolor = ['#A9A9A9','#F4CE14','#37B5B6','#EF4040'];

$total_historias = count(Hccohc::find()->where(['in','id_empresa',$ids_empresas])->all());
$historias = Hccohc::find()->where(['in','id_trabajador',$ids_trabajadores])->all();
$historiascompletas = Hccohc::find()->where(['in','id_trabajador',$ids_trabajadores])->andWhere(['status'=>1])->all();

foreach($historias as $key=>$historia){
    if(isset($historia->status) && $historia->status != null && $historia->status != ''){
        $statushctotal[($historia->status)] = $statushctotal[($historia->status)]+1;
    } else {
        $statushctotal[0] = $statushctotal[0]+1;
    }
}

$trab_conhistoria = count($historiascompletas);
$trab_sinhistoria = count($trabajadores)-count($historiascompletas);


$nordicos = Cuestionario::find()->where(['id_tipo_cuestionario'=>1])->andWhere(['in','id_paciente',$ids_trabajadores])->all();
$nordicos_hombres = count(Cuestionario::find()->where(['id_tipo_cuestionario'=>1])->andWhere(['in','id_paciente',$hombres_activos])->all());
$nordicos_mujeres = count(Cuestionario::find()->where(['id_tipo_cuestionario'=>1])->andWhere(['in','id_paciente',$mujeres_activos])->all());
$nordicos_otros = count(Cuestionario::find()->where(['id_tipo_cuestionario'=>1])->andWhere(['in','id_paciente',$otros_activos])->all());

$antropometricas = Cuestionario::find()->where(['id_tipo_cuestionario'=>4])->andWhere(['in','id_paciente',$ids_trabajadores])->all();
$antropometricas_hombres = count(Cuestionario::find()->where(['id_tipo_cuestionario'=>4])->andWhere(['in','id_paciente',$hombres_activos])->all());
$antropometricas_mujeres = count(Cuestionario::find()->where(['id_tipo_cuestionario'=>4])->andWhere(['in','id_paciente',$mujeres_activos])->all());
$antropometricas_otros = count(Cuestionario::find()->where(['id_tipo_cuestionario'=>4])->andWhere(['in','id_paciente',$otros_activos])->all());


$totalnordicos_hombres = 0;
$totalnordicos_mujeres = 0;
$totalnordicos_otros = 0;
if(count($nordicos)>0){
    $totalnordicos_hombres = ($nordicos_hombres*100)/count($nordicos);
    $totalnordicos_mujeres = ($nordicos_mujeres*100)/count($nordicos);
    $totalnordicos_otros = ($nordicos_otros*100)/count($nordicos);
}

$totalantropometricas_hombres = 0;
$totalantropometricas_mujeres = 0;
$totalantropometricas_otros = 0;
if(count($antropometricas)>0){
    $totalantropometricas_hombres = ($antropometricas_hombres*100)/count($antropometricas);
    $totalantropometricas_mujeres = ($antropometricas_mujeres*100)/count($antropometricas);
    $totalantropometricas_otros = ($antropometricas_otros*100)/count($antropometricas);
}



$total = count($trabajadores);
$totalactivos = count($trabajadoresactivos);
$hombres = 0;
$mujeres = 0;
$otros = 0;

$total_hombres = 0;
$total_mujeres = 0;
$total_otros = 0;

$activo = 0;
$baja = 0;

$total_activo = 0;
$total_baja = 0;

$data_arrpuestos = [];


if($puestos){
    foreach($puestos as $key=>$puesto){
        $total_trabajadores = count(Trabajadores::find()->where(['id_puesto'=>$key])->andWhere(['status'=>1])->all());
        $data_arrpuestos[$key] = $total_trabajadores;
    }

}


//POES POR AÑO--------------------------------------------------------------------------INICIO
$poesanio = Poes::find()->where(['in','id_empresa',$ids_empresas])->select('anio')->distinct()->all();
$data_arrpoes = [];
$label_arrpoes = [];
$colores_arrpoes = [];

if($poes){
    foreach($poesanio as $key=>$poe){
        $total_poes = count(Poes::find()->where(['anio'=>$poe->anio])->andWhere(['in','id_empresa',$ids_empresas])->all());
        $data_arrpoes[$key] = $total_poes;
        $label_arrpoes[$key] = $poe->anio;
        array_push($colores_arrpoes, $colores[rand(1,(count($colores)-1))]);
    }
}
//POES POR AÑO--------------------------------------------------------------------------FIN



//ESTUDIOS POES-------------------------------------------------------------------------INICIO
$ids_poes = [];
foreach($poes as $key=>$poe){
    if(!in_array($poe->id, $ids_poes)){
        array_push($ids_poes, $poe->id);
    }
}
$estudiospoes = Poeestudio::find()->where(['in','id_poe',$ids_poes])->select('id_estudio')->distinct()->all();

$data_arrestudios = [];
$label_arrestudios = [];
$colores_arrestudios = [];

if($poes){
    foreach($estudiospoes as $key=>$poe){
        $est = Servicios::find()->where(['id'=>$poe->id_estudio])->one();
        $total_estudios = count(Poeestudio::find()->where(['id_estudio'=>$poe->id_estudio])->andWhere(['in','id_poe',$ids_poes])->all());
        $data_arrestudios[$key] = $total_estudios;
        $label_arrestudios[$key] = $est->nombre;
        array_push($colores_arrestudios, $colores[rand(1,(count($colores)-1))]);
    }
}
//ESTUDIOS POES-------------------------------------------------------------------------FIN


//INCAPACIDADES--------------------------------------------------------------------------INICIO
date_default_timezone_set('America/Monterrey'); 
$hoy = date('Y-m-d');
$incapacidades = count(Consultas::find()->where(['in','id_empresa',$ids_empresas])->andWhere(['tipo'=>4])->all());
$incapacidadesactivas = count(Consultas::find()->where(['in','id_empresa',$ids_empresas])->andWhere(['tipo'=>4])->andWhere(['>=','incapacidad_fechafin',$hoy])->all());
$incapacidadesvencidas = count(Consultas::find()->where(['in','id_empresa',$ids_empresas])->andWhere(['tipo'=>4])->andWhere(['<','incapacidad_fechafin',$hoy])->all());

$tipoexameninca_label = ['IMSS','INTERNA','PARTICULAR'];
$tipoexameninca_data = [0,0,0];
$tipoexameninca_color = ['#FFFFFF','#FFFFFF','#FFFFFF'];

$riesgostiposinca_label = ['Maternidad','Enfermedad General','Riesgo del Trabajo'];
$riesgostiposinca_data = [
    0,
    0,
    0
];
$riesgostiposinca_color = [
    '#FFFFFF',
    '#FFFFFF',
    '#FFFFFF'
];

foreach($tipoexameninca_label as $key=>$tipo){
    $totalincas = count(Consultas::find()->where(['in','id_empresa',$ids_empresas])->andWhere(['incapacidad_tipo'=>($key+1)])->andWhere(['tipo'=>4])->all());
    $tipoexameninca_data[$key] = $totalincas;
    $tipoexameninca_color[$key] = $colores[$key];
}


foreach($riesgostiposinca_label as $key=>$ramo){
    $totalincas = count(Consultas::find()->where(['in','id_empresa',$ids_empresas])->andWhere(['incapacidad_ramo'=>($key+1)])->andWhere(['tipo'=>4])->all());
    if($incapacidades>0){
        $riesgostiposinca_data[$key] = ($totalincas*100)/$incapacidades;
    } else{
        $riesgostiposinca_data[$key] = 0;
    }
    $riesgostiposinca_label[$key] = $riesgostiposinca_label[$key].' '.$totalincas;
    $riesgostiposinca_color[$key] = $colores[$key];
}
//INCAPACIDADES--------------------------------------------------------------------------FIN


arsort($data_arrpuestos);
$inicio = 0; 
$fin = 10;
$lbl_puestos = [];
$colores_puestos = [];
$data_puestos = [];

foreach($data_arrpuestos as $puesto=>$cantidad){
    if($inicio < $fin){
        $puesto = Puestostrabajo::find()->where(['id'=>$puesto])->one();
        array_push($lbl_puestos, $puesto->nombre);
        $total_trabajadores = count(Trabajadores::find()->where(['id_puesto'=>$puesto->id])->andWhere(['status'=>1])->all());
        array_push($data_puestos, $total_trabajadores);
        array_push($colores_puestos, $colores[rand(1,(count($colores)-1))]);
    }
    $inicio++;
}


if($trabajadores){
    $total_hombres = count(Trabajadores::find()->where(['in','id_empresa',$ids_empresas])->andWhere(['sexo'=>1])->andWhere(['status'=>1])->all());
    $total_mujeres = count(Trabajadores::find()->where(['in','id_empresa',$ids_empresas])->andWhere(['sexo'=>2])->andWhere(['status'=>1])->all());
    $total_otros = count(Trabajadores::find()->where(['in','id_empresa',$ids_empresas])->andWhere(['sexo'=>3])->andWhere(['status'=>1])->all());

    $total_activo = count(Trabajadores::find()->where(['in','id_empresa',$ids_empresas])->andWhere(['status'=>1])->all());
    $total_baja = count(Trabajadores::find()->where(['in','id_empresa',$ids_empresas])->andWhere(['status'=>2])->all());
    
    $hombres = ($total_hombres*100)/$totalactivos;
    $mujeres = ($total_mujeres*100)/$totalactivos;
    $otros = ($total_otros*100)/$totalactivos;

    $activo = ($total_activo*100)/$total;
    $baja = ($total_baja*100)/$total;
} */
?>

<?php


/* $limite_usuarios = 5;
$total_utilizado = 0;
$total_disponible = 5;
$utilizados = 0;
$disponibles = 100;

$administradores = 1;
$medicos = 1;
$medicoslaborales = 1;

$porcen_administradores = 0;
$porcen_medicos = 0;
$porcen_medicoslaborales = 0;

$utilizados_administradores = 0;
$utilizados_medicos = 0;
$utilizados_medicoslaborales = 0;

$class_disponible = 'bgcolor7';
$class_disponible1 = 'bg-info';
$class_disponible2 = 'bg-info';
$class_disponible3 = 'bg-info';

if(Yii::$app->user->identity->empresa && Yii::$app->user->identity->empresa->configuracion){
    $limite_usuarios = Yii::$app->user->identity->empresa->configuracion->cantidad_usuarios;
    $total_disponible = $limite_usuarios;

    if(isset(Yii::$app->user->identity->empresa->configuracion->cantidad_administradores)){
        $administradores = Yii::$app->user->identity->empresa->configuracion->cantidad_administradores;
    }
    if(isset(Yii::$app->user->identity->empresa->configuracion->cantidad_medicos)){
        $medicos = Yii::$app->user->identity->empresa->configuracion->cantidad_medicos;
    }
    if(isset(Yii::$app->user->identity->empresa->configuracion->cantidad_medicoslaborales)){
        $medicoslaborales = Yii::$app->user->identity->empresa->configuracion->cantidad_medicoslaborales;
    }

    if(Yii::$app->user->identity->empresa->administradores){
        $utilizados_administradores = count(Yii::$app->user->identity->empresa->administradores);
        
        if($administradores > 0){
            $porcen_administradores = ($utilizados_administradores*100)/$administradores;
        }
    }
    if($porcen_administradores >80){
        $class_disponible1 = 'bg-danger'; 
    } else if($porcen_administradores> 49){
        $class_disponible1 = 'bg-warning'; 
    }


    if(Yii::$app->user->identity->empresa->medicos){
        $utilizados_medicos = count(Yii::$app->user->identity->empresa->medicos);

        if($medicos > 0){
            $porcen_medicos = ($utilizados_medicos*100)/$medicos;
        }
    }
    if($porcen_medicos >80){
        $class_disponible2 = 'bg-danger'; 
    } else if($porcen_administradores> 49){
        $class_disponible2 = 'bg-warning'; 
    }

    if(Yii::$app->user->identity->empresa->firmasactivas){
        $utilizados_medicoslaborales = count(Yii::$app->user->identity->empresa->firmasactivas);

        if($medicoslaborales > 0){
            $porcen_medicoslaborales = ($utilizados_medicoslaborales*100)/$medicoslaborales;
        }
    }
    if($porcen_medicoslaborales >80){
        $class_disponible3 = 'bg-danger'; 
    } else if($porcen_administradores> 49){
        $class_disponible3 = 'bg-warning'; 
    }
    
}

if(Yii::$app->user->identity->empresa){
    $empresa = Yii::$app->user->identity->empresa;
    if($empresa->usuariosactivos){
        $total_utilizado = count($empresa->usuariosactivos); 
        $total_disponible = $total_disponible-$total_utilizado;
    }
}

if($total_disponible <1){
    $class_disponible = 'bgnocumple';
}

if($limite_usuarios > 0){
    $utilizados = ($total_utilizado * 100)/$limite_usuarios; 
    $disponibles = ($total_disponible * 100)/$limite_usuarios; 
} */
?>
<div class="site-index">

    <div class="body-content container-fluid">

        <div class="row my-2">
            <h1 class="title1 text-center">Bienvenido <?php echo $name_user.$logo;?></h1>
        </div>

    </div>
</div>