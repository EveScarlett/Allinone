<?php

use yii\helpers\Html;
use yii\widgets\DetailView;




use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\Riesgos;
use app\models\Empresas;
use app\models\Trabajadores;
use app\models\Areas;
use app\models\Puestostrabajo;
use kartik\date\DatePicker;
use unclead\multipleinput\MultipleInput;
use app\models\ProgramaSalud;
use kartik\file\FileInput;
use yii\helpers\Url;
use app\models\TipoServicios;
use app\models\Servicios;

use app\models\Vigencias;
use app\models\Vacunacion;
use app\models\Programasaludempresa;

/** @var yii\web\View $this */
/** @var app\models\HccOhc $model */

?>

<?php
$tipoexamen = ['1'=>'NUEVO INGRESO','2'=>'PRIMERA VEZ','3'=>'PERIODICO','4'=>'PREEXISTENTE','5'=>'SALIDA'];

//AHF -> ANTECEDENTES HEREDO FAMILIARES
$AHF_diabetes = '';
$AHF_hipertension = '';
$AHF_cancer = '';
$AHF_nefropatias = '';
$AHF_cardiopatias = '';
$AHF_enfreumaticas = '';
$AHF_hepaticos = '';
$AHF_tuberculosis = '';
$AHF_psiquiatricos = '';

//APNP -> ANTECEDENTES PERSONALES NO PATOLOGICOS
$APNP_tabaquismofrec = '';
$APNP_tabaquismocant = '';
$APNP_alcoholismofrec = '';
$APNP_alcoholismocant = '';
$APNP_audifonosfrec = '';
$APNP_audifonoscant = '';
$APNP_drogadicciontipo = '';
$APNP_drogadiccionfecha = '';
$APNP_vacunacionnombre = '';
$APNP_vacunacionnum = '';
$APNP_mano = '';

//AGO -> ANTECEDENTES GINECO OBSTETRICOS
$AGO_mpf = '';
$AGO_doc = '';
$AGO_docma = '';

//EXP -> EXPLORACION FISICA
$EXP_inspecciong = '';
$EXP_cabeza = '';
$EXP_oidos = '';
$EXP_ojoscara = '';
$EXP_boca = '';
$EXP_cuello = '';
$EXP_torax = '';
$EXP_abdomen = '';
$EXP_miembrossup = '';
$EXP_miembrosinf = '';
$EXP_columna = '';
$EXP_neurologicos = '';

//EXPLAB -> EXPERIENCIA LABORAL
$EXPLAB_antiguedad = '';
$EXPLAB_0tiempoexposicion = '';
$EXPLAB_0exposicion = '';
$EXPLAB_1tiempoexposicion = '';
$EXPLAB_1exposicion = '';
$EXPLAB_2tiempoexposicion = '';
$EXPLAB_2exposicion = '';
$EXPLAB_3tiempoexposicion = '';
$EXPLAB_3exposicion = '';

$enf_ocular = '';
$enf_auditiva = '';
$fractura ='';
$amputacion ='';
$hernias ='';
$enf_sanguinea ='';
$tumores ='';
$enf_psicolog ='';
$fecha_laborala ='';
$fecha_laborala_2 ='';

$enf_ocular = $model->enf_ocular_txt;
$enf_auditiva = $model->enf_auditiva_txt;
$fractura = $model->fractura_txt;
$amputacion = $model->amputacion_txt;
$hernias = $model->hernias_txt;
$enf_sanguinea = $model->enfsanguinea_txt;
$tumores = $model->tumorescancer_txt;
$enf_psicolog = $model->enfpsico_txt;
$fecha_laboral1 = '';
$fecha_laboral1_2 = '';

$array_conclusion = ['0'=>'PENDIENTE','1'=>'BUENO','2'=>'REGULAR','3'=>'MALO'];
$array_grupo = ['1'=>'A','2'=>'B','3'=>'AB','4'=>'O','5'=>'PENDIENTE'];
$array_rh = ['1'=>'POSITIVO (+)','2'=>'NEGATIVO (-)','3'=>'PENDIENTE'];
$array_lectura = ['1'=>'Bueno','2'=>'Regular','3'=>'Malo'];
$array_antlaboral =  [''=>'Seleccione','1'=>'1 Mes','2'=>'2 Meses','3'=>'3 Meses','4'=>'4 Meses','5'=>'5 Meses','6'=>'6 Meses','7'=>'7 Meses','8'=>'8 Meses','9'=>'9 Meses','10'=>'10 Meses','11'=>'11 Meses','12'=>'1 AÑO','13'=>'1 AÑO Y MEDIO','14'=>'2 AÑOS','15'=>'+ DE 2 AÑOS'];
$array_familiares = ['1'=>'PADRE','2'=>'MADRE','3'=>'ABUELOS','4'=>'HERMANOS'];
$array_frecuencia = ['1'=>'DIARIO','2'=>'SEMANAL','3'=>'QUINCENAL','4'=>'MENSUAL','5'=>'OCASIONAL'];
$array_tabacocant = ['1'=>'1 CIGARRO','2'=>'2 A 4 CIGARROS','3'=>'MÁS DE 5 CIGARROS'];
$array_alcoholcant = ['1'=>'1 COPA','2'=>'2 - 5 COPAS','3'=>'MÁS DE 5 COPAS'];
$array_audifonotime = ['1'=>'30 MINUTOS','2'=>'1 HORA','3'=>'2 HORAS','4'=>'3 HORAS','5'=>'4 HORAS','6'=>'5 HORAS','7'=>'6 HORAS','8'=>'7 HORAS','9'=>'8 HORAS','10'=>'MÁS DE 8 HORAS'];
$array_drogas = ['1'=>'ANFETAMINAS','2'=>'META-ANFETAMINAS','3'=>'MARIHUANA','4'=>'OPIACEOS','5'=>'BENZODIACEPINAS','6'=>'BARBITURICOS','7'=>'COCAINA','8'=>'MORFINA','9'=>'BENCICLIDINA'];
$array_drogasultimo = ['1'=>'HOY','2'=>'MENOR A UNA SEMANA','3'=>'MENOR A UN MES','4'=>'MENOR A 6 MESES','5'=>'MAYOR A 6 MESES'];
$array_vacuna = ['1'=>'PFIZER/BIONTECH','2'=>'MODERNA','3'=>'ASTRAZENECA','4'=>'JANSSEN','5'=>'SINOPHARM','6'=>'SINOVAC','7'=>'BHARAT','8'=>'CANSINO','9'=>'VALNEVA','10'=>'NOVAVAX','11'=>'SPUTNIK V','12'=>'OTRA'];
$array_vacunadosis = ['1' => 'PRIMERA', '2' => 'SEGUNDA', '3' => 'TERCERA', '4'=>'UNICA', '5'=>'REFUERZO'];
$array_alimentacion = ['1' => 'BUENA EN CALIDAD Y CANTIDAD', '2' => 'REGULAR EN CALIDAD Y CANTIDAD', '3' => 'MALA EN CALIDAD Y CANTIDAD'];
$array_vivienda = ['1'=>'CEMENTO','2'=>'LADRILLO','3'=>'TEJA','4'=>'LAMINA','5'=>'VITROPISO','6'=>'TIERRA'];
$array_servicios = ['1'=>'AGUA','2'=>'LUZ','3'=>'DRENAJE','4'=>'FOSA SÉPTICA','5'=>'PORTÁTIL'];
$array_frecuencia2 = ['1'=>'DIARIO','2'=>'CADA 2 DÍAS','3'=>'2 VECES POR SEMANA','4'=>'UNA VEZ POR SEMANA','5'=>'NO SE REALIZA'];
$array_horasuenio =  ['1'=>'MENOS DE 6 HORAS DIARIAS','2'=>'ENTRE 6 Y 8 HORAS DIARIAS','3'=>'MÁS DE 8 HORAS DIARIAS'];
$array_mpf = ['0'=>'CONDÓN FEMENINO','1'=>'CONDÓN MASCULINO','2'=>'PASTILLAS ANTICONCEPTIVAS','3'=>'PARCHES ANTICONCEPTIVOS','4'=>'IMPLANTE SUDÉRMICO','5'=>'INYECCIONES ANTICONCEPTIVAS','6'=>'PASTILLA ANTICONCEPCIÓN DE EMERGENCIA','7'=>'DISPOSITIVO INTRAUTERINO','8'=>'ANILLO VAGINAL','9'=>'MÉTODOS PERMANENTES','10'=>'SISTEMA INTRAUTERINO (SIU)','11'=>'NINGUNO'];
$array_doc = ['1'=>'NUNCA','2'=>'MENOS DE 1 AÑO','3'=>'MENOS DE 2 AÑOS','4'=>'MÁS DE 2 AÑOS'];
$array_inspeccion = ['1'=>'ORIENTADO','2'=>'DESORIENTADO','3'=>'HIDRATADO','4'=>'DESHIDRATADO','5'=>'BUENA COLORACIÓN','6'=>'PALIDEZ','7'=>'ICTERICIA','8'=>'MARCHA ANORMAL','9'=>'MARCHA NORMAL','10'=>'SIN DATOS PATOLÓGICOS'];
$array_cabeza = ['1' => 'NORMOCÉFALO', '2' => 'ALOPECIA', '3' => 'CABELLO BIEN IMPLANTADO', '4' => 'SIN DATOS PATOLÓGICOS'];
$array_oidos = ['1' => 'INTEGRA', '2' => 'SIMÉTRICOS', '3' => 'SIN DOLOR A LA PALPACIÓN', '4' => 'MEMBRANA TIMPÁNICA SIN ALTERACIONES', '5' => 'MALFORMACIÓN CONGÉNITA', '6' => 'MEMBRANA TIMPÁNICA ANORMAL', '7' => 'ADENOPATÍA PREARICULAR PALPABLE', '8' => 'SIN DATOS PATOLÓGICOS'];
$array_ojos = ['1'=>'ÍNTEGROS','2'=>'PRÓTESIS','3'=>'PUPILAS ISOCÓRICAS','4'=>'PUPILAS ANISOCÓRICAS','5'=>'FOSAS PERMEABLES','6'=>'FOSAS OBSTRUIDAS','7'=>'ADENOMEGALIAS RETROAURICULARES','8'=>'ADENOMEGALIAS SUBMANDIBULARES','9'=>'NO PALPABLES','10'=>'SIN DATOS PATOLÓGICOS'];
$array_boca = ['1'=>'NORMAL','2'=>'HIPEREMIA','3'=>'AMÍGDALAS NORMALES','4'=>'AMÍGDALAS HIPERTRÓFICAS','5'=>'SIN DATOS PATOLÓGICOS','6'=>'EXUDADO PURULENTO'];
$array_cuello = ['1'=>'TRAQUEA CENTRAL','2'=>'CILÍNDRICO','3'=>'CRECIMIENTO TIROIDEO','4'=>'ADENOMEGALIAS RETROAURICULARES','5'=>'ADENOMEGALIAS SUBMANDIBULARES','7'=>'SIN DATOS PATOLÓGICOS','6'=>'NO PALPABLES'];
$array_torax = ['1'=>'CAMPOS PULMONARES VENTILADOS','2'=>'ESTERTORES','3'=>'SIBILANCIAS','4'=>'RUIDOS CARDIACOS RÍTMICOS','5'=>'ARRITMIA','6'=>'ADENOMEGALIAS AXILARES','7'=>'NORMOLÍNEO','8'=>'SIN DATOS PATOLÓGICOS'];
$array_abdomen = ['1'=>'GLOBOSO','2'=>'PLANO','3'=>'BLANDO Y DEPRESIBLE','4'=>'ABDOMEN EN MADERA','5'=>'DOLOR A LA PALPACIÓN','6'=>'VISCEROMEGALIAS','7'=>'RESISTENCIA','8'=>'HEPATOMEGALIA','9'=>'ESPLENOMEGALIA','10'=>'PERISTALSIS ALTERADA','11'=>'SIN DATOS PATOLÓGICOS'];
$array_miembrossup = ['1'=>'ÍNTEGROS','2'=>'PRÓTESIS','3'=>'SIMÉTRICOS','4'=>'PULSOS PALPABLES','5'=>'ALTERACIONES FUNCIONALES','6'=>'ALTERACIONES ESTRUCTURALES','7'=>'ALTERACIONES VASCULARES','8'=>'SIN DATOS PATOLÓGICOS'];
$array_miembrosinf = ['1'=>'ÍNTEGROS','2'=>'PRÓTESIS','3'=>'SIMÉTRICOS','4'=>'PULSOS PALPABLES','5'=>'ALTERACIONES FUNCIONALES','6'=>'ALTERACIONES ESTRUCTURALES','7'=>'ALTERACIONES VASCULARES','8'=>'SIN DATOS PATOLÓGICOS'];
$array_columna = ['1'=>'INTEGRA','2'=>'SIN LIMITACIONES FUNCIONALES','3'=>'MOVIMIENTOS MUSCULOESQUELÉTICOS LIMITADOS','4'=>'SIN DATOS PATOLÓGICOS'];
$array_neurologicos = ['1'=>'RESPUESTA VERBAL ALTERADA','2'=>'RESPUESTA MOTORA ALTERADA','3'=>'ALTERACIONES DE LA MEMORIA','4'=>'DESORIENTADO','5'=>'SIN DATOS PATOLÓGICOS'];

$array_manos = ['1' => 'IZQUIERDA', '2' => 'DERECHA', '3' => 'AMBIDIESTRO'];

$array_sexo = ['1' => 'MASCULINO', '2' => 'FEMENINO'];

$array_antiguedad = ['1'=>'1 Mes','2'=>'2 Meses','3'=>'3 Meses','4'=>'4 Meses','5'=>'5 Meses','6'=>'6 Meses','7'=>'7 Meses','8'=>'8 Meses','9'=>'9 Meses','10'=>'10 Meses','11'=>'11 Meses','12'=>'1 AÑO','13'=>'1 AÑO Y MEDIO','14'=>'2 AÑOS','15'=>'+ DE 2 AÑOS'];
$array_exposiciones = ['1' => 'Ruido', '2' => 'Polvos/Humos/Vap', '3' => 'Mov. Repetidos', '4' => 'Temperatura', '5' => 'Químicos', '6' => 'Vibraciones', '7' => 'Biológicos', '8' => 'Iluminación', '9' => 'Levantar Peso'];

$estadocivil = ['1'=>'Soltero','2'=>'Casado','3'=>'Viudo','4'=>'Unión Libre'];

$array_conclusionhc = [
    '1'=>'SANO Y APTO',
    '2'=>'REQUIERE MEJORAR SALUD, APTO PARA EL PUESTO',
    '3'=>'APTO TEMPORAL',
    '4'=>'PENDIENTE',
    '5'=>'NO APTO',
];


if($model->diabetess =='NO'){
    $AHF_diabetes = 'NO';
}else{
    $AHF_diabetes = '';
    $array = explode(',', $model->diabetesstxt);

    if(isset($model->diabetesstxt) && $model->diabetesstxt != null && $model->diabetesstxt != ''){
        foreach($array as $key=>$elemento){
            $AHF_diabetes .= $array_familiares[$elemento];  
            if($key < (count($array)-1)){
                $AHF_diabetes .= ', ';
            }
        }
    }
}

if($model->hipertension =='NO'){
    $AHF_hipertension = 'NO';
}else{
    $AHF_hipertension = '';
    $array = explode(',', $model->hipertensiontxt);

    if(isset($model->hipertensiontxt) && $model->hipertensiontxt != null && $model->hipertensiontxt != ''){
        foreach($array as $key=>$elemento){
            $AHF_hipertension .= $array_familiares[$elemento];  
            if($key < (count($array)-1)){
                $AHF_hipertension .= ', ';
            }
        }
    }
}

if($model->cancer =='NO'){
    $AHF_cancer = 'NO';
}else{
    $array = explode(',', $model->cancertxt);

    if(isset($model->cancertxt) && $model->cancertxt != null && $model->cancertxt != ''){
        foreach($array as $key=>$elemento){
            $AHF_cancer .= $array_familiares[$elemento];  
            if($key < (count($array)-1)){
                $AHF_cancer .= ', ';
            }
        }
    }
}

if($model->nefropatias =='NO'){
    $AHF_nefropatias = 'NO';
}else{
    $array = explode(',', $model->nefropatiastxt);

    if(isset($model->nefropatiastxt) && $model->nefropatiastxt != null && $model->nefropatiastxt != ''){
        foreach($array as $key=>$elemento){
            $AHF_nefropatias .= $array_familiares[$elemento];  
            if($key < (count($array)-1)){
                $AHF_nefropatias .= ', ';
            }
        }
    }
}

if($model->cardiopatias =='NO'){
    $AHF_cardiopatias = 'NO';
}else{
    $array = explode(',', $model->cardiopatiastxt);

    if(isset($model->cardiopatiastxt) && $model->cardiopatiastxt != null && $model->cardiopatiastxt != ''){
        foreach($array as $key=>$elemento){
            $AHF_cardiopatias .= $array_familiares[$elemento];  
            if($key < (count($array)-1)){
                $AHF_cardiopatias .= ', ';
            }
        }
    }
}

if($model->reuma =='NO'){
    $AHF_enfreumaticas = 'NO';
}else{
    $array = explode(',', $model->reumatxt);

    if(isset($model->reumatxt) && $model->reumatxt != null && $model->reumatxt != ''){
        foreach($array as $key=>$elemento){
            $AHF_enfreumaticas .= $array_familiares[$elemento];  
            if($key < (count($array)-1)){
                $AHF_enfreumaticas .= ', ';
            }
        }
    }
}

if($model->tuber =='NO'){
    $AHF_tuberculosis = 'NO';
}else{
    $array = explode(',', $model->tubertxt);

    if(isset($model->tubertxt) && $model->tubertxt != null && $model->tubertxt != ''){
        foreach($array as $key=>$elemento){
            $AHF_tuberculosis .= $array_familiares[$elemento];  
            if($key < (count($array)-1)){
                $AHF_tuberculosis .= ', ';
            }
        }
    }
}

if($model->hepa =='NO'){
    $AHF_hepaticos = 'NO';
}else{
    $array = explode(',', $model->hepatxt);

    if(isset($model->hepatxt) && $model->hepatxt != null && $model->hepatxt != ''){
        foreach($array as $key=>$elemento){
            $AHF_hepaticos .= $array_familiares[$elemento];  
            if($key < (count($array)-1)){
                $AHF_hepaticos .= ', ';
            }
        }
    }
}

if($model->psi =='NO'){
    $AHF_psiquiatricos = 'NO';
}else{
    $array = explode(',', $model->psitxt);

    if(isset($model->psitxt) && $model->psitxt != null && $model->psitxt != ''){
        foreach($array as $key=>$elemento){
            $AHF_psiquiatricos .= $array_familiares[$elemento];  
            if($key < (count($array)-1)){
                $AHF_psiquiatricos .= ', ';
            }
        }
    }
}

if($model->tabaquismo =='SI'){
    $APNP_tabaquismofrec = $array_frecuencia[$model->tabfrec];
    $APNP_tabaquismocant = $array_tabacocant[$model->tabcantidad]; 
}
if($model->alcoholismo =='SI'){
    $APNP_alcoholismofrec = $array_frecuencia[$model->alcofrec];
    $APNP_alcoholismocant = $array_alcoholcant[$model->alcocantidad];
}
if($model->audifonos =='SI'){
    $APNP_audifonosfrec = $array_frecuencia[$model->audiodesde];
    $APNP_audifonoscant = $array_audifonotime[$model->audiocuando];
}

if($model->droga =='NO'){
    $APNP_drogadicciontipo = 'NO';
}else{
    $array = explode(',', $model->drogatxt);

    if(isset($model->drogatxt) && $model->drogatxt != null && $model->drogatxt != ''){
        foreach($array as $key=>$elemento){
            $APNP_drogadicciontipo .= $array_drogas[$elemento];  
            if($key < (count($array)-1)){
                $APNP_drogadicciontipo .= ', ';
            }
        }
    }

    if(isset($model->fecha_droga) && $model->fecha_droga != null && $model->fecha_droga != ''){
        $APNP_drogadiccionfecha = $array_drogasultimo[$model->fecha_droga];
    }
}

if($model->vacunacion_cov =='SI'){
    if(isset($model->nombre_vacunacion) && $model->nombre_vacunacion != ''){
        $APNP_vacunacionnombre = $array_vacuna[$model->nombre_vacunacion];
    }
    if(isset($model->dosis_vacunacion) && $model->dosis_vacunacion != ''){
        $APNP_vacunacionnum = $array_vacunadosis[$model->dosis_vacunacion];
    }
}

if(isset($model->alimentacion) && $model->alimentacion!= '' && array_key_exists($model->alimentacion, $array_alimentacion)){
    $APHD_Alimentacion = $array_alimentacion[$model->alimentacion];
}

if(isset($model->vivienda) && $model->vivienda!= ''){
    $array = explode(',', $model->vivienda);

    if(isset($model->vivienda) && $model->vivienda != null && $model->vivienda != ''){
        foreach($array as $key=>$elemento){
            $APHD_Vivienda .= $array_vivienda[$elemento];  
            if($key < (count($array)-1)){
                $APHD_Vivienda .= ', ';
            }
        }
    }
}

if(isset($model->servicios) && $model->servicios!= ''){
    $array = explode(',', $model->servicios);

    if(isset($model->servicios) && $model->servicios != null && $model->servicios != ''){
        foreach($array as $key=>$elemento){
            $APHD_Servicios .= $array_servicios[$elemento];  
            if($key < (count($array)-1)){
                $APHD_Servicios .= ', ';
            }
        }
    }
}

if(isset($model->wc) && $model->wc!= '' && array_key_exists($model->wc, $array_frecuencia2)){
    $APHD_Banio = $array_frecuencia2[$model->wc];
}

if(isset($model->ropa) && $model->ropa!= '' && array_key_exists($model->ropa, $array_frecuencia2)){
    $APHD_Ropa = $array_frecuencia2[$model->ropa];
}

if(isset($model->bucal) && $model->bucal!= '' && array_key_exists($model->bucal, $array_frecuencia2)){
    $APHD_Bucal = $array_frecuencia2[$model->bucal];
}

if(isset($model->deporte) && $model->deporte!= '' && array_key_exists($model->deporte, $array_frecuencia2)){
    $APHD_Deporte = $array_frecuencia2[$model->deporte];
}

if(isset($model->recreativa) && $model->recreativa!= '' && array_key_exists($model->recreativa, $array_frecuencia2)){
    $APHD_Actividad = $array_frecuencia2[$model->recreativa];
}

if(isset($model->horas) && $model->horas!= '' && array_key_exists($model->horas, $array_horasuenio)){
    $APHD_Suenio = $array_horasuenio[$model->horas];
}

if(isset($model->mpf) && $model->mpf!= '' && array_key_exists($model->mpf, $array_mpf)){
    $AGO_mpf = $array_mpf[$model->mpf];
}

if(isset($model->doc) && $model->doc!= '' && array_key_exists($model->doc, $array_doc)){
    $AGO_doc = $array_doc[$model->doc];
}

if(isset($model->docma) && $model->docma!= '' && array_key_exists($model->docma, $array_doc)){
    $AGO_docma = $array_doc[$model->docma];
}

if(isset($model->mano) && $model->mano!= '' && array_key_exists($model->mano, $array_manos)){
    $APNP_mano = $array_manos[$model->mano];
}


if(isset($model->inspeccion) && $model->inspeccion != ''){
    $array = explode(',', $model->inspeccion);

    if(isset($model->inspeccion) && $model->inspeccion != null && $model->inspeccion != ''){
        foreach($array as $key=>$elemento){
            $EXP_inspecciong .= $array_inspeccion[$elemento];  
            if($key < (count($array)-1)){
                $EXP_inspecciong .= ', ';
            }
        }
    }
}
if($model->inspeccion_otros == 1){
    $EXP_inspecciong .= ', '.$model->txt_inspeccion_otros; 
}


if(isset($model->cabeza) && $model->cabeza != ''){
    $array = explode(',', $model->cabeza);

    if(isset($model->cabeza) && $model->cabeza != null && $model->cabeza != ''){
        foreach($array as $key=>$elemento){
            $EXP_cabeza .= $array_cabeza[$elemento];  
            if($key < (count($array)-1)){
                $EXP_cabeza .= ', ';
            }
        }
    }
}
if($model->cabeza_otros == 1){
    $EXP_cabeza .= ', '.$model->txt_cabeza_otros; 
}


if(isset($model->oidos) && $model->oidos != ''){
    $array = explode(',', $model->oidos);

    if(isset($model->oidos) && $model->oidos != null && $model->oidos != ''){
        foreach($array as $key=>$elemento){
            $EXP_oidos .= $array_oidos[$elemento];  
            if($key < (count($array)-1)){
                $EXP_oidos .= ', ';
            }
        }
    }
}
if($model->oidos_otros == 1){
    $EXP_oidos .= ', '.$model->txt_oidos_otros; 
}


if(isset($model->ojos) && $model->ojos != ''){
    $array = explode(',', $model->ojos);

    if(isset($model->ojos) && $model->ojos != null && $model->ojos != ''){
        foreach($array as $key=>$elemento){
            $EXP_ojoscara .= $array_ojos[$elemento];  
            if($key < (count($array)-1)){
                $EXP_ojoscara .= ', ';
            }
        }
    }
}
if($model->ojos_otros == 1){
    $EXP_ojoscara .= ', '.$model->txt_ojos_otros; 
}


if(isset($model->boca) && $model->boca != ''){
    $array = explode(',', $model->boca);

    if(isset($model->boca) && $model->boca != null && $model->boca != ''){
        foreach($array as $key=>$elemento){
            $EXP_boca .= $array_boca[$elemento];  
            if($key < (count($array)-1)){
                $EXP_boca .= ', ';
            }
        }
    }
}
if($model->boca_otros == 1){
    $EXP_boca .= ', '.$model->txt_boca_otros; 
}


if(isset($model->cuello) && $model->cuello != ''){
    $array = explode(',', $model->cuello);

    if(isset($model->cuello) && $model->cuello != null && $model->cuello != ''){
        foreach($array as $key=>$elemento){
            $EXP_cuello .= $array_cuello[$elemento];  
            if($key < (count($array)-1)){
                $EXP_cuello .= ', ';
            }
        }
    } 
}
if($model->cuello_otros == 1){
    $EXP_cuello .= ', '.$model->txt_cuello_otros; 
}


if(isset($model->torax) && $model->torax != ''){
    $array = explode(',', $model->torax);

    if(isset($model->torax) && $model->torax != null && $model->torax != ''){
        foreach($array as $key=>$elemento){
            $EXP_torax .= $array_torax[$elemento];  
            if($key < (count($array)-1)){
                $EXP_torax .= ', ';
            }
        }
    }
}
if($model->torax_otros == 1){
    $EXP_torax .= ', '.$model->txt_torax_otros; 
}


if(isset($model->abdomen) && $model->abdomen != ''){
    $array = explode(',', $model->abdomen);

    if(isset($model->abdomen) && $model->abdomen != null && $model->abdomen != ''){
        foreach($array as $key=>$elemento){
            $EXP_abdomen .= $array_abdomen[$elemento];  
            if($key < (count($array)-1)){
                $EXP_abdomen .= ', ';
            }
        }
    }
}
if($model->abdomen_otros == 1){
    $EXP_abdomen .= ', '.$model->txt_abdomen_otros; 
}


if(isset($model->superior) && $model->superior != ''){
    $array = explode(',', $model->superior);

    if(isset($model->superior) && $model->superior != null && $model->superior != ''){
        foreach($array as $key=>$elemento){
            $EXP_miembrossup .= $array_miembrossup[$elemento];  
            if($key < (count($array)-1)){
                $EXP_miembrossup .= ', ';
            }
        }
    } 
}
if($model->miembrossup_otros == 1){
    $EXP_miembrossup .= ', '.$model->txt_miembrossup_otros; 
}


if(isset($model->inferior) && $model->inferior != ''){
    $array = explode(',', $model->inferior);

    if(isset($model->inferior) && $model->inferior != null && $model->inferior != ''){
        foreach($array as $key=>$elemento){
            $EXP_miembrosinf .= $array_miembrosinf[$elemento];  
            if($key < (count($array)-1)){
                $EXP_miembrosinf .= ', ';
            }
        }
    }
}
if($model->miembrosinf_otros == 1){
    $EXP_miembrosinf .= ', '.$model->txt_miembrosinf_otros; 
}


if(isset($model->columna) && $model->columna != ''){
    $array = explode(',', $model->columna);

    if(isset($model->columna) && $model->columna != null && $model->columna != ''){
        foreach($array as $key=>$elemento){
            $EXP_columna .= $array_columna[$elemento];  
            if($key < (count($array)-1)){
                $EXP_columna .= ', ';
            }
        }
    }
}
if($model->columna_otros == 1){
    $EXP_columna .= ', '.$model->txt_columna_otros; 
}


if(isset($model->txtneurologicos) && $model->txtneurologicos != ''){
    $array = explode(',', $model->txtneurologicos);

    if(isset($model->txtneurologicos) && $model->txtneurologicos != null && $model->txtneurologicos != ''){
        foreach($array as $key=>$elemento){
            $EXP_neurologicos .= $array_neurologicos[$elemento];  
            if($key < (count($array)-1)){
                $EXP_neurologicos .= ', ';
            }
        }
    }
}
if($model->neurologicos_otros == '1'){
    $EXP_neurologicos .= ', '.$model->txt_neurologicos_otros; 
}


if(isset($model->antlaboral_antiguedad) && $model->antlaboral_antiguedad!= '' && array_key_exists($model->antlaboral_antiguedad, $array_antiguedad)){
    $EXPLAB_antiguedad = $array_antiguedad[$model->antlaboral_antiguedad];
}

if(isset($model->laboral0_tiempoexposicion) && $model->laboral0_tiempoexposicion!= '' && array_key_exists($model->laboral0_tiempoexposicion, $array_antiguedad)){
    $EXPLAB_0tiempoexposicion = $array_antiguedad[$model->laboral0_tiempoexposicion];
}

if(isset($model->laboral1_tiempoexposicion) && $model->laboral1_tiempoexposicion!= '' && array_key_exists($model->laboral1_tiempoexposicion, $array_antiguedad)){
    $EXPLAB_1tiempoexposicion = $array_antiguedad[$model->laboral1_tiempoexposicion];
}

if(isset($model->laboral2_tiempoexposicion) && $model->laboral2_tiempoexposicion!= '' && array_key_exists($model->laboral2_tiempoexposicion, $array_antiguedad)){
    $EXPLAB_2tiempoexposicion = $array_antiguedad[$model->laboral2_tiempoexposicion];
}

if(isset($model->laboral3_tiempoexposicion) && $model->laboral3_tiempoexposicion!= '' && array_key_exists($model->laboral3_tiempoexposicion, $array_antiguedad)){
    $EXPLAB_3tiempoexposicion = $array_antiguedad[$model->laboral3_tiempoexposicion];
}

if(isset($model->laboral0_exposicion) && $model->laboral0_exposicion != ''){
    $array = explode(',', $model->laboral0_exposicion);

    if(isset($model->laboral0_exposicion) && $model->laboral0_exposicion != null && $model->laboral0_exposicion != ''){
        foreach($array as $key=>$elemento){
            $EXPLAB_0exposicion .= $array_exposiciones[$elemento];  
            if($key < (count($array)-1)){
                $EXPLAB_0exposicion .= ', ';
            }
        }
    }
}

if(isset($model->laboral1_exposicion) && $model->laboral1_exposicion != ''){
    $array = explode(',', $model->laboral1_exposicion);

    if(isset($model->laboral1_exposicion) && $model->laboral1_exposicion != null && $model->laboral1_exposicion != ''){
        foreach($array as $key=>$elemento){
            $EXPLAB_1exposicion .= $array_exposiciones[$elemento];  
            if($key < (count($array)-1)){
                $EXPLAB_1exposicion .= ', ';
            }
        }
    }
}

if(isset($model->laboral2_exposicion) && $model->laboral2_exposicion != ''){
    $array = explode(',', $model->laboral2_exposicion);

    if(isset($model->laboral2_exposicion) && $model->laboral2_exposicion != null && $model->laboral2_exposicion != ''){
        foreach($array as $key=>$elemento){
            $EXPLAB_2exposicion .= $array_exposiciones[$elemento];  
            if($key < (count($array)-1)){
                $EXPLAB_2exposicion .= ', ';
            }
        }
    }
}

if(isset($model->laboral3_exposicion) && $model->laboral3_exposicion != ''){
    $array = explode(',', $model->laboral3_exposicion);

    if(isset($model->laboral3_exposicion) && $model->laboral3_exposicion != null && $model->laboral3_exposicion != ''){
        foreach($array as $key=>$elemento){
            $EXPLAB_3exposicion .= $array_exposiciones[$elemento];  
            if($key < (count($array)-1)){
                $EXPLAB_3exposicion .= ', ';
            }
        }
    }
}
?>
<div class="hcc-ohc-view">

    <div class="container-fluid">

        <?php
$showempresa = 'block';
$empresas = explode(',', Yii::$app->user->identity->empresas_select);

if(Yii::$app->user->identity->empresa_all != 1){
    if(count($empresas) == 1){
        $showempresa = 'none';
    }
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
$areas = ArrayHelper::map(Areas::find()->where(['id_empresa'=>$model->id_empresa])->orderBy('area')->all(), 'id', 'area');
$puestos = ArrayHelper::map(Puestostrabajo::find()->where(['id_empresa'=>$model->id_empresa])->orderBy('nombre')->all(), 'id', 'nombre');
$puestos[0] = 'NUEVO PUESTO';
$programas = [];

if(isset($model->id_empresa)){
    $empresa = Empresas::find()->where(['id'=>$model->id_empresa])->one();

    //dd($empresa);
    if($empresa){
        $p_e = Programasaludempresa::find()->where(['id_empresa'=>$model->id_empresa])->all();
        foreach($p_e as $key=>$pe){
            if($pe->programa){
                $programas[$pe->programa->id] = $pe->programa->nombre;
            }
        }
        //dd($programas);
        //$programas = ArrayHelper::map($empresa->programas, 'id', 'nombre');
    }
}

$trabajadores = ArrayHelper::map(Trabajadores::find()->where(['id_empresa'=>$model->id_empresa])->orderBy('nombre')->all(), 'id', function($data){
    return $data['nombre'].' '.$data['apellidos'];
});
$tipoexamen = ['1'=>'NUEVO INGRESO','2'=>'PRIMERA VEZ','3'=>'PERIODICO','4'=>'PREEXISTENTE','5'=>'SALIDA'];

$tipos = ArrayHelper::map(TipoServicios::find()->where(['status'=>1])->orderBy('nombre')->all(), 'id', 'nombre');
$estudios = ArrayHelper::map(Servicios::find()->where(['status'=>1])->orderBy('nombre')->all(), 'id', 'nombre');
$estudios[0] ='NUEVO ESTUDIO';

$vigencias = ArrayHelper::map(Vigencias::find()->orderBy('orden')->all(), 'id', 'vigencia');

$array_vacunas = ArrayHelper::map(Vacunacion::find()->orderBy('vacuna')->all(), 'id', 'vacuna');
$array_vacunas[0] ='NUEVA VACUNA';
?>

        <div class="hcc-ohc-form">

            <?php $form = ActiveForm::begin(['id'=>'formOHC',
'options' => ['enctype' => 'multipart/form-data'],]); ?>

            <div class="row">
                <div class="col-lg-4" style="display:none;">
                    <?= $form->field($model, 'envia_form')->textInput(['maxlength' => true,'type'=>'hidden']) ?>
                </div>
            </div>

            <div class="row my-3">
                <div class="col-lg-6" style="display:<?php echo $showempresa?>;">
                    <label class="control-label"><?= $model->getAttributeLabel('id_empresa');?></label>
                    <div class="form-control bg-disabled ">
                        <?php
                        $ret = '&nbsp;';
                        if($model->empresa){
                            $ret .= $model->empresa->comercial;
                        }
                        echo $ret;?>
                    </div>
                </div>
                <div class="col-lg-3">
                    <label class="control-label"><?= $model->getAttributeLabel('folio');?></label>
                    <div class="form-control bg-disabled ">
                        <?php
                        $ret = '&nbsp;';
                        if($model->folio){
                            $ret .= $model->folio;
                        }
                        echo $ret;?>
                    </div>
                </div>
                <div class="col-lg-3">
                    <label class="control-label"><?= $model->getAttributeLabel('fecha');?></label>
                    <div class="form-control bg-disabled ">
                        <?php
                        $ret = '&nbsp;';
                        if($model->fecha){
                            $ret .= $model->fecha;
                        }
                        echo $ret;?>
                    </div>
                </div>
            </div>

            <div class="row my-3">
                <div class="col-lg-6">
                    <label class="control-label"><?= $model->getAttributeLabel('id_trabajador');?></label>
                    <div class="form-control bg-disabled ">
                        <?php
                        $ret = '&nbsp;';
                        if($model->trabajador){
                            $ret .= $model->trabajador->nombre.' '.$model->trabajador->apellidos;
                        }
                        echo $ret;?>
                    </div>
                </div>
                <div class="col-lg-4">
                    <label class="control-label"><?= $model->getAttributeLabel('examen');?></label>
                    <div class="form-control bg-disabled ">
                        <?php
                        $ret = '&nbsp;';
                        if($model->examen){//aqui estoy
                            $ret = $tipoexamen[$model->examen];
                        }
                        echo $ret;?>
                    </div>
                </div>
            </div>

            <div class="row my-3">
                <div class="col-lg-3">
                    <label class="control-label"><?= $model->getAttributeLabel('nombre');?></label>
                    <div class="form-control bg-disabled ">
                        <?php
                        $ret = '&nbsp;';
                        if($model->nombre){
                            $ret .= $model->nombre;
                        }
                        echo $ret;?>
                    </div>
                </div>
                <div class="col-lg-3">
                    <label class="control-label"><?= $model->getAttributeLabel('apellidos');?></label>
                    <div class="form-control bg-disabled ">
                        <?php
                        $ret = '&nbsp;';
                        if($model->apellidos){
                            $ret .= $model->apellidos;
                        }
                        echo $ret;?>
                    </div>
                </div>

                <div class="col-lg-2">
                    <label class="control-label"><?= $model->getAttributeLabel('sexo');?></label>
                    <div class="form-control bg-disabled ">
                        <?php
                        $ret = '&nbsp;';
                        if($model->sexo != null && $model->sexo != '' && $model->sexo != ' '){
                            $ret .= $array_sexo[$model->sexo];
                        }
                        echo $ret;?>
                    </div>
                </div>

                <div class="col-lg-2">
                    <label class="control-label"><?= $model->getAttributeLabel('fecha_nacimiento');?></label>
                    <div class="form-control bg-disabled ">
                        <?php
                        $ret = '&nbsp;';
                        if($model->fecha_nacimiento){
                            $ret .= $model->fecha_nacimiento;
                        }
                        echo $ret;?>
                    </div>
                </div>
                <div class="col-lg-1">
                    <label class="control-label"><?= $model->getAttributeLabel('edad');?></label>
                    <div class="form-control bg-disabled ">
                        <?php
                        $ret = '&nbsp;';
                        if($model->edad){
                            $ret .= $model->edad;
                        }
                        echo $ret;?>
                    </div>
                </div>
            </div>

            <div class="row my-3">
                <div class="col-lg-2">
                    <label class="control-label"><?= $model->getAttributeLabel('num_trabajador');?></label>
                    <div class="form-control bg-disabled ">
                        <?php
                        $ret = '&nbsp;';
                        if($model->num_trabajador){
                            $ret .= $model->num_trabajador;
                        }
                        echo $ret;?>
                    </div>
                </div>
                <div class="col-lg-3">
                    <label class="control-label"><?= $model->getAttributeLabel('area');?></label>
                    <div class="form-control bg-disabled ">
                        <?php
                        $ret = '&nbsp;';
                        if($model->areadata){//aqui estoy
                            $ret .= $model->areadata->area;
                        }
                        echo $ret;?>
                    </div>
                </div>
                <div class="col-lg-3">
                    <label class="control-label"><?= $model->getAttributeLabel('puesto');?></label>
                    <div class="form-control bg-disabled ">
                        <?php
                        $ret = '&nbsp;';
                        if($model->puestodata){//aqui estoy
                            $ret .= $model->puestodata->nombre;
                        }
                        echo $ret;?>
                    </div>
                </div>
                <?php
                $display_puesto = 'none';
                if($model->puesto == 0){
                    $display_puesto = 'block';
                }
                ?>
                <div class="col-lg-4" id="bloquenuevo_puesto" style="display:<?=$display_puesto;?>;">
                    <label class="control-label"><?= $model->getAttributeLabel('aux_nuevopuesto');?></label>
                    <div class="form-control bg-disabled ">
                        <?php
                        $ret = '&nbsp;';
                        if($model->aux_nuevopuesto){
                            $ret .= $model->aux_nuevopuesto;
                        }
                        echo $ret;?>
                    </div>
                </div>
            </div>

            <div class="row my-3 mt-5">
                <div class="col-lg-2">
                    <label class="control-label"><?= $model->getAttributeLabel('nivel_lectura');?></label>
                    <div class="form-control bg-disabled ">
                        <?php
                        $ret = '&nbsp;';
                        if($model->nivel_lectura != null && $model->nivel_lectura != '' && $model->nivel_lectura != ' '){
                            $ret .= $array_lectura[$model->nivel_lectura];
                        }
                        echo $ret;?>
                    </div>
                </div>
                <div class="col-lg-2">
                    <label class="control-label"><?= $model->getAttributeLabel('nivel_escritura');?></label>
                    <div class="form-control bg-disabled ">
                        <?php
                        $ret = '&nbsp;';
                        if($model->nivel_escritura != null && $model->nivel_escritura != '' && $model->nivel_escritura != ' '){
                            $ret .= $array_lectura[$model->nivel_escritura];
                        }
                        echo $ret;?>
                    </div>
                </div>
                <div class="col-lg-3">
                    <label class="control-label"><?= $model->getAttributeLabel('estado_civil');?></label>
                    <div class="form-control bg-disabled ">
                        <?php
                        $ret = '&nbsp;';
                        if($model->estado_civil != null && $model->estado_civil != '' && $model->estado_civil != ' '){
                            $ret .= $estadocivil[$model->estado_civil];
                        }
                        echo $ret;?>
                    </div>
                </div>

                <div class="col-lg-2 offset-lg-1">
                    <label class="control-label"><?= $model->getAttributeLabel('grupo');?></label>
                    <div class="form-control bg-disabled ">
                        <?php
                        $ret = '&nbsp;';
                        if($model->grupo != null && $model->grupo != '' && $model->grupo != ' '){
                            $ret .= $array_grupo[$model->grupo];
                        }
                        echo $ret;?>
                    </div>
                </div>
                <div class="col-lg-2">
                    <label class="control-label"><?= $model->getAttributeLabel('rh');?></label>
                    <div class="form-control bg-disabled ">
                        <?php
                        $ret = '&nbsp;';
                        if($model->rh != null && $model->rh != '' && $model->rh != ' '){
                            $ret .= $array_rh[$model->rh];
                        }
                        echo $ret;?>
                    </div>
                </div>
            </div>


            <div class="row my-3 mt-5">
                <div class="col-lg-2">
                    <label class="control-label"><?= $model->getAttributeLabel('numero_emergencia');?></label>
                    <div class="form-control bg-disabled ">
                        <?php
                        $ret = '&nbsp;';
                        if($model->numero_emergencia){
                            $ret .= $model->numero_emergencia;
                        }
                        echo $ret;?>
                    </div>
                </div>
                <div class="col-lg-3">
                    <label class="control-label"><?= $model->getAttributeLabel('familiar_empresa');?></label>
                    <div class="form-control bg-disabled ">
                        <?php
                        $ret = '&nbsp;';
                        if($model->familiar_empresa){
                            $ret .= $model->familiar_empresa;
                        }
                        echo $ret;?>
                    </div>
                </div>
                <?php 
                $show = 'none';
                if($model->familiar_empresa == 'SI'){
                    $show = 'block';
                }
                ?>
                <div class="col-lg-4" id="familiar1" style="display:<?php echo $show;?>;">
                    <label class="control-label"><?= $model->getAttributeLabel('id_familiar');?></label>
                    <div class="form-control bg-disabled ">
                        <?php
                        $ret = '&nbsp;';
                        if($model->familiar){
                            $ret .= $model->familiar->nombre.' '.$model->familiar->apellidos;
                        }
                        echo $ret;?>
                    </div>
                </div>
                <div class="col-lg-3" id="familiar2" style="display:<?php echo $show;?>;">
                    <label class="control-label"><?= $model->getAttributeLabel('id_area');?></label>
                    <div class="form-control bg-disabled ">
                        <?php
                        $ret = '&nbsp;';
                        if($model->areafamiliar){
                            $ret .= $model->areafamiliar->area;
                        }
                        echo $ret;?>
                    </div>
                </div>
            </div>


            <!-- <?= $form->field($model, 'hora')->textInput() ?> -->

            <!-- <?= $form->field($model, 'empresa')->textInput(['maxlength' => true]) ?> -->


            <?php
            $iconclip = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clipboard2-fill" viewBox="0 0 16 16">
            <path d="M9.5 0a.5.5 0 0 1 .5.5.5.5 0 0 0 .5.5.5.5 0 0 1 .5.5V2a.5.5 0 0 1-.5.5h-5A.5.5 0 0 1 5 2v-.5a.5.5 0 0 1 .5-.5.5.5 0 0 0 .5-.5.5.5 0 0 1 .5-.5h3Z"/>
            <path d="M3.5 1h.585A1.498 1.498 0 0 0 4 1.5V2a1.5 1.5 0 0 0 1.5 1.5h5A1.5 1.5 0 0 0 12 2v-.5c0-.175-.03-.344-.085-.5h.585A1.5 1.5 0 0 1 14 2.5v12a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 14.5v-12A1.5 1.5 0 0 1 3.5 1Z"/>
            </svg>';
            ?>
            <div class="container-fluid my-3 border30 bg-customlight border-custom p-4">
                <div class="row m-0 p-0">
                    <div class="col-lg-4 title2 boxtitle p-1 rounded-3 color3 my-3">
                        <label class="">
                            <span class="mx-2"><?php echo  $iconclip;?></span>Antecedentes Heredo Familiares
                        </label>
                    </div>
                </div>

                <!-- DIABETES -->
                <div class="row my-3">
                    <div class="col-lg-2">
                        <p class="p-1 bgtransparent1 text-dark font14 m-1"><?= 'Diabetes' ?></p>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-control bg-disabled ">
                            <?php
                            $ret = '&nbsp;';
                            if($model->diabetess){
                                $ret .= $model->diabetess;
                            }
                            echo $ret;?>
                        </div>
                    </div>
                    <?php 
                    $show = 'none';
                    if($model->diabetess == 'SI'){
                        $show = 'block';
                    }
                    ?>
                    <div class="col-lg-8" id="diabetesstxt" style="display:<?php echo $show;?>;">
                        <div class="form-control bg-disabled ">
                            <?php
                            $ret = '&nbsp;';
                            if($AHF_diabetes){
                                $ret .= $AHF_diabetes;
                            }
                            echo $ret;?>
                        </div>
                    </div>
                </div>

                <!-- HIPERTENSIÓN -->
                <div class="row my-3">
                    <div class="col-lg-2">
                        <p class="p-1 bgtransparent1 text-dark font14 m-1"><?= 'Hipertensión' ?></p>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-control bg-disabled ">
                            <?php
                            $ret = '&nbsp;';
                            if($model->hipertension){
                                $ret .= $model->hipertension;
                            }
                            echo $ret;?>
                        </div>
                    </div>
                    <?php 
                    $show = 'none';
                    if($model->hipertension == 'SI'){
                        $show = 'block';
                    }?>
                    <div class="col-lg-8" id="hipertensiontxt" style="display:<?php echo $show;?>;">
                        <div class="form-control bg-disabled ">
                            <?php
                            $ret = '&nbsp;';
                            if($AHF_hipertension){
                                $ret .= $AHF_hipertension;
                            }
                            echo $ret;?>
                        </div>
                    </div>
                </div>

                <!-- CANCER -->
                <div class="row my-3">
                    <div class="col-lg-2">
                        <p class="p-1 bgtransparent1 text-dark font14 m-1"><?= 'Cancer' ?></p>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-control bg-disabled ">
                            <?php
                            $ret = '&nbsp;';
                            if($model->cancer){
                                $ret .= $model->cancer;
                            }
                            echo $ret;?>
                        </div>
                    </div>
                    <?php 
                    $show = 'none';
                    if($model->cancer == 'SI'){
                        $show = 'block';
                    }?>
                    <div class="col-lg-8" id="cancertxt" style="display:<?php echo $show;?>;">
                        <div class="form-control bg-disabled ">
                            <?php
                            $ret = '&nbsp;';
                            if($AHF_cancer){
                                $ret .= $AHF_cancer;
                            }
                            echo $ret;?>
                        </div>
                    </div>
                </div>

                <!-- NEFROPATÍAS -->
                <div class="row my-3">
                    <div class="col-lg-2">
                        <p class="p-1 bgtransparent1 text-dark font14 m-1"><?= 'Nefropatias' ?></p>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-control bg-disabled ">
                            <?php
                            $ret = '&nbsp;';
                            if($model->nefropatias){
                                $ret .= $model->nefropatias;
                            }
                            echo $ret;?>
                        </div>
                    </div>
                    <?php 
                    $show = 'none';
                    if($model->nefropatias == 'SI'){
                        $show = 'block';
                    }?>
                    <div class="col-lg-8" id="nefropatiastxt" style="display:<?php echo $show;?>;">
                        <div class="form-control bg-disabled ">
                            <?php
                            $ret = '&nbsp;';
                            if($AHF_nefropatias){
                                $ret .= $AHF_nefropatias;
                            }
                            echo $ret;?>
                        </div>
                    </div>
                </div>

                <!-- CARDIOPATÍAS -->
                <div class="row my-3">
                    <div class="col-lg-2">
                        <p class="p-1 bgtransparent1 text-dark font14 m-1"><?= 'Cardiopatias' ?></p>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-control bg-disabled ">
                            <?php
                            $ret = '&nbsp;';
                            if($model->cardiopatias){
                                $ret .= $model->cardiopatias;
                            }
                            echo $ret;?>
                        </div>
                    </div>
                    <?php 
                    $show = 'none';
                    if($model->cardiopatias == 'SI'){
                        $show = 'block';
                    }?>
                    <div class="col-lg-8" id="cardiopatiastxt" style="display:<?php echo $show;?>;">
                        <div class="form-control bg-disabled ">
                            <?php
                            $ret = '&nbsp;';
                            if($AHF_cardiopatias){
                                $ret .= $AHF_cardiopatias;
                            }
                            echo $ret;?>
                        </div>
                    </div>
                </div>

                <!-- ENFERMEDADES REUMATICAS -->
                <div class="row my-3">
                    <div class="col-lg-2">
                        <p class="p-1 bgtransparent1 text-dark font14 m-1"><?= 'Enfermedades Reumaticas' ?></p>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-control bg-disabled ">
                            <?php
                            $ret = '&nbsp;';
                            if($model->reuma){
                                $ret .= $model->reuma;
                            }
                            echo $ret;?>
                        </div>
                    </div>
                    <?php 
                    $show = 'none';
                    if($model->reuma == 'SI'){
                        $show = 'block';
                    }?>
                    <div class="col-lg-8" id="reumatxt" style="display:<?php echo $show;?>;">
                        <div class="form-control bg-disabled ">
                            <?php
                            $ret = '&nbsp;';
                            if($AHF_enfreumaticas){
                                $ret .= $AHF_enfreumaticas;
                            }
                            echo $ret;?>
                        </div>
                    </div>
                </div>

                <!-- HEPATICOS   -->
                <div class="row">
                    <div class="col-lg-2">
                        <p class="p-1 bgtransparent1 text-dark font14 m-1"><?= 'Hepáticos' ?></p>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-control bg-disabled ">
                            <?php
                            $ret = '&nbsp;';
                            if($model->hepa){
                                $ret .= $model->hepa;
                            }
                            echo $ret;?>
                        </div>
                    </div>
                    <?php 
                    $show = 'none';
                    if($model->hepa == 'SI'){
                        $show = 'block';
                    }?>
                    <div class="col-lg-8" id="hepatxt" style="display:<?php echo $show;?>;">
                        <div class="form-control bg-disabled ">
                            <?php
                            $ret = '&nbsp;';
                            if($AHF_hepaticos){
                                $ret .= $AHF_hepaticos;
                            }
                            echo $ret;?>
                        </div>
                    </div>
                </div>

                <!-- TUBERCULOSIS   -->
                <div class="row my-3">
                    <div class="col-lg-2">
                        <p class="p-1 bgtransparent1 text-dark font14 m-1"><?= 'Tuberculosis' ?></p>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-control bg-disabled ">
                            <?php
                            $ret = '&nbsp;';
                            if($model->tuber){
                                $ret .= $model->tuber;
                            }
                            echo $ret;?>
                        </div>
                    </div>
                    <?php 
                    $show = 'none';
                    if($model->tuber == 'SI'){
                        $show = 'block';
                    }?>
                    <div class="col-lg-8" id="tubertxt" style="display:<?php echo $show;?>;">
                        <div class="form-control bg-disabled ">
                            <?php
                            $ret = '&nbsp;';
                            if($AHF_tuberculosis){
                                $ret .= $AHF_tuberculosis;
                            }
                            echo $ret;?>
                        </div>
                    </div>
                </div>

                <!-- PSIQUIATRICOS   -->
                <div class="row my-3">
                    <div class="col-lg-2">
                        <p class="p-1 bgtransparent1 text-dark font14 m-1"><?= 'Psiquiátricos' ?></p>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-control bg-disabled ">
                            <?php
                            $ret = '&nbsp;';
                            if($model->psi){
                                $ret .= $model->psi;
                            }
                            echo $ret;?>
                        </div>
                    </div>
                    <?php 
                    $show = 'none';
                    if($model->psi == 'SI'){
                        $show = 'block';
                    }?>
                    <div class="col-lg-8" id="psitxt" style="display:<?php echo $show;?>;">
                        <div class="form-control bg-disabled ">
                            <?php
                            $ret = '&nbsp;';
                            if($AHF_psiquiatricos){
                                $ret .= $AHF_psiquiatricos;
                            }
                            echo $ret;?>
                        </div>
                    </div>
                </div>
            </div>


            <div class="container-fluid my-3 border30 bg-light p-4">
                <div class="row m-0 p-0">
                    <div class="col-lg-4 title2 boxtitle p-1 rounded-3 color3 my-3">
                        <label class="">
                            <span class="mx-2"><?php echo  $iconclip;?></span>Antecedentes Personales no Patológicos
                        </label>
                    </div>
                </div>

                <!-- TABAQUISMO -->
                <div class="row my-3">
                    <div class="col-lg-2">
                        <p class="p-1 bgtransparent1 text-dark font14 m-1"><?= 'Tabaquismo' ?></p>
                        <label class="form-check-label"></label>
                    </div>

                    <div class="col-lg-2">
                        <div class="form-control bg-disabled ">
                            <?php
                            $ret = '&nbsp;';
                            if($model->tabaquismo){
                                $ret .= $model->tabaquismo;
                            }
                            echo $ret;?>
                        </div>
                    </div>
                    <?php  
                    $show = 'none';
                    if($model->tabaquismo == 'SI'){
                        $show = 'block';
                    }
                    ?>
                    <div class="col-lg-8" id="tabaquismodata" style="display:<?php echo $show;?>;">
                        <div class="row">
                            <div class="col-lg-4">
                                <label class="control-label"><?= $model->getAttributeLabel('tabdesde');?></label>
                                <div class="form-control bg-disabled ">
                                    <?php
                                    $ret = '&nbsp;';
                                    if($model->tabdesde){
                                        $ret .= $model->tabdesde;
                                    }
                                    echo $ret;?>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <label class="control-label"><?= $model->getAttributeLabel('tabfrec');?></label>
                                <div class="form-control bg-disabled ">
                                    <?php
                                    $ret = '&nbsp;';
                                    if($APNP_tabaquismofrec){
                                        $ret .= $APNP_tabaquismofrec;
                                    }
                                    echo $ret;?>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <label class="control-label"><?= $model->getAttributeLabel('tabcantidad');?></label>
                                <div class="form-control bg-disabled ">
                                    <?php
                                    $ret = '&nbsp;';
                                    if($APNP_tabaquismocant){
                                        $ret .= $APNP_tabaquismocant;
                                    }
                                    echo $ret;?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ALCOHOLISMO -->
                <div class="row my-3">
                    <div class="col-lg-2">
                        <p class="p-1 bgtransparent1 text-dark font14 m-1"><?= 'Alcoholismo' ?></p>
                        <label class="form-check-label"></label>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-control bg-disabled ">
                            <?php
                            $ret = '&nbsp;';
                            if($model->alcoholismo){
                                $ret .= $model->alcoholismo;
                            }
                            echo $ret;?>
                        </div>
                    </div>
                    <?php  
                    $show = 'none';
                    if($model->alcoholismo == 'SI'){
                        $show = 'block';
                    }
                    ?>
                    <div class="col-lg-8" id="alcoholismodata" style="display:<?php echo $show;?>;">
                        <div class="row">
                            <div class="col-lg-4">
                                <label class="control-label"><?= $model->getAttributeLabel('alcodesde');?></label>
                                <div class="form-control bg-disabled ">
                                    <?php
                                    $ret = '&nbsp;';
                                    if($model->alcodesde){
                                        $ret .= $model->alcodesde;
                                    }
                                    echo $ret;?>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <label class="control-label"><?= $model->getAttributeLabel('alcofrec');?></label>
                                <div class="form-control bg-disabled ">
                                    <?php
                                    $ret = '&nbsp;';
                                    if($APNP_alcoholismofrec){
                                        $ret .= $APNP_alcoholismofrec;
                                    }
                                    echo $ret;?>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <label class="control-label"><?= $model->getAttributeLabel('alcocantidad');?></label>
                                <div class="form-control bg-disabled ">
                                    <?php
                                    $ret = '&nbsp;';
                                    if($APNP_alcoholismocant){
                                        $ret .= $APNP_alcoholismocant;
                                    }
                                    echo $ret;?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- COCINA CON LEÑA -->
                <div class="row my-3">
                    <div class="col-lg-2">
                        <p class="p-1 bgtransparent1 text-dark font14 m-1"><?= 'Cocina con leña' ?></p>
                        <label class="form-check-label"></label>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-control bg-disabled ">
                            <?php
                            $ret = '&nbsp;';
                            if($model->cocina){
                                $ret .= $model->cocina;
                            }
                            echo $ret;?>
                        </div>
                    </div>
                    <?php 
                    $show = 'none';
                    if($model->cocina == 'SI'){
                        $show = 'none';
                    }
                    ?>
                    <div class="col-lg-3" id="cocinadata" style="display:<?php echo $show;?>;">
                        <label class="control-label"><?= $model->getAttributeLabel('cocinadesde');?></label>
                        <div class="form-control bg-disabled ">
                            <?php
                                    $ret = '&nbsp;';
                                    if($model->cocinadesde){
                                        $ret .= $model->cocinadesde;
                                    }
                                    echo $ret;?>
                        </div>
                    </div>
                </div>

                <!-- USO DE AUDIFONOS -->
                <div class="row my-3">
                    <div class="col-lg-2">
                        <p class="p-1 bgtransparent1 text-dark font14 m-1"><?= 'Audifonos' ?></p>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-control bg-disabled ">
                            <?php
                                    $ret = '&nbsp;';
                                    if($model->audifonos){
                                        $ret .= $model->audifonos;
                                    }
                                    echo $ret;?>
                        </div>
                    </div>
                    <?php 
                    $show = 'none';
                    if($model->audifonos == 'SI'){
                        $show = 'block';
                    }
                    ?>
                    <div class="col-lg-8" id="audifonosdata" style="display:<?php echo $show;?>;">
                        <div class="row">
                            <div class="col-lg-4">
                                <label class="control-label"><?= $model->getAttributeLabel('audiodesde');?></label>
                                <div class="form-control bg-disabled ">
                                    <?php
                                    $ret = '&nbsp;';
                                    if($APNP_audifonosfrec){
                                        $ret .= $APNP_audifonosfrec;
                                    }
                                    echo $ret;?>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <label class="control-label"><?= $model->getAttributeLabel('audiocuando');?></label>
                                <div class="form-control bg-disabled ">
                                    <?php
                                    $ret = '&nbsp;';
                                    if($APNP_audifonoscant){
                                        $ret .= $APNP_audifonoscant;
                                    }
                                    echo $ret;?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- DROGADICCION -->
                <div class="row my-3">
                    <div class="col-lg-2">
                        <p class="p-1 bgtransparent1 text-dark font14 m-1"><?= 'Drogadicción' ?></p>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-control bg-disabled ">
                            <?php
                                    $ret = '&nbsp;';
                                    if($model->droga){
                                        $ret .= $model->droga;
                                    }
                                    echo $ret;?>
                        </div>
                    </div>
                    <?php 
                    $show = 'none';
                    if($model->droga == 'SI'){
                        $show = 'block';
                    }
                    ?>
                    <div class="col-lg-8" id="drogadata" style="display:<?php echo $show;?>;">
                        <div class="row">
                            <div class="col-lg-4">
                                <label class="control-label"><?= $model->getAttributeLabel('drogatxt');?></label>
                                <div class="form-control bg-disabled ">
                                    <?php
                                    $ret = '&nbsp;';
                                    if($APNP_drogadicciontipo){
                                        $ret .= $APNP_drogadicciontipo;
                                    }
                                    echo $ret;?>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <label class="control-label"><?= $model->getAttributeLabel('duracion_droga');?></label>
                                <div class="form-control bg-disabled ">
                                    <?php
                                    $ret = '&nbsp;';
                                    if($model->duracion_droga){
                                        $ret .= $model->duracion_droga;
                                    }
                                    echo $ret;?>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <label class="control-label"><?= $model->getAttributeLabel('fecha_droga');?></label>
                                <div class="form-control bg-disabled ">
                                    <?php
                                    $ret = '&nbsp;';
                                    if($APNP_drogadiccionfecha){
                                        $ret .= $APNP_drogadiccionfecha;
                                    }
                                    echo $ret;?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- VACUNACION -->
                <div class="row my-3">

                    <div class="col-lg-2">
                        <p class="p-1 bgtransparent1 text-dark font14 m-1"><?= 'Vacunación' ?></p>
                    </div>

                    <div class="col-lg-2">
                        <div class="form-control bg-disabled ">
                            <?php
                                    $ret = '&nbsp;';
                                    if($model->vacunacion_cov){
                                        $ret .= $model->vacunacion_cov;
                                    }
                                    echo $ret;?>
                        </div>
                    </div>

                    <?php 
                    $show = 'none';
                    if($model->vacunacion_cov == 'SI'){
                        $show = 'block';
                    }
                    ?>
                    <div class="col-lg-8" id="vacunaciondata" style="display:<?php echo $show;?>;">
                        <div class="row">

                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th class="control-label font500" width="5%">#</th>
                                        <th class="control-label font500" width="55%">Vacuna</th>
                                        <th class="control-label font500" width="40%">Fecha</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach($model->dVacunacion as $key=>$data){
                                        $retvacuna = '';

                                        if($data->vacuna){
                                            $retvacuna = $data->vacuna->vacuna;
                                        }
                                        echo '<tr>
                                            <td class="t5 " width="5%">'.($key+1).'</td>
                                            <td class="t5 " width="55%">'.$retvacuna.'</td>
                                            <td class="t5 color3" width="40%">'.$data->fecha.'</td>
                                            </tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>

                            <div class="col-lg-4" style="display:none;">
                                <label
                                    class="control-label"><?= $model->getAttributeLabel('nombre_vacunacion');?></label>
                                <div class="form-control bg-disabled ">
                                    <?php
                                    $ret = '&nbsp;';
                                    if($APNP_vacunacionnombre){
                                        $ret .= $APNP_vacunacionnombre;
                                    }
                                    echo $ret;?>
                                </div>
                            </div>
                            <div class="col-lg-4" style="display:none;">
                                <label
                                    class="control-label"><?= $model->getAttributeLabel('dosis_vacunacion');?></label>
                                <div class="form-control bg-disabled ">
                                    <?php
                                    $ret = '&nbsp;';
                                    if($APNP_vacunacionnum){
                                        $ret .= $APNP_vacunacionnum;
                                    }
                                    echo $ret;?>
                                </div>
                            </div>
                            <div class="col-lg-4" style="display:none;">
                                <label
                                    class="control-label"><?= $model->getAttributeLabel('fecha_vacunacion');?></label>
                                <div class="form-control bg-disabled ">
                                    <?php
                                    $ret = '&nbsp;';
                                    if($model->fecha_vacunacion){
                                        $ret .= $model->fecha_vacunacion;
                                    }
                                    echo $ret;?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- MANO PREDOMINANTE -->
                <div class="row my-3">
                    <div class="col-lg-2">
                        <p class="p-1 bgtransparent1 text-dark font14 m-1"><?= 'Mano Predominante' ?></p>
                    </div>
                    <div class="col-lg-3">
                        <label class="control-label"><?= $model->getAttributeLabel('mano');?></label>
                        <div class="form-control bg-disabled ">
                            <?php
                                    $ret = '&nbsp;';
                                    if($APNP_mano){
                                        $ret .= $APNP_mano;
                                    }
                                    echo $ret;?>
                        </div>
                    </div>
                </div>

                <!-- RECIENTEMENTE COVID -->
                <div class="row my-3">
                    <div class="col-lg-2">
                        <p class="p-1 bgtransparent1 text-dark font14 m-1"><?= 'Recientemente Covid' ?></p>
                    </div>
                    <div class="col-lg-2">
                        <label class="control-label"><?= $model->getAttributeLabel('covidreciente');?></label>
                        <div class="form-control bg-disabled ">
                            <?php
                                    $ret = '&nbsp;';
                                    if($model->covidreciente){
                                        $ret .= $model->covidreciente;
                                    }
                                    echo $ret;?>
                        </div>
                    </div>
                    <?php 
                    $show = 'none';
                    if($model->covidreciente == 'SI'){
                        $show = 'block';
                    }
                    ?>
                    <div class="col-lg-8" id="covidrecientedata" style="display:<?php echo $show;?>;">
                        <div class="row">
                            <div class="col-lg-4">
                                <label
                                    class="control-label"><?= $model->getAttributeLabel('covidreciente_fecha');?></label>
                                <div class="form-control bg-disabled ">
                                    <?php
                                    $ret = '&nbsp;';
                                    if($model->covidreciente_fecha){
                                        $ret .= $model->covidreciente_fecha;
                                    }
                                    echo $ret;?>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <label
                                    class="control-label"><?= $model->getAttributeLabel('covidreciente_secuelas');?></label>
                                <div class="form-control bg-disabled ">
                                    <?php
                                    $ret = '&nbsp;';
                                    if($model->covidreciente_secuelas){
                                        $ret .= $model->covidreciente_secuelas;
                                    }
                                    echo $ret;?>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <label
                                    class="control-label"><?= $model->getAttributeLabel('covidreciente_vacunacion');?></label>
                                <div class="form-control bg-disabled ">
                                    <?php
                                    $ret = '&nbsp;';
                                    if($model->covidreciente_vacunacion){
                                        $ret .= $model->covidreciente_vacunacion;
                                    }
                                    echo $ret;?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>


            <div class="container-fluid my-3 border30 bg-light p-4">
                <div class="row m-0 p-0">
                    <div class="col-lg-4 title2 boxtitle p-1 rounded-3 color3 my-3">
                        <label class="">
                            <span class="mx-2"><?php echo  $iconclip;?></span>
                            Antecedentes Personales Patológicos
                        </label>
                    </div>
                </div>

                <!-- ALERGIAS -->
                <div class="row py-3 borderbuttonb">
                    <div class="col-lg-2">
                        <p class="p-1 bgtransparent1 text-dark font14 m-1"><?= 'Alergias' ?></p>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-control bg-disabled ">
                            <?php
                                    $ret = '&nbsp;';
                                    if($model->alergias){
                                        $ret .= $model->alergias;
                                    }
                                    echo $ret;?>
                        </div>
                    </div>
                    <?php 
                    $show = 'none';
                    if($model->alergias == 'SI'){
                        $show = 'block';
                    }
                    ?>
                    <div class="col-lg-7 px-0" id='alergiasdata' style="display:<?php echo $show;?>;">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th class="control-label font500" width="5%">#</th>
                                    <th class="control-label font500" width="75%">Diagnóstico</th>
                                    <th class="control-label font500" width="20%">Año</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    foreach($model->dAlergias as $key=>$data){
                                        echo '<tr>
                                            <td class="t5 " width="5%">'.($key+1).'</td>
                                            <td class="t5 " width="75%">'.$data->descripcion.'</td>
                                            <td class="t5 color3" width="50%">'.$data->anio.'</td>
                                            </tr>';
                                    }
                                    ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- ASMA -->
                <div class="row py-3 borderbuttonb">
                    <div class="col-lg-2">
                        <p class="p-1 bgtransparent1 text-dark font14 m-1"><?= 'Asma' ?></p>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-control bg-disabled ">
                            <?php
                                    $ret = '&nbsp;';
                                    if($model->asma){
                                        $ret .= $model->asma;
                                    }
                                    echo $ret;?>
                        </div>
                    </div>
                    <?php 
                    $show = 'none';
                    if($model->asma == 'SI'){
                        $show = 'block';
                    }
                    ?>
                    <div class="col-lg-8" id='asmadata' style="display:<?php echo $show;?>;">
                        <div class="row">
                            <div class="col-lg-4">
                                <label class="control-label"><?= $model->getAttributeLabel('asmatxt');?></label>
                                <div class="form-control bg-disabled ">
                                    <?php
                                    $ret = '&nbsp;';
                                    if($model->asmatxt){
                                        $ret .= $model->asmatxt;
                                    }
                                    echo $ret;?>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <label class="control-label"><?= $model->getAttributeLabel('asma_anio');?></label>
                                <div class="form-control bg-disabled ">
                                    <?php
                                    $ret = '&nbsp;';
                                    if($model->asma_anio){
                                        $ret .= $model->asma_anio;
                                    }
                                    echo $ret;?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CARDIOPATIAS -->
                <div class="row py-3 borderbuttonb">
                    <div class="col-lg-2">
                        <p class="p-1 bgtransparent1 text-dark font14 m-1"><?= 'Cardiopatías' ?></p>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-control bg-disabled ">
                            <?php
                                    $ret = '&nbsp;';
                                    if($model->cardio){
                                        $ret .= $model->cardio;
                                    }
                                    echo $ret;?>
                        </div>
                    </div>
                    <?php 
                    $show = 'none';
                    if($model->cardio == 'SI'){
                        $show = 'block';
                    }
                    ?>
                    <div class="col-lg-7 px-0" id='cardiodata' style="display:<?php echo $show;?>;">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th class="control-label font500" width="5%">#</th>
                                    <th class="control-label font500" width="75%">Diagnóstico</th>
                                    <th class="control-label font500" width="20%">Año</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    foreach($model->dCardiopatias as $key=>$data){
                                        echo '<tr>
                                            <td class="t5 " width="5%">'.($key+1).'</td>
                                            <td class="t5 " width="75%">'.$data->descripcion.'</td>
                                            <td class="t5 color3" width="50%">'.$data->anio.'</td>
                                            </tr>';
                                    }
                                    ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- CIRUGIAS -->
                <div class="row py-3 borderbuttonb">
                    <div class="col-lg-2">
                        <p class="p-1 bgtransparent1 text-dark font14 m-1"><?= 'Cirugías' ?></p>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-control bg-disabled ">
                            <?php
                                    $ret = '&nbsp;';
                                    if($model->cirugias){
                                        $ret .= $model->cirugias;
                                    }
                                    echo $ret;?>
                        </div>
                    </div>
                    <?php 
                    $show = 'none';
                    if($model->cirugias == 'SI'){
                        $show = 'block';
                    }
                    ?>
                    <div class="col-lg-7 px-0" id='cirugiasdata' style="display:<?php echo $show;?>;">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th class="control-label font500" width="5%">#</th>
                                    <th class="control-label font500" width="75%">Diagnóstico</th>
                                    <th class="control-label font500" width="20%">Año</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    foreach($model->dCirugias as $key=>$data){
                                        echo '<tr>
                                            <td class="t5 " width="5%">'.($key+1).'</td>
                                            <td class="t5 " width="75%">'.$data->descripcion.'</td>
                                            <td class="t5 color3" width="50%">'.$data->anio.'</td>
                                            </tr>';
                                    }
                                    ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- CONVULSIONES -->
                <div class="row py-3 borderbuttonb">
                    <div class="col-lg-2">
                        <p class="p-1 bgtransparent1 text-dark font14 m-1"><?= 'Convulsiones' ?></p>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-control bg-disabled ">
                            <?php
                                    $ret = '&nbsp;';
                                    if($model->convulsiones){
                                        $ret .= $model->convulsiones;
                                    }
                                    echo $ret;?>
                        </div>
                    </div>
                    <?php 
                    $show = 'none';
                    if($model->convulsiones == 'SI'){
                        $show = 'block';
                    }
                    ?>
                    <div class="col-lg-7 px-0" id='convulsionesdata' style="display:<?php echo $show;?>;">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th class="control-label font500" width="5%">#</th>
                                    <th class="control-label font500" width="75%">Diagnóstico</th>
                                    <th class="control-label font500" width="20%">Año</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    foreach($model->dConvulsiones as $key=>$data){
                                        echo '<tr>
                                            <td class="t5 " width="5%">'.($key+1).'</td>
                                            <td class="t5 " width="75%">'.$data->descripcion.'</td>
                                            <td class="t5 color3" width="50%">'.$data->anio.'</td>
                                            </tr>';
                                    }
                                    ?>
                            </tbody>
                        </table>
                    </div>

                </div>

                <!-- DIABETES -->
                <div class="row py-3 borderbuttonb">
                    <div class="col-lg-2">
                        <p class="p-1 bgtransparent1 text-dark font14 m-1"><?= 'Diabetes' ?></p>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-control bg-disabled ">
                            <?php
                                    $ret = '&nbsp;';
                                    if($model->diabetes){
                                        $ret .= $model->diabetes;
                                    }
                                    echo $ret;?>
                        </div>
                    </div>
                    <?php 
                    $show = 'none';
                    if($model->diabetes == 'SI'){
                        $show = 'block';
                    }
                    ?>
                    <div class="col-lg-7 px-0" id='diabetesdata' style="display:<?php echo $show;?>;">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th class="control-label font500" width="5%">#</th>
                                    <th class="control-label font500" width="75%">Diagnóstico</th>
                                    <th class="control-label font500" width="20%">Año</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    foreach($model->dDiabetes as $key=>$data){
                                        echo '<tr>
                                            <td class="t5 " width="5%">'.($key+1).'</td>
                                            <td class="t5 " width="75%">'.$data->descripcion.'</td>
                                            <td class="t5 color3" width="50%">'.$data->anio.'</td>
                                            </tr>';
                                    }
                                    ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- HIPERTENSION -->
                <div class="row py-3 borderbuttonb">
                    <div class="col-lg-2">
                        <p class="p-1 bgtransparent1 text-dark font14 m-1"><?= 'Hipertensión' ?></p>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-control bg-disabled ">
                            <?php
                                    $ret = '&nbsp;';
                                    if($model->hiper){
                                        $ret .= $model->hiper;
                                    }
                                    echo $ret;?>
                        </div>
                    </div>
                    <?php 
                    $show = 'none';
                    if($model->hiper == 'SI'){
                        $show = 'block';
                    }
                    ?>
                    <div class="col-lg-7 px-0" id='hiperdata' style="display:<?php echo $show;?>;">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th class="control-label font500" width="5%">#</th>
                                    <th class="control-label font500" width="75%">Diagnóstico</th>
                                    <th class="control-label font500" width="20%">Año</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    foreach($model->dHipertension as $key=>$data){
                                        echo '<tr>
                                            <td class="t5 " width="5%">'.($key+1).'</td>
                                            <td class="t5 " width="75%">'.$data->descripcion.'</td>
                                            <td class="t5 color3" width="50%">'.$data->anio.'</td>
                                            </tr>';
                                    }
                                    ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- LUMBALGIAS -->
                <div class="row py-3 borderbuttonb">
                    <div class="col-lg-2">
                        <p class="p-1 bgtransparent1 text-dark font14 m-1"><?= 'Lumbalgias' ?></p>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-control bg-disabled ">
                            <?php
                                    $ret = '&nbsp;';
                                    if($model->lumbalgias){
                                        $ret .= $model->lumbalgias;
                                    }
                                    echo $ret;?>
                        </div>
                    </div>
                    <?php 
                    $show = 'none';
                    if($model->lumbalgias == 'SI'){
                        $show = 'block';
                    }
                    ?>
                    <div class="col-lg-7 px-0" id='lumbalgiasdata' style="display:<?php echo $show;?>;">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th class="control-label font500" width="5%">#</th>
                                    <th class="control-label font500" width="75%">Diagnóstico</th>
                                    <th class="control-label font500" width="20%">Año</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    foreach($model->dLumbalgias as $key=>$data){
                                        echo '<tr>
                                            <td class="t5 " width="5%">'.($key+1).'</td>
                                            <td class="t5 " width="75%">'.$data->descripcion.'</td>
                                            <td class="t5 color3" width="50%">'.$data->anio.'</td>
                                            </tr>';
                                    }
                                    ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- NEFROPATIAS -->
                <div class="row py-3 borderbuttonb">
                    <div class="col-lg-2">
                        <p class="p-1 bgtransparent1 text-dark font14 m-1"><?= 'Nefropatías' ?></p>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-control bg-disabled ">
                            <?php
                                    $ret = '&nbsp;';
                                    if($model->nefro){
                                        $ret .= $model->nefro;
                                    }
                                    echo $ret;?>
                        </div>
                    </div>
                    <?php 
                    $show = 'none';
                    if($model->nefro == 'SI'){
                        $show = 'block';
                    }
                    ?>
                    <div class="col-lg-7 px-0" id='nefrodata' style="display:<?php echo $show;?>;">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th class="control-label font500" width="5%">#</th>
                                    <th class="control-label font500" width="75%">Diagnóstico</th>
                                    <th class="control-label font500" width="20%">Año</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    foreach($model->dNefropatias as $key=>$data){
                                        echo '<tr>
                                            <td class="t5 " width="5%">'.($key+1).'</td>
                                            <td class="t5 " width="75%">'.$data->descripcion.'</td>
                                            <td class="t5 color3" width="50%">'.$data->anio.'</td>
                                            </tr>';
                                    }
                                    ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- POLIOMELITIS -->
                <div class="row py-3 borderbuttonb">
                    <div class="col-lg-2">
                        <p class="p-1 bgtransparent1 text-dark font14 m-1"><?= 'Poliomelitis' ?></p>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-control bg-disabled ">
                            <?php
                                    $ret = '&nbsp;';
                                    if($model->polio){
                                        $ret .= $model->polio;
                                    }
                                    echo $ret;?>
                        </div>
                    </div>
                    <?php 
                    $show = 'none';
                    if($model->polio == 'SI'){
                        $show = 'block';
                    }
                    ?>
                    <div class="col-lg-3 px-0" id='poliodata' style="display:<?php echo $show;?>;">
                        <label class="control-label"><?= $model->getAttributeLabel('poliomelitis_anio');?></label>
                        <div class="form-control bg-disabled ">
                            <?php
                                    $ret = '&nbsp;';
                                    if($model->poliomelitis_anio){
                                        $ret .= $model->poliomelitis_anio;
                                    }
                                    echo $ret;?>
                        </div>
                    </div>
                </div>

                <!-- SARAMPION -->
                <div class="row py-3 borderbuttonb">
                    <div class="col-lg-2">
                        <p class="p-1 bgtransparent1 text-dark font14 m-1"><?= 'Sarampión' ?></p>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-control bg-disabled ">
                            <?php
                                    $ret = '&nbsp;';
                                    if($model->saram){
                                        $ret .= $model->saram;
                                    }
                                    echo $ret;?>
                        </div>
                    </div>
                    <?php 
                    $show = 'none';
                    if($model->saram == 'SI'){
                        $show = 'block';
                    }
                    ?>
                    <div class="col-lg-3 px-0" id='saramdata' style="display:<?php echo $show;?>;">
                        <label class="control-label"><?= $model->getAttributeLabel('saram_anio');?></label>
                        <div class="form-control bg-disabled ">
                            <?php
                                    $ret = '&nbsp;';
                                    if($model->saram_anio){
                                        $ret .= $model->saram_anio;
                                    }
                                    echo $ret;?>
                        </div>
                    </div>
                </div>

                <!-- PROBLEMAS PULMONARES -->
                <div class="row py-3 borderbuttonb">
                    <div class="col-lg-2">
                        <p class="p-1 bgtransparent1 text-dark font14 m-1"><?= 'Enf. Pulmonares' ?></p>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-control bg-disabled ">
                            <?php
                                    $ret = '&nbsp;';
                                    if($model->pulmo){
                                        $ret .= $model->pulmo;
                                    }
                                    echo $ret;?>
                        </div>
                    </div>
                    <?php 
                    $show = 'none';
                    if($model->pulmo == 'SI'){
                        $show = 'block';
                    }
                    ?>
                    <div class="col-lg-7 px-0" id='pulmodata' style="display:<?php echo $show;?>;">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th class="control-label font500" width="5%">#</th>
                                    <th class="control-label font500" width="75%">Diagnóstico</th>
                                    <th class="control-label font500" width="20%">Año</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    foreach($model->dPulmonares as $key=>$data){
                                        echo '<tr>
                                            <td class="t5 " width="5%">'.($key+1).'</td>
                                            <td class="t5 " width="75%">'.$data->descripcion.'</td>
                                            <td class="t5 color3" width="50%">'.$data->anio.'</td>
                                            </tr>';
                                    }
                                    ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- TRASTORNOS HEMATICOS -->



                <!-- TRAUMATISMOS -->
                <div class="row py-3 borderbuttonb">
                    <div class="col-lg-2">
                        <p class="p-1 bgtransparent1 text-dark font14 m-1"><?= 'Traumatismos' ?></p>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-control bg-disabled ">
                            <?php
                                    $ret = '&nbsp;';
                                    if($model->trauma){
                                        $ret .= $model->trauma;
                                    }
                                    echo $ret;?>
                        </div>
                    </div>
                    <?php 
                    $show = 'none';
                    if($model->trauma == 'SI'){
                        $show = 'block';
                    }
                    ?>
                    <div class="col-lg-7 px-0" id='traumadata' style="display:<?php echo $show;?>;">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th class="control-label font500" width="5%">#</th>
                                    <th class="control-label font500" width="75%">Diagnóstico</th>
                                    <th class="control-label font500" width="20%">Año</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    foreach($model->dTraumatismos as $key=>$data){
                                        echo '<tr>
                                            <td class="t5 " width="5%">'.($key+1).'</td>
                                            <td class="t5 " width="75%">'.$data->descripcion.'</td>
                                            <td class="t5 color3" width="50%">'.$data->anio.'</td>
                                            </tr>';
                                    }
                                    ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- USO DE MEDICAMENTOS -->
                <div class="row py-3 borderbuttonb">
                    <div class="col-lg-2">
                        <p class="p-1 bgtransparent1 text-dark font14 m-1"><?= 'Uso de Medicamentos' ?></p>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-control bg-disabled ">
                            <?php
                                    $ret = '&nbsp;';
                                    if($model->medicamentos){
                                        $ret .= $model->medicamentos;
                                    }
                                    echo $ret;?>
                        </div>
                    </div>
                    <?php 
                    $show = 'none';
                    if($model->medicamentos == 'SI'){
                        $show = 'block';
                    }
                    ?>
                    <div class="col-lg-7 px-0" id='medicamentosdata' style="display:<?php echo $show;?>;">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th class="control-label font500" width="5%">#</th>
                                    <th class="control-label font500" width="75%">Medicamento</th>
                                    <th class="control-label font500" width="20%">Año de Inicio</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    foreach($model->dMedicamentos as $key=>$data){
                                        echo '<tr>
                                            <td class="t5 " width="5%">'.($key+1).'</td>
                                            <td class="t5 " width="75%">'.$data->descripcion.'</td>
                                            <td class="t5 color3" width="50%">'.$data->anio.'</td>
                                            </tr>';
                                    }
                                    ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- USO DE PROTESIS -->
                <div class="row py-3 borderbuttonb">
                    <div class="col-lg-2">
                        <p class="p-1 bgtransparent1 text-dark font14 m-1"><?= 'Uso de prótesis' ?></p>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-control bg-disabled ">
                            <?php
                                    $ret = '&nbsp;';
                                    if($model->protesis){
                                        $ret .= $model->protesis;
                                    }
                                    echo $ret;?>
                        </div>
                    </div>
                    <?php 
                    $show = 'none';
                    if($model->protesis == 'SI'){
                        $show = 'block';
                    }
                    ?>
                    <div class="col-lg-7 px-0" id='protesisdata' style="display:<?php echo $show;?>;">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th class="control-label font500" width="5%">#</th>
                                    <th class="control-label font500" width="95%">Tipo</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    foreach($model->dProtesis as $key=>$data){
                                        echo '<tr>
                                            <td class="t5 " width="5%">'.($key+1).'</td>
                                            <td class="t5 " width="95%">'.$data->descripcion.'</td>
                                            </tr>';
                                    }
                                    ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- TRANSFUSIONES -->
                <div class="row py-3 borderbuttonb">
                    <div class="col-lg-2">
                        <p class="p-1 bgtransparent1 text-dark font14 m-1"><?= 'Transfusiones' ?></p>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-control bg-disabled ">
                            <?php
                                    $ret = '&nbsp;';
                                    if($model->trans){
                                        $ret .= $model->trans;
                                    }
                                    echo $ret;?>
                        </div>
                    </div>
                    <?php 
                    $show = 'none';
                    if($model->trans == 'SI'){
                        $show = 'block';
                    }
                    ?>
                    <div class="col-lg-7 px-0" id='transdata' style="display:<?php echo $show;?>;">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th class="control-label font500" width="5%">#</th>
                                    <th class="control-label font500" width="95%">Año</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    foreach($model->dTransfusiones as $key=>$data){
                                        echo '<tr>
                                            <td class="t5 " width="5%">'.($key+1).'</td>
                                            <td class="t5 color3" width="95%">'.$data->anio.'</td>
                                            </tr>';
                                    }
                                    ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- ENFERMEDAD OCULAR -->
                <div class="row py-3 borderbuttonb">
                    <div class="col-lg-2">
                        <p class="p-1 bgtransparent1 text-dark font14 m-1"><?= 'Enfermedad Ocular' ?></p>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-control bg-disabled ">
                            <?php
                                    $ret = '&nbsp;';
                                    if($model->enf_ocular){
                                        $ret .= $model->enf_ocular;
                                    }
                                    echo $ret;?>
                        </div>
                    </div>
                    <?php 
                    $show = 'none';
                    if($model->enf_ocular == 'SI'){
                        $show = 'block';
                    }
                    ?>
                    <div class="col-lg-7 px-0" id='enf_oculardata' style="display:<?php echo $show;?>;">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th class="control-label font500" width="5%">#</th>
                                    <th class="control-label font500" width="75%">Diagnóstico</th>
                                    <th class="control-label font500" width="20%">Año de Inicio</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    foreach($model->dOcular as $key=>$data){
                                        echo '<tr>
                                            <td class="t5 " width="5%">'.($key+1).'</td>
                                            <td class="t5 " width="75%">'.$data->descripcion.'</td>
                                            <td class="t5 color3" width="50%">'.$data->anio.'</td>
                                            </tr>';
                                    }
                                    ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- ENFERMEDAD AUDITIVA -->
                <div class="row py-3 borderbuttonb">
                    <div class="col-lg-2">
                        <p class="p-1 bgtransparent1 text-dark font14 m-1"><?= 'Enfermedad Auditiva' ?></p>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-control bg-disabled ">
                            <?php
                                    $ret = '&nbsp;';
                                    if($model->enf_auditiva){
                                        $ret .= $model->enf_auditiva;
                                    }
                                    echo $ret;?>
                        </div>
                    </div>
                    <?php 
                    $show = 'none';
                    if($model->enf_auditiva == 'SI'){
                        $show = 'block';
                    }
                    ?>
                    <div class="col-lg-7 px-0" id='enf_auditivadata' style="display:<?php echo $show;?>;">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th class="control-label font500" width="5%">#</th>
                                    <th class="control-label font500" width="75%">Diagnóstico</th>
                                    <th class="control-label font500" width="20%">Año de Inicio</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    foreach($model->dAuditiva as $key=>$data){
                                        echo '<tr>
                                            <td class="t5 " width="5%">'.($key+1).'</td>
                                            <td class="t5 " width="75%">'.$data->descripcion.'</td>
                                            <td class="t5 color3" width="50%">'.$data->anio.'</td>
                                            </tr>';
                                    }
                                    ?>
                            </tbody>
                        </table>
                    </div>
                </div>


                <!-- FRACTURA LUXACIÓN -->
                <div class="row py-3 borderbuttonb">
                    <div class="col-lg-2">
                        <p class="p-1 bgtransparent1 text-dark font14 m-1"><?= 'Fractura / Luxación' ?></p>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-control bg-disabled ">
                            <?php
                                    $ret = '&nbsp;';
                                    if($model->fractura){
                                        $ret .= $model->fractura;
                                    }
                                    echo $ret;?>
                        </div>
                    </div>
                    <?php 
                    $show = 'none';
                    if($model->fractura == 'SI'){
                        $show = 'block';
                    }
                    ?>

                    <div class="col-lg-7 px-0" id='fracturadata' style="display:<?php echo $show;?>;">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th class="control-label font500" width="5%">#</th>
                                    <th class="control-label font500" width="75%">Diagnóstico</th>
                                    <th class="control-label font500" width="20%">Año de Inicio</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    foreach($model->dFractura as $key=>$data){
                                        echo '<tr>
                                            <td class="t5 " width="5%">'.($key+1).'</td>
                                            <td class="t5 " width="75%">'.$data->descripcion.'</td>
                                            <td class="t5 color3" width="50%">'.$data->anio.'</td>
                                            </tr>';
                                    }
                                    ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- AMPUTACIÓN -->
                <div class="row py-3 borderbuttonb">
                    <div class="col-lg-2">
                        <p class="p-1 bgtransparent1 text-dark font14 m-1"><?= 'Amputación' ?></p>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-control bg-disabled ">
                            <?php
                                    $ret = '&nbsp;';
                                    if($model->amputacion){
                                        $ret .= $model->amputacion;
                                    }
                                    echo $ret;?>
                        </div>
                    </div>
                    <?php 
                    $show = 'none';
                    if($model->amputacion == 'SI'){
                        $show = 'block';
                    }
                    ?>
                    <div class="col-lg-7 px-0" id='amputaciondata' style="display:<?php echo $show;?>;">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th class="control-label font500" width="5%">#</th>
                                    <th class="control-label font500" width="75%">Diagnóstico</th>
                                    <th class="control-label font500" width="20%">Año de Inicio</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    foreach($model->dAmputacion as $key=>$data){
                                        echo '<tr>
                                            <td class="t5 " width="5%">'.($key+1).'</td>
                                            <td class="t5 " width="75%">'.$data->descripcion.'</td>
                                            <td class="t5 color3" width="50%">'.$data->anio.'</td>
                                            </tr>';
                                    }
                                    ?>
                            </tbody>
                        </table>
                    </div>
                </div>


                <!-- HERNIAS -->
                <div class="row py-3 borderbuttonb">
                    <div class="col-lg-2">
                        <p class="p-1 bgtransparent1 text-dark font14 m-1"><?= 'Hernias' ?></p>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-control bg-disabled ">
                            <?php
                                    $ret = '&nbsp;';
                                    if($model->hernias){
                                        $ret .= $model->hernias;
                                    }
                                    echo $ret;?>
                        </div>
                    </div>
                    <?php 
                    $show = 'none';
                    if($model->hernias == 'SI'){
                        $show = 'block';
                    }
                    ?>
                    <div class="col-lg-7 px-0" id='herniasdata' style="display:<?php echo $show;?>;">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th class="control-label font500" width="5%">#</th>
                                    <th class="control-label font500" width="75%">Diagnóstico</th>
                                    <th class="control-label font500" width="20%">Año de Inicio</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    foreach($model->dHernias as $key=>$data){
                                        echo '<tr>
                                            <td class="t5 " width="5%">'.($key+1).'</td>
                                            <td class="t5 " width="75%">'.$data->descripcion.'</td>
                                            <td class="t5 color3" width="50%">'.$data->anio.'</td>
                                            </tr>';
                                    }
                                    ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- ENFERMEDADES SANGUÍNEAS / INMUNOLÓGICA: ANEMIA/VIH -->
                <div class="row py-3 borderbuttonb">
                    <div class="col-lg-2">
                        <p class="p-1 bgtransparent1 text-dark font14 m-1">
                            <?= 'Enfermedades Sanguíneas/inmunológica: Anemia/VIH' ?></p>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-control bg-disabled ">
                            <?php
                                    $ret = '&nbsp;';
                                    if($model->enfsanguinea){
                                        $ret .= $model->enfsanguinea;
                                    }
                                    echo $ret;?>
                        </div>
                    </div>
                    <?php 
                    $show = 'none';
                    if($model->enfsanguinea == 'SI'){
                        $show = 'block';
                    }
                    ?>
                    <div class="col-lg-7 px-0" id='enfsanguineadata' style="display:<?php echo $show;?>;">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th class="control-label font500" width="5%">#</th>
                                    <th class="control-label font500" width="75%">Diagnóstico</th>
                                    <th class="control-label font500" width="20%">Año de Inicio</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    foreach($model->dSanguineas as $key=>$data){
                                        echo '<tr>
                                            <td class="t5 " width="5%">'.($key+1).'</td>
                                            <td class="t5 " width="75%">'.$data->descripcion.'</td>
                                            <td class="t5 color3" width="50%">'.$data->anio.'</td>
                                            </tr>';
                                    }
                                    ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- TUMORES/CANCER -->
                <div class="row py-3 borderbuttonb">
                    <div class="col-lg-2">
                        <p class="p-1 bgtransparent1 text-dark font14 m-1"><?= 'Tumores/Cáncer' ?></p>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-control bg-disabled ">
                            <?php
                                    $ret = '&nbsp;';
                                    if($model->tumorescancer){
                                        $ret .= $model->tumorescancer;
                                    }
                                    echo $ret;?>
                        </div>
                    </div>
                    <?php 
                    $show = 'none';
                    if($model->tumorescancer == 'SI'){
                        $show = 'block';
                    }
                    ?>
                    <div class="col-lg-7 px-0" id='tumorescancerdata' style="display:<?php echo $show;?>;">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th class="control-label font500" width="5%">#</th>
                                    <th class="control-label font500" width="75%">Diagnóstico</th>
                                    <th class="control-label font500" width="20%">Año de Inicio</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    foreach($model->dTumores as $key=>$data){
                                        echo '<tr>
                                            <td class="t5 " width="5%">'.($key+1).'</td>
                                            <td class="t5 " width="75%">'.$data->descripcion.'</td>
                                            <td class="t5 color3" width="50%">'.$data->anio.'</td>
                                            </tr>';
                                    }
                                    ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- ENFERMEDADES PSICOLÓGICAS/PSIQUIÁTRICAS -->
                <div class="row py-3 borderbuttonb">
                    <div class="col-lg-2">
                        <p class="p-1 bgtransparent1 text-dark font14 m-1">
                            <?= 'Enfermedades Psicológicas/Psiquiátricas' ?>
                        </p>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-control bg-disabled ">
                            <?php
                                    $ret = '&nbsp;';
                                    if($model->enfpsico){
                                        $ret .= $model->enfpsico;
                                    }
                                    echo $ret;?>
                        </div>
                    </div>
                    <?php 
                    $show = 'none';
                    if($model->enfpsico == 'SI'){
                        $show = 'block';
                    }
                    ?>
                    <div class="col-lg-7 px-0" id='enfpsicodata' style="display:<?php echo $show;?>;">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th class="control-label font500" width="5%">#</th>
                                    <th class="control-label font500" width="75%">Diagnóstico</th>
                                    <th class="control-label font500" width="20%">Año de Inicio</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    foreach($model->dPsicologicas as $key=>$data){
                                        echo '<tr>
                                            <td class="t5 " width="5%">'.($key+1).'</td>
                                            <td class="t5 " width="75%">'.$data->descripcion.'</td>
                                            <td class="t5 color3" width="50%">'.$data->anio.'</td>
                                            </tr>';
                                    }
                                    ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            <?php 
            $show = 'none';
            if($model->sexo == '2'){
                $show = 'block';
            }
            ?>

            <div class="container-fluid my-3 border30 bg-light p-4" id="antecedentesgineco"
                style="display:<?php echo $show;?>;">
                <div class="row m-0 p-0">
                    <div class="col-lg-4 title2 boxtitle p-1 rounded-3 color3 my-3">
                        <label class="">
                            <span class="mx-2"><?php echo  $iconclip;?></span>
                            Antecedentes Gineco Obstétricos
                        </label>
                    </div>
                </div>

                <div class="from-group" id="gineco_obstetrico">
                    <div class="row">
                        <div class="col-lg-1">
                            <p class="color9 font-600"><?= 'Gestas' ?></p>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-control bg-disabled ">
                                <?php
                                    $ret = '&nbsp;';
                                    $ret .= $model->gestas;
                                    echo $ret;?>
                            </div>
                        </div>
                        <div class="col-lg-1">
                            <p class="color9 font-600"><?= 'Partos' ?></p>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-control bg-disabled ">
                                <?php
                                    $ret = '&nbsp;';
                                    $ret .= $model->partos;
                                    echo $ret;?>
                            </div>
                        </div>
                        <div class="col-lg-1">
                            <p class="color9 font-600"><?= 'Abortos' ?></p>
                        </div>

                        <div class="col-lg-2">
                            <div class="form-control bg-disabled ">
                                <?php
                                    $ret = '&nbsp;';
                                    $ret .= $model->abortos;
                                    echo $ret;?>
                            </div>
                        </div>

                        <div class="col-lg-1">
                            <p class="color9 font-600"><?= 'Cesareas' ?></p>
                        </div>

                        <div class="col-lg-2">
                            <div class="form-control bg-disabled ">
                                <?php
                                    $ret = '&nbsp;';
                                    $ret .= $model->cesareas;
                                    echo $ret;?>
                            </div>
                        </div>
                    </div>

                    <div class="row my-3">
                        <div class="col-lg-1">
                            <p class="color9 font-600"><?= 'Menarca'.' (Años)' ?></p>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-control bg-disabled ">
                                <?php
                                    $ret = '&nbsp;';
                                    $ret .= $model->menarca;
                                    echo $ret;?>
                            </div>
                        </div>
                        <div class="col-lg-1">
                            <p class="color9 font-600"><?= 'IVSA'.' (Años)' ?></p>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-control bg-disabled ">
                                <?php
                                    $ret = '&nbsp;';
                                    $ret .= $model->ivsa;
                                    echo $ret;?>
                            </div>
                        </div>
                        <div class="col-lg-1">
                            <p class="color9 font-600"><?= 'FUM' ?></p>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-control bg-disabled ">
                                <?php
                                    $ret = '&nbsp;';
                                    $ret .= $model->fum;
                                    echo $ret;?>
                            </div>
                        </div>
                    </div>



                    <div class="row my-3">
                        <div class="col-lg-1">
                            <p class="color9 font-600"><?= 'MPF' ?></p>
                        </div>
                        <div class="col-lg-5">
                            <div class="form-control bg-disabled ">
                                <?php
                                    $ret = '&nbsp;';
                                    $ret .= $AGO_mpf;
                                    echo $ret;?>
                            </div>
                        </div>
                        <div class="col-lg-1">
                            <p class="color9 font-600"> <?= 'DOC' ?></p>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-control bg-disabled ">
                                <?php
                                    $ret = '&nbsp;';
                                    $ret .= $AGO_doc;
                                    echo $ret;?>
                            </div>
                        </div>
                        <div class="col-lg-1">
                            <p class="color9 font-600"><?= 'DOCMA' ?></p>
                        </div>

                        <div class="col-lg-2">
                            <div class="form-control bg-disabled ">
                                <?php
                                    $ret = '&nbsp;';
                                    $ret .= $AGO_docma;
                                    echo $ret;?>
                            </div>
                        </div>
                    </div>
                </div>

            </div>


            <div class="container-fluid my-3 border30 bg-light p-4">
                <div class="row m-0 p-0">
                    <div class="col-lg-4 title2 boxtitle p-1 rounded-3 color3 my-3">
                        <label class="">
                            <span class="mx-2"><?php echo  $iconclip;?></span>
                            Antecedentes Laborales
                        </label>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-2">
                        <div class="form-control bg-disabled ">
                            <?php
                                    $ret = '&nbsp;';

                                    if($model->antecedentes_sino == 1){
                                        $ret .= 'SI';
                                    } else if($model->antecedentes_sino == 2){
                                        $ret .= 'NO';
                                    }
                                    
                                    echo $ret;?>
                        </div>
                    </div>
                </div>

                <?php 
                $show = 'none';
                if($model->antecedentes_sino == 1){
                    $show = 'block';
                }
                ?>
                <div class="from-group mt-3" id="antecedentes_laborales" style="display:<?php echo $show;?>;">
                    <div class="row">
                        <div class="col-lg-2">
                            <p class="color9 font-600"><?= 'Edad Inicio a Laborar (Años)' ?></p>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-control bg-disabled ">
                                <?php
                                    $ret = '&nbsp;';
                                    $ret .= $model->inicio_laboral;
                                    echo $ret;?>
                            </div>
                        </div>
                        <div class="col-lg-1">
                            <p class="color9 font-600 text-end"><?= 'Área' ?></p>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-control bg-disabled ">
                                <?php
                                    $ret = '&nbsp;';
                                    $ret .= $model->antlaboral_area;
                                    echo $ret;?>
                            </div>
                        </div>
                        <div class="col-lg-1">
                            <p class="color9 font-600 text-end"><?= 'Puesto' ?></p>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-control bg-disabled ">
                                <?php
                                    $ret = '&nbsp;';
                                    $ret .= $model->antlaboral_puesto;
                                    echo $ret;?>
                            </div>
                        </div>
                        <div class="col-lg-2 mt-2">
                            <p class="color9 font-600"><?= 'Antigüedad en el Puesto' ?></p>
                        </div>
                        <div class="col-lg-2 mt-2">
                            <div class="form-control bg-disabled ">
                                <?php
                                    $ret = '&nbsp;';
                                    $ret .= $EXPLAB_antiguedad;
                                    echo $ret;?>
                            </div>
                        </div>
                    </div>


                    <div class="row py-3 border-bottom border-primary">
                        <div class="col-lg-3 px-0">
                            <p class="p-1 bgtransparent1 text-dark font14 m-1"><?= 'Actividad Actual' ?></p>
                        </div>
                        <div class="col-lg-9"></div>
                        <div class="col-lg-6 mt-2">
                            <div class="row">
                                <div class="col-lg-4">
                                    <p class="color9 font-600"><?= 'Actividad Actual(E.M.P)' ?></p>
                                </div>
                                <div class="col-lg-8">
                                    <div class="form-control bg-disabled ">
                                        <?php
                                        $ret = '&nbsp;';
                                        $ret .= $model->laboral0_actividad;
                                        echo $ret;?>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <p class="color9 font-600"><?= 'Tiempo de Exposición' ?></p>
                                </div>
                                <div class="col-lg-8">
                                    <div class="form-control bg-disabled ">
                                        <?php
                                        $ret = '&nbsp;';
                                        $ret .= $EXPLAB_0tiempoexposicion;
                                        echo $ret;?>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <p class="color9 font-600"><?= 'Equipo de Protección' ?></p>
                                </div>
                                <div class="col-lg-8">
                                    <div class="form-control bg-disabled ">
                                        <?php
                                        $ret = '&nbsp;';
                                        $ret .= $model->laboral0_epp;
                                        echo $ret;?>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <p class="color9 font-600"><?= '%IPP Accidente o Enf.Prof' ?></p>
                                </div>
                                <div class="col-lg-8">
                                    <div class="form-control bg-disabled ">
                                        <?php
                                        $ret = '&nbsp;';
                                        $ret .= $model->laboral0_ipp;
                                        echo $ret;?>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <p class="color9 font-600"><?= 'Fecha de periodo laboral' ?></p>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-control bg-disabled ">
                                        <?php
                                        $ret = '&nbsp;';
                                        $ret .= $model->laboral0_desde;
                                        echo $ret;?>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="col-lg-6 mt-2">
                            <div class="col-lg-12">
                                <p class="color9 font-600 text-center"><?= 'Exposición' ?></p>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-control bg-disabled ">
                                    <?php
                                        $ret = '&nbsp;';
                                        $ret .= $EXPLAB_0exposicion;
                                        echo $ret;?>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row py-3 border-bottom border-primary">
                        <div class="col-lg-3 px-0">
                            <p class="p-1 bgtransparent1 text-dark font14 m-1"><?= 'Actividad Anterior 1' ?></p>
                        </div>
                        <div class="col-lg-9"></div>
                        <div class="col-lg-6 mt-2">
                            <div class="row">
                                <div class="col-lg-4">
                                    <p class="color9 font-600"><?= 'Actividad Anterior 1' ?></p>
                                </div>
                                <div class="col-lg-8">
                                    <div class="form-control bg-disabled ">
                                        <?php
                                        $ret = '&nbsp;';
                                        $ret .= $model->laboral1_actividad;
                                        echo $ret;?>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <p class="color9 font-600"><?= 'Tiempo de Exposición' ?></p>
                                </div>
                                <div class="col-lg-8">
                                    <div class="form-control bg-disabled ">
                                        <?php
                                        $ret = '&nbsp;';
                                        $ret .= $EXPLAB_1tiempoexposicion;
                                        echo $ret;?>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <p class="color9 font-600"><?= 'Equipo de Protección' ?></p>
                                </div>
                                <div class="col-lg-8">
                                    <div class="form-control bg-disabled ">
                                        <?php
                                        $ret = '&nbsp;';
                                        $ret .= $model->laboral1_epp;
                                        echo $ret;?>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <p class="color9 font-600"><?= '%IPP Accidente o Enf.Prof' ?></p>
                                </div>
                                <div class="col-lg-8">
                                    <div class="form-control bg-disabled ">
                                        <?php
                                        $ret = '&nbsp;';
                                        $ret .= $model->laboral1_ipp;
                                        echo $ret;?>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <p class="color9 font-600"><?= 'Fecha de periodo laboral' ?></p>
                                </div>
                                <div class="col-lg-4">
                                    <label
                                        class="control-label"><?= $model->getAttributeLabel('laboral1_desde');?></label>
                                    <div class="form-control bg-disabled ">
                                        <?php
                                        $ret = '&nbsp;';
                                        $ret .= $model->laboral1_desde;
                                        echo $ret;?>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <label
                                        class="control-label"><?= $model->getAttributeLabel('laboral1_hasta');?></label>
                                    <div class="form-control bg-disabled ">
                                        <?php
                                        $ret = '&nbsp;';
                                        $ret .= $model->laboral1_hasta;
                                        echo $ret;?>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="col-lg-6 mt-2">
                            <div class="col-lg-12">
                                <p class="color9 font-600 text-center"><?= 'Exposición' ?></p>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-control bg-disabled ">
                                    <?php
                                        $ret = '&nbsp;';
                                        $ret .= $EXPLAB_1exposicion;
                                        echo $ret;?>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row py-3 border-bottom border-primary">
                        <div class="col-lg-3 px-0">
                            <p class="p-1 bgtransparent1 text-dark font14 m-1"><?= 'Actividad Anterior 2' ?></p>
                        </div>
                        <div class="col-lg-9"></div>
                        <div class="col-lg-6 mt-2">
                            <div class="row">
                                <div class="col-lg-4">
                                    <p class="color9 font-600"><?= 'Actividad Anterior 2' ?></p>
                                </div>
                                <div class="col-lg-8">
                                    <div class="form-control bg-disabled ">
                                        <?php
                                        $ret = '&nbsp;';
                                        $ret .= $model->laboral2_actividad;
                                        echo $ret;?>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <p class="color9 font-600"><?= 'Tiempo de Exposición' ?></p>
                                </div>
                                <div class="col-lg-8">
                                    <div class="form-control bg-disabled ">
                                        <?php
                                        $ret = '&nbsp;';
                                        $ret .= $EXPLAB_2tiempoexposicion;
                                        echo $ret;?>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <p class="color9 font-600"><?= 'Equipo de Protección' ?></p>
                                </div>
                                <div class="col-lg-8">
                                    <div class="form-control bg-disabled ">
                                        <?php
                                        $ret = '&nbsp;';
                                        $ret .= $model->laboral2_epp;
                                        echo $ret;?>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <p class="color9 font-600"><?= '%IPP Accidente o Enf.Prof' ?></p>
                                </div>
                                <div class="col-lg-8">
                                    <div class="form-control bg-disabled ">
                                        <?php
                                        $ret = '&nbsp;';
                                        $ret .= $model->laboral2_ipp;
                                        echo $ret;?>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <p class="color9 font-600"><?= 'Fecha de periodo laboral' ?></p>
                                </div>
                                <div class="col-lg-4">
                                    <label
                                        class="control-label"><?= $model->getAttributeLabel('laboral2_desde');?></label>
                                    <div class="form-control bg-disabled ">
                                        <?php
                                        $ret = '&nbsp;';
                                        $ret .= $model->laboral2_desde;
                                        echo $ret;?>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <label
                                        class="control-label"><?= $model->getAttributeLabel('laboral2_hasta');?></label>
                                    <div class="form-control bg-disabled ">
                                        <?php
                                        $ret = '&nbsp;';
                                        $ret .= $model->laboral2_hasta;
                                        echo $ret;?>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="col-lg-6 mt-2">
                            <div class="col-lg-12">
                                <p class="color9 font-600 text-center"><?= 'Exposición' ?></p>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-control bg-disabled ">
                                    <?php
                                        $ret = '&nbsp;';
                                        $ret .= $EXPLAB_2exposicion;
                                        echo $ret;?>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row py-3 border-bottom border-primary">
                        <div class="col-lg-3 px-0">
                            <p class="p-1 bgtransparent1 text-dark font14 m-1"><?= 'Actividad Anterior 3' ?></p>
                        </div>
                        <div class="col-lg-9"></div>
                        <div class="col-lg-6 mt-2">
                            <div class="row">
                                <div class="col-lg-4">
                                    <p class="color9 font-600"><?= 'Actividad Anterior 3' ?></p>
                                </div>
                                <div class="col-lg-8">
                                    <div class="form-control bg-disabled ">
                                        <?php
                                        $ret = '&nbsp;';
                                        $ret .= $model->laboral3_actividad;
                                        echo $ret;?>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <p class="color9 font-600"><?= 'Tiempo de Exposición' ?></p>
                                </div>
                                <div class="col-lg-8">
                                    <div class="form-control bg-disabled ">
                                        <?php
                                        $ret = '&nbsp;';
                                        $ret .= $EXPLAB_3tiempoexposicion;
                                        echo $ret;?>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <p class="color9 font-600"><?= 'Equipo de Protección' ?></p>
                                </div>
                                <div class="col-lg-8">
                                    <div class="form-control bg-disabled ">
                                        <?php
                                        $ret = '&nbsp;';
                                        $ret .= $model->laboral3_epp;
                                        echo $ret;?>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <p class="color9 font-600"><?= '%IPP Accidente o Enf.Prof' ?></p>
                                </div>
                                <div class="col-lg-8">
                                    <div class="form-control bg-disabled ">
                                        <?php
                                        $ret = '&nbsp;';
                                        $ret .= $model->laboral3_ipp;
                                        echo $ret;?>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <p class="color9 font-600"><?= 'Fecha de periodo laboral' ?></p>
                                </div>
                                <div class="col-lg-4">
                                    <label
                                        class="control-label"><?= $model->getAttributeLabel('laboral3_desde');?></label>
                                    <div class="form-control bg-disabled ">
                                        <?php
                                        $ret = '&nbsp;';
                                        $ret .= $model->laboral3_desde;
                                        echo $ret;?>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <label
                                        class="control-label"><?= $model->getAttributeLabel('laboral3_hasta');?></label>
                                    <div class="form-control bg-disabled ">
                                        <?php
                                        $ret = '&nbsp;';
                                        $ret .= $model->laboral3_hasta;
                                        echo $ret;?>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="col-lg-6 mt-2">
                            <div class="col-lg-12">
                                <p class="color9 font-600 text-center"><?= 'Exposición' ?></p>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-control bg-disabled ">
                                    <?php
                                        $ret = '&nbsp;';
                                        $ret .= $EXPLAB_3exposicion;
                                        echo $ret;?>
                                </div>
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
                            <div class="form-control bg-disabled ">
                                <?php
                                        $ret = '&nbsp;';
                                        $ret .= $model->peso;
                                        echo $ret;?>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <label class="control-label"><?= $model->getAttributeLabel('talla');?></label>
                            <div class="form-control bg-disabled ">
                                <?php
                                        $ret = '&nbsp;';
                                        $ret .= $model->talla;
                                        echo $ret;?>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <label class="control-label"><?= $model->getAttributeLabel('imc');?></label>
                            <div class="form-control bg-disabled ">
                                <?php
                                        $ret = '&nbsp;';
                                        $ret .= $model->imc;
                                        echo $ret;?>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <label class="control-label"><?= $model->getAttributeLabel('categoria_imc');?></label>
                            <div class="form-control bg-disabled ">
                                <?php
                                        $ret = '&nbsp;';
                                        $ret .= $model->categoria_imc;
                                        echo $ret;?>
                            </div>
                        </div>
                    </div>
                    <div class="row my-3">
                        <div class="col-lg-2">
                            <label class="control-label"><?= $model->getAttributeLabel('fc');?></label>
                            <div class="form-control bg-disabled ">
                                <?php
                                        $ret = '&nbsp;';
                                        $ret .= $model->fc;
                                        echo $ret;?>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <label class="control-label"><?= $model->getAttributeLabel('fr');?></label>
                            <div class="form-control bg-disabled ">
                                <?php
                                        $ret = '&nbsp;';
                                        $ret .= $model->fr;
                                        echo $ret;?>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <label class="control-label"><?= $model->getAttributeLabel('temp');?></label>
                            <div class="form-control bg-disabled ">
                                <?php
                                        $ret = '&nbsp;';
                                        $ret .= $model->temp;
                                        echo $ret;?>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <label class="control-label"><?= $model->getAttributeLabel('ta');?></label>
                            <div class="form-control bg-disabled ">
                                <?php
                                        $ret = '&nbsp;';
                                        $ret .= $model->ta;
                                        echo $ret;?>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <label class="control-label"><?= $model->getAttributeLabel('ta_diastolica');?></label>
                            <div class="form-control bg-disabled ">
                                <?php
                                        $ret = '&nbsp;';
                                        $ret .= $model->ta_diastolica;
                                        echo $ret;?>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <label class="control-label"><?= $model->getAttributeLabel('caries_rd');?></label>
                            <div class="form-control bg-disabled ">
                                <?php
                                        $ret = '&nbsp;';
                                        $ret .= $model->caries_rd;
                                        echo $ret;?>
                            </div>
                        </div>
                    </div>
                    <div class="row my-3">
                        <div class="col-lg-2">
                            <label class="control-label"><?= $model->getAttributeLabel('pso2');?></label>
                            <div class="form-control bg-disabled ">
                                <?php
                                        $ret = '&nbsp;';
                                        $ret .= $model->pso2;
                                        echo $ret;?>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-5 py-3 border-bottom border-primary">
                        <div class="col-lg-2">
                            <p class="color9 font-600"><?= 'Inspección General' ?></p>
                        </div>
                        <div class="col-lg-7">
                            <div class="form-control bg-disabled ">
                                <?php
                                        $ret = '&nbsp;';
                                        $ret .= $EXP_inspecciong;
                                        echo $ret;?>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="row">
                                <div class="col-lg-12">
                                </div>
                                <?php
                                $show = 'none';
                                if($model->inspeccion_otros == 1){
                                    $show = 'block';
                                }
                                ?>
                                <div class="col-lg-12" id="txt_inspeccion_otros" style="display:<?php echo $show;?>;">
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="row mt-5 py-3 border-bottom border-primary">
                        <div class="col-lg-2">
                            <p class="color9 font-600"><?= 'Cabeza' ?></p>
                        </div>
                        <div class="col-lg-7">
                            <div class="form-control bg-disabled ">
                                <?php
                                        $ret = '&nbsp;';
                                        $ret .= $EXP_cabeza;
                                        echo $ret;?>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="row">
                                <div class="col-lg-12">

                                </div>
                                <?php
                                $show = 'none';
                                if($model->cabeza_otros == 1){
                                    $show = 'block';
                                }
                                ?>
                                <div class="col-lg-12" id="txt_cabeza_otros" style="display:<?php echo $show;?>;">

                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="row mt-5 py-3 border-bottom border-primary">
                        <div class="col-lg-2">
                            <p class="color9 font-600"><?= 'Oidos' ?></p>
                        </div>
                        <div class="col-lg-7">
                            <div class="form-control bg-disabled ">
                                <?php
                                        $ret = '&nbsp;';
                                        $ret .= $EXP_oidos;
                                        echo $ret;?>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="row">
                                <div class="col-lg-12">

                                </div>
                                <?php
                                $show = 'none';
                                if($model->oidos_otros == 1){
                                    $show = 'block';
                                }
                                ?>
                                <div class="col-lg-12" id="txt_oidos_otros" style="display:<?php echo $show;?>;">

                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="row mt-5 py-3 border-bottom border-primary">
                        <div class="col-lg-2">
                            <p class="color9 font-600"><?= 'Ojos/Cara' ?></p>
                        </div>
                        <div class="col-lg-7">
                            <div class="form-control bg-disabled ">
                                <?php
                                        $ret = '&nbsp;';
                                        $ret .= $EXP_ojoscara;
                                        echo $ret;?>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="row">
                                <div class="col-lg-12">

                                </div>
                                <?php
                                $show = 'none';
                                if($model->ojos_otros == 1){
                                    $show = 'block';
                                }
                                ?>
                                <div class="col-lg-12" id="txt_ojos_otros" style="display:<?php echo $show;?>;">

                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="row mt-5">
                        <div class="col-lg-2">
                            <p class="color9 font-600"><?= 'Agudeza Visual sin Lentes' ?></p>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-control bg-disabled ">
                                <?php
                                    $ret = '&nbsp;';
                                    if($model->sLentes != null && $model->sLentes != '' && $model->sLentes!= ' '){
                                        $ret .= '20/'.$model->sLentes;
                                    }
                                    echo $ret;?>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-control bg-disabled ">
                                <?php
                                    $ret = '&nbsp;';
                                    if($model->sLentesD != null && $model->sLentesD != '' && $model->sLentesD!= ' '){
                                        $ret .= '20/'.$model->sLentesD;
                                    }
                                    echo $ret;?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-2">
                            <p class="color9 font-600"><?= 'Agudeza Visual con Lentes' ?></p>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-control bg-disabled ">
                                <?php
                                    $ret = '&nbsp;';
                                    if($model->cLentes != null && $model->cLentes != '' && $model->cLentes!= ' '){
                                        $ret .= '20/'.$model->cLentes;
                                    }
                                    echo $ret;?>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-control bg-disabled ">
                                <?php
                                    $ret = '&nbsp;';
                                    if($model->cLentesD != null && $model->cLentesD != '' && $model->cLentesD!= ' '){
                                        $ret .= '20/'.$model->cLentesD;
                                    }
                                    echo $ret;?>
                            </div>
                        </div>
                    </div>
                    <div class="row py-3 border-bottom border-primary">
                        <div class="col-lg-2">
                            <p class="color9 font-600"><?= '¿Requiere Lentes Graduados?' ?></p>
                        </div>
                        <div class="col-lg-1">
                            <div class="form-control bg-disabled ">
                                <?php
                                    $ret = '&nbsp;';
                                    $ret .= $model->Rlentes;
                                    echo $ret;?>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <p class="color9 font-600"><?= '¿Cuenta con Lentes Graduados?' ?></p>
                        </div>
                        <div class="col-lg-1">
                            <div class="form-control bg-disabled ">
                                <?php
                                    $ret = '&nbsp;';
                                    $ret .= $model->Ulentes;
                                    echo $ret;?>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-5 py-3 border-bottom border-primary">
                        <div class="col-lg-2">
                            <p class="color9 font-600"><?= 'Boca/Faringe' ?></p>
                        </div>

                        <div class="col-lg-7">
                            <div class="form-control bg-disabled ">
                                <?php
                                        $ret = '&nbsp;';
                                        $ret .= $EXP_boca;
                                        echo $ret;?>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="row">
                                <div class="col-lg-12">
                                </div>
                                <?php
                                $show = 'none';
                                if($model->boca_otros == 1){
                                    $show = 'block';
                                }
                                ?>
                                <div class="col-lg-12" id="txt_boca_otros" style="display:<?php echo $show;?>;">

                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="row mt-5 py-3 border-bottom border-primary">
                        <div class="col-lg-2">
                            <p class="color9 font-600"><?= 'Cuello' ?></p>
                        </div>
                        <div class="col-lg-7">
                            <div class="form-control bg-disabled ">
                                <?php
                                        $ret = '&nbsp;';
                                        $ret .= $EXP_cuello;
                                        echo $ret;?>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="row">
                                <div class="col-lg-12">

                                </div>
                                <?php
                                $show = 'none';
                                if($model->cuello_otros == 1){
                                    $show = 'block';
                                }
                                ?>
                                <div class="col-lg-12" id="txt_cuello_otros" style="display:<?php echo $show;?>;">

                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="row mt-5 py-3 border-bottom border-primary">
                        <div class="col-lg-2">
                            <p class="color9 font-600"><?= 'Torax' ?></p>
                        </div>
                        <div class="col-lg-7">
                            <div class="form-control bg-disabled ">
                                <?php
                                        $ret = '&nbsp;';
                                        $ret .= $EXP_torax;
                                        echo $ret;?>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="row">
                                <div class="col-lg-12">

                                </div>
                                <?php
                                $show = 'none';
                                if($model->torax_otros == 1){
                                    $show = 'block';
                                }
                                ?>
                                <div class="col-lg-12" id="txt_torax_otros" style="display:<?php echo $show;?>;">

                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="row mt-5 py-3 border-bottom border-primary">
                        <div class="col-lg-2">
                            <p class="color9 font-600"><?= 'Abdomen' ?></p>
                        </div>
                        <div class="col-lg-7">
                            <div class="form-control bg-disabled ">
                                <?php
                                        $ret = '&nbsp;';
                                        $ret .= $EXP_abdomen;
                                        echo $ret;?>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="row">
                                <div class="col-lg-12">

                                </div>
                                <?php
                                $show = 'none';
                                if($model->abdomen_otros == 1){
                                    $show = 'block';
                                }
                                ?>
                                <div class="col-lg-12" id="txt_abdomen_otros" style="display:<?php echo $show;?>;">

                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="row mt-5 py-3 border-bottom border-primary">
                        <div class="col-lg-2">
                            <p class="color9 font-600"><?= 'Miembros Superiores' ?></p>
                        </div>
                        <div class="col-lg-8">
                            <div class="form-control bg-disabled ">
                                <?php
                                        $ret = '&nbsp;';
                                        $ret .= $EXP_miembrossup;
                                        echo $ret;?>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="row">
                                <div class="col-lg-12">

                                </div>
                                <?php
                                $show = 'none';
                                if($model->miembrossup_otros == 1){
                                    $show = 'block';
                                }
                                ?>
                                <div class="col-lg-12" id="txt_miembrossup_otros" style="display:<?php echo $show;?>;">

                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="row mt-5 py-3 border-bottom border-primary">
                        <div class="col-lg-2">
                            <p class="color9 font-600"><?= 'Miembros Inferiores' ?></p>
                        </div>
                        <div class="col-lg-8">
                            <div class="form-control bg-disabled ">
                                <?php
                                        $ret = '&nbsp;';
                                        $ret .= $EXP_miembrosinf;
                                        echo $ret;?>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="row">
                                <div class="col-lg-12">

                                </div>
                                <?php
                                $show = 'none';
                                if($model->miembrosinf_otros == 1){
                                    $show = 'block';
                                }
                                ?>
                                <div class="col-lg-12" id="txt_miembrosinf_otros" style="display:<?php echo $show;?>;">

                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="row mt-5 py-3 border-bottom border-primary">
                        <div class="col-lg-2">
                            <p class="color9 font-600"><?= 'Columna' ?></p>
                        </div>
                        <div class="col-lg-8">
                            <div class="form-control bg-disabled ">
                                <?php
                                        $ret = '&nbsp;';
                                        $ret .= $EXP_columna;
                                        echo $ret;?>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="row">
                                <div class="col-lg-12">

                                </div>
                                <?php
                                $show = 'none';
                                if($model->columna_otros == 1){
                                    $show = 'block';
                                }
                                ?>
                                <div class="col-lg-12" id="txt_columna_otros" style="display:<?php echo $show;?>;">

                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="row mt-5 py-3 border-bottom border-primary">
                        <div class="col-lg-2">
                            <p class="color9 font-600"><?= 'Neurológicos' ?></p>
                        </div>
                        <div class="col-lg-8">
                            <div class="form-control bg-disabled ">
                                <?php
                                        $ret = '&nbsp;';
                                        $ret .= $EXP_neurologicos;
                                        echo $ret;?>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="row">
                                <div class="col-lg-12">

                                </div>
                                <?php
                                $show = 'none';
                                if($model->neurologicos_otros == 1){
                                    $show = 'block';
                                }
                                ?>
                                <div class="col-lg-12" id="txt_neurologicos_otros" style="display:<?php echo $show;?>;">

                                </div>
                            </div>

                        </div>
                    </div>
                </div>


                <div class="container-fluid my-3 border30 bg-light p-4">
                    <div class="row m-0 p-0">
                        <div class="col-lg-4 title2 boxtitle p-1 rounded-3 color3 my-3">
                            <label class="">
                                <span class="mx-2"><?php echo  $iconclip;?></span>
                                Estudios Complementarios
                            </label>
                        </div>
                    </div>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th class="control-label font500" width="5%">#</th>
                                <th class="control-label font500" width="30%">Estudio</th>
                                <th class="control-label font500" width="15%">Categoria</th>
                                <th class="control-label font500" width="5%">Doc.</th>
                                <th class="control-label font500" width="25%">Conclusión</th>
                                <th class="control-label font500" width="20%">Comentarios Clínicos</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                foreach($model->testudios as $key=>$estudio){
                                    $est_categoria = '';
                                    $est_estudio = '';
                                    $est_conclusion = '';
                                    $est_comentarios = $estudio->comentario;
        
                                    if (array_key_exists($estudio->conclusion, $array_conclusion)) {
                                        $est_conclusion = $array_conclusion[$estudio->conclusion];
                                    }
        
                                    if($estudio->categoria){
                                        $est_categoria = $estudio->categoria->nombre;
                                    }
        
                                    if($estudio->estudio){
                                        $est_estudio = $estudio->estudio->nombre;
                                    }

                                    $pdf = '';
                                    $image = '<span class="" style="font-size:30px"><i class="bi bi-file-earmark-pdf"></i></span>';
                                    if($estudio->evidencia != '' && $estudio->evidencia != null){
                                        $filePath = 'resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id_trabajador.'/Documentos/'.$estudio->evidencia;
                                        $pdf = Html::a('<span style="font-size:30px;" class="color1">'.$image.'</span>', $filePath, $options = ['target'=>'_blank']);
                                    }
                                    
                                    echo '<tr>
                                            <td class="t5 " width="5%">'.($key+1).'</td>
                                            <td class="t5 " width="30%">'.$est_estudio.'</td>
                                            <td class="t5 " width="15%">'.$est_categoria.'</td>
                                            <td class="t5 " width="5%">'.$pdf.'</td>
                                            <td class="t5 " width="25%">'.$est_conclusion.'</td>
                                            <td class="t5 color3" width="20%">'.$est_comentarios.'</td>
                                        </tr>';
                                }
                                ?>
                        </tbody>
                    </table>
                </div>



                <div class="container-fluid my-3 border30 bg-light p-4">
                    <div class="row m-0 p-0">
                        <div class="col-lg-4 title2 boxtitle p-1 rounded-3 color3 my-3">
                            <label class="">
                                <span class="mx-2"><?php echo  $iconclip;?></span>
                                Conclusión
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <label class="control-label"><?= $model->getAttributeLabel('diagnostico');?></label>
                        <p class="form-control bg-disabled ">
                            <?php
                                        $ret = '&nbsp;';
                                        $ret .= $model->diagnostico;
                                        echo $ret;?>
                        </p>
                    </div>
                    <div class="col-lg-12">
                        <label class="control-label"><?= $model->getAttributeLabel('comentarios');?></label>
                        <p class="form-control bg-disabled ">
                            <?php
                                        $ret = '&nbsp;';
                                        $ret .= $model->comentarios;
                                        echo $ret;?>
                        </p>
                    </div>
                    <div class="col-lg-12 my-3">
                        <p class="p-2 bgtransparent1 text-dark font14 m-1">Puesto de Trabajo:
                            <span id="datapuesto" class="font600">
                                <?php
                                if($model->puestodata){
                                    echo $model->puestodata->nombre;
                                }
                                ?>
                            </span>
                        </p>
                    </div>
                    <div class="col-lg-12 my-3">
                        <label class="control-label"><?= $model->getAttributeLabel('conclusion');?></label>
                        <p class="form-control bg-disabled ">
                            <?php
                                $ret = '&nbsp;';
                                if (isset($model->conclusion) && $model->conclusion != null && $model->conclusion != ''){
                                    $ret .= $array_conclusionhc[$model->conclusion];
                                }
                                echo $ret;
                            ?>
                        </p>
                    </div>

                    <div class="col-lg-4 my-5">
                        <label class="control-label"><?= $model->getAttributeLabel('vigencia');?></label>
                        <p class="form-control bg-disabled ">
                            <?php
                                $ret = '&nbsp;';
                                if ($model->datavigencia){
                                    $ret .= $model->datavigencia->vigencia;
                                }
                                echo $ret;
                            ?>
                        </p>
                    </div>
                    <div class="col-lg-12 my-3">
                        <label class="control-label"><?= $model->getAttributeLabel('recomendaciones');?></label>
                        <p class="form-control bg-disabled ">
                            <?php
                                $ret = '&nbsp;';
                                $ret .= $model->recomendaciones;
                                echo $ret;
                            ?>
                        </p>
                    </div>
                </div>
                <?php
                $url = Url::to(['firma']);
                ?>

                <div class="row my-3">
                    <div class="col-lg-3 offset-lg-3">

                    </div>
                    <div class="col-lg-4">
                        <?php if(isset($model->firma_ruta)):?>
                        <img src="<?php  echo '/resources/Empresas/'.$model->id_empresa.'/HC/'.$model->id.'/'.$model->firma_ruta;?>"
                            class="img-fluid img-responsive" width="150px" height="auto"
                            style='height:100px; width:auto;opacity: 0.6;' />
                        <?php endif;?>
                        <label class="control-label" for="">Firma Actual</label>
                    </div>
                    <div class="col-lg-12 text-center">

                    </div>
                    <div class="col-lg-12 text-center">
                        <div for="" class="border-top">Firma Trabajador</div>
                    </div>
                </div>
                <div class="row" style="display:none;">

                </div>



                <div class="row mt-5">
                    <div class="col-lg-4 d-grid">

                    </div>
                </div>



                <?php
                $ver_programassalud = 'none';
                if(Yii::$app->user->can('programasalud_hc')){
                    $ver_programassalud = 'block';
                }
                ?>

                <div class="container-fluid my-3 bg-light p-4 mt-5 borderlcolor3 border-top"
                    style="display:<?php echo $ver_programassalud?>;">
                    <div class="row m-0 p-0">
                        <div class="col-lg-4 title2 boxtitle p-1 rounded-3 color3 my-3">
                            <label class="">
                                <span class="mx-2"><?php echo  $iconclip;?></span>
                                Programas de Salud
                            </label>
                        </div>
                    </div>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th class="control-label font500" width="5%">#</th>
                                <th class="control-label font500" width="55%">Programa</th>
                                <th class="control-label font500" width="40%">Conclusión</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if($model->trabajador && $model->trabajador->programas){
                                foreach($model->trabajador->programas as $key=>$programa){
                                    $retprograma = '';
                                    $retconclusion = '';

                                    if($programa->programa){
                                        $retprograma = $programa->programa->nombre;
                                    }

                                    if($programa->conclusion == 1){
                                        $retconclusion = 'CONTROLADO';
                                    } else if ($programa->conclusion == 2){
                                        $retconclusion = 'DESCONTROLADO';
                                    }
                                    echo '<tr>
                                        <td class="t5 " width="5%">'.($key+1).'</td>
                                        <td class="t5 " width="55%">'.$retprograma.'</td>
                                        <td class="t5 color3" width="40%">'.$retconclusion.'</td>
                                        </tr>';
                                
                                }
                            }       
                            ?>
                        </tbody>
                    </table>
                    
                </div>

                <?php ActiveForm::end(); ?>

            </div>


        </div>
    </div>